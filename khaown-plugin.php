<?php
/**
 * Plugin Name:       Khaown-Plugin
 * Description:       Handle the basics with this plugin.
 * Version:           1.1
 * Requires at least: 5.2
 * Requires PHP:      5.6
 * Author:            Motahar Hossain
 * Author URI:        http://e-motahar.com/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 */
namespace khaown_pro_plugin;
/**
 * Use namespace to avoid conflict
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
//************** Starts Custom post type food_items with meta boxes ***************//
/**
 * Class khaown_pp_class
 * @package khaown_pro_plugin
 *
 * Use actual name of post type for
 * easy readability.
 *
 * Potential conflicts removed by namespace
 */
if ( !class_exists( 'khaown_pp_class' ) ) {
    class khaown_pp_class {
        /**
         * @var string
         *
         * Set post type params
         */

        /**
         * Register post type
         */
        public function khaown_create_food_items_post_type() {

            $type               = 'food_menu';
            $slug               = 'food_menus';
            $name               = 'Food Menus';
            $singular_name      = 'Food Menu';
            
            $labels = array(
                'name'                  => $name,
                'singular_name'         => $singular_name,
                'add_new'               => 'Add New',
                'add_new_item'          => 'Add New '   . $singular_name,
                'edit_item'             => 'Edit '      . $singular_name,
                'new_item'              => 'New '       . $singular_name,
                'all_items'             => 'All '       . $name,
                'view_item'             => 'View '      . $name,
                'search_items'          => 'Search '    . $name,
                'not_found'             => 'No '        . strtolower($name) . ' found',
                'not_found_in_trash'    => 'No '        . strtolower($name) . ' found in Trash',
                'parent_item_colon'     => '',
                'menu_name'             => $name
            );
            $args = array(
                'labels'                => $labels,
                'public'                => true,
                'publicly_queryable'    => true,
                'show_ui'               => true,
                'show_in_menu'          => true,
                'query_var'             => true,
                'rewrite'               => array( 'slug' => $slug ),
                'capability_type'       => 'post',
                'has_archive'           => true,
                'hierarchical'          => true,
                'menu_icon'             => 'dashicons-buddicons-community',
                'menu_position'         => 26,
                'supports'              => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail'),
                'show_in_rest'          => true
            );
            register_post_type( $type, $args );
        }

        public function khaown_create_customer_review_post_type() {

            $type               = 'customer_review';
            $slug               = 'customer_reviews';
            $name               = 'Customer Reviews';
            $singular_name      = 'Customer Review';
            
            $labels = array(
                'name'                  => $name,
                'singular_name'         => $singular_name,
                'add_new'               => 'Add New',
                'add_new_item'          => 'Add New '   . $singular_name,
                'edit_item'             => 'Edit '      . $singular_name,
                'new_item'              => 'New '       . $singular_name,
                'all_items'             => 'All '       . $name,
                'view_item'             => 'View '      . $name,
                'search_items'          => 'Search '    . $name,
                'not_found'             => 'No '        . strtolower($name) . ' found',
                'not_found_in_trash'    => 'No '        . strtolower($name) . ' found in Trash',
                'parent_item_colon'     => '',
                'menu_name'             => $name
            );
            $args = array(
                'labels'                => $labels,
                'public'                => true,
                'publicly_queryable'    => true,
                'show_ui'               => true,
                'show_in_menu'          => true,
                'query_var'             => true,
                'rewrite'               => array( 'slug' => $slug ),
                'capability_type'       => 'post',
                'has_archive'           => true,
                'hierarchical'          => true,
                'menu_icon'             => 'dashicons-buddicons-buddypress-logo',
                'menu_position'         => 26,
                'supports'              => array( 'editor', 'excerpt', 'author'),
                'show_in_rest'          => true
            );
            register_post_type( $type, $args );
        }

        //adding meta box to save additional meta data for the content type
        public function add_meta_boxes_to_customer_review(){
            //add a meta box
            add_meta_box(
                'customer_review_meta_box', //id
                'Add review providers name', // $title
                array($this,'callback_to_show_the_review_meta_boxes'),  //display function
                'customer_review', //content type 
                'normal', //context
                'high' //priority
            );
            
        }

        //displays the back-end admin output for the event information
        public function callback_to_show_the_review_meta_boxes( $post ) {
            wp_nonce_field( basename( __FILE__ ), 'prfx_nonce' );
          $em_reviews_stored_meta = get_post_meta( $post->ID );
        ?>
          
          <p>
            <label for="display_reviewer_name" class="prfx-row-title"><?php _e( "Add Reviewer Name:", 'prfx-textdomain' )?></label>
            <input type="text" name="display_reviewer_name" id="display_reviewer_name" value="<?php if ( isset ( $em_reviews_stored_meta['display_reviewer_name'] ) ) echo $em_reviews_stored_meta['display_reviewer_name'][0]; ?>" />
          </p>
          
        <?php }

        // Save all post types meta fields
        public function save_all_posts_types_meta_fields_meta( $post_id ) {
            // Checks save status
            $is_autosave = wp_is_post_autosave( $post_id );
            $is_revision = wp_is_post_revision( $post_id );
            $is_valid_nonce = ( isset( $_POST[ 'prfx_nonce' ] ) && wp_verify_nonce( $_POST[ 'prfx_nonce' ], basename( __FILE__ ) ) ) ? 'true' : 'false';

            // Exits script depending on save status
            if ( $is_autosave || $is_revision || !$is_valid_nonce ) {
                return;
            }
        // *************** post-type: Reviews **************//

        // Checks for input and sanitizes/saves if needed
            if( isset( $_POST[ 'display_reviewer_name' ] ) ) {
                update_post_meta( $post_id, 'display_reviewer_name', $_POST[ 'display_reviewer_name' ] );
            }
        }

        

        


        // public function khaown_pro_plugin_activation() {
        //     // trigger our function that registers the custom post type
        //     khaown_create_food_items_post_type();
        //     khaown_create_customer_review_post_type();
        //     add_meta_boxes_to_customer_review();
        
        //     // clear the permalinks after the post type has been registered
        //     flush_rewrite_rules();
        // }
        
        
        // public function khaown_pro_plugin_deactivation() {
        //     // unregister the post type, so the rules are no longer in memory
        //     unregister_post_type( 'food_menu' );
        //     unregister_post_type( 'customer_review' );
        //     // clear the permalinks to remove our post type's rules from the database
        //     flush_rewrite_rules();
        // }

        /**
         * food_menu constructor.
         *
         * When class is instantiated
         */
        public function __construct() {
            // Register the post type
            add_action('init', array($this, 'khaown_create_food_items_post_type'));
            add_action('init', array($this, 'khaown_create_customer_review_post_type'));
            add_action('add_meta_boxes', array($this,'add_meta_boxes_to_customer_review')); //add meta boxes
            add_action('save_post', array($this,'save_all_posts_types_meta_fields_meta')); //add meta boxes

            // register_activation_hook( __FILE__, 'khaown_pro_plugin_activation' );
            // register_deactivation_hook( __FILE__, 'khaown_pro_plugin_deactivation' );
        }
        
        
        
    }
}
/**
 * Instantiate class, creating post type
 */
$khaown_pro_plugin = new khaown_pp_class();