<?php
/**
 * Simple Example World Domination Article post type
 * 
 * @package default
 */
class H1_Project_Article extends H1M_PostType {
	protected static $__CLASS__ = __CLASS__;

	public static $type = 'h1_project_article';

	static function register() {
		
		/**
		 * $names only works for Finnish, use an empty array if you need i18n
		 */		
		$names = array( 
			);

		/**
		 * If the post type needs to be translatable, uncomment and modify all i18ized stringses here
		 */
		$generated_labels = array( 
			'menu_name' 			=> __( 'Articles', 'h1-project-features' ), 
			'name'              	=> _x( 'Articles', 'post type general name', 'h1-project-features' ),
			'singular_name'     	=> _x( 'Article', 'post type singular name', 'h1-project-features' ),
			'name_admin_bar'    	=> _x( 'Article', 'add new on admin bar', 'h1-project-features' ),
			'add_new'            	=> _x( 'Add New', 'product', 'h1-project-features' ),
			'add_new_item'       	=> __( 'Add New Article', 'h1-project-features' ),
			'new_item'           	=> __( 'New Article', 'h1-project-features' ),
			'edit_item'          	=> __( 'Edit Article', 'h1-project-features' ),
			'view_item'          	=> __( 'View Article', 'h1-project-features' ),
			'all_items'          	=> __( 'All Articles', 'h1-project-features' ),
			'search_items'       	=> __( 'Search Articles', 'h1-project-features' ),
			'parent_item_colon'  	=> __( 'Parent Articles:', 'h1-project-features' ),
			'not_found'          	=> __( 'No articles found.', 'h1-project-features' ),
			'not_found_in_trash' 	=> __( 'No articles found in Trash.', 'h1-project-features' ),			
			);

		/**
		 * Define other arguments. For full list see http://codex.wordpress.org/Function_Reference/register_post_type
		 */
		$args = array(
			'rewrite'  => array( 'slug' => 'artikkeli' ),
			'supports' => array( 'title', 'editor', 'thumbnail', 'excerpt', 'revisions' ),
			'taxonomies' => array( 'category' )
		);
		
		parent::register( $args, $names, $generated_labels );
	}

}