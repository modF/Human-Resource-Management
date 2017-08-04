<div class="hrm-update-notification"></div>

<div id="hrm-leave-type"></div>

<?php

$results = Hrm_Settings::getInstance()->hrm_query('hrm_leave_type');

$total = $results['total_row'];
unset($results['total_row']);

$add_permission    = hrm_user_can_access($page, $tab, $subtab, 'add') ? true : false;
$delete_permission = hrm_user_can_access($page, $tab, $subtab, 'delete') ? true : false;
$body              = array();
$td_attr           = array();

foreach ($results as $key => $value) {
    if ($delete_permission) {
        $del_checkbox = '<input class="hrm-single-checked" name="hrm_check['.$value->id.']" value="" type="checkbox">';
        $delete_text  = '<a href="#" class="hrm-delete" data-id='.$value->id.'>'.__('Delete', 'hrm').'</a>';
        $td_attr[][0] = 'class="hrm-table-checkbox"';
    } else {
        $del_checkbox = '';
        $delete_text  = '';
    }

    if ($add_permission) {
        $name_id = '<div class="hrm-title-wrap"><a href="#" class="hrm-editable hrm-title" data-table_option="hrm_leave_type" data-id='.$value->id.'>'.$value->leave_type_name.'</a>
        <div class="hrm-title-action"><a href="#" class="hrm-editable hrm-edit" data-table_option="hrm_leave_type" data-id='.$value->id.'>'.__('Edit', 'hrm').'</a>'
        .$delete_text. '</div></div>';
    } else {
        $name_id = $value->leave_type_name;
    }

    if ($delete_permission) {
        $body[] = array(
            $del_checkbox,
            $name_id,
            intval($value->entitlement),
            hrm_get_date2mysql($value->entitle_from),
            hrm_get_date2mysql($value->entitle_to)
        );
    } else {
        $body[] = array(
            $name_id,
            intval($value->entitlement),
            hrm_get_date2mysql($value->entitle_from),
            hrm_get_date2mysql($value->entitle_to)
        );
    }
}
$table = array();

if ($delete_permission) {
    $table['head'] = array(
        '<input class="hrm-all-checked" type="checkbox">',
        __('Leave Type', 'hrm'),
        __('Entitlement', 'hrm'),
        __('Entitle From', 'hrm'),
        __('Entitle To', 'hrm')
    );
} else {
    $table['head'] = array(
        __('Leave Type', 'hrm'),
        __('Entitlement', 'hrm'),
        __('Entitle From', 'hrm'),
        __('Entitle To', 'hrm')
    );
}

$table['body']       = isset($body) ? $body : array();
$table['td_attr']    = isset($td_attr) ? $td_attr : '';
$table['table_attr'] = array( 'class' => 'widefat' );
$table['table']      = 'hrm_leave_type';
$table['action']     = 'hrm_delete';
$table['tab']        = $tab;
$table['subtab']     = $subtab;
$table['page']       = $page;


echo hrm_Settings::getInstance()->table($table);
//table

$url       = hrm_Settings::getInstance()->get_current_page_url($page, $tab, $subtab);
$file_path = urlencode(__FILE__);
global $hrm_is_admin;
?>
<script type="text/javascript">
    jQuery(function($) {
        hrm_dataAttr = {
           add_form_generator_action : 'add_form',
           add_form_apppend_wrap : 'hrm-leave-type',
           class_name : 'hrm_Leave',
           redirect : '<?php echo $url; ?>',
           function_name : 'leave_type_form',
           page: '<?php echo $page; ?>',
           tab: '<?php echo $tab; ?>',
           subtab: '<?php echo $subtab; ?>',
           req_frm: '<?php echo $file_path; ?>',
           is_admin : '<?php echo $hrm_is_admin; ?>'
        };
    });
</script>