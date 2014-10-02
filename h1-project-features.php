<?php
/*
Plugin Name: H1 Project Features
Plugin URI: http://h1.fi
Description: Define Custom post types, taxonomies and fields for project
Version: 1.1
Author: Daniel Koskinen / H1
Author URI: http://h1.fi
License: GPL2
*/
/*  Copyright 2012  Daniel Koskinen (email : dani@h1.fi)

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License, version 2, as 
	published by the Free Software Foundation.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/**
 * Useful helper functions
 */
require_once 'lib/h1lib.php';

/**
 * Basic model classes for post types etc
 */
require_once 'lib/h1modelapp.php';

//
//  SET THE PREFIXES AND CHANGE THE PLUGIN NAME / PLUGIN CLASSNAME!!!!
//
if ( !class_exists( 'H1_Project_Features' ) ) {

	class H1_Project_Features {

		/**
		 * Prefix used for custom post types, taxonomies
		 *
		 * @var string
		 */
		public $features_slug_prefix = 'h1_project';
		public $features_name_prefix = 'H1_Project';


		/**
		 * 
		 */
		function __construct() {

			register_activation_hook( __FILE__, array( $this, 'run_on_activation' ) );
			
			// Register post types on init
			add_action( 'muplugins_loaded', array( $this, 'muplugins_loaded_hook' ), 11 );
			add_action( 'plugins_loaded', array( $this, 'plugins_loaded_hook' ) );
			add_action( 'init', array( $this, 'register_taxonomies' ), 11 );

			// Register connections with Posts 2 Posts
			add_action( 'p2p_init', array( $this, 'register_connections' ) );

			// Register any global custom fields we want
			add_filter( 'cmb_meta_boxes', array( $this, 'global_custom_fields' ) );

			// Customize menus
			add_action( 'admin_menu', array( $this, 'custom_admin_menus' ) );

			// Reveal some hidden tinymce buttons (there are two other TinyMCE toolbars that are displayed in WordPress by default).
			// add_filter('mce_buttons', array( $this, 'mce_buttons' ) ); // primary toolbar (always visible)
			// add_filter('mce_buttons_2', array( $this, 'mce_buttons_2' ) ); // advanced toolbar (can be toggled on/off by user)


			// Filter on save_post and add default category
			add_action( 'save_post', array( $this, 'default_terms' ) );

			// Modify the query before it is run
			add_action( 'pre_get_posts', array( $this, 'pre_get_posts_hook' ) );

		}

		/**
		 * Everything we want to run on activation goes here
		 */
		public function run_on_activation() {
			// Post types need to be registered before flushing rewrites
			$this->register_post_types();
			$this->register_taxonomies();

			// Make sure rewrite rules are updated to reflect our post types/taxonomies
			flush_rewrite_rules();
		}

		/**
		 * Stuff to run on 'init'
		 */
		public function muplugins_loaded_hook() {

			$this->register_post_types();

		}


		/**
		 * Functionality that needs to run on 'plugins_loaded'.
		 *
		 * @return  void
		 */
		public function plugins_loaded_hook() {

			$this->register_post_types();

		}


		/**
		 * Register custom post types.
		 *  
		 * @return void
		 */
		function register_post_types() {

			// $posttypes = array(
			// 	$this->features_slug_prefix . '_article' => $this->features_name_prefix . '_Article',
			// 	$this->features_slug_prefix . '_product' => $this->features_name_prefix . '_Product',
			// );

			// foreach ( $posttypes as $posttype => $class_name ) {

			// 	require_once( 'types/posttype-' . $posttype . '.php' );
				
			// 	call_user_func( array( $class_name, 'setup' ) );

			// }

		}


		/**
		 * Register custom taxonomies.
		 * 
		 * @return void
		 */
		function register_taxonomies() {

			$taxonomies = array();

			// Add here taxonomy description
			/*
			$taxonomies[ $this->features_slug_prefix . '_type' ] = array(
				'class_name' 	=> $this->features_slug_prefix . '_Example_Taxonomy',
				'types' 		=> array( $this->features_slug_prefix . '_product' ),
			);

			foreach ( $taxonomies as $taxonomy => $params ) {
				require_once( 'taxonomies/taxonomy-' . $taxonomy . '.php' );
				
				call_user_func( array( $params['class_name'], 'register' ), $params['types'] );
			}


			// Set some default categories
			if ( !term_exists( 'Category-A', 'category' ) ) {
				wp_insert_term( 'Category-A', 'category' );
			}

			register_taxonomy_for_object_type( 'category', $this->features_slug_prefix . '_article' );
			register_taxonomy_for_object_type( 'category', $this->features_slug_prefix . '_product' );
			*/

		}


		/**
		 * Register post relationships.
		 *
		 * Defines/registers relationships between posts, currently uses 'Posts 2 Posts' plugin.
		 *
		 * @link http://wordpress.org/plugins/posts-to-posts/
		 * @link https://github.com/scribu/wp-posts-to-posts/wiki
		 * 
		 */
		function register_connections() {

			// Add here a description of the relationship.
			/*
			p2p_register_connection_type( array(
					'name'  => 'featured_product',
					'from'  => 'page',
					'to'    => array(
						$this->features_slug_prefix . '_product',
						$this->features_slug_prefix . '_article' ),
					'admin_box' => array(
						'show' => 'from',
						'context' => 'advanced',
						),
					'sortable' => 'from',
					'title'     => array(
						'from'  => '',
						),
					'to_labels' => array(
						'singular_name' => __( 'Items', 'h1_project' ),
						'search_items'  => __( 'Search Items', 'h1_project' ),
						'not_found'     => __( 'Not Found', 'h1_project' ),
						'create'        => __( 'Create connections ', 'h1_project' ),
						),
					'fields' => array(
						'bigfeature' => array(
							'title' => 'Iso nosto',
							'type'  => 'checkbox'
							)
						)
				) );
			*/

		}


		/**
		 * Defines custom fields.
		 *
		 * Defines custom fields, works with filter of Meta Box and Custom Meta Boxes plugins.
		 *  
		 * @param  array $meta_boxes Meta boxes list
		 * @return array             Meta boxes list with global fields
		 */
		function global_custom_fields( $meta_boxes ) {

			// Gallery, these can be added anywhere
			// $meta_boxes[] = array(

			// 		'id' => 'testbox',
			// 		'title' => 'TestBox',
			// 		'pages' => array( $this->features_slug_prefix . '_article' ),
			// 		'context' => 'normal',
			// 		'priority' => 'high',
			// 		'fields' => array(
			// 			array(
			// 				'id'    => 'testfield',
			// 				'name'  => 'lorem ipsum',
			// 				'type'  => 'image',
			// 				'repeatable' => true,
			// 				'sortable' => true
			// 			),
			// 		),
			// 	);

			return $meta_boxes;
		} 


		/**
		 * Add some custom taxonomy menus manually, and remove some others
		 */
		function custom_admin_menus() {          

		}


		/**
		 * Change Admin wysiwyg editor advanced toolbar buttons.
		 * 
		 * @param  array $buttons List of buttons currently defined in the editor.
		 * @return array          Changed list of buttons in the editor.
		 */
		function mce_buttons_2( $buttons ) {   

			// Add in a core button that's disabled by default
			$buttons[] = 'sup';
			$buttons[] = 'sub';

			return $buttons;

		}

		/**
		 * Set the default terms for specific post types
		 * 
		 * @param int $post_id 
		 */
		function default_terms( $post_id ) {

			// If this is just a revision, don't set any terms
			if ( wp_is_post_revision( $post_id ) )
				return;

			if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
				return $post_id;

			// Array of affected post types and associated categories
			// Uncomment and modify the following to set default terms

			$types_terms = array(
//					$this->features_slug_prefix . '_article'  => 'Default Term Name',
				);

			$post_type = get_post_type( $post_id );

			foreach ( $types_terms as $type => $term ) {
				if ( $type == $post_type ) {
					$term_id = term_exists( $term, 'category', 0 );
					
					$success = wp_set_post_terms( $post_id, array( $term_id['term_id'] ), 'category' );
				}
			}
		}

		/**
		 * Include a custom post type 
		 * 
		 * @param type $query 
		 * @return type
		 */
		function pre_get_posts_hook( $query ) {
						
			/*
				EXAMPLE: add a custom post type to category archives

				If this is a category archive, include the listed post types
				
				if ( $query->is_main_query() && $query->is_category() ) {
					$query->set( 'post_type', array( 'post', $this->features_slug_prefix . '_article' ) );
				}
			*/

		}

	}

	$h1_features = new H1_Project_Features();

}
