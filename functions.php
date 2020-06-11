<?php
/**
 * Theme setup functions
 */
require_once get_template_directory() . '/inc/theme-setup.php';
/**
 * Customizer functions
 */
require_once get_template_directory() . '/inc/customizer.php';
/**
 * Theme info page + activation staff
 */
require_once get_template_directory() . '/inc/theme-panel.php';
/**
 * AJAX functions
 */
require_once get_template_directory() . '/inc/ajax-functions.php';

// enqueues #
if (!function_exists( 'digitalnomad_add_styles' )) {

    /**
     * Styles
     */
    function digitalnomad_add_styles() {
        wp_enqueue_style( 'digital-nomad', get_template_directory_uri() . '/style.css', array(), filemtime( dirname( __FILE__ ) . '/style.css' ) );
        //wp_enqueue_style( 'digital-nomad', get_template_directory_uri() . '/style-empty.css' ); // uncomment for development purposes
        wp_add_inline_style( 'digital-nomad', digitalnomad_header_image_background() );

    }

}

add_action( 'wp_print_styles', 'digitalnomad_add_styles' );

/**
 * Uncomment for development purposes
 */
/*add_action( 'wp_head', function() {
    echo '<style>' . PHP_EOL;
    include 'style.css';
    echo '</style>' . PHP_EOL;
} );*/

if (!function_exists( 'digitalnomad_header_image_background' )) {

    /**
     * Adds workflow around styles of header background image
     */
    function digitalnomad_header_image_background() {
        $code = null;
        // header_image is always set since theme define default custom image
        $header_image_full = get_header_image();
        $header_image_data = get_theme_mod( 'header_image_data' );
        // if custom header image is set we have a header_image_data
        if (is_array( $header_image_data ) && isset( $header_image_data[ 'attachment_id' ] )) {
            // find 'large' thumb url of header image for mobile devices (don't serve images larger then 1024 to mobile)
            $default_image_large_arr = wp_get_attachment_image_src( $header_image_data[ 'attachment_id' ], 'large' );
            $default_image_large = $default_image_large_arr[ 0 ];
        } else {
            // if no custom header image is set, then use full image version for mobile too
            $default_image_large = $header_image_full;
        }
        // get layout width
        $layout_width = get_theme_mod( 'digitalnomad_layout_width', 56 );

        $code .= digitalnomad_custom_svg_placeholder_for_header() . PHP_EOL;
        $code .= '#header {background-image: url("' . esc_url( $default_image_large ) . '");}' . PHP_EOL;
        $code .= '@media (min-width: 480px) {#header {background-image: url("' . esc_url( $header_image_full ) . '");}}' . PHP_EOL;
        $code .= '.inner_content, #respond, .comment-body {max-width: ' . esc_attr( $layout_width ) . 'rem}';

        return $code;

    }

}

if (!function_exists( 'digitalnomad_custom_svg_placeholder_for_header' )) {

    /**
     * Add easily custom SVG placeholder for header using child theme
     * See https://yoksel.github.io/url-encoder/
     */
    function digitalnomad_custom_svg_placeholder_for_header() {
        return '/* #placeholder {background-image: [custom_svg_placeholder_here]}*/';

    }

}

if (!function_exists( 'digitalnomad_footer_image_background' )) {

    /**
     * Adds workflow around footer background image
     * Start downloading on background after "onload" event for better performance
     * See main.js for deferred-style manipulation
     */
    function digitalnomad_footer_image_background() {
        $footer_attach_id = get_theme_mod( 'digitalnomad_footer_image' );
        if (!empty( $footer_attach_id )) {
            $footer_image_large_arr = wp_get_attachment_image_src( $footer_attach_id, 'large' );
            $footer_image_large = $footer_image_large_arr[ 0 ];
            $footer_image_full_arr = wp_get_attachment_image_src( $footer_attach_id, 'full' );
            $footer_image_full = $footer_image_full_arr[ 0 ];

            ?>
            <noscript id="deferred-styles">
            <style>
                #footer {background-image: url('<?php echo esc_url( $footer_image_large ); ?>');}
                @media (min-width: 480px) {#footer {background-image: url('<?php echo esc_url( $footer_image_full ); ?>');}}
            </style>
            </noscript>
            <?php
        }

    }

}

add_action( 'wp_footer', 'digitalnomad_footer_image_background', -1 );

if (!function_exists( 'digitalnomad_add_scripts' )) {

    /**
     * Adds required scripts to theme
     * https://rudrastyh.com/wordpress/include-css-and-javascript.html#cached-css-and-js
     */
    function digitalnomad_add_scripts() {

        $theme_version = wp_get_theme()->get( 'Version' );

        if (get_theme_mod( 'digitalnomad_blazy_lazyload', true ) && ( is_archive() || is_home() )) {
            wp_enqueue_script( 'blazy-lazyload', get_template_directory_uri() . '/js/blazy.min.js', null, $theme_version, true );
            wp_add_inline_script( 'blazy-lazyload', 'var bLazy = new Blazy({offset:500,});' );
        }

        wp_enqueue_script( 'digital-nomad', get_template_directory_uri() . '/js/main.min.js', null, $theme_version, true );
        $config = digitalnomad_config_for_main_js();
        if ($config) {
            wp_add_inline_script( 'digital-nomad', $config );
        }

        if (is_singular() && comments_open() && ( get_option( 'thread_comments' ) == 1 )) {
            wp_enqueue_script( 'comment-reply', '/wp-includes/js/comment-reply.min.js', null, get_bloginfo( 'version' ), true );
        }

    }

}

add_action( 'wp_enqueue_scripts', 'digitalnomad_add_scripts' );

if (!function_exists( 'digitalnomad_config_for_main_js' )) {

    /**
     * Adds configuration for main javascript
     */
    function digitalnomad_config_for_main_js() {
        $code = null;
        if (get_theme_mod( 'digitalnomad_infinite_scroll', true ) && ( is_archive() || is_home() )) {

            $code .= 'var postUrl = "' . admin_url( 'admin-ajax.php' ) . '";' . PHP_EOL;
            $code .= 'var ajaxNonce = "' . wp_create_nonce( 'ajax-nonce' ) . '";' . PHP_EOL;
            $code .= 'var postTypeObject = "' . digitalnomad_get_archive_post_type_object() . '";' . PHP_EOL; // includes/ajax-functions.php
            $code .= 'var whatKind = "' . digitalnomad_get_what_kind_details() . '";' . PHP_EOL; // includes/ajax-functions.php
            $code .= 'var rescrollMsg = "' . __( 'Rescroll to load next posts.', 'digital-nomad' ) . '";' . PHP_EOL;
            $code .= 'var noMorePostsMsg = "<div>' . __( 'That\'s all', 'digital-nomad' ) . '</div>";' . PHP_EOL;
            $code .= 'var spinnerHTML = "<div class=\'hcs\'><div class=\'cir cir-1\'></div><div class=\'cir cir-2\'></div></div>";' . PHP_EOL;
            $code .= 'var unexpectedMsg = "' . __( 'Something unexpected happened with AJAX request :-/', 'digital-nomad' ) . '";' . PHP_EOL;
            $code .= 'document.getElementById("spinner").innerHTML = spinnerHTML;' . PHP_EOL;
        }
        return $code;

    }

}
// enqueues ends #
// content functions #
if (!function_exists( 'digitalnomad_the_date' )) {

    /**
     * Custom the_date function
     */
    function digitalnomad_the_date() {
        echo digitalnomad_get_the_date(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

    }

}

if (!function_exists( 'digitalnomad_get_the_date' )) {

    /**
     * Custom get_the_date function
     */
    function digitalnomad_get_the_date() {
        $code = '';
        $published = esc_attr( get_the_date( 'U' ) );
        $updated = esc_attr( get_the_modified_time( 'U' ) );
        $date_format = esc_attr( get_option( 'date_format' ) );

        $code .= '<time class="published" datetime="' . date( 'c', $published ) . '">' . date( $date_format, $published ) . '</time>';
        if ($published != $updated) {
            $code .= ', <span class="last_update_text">' . __( 'updated:', 'digital-nomad' ) . '</span> <time class="updated" datetime="' . date( 'c', $updated ) . '">' . date_i18n( $date_format, $updated ) . '</time>';
        }
        return $code;

    }

}

if (!function_exists( 'digitalnomad_get_the_date_ago' )) {

    /**
     * Print "time ago" for post_created
     *
     * @return string
     */
    function digitalnomad_get_the_date_ago() {
        $code = '';
        $published = get_the_date( 'U' );
        $date_format = get_option( 'date_format' );
        /* translators: %s: time difference in human-readable way */
        $ago = sprintf( esc_html__( '%s ago', 'digital-nomad' ), human_time_diff( $published, current_time( 'timestamp' ) ) );
        $code .= '<time class="published" datetime="' . date( 'c', $published ) . '" title="' . date( $date_format, $published ) . '">' . $ago . '</time>';

        return $code;

    }

}

if (!function_exists( 'digitalnomad_post_preview_layout' )) {

    /**
     * Renders posts preview in archives
     *
     * @return string
     */
    function digitalnomad_post_preview_layout() {

        if (!has_excerpt()) {
            $excerpt = wp_trim_words( wp_strip_all_tags( get_the_content( '' ), true ), 22 );
        } else {
            $excerpt = trim( wp_strip_all_tags( get_the_excerpt(), true ) );
        }

        if (has_post_thumbnail()) {

            $feature_image_arr = wp_get_attachment_image_src( get_post_thumbnail_id(), 'large' );
            $feature_image_src = $feature_image_arr[ 0 ];
        } elseif (get_theme_mod( 'digitalnomad_default_feature_image' )) {

            $feature_image_arr = wp_get_attachment_image_src( get_theme_mod( 'digitalnomad_default_feature_image' ), 'large' );
            $feature_image_src = $feature_image_arr[ 0 ];
        } else {
            $feature_image_src = null; // because digitalnomad_default_feature_image may not be set
        }

        $style = !empty( $feature_image_src ) ? 'background-image: url(\'' . esc_url( $feature_image_src ) . '\');' : '';

        $layout = '';

        if (get_theme_mod( 'digitalnomad_blazy_lazyload', true )) {
            $layout .= '<article id="post_' . get_the_ID() . '" class="' . implode( ' ', get_post_class( 'preview_content basic_bg b-lazy' ) ) . '" data-src="' . $feature_image_src . '">' . PHP_EOL;
            $layout .= '<noscript><style>#post_' . get_the_ID() . ' {' . $style . '}</style></noscript>' . PHP_EOL;
        } else {
            $layout .= '<article class="' . implode( ' ', get_post_class( 'preview_content basic_bg' ) ) . '" style="' . $style . '">' . PHP_EOL;
        }
        $layout .= ' <a href="' . esc_url( get_the_permalink() ) . '" title="' . esc_attr( $excerpt ) . '">' . PHP_EOL;
        $layout .= '  <div class="preview_inside">' . PHP_EOL;
        $layout .= '   <div class="preview_time main_time text_shadow">' . digitalnomad_get_the_date_ago() . '</div>' . PHP_EOL;
        $layout .= '   <h2 class="preview_title main_title text_shadow">' . esc_html( get_the_title() ) . '</h2>' . PHP_EOL;
        $layout .= '  </div>' . PHP_EOL;
        $layout .= ' </a>' . PHP_EOL;
        $layout .= '</article>' . PHP_EOL;

        return $layout;

    }

}
// content functions end #
// navigation #
if (!function_exists( 'digitalnomad_wp_link_pages' )) {

    /**
     * Prints pager when page paginated
     */
    function digitalnomad_wp_link_pages( $not_in ) {
        wp_link_pages(
                array(
                    'before' => '<div class="paginated"><div class="page-link-text">' . __( 'More pages: ', 'digital-nomad' ) . '</div>',
                    'after' => '</div>',
                    'link_before' => '<span class="page-link">',
                    'link_after' => '</span>',
                    'separator' => ' | ',
                )
        );

    }

}
add_action( 'digitalnomad_after_content_itself', 'digitalnomad_wp_link_pages' );

if (!function_exists( 'digitalnomad_get_random_post_object' )) {

    /**
     * Return post object for random article for next/prev article navigation
     *
     * @return type
     */
    function digitalnomad_get_random_post_object( $not_in ) {
        $args = array(
            'orderby' => 'rand',
            'posts_per_page' => 1,
            'post__not_in' => $not_in,
        );
        // if polylang enabled
        if (function_exists( 'pll_current_language' )) {
            $args[ 'lang' ] = pll_current_language();
        }

        $random_post = get_posts( $args );
        return $random_post[ 0 ];

    }

}

if (!function_exists( 'digitalnomad_pager_bg_image_src' )) {

    /**
     * Prints src of background image for pager
     *
     * @param type    $post
     */
    function digitalnomad_pager_bg_image_src( $post = null, $direction ) {
        // if is requested for post which has feature image
        if (!is_null( $post )) {
            $bg_src = get_the_post_thumbnail_url( $post, 'large' );
            if (!empty( $bg_src )) {
                return $bg_src;
            }
        }
        // if the post doesn't have feature image or function requested for archive pager
        $attach_id = $direction == 'prev' ? get_theme_mod( 'digitalnomad_previous_bg' ) : get_theme_mod( 'digitalnomad_next_bg' );
        if (!empty( $attach_id )) {
            $bg_src_arr = wp_get_attachment_image_src( $attach_id, 'large' );
            if (!empty( $bg_src_arr[ 0 ] )) {
                return $bg_src_arr[ 0 ];
            }
        }
        // if no default bg for previous or next pager is set use default feature image
        $attach_id = get_theme_mod( 'digitalnomad_default_feature_image' );
        if (!empty( $attach_id )) {
            $bg_src_arr = wp_get_attachment_image_src( $attach_id, 'large' );
            if (!empty( $bg_src_arr[ 0 ] )) {
                return $bg_src_arr[ 0 ];
            }
        }
        // if no default feature image show empty bg
        return '';

    }

}
if (!function_exists( 'digitalnomad_get_previous_post' )) {

    /**
     * Easy edit in child theme
     * @return type
     */
    function digitalnomad_get_previous_post() {
        return get_previous_post();

    }

}
if (!function_exists( 'digitalnomad_get_next_post' )) {

    /**
     * Easy edit in child theme
     * @return type
     */
    function digitalnomad_get_next_post() {
        return get_next_post();

    }

}
if (!function_exists( 'digitalnomad_the_posts_navigation' )) {

    /**
     * Navigation in post itself
     *
     * @return type
     */
    function digitalnomad_the_posts_navigation() {
        $code = '';

        // previous post logic
        $previous_post = digitalnomad_get_previous_post();
        $next_post = digitalnomad_get_next_post();
        // die if blog has only one post
        if (empty( $previous_post ) && empty( $next_post )) {
            return null;
        }
        if (empty( $previous_post )) {
            $not_in = array(get_the_ID(), $next_post->ID);
            $previous_post = digitalnomad_get_random_post_object( $not_in );
        }
        if (empty( $next_post )) {
            $not_in = array(get_the_ID(), $previous_post->ID);
            $next_post = digitalnomad_get_random_post_object( $not_in );
        }
        // if one of them still empty, show only one pager item
        $single_pager = $previous_post && $next_post ? 'no' : 'yes';

        $code .= '<nav class="post_nav single-' . $single_pager . '">';
        // prev
        $prev_title = empty( $previous_post->post_title ) ? digitalnomad_notitle() : $previous_post->post_title;

        $code .= '<div class="preview_content basic_bg" style="background-image: url(\'' . esc_url( digitalnomad_pager_bg_image_src( $previous_post, 'prev' ) ) . '\');">';
        $code .= '<a href="' . esc_url( get_the_permalink( $previous_post ) ) . '" title="' . esc_attr__( 'Previous post:', 'digital-nomad' ) . ' ' . esc_attr( $prev_title ) . '" class="post_nav_link post_nav_prev text_shadow" rel="prev">';
        $code .= '<div class="preview_inside">&laquo; ' . esc_html( $prev_title ) . '</div>';
        $code .= '</a>';
        $code .= '</div>';
        // next
        $next_title = empty( $next_post->post_title ) ? digitalnomad_notitle() : $next_post->post_title;

        $code .= '<div class="preview_content basic_bg" style="background-image: url(\'' . esc_url( digitalnomad_pager_bg_image_src( $next_post, 'next' ) ) . '\');">';
        $code .= '<a href="' . esc_url( get_the_permalink( $next_post ) ) . '" title="' . esc_attr__( 'Next post:', 'digital-nomad' ) . ' ' . esc_attr( $next_title ) . '" class="post_nav_link post_nav_next text_shadow" rel="next">';
        $code .= '<div class="preview_inside">' . esc_html( $next_title ) . ' &raquo;</div>';
        $code .= '</a>';
        $code .= '</div>';

        $code .= '</nav>';

        echo $code; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

    }

}

if (!function_exists( 'digitalnomad_posts_nav_link' )) {

    /**
     * Theme navigation for posts archives
     */
    function digitalnomad_posts_nav_link() {

        $previous_posts_link = get_previous_posts_link( '<div class="preview_inside">' . __( '&laquo; Previous Page', 'digital-nomad' ) . '</div>' );
        $next_posts_link = get_next_posts_link( '<div class="preview_inside">' . __( 'Next Page &raquo;', 'digital-nomad' ) . '</div>' );
        $code = '';

        if ($previous_posts_link || $next_posts_link) :

            $single_pager = $previous_posts_link && $next_posts_link ? 'no' : 'yes';

            $code .= '<nav class="post_nav single-' . $single_pager . '">';

            if ($previous_posts_link) :
                $code .= '<div class="preview_content basic_bg" style="background-image: url(\'' . esc_url( digitalnomad_pager_bg_image_src( null, 'prev' ) ) . '\');">';
                $code .= $previous_posts_link;
                $code .= '</div>';

            endif;

            if ($next_posts_link) :
                $code .= '<div class="preview_content basic_bg" style="background-image: url(\'' . esc_url( digitalnomad_pager_bg_image_src( null, 'next' ) ) . '\');">';
                $code .= $next_posts_link;
                $code .= '</div>';

            endif;

            $code .= '</nav>';

        endif;

        if (get_theme_mod( 'digitalnomad_infinite_scroll', true )) :
            return '<noscript>' . $code . '</noscript>';
        else :
            return $code;
        endif;

    }

}

if (!function_exists( 'digitalnomad_next_posts_link_attributes' )) {

    /**
     * Add class and rel attribute to the next pager link of general (posts) pager
     *
     * @return string
     */
    function digitalnomad_next_posts_link_attributes() {
        return 'class="post_nav_link post_nav_next text_shadow" rel="next"';

    }

}
add_filter( 'next_posts_link_attributes', 'digitalnomad_next_posts_link_attributes' );

if (!function_exists( 'digitalnomad_previous_posts_link_attributes' )) {

    /**
     * Add class and rel attribute to the previous pager link of general (posts) pager
     *
     * @return string
     */
    function digitalnomad_previous_posts_link_attributes() {
        return 'class="post_nav_link post_nav_prev text_shadow" rel="prev"';

    }

}
add_filter( 'previous_posts_link_attributes', 'digitalnomad_previous_posts_link_attributes' );
// navigation ends #
// wp_footer #
if (!function_exists( 'digitalnomad_go_up_for_footer' )) {

    /**
     * Adds workflow around go-up button
     */
    function digitalnomad_go_up_for_footer() {

        ?>
        <a href="#header" class="gp" title="<?php esc_attr_e( '&uarr; Up', 'digital-nomad' ); ?>"><?php esc_html_e( '&uarr; Up', 'digital-nomad' ); ?></a>
        <?php

    }

}

add_action( 'wp_footer', 'digitalnomad_go_up_for_footer' );

if (!function_exists( 'digitalnomad_basic_footer_text' )) {

    /**
     * Basic footer text for theme
     */
    function digitalnomad_basic_footer_text() {

        ?>
        <p>
            <?php echo esc_html( date( 'Y', time() ) ); ?> &copy; <?php bloginfo( 'name' ); ?>.
            <?php
            /* translators: %s: WordPress homepage link */
            printf( esc_html__( 'Powered by %s.', 'digital-nomad' ), '<a href="https://wordpress.org/" target="_blank">WordPress</a>' );

            ?>
            <?php
            /* translators: %s: credit link */
            printf( esc_html__( 'Themed by %s.', 'digital-nomad' ), '<a href="https://www.jasom.net" target="_blank">Jasom Dotnet</a>' );

            ?>
        </p>
        <?php

    }

}

add_action( 'digitalnomad_for_footer', 'digitalnomad_basic_footer_text' );

if (!function_exists( 'digitalnomad_page_is_archive' )) {

    /**
     * Figures out if current page is Post Archive Page or not
     * 
     * @staticvar integer $archive_page_id
     * @param integer $post_id
     * @return boolean True if current page is Post Archive Page, false otherwise
     */
    function digitalnomad_page_is_archive( $post_id = null ) {

        static $archive_page_id = null;

        if ($archive_page_id === null) {

            $archive_page_id = get_theme_mod( 'digitalnomad_archive_page', url_to_postid( 'archive' ) );
        }

        // if Post Archive page is set
        if (!empty( $archive_page_id )) {

            $curret_post_id = is_null( $post_id ) ? get_the_ID() : $post_id;

            // Polylang integration
            if (function_exists( 'pll_get_post' )) {
                $curret_post_id = pll_get_post( $curret_post_id, pll_default_language() );
            }
            // TODO: WPML integration

            return $curret_post_id == $archive_page_id;
        }

        return false;

    }

}

if (!function_exists( 'digitalnomad_add_post_state' )) {

    /**
     * Add 'Post Archive Page' note in admin area
     *
     * @param type    $post_states
     * @param type    $post
     * @return type
     */
    function digitalnomad_add_post_state( $post_states, $post ) {
        if (digitalnomad_page_is_archive( $post->ID )) {
            $post_states[] = __( 'Post Archive Page', 'digital-nomad' );
        }
        return $post_states;

    }

}

add_filter( 'display_post_states', 'digitalnomad_add_post_state', 10, 2 );

if (!function_exists( 'digitalnomad_add_class_for_body_tag' )) {

    /**
     * Adds additional classes to body tag
     *
     * @param string  $classes
     * @return string
     */
    function digitalnomad_add_class_for_body_tag( $classes ) {

        if (get_theme_mod( 'digitalnomad_short_about' ) == true) {
            $classes[] = 'short_about';
        }

        if (has_nav_menu( 'main' )) {
            $classes[] = 'main_menu_on';
        }

        if (is_front_page() && get_option( 'show_on_front' ) == 'posts') {
            $classes[] = 'intended_front_page';
            return $classes;
        }

        if (digitalnomad_page_is_archive()) {
            $classes[] = 'main-archive';
        }
        return $classes;

    }

}
add_filter( 'body_class', 'digitalnomad_add_class_for_body_tag' );

if (!function_exists( 'digitalnomad_remove_date_archives' )) {

    /**
     * Remove date archives
     * https://codex.wordpress.org/Conditional_Tags#A_Date_Page
     * https://stackoverflow.com/questions/44428114/how-to-remove-wordpress-date-archive-pages
     *
     * @global type $wp_query
     */
    function digitalnomad_remove_date_archives() {

        //if we are on date archive page
        if (is_date()) {
            $archive_page_id = get_theme_mod( 'digitalnomad_archive_page', url_to_postid( 'archive' ) );
            if ($archive_page_id) {
                wp_redirect( esc_url( get_page_link( $archive_page_id ) ) );
                die();
            } else {
                global $wp_query;
                $wp_query->set_404();
            }
        }

    }

}

add_action( 'template_redirect', 'digitalnomad_remove_date_archives' );

if (!function_exists( 'digitalnomad_add_post_archive_to_page' )) {

    /**
     * HTML for post archive
     */
    function digitalnomad_add_post_archive_to_page() {

        if (is_page()) {

            if (digitalnomad_page_is_archive()) {

                do_action( 'digitalnomad_post_archive_before' );

                get_search_form();

                $args = [
                    'posts_per_page' => '-1',
                ];
                // if polylang enabled
                if (function_exists( 'pll_current_language' )) {
                    $args[ 'lang' ] = pll_current_language();
                }
                // new query object
                $the_query = new WP_Query( $args );

                if ($the_query->have_posts()) {

                    while ($the_query->have_posts()) {
                        $the_query->the_post();

                        $archive[ get_the_date( 'Y' ) ][ get_the_ID() ][ 'title' ] = esc_attr( get_the_title() );
                        $archive[ get_the_date( 'Y' ) ][ get_the_ID() ][ 'link' ] = esc_url( get_the_permalink() );
                        $archive[ get_the_date( 'Y' ) ][ get_the_ID() ][ 'post_date' ] = date_i18n( 'd. M', get_the_date( 'U' ) );
                    }
                    krsort( $archive, SORT_NUMERIC ); // sort by year
                    digitalnomad_the_archive_table( $archive );
                } else {

                    echo '<p>' . esc_html__( 'There are no posts.', 'digital-nomad' ) . '</p>' . PHP_EOL;
                }

                /* Restore original Post Data */
                wp_reset_postdata();

                do_action( 'digitalnomad_post_archive_after' );
            }
        }

    }

}

add_action( 'digitalnomad_inner_content_ends', 'digitalnomad_add_post_archive_to_page' );

if (!function_exists( 'digitalnomad_the_archive_table' )) {

    /**
     * Print archive table itself + table for search results
     * $data - array data for table
     * $category - show category in archived table
     * $before - html code before the table
     * $after - html code after the table
     */
    function digitalnomad_the_archive_table( $data, $category = true, $before = null, $after = null ) {

        echo wp_kses_post( $before ? $before . PHP_EOL : PHP_EOL  );
        echo '<div class="archive_table">' . PHP_EOL;

        foreach ($data as $year => $posts) {

            echo PHP_EOL . '<div class="archive_row"><div class="archive_year archive_cell"><h3 class="archive_year_title">' . esc_html( $year ) . '</h3></div></div>' . PHP_EOL . PHP_EOL;

            foreach ($posts as $post_id => $post) {

                echo '<div class="archive_row">' . PHP_EOL;

                echo ' <div class="archive_date archive_cell">' . esc_html( $post[ 'post_date' ] ) . '</div><!-- /archive_date -->' . PHP_EOL;

                if ($category) {

                    echo ' <div class="archive_category archive_cell">';
                    $cats = null;
                    $cats = get_the_category( $post_id );
                    if (!empty( $cats )) {
                        $cats_html = array();
                        foreach ($cats as $cat) {
                            $cats_html[] = '<a href="' . esc_url( get_category_link( $cat->term_id ) ) . '">' . esc_html( get_cat_name( $cat->term_id ) ) . '</a>';
                        }
                        // already escaped in get_category_link() and get_cat_name() functions
                        echo implode( ', ', $cats_html ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                    }
                    echo '</div><!-- /archive_categoty -->' . PHP_EOL;
                }
                $title = empty( $post[ 'title' ] ) ? digitalnomad_notitle() : $post[ 'title' ];

                echo ' <div class="archive_post archive_cell"><a href="' . esc_url( $post[ 'link' ] ) . '" title="' . esc_attr( $title ) . '">' . esc_html( $title ) . '</a></div><!-- /archive_post -->' . PHP_EOL;

                echo '</div><!-- /archive_row -->' . PHP_EOL;
            }
        }
        echo '</div><!-- /archive_table -->' . PHP_EOL;
        echo wp_kses_post( $after ? $after . PHP_EOL : PHP_EOL  );

    }

}

if (!function_exists( 'digitalnomad_notitle' )) {

    /**
     * Returns 'no-title' string for posts with no title
     */
    function digitalnomad_notitle() {
        return __( '(no title)', 'digital-nomad' );

    }

}
if (!function_exists( 'digitalnomad_get_latest_article' )) {

    /**
     * Returns latest post for 404.php page
     */
    function digitalnomad_get_latest_article() {
        $args = [
            'numberposts' => 1,
            'post_type' => 'post',
            'post_status' => 'publish',
            'suppress_filters' => true,
        ];

        // if polylang enabled
        if (function_exists( 'pll_current_language' )) {
            $args[ 'lang' ] = pll_current_language();
        }

        $recent_post = wp_get_recent_posts( $args, ARRAY_A );

        if (!empty( $recent_post[ 0 ] )) {
            return '<a href="' . esc_url( get_permalink( $recent_post[ 0 ][ 'ID' ] ) ) . '">' . esc_html( $recent_post[ 0 ][ 'post_title' ] ) . '</a>';
        }
        return null;

    }

}
if (!function_exists( 'digitalnomad_adds_search_form_to_search_page' )) {

    /**
     * Adds search form to search page
     */
    function digitalnomad_adds_search_form_to_search_page() {
        if (is_search()) {

            ?>
            <div class="inner_content pb0">
                <?php
                get_search_form();

                ?>
            </div>
            <?php
        }

    }

}
add_action( 'digitalnomad_after_inner_content_opening', 'digitalnomad_adds_search_form_to_search_page' );

if (!function_exists( 'digitalnomad_change_search_posts_per_page' )) {

    /**
     * Edits query for search, returns all results on one page
     * Code style: C
     *
     * @param type    $query
     * @return type
     */
    function digitalnomad_change_search_posts_per_page( $query ) {
        if ($query->is_search) {
            // Paging disabled because Digital nomad theme is designed for personal blogs with a few dozen articles, not a big portals with thousand of posts
            $query->query_vars[ 'posts_per_page' ] = -1; // phpcs:ignore WPThemeReview.CoreFunctionality.PostsPerPage.posts_per_page_posts_per_page
        }

        return $query;

    }

}

add_filter( 'pre_get_posts', 'digitalnomad_change_search_posts_per_page' );

// post archive ends #
// comments #
add_action(
        'comment_form_before', function() {
    echo '<div class="outher_respond">';
}
);
add_action(
        'comment_form_after', function() {
    echo '</div><!-- /respond_wrap -->';
}
);

if (!function_exists( 'digitalnomad_the_category_header' )) {

    /**
     * Prints categories and tags after post title
     */
    function digitalnomad_the_category_header() {

        $categories_position = get_theme_mod( 'digitalnomad_categories_position', false );
        if (!$categories_position) {

            ?>

            <div class="post_category"><?php the_category( ', ' ); ?> <?php the_tags( '(<span class="post_tags">', ', ', '</span>)' ); ?></div><!-- /post_category -->

            <?php
        }

    }

}

if (!function_exists( 'digitalnomad_the_category_footer' )) {

    /**
     * Prints categories and tags after post title
     */
    function digitalnomad_the_category_footer() {

        $categories_position = get_theme_mod( 'digitalnomad_categories_position', false );
        if ($categories_position) {

            ?>

            <footer id="post_footer">

                <div class="post_category post_category_bottom"><?php the_category( ', ' ); ?> <?php the_tags( '(<span class="post_tags">', ', ', '</span>)' ); ?></div><!-- /post_category -->

            </footer>

            <?php
        }

    }

}

if (!class_exists( 'Digitalnomad_Walker_Comment' )) {

    /**
     * Custom comment walker
     *
     * @users Walker_Comment
     */
    class Digitalnomad_Walker_Comment extends Walker_Comment {

        public function html5_comment( $comment, $depth, $args ) {

            $tag = ( 'div' === $args[ 'style' ] ) ? 'div' : 'li';

            $commenter = wp_get_current_commenter();
            if ($commenter[ 'comment_author_email' ]) {
                $moderation_note = __( 'Your comment is awaiting moderation.', 'digital-nomad' );
            } else {
                $moderation_note = __( 'Your comment is awaiting moderation. This is a preview, your comment will be visible after it has been approved.', 'digital-nomad' );
            }

            ?>
            <<?php echo $tag; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped                       ?> id="comment-<?php comment_ID(); ?>" <?php comment_class( $this->has_children ? 'parent' : '', $comment ); ?>>
            <article id="div-comment-<?php comment_ID(); ?>" class="comment-body">

                <header class="comment-meta">
                    <h4 class="comment-author time_permalink">
                        <?php
                        // this function is copied from WordPress core
                        printf( wp_kses_post( '%s <span class="says">said on</span>', 'digital-nomad' ), sprintf( '<b class="fn">%s</b>', wp_kses_post( get_comment_author_link( $comment ) ) ) ); // phpcs:ignore WordPress.WP.I18n.MissingTranslatorsComment

                        ?>


                        <a href="<?php echo esc_url( get_comment_link( $comment, $args ) ); ?>">
                            <time datetime="<?php comment_time( 'c' ); ?>">
                                <?php
                                /* translators: 1: Comment date, 2: Comment time. */
                                printf( esc_html__( '%1$s at %2$s', 'digital-nomad' ), esc_attr( get_comment_date( '', $comment ) ), esc_attr( get_comment_time() ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

                                ?>
                            </time>
                        </a>
                    </h4><!-- .comment-author -->

                    <?php if ('0' == $comment->comment_approved) : ?>
                        <div class="comment-awaiting-moderation"><em><?php echo $moderation_note; ?></em></div><?php // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped                      ?>
                    <?php endif; ?>
                </header><!-- .comment-meta -->

                <div class="comment-content">
                    <?php comment_text(); ?>
                </div><!-- .comment-content -->

                <footer class="comment-metadata">
                    <?php do_action( 'comment_metadata_before' ); ?><?php // phpcs:ignore WPThemeReview.CoreFunctionality.PrefixAllGlobals.NonPrefixedHooknameFound   ?>
                    <?php
                    comment_reply_link(
                            array_merge(
                                    $args, array(
                        'add_below' => 'div-comment',
                        'depth' => $depth,
                        'max_depth' => $args[ 'max_depth' ],
                        'before' => '<span class="reply">',
                        'after' => '</span>',
                                    )
                            )
                    );

                    ?>
                    <?php edit_comment_link( __( 'Edit', 'digital-nomad' ), '<span class="edit">', '</span>' ); ?>
                    <?php do_action( 'comment_metadata_after' ); ?><?php // phpcs:ignore WPThemeReview.CoreFunctionality.PrefixAllGlobals.NonPrefixedHooknameFound     ?>
                </footer><!-- .comment-metadata -->

            </article><!-- .comment-body -->
            <?php

        }

    }

}
// comments end #
