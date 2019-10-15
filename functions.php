<?php
define( 'DIGITALNOMAD_THEME_VERSION', '1.0' );
/**
 * Theme setup functions
 */
require_once get_template_directory() . '/inc/theme-setup.php';
/**
 * Customizer functions
 */
require_once get_template_directory() . '/inc/customizer.php';
/**
 * Theme info page + after install staff
 */
require_once get_template_directory() . '/inc/theme-panel.php';
/**
 * AJAX functions
 */
require_once get_template_directory() . '/inc/ajax-functions.php';
# helpers #
if ( !function_exists( 'pa' ) ) {

	/**
	 * Custom print_r function for development
	 */
	function pa( $data ) {
		print '<hr><pre>';
		print_r( $data );
		print '</pre><hr>';
	}

}
# helpers ends #
# enqueues #
if ( !function_exists( 'digitalnomad_add_styles_in_footer' ) ) {

	/**
	 * move_styles_to_footer for better perfomance
	 */
	function digitalnomad_add_styles_in_footer() {
		wp_enqueue_style( 'main', get_template_directory_uri() . '/style.css', array(), get_bloginfo( 'version' ) /* time() for dev */ );
	}

}

add_action( 'wp_print_styles', 'digitalnomad_add_styles_in_footer' );

if ( !function_exists( 'digitalnomad_add_scripts' ) ) {

	/**
	 * Adds required scripts to theme
	 */
	function digitalnomad_add_scripts() {

		if ( get_theme_mod( 'digitalnomad_blazy_lazyload', true ) && (is_archive() || is_home() /* || is_singular() */) ) {
			wp_enqueue_script( 'blazy-lazyload', get_template_directory_uri() . '/js/blazy.min.js', null, DIGITALNOMAD_THEME_VERSION, true );
		}
		if ( get_theme_mod( 'digitalnomad_infinite_scroll', true ) && (is_archive() || is_home()) ) {
			wp_enqueue_script( 'infinite-scroll', get_template_directory_uri() . '/js/infinite-scroll.min.js', null, DIGITALNOMAD_THEME_VERSION, true );
		}

		if ( is_singular() && comments_open() && (get_option( 'thread_comments' ) == 1) ) {
			wp_enqueue_script( 'comment-reply', '/wp-includes/js/comment-reply.min.js', null, get_bloginfo( 'version' ), true );
		}
	}

}

add_action( 'wp_enqueue_scripts', 'digitalnomad_add_scripts' );
# enqueues ends #
# content functions #
if ( !function_exists( 'digitalnomad_the_date' ) ) {

	/**
	 * Custom the_date function
	 */
	function digitalnomad_the_date() {
		echo digitalnomad_get_the_date();
	}

}

if ( !function_exists( 'digitalnomad_get_the_date' ) ) {

	/**
	 * Custom the_date function
	 */
	function digitalnomad_get_the_date() {
		$code		 = '';
		$published	 = get_the_date( 'U' );
		$updated	 = get_the_modified_time( 'U' );
		$date_format = get_option( 'date_format' );

		$code .= '<time class="published" datetime="' . date( 'c', $published ) . '">' . date( $date_format, $published ) . '</time>';
		if ( $published != $updated ) {
			$code .= ', <span class="last_update_text">' . __( 'edited:', 'digital-nomad' ) . '</span> <time class="updated" datetime="' . date( 'c', $updated ) . '">' . date( $date_format, $updated ) . '</time>';
		} else {
			
		}
		return $code;
	}

}

if ( !function_exists( 'digitalnomad_get_the_date_ago' ) ) {

	/**
	 * Print "time ago" for post_created
	 * @return string
	 */
	function digitalnomad_get_the_date_ago() {
		$code		 = '';
		$published	 = get_the_date( 'U' );
		$date_format = get_option( 'date_format' );
		$ago		 = sprintf( _x( '%s ago', '%s = human-readable time difference', 'digital-nomad' ), human_time_diff( $published, current_time( 'timestamp' ) ) );
		$code		 .= '<time class="published" datetime="' . date( 'c', $published ) . '" title="' . date( $date_format, $published ) . '">' . $ago . '</time>';

		return $code;
	}

}

if ( !function_exists( 'digitalnomad_post_preview_layout' ) ) {

	/**
	 * Renders posts preview in archives
	 * @return string
	 */
	function digitalnomad_post_preview_layout() {

		if ( !has_excerpt() ) {
			$excerpt = wp_trim_words( wp_strip_all_tags( get_the_content( '' ), true ), 22 );
		} else {
			$excerpt = trim( wp_strip_all_tags( get_the_excerpt(), true ) );
		}

		if ( has_post_thumbnail() ) {

			$feature_image_arr	 = wp_get_attachment_image_src( get_post_thumbnail_id(), 'large' );
			$feature_image_src	 = $feature_image_arr[ 0 ];
		} elseif ( get_theme_mod( 'digitalnomad_default_feature_image' ) ) {

			$feature_image_arr	 = wp_get_attachment_image_src( get_theme_mod( 'digitalnomad_default_feature_image' ), 'large' );
			$feature_image_src	 = $feature_image_arr[ 0 ];
		} else {
			$feature_image_src = null; // because digitalnomad_default_feature_image may not be set
		}

		$style = !empty( $feature_image_src ) ? 'background-image: url(\'' . $feature_image_src . '\');' : '';

		$layout = '';

		if ( get_theme_mod( 'digitalnomad_blazy_lazyload', true ) ) {
			$layout	 .= '<article id="post_' . get_the_ID() . '" class="' . implode( ' ', get_post_class( 'preview_content basic_bg b-lazy' ) ) . '" data-src="' . $feature_image_src . '">' . PHP_EOL;
			$layout	 .= '<noscript><style>#post_' . get_the_ID() . ' {' . $style . '}</style></noscript>' . PHP_EOL;
		} else {
			$layout .= '<article class="preview_content basic_bg" style="' . $style . '">' . PHP_EOL;
		}
		$layout	 .= ' <a href="' . esc_url( get_the_permalink() ) . '" title="' . esc_attr( $excerpt ) . '">' . PHP_EOL;
		$layout	 .= '  <div class="preview_inside">' . PHP_EOL;
		$layout	 .= '   <div class="preview_time main_time text_shadow">' . digitalnomad_get_the_date_ago() . '</div>' . PHP_EOL;
		$layout	 .= '   <h2 class="preview_title main_title text_shadow">' . esc_html( get_the_title() ) . '</h2>' . PHP_EOL;
		$layout	 .= '  </div>' . PHP_EOL;
		$layout	 .= ' </a>' . PHP_EOL;
		$layout	 .= '</article>' . PHP_EOL;

		return $layout;
	}

}
# content functions end #
# navigation #
if ( !function_exists( 'digitalnomad_get_random_post_object' ) ) {

	/**
	 * Return post object for random article for next/prev article navigation
	 * @return type
	 */
	function digitalnomad_get_random_post_object( $not_in ) {
		$args = array(
			'orderby'		 => 'rand',
			'posts_per_page' => 1,
			'post__not_in'	 => $not_in,
		);

		$random_post = get_posts( $args );
		return $random_post[ 0 ];
	}

}

if ( !function_exists( 'digitalnomad_pager_bg_image_src' ) ) {

	/**
	 * Prints src of background image for pager
	 * @param type $post
	 */
	function digitalnomad_pager_bg_image_src( $post = null, $direction ) {
		// if is requested for post which has feature image
		if ( !is_null( $post ) ) {
			$bg_src = get_the_post_thumbnail_url( $post, 'large' );
			if ( !empty( $bg_src ) ) {
				return $bg_src;
			}
		}
		// if post doesn't have feature image or requester for pager
		$attach_id = $direction == 'prev' ? get_theme_mod( 'digitalnomad_previous_bg' ) : get_theme_mod( 'digitalnomad_next_bg' );
		if ( !empty( $attach_id ) ) {
			$bg_src_arr = wp_get_attachment_image_src( $attach_id, 'large' );
			if ( !empty( $bg_src_arr[ 0 ] ) ) {
				return $bg_src_arr[ 0 ];
			}
		}
		// if no default bg for previous or next pager is set use default feature image
		$attach_id = get_theme_mod( 'digitalnomad_default_feature_image' );
		if ( !empty( $attach_id ) ) {
			$bg_src_arr = wp_get_attachment_image_src( $attach_id, 'large' );
			if ( !empty( $bg_src_arr[ 0 ] ) ) {
				return $bg_src_arr[ 0 ];
			}
		} else {
			// if no default feature image show empty bg
			return '';
		}
	}

}
if ( !function_exists( 'digitalnomad_get_previous_post' ) ) {

	function digitalnomad_get_previous_post() {
		return get_previous_post();
	}

}
if ( !function_exists( 'digitalnomad_get_next_post' ) ) {

	function digitalnomad_get_next_post() {
		return get_next_post();
	}

}
if ( !function_exists( 'digitalnomad_the_posts_navigation' ) ) {

	/**
	 * Navigation in post itself
	 * @return type
	 */
	function digitalnomad_the_posts_navigation() {
		$code = '';

		// previous post logic
		$previous_post	 = digitalnomad_get_previous_post();
		$next_post		 = digitalnomad_get_next_post();
		// die if there is only one post
		if ( empty( $previous_post ) && empty( $next_post ) ) {
			return null;
		}
		if ( empty( $previous_post ) ) {
			$not_in			 = array( get_the_ID(), $next_post->ID );
			$previous_post	 = digitalnomad_get_random_post_object( $not_in );
		}
		if ( empty( $next_post ) ) {
			$not_in		 = array( get_the_ID(), $previous_post->ID );
			$next_post	 = digitalnomad_get_random_post_object( $not_in );
		}
		// if one of them still empty, show only one pager item
		$single_pager = $previous_post && $next_post ? 'no' : 'yes';


		$code	 .= '<nav class="post_nav single-' . $single_pager . '">';
		// prev
		$code	 .= '<div class="preview_content basic_bg" style="background-image: url(\'' . digitalnomad_pager_bg_image_src( $previous_post, 'prev' ) . '\');">';
		$code	 .= '<a href="' . esc_url( get_the_permalink( $previous_post ) ) . '" title="' . $previous_post->post_title . '" class="post_nav_link post_nav_prev text_shadow" rel="prev">';
		$code	 .= '<div class="preview_inside">';
		$code	 .= $previous_post->post_title;
		$code	 .= '</div>';
		$code	 .= '</a>';
		$code	 .= '</div>';
		// next
		$code	 .= '<div class="preview_content basic_bg" style="background-image: url(\'' . digitalnomad_pager_bg_image_src( $next_post, 'next' ) . '\');">';
		$code	 .= '<a href="' . esc_url( get_the_permalink( $next_post ) ) . '" title="' . $next_post->post_title . '" class="post_nav_link post_nav_next text_shadow" rel="next">';
		$code	 .= '<div class="preview_inside">';
		$code	 .= $next_post->post_title;
		$code	 .= '</div>';
		$code	 .= '</a>';
		$code	 .= '</div>';

		$code .= '</nav>';

		echo $code;
	}

}

if ( !function_exists( 'digitalnomad_posts_nav_link' ) ) {

	/**
	 * Theme navigation for posts archives
	 */
	function digitalnomad_posts_nav_link() {

		$previous_posts_link = get_previous_posts_link( '<div class="preview_inside">' . __( '&laquo; Previous Page', 'digital-nomad' ) . '</div>' );
		$next_posts_link	 = get_next_posts_link( '<div class="preview_inside">' . __( 'Next Page &raquo;', 'digital-nomad' ) . '</div>' );
		$code				 = '';

		if ( $previous_posts_link || $next_posts_link ) :

			$single_pager = $previous_posts_link && $next_posts_link ? 'no' : 'yes';

			$code .= '<nav class="post_nav single-' . $single_pager . '">';

			if ( $previous_posts_link ) :
				$code	 .= '<div class="preview_content basic_bg" style="background-image: url(\'' . digitalnomad_pager_bg_image_src( null, 'prev' ) . '\');">';
				$code	 .= $previous_posts_link;
				$code	 .= '</div>';

			endif;

			if ( $next_posts_link ) :
				$code	 .= '<div class="preview_content basic_bg" style="background-image: url(\'' . digitalnomad_pager_bg_image_src( null, 'next' ) . '\');">';
				$code	 .= $next_posts_link;
				$code	 .= '</div>';

			endif;

			$code .= '</nav>';

		endif;

		if ( get_theme_mod( 'digitalnomad_infinite_scroll', true ) ) :
			return '<noscript>' . $code . '</noscript>';
		else:
			return $code;
		endif;
	}

}

if ( !function_exists( 'digitalnomad_next_posts_link_attributes' ) ) {

	/**
	 * Add class and rel attribute to the next pager link of general (posts) pager
	 * @return string
	 */
	function digitalnomad_next_posts_link_attributes() {
		return 'class="post_nav_link post_nav_next text_shadow" rel="next"';
	}

}
add_filter( 'next_posts_link_attributes', 'digitalnomad_next_posts_link_attributes' );

if ( !function_exists( 'digitalnomad_previous_posts_link_attributes' ) ) {

	/**
	 * Add class and rel attribute to the previous pager link of general (posts) pager
	 * @return string
	 */
	function digitalnomad_previous_posts_link_attributes() {
		return 'class="post_nav_link post_nav_prev text_shadow" rel="prev"';
	}

}
add_filter( 'previous_posts_link_attributes', 'digitalnomad_previous_posts_link_attributes' );
# navigation ends #
# wp_footer and wp_head #
if ( !function_exists( 'staff_for_footer' ) ) {

	/**
	 * Adds workflow around theme scripts etc
	 */
	function staff_for_footer() {
		if ( get_theme_mod( 'digitalnomad_infinite_scroll', true ) && (is_archive() || is_home()) ) {
			?>

			<script>
				var postUrl = '<?php echo admin_url( 'admin-ajax.php' ); ?>';
				var postTypeObject = '<?php echo get_archive_post_type_object(); // includes/ajax-functions.php ?>';
				var whatKind = '<?php echo digitalnomad_get_what_kind_details(); // includes/ajax-functions.php ?>';
				var rescrollMsg = '<?php _e( 'Rescroll to load news posts.', 'digital-nomad' ); ?>';
				var noMorePostsMsg = '<div><?php _e( 'No more posts', 'digital-nomad' ); ?></div>';
				var spinnerHTML = '<div class="hcs"><div class="cir cir-1"></div><div class="cir cir-2"></div></div>';
				var unexpectedMsg = '<?php _e( 'Something unexpected happened with AJAX request :-/', 'digital-nomad' ); ?>';
			</script>
			<?php
		}
		if ( is_archive() || is_home() ) {
			?>
			<script>
				document.getElementById( 'spinner' ).innerHTML = spinnerHTML;
			</script>
			<?php
		}
		if ( get_theme_mod( 'digitalnomad_blazy_lazyload', true ) && (is_archive() || is_home() /* || is_singular() */) ) {
			?>

			<script>
				var bLazy = new Blazy( {
					offset: 500,
				} );
			</script>
			<?php
		}
	}

}

add_action( 'wp_footer', 'staff_for_footer', 999 );

if ( !function_exists( 'header_image_for_footer' ) ) {

	/**
	 * Adds workflow around header background image
	 */
	function header_image_for_footer() {

		// header_image is always set since theme define default custom image
		$header_image_full = get_header_image();

		$header_image_data = get_theme_mod( 'header_image_data' );
		// if custom header image is set we have a header_image_data
		if ( is_array( $header_image_data ) && isset( $header_image_data[ 'attachment_id' ] ) ) {
			// find 'large' thumb url of header image for mobile devices (don't serve images larger then 1024 to mobile)
			$default_image_large_arr = wp_get_attachment_image_src( $header_image_data[ 'attachment_id' ], 'large' );
			$default_image_large	 = $default_image_large_arr[ 0 ];
		} else {
			// if no custom header image is set, then use full image version for mobile too
			$default_image_large = $header_image_full;
		}
		?>

		<style>
			#placeholder {
				/* background-image: ('custom_svg_placeholder_here') ; */
			}
			#header{
				background-image: url('<?php echo $default_image_large; ?>');
			}
			@media (min-width: 480px) {
				#header {
					background-image: url('<?php echo $header_image_full; ?>');
				}
			}
		</style>
		<?php
	}

}

add_action( 'wp_head', 'header_image_for_footer' );

if ( !function_exists( 'footer_image_for_footer' ) ) {

	/**
	 * Adds workflow around footer background image
	 * Footer image is deffered (download on background)
	 */
	function footer_image_for_footer() {
		$footer_attach_id = get_theme_mod( 'digitalnomad_footer_image' );
		if ( !empty( $footer_attach_id ) ) {
			$footer_image_large_arr	 = wp_get_attachment_image_src( $footer_attach_id, 'large' );
			$footer_image_large	 = $footer_image_large_arr[ 0 ];
			$footer_image_full_arr	 = wp_get_attachment_image_src( $footer_attach_id, 'full' );
			$footer_image_full	 = $footer_image_full_arr[ 0 ];
			?>
			<noscript id="deferred-styles">
			<style>
				#footer {
					background-image: url('<?php echo $footer_image_large; ?>');
				}
				@media (min-width: 480px) {
					#footer {
						background-image: url('<?php echo $footer_image_full; ?>');
					}
				}
			</style>
			</noscript>
			<script>
				var loadDeferredStyles = function () {
					var addStylesNode = document.getElementById( 'deferred-styles' );
					var replacement = document.createElement( 'div' );
					replacement.innerHTML = addStylesNode.textContent;
					document.body.appendChild( replacement )
					addStylesNode.parentElement.removeChild( addStylesNode );
				};
				var raf = window.requestAnimationFrame || window.mozRequestAnimationFrame ||
					window.webkitRequestAnimationFrame || window.msRequestAnimationFrame;
				if ( raf )
					raf( function () {
						window.setTimeout( loadDeferredStyles, 0 );
					} );
				else
					window.addEventListener( 'load', loadDeferredStyles );

			</script>

			<?php
		}
	}

}

add_action( 'wp_head', 'footer_image_for_footer' );

if ( !function_exists( 'go_up_for_footer' ) ) {

	/**
	 * Adds workflow around go-up button
	 */
	function go_up_for_footer() {
		?>
		<a href="#header" class="gp" title="<?php _e( '&uarr; Up', 'digital-nomad' ); ?>"><?php _e( '&uarr; Up', 'digital-nomad' ); ?></a>
		<script>var body = document.body, stickyHeaderTop = body.offsetTop + 300;
			window.onscroll = function () {
				window.pageYOffset > stickyHeaderTop ? body.classList.add( 'scrl' ) : body.classList.remove( 'scrl' )
			};
		</script>
		<?php
	}

}

add_action( 'wp_footer', 'go_up_for_footer' );

if ( !function_exists( 'digitalnomad_basic_footer_text' ) ) {

	/**
	 * Basic footer text for theme
	 */
	function digitalnomad_basic_footer_text() {
		?>
		<p>
			<?php echo date( 'Y', time() ); ?> &copy; <?php bloginfo( 'name' ); ?>.
			<?php printf( __( 'Powered by %s.', 'digital-nomad' ), '<a href="https://wordpress.org/" target="_blank">WordPress</a>' ); ?> 
			<?php printf( __( 'Theme by %s.', 'digital-nomad' ), '<a href="https://www.jasom.net" target="_blank">Jasom Dotnet</a>' ); ?> 
		</p>
		<?php
	}

}

add_action( 'digitalnomad_for_footer', 'digitalnomad_basic_footer_text' );
# wp_footer and wp_head ends #
# post archive #
if ( !function_exists( 'digitalnomad_add_post_state' ) ) {

	/**
	 * Add 'Post Archive Page' note in admin area
	 * @param type $post_states
	 * @param type $post
	 * @return type
	 */
	function digitalnomad_add_post_state( $post_states, $post ) {
		if ( $post->ID == get_theme_mod( 'digitalnomad_archive_page', url_to_postid( 'archive' ) ) ) {
			$post_states[] = __( 'Post Archive Page', 'digital-nomad' );
		}
		return $post_states;
	}

}

add_filter( 'display_post_states', 'digitalnomad_add_post_state', 10, 2 );

if ( !function_exists( 'digitalnomad_add_post_archive_to_page' ) ) {

	/**
	 * Add some class for archive page
	 * @param string $classes
	 * @return string
	 */
	function digitalnomad_add_class_for_archive_page( $classes ) {

		if ( is_front_page() && get_option( 'show_on_front' ) == 'posts' ) {
			$classes[] = 'intended_front_page';
			return $classes;
		}

		$archive_page_id = get_theme_mod( 'digitalnomad_archive_page', url_to_postid( 'archive' ) );

		if ( !empty( $archive_page_id ) && $archive_page_id == get_the_ID() ) {
			$classes[] = 'main-archive';
		}
		return $classes;
	}

}
add_filter( 'body_class', 'digitalnomad_add_class_for_archive_page' );

if ( !function_exists( 'digitalnomad_remove_date_archives' ) ) {

	/**
	 * Remoe date archives
	 * https://codex.wordpress.org/Conditional_Tags#A_Date_Page
	 * https://stackoverflow.com/questions/44428114/how-to-remove-wordpress-date-archive-pages
	 * @global type $wp_query
	 */
	function digitalnomad_remove_date_archives() {

		//if we are on date archive page
		if ( is_date() ) {
			$archive_page = get_theme_mod( 'digitalnomad_archive_page', url_to_postid( 'archive' ) );
			if ( $archive_page ) {
				wp_redirect( esc_url( get_page_link( $archive_page ) ) );
				die();
			} else {
				global $wp_query;
				$wp_query->set_404();
			}
		}
	}

}

add_action( 'template_redirect', 'digitalnomad_remove_date_archives' );

if ( !function_exists( 'digitalnomad_add_post_archive_to_page' ) ) {

	/**
	 * HTML for post archive
	 */
	function digitalnomad_add_post_archive_to_page() {

		if ( is_page() ) {

			$archive_page_id = get_theme_mod( 'digitalnomad_archive_page', url_to_postid( 'archive' ) );

			if ( !empty( $archive_page_id ) && $archive_page_id == get_the_ID() ) {

				do_action( 'post_archive_before' );

				get_search_form();

				$the_query = new WP_Query( array( 'posts_per_page' => '-1', ) );

				if ( $the_query->have_posts() ) {


					while ( $the_query->have_posts() ) {
						$the_query->the_post();

						$archive[ get_the_date( 'Y' ) ][ get_the_ID() ][ 'title' ]		 = esc_attr( get_the_title() );
						$archive[ get_the_date( 'Y' ) ][ get_the_ID() ][ 'link' ]		 = esc_url( get_the_permalink() );
						$archive[ get_the_date( 'Y' ) ][ get_the_ID() ][ 'post_date' ]	 = date_i18n( 'd. M', get_the_date( 'U' ) );
					}

					digitalnomad_the_archive_table( $archive );
				} else {

					echo '<p>' . __( 'There are no posts.', 'digital-nomad' ) . '</p>' . PHP_EOL;
				}


				/* Restore original Post Data */
				wp_reset_postdata();

				do_action( 'post_archive_after' );
			}
		}
	}

}

add_action( 'digitalnomad_inner_content_ends', 'digitalnomad_add_post_archive_to_page' );

if ( !function_exists( 'digitalnomad_the_archive_table' ) ) {

	/**
	 * Print archive table itself + table for search results
	 * $data - array data for dable
	 * $category - show category in archived table
	 * $before - html code before yhe table
	 * $after - html code after the table
	 */
	function digitalnomad_the_archive_table( $data, $category = true, $before = null, $after = null ) {

		echo $before ? $before . PHP_EOL : PHP_EOL;
		echo '<div class="archive_table">' . PHP_EOL;



		foreach ( $data as $year => $posts ) {



			echo PHP_EOL . '<div class="archive_row"><div class="archive_year archive_cell"><h3 class="archive_year_title">' . $year . '</h3></div></div>' . PHP_EOL . PHP_EOL;

			foreach ( $posts as $post_id => $post ) {

				echo '<div class="archive_row">' . PHP_EOL;

				echo ' <div class="archive_date archive_cell">' . $post[ 'post_date' ] . '</div><!-- /archive_date -->' . PHP_EOL;

				if ( $category ) {

					echo ' <div class="archive_categoty archive_cell">';
					$cats	 = null;
					$cats	 = get_the_category( $post_id );
					if ( !empty( $cats ) ) {
						$cats_html = array();
						foreach ( $cats as $cat ) {
							$cats_html[] = '<a href="' . get_category_link( $cat->term_id ) . '">' . get_cat_name( $cat->term_id ) . '</a>';
						}
						echo implode( ', ', $cats_html );
					}
					echo '</div><!-- /archive_categoty -->' . PHP_EOL;
				}

				echo ' <div class="archive_post archive_cell"><a href="' . $post[ 'link' ] . '" title="' . $post[ 'title' ] . '">' . $post[ 'title' ] . '</a></div><!-- /archive_post -->' . PHP_EOL;

				echo '</div><!-- /archive_row -->' . PHP_EOL;
			}
		}
		echo '</div><!-- /archive_table -->' . PHP_EOL;
		echo $after ? $after . PHP_EOL : PHP_EOL;
	}

}

if ( !function_exists( 'digitalnomad_get_latest_article' ) ) {

	/**
	 * Returns latest post for 404.php page
	 */
	function digitalnomad_get_latest_article() {
		$args = array(
			'numberposts'		 => 1,
			'post_type'			 => 'post',
			'post_status'		 => 'publish',
			'suppress_filters'	 => true
		);

		$recent_posts = wp_get_recent_posts( $args, ARRAY_A );


		foreach ( $recent_posts as $post ) {
			$code = '<a href="' . esc_url( get_permalink( $post[ 'ID' ] ) ) . '">' . $post[ 'post_title' ] . '</a>';
		}
		wp_reset_query();
		return $code;
	}

}
if ( !function_exists( 'adds_search_form_to_search_page' ) ) {

	/**
	 * Adds search form to search page
	 */
	function adds_search_form_to_search_page() {
		if ( is_search() ) {
			?>
			<div class="inner_content pb0"><?php
				get_search_form();
				?>
			</div>
			<?php
		}
	}

}
add_action( 'digitalnomad_after_inner_content_opening', 'adds_search_form_to_search_page' );

if ( !function_exists( 'digitalnomad_change_search_posts_per_page' ) ) {

	/**
	 * Edits query for search, returns all results on one page
	 * Code style: C
	 * @param type $query
	 * @return type
	 */
	function digitalnomad_change_search_posts_per_page( $query ) {
		if ( $query->is_search )
			$query->query_vars[ 'posts_per_page' ] = -1;

		return $query;
	}

}

add_filter( 'pre_get_posts', 'digitalnomad_change_search_posts_per_page' );

# post archive ends #
# comments #
add_action( 'comment_form_before', function() {
	echo '<div class="outher_respond">';
} );
add_action( 'comment_form_after', function() {
	echo '</div><!-- /respond_wrap -->';
} );

if ( !class_exists( 'Digitalnomad_Walker_Comment' ) ) {

	/**
	 * Custom comment walker
	 *
	 * @users Walker_Comment
	 */
	class Digitalnomad_Walker_Comment extends Walker_Comment {

		public function html5_comment( $comment, $depth, $args ) {

			$tag = ( 'div' === $args[ 'style' ] ) ? 'div' : 'li';

			$commenter = wp_get_current_commenter();
			if ( $commenter[ 'comment_author_email' ] ) {
				$moderation_note = __( 'Your comment is awaiting moderation.', 'digital-nomad' );
			} else {
				$moderation_note = __( 'Your comment is awaiting moderation. This is a preview, your comment will be visible after it has been approved.', 'digital-nomad' );
			}
			?>
			<<?php echo $tag; ?> id="comment-<?php comment_ID(); ?>" <?php comment_class( $this->has_children ? 'parent' : '', $comment ); ?>>
			<article id="div-comment-<?php comment_ID(); ?>" class="comment-body">

				<header class="comment-meta">
					<h4 class="comment-author time_permalink">
						<?php
						printf( __( '%s <span class="says">said on</span>', 'digital-nomad' ), sprintf( '<b class="fn">%s</b>', get_comment_author_link( $comment ) ) );
						?>	

						<a href="<?php echo esc_url( get_comment_link( $comment, $args ) ); ?>">
							<time datetime="<?php comment_time( 'c' ); ?>">
								<?php
								/* translators: 1: Comment date, 2: Comment time. */
								printf( __( '%1$s at %2$s', 'digital-nomad' ), get_comment_date( '', $comment ), get_comment_time() );
								?>
							</time>
						</a>
					</h4><!-- .comment-author -->

					<?php if ( '0' == $comment->comment_approved ) : ?>
						<div class="comment-awaiting-moderation"><em><?php echo $moderation_note; ?></em></div>
					<?php endif; ?>
				</header><!-- .comment-meta -->

				<div class="comment-content">
					<?php comment_text(); ?>
				</div><!-- .comment-content -->

				<footer class="comment-metadata">
					<?php do_action( 'comment_metadata_before' ); ?>
					<?php
					comment_reply_link( array_merge( $args, array(
						'add_below'	 => 'div-comment',
						'depth'		 => $depth,
						'max_depth'	 => $args[ 'max_depth' ],
						'before'	 => '<span class="reply">',
						'after'		 => '</span>',
					) ) );
					?>
					<?php edit_comment_link( __( 'Edit', 'digital-nomad' ), '<span class="edit">', '</span>' ); ?>
					<?php do_action( 'comment_metadata_after' ); ?>
				</footer><!-- .comment-metadata -->

			</article><!-- .comment-body -->
			<?php
		}

	}

}
# comments end #
# custom login starts #

if ( !function_exists( 'digitalnomad_login_logo' ) ) {

	/**
	 * Custom login logo if set
	 */
	function digitalnomad_login_logo() {

		$custom_logo_id = get_theme_mod( 'custom_logo' );
		if ( !empty( $custom_logo_id ) ) {
			?>
			<style type="text/css">
				#login h1 a, .login h1 a {
					background-image: url('<?php echo wp_get_attachment_image_url( $custom_logo_id, 'medium' ); ?>');
					background-size: 84px 84px;
					height: 84px;
					width: 84px;
					border-radius: 50%;
				}
			</style>
			<?php
		}
	}

}

add_action( 'login_enqueue_scripts', 'digitalnomad_login_logo' );

if ( !function_exists( 'digitalnomad_login_logo_url' ) ) {

	/**
	 * Links to homepage on wp-login.php
	 * @return type
	 */
	function digitalnomad_login_logo_url() {
		return home_url();
	}

}

add_filter( 'login_headerurl', 'digitalnomad_login_logo_url' );

if ( !function_exists( 'digitalnomad_login_logo_title' ) ) {

	/**
	 * Improves <h1> title on wp-login.php
	 * @return type
	 */
	function digitalnomad_login_logo_title() {
		return get_bloginfo( 'name' );
	}

}

add_filter( 'login_headertext', 'digitalnomad_login_logo_title' );
# custom login ends #