<?php
/**
 * PostType class. Extend for creating your own post types quickly. 
 */
abstract class H1M_PostType {
	protected static $__CLASS__ = __CLASS__;
	
	protected static $type;
	protected static $fields;

	//arguments of the register_post_type function
	public static $label;
	public static $labels;
	public static $public;
	public static $publicly_queryable;
	public static $exclude_from_search;
	public static $show_ui; 
	public static $show_in_menu;
	public static $query_var;
	public static $rewrite;
	public static $capability_type;
	public static $has_archive;
	public static $hierarchical;
	public static $menu_position;
	public static $supports;

	protected function is( $type ) {
		return $this::$type == $type;
	}
	
	static function get( $id ) {
		$post = get_post( $id );
		self::construct_model_fields( $post );
		return $post;
	}
	
	/**
	 * This sets everything up, call this from your plugin eg. call_user_func( array( $class_name, 'setup' ) );
	 */
	static function setup() {

		// Post types should be registered on 'init'
		add_action( 'init', array( static::$__CLASS__, 'register' ) );

		// We support both CMB and Meta Box. Choose one.
		if ( function_exists( 'cmb_init' ) ) {
			add_filter( 'cmb_meta_boxes', array( static::$__CLASS__, 'custom_fields' ) );
		} elseif ( class_exists( 'RW_Meta_Box' ) ) {
			add_filter( 'rwmb_meta_boxes', array( static::$__CLASS__, 'custom_fields' ) );
		}
	}

	/**
	 * Register the post type
	 * 
	 * @param array $args 
	 * @param array $names 
	 * @param array $labels 
	 */
	static function register( $args, $names, $labels = array() ) {

		/**
		 * Default names
		 */
		$default_names = array(
				'name'             => 'Artikkeli',
				'plural'           => 'Artikkelit',
				'partitive'        => 'Artikkelia',
				'partitive_plural' => 'Artikkeleita',
			);
		$names = wp_parse_args( $names, $default_names );

		/**
		 * These only work for Finnish. If the original labels are not in Finnish, or are __("localised"),
		 * you'll need to override all the labels in the passed $labels
		 */
		$generated_labels = array(  
			'name'                  => $names[ 'plural' ],
			'singular_name'         => $names[ 'name'   ],
			'add_new'               => 'Lisää uusi',
			'add_new_item'          => 'Lisää uusi ' . mb_strtolower( $names[ 'name'      ] ),
			'edit_item'             => 'Muokkaa '    . mb_strtolower( $names[ 'partitive' ] ),
			'new_item'              => 'Uusi '       . mb_strtolower( $names[ 'name'      ] ),
			'all_items'             => 'Kaikki '     . mb_strtolower( $names[ 'plural'    ] ),
			'view_item'             => 'Näytä '      . mb_strtolower( $names[ 'name'      ] ),
			'search_items'          => 'Etsi '       . mb_strtolower( $names[ 'partitive' ] ),
			'not_found'             => $names[ 'partitive_plural' ] . ' ei löytynyt',
			'not_found_in_trash'    => $names[ 'partitive_plural' ] . ' ei löytynyt roskakorista',
			'parent_item_colon'     => '',
			'menu_name'             => $names[ 'plural' ]
		);
		$labels = wp_parse_args( $labels, $generated_labels );

		/**
		 * Default arguments for register_post_type. Override with $args
		 */
		$defaults = array(
				'label'               => $names[ 'plural' ],
				'labels'              => $labels,
				'public'              => true,
				'publicly_queryable'  => true,
				'exclude_from_search' => false,
				'show_ui'             => true, 
				'show_in_menu'        => true, 
				'query_var'           => true,
				'rewrite'             => array( 'slug' => $names[ 'plural' ] ),
				'capability_type'     => 'post',
				'has_archive'         => true, 
				'hierarchical'        => false,
				'menu_position'       => null,
				'supports'            => array( 'title', 'editor', 'author', 'excerpt', 'comments', 'thumbnail' )
			);
		$args = wp_parse_args( $args, $defaults );

		register_post_type( static::$type, $args );
	}

	/**
	 * Redefine this method in your post type classes, and define your fields and meta boxes
	 * according to CMB documentation here https://github.com/humanmade/Custom-Meta-Boxes/wiki/Create-a-Meta-Box
	 */
	static function custom_fields( $meta_boxes ) {

		return $meta_boxes;
	}
	
	protected $post;
	function __construct( &$post ) {
		$this->post = $post;
	}
}
