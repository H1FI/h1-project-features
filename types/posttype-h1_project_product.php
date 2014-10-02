<?php
/**
 * Example World Domination Product post type
 * 
 * @package default
 */
class H1_Project_Product extends H1M_PostType {
	protected static $__CLASS__ = __CLASS__;

	public static $type = 'h1_project_product';

	static function register() {
		
		/**
		 * $names only works for Finnish, use an empty array if you need i18n
		 */		
		$names = array( 
			'name' => 'Tuote', 
			'plural' => 'Tuotteet', 
			'partitive' => 'Tuotetta', 
			'partitive_plural' => 'Tuotteita' 
			);


		/**
		 * If the post type needs to be translatable, uncomment and modify all i18ized stringses here
		 */
		$generated_labels = array( 
			'menu_name' => __( 'Tuotteet', 'h1-project-features' ), 
			// 'name'               => _x( 'Products', 'post type general name', 'h1-project-features' ),
			// 'singular_name'      => _x( 'Product', 'post type singular name', 'h1-project-features' ),
			// 'name_admin_bar'     => _x( 'Product', 'add new on admin bar', 'h1-project-features' ),
			// 'add_new'            => _x( 'Add New', 'product', 'h1-project-features' ),
			// 'add_new_item'       => __( 'Add New Product', 'h1-project-features' ),
			// 'new_item'           => __( 'New Product', 'h1-project-features' ),
			// 'edit_item'          => __( 'Edit Product', 'h1-project-features' ),
			// 'view_item'          => __( 'View Product', 'h1-project-features' ),
			// 'all_items'          => __( 'All Products', 'h1-project-features' ),
			// 'search_items'       => __( 'Search Products', 'h1-project-features' ),
			// 'parent_item_colon'  => __( 'Parent Products:', 'h1-project-features' ),
			// 'not_found'          => __( 'No products found.', 'h1-project-features' ),
			// 'not_found_in_trash' => __( 'No products found in Trash.', 'h1-project-features' ),			
			);

		/**
		 * Define other arguments. For full list see http://codex.wordpress.org/Function_Reference/register_post_type
		 */
		$args = array(
			'rewrite'  => array( 'slug' => 'tuote' ),
			'supports' => array( 'title', 'editor', 'thumbnail', 'excerpt', 'revisions' ),
			'taxonomies' => array( 'category' )
		);
		
		parent::register( $args, $names, $generated_labels );
	}

	static function custom_fields( $meta_boxes ) {

		// Define your meta boxes here
		// If using Meta Box by Rilwis, see http://www.deluxeblogtips.com/meta-box/getting-started/
		// If using Custom Meta Boxes by Humanmade, see https://github.com/humanmade/Custom-Meta-Boxes/wiki

		/*

			EXAMPLE (this will work with both plugins)

		    $meta_boxes[] = array(
		        'title' => 'Meta Box Title',
		        'pages' => array( 'page' ), // Array of post types
		        'context'    => 'normal',
		        'priority'   => 'high',
		        'fields' => array(
		        	// A single text field
					array( 
					    'id'   => 'example-field-text', 
					    'name' => 'A simple text input', 
					    'type' => 'text', 
					),
		        ),
		    );
		*/

		return $meta_boxes;
	}	
	
	static function get_latest() {
		$args = array(
						'post_type' => self::$type,
						'paged' => 1,
						'posts_per_page' => 10,
						'no_found_rows' => true,
					);
		$query = new WP_Query( $args );
		return $query;
	}
}