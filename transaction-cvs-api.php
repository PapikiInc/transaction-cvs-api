<?php
/** 
 * @package transaction-cvs-api
 * Plugin Name: Transaction Cvs Api 
 * Description:       This plugin creates a datable on the DB on activation(table gets deleted on deactivation), loads the table and provides two API.
 * Version:           1.0.0
 * Author:            Sone Thasi*/

 //to not trigger the plugin if a hacker tries to access directly
if( ! defined('ABSPATH')){
	die();
}

if (!class_exists('TransactionCvsApi')) {
    class TransactionCvsApi
    {
        // function __construct(){
        // }
        public function register()
        {
            add_action('admin_enqueue_scripts', array( $this, 'enqueue'));
            add_action('admin_menu', array( $this, 'add_admin_pages'));
        }

        public function add_admin_pages()
        {
            add_menu_page('Transaction Cvs', 'Transaction Details', 'manage_options', 'transactioncvsapi_plugin', array( $this, 'admin_index'), 'dashicons-format-aside', 110);
        }

        public function admin_index()
        {
            //requeire templates
            require_once plugin_dir_path(__FILE__) . 'templates/admin.php';
        }

        public function enqueue()
        {
            //enqueue scripts
            wp_enqueue_style('mypluginstyle', plugins_url('/assets/style.css', __FILE__));
            wp_enqueue_script('mypluginscript', plugins_url('/assets/myscripts.js', __FILE__));
        }
    }

    $transactioncvsapi = new TransactionCvsApi();
    $transactioncvsapi->register();
}

//activation
require_once plugin_dir_path(__FILE__) . 'includes/transaction-cvs-activate.php';
register_activation_hook(__FILE__, array('TransactionCvsActivate', 'activate'));


//deactivation
require_once plugin_dir_path(__FILE__) . 'includes/transaction-cvs-deactivate.php';
register_deactivation_hook(__FILE__, array('TransactionCvsDeactivate', 'deactivate'));


// APis
//contacts to be moved to contacts.php
function wl_contacts(){

    global $wpdb;
    //pagination
    $numberOfRec = 50;
    $query = "SELECT * FROM `wp_mock_data` limit {$numberOfRec}";
    $lists = $wpdb->get_results($query);

    $data = [];
    $i = 0;
    foreach($lists as $list) {
		$data[$i]['id'] = $list->id;
        $data[$i]['title'] = $list->title;
        $data[$i]['first_name'] = $list->first_name;
        $data[$i]['last_name'] = $list->last_name;
        $data[$i]['email'] = $list->email;
        $data[$i]['email_valid'] = $list->email_valid;
        $data[$i]['tz'] = $list->tz;
        $data[$i]['date'] = $list->date;
        $data[$i]['time'] = $list->time;
        $data[$i]['note'] = $list->note;
		$i++;
	}

    return $data;
}
//timezones to be moved to timezones.php
function wl_timezones(){

    global $wpdb;
    //pagination
    $numberOfRec = 50;
    $query = "SELECT * FROM `wp_mock_data` limit {$numberOfRec}";
    $lists = $wpdb->get_results($query);

    
    $data = [];
    $i = 0;
    foreach($lists as $list) {
        $data[$i]['id'] = $list->id;
        $data[$i]['title'] = $list->title;
        $data[$i]['first_name'] = $list->first_name;
        $data[$i]['last_name'] = $list->last_name;
        $data[$i]['email'] = $list->email;
        $data[$i]['email_valid'] = $list->email_valid;
        $data[$i]['tz'] = $list->tz;
        $data[$i]['Local_Time_zone'] = $list->tz;
        $data[$i]['date'] = $list->date;
        $data[$i]['time'] = $list->time;
        $data[$i]['note'] = $list->note;
		$i++;
	}

    return $data;
}
add_action('rest_api_init', function(){
    register_rest_route('wl/v1','contacts',[

        'methods' => 'GET',

        'callback' => 'wl_contacts',

    ]);
});

add_action('rest_api_init', function(){
    register_rest_route('wl/v1','timezones',[

        'methods' => 'GET',

        'callback' => 'wl_timezones',

    ]);
});