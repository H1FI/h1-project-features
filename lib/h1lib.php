<?php
/*
Plugin Name: H1Lib
Plugin URI: http://h1.fi/
Description: collection of static functions of general usefulness
Author: Aki Björklund
Version: 1.2
Author URI: http://h1.fi/
*/

if ( ! class_exists( 'H1Lib' ) ) {
	class H1Lib {
		static function content_loop( $query = null, $inject = null ) {
			if ( $query == null ) {
				global $wp_query;
				$query = $wp_query;
			}
			$i = 1;
			if ( $query->have_posts() ) :
				while ( $query->have_posts() ) : $query->the_post();
					get_template_part( 'content', get_post_type() );
					if ( is_array( $inject ) && isset( $inject[ $i ] ) ) echo $inject[ $i ];
					$i++;
				endwhile;
			endif;
		}

		static function the_meta_field( $key, $default = null, $echo = true, $esc = true ) {
			$meta = get_post_meta( get_the_ID(), $key, true );
			if ( ! $meta ) $meta = $default;
			
			if ( $esc ) {
				$meta = esc_attr( $meta );
			}
			if ( $echo ) {
				echo $meta;
				return true;
			} else {
				return $meta;
			}
		}

		static function get_the_meta_field( $key, $default = null ) {
			return H1Lib::the_meta_field( $key, $default, false );
		}
		
		static function get_the_meta_field_raw( $key, $default = null ) {
			return H1Lib::the_meta_field( $key, $default, false, false );
		}
		
		static function the_meta_field_raw( $key, $default = null ) {
			H1Lib::the_meta_field( $key, $default, true, false );
		}
		
		static function the_meta_fields( $keys, $separator ) {
			$first = true;
			foreach ( $keys as $key ) {
				$field = trim ( self::get_the_meta_field( $key, '' ) );
				if ( ! empty( $field ) && ! $first ) {
					echo $separator;
				}
				
				if ( ! empty( $field ) ) {
					echo $field;
					$first = false;
				}
			}
		}

		// normalizes urls
		static function full_url( $url = '' ) {
			if ( $url != '' && H1Lib::starts_with( $url, 'http' ) ) return $url;
	
			$pageURL = H1Lib::root_url();
	
			if ( $url != '' ) {
				if ( ! H1Lib::starts_with( $url, '/' ) ) $url = '/' . $url;
				$pageURL .= $url;
			} else {
				$pageURL .= $_SERVER[ "REQUEST_URI" ];
			}
			return $pageURL;
		}

		static function root_url() {
			$url = ( @$_SERVER["HTTPS" ] == "on" ) ? "https://" : "http://";
			if ( $_SERVER[ "SERVER_PORT" ] != "80" ) {
				$url .= $_SERVER[ "SERVER_NAME" ] . ":" . $_SERVER[ "SERVER_PORT" ];
			} else {
				$url .= $_SERVER[ "SERVER_NAME" ];
			}
			return $url;
		}
		
		static function url_contains( $string ) {
			return strpos( self::full_url(), $string ) !== false;
		}

		static function is_really_single() {
			return is_single() && H1Lib::starts_with( H1Lib::full_url(), get_permalink() );
		}

		static function smart_nav_class( $classes, $item ) {
			if ( ! in_array( 'current_page_item', $classes ) && H1Lib::full_url( $item->url ) == H1Lib::full_url() ) {
				$classes[] = "current_page_item";
			} else if ( ! in_array( 'current_page_item', $classes )
				&& ! in_array( 'current_page_ancestor', $classes )
				&& H1Lib::full_url( $item->url ) != get_option( 'home' ) . '/'
				&& H1Lib::starts_with( H1Lib::full_url( H1Lib::full_url() ), $item->url ) ) {
				$classes[] = "current_page_ancestor";
			}
			return $classes;
		}

		static function the_post_thumbnail_remove_title( $output ) {
		    $output = preg_replace( "/ title=\"[^\"]*\"/i", '', $output );
		    return $output;
		}

		// Add ?v=[last modified time] to style sheets
		static function versioned_stylesheet( $relative_url, $add_attributes = "" ){
			echo '<link rel="stylesheet" href="' . H1Lib::versioned_resource( $relative_url ) . '" '.$add_attributes . '>' . "\n";
		}

		// Add ?v=[last modified time] to javascripts
		static function versioned_javascript( $relative_url, $add_attributes = "" ) {
			echo '<script src="' . H1Lib::versioned_resource( $relative_url ) . '" ' . $add_attributes . '></script>' . "\n";
		}

		// Add ?v=[last modified time] to a file url
		static function versioned_resource( $relative_url ) {
			$file = $_SERVER[ "DOCUMENT_ROOT" ] . $relative_url;
			$file_version = "";

			if ( file_exists( $file ) ) {
				$file_version = "?v=" . filemtime( $file );
			}

			return $relative_url . $file_version;
		}

		static function starts_with( $haystack, $needle ) {
		    $length = strlen( $needle );
		    return ( substr( $haystack, 0, $length ) === $needle );
		}

		static function ends_with( $haystack, $needle ) {
		    $length = strlen( $needle );
		    $start  = $length * -1; //negative
		    return ( substr( $haystack, $start ) === $needle );
		}
	
		static function make_tinymce_sane() {
			if ( is_admin() ) {
				add_filter( 'tiny_mce_before_init', array( __CLASS__, 'tinymce_refine_format' ) );
			}
		}
	
		static function add_custom_editor_css_to_tinymce() {
			if ( is_admin() ) {
				add_filter( 'mce_css', array( __CLASS__, 'tdav_css' ) );
			}
		}
	
		/*
		Customises the list of styles available in the editor.
		*/
		static function tinymce_refine_format( $initvalues ) {
			$initvalues[ 'theme_advanced_blockformats' ] = "h3,h4,h5,p";
			$initvalues[ 'theme_advanced_buttons1_add_before' ] = 'formatselect';
			$initvalues[ 'theme_advanced_disable' ] = 'justifyleft, justifycenter, justifyright, underline, justifyfull, forecolor, outdent, indent';
			//add hr after blockquote
			$initvalues[ 'theme_advanced_buttons1' ] = preg_replace( '/blockquote,/', 'blockquote, hr,', $initvalues[ 'theme_advanced_buttons1' ] );
			//manually remove formatselect from row 2 to avoid it being removed from row 1 too
			$initvalues[ 'theme_advanced_buttons2' ] = preg_replace( '/formatselect,?/', '', $initvalues[ 'theme_advanced_buttons2' ] );
			return $initvalues;
		}
	
		/*
		 *
		 *  Adds a filter to append the default stylesheet to the tinymce editor.
		 *
		 */
		static function tdav_css( $wp ) {
			$css = self::versioned_resource( $GLOBALS["TEMPLATE_RELATIVE_URL"] . "editor.css" );
			$wp .= ',' . $css;
			return $wp;
		}
		
		static function add_h1_menu( $more_links = array() ) {
			if ( is_admin() || is_admin_bar_showing() ) {
				add_filter( 'admin_bar_menu', array( __CLASS__, 'h1_menu' ), 99 );
				if ( ! empty( $more_links ) ) {
					self::$h1_menu_more_links = $more_links;
				}
			}
		}
		private static $h1_menu_more_links;
		
		static function h1_menu() {
			global $wp_admin_bar;

			$links = array(
				array(
					'title' => 'Tuki (help@h1.fi)',
					'href' => 'mailto:help@h1.fi',
					'id' => 'tuki',
				)
			);
			
			if ( self::$h1_menu_more_links )
				$links = array_merge( $links, self::$h1_menu_more_links );
			
			$wp_admin_bar->add_menu( array(
				'title' => 'H1',
				'href' => 'http://h1.fi',
				'id' => 'h1_menu',
			));

			foreach ( $links as $link ) {
				$wp_admin_bar->add_menu( array(
					'title' => $link[ 'title' ],
					'href' => $link[ 'href' ],
					'parent' => 'h1_menu',
					'id' => 'h1_menu_' . $link[ 'id' ],
				));
			}
		}
		
		static function include_with_prefix( $dirs, $prefixes ) {
			if ( ! is_array( $prefixes ) )
				$prefixes = array( $prefixes );
			if ( ! is_array( $dirs ) )
				$dirs = array( $dirs );

			$matched_files = array();
			foreach ( $dirs as $dir ) {
				if ( $handle = opendir( $dir ) ) {
				    while ( false !== ( $file = readdir( $handle ) ) ) {
				    	foreach ( $prefixes as $prefix ) {
					        if ( H1Lib::starts_with( $file, $prefix ) ) {
					            include_once( $dir . '/' . $file );
								$matched_files[] = $dir . '/' . $file;
					        }
						}
				    }
				    closedir( $handle );
				}
			}
			return $matched_files;
		}
		
		static function get_single_term( $post_id, $taxonomy, $field = null, $default = null ) {
			$terms = get_the_terms( $post_id, $taxonomy );
			if ( is_wp_error( $terms ) || ! $terms ) return $default;
			
			foreach ( $terms as $term ) {
				if ( $field ) {
					return $term->$field;
				}
				return $term;
			}
		}
		
		static function get_terms( $post_id, $taxonomy, $field = null, $default = null ) {
			$terms = get_the_terms( $post_id, $taxonomy );
			if ( is_wp_error( $terms ) || ! $terms ) return $default;
			
			$return = array();
			foreach ( $terms as $term ) {
				if ( $field ) {
					$return[] = $term->$field;
					continue;
				}
				$return[] = $term;
			}
			return $return;
		}
		
		static function the_thumbnail_or_first( $post_id = null, $size = 'post-thumbnail', $default = null, $echo = true ) {
			$pic = '';

			if ( $post_id == null ) {
				global $post;
				$post_id = $post->ID;
			}

			if ( has_post_thumbnail( $post_id ) ) {
				$pic = get_the_post_thumbnail( $post_id, $size );
			} else {
				$args = array(
					'numberposts' => 1,
					'order'=> 'ASC',
					'post_mime_type' => 'image',
					'post_parent' => $post_id,
					'post_status' => null,
					'post_type' => 'attachment'
				);

				$attachments = get_children( $args ); // get the first attachment

				if ( empty( $attachments ) ) return false;

				if ( $attachments ) {
					foreach( $attachments as $attachment ) {
						$image = wp_get_attachment_image_src( $attachment->ID, $size );

						$pic = '<img src="'. $image[ 0 ] .'" />';
					}
				}

			}

			if ( $echo ) echo $pic;
			else return $pic;
		}

	}
}