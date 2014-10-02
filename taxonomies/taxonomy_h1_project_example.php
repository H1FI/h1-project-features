<?php
/**
 * Example taxonomy. Split into individual files purely for organizational purposes
 * 
 * Remember to edit the strings and arguments to your liking!
 * 
 * @package default
 */
class H1_Project_Example_Taxonomy {
	static $taxonomy = 'h1_project_example_taxonomy';

	static function register( $types ) {

		// Taxonomy labels. Remember to edit these!
		$labels = array(
			'name'					=> _x( 'Plural Name', 'Taxonomy plural name', 'h1-project-features' ),
			'singular_name'			=> _x( 'Singular Name', 'Taxonomy singular name', 'h1-project-features' ),
			'search_items'			=> __( 'Search Plural Name', 'h1-project-features' ),
			'popular_items'			=> __( 'Popular Plural Name', 'h1-project-features' ),
			'all_items'				=> __( 'All Plural Name', 'h1-project-features' ),
			'parent_item'			=> __( 'Parent Singular Name', 'h1-project-features' ),
			'parent_item_colon'		=> __( 'Parent Singular Name', 'h1-project-features' ),
			'edit_item'				=> __( 'Edit Singular Name', 'h1-project-features' ),
			'update_item'			=> __( 'Update Singular Name', 'h1-project-features' ),
			'add_new_item'			=> __( 'Add New Singular Name', 'h1-project-features' ),
			'new_item_name'			=> __( 'New Singular Name Name', 'h1-project-features' ),
			'add_or_remove_items'	=> __( 'Add or remove Plural Name', 'h1-project-features' ),
			'choose_from_most_used'	=> __( 'Choose from most used text-domain', 'h1-project-features' ),
			'menu_name'				=> __( 'Singular Name', 'h1-project-features' ),
		);
	
		$args = array(
			'labels'            => $labels,
			'public'            => true,
			'show_in_nav_menus' => true,
			'show_admin_column' => true,
			'hierarchical'      => true,
			'show_tagcloud'     => true,
			'show_ui'           => true,
			'query_var'         => true,
			'rewrite'           => array( 'slug' => 'example-taxonomy' ),
			'query_var'         => true,
		);

		register_taxonomy( self::$taxonomy, $types, $args );

		/**
		 * Explicitly register taxonomies for each post type
		 */
		foreach ( $types as $type ) {
			register_taxonomy_for_object_type( self::$taxonomy, $type );	
		}
	  	
	}

}