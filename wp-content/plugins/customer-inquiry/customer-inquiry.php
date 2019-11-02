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
        // add_action( 'wp_ajax_store_admin_data', array( $this, 'storeAdminData' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'addAdminScripts' ) );
        add_action( 'wp_ajax_nopriv_getInquiryContact', 'getInquiryContact');
        add_action( 'wp_ajax_getInquiryContact', array($this, 'getInquiryContact') );
        // add_action( 'wp_ajax_nopriv_getPost', 'getPost');
        // add_action( 'wp_ajax_getPost', array($this, 'getPost') );
        // add_action( 'wp_ajax_nopriv_storeReaArticleBlock', 'storeReaArticleBlock');
        // add_action( 'wp_ajax_storeReaArticleBlock', array($this, 'storeReaArticleBlock') );
        // add_action( 'wp_ajax_nopriv_deleteReaArticleBlock', 'deleteReaArticleBlock');
        // add_action( 'wp_ajax_deleteReaArticleBlock', array($this, 'deleteReaArticleBlock') );
        // add_action( 'wp_ajax_nopriv_sortReaArticleBlock', 'sortReaArticleBlock');
        // add_action( 'wp_ajax_sortReaArticleBlock', array($this, 'sortReaArticleBlock') );
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

    function getPost() {
        global $wpdb;
        $keyword = $_GET['search'];
        $meta_key = 'rea_article_block';
        $exist_posts = $wpdb->get_results( "SELECT post_id FROM {$wpdb->prefix}postmeta WHERE meta_key='{$meta_key}'");
        $data_post=[];
        foreach($exist_posts as $kPostMeta=>$vPostMeta) {
            $data_post[] = $vPostMeta->post_id;
        }
        $implode_post = implode(',', $data_post);
        $exclude_query = $implode_post ? ' AND ID NOT IN (' . $implode_post . ') ' : '';
        $query = $wpdb->get_results( "SELECT ID,post_title FROM {$wpdb->prefix}posts WHERE post_title LIKE '%{$keyword}%' AND post_status='publish' AND post_type='post' {$exclude_query} LIMIT 0, 20");
        $result = [];
        foreach ($query as $k=>$v) {
            $result[] = [
            'id' => $v->ID,
            'text' => $v->post_title
            ];
        }
        echo json_encode($result);
        die;
    }

    function storeReaArticleBlock() {
        global $wpdb;

        $post = $_POST['post_select'];
        $meta_key = 'rea_article_block';

        if ($post) {
            $last_position = $wpdb->get_row("SELECT MAX(meta_value) as last_position FROM {$wpdb->prefix}postmeta WHERE meta_key='{$meta_key}'");
            $insert = $wpdb->insert($wpdb->prefix.'postmeta', 
                array(
                    'post_id' => $post,
                    'meta_key'  => $meta_key,
                    'meta_value' => $last_position->last_position + 1
                )
            );
            echo json_encode(['status'=>'success']);
        }
        die;
    }

    function deleteReaArticleBlock() {
        global $wpdb;

        $post = $_POST['id'];
        $meta_key = 'rea_article_block';
        $delete = $wpdb->delete( $wpdb->prefix . 'postmeta', array( 'post_id' => $post, 'meta_key' => $meta_key ) );
        if ($delete) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error']);
        }
        die;
    }

    function sortReaArticleBlock() {
        global $wpdb;

        $order = $_POST['order'];
        $temp_data = str_replace("\\", "",$order);
        $post_order = json_decode($temp_data, true);
        $meta_key = 'rea_article_block';
        foreach($post_order as $k=>$v) {
            $old_data = $v['oldData'];
            $new_position = $v['newPosition'];
            $post = $wpdb->get_row("SELECT post_id FROM {$wpdb->prefix}postmeta WHERE post_id={$old_data}");
            $update = $wpdb->update($wpdb->prefix.'postmeta', 
                array(
                    'meta_value' => $new_position
                ),
                array(
                    'post_id' => $post->post_id,
                    'meta_key' => $meta_key
                )
            );
        }
        echo json_encode(['status' => 'success']);
        die;
    }
}

new CustomerInquiry();
?>