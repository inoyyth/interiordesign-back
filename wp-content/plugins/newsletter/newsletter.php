<?php
namespace CustomPlugin;
/**
 * Plugin Name:       Newsletter
 * Description:       Newsletter record
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

class Newsletter 
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
        add_action( 'wp_ajax_nopriv_getNewsletter', 'getNewsletter');
        add_action( 'wp_ajax_getNewsletter', array($this, 'getNewsletter') );
    }

    function activate() {
        //create you rule when activate the plugin
    }

    function deactivate() {
        //create you rule when deactivate the plugin
    }
    
    /**
     * Adds the Brands label to the WordPress Admin Sidebar Menu
    */
    public function addAdminMenu()
    {
        add_menu_page(
            __( 'Newsletter', 'newsletter' ),
            __( 'Newsletter', 'newsletter' ),
            'manage_options',
            'newsletter', 
            array($this, 'adminLayout'),
            'dashicons-format-aside',
            30
        );

        add_submenu_page(
            '', 
            'Add Newsletter', 
            'Add Newsletter', 
            'manage_options', 
            'add_newsletter', 
            array($this, 'addNewsletterPage')
        );

        add_submenu_page(
            '', 
            'Save Newsletter', 
            'Save Newsletter', 
            'manage_options', 
            'save_newsletter', 
            array($this, 'saveNewsletter')
        );

        add_submenu_page(
            '', 
            'Edit Newsletter', 
            'Edit Newsletter', 
            'manage_options', 
            'edit_newsletter', 
            array($this, 'editNewsletterPage')
        );

        add_submenu_page(
            '', 
            'Delete Newsletter', 
            'Delete Newsletter', 
            'manage_options', 
            'delete_newsletter', 
            array($this, 'deleteNewsletterPage')
        );

        add_submenu_page(
            '', 
            'Share Newsletter', 
            'Share Newsletter', 
            'manage_options', 
            'share_newsletter', 
            array($this, 'shareNewsletterPage')
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

    public function addNewsletterPage() {
        include(plugin_dir_path( __FILE__ ) . 'page/add.php');
    }

    public function editNewsletterPage() {
        include(plugin_dir_path( __FILE__ ) . 'page/edit.php');
    }

    function addAdminScripts($hook) {
        if ( 'toplevel_page_newsletter' == $hook || 
            'admin_page_add_newsletter' == $hook || 
            'admin_page_save_newsletter' == $hook ||
            'admin_page_edit_newsletter' == $hook ||
            'admin_page_share_newsletter' == $hook ||
            'admin_page_delete_newsletter' == $hook ) {
            //style
            wp_enqueue_style( 'bootstrap',  plugin_dir_url( __FILE__ ) . 'assets/boostrap/css/bootstrap.min.css' );
            wp_enqueue_style( 'datatables',  plugin_dir_url( __FILE__ ) . 'assets/DataTables/datatables.min.css' );
            wp_enqueue_style( 'ckeditor',  plugin_dir_url( __FILE__ ) . 'assets/ckeditor/contents.css' );
            //script
            wp_enqueue_script( 'boostrap',  plugin_dir_url( __FILE__ ) . 'assets/boostrap/js/bootstrap.min.js');
            wp_enqueue_script( 'datatables',  plugin_dir_url( __FILE__ ) . 'assets/DataTables/datatables.min.js' );
            wp_enqueue_script( 'ckeditor',  plugin_dir_url( __FILE__ ) . 'assets/ckeditor/ckeditor.js' );
        }
    }

    function getNewsletter() {
        global $wpdb;
        $search = $_GET['search']['value'];
        $start = $_GET['start'];
        $length = $_GET['length'];
        $get_post = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}newsletter WHERE (title LIKE '%{$search}%' OR message LIKE '%{$search}%') AND deleted_at IS NULL ORDER BY datetime desc LIMIT {$start}, {$length}", OBJECT );
        $get_post_total = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}newsletter WHERE (title LIKE '%{$search}%' OR message LIKE '%{$search}%') AND deleted_at IS NULL", OBJECT );
        echo json_encode(
            array(
                "recordsTotal" => count($get_post_total),
                "recordsFiltered" => count($get_post_total),
                "data" => $get_post
            )
        );
        die;
    }

    function saveNewsletter() {
        global $wpdb;

        $id = isset($_POST['id']) ? $_POST['id'] : null;
        $title = $_POST['title'];
        $message = $_POST['message'];
        try{
            if ($id == null) {
                $query = $wpdb->insert($wpdb->prefix.'newsletter', 
                    array(
                        'title'=>$title,
                        'message'=>stripslashes($message),
                        'created_at'=>date('Y-m-d H:i:s')
                    )
                );
            } else {
                $query = $wpdb->update($wpdb->prefix.'newsletter', 
                    array(
                        'title' => $title,
                        'message' => stripslashes($message),
                        'updated_at'  => date('Y-m-d H:i:s')
                    ),
                    array(
                        'id' => $id
                    )
                );
            }

            include(plugin_dir_path( __FILE__ ) . 'page/main.php');
        } catch (Exception $e) {
            echo 'Caught exception: ',  $e->getMessage(), "\n";
        }
    }

    function deleteNewsletterPage() {
        global $wpdb;
        
        try{
            $wpdb->update( $wpdb->prefix.'newsletter',array('deleted_at' => date('Y-m-d H:i:s')), array( 'id' => $_GET['id'] ) );
            include(plugin_dir_path( __FILE__ ) . 'page/main.php');
        } catch (Exception $e) {
            echo 'Caught exception: ',  $e->getMessage(), "\n";
        }
    }

    function shareNewsletterPage() {
        global $wpdb;
        
        try{
            $wpdb->update( $wpdb->prefix.'newsletter',array('datetime' => date('Y-m-d H:i:s')), array( 'id' => $_GET['id'] ) );
            include(plugin_dir_path( __FILE__ ) . 'page/main.php');
        } catch (Exception $e) {
            echo 'Caught exception: ',  $e->getMessage(), "\n";
        }
    }
}

if ( !class_exists( 'Newsletter' ) ) {
    $newsletter = new Newsletter();
}

// activation
register_activation_hook( __FILE__, array( $newsletter, 'activate' ));

// deactivation
register_deactivation_hook( __FILE__, array( $newsletter, 'deactivate' ));

?>