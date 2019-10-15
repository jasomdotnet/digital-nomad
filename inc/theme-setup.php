<?php

if ( !function_exists( 'digitalnomad_widgets_init' ) ) {

	/**
	 * Register sitebar in header
	 */
	function digitalnomad_widgets_init() {
		register_sidebar( array(
			'id'			 => 'header-text',
			'name'			 => __( 'Header text', 'digital-nomad' ),
			'description'	 => __( 'Menu/about text for Header sidebar. Use "Custom HTML" widget.', 'digital-nomad' ),
			'before_widget'	 => '<div class="widget header_text text_shadow %2$s" id="%1$s">',
			'after_widget'	 => '</div>',
			'before_title'	 => '<!--',
			'after_title'	 => '-->',
		) );
	}

}
add_action( 'widgets_init', 'digitalnomad_widgets_init' );

if ( !function_exists( 'digitalnomad_theme_features' ) ) {

	/**
	 * https://generatewp.com/theme-support/
	 */
	function digitalnomad_theme_features() {

		// Add theme support for Automatic Feed Links
		add_theme_support( 'automatic-feed-links' );
		// Add theme support for Featured Images
		add_theme_support( 'post-thumbnails' );
		// https://developer.wordpress.org/themes/functionality/custom-logo/
		$custom_logo_args	 = array(
			'height'		 => 480,
			'width'			 => 480,
			'header-text'	 => array( 'site-title' ),
		);
		add_theme_support( 'custom-logo', $custom_logo_args );
		// Add theme support for HTML5 Semantic Markup
		$html5_args			 = array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		);
		add_theme_support( 'html5', $html5_args );
		// Add theme support for document Title tag
		add_theme_support( 'title-tag' );
		// Add theme support for Custom Header
		$header_args		 = array(
			'default-image'			 => get_template_directory_uri() . '/images/default-header-prague-665637-pxhere-com.jpg',
			'width'					 => 0,
			'height'				 => 0,
			'flex-width'			 => true,
			'flex-height'			 => true,
			'uploads'				 => true,
			'random-default'		 => false,
			'header-text'			 => false,
			'default-text-color'	 => '',
			'wp-head-callback'		 => '',
			'admin-head-callback'	 => '',
			'admin-preview-callback' => '',
			'video'					 => false,
			'video-active-callback'	 => '',
		);
		add_theme_support( 'custom-header', $header_args );
		// Add theme support for custom CSS in the TinyMCE visual editor
		add_editor_style();
		// Add theme support for Translation
		load_theme_textdomain( 'digital-nomad', get_template_directory() . '/language' );
		// Add theme support for starter-content
		add_theme_support( 'starter-content', array(
			'posts'			 => array(
				'archive' => array(
					'post_type'	 => 'page',
					'post_title' => __( 'Archive', 'digital-nomad' ),
				),
			),
			'attachments'	 => array(
				'image-header'	 => array(
					'post_title' => __( 'Header default image', 'digital-nomad' ),
					'file'		 => 'images/default-header-prague-665637-pxhere-com.jpg',
				),
				'image-footer'	 => array(
					'post_title' => __( 'Footer default image', 'digital-nomad' ),
					'file'		 => 'images/default-footer-sea-horizon-sunset-616734-pxhere-com.jpg',
				),
				'image-feature'	 => array(
					'post_title' => __( 'Feature default image', 'digital-nomad' ),
					'file'		 => 'images/default-feature-image-wordpress-1344296-pxhere-com.jpg',
				),
				'image-bg-prev'	 => array(
					'post_title' => __( 'Footer default image', 'digital-nomad' ),
					'file'		 => 'images/default-previous-pager-675708-pxhere-com.jpg',
				),
				'image-bg-next'	 => array(
					'post_title' => __( 'Footer default image', 'digital-nomad' ),
					'file'		 => 'images/default-next-pager-107966-pxhere-com.jpg',
				),
			),
			'widgets'		 => array(
				'header-text' => array(
					'digitalnomad_def_header' => array(
						'custom_html',
						array(
							'title'		 => __( 'About like menu', 'digital-nomad' ),
							'content'	 => sprintf( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut mollis posuere massa, ac porta risus. Sed elit turpis, imperdiet <a href="%s">home</a> lectus vel, eleifend tempor diam. <a href="%s">Archive</a> finibus aliquam est, sed tincidunt ipsum congue et. Integer a gravida enim, vitae tempor leo.', home_url(), home_url() . '/archive/' ),
						)
					)
				)
			),
			'options'		 => array(
				//'digitalnomad_archive_page'			 => '{{archive}}', // doesn't work, don't know why
				'digitalnomad_default_feature_image' => '{{image-feature}}',
				'digitalnomad_footer_image'			 => '{{image-footer}}',
				'digitalnomad_previous_bg'			 => '{{image-bg-prev}}',
				'digitalnomad_next_bg'				 => '{{image-bg-next}}',
			),
		) );
	}

}
add_action( 'after_setup_theme', 'digitalnomad_theme_features' );

if ( !isset( $content_width ) )
	$content_width = 1200;
