<?php
/** 
 * @package transaction-cvs-api
 * */

 class TransactionCvsActivate
 {
    public static function activate(){
        //generate the transaction DB table to dump the cvs to
        global $wpdb;
        $wpdb->query( "CREATE TABLE `wp_mock_data` (
        `id` int(4) DEFAULT NULL,
        `title` varchar(9) DEFAULT NULL,
        `first_name` varchar(13) DEFAULT NULL,
        `last_name` varchar(15) DEFAULT NULL,
        `email` varchar(35) DEFAULT NULL,
        `email_valid` varchar(35) DEFAULT NULL,
        `tz` varchar(30) DEFAULT NULL,
        `date` varchar(255) DEFAULT NULL,
        `time` time DEFAULT NULL,
        `note` varchar(217) DEFAULT NULL,
        UNIQUE KEY `id` (`id`)
    )" );
    }
 }
// $customers_transaction = get_posts(array('post_type' => 'book', 'numberposts'=> -1));

// foreach( $books as $book){
//     wp_delete_post($book->ID,true);
// }