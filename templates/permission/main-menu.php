<?php
$header_path = dirname(__FILE__) . '/header.php';
$header_path = apply_filters('hrm_header_path', $header_path, 'file');

if (file_exists($header_path)) {
    require_once $header_path;
}

if (! hrm_user_can_access($page, $tab, $subtab, 'view')) {
    printf('<h1>%s</h1>', __('You do no have permission to access this page', 'cpm'));
    return;
}

?>
<div class="hrm-update-notification"></div>
<div id="hrm-admin-role"></div>
<?php
$jk = get_option('pro_test_role');

//hidden form

global $wp_roles;

if (!$wp_roles) {
    $wp_roles = new WP_Roles();
}

$role_names = $wp_roles->get_names();
$wp_built_in_role = array( 'administrator', 'editor', 'author', 'contributor', 'subscriber' );

$add_permission    = hrm_user_can_access($page, $tab, $subtab, 'add') ? true : false;
$delete_permission = hrm_user_can_access($page, $tab, $subtab, 'delete') ? true : false;
$current_user_role = hrm_current_user_role();
$body              = array();
$td_attr           = array();
foreach ($role_names as $name => $display_name) {
    if ($current_user_role == $name) {
        continue;
    }

    if (false) {
        $del_checkbox = '<input class="hrm-single-checked" name="hrm_check['.$name.']" value="" type="checkbox">';
        $delete_text  = '<a href="#" class="hrm-delete" data-id='.$name.'>'.__('Delete', 'hrm').'</a>';
        $td_attr[][0] = 'class="hrm-table-checkbox"';
    } else {
        $del_checkbox = '';
        $delete_text  = '';
    }

    if ($add_permission) {
        $name_id = '<div class="hrm-title-wrap"><a data-role_name="'.$name.'" data-action="get_role" data-display_name="'.$display_name.'" class="hrm-editable" href="#">'.$name.'</a>
		<div class="hrm-title-action"><a href="#" data-role_name="'.$name.'" data-action="get_role" data-display_name="'.$display_name.'" class="hrm-editable hrm-edit">'.__('Edit', 'hrm').'</a>'
        .$delete_text. '</div></div>';
    } else {
        $name_id = $name;
    }
    if ($delete_permission) {
        $body[] = array(
         // $del_checkbox,
          $name_id,
          $display_name
      );
    } else {
        $body[] = array(
          $name_id,
          $display_name
      );
    }
}
$table = array();
if ($delete_permission) {
    // $table['head']  = array( '<input class="hrm-all-checked" type="checkbox">', __( 'User Role', 'hrm' ), __( 'Display Name', 'hrm' ) );
    $table['head']  = array(  __('User Role', 'hrm'), __('Display Name', 'hrm') );
} else {
    $table['head']  = array( __('User Role', 'hrm'), __('Display Name', 'hrm') );
}


$table['body']          = isset($body) ? $body : array();


$table['td_attr']       = isset($td_attr) ? $td_attr : '';
$table['table_attr']    = array( 'class' => 'widefat' );

$table['action']        = 'role_delete';
$table['table_attr']    = array( 'class' => 'widefat' );
$table['tab']           = $tab;
$table['subtab']        = $subtab;
$table['page']          = $page;
$table['pagination']    = false;
$table['add_btn_name']  = false;
$table['delete_button'] = false;

echo Hrm_Settings::getInstance()->table($table);
$file_path = urlencode(__FILE__);
$url = Hrm_Settings::getInstance()->get_current_page_url($page, $tab, $subtab);
global $hrm_is_admin;
?>
<script type="text/javascript">
	jQuery(function($) {
		hrm_dataAttr = {
		   add_form_generator_action : 'add_form',
		   add_form_apppend_wrap : 'hrm-admin-role',
		   class_name : 'Hrm_Admin',
		   redirect : '<?php echo $url; ?>',
		   function_name : 'main_menu_access_permission_form',
		   page: '<?php echo $page; ?>',
		   tab: '<?php echo $tab; ?>',
		   subtab: '<?php echo $subtab; ?>',
		   req_frm: '<?php echo $file_path; ?>',
		   is_admin : '<?php echo $hrm_is_admin; ?>'
		};
	});
</script>

<?php 
if (apply_filters('hrm_permission_purchase_notice', true)) {
    ?>

	<a style="margin-top: 20px;" target="_blank" class="button button-primary" href="<?php echo HRM_PERMISSION_PURCHASE_URL; ?>">
	  <?php _e('To get this feature you have to purchase the HRM permission addon ', 'hrm'); ?>
	</a>
	<?php
}
