<?php
/**
 * Moottori version of H1ModelApp
 */

require_once 'posttype.php';

class H1ModelApp {
	private $models_paths;

	function __construct( $models_paths ) {
		if ( $models_paths && ! is_array( $models_paths ) )
			$models_paths = array( $models_paths );

		$this->models_paths = $models_paths;

		$this->register_post_types();

		do_action( 'h1m_register_posttype' );
	}

	function register_post_types() {
		if ( ! class_exists( 'H1Lib' ) || ! $this->models_paths )
			return;

		global $meta_boxes;

		$files = H1Lib::include_with_prefix( $this->models_paths, 'posttype-' );

		foreach ( $files as $file ) {
			$class = $this->get_inheriting_class_name( $file, 'H1M_PostType' );
			if ( $class ) {
				call_user_func( array( $class, 'register' ), &$meta_boxes );
			}
		}
	}

	function get_inheriting_class_name( $file, $base_class ) {
		$content  = file_get_contents( $file );
		if ( ! preg_match( "/class ([^ ]+) extends $base_class/", $content, $matches ) ) {
			return false;
		}

		return $matches[ 1 ];
	}

}