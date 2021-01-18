<?php
/*
 * Plugin Name: Vistior Tracker
 * Plugin URI: http://www.almasin.net/visitortracker
 * Description: Tracks users ip, browser info, the date and time they visited.
 * Version: 0.0.1
 * Author: Joshua Almasin
 * Author URI: https://www.almasin.net
 * License: GPL2
 */


add_shortcode('vtracker', 'saveVisitors');

function vtracker_activate()
{
    global $wpdb;

    $charset_collate = $wpdb->get_charset_collate();

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

    $table_name = $wpdb->prefix. 'v_tracker';

    if( $wpdb->get_var( $wpdb->prepare( "SHOW TABLES LIKE '$table_name'") != $table_name))
    {
        $table_name = $wpdb->prefix . 'v_tracker';
        $sql = "CREATE TABLE $table_name (
            v_id INTEGER NOT NULL AUTO_INCREMENT,
             v_time VARCHAR(255)NOT NULL,
             v_ip INT NOT NULL,
             v_browser VARCHAR(255) NOT NULL,
             PRIMARY KEY (v_id)
             ) $charset_collate;";
        dbDelta( $sql );

    }
}

function saveVisitors()
{
    global $wpdb;
    $table_name = $wpdb->prefix. 'v_tracker';

    if($_SERVER['REMOTE_ADDR'])
    {
        $v_time = date('m-d-Y H:i');
        $v_ip = $_SERVER['REMOTE_ADDR'];
        $v_browser = $_SERVER['HTTP_USER_AGENT'];

        $wpdb->insert($table_name, array(
            'v_time' => $v_time,
            'v_ip' => $v_ip,
            'v_browser' => $v_browser
        ));

    }
}


register_activation_hook( __FILE__, 'vtracker_activate' );



?>