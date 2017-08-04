<?php
$header_path = dirname(__FILE__) . '/header.php';
$header_path = apply_filters('hrm_header_path', $header_path, 'time');

if (file_exists($header_path)) {
    require_once $header_path;
}
if (! hrm_user_can_access($page, $tab, $subtab, 'view')) {
    printf('<h1>%s</h1>', __('You do no have permission to access this page', 'cpm'));
    return;
}
?>
<div class="hrm-update-notification"></div>
<?php

$users = get_users();
$user_id = isset($_POST['user_id']) ? $_POST['user_id'] : false;
if (!$user_id) {
    $user_id = isset($post['user_id_js']) ? $post['user_id_js'] : false;
}

$option_value = array();
foreach ($users as $user) {
    $option_value[$user->ID] = $user->display_name;
}

if ((isset($_GET['action_search']) && $_GET['action_search'])) {
    $search_status = true;
    $search_post = get_user_meta(get_current_user_id(), '_hrm_search_data', true);
} elseif (isset($_POST['action_search'])) {
    $search_status = true;
    $search_post = $_POST;
} elseif (isset($_POST['search_status']) && $_POST['search_status']) {
    $search_status = true;
    $search_post = get_user_meta(get_current_user_id(), '_hrm_search_data', true);
} else {
    $search_status = false;
}

$search['user_id'] = array(
    'label'    => __('Employer/Employee Name', 'hrm'),
    'class'    => 'hrm-chosen',
    'type'     => 'select',
    'option'   => $option_value,
    'selected' => $search_status ? $search_post['user_id'] : '',
    'desc'     => __('Type employer/employee name', 'hrm'),
    'extra' => array(
        'data-placeholder' => __("Choose employer/employee name", 'hrm'),
    ),
);

$search['from_date'] = array(
    'label' => __('From Date', 'hrm'),
    'class' => 'hrm-datepicker-from',
    'type'  => 'text',
    'desc'  => __('Choose Date', 'hrm'),
    'value' => $search_status ? $search_post['from_date'] : '',
);

$search['to_date'] = array(
    'label' => __('To Date', 'hrm'),
    'class' => 'hrm-datepicker-to',
    'type'  => 'text',
    'desc'  => __('Choose Date', 'hrm'),
    'value' => $search_status ? $search_post['to_date'] : '',
);

$search['visibility']   = $search_status ? true : true;
$search['action']       = 'hrm_search';
$search['table_option'] = 'hrm_attendance_record_both';
echo hrm_Settings::getInstance()->get_serarch_form($search, __('Attendance Records for Employer/Employee', 'hrm'));
?>

<div id="hrm-attendance" class="hrm-time-attendance"></div>

<?php

$pagenum     = hrm_pagenum();
$limit       = hrm_result_limit();

if ($search_status) {
    $query            = Hrm_Time::getInstance()->search_punch_in_out_recored($search_post, $limit, $pagenum);
    $posts            = $query->posts;
    $total_pagination = $query->found_posts;
    update_user_meta(
        get_current_user_id(),
        '_hrm_search_data',
            array( 'user_id' => $search_post['user_id'], 'from_date' => $search_post['from_date'], 'to_date' => $search_post['to_date'] )
    );
} else {
    update_user_meta(get_current_user_id(), '_hrm_search_data', false);
    $posts            = array();
    $total_pagination = 0;
}


$add_permission    = hrm_user_can_access($page, $tab, $subtab, 'add') ? true : false;
$delete_permission = hrm_user_can_access($page, $tab, $subtab, 'delete') ? true : false;

$total_duration = 0;
foreach ($posts as $key => $post) {
    if ($delete_permission) {
        $del_checkbox = '<input class="hrm-single-checked" name="hrm_check['.$post->ID.']" value="" type="checkbox">';
        $delete_text  = '<a href="#" class="hrm-delete" data-id='.$post->ID.'>'.__('Delete', 'hrm').'</a>';
        $td_attr[][0] = 'class="hrm-table-checkbox"';
    } else {
        $del_checkbox = '';
        $delete_text  = '';
    }

    if ($add_permission) {
        $name_id = '<div class="hrm-title-wrap"><a href="#" class="hrm-time-editable hrm-title"  data-post_id='.$post->ID.'>'.hrm_get_punch_in_time($post->post_date).'</a>
        <div class="hrm-title-action"><a href="#" class="hrm-time-editable hrm-edit"  data-post_id='.$post->ID.'>'.__('Edit', 'hrm').'</a>'
        .$delete_text. '</div></div>';
    } else {
        $name_id = hrm_get_punch_in_time($post->post_date);
    }


    /*
        if ( $add_permission ) {
            $name_id = '<a href="#" class="hrm-time-editable"  data-post_id='.$post->ID.'>'.hrm_get_punch_in_time($post->post_date).'<a>';
        } else {
            $name_id = hrm_get_punch_in_time($post->post_date);
        }
    
        if ( $delete_permission ) {
            $del_checkbox = '<input name="hrm_check['.$post->ID.']"$post="" type="checkbox">';
        } else {
            $del_checkbox = '';
        }*/

    $punch_out_time = get_post_meta($post->ID, '_puch_out_time', true);
    $puch_out_note = get_post_meta($post->ID, '_puch_out_note', true);

    $punch_in_ip = get_post_meta($post->ID, '_punch_in_ip', true);
    $punch_out_ip = get_post_meta($post->ID, '_punch_out_ip', true);

    if (!empty($punch_out_time)) {
        $total_duration = $total_duration + ($punch_out_time - strtotime($post->post_date));
        $punch_out      = date("Y-m-d H:i:s", $punch_out_time);
        $punch_out      = new DateTime($punch_out);
        $punch_in       = new DateTime($post->post_date);
        $interval       = date_diff($punch_out, $punch_in);
        $duration       = $interval->format('%H:%I:%S');
    }

    if ($delete_permission) {
        $body[] = array(
            $del_checkbox,
            $name_id,
        $punch_in_ip,
            $post->post_content,
            !empty($punch_out_time) ? hrm_get_punch_in_time($punch_out_time, false) : '',
        $punch_out_ip,
            $puch_out_note,
            isset($duration) ? $duration : '',
        );
    } else {
        $body[] = array(
            $name_id,
            $post->post_content,
            !empty($punch_out_time) ? hrm_get_punch_in_time($punch_out_time, false) : '',
            $puch_out_note,
            isset($duration) ? $duration : '',
        );
    }
}
$total      = hrm_second_to_time($total_duration);
$total_time = $total['hour'] .':'. $total['minute'] .':'. $total['second'];


if ($delete_permission) {
    $body[] = array(
        '',
        '',
        '',
        '',
        '<strong>' . __('Total', 'hrm') . '</strong>',
        $total_time
    );
} else {
    $body[] = array(
        '',
        '',
        '',
        '<strong>' . __('Total', 'hrm') . '</strong>',
        $total_time
    );
}

$table = array();

if ($delete_permission) {
    $table['head'] = array(
        '<input class="hrm-all-checked" type="checkbox">',
        __('Punch In', 'hrm'),
    __('Punch In IP', 'hrm'),
        __('Punch In Note', 'hrm'),
        __('Punch Out', 'hrm'),
    __('Punch Out IP', 'hrm'),
        __('Punch Out Note', 'hrm'),
        __('Duration (Hours)', 'hrm'),
    );
} else {
    $table['head'] = array(
        __('Punch In', 'hrm'),
        __('Punch In Note', 'hrm'),
        __('Punch Out', 'hrm'),
        __('Punch Out Note', 'hrm'),
        __('Duration (Hours)', 'hrm'),
    );
}

$table['body']        = isset($body) ? $body : array();
$table['td_attr']     = isset($td_attr) ? $td_attr : array();
$table['table_attr']  = array( 'class' => 'widefat' );
$table['table']       = '';
$table['action']      = 'hrm_post_delete';
$table['tab']         = $tab;
$table['subtab']      = $subtab;
$table['page']        = $page;
$table['search']      = __('Search Mode', 'hrm');
$table['data_table']  = false;
$table['search_mode'] = true;

$arg = array(
        'post_type'   => 'hrm_punch',
        'post_status' => 'publish',
        'author'      => $user_id,
        'meta_query' => array(
            array(
                'key'     => '_puch_in_status',
                'value'   => '1',
                'compear' => '='
            ),
        )
    );
$query = new WP_Query($arg);

$table['add_btn_name'] = (!isset($query->posts[0])) ? __('Punch In', 'hrm') : __('Punch Out', 'hrm');
if ($search_status) {
    echo Hrm_Settings::getInstance()->table($table);
}

echo Hrm_Settings::getInstance()->pagination($total_pagination, $limit, $pagenum);

$url       = hrm_Settings::getInstance()->get_current_page_url($page, $tab, $subtab);
$url       = add_query_arg(array( 'user_id' => $user_id ), $url);
$file_path = urlencode(__FILE__);
global $hrm_is_admin;
?>


<script type="text/javascript">
    jQuery(function($) {
        hrm_dataAttr = {
           add_form_generator_action : 'add_form',
           add_form_apppend_wrap : 'hrm-attendance',
           class_name : 'Hrm_Time',
           redirect : '<?php echo $url; ?>',
           function_name : 'punch_in_out_form',
           user_id_js : '<?php echo $user_id; ?>',
           page: '<?php echo $page; ?>',
           tab: '<?php echo $tab; ?>',
           subtab: '<?php echo $subtab; ?>',
           req_frm: '<?php echo $file_path; ?>',
           limit: '<?php echo $limit; ?>',
           search_status: '<?php echo $search_status; ?>',
           is_admin: '<?php echo $hrm_is_admin; ?>',
        };
    });
</script>
