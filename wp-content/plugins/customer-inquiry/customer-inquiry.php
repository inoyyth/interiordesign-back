<?php
namespace CustomPlugin;
/**
 * Plugin Name:       Customer Inquiry
 * Description:       Customer inquiry record
 * Version:           1.0.0
 * Author:            Inoy Yth
 * Author URI:        https://rumah123.com
 * Text Domain:       Inoy Cute
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * GitHub Plugin URI: https://github.com/2Fwebd/feedier-wordpress
 */
 
/*
 * Main class
 */
/**
 * Class Brands
 *
 * This class creates the option page and add the web app script
 */
if( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class CustomerInquiry 
{
    /**
     * Reabrands constructor.
     *
     * The main plugin actions registered for WordPress
     */
    public function __construct()
    {
        // Admin page calls:
        add_action( 'admin_menu', array( $this, 'addAdminMenu' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'addAdminScripts' ) );
        add_action( 'wp_ajax_nopriv_getInquiryContact', 'getInquiryContact');
        add_action( 'wp_ajax_getInquiryContact', array($this, 'getInquiryContact') );
    }
    
    /**
     * Adds the Brands label to the WordPress Admin Sidebar Menu
    */
    public function addAdminMenu()
    {
        add_menu_page(
        __( 'Customer Inquiry', 'customer_inquiry' ),
        __( 'Customer Inquiry', 'customer_inquiry' ),
        'manage_options',
        'customer_inquiry',
        array($this, 'adminLayout'),
        'dashicons-media-document',
        30
        );
    }

    /**
     * Outputs the Admin Dashboard layout containing the form with all its options
     *
     * @return void
     */
    public function adminLayout()
    {
        include(plugin_dir_path( __FILE__ ) . 'page/main.php');
    }

    function addAdminScripts($hook) {
        if ( 'toplevel_page_customer_inquiry' == $hook ) {
            //style
            wp_enqueue_style( 'bootstrap',  plugin_dir_url( __FILE__ ) . 'assets/boostrap/css/bootstrap.min.css' );
            wp_enqueue_style( 'datatables',  plugin_dir_url( __FILE__ ) . 'assets/DataTables/datatables.min.css' );
            //script
            wp_enqueue_script( 'boostrap',  plugin_dir_url( __FILE__ ) . 'assets/boostrap/js/bootstrap.min.js');
            wp_enqueue_script( 'datatables',  plugin_dir_url( __FILE__ ) . 'assets/DataTables/datatables.min.js' );
        }
    }

    function getInquiryContact() {
        global $wpdb;
        $search = $_GET['search']['value'];
        $start = $_GET['start'];
        $length = $_GET['length'];
        $get_post = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}customer_inquiry WHERE name LIKE '%{$search}%' OR email LIKE '%{$search}%' ORDER BY datetime desc LIMIT {$start}, {$length}", OBJECT );
        $get_post_total = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}customer_inquiry WHERE name LIKE '%{$search}%' OR email LIKE '%{$search}%'", OBJECT );
        echo json_encode(
            array(
                "recordsTotal" => count($get_post_total),
                "recordsFiltered" => count($get_post_total),
                "data" => $get_post
            )
        );
        die;
    }
}

new CustomerInquiry();
?>