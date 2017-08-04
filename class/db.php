<?php
class Hrm_Db
{
    public function __construct()
    {
        $this->employer_role();
        $this->organization();
        $this->job();
        $this->qualification();
        $this->configure();
        $this->leave_summary();
        $this->personal();
        $this->project_info();
        $this->pim();
        $this->time();
        $this->employer();
        $this->worker_evaluation();
        $this->client_partial_payment();
    }

    public function client_partial_payment()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'hrm_client_partial_payment';
        $sql = "CREATE TABLE IF NOT EXISTS {$table_name} (
          `id` bigint(20) NOT NULL AUTO_INCREMENT,
          `client_id` bigint(20) NOT NULL,
          `description` text NOT NULL,
          `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
          `currency` VARCHAR( 10 ) NOT NULL,
          `project_id` BIGINT NOT NULL,
          `amount` BIGINT NOT NULL,
          PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);

        $table_option['table_name'] = 'hrm_client_partial_payment';
        $table_option['table_option'] = array(
            'client_id'   => 'client_id',
            'description' => 'description',
            'date'        => 'date',
            'currency'    => 'currency',
            'project_id'  => 'project_id',
            'amount'      => 'amount',
        );

        $table_option_name = 'hrm_client_partial_payment';

        hrm_Settings::getInstance()->update_table_option($table_option_name, $table_option);
    }

    public function worker_evaluation()
    {
        $table_option['table_option'] = array(
            'from_date' => 'from_date',
            'to_date'   => 'to_date',
        );
        $table_option_name = 'hrm_rating_record';

        hrm_Settings::getInstance()->update_table_option($table_option_name, $table_option);
    }

    public function employer()
    {
        $table_option['table_option'] = array(
            'employer' => 'employer',
        );
        $table_option_name = 'hrm_user_search';

        hrm_Settings::getInstance()->update_table_option($table_option_name, $table_option);
    }

    public function time()
    {
        $this->punch_in_out();
        $this->employer_employee_records();
    }

    public function employer_employee_records()
    {
        $table_option['table_option'] = array(
            'from_date' => 'from_date',
            'to_date'   => 'to_date',
            'user_id'   => 'user_id'
        );
        $table_option_name = 'hrm_attendance_record_both';

        hrm_Settings::getInstance()->update_table_option($table_option_name, $table_option);
    }

    public function punch_in_out()
    {
        $table_option['table_option'] = array(
            'date'       => 'date',
        );
        $table_option_name = 'hrm_attendance';

        hrm_Settings::getInstance()->update_table_option($table_option_name, $table_option);
    }

    public function pim()
    {
        $this->employ_list();
    }

    public function employ_list()
    {
        $table_option['table_option'] = array(
            'user'       => 'user',
            'first_name' => 'first_name',
            'last_name'  => 'last_name',
            'status'     => 'status',
            'mobile'     => 'mobile',
        );
        $table_option_name = 'hrm_employee';

        hrm_Settings::getInstance()->update_table_option($table_option_name, $table_option);
    }

    public function project_info()
    {
        $this->project_user_role();
        $this->project_search_table_option();
    }

    public function project_search_table_option()
    {
        $table_option['table_option'] = array(
            'title' => 'title',
        );
        $table_option_name = 'hrm_projects';

        hrm_Settings::getInstance()->update_table_option($table_option_name, $table_option);
    }

    public function project_user_role()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'hrm_user_role';
        $sql = "CREATE TABLE IF NOT EXISTS {$table_name} (
          `ID` bigint(20) NOT NULL AUTO_INCREMENT,
          `project_id` bigint(20) NOT NULL,
          `user_id` bigint(20) NOT NULL,
          `role` varchar(20) NOT NULL,
          PRIMARY KEY (`ID`)
        ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }

    public function personal()
    {
        $this->salary();
        $this->work_exp();
        $this->personal_education();
        $this->personal_skill();
        $this->personal_language();
        $this->personal_info();
        $this->personal_job();
    }

    public function personal_job()
    {
        $user_meta_key = array(
            '_job_title'        => 'job_title',
            '_job_category'     => 'job_category',
            '_location'         => 'location',
            '_contract_start'   => 'contract_start',
            '_contract_end'     => 'contract_end',
            '_contract_details' => 'contract_details',
        );

        $table_option_name = 'hrm_personal_job';

        hrm_settings::getInstance()->update_table_option($table_option_name, $user_meta_key);
    }

    public function personal_info()
    {
        $user_meta_key = array(
            '_gender'         => 'gender',
            '_marital_status' => 'marital_status',
            '_national_code'  => 'national_code',
            '_birthday'       => 'birthday',
            '_street1'        => 'street1',
            '_street2'        => 'street2',
            '_city_code'      => 'city_code',
            '_state'          => 'state',
            '_zip'            => 'zip',
            '_country_code'   => 'country_code',
            '_work_mobile'    => 'work_mobile',
            '_work_email'     => 'work_email'
        );

        $table_option_name = 'hrm_personal_info';

        hrm_settings::getInstance()->update_table_option($table_option_name, $user_meta_key);
    }

    public function personal_language()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'hrm_personal_language';
        $sql = "CREATE TABLE IF NOT EXISTS {$table_name} (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `emp_id` int(7) NOT NULL,
          `language_id` int(11) NOT NULL,
          `fluency` text NOT NULL,
          `competency` text NOT NULL,
          `comments` varchar(100) DEFAULT NULL,
          KEY `lang_id` (`language_id`),
          KEY `id` (`id`)
        ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);

        $table_option['table_name'] = 'hrm_personal_language';
        $table_option['table_format'] = array( '%d', '%d', '%s', '%s', '%s' );
        $table_option['table_option'] = array(
            'emp_id'      => 'emp_id',
            'language_id' => 'language_id',
            'fluency'     => 'fluency',
            'competency'  => 'competency',
            'comments'    => 'comments',
        );
        $table_option_name = 'hrm_personal_language';

        hrm_Settings::getInstance()->update_table_option($table_option_name, $table_option);
    }

    public function personal_skill()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'hrm_personal_skill';
        $sql = "CREATE TABLE IF NOT EXISTS {$table_name} (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `emp_id` int(7) NOT NULL DEFAULT '0',
          `skill_id` int(11) NOT NULL,
          `years_of_exp` decimal(2,0) DEFAULT NULL,
          `comments` varchar(100) NOT NULL DEFAULT '',
          PRIMARY KEY (`id`),
          KEY `emp_number` (`emp_id`),
          KEY `skill_id` (`skill_id`)
        ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);

        $table_option['table_name'] = 'hrm_personal_skill';
        $table_option['table_format'] = array( '%d', '%d', '%s', '%s' );
        $table_option['table_option'] = array(
            'emp_id'       => 'emp_id',
            'skill_id'     => 'skill_id',
            'years_of_exp' => 'years_of_exp',
            'comments'     => 'comments',
        );
        $table_option_name = 'hrm_personal_skill';

        hrm_Settings::getInstance()->update_table_option($table_option_name, $table_option);
    }

    public function personal_education()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'hrm_personal_education';
        $sql = "CREATE TABLE IF NOT EXISTS {$table_name} (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `emp_id` int(11) NOT NULL,
          `education_id` int(11) NOT NULL,
          `institute` varchar(100) DEFAULT NULL,
          `major` varchar(100) DEFAULT NULL,
          `year` timestamp NULL DEFAULT NULL,
          `score` varchar(25) DEFAULT NULL,
          `start_date` timestamp NULL DEFAULT NULL,
          `end_date` timestamp NULL DEFAULT NULL,
          PRIMARY KEY (`id`),
          KEY `emp_number` (`emp_id`),
          KEY `education_id` (`education_id`)
        ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);

        $table_option['table_name'] = 'hrm_personal_education';
        $table_option['table_format'] = array( '%d', '%d', '%s', '%s', '%s', '%f', '%s', '%s' );
        $table_option['table_option'] = array(
            'emp_id'       => 'emp_id',
            'education_id' => 'education_id',
            'institute'    => 'institute',
            'major'        => 'major',
            'year'         => 'year',
            'score'        => 'score',
            'start_date'   => 'start_date',
            'end_date'     => 'end_date',
        );
        $table_option_name = 'hrm_personal_education';

        hrm_Settings::getInstance()->update_table_option($table_option_name, $table_option);
    }

    public function work_exp()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'hrm_work_experience';
        $sql = "CREATE TABLE IF NOT EXISTS {$table_name} (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `emp_number` varchar(7) NOT NULL DEFAULT '0',
            `eexp_company` varchar(100) DEFAULT NULL,
            `eexp_jobtit` varchar(120) DEFAULT NULL,
            `eexp_from_date` varchar(15) DEFAULT NULL,
            `eexp_to_date` varchar(15) DEFAULT NULL,
            `eexp_comments` varchar(200) DEFAULT NULL,
        PRIMARY KEY (`id`)
        ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);

        $table_option['table_name'] = 'hrm_work_experience';
        $table_option['table_format'] = array( '%s', '%s', '%s', '%s', '%s', '%s');
        $table_option['table_option'] = array(
            'emp_number'     => 'emp_id',
            'eexp_company'   => 'company_name',
            'eexp_jobtit'    => 'job_title',
            'eexp_from_date' => 'form',
            'eexp_to_date'   => 'to',
            'eexp_comments'  => 'description',
        );
        $table_option_name = 'hrm_work_experience';

        hrm_Settings::getInstance()->update_table_option($table_option_name, $table_option);
    }

    public function salary()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'hrm_salary';
        $sql = "CREATE TABLE IF NOT EXISTS {$table_name} (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `emp_id` int(11) NOT NULL,
          `pay_grade` varchar(50) NOT NULL,
          `component` varchar(100) NOT NULL,
          `frequency` int(11) NOT NULL,
          `currency` varchar(10) NOT NULL,
          `amount` decimal(4,2) NOT NULL,
          `comments` text NOT NULL,
          `direct_deposit` varchar(3) NOT NULL,
          `account_number` int(11) NOT NULL,
          `account_type` int(11) NOT NULL,
          `specify` varchar(200) NOT NULL,
          `routing` int(11) NOT NULL,
          `dipo_amount` int(11) NOT NULL,
           `billing_date` TIMESTAMP NOT NULL,
          PRIMARY KEY (`id`)
        ) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);

        $table_option['table_name'] = 'hrm_salary';
        $table_option['table_format'] = array( '%d', '%s', '%s', '%d', '%s', '%d', '%s', '%s', '%s', '%d', '%d', '%d', '%s' );
        $table_option['table_option'] = array(
            'emp_id'         => 'emp_id',
            'pay_grade'      => 'pay_grade',
            'component'      => 'component',
            'frequency'      => 'frequency',
            'currency'       => 'currency',
            'amount'         => 'amount',
            'comments'       => 'comments',
            'direct_deposit' => 'direct_deposit',
            'account_number' => 'account_number',
            'account_type'   => 'account_type',
            'routing'        => 'routing',
            'dipo_amount'    => 'dipo_amount',
            'billing_date'   => 'billing_date'
        );
        $table_option_name = 'hrm_salary';

        hrm_Settings::getInstance()->update_table_option($table_option_name, $table_option);
    }

    public function leave_summary()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'hrm_leave';
        $sql = "CREATE TABLE IF NOT EXISTS {$table_name} (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `leave_status` smallint(6) DEFAULT NULL,
          `leave_comments` varchar(256) DEFAULT NULL,
          `leave_type_id` varchar(13) NOT NULL,
          `emp_id` int(7) NOT NULL,
          `start_time` timestamp NULL DEFAULT NULL,
          `end_time` timestamp NULL DEFAULT NULL,
          PRIMARY KEY (`id`)
        ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);

        $table_option['table_name'] = 'hrm_leave';
        $table_option['table_format'] = array( '%s', '%s', '%s', '%s', '%s', '%d' );
        $table_option['table_option'] = array(
            'start_time'     => 'from',
            'end_time'       => 'to',
            'leave_comments' => 'comment',
            'leave_type_id'  => 'type_id',
            'emp_id'         => 'emp_id',
            'leave_status'   => 'leave_status'
        );
        $table_option_name = 'hrm_leave';

        hrm_Settings::getInstance()->update_table_option($table_option_name, $table_option);
    }

    public function configure()
    {
        $this->leave_type();
        $this->work_week();
        $this->holiday();
    }

    public function work_week()
    {
        $option['field_dif'] = array( 'saturday', 'sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday' );
        update_option('hrm_work_week', $option);
    }

    public function holiday()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'hrm_holiday';
        $sql = "CREATE TABLE IF NOT EXISTS {$table_name} (
          `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
          `name` varchar(20) NOT NULL,
          `description` text,
          `from` timestamp NULL DEFAULT NULL,
          `to` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
          `length` varchar(10) NOT NULL,
          `index_holiday` text NOT NULL,
          PRIMARY KEY (`id`)
        ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);

        $table_option['table_name'] = 'hrm_holiday';
        $table_option['table_format'] = array( '%s', '%s', '%s', '%s', '%s' );
        $table_option['table_option'] = array(
            'name'        => 'name',
            'description' => 'description',
            'from'        => 'from',
            'to'          => 'to',
            'length'      => 'length',
        );
        $table_option_name = 'hrm_holiday';

        hrm_Settings::getInstance()->update_table_option($table_option_name, $table_option);
    }

    public function leave_type()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'hrm_leave_type';
        $sql = "CREATE TABLE IF NOT EXISTS {$table_name} (
          `id` bigint(13) NOT NULL AUTO_INCREMENT,
          `leave_type_name` varchar(50) DEFAULT NULL,
          `entitlement` smallint(6) DEFAULT NULL,
          `entitle_from` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
          `entitle_to` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
          PRIMARY KEY (`id`)
        ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);

        $table_option['table_name'] = 'hrm_leave_type';
        $table_option['table_format'] = array( '%s', '%d', '%s', '%s' );
        $table_option['table_option'] = array(
            'leave_type_name' => 'leave_type',
            'entitlement'     => 'entitlement',
            'entitle_from'    => 'entitle_from',
            'entitle_to'      => 'entitle_to'
        );
        $table_option_name = 'hrm_leave_type';
        hrm_Settings::getInstance()->update_table_option($table_option_name, $table_option);
    }

    public function qualification()
    {
        $this->skills();
        $this->education();
        $this->language();
    }

    public function language()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'hrm_language';
        $sql = "CREATE TABLE IF NOT EXISTS {$table_name} (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `name` varchar(120) DEFAULT NULL,
          PRIMARY KEY (`id`)
        ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);

        $table_option['table_name'] = 'hrm_language';
        $table_option['table_format'] = array( '%s' );
        $table_option['table_option'] = array(
            'name' => 'language',
        );
        $table_option_name = 'hrm_language';

        hrm_Settings::getInstance()->update_table_option($table_option_name, $table_option);
    }

    public function education()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'hrm_education';
        $sql = "CREATE TABLE IF NOT EXISTS {$table_name} (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `name` varchar(100) NOT NULL,
          PRIMARY KEY (`id`)
        ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);

        $table_option['table_name'] = 'hrm_education';
        $table_option['table_format'] = array( '%s');
        $table_option['table_option'] = array(
            'name' => 'education_name',
        );
        $table_option_name = 'hrm_qualification_education';

        hrm_Settings::getInstance()->update_table_option($table_option_name, $table_option);
    }

    public function skills()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'hrm_skill';
        $sql = "CREATE TABLE IF NOT EXISTS {$table_name} (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `name` varchar(120) DEFAULT NULL,
            `description` text,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);

        $table_option['table_name'] = 'hrm_skill';
        $table_option['table_format'] = array( '%s', '%s');
        $table_option['table_option'] = array(
            'name' => 'skill_name',
            'description' => 'skill_desc',
        );
        $table_option_name = 'hrm_qualification_skills';

        hrm_Settings::getInstance()->update_table_option($table_option_name, $table_option);
    }

    public function job()
    {
        $this->title();
        $this->category();
        $this->pay_grades();
    }

    public function category()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'hrm_job_category';
        $sql = "CREATE TABLE IF NOT EXISTS {$table_name} (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `name` varchar(60) DEFAULT NULL,
          `active` varchar(4) NOT NULL,
          PRIMARY KEY (`id`)
        ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);

        $table_option['table_name'] = 'hrm_job_category';
        $table_option['table_format'] = array( '%s', '%s' );
        $table_option['table_option'] = array(
            'name'   => 'job_category',
            'active' => 'active'
        );
        $table_option_name = 'hrm_job_category';

        hrm_Settings::getInstance()->update_table_option($table_option_name, $table_option);
    }

    public function pay_grades()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'hrm_pay_grade';
        $sql = "CREATE TABLE IF NOT EXISTS {$table_name} (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `name` varchar(50) NOT NULL,
          PRIMARY KEY (`id`)
        ) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);

        $table_option['table_name'] = 'hrm_pay_grade';
        $table_option['table_format'] = array( '%s' );
        $table_option['table_option'] = array(
            'name' => 'name',
        );
        $table_option_name = 'hrm_pay_grade';

        hrm_Settings::getInstance()->update_table_option($table_option_name, $table_option);
    }

    public function title()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'hrm_job_title';
        $sql = "CREATE TABLE IF NOT EXISTS {$table_name} (
          `id` int(13) NOT NULL AUTO_INCREMENT,
          `job_title` varchar(100) NOT NULL,
          `job_description` varchar(400) DEFAULT NULL,
          `note` varchar(400) DEFAULT NULL,
          `is_deleted` tinyint(1) DEFAULT '0',
          PRIMARY KEY (`id`)
        ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);

        $table_option['table_name'] = 'hrm_job_title';
        $table_option['table_format'] = array( '%s', '%s', '%s' );
        $table_option['table_option'] = array(
            'job_title'       => 'job_title',
            'job_description' => 'job_description',
            'note'            => 'note',
        );
        $table_option_name = 'hrm_job_title_option';

        hrm_Settings::getInstance()->update_table_option($table_option_name, $table_option);
    }

    public function employer_role()
    {
        $role_name            = 'hrm_employee';
        $display_name         = __('HRM employee', 'hrm');
        $capabilities['read'] = true;
        add_role($role_name, $display_name, $capabilities);
    }

    public function organization()
    {
        $this->generl_info();
        $this->location();
        $this->notice();
    }

    public function notice()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'hrm_notice';

        $sql = "CREATE TABLE IF NOT EXISTS {$table_name} (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `title` varchar(250) NOT NULL,
          `description` longtext NOT NULL,
          `user_id` int(11) NOT NULL,
          `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
          PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);

        $table_option['table_name'] = 'hrm_notice';
        $table_option['table_format'] = array( '%s', '%s', '%d', '%s' );
        $table_option['table_option'] = array(
            'title'       => 'title',
            'description' => 'description',
            'user_id'     => 'user_id',
            'date'        => 'date'
        );
        $table_option_name = 'hrm_notice';

        hrm_Settings::getInstance()->update_table_option($table_option_name, $table_option);
    }

    public function location()
    {
        global $wpdb;

        $table_name = $wpdb->prefix . 'hrm_location';
        $sql = "CREATE TABLE IF NOT EXISTS {$table_name} (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `name` varchar(110) NOT NULL,
          `country_code` varchar(3) NOT NULL,
          `province` varchar(60) DEFAULT NULL,
          `city` varchar(60) DEFAULT NULL,
          `address` varchar(255) DEFAULT NULL,
          `zip_code` varchar(35) DEFAULT NULL,
          `phone` varchar(35) DEFAULT NULL,
          `fax` varchar(35) DEFAULT NULL,
          `notes` varchar(255) DEFAULT NULL,
          PRIMARY KEY (`id`),
          KEY `country_code` (`country_code`)
        ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);

        $table_option['table_name']   = 'hrm_location';
        $table_option['table_format'] = array( '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s' );
        $table_option['table_option'] = array(
            'name'         => 'name',
            'country_code' => 'country',
            'province'     => 'province',
            'city'         => 'city',
            'address'      => 'address',
            'zip_code'     => 'zipcode',
            'phone'        => 'phone',
            'fax'          => 'fax',
            'notes'        => 'notes'
        );
        $table_option_name = 'hrm_location_option';

        hrm_Settings::getInstance()->update_table_option($table_option_name, $table_option);
    }

    public function generl_info()
    {
        $option['field_dif'] = array(
            'organization_name',
            'tax_id',
            'registration_number',
            'phone',
            'fax',
            'addres_street_1',
            'address_street_2',
            'city',
            'state_province',
            'zip',
            'country',
            'note'
        );
        update_option('hrm_general_info', $option);
    }
}
