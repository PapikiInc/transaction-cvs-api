<?php
/** 
 * @package transaction-cvs-api
 * */

 class TransactionCvsDeactivate
 {
    public static function deactivate(){
        //delete the transaction DB
     global $wpdb;
     $wpdb->query( "DROP TABLE `wp_mock_data`");
    }
 }