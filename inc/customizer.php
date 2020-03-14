<?php

if (!function_exists( 'digitalnomad_customizer_register' )) {

    /**
     * Adds option for default feature image in theme customization
     * https://silicondales.com/tutorials/wordpress/tutorial-customizing-wordpress-customizer-image-uploads-use-theme/
     * htps://gist.github.com/ajskelton/740788f98df3283355dd7e0c2f5abb2a
     * https://divpusher.com/blog/wordpress-customizer-sanitization-examples/
     * https://gist.github.com/ControlledChaos/a68997f4a348d447132033332a4e50f1 - Sanitization
     * @param type $wp_customize
     */
    function digitalnomad_customizer_register( $wp_customize ) {

        $pages = get_pages();
        foreach ($pages as $page) {
            $values_for_select[ $page->ID ] = $page->post_title;
        }

        // Add and manipulate theme images to be used.
        $wp_customize->add_section( 'digitalnomad_options', array(
            'title' => __( 'Digital Nomad Settings', 'digital-nomad' ),
            'priority' => 168,
        ) );

        // footer image
        $wp_customize->add_setting( 'digitalnomad_footer_image', array(
            'default' => '',
            'type' => 'theme_mod',
            'capability' => 'edit_theme_options',
            'sanitize_callback' => 'absint',
        ) );
        $wp_customize->add_control(
                new WP_Customize_Media_Control( $wp_customize, 'digitalnomad_footer_image', array(
                    'label' => __( 'Background Image For Footer', 'digital-nomad' ),
                    'description' => __( 'Image used in footer as background.', 'digital-nomad' ),
                    'section' => 'digitalnomad_options',
                    'settings' => 'digitalnomad_footer_image',
                ) ) );

        // default feature image
        $wp_customize->add_setting( 'digitalnomad_default_feature_image', array(
            'default' => '',
            'type' => 'theme_mod',
            'capability' => 'edit_theme_options',
            'sanitize_callback' => 'absint',
        ) );
        $wp_customize->add_control(
                new WP_Customize_Media_Control( $wp_customize, 'digitalnomad_default_feature_image', array(
                    'label' => __( 'Default Featured Image', 'digital-nomad' ),
                    'description' => __( 'Image used in post preview when no feature image is set in the post.', 'digital-nomad' ),
                    'section' => 'digitalnomad_options',
                    'settings' => 'digitalnomad_default_feature_image',
                ) ) );

        // post archive page
        $wp_customize->add_setting( 'digitalnomad_archive_page', array(
            'default' => url_to_postid( 'archive' ),
            'type' => 'theme_mod',
            'capability' => 'edit_theme_options',
            'sanitize_callback' => 'digitalnomad_sanitize_archive_page',
        ) );
        $wp_customize->add_control(
                new WP_Customize_Control( $wp_customize, 'digitalnomad_archive_page', array(
                    'label' => __( 'Archive Page', 'digital-nomad' ),
                    /* translators: %s: admin url pointing to archive for post type page */
                    'description' => sprintf( __( 'Select a <a href="%s">static page</a> which will serve as archive for posts.', 'digital-nomad' ), esc_url( admin_url( 'edit.php?post_type=page' ) ) ),
                    'section' => 'digitalnomad_options',
                    'settings' => 'digitalnomad_archive_page',
                    'priority' => 10,
                    'type' => 'select',
                    'choices' => $values_for_select,
                ) ) );

        // enable infinity scroll for blog archive, category and tags.
        $wp_customize->add_setting( 'digitalnomad_infinite_scroll', array(
            'capability' => 'edit_theme_options',
            'type' => 'theme_mod',
            'default' => true,
            'sanitize_callback' => 'digitalnomad_sanitize_checkbox',
        ) );
        $wp_customize->add_control(
                new WP_Customize_Control( $wp_customize, 'digitalnomad_infinite_scroll', array(
                    'label' => __( 'Infinite Scroll', 'digital-nomad' ),
                    'description' => __( 'Enable infinite scroll for blog archive, categories and tags.', 'digital-nomad' ),
                    'section' => 'digitalnomad_options',
                    'type' => 'checkbox',
                    'settings' => 'digitalnomad_infinite_scroll',
                ) ) );

        // digitalnomad_blazy
        $wp_customize->add_setting( 'digitalnomad_blazy_lazyload', array(
            'capability' => 'edit_theme_options',
            'type' => 'theme_mod',
            'default' => true,
            'sanitize_callback' => 'digitalnomad_sanitize_checkbox',
        ) );
        $wp_customize->add_control(
                new WP_Customize_Control( $wp_customize, 'digitalnomad_blazy_lazyload', array(
                    'label' => __( 'Lazyload', 'digital-nomad' ),
                    'description' => __( 'Enable lazyload using bLazy (vanilla JS library). Leave this option enabled otherwise you will break the Internet. You do not want break the Internet, right?', 'digital-nomad' ),
                    'section' => 'digitalnomad_options',
                    'type' => 'checkbox',
                    'settings' => 'digitalnomad_blazy_lazyload',
                ) ) );

        // digitalnomad_previous_bg
        $wp_customize->add_setting( 'digitalnomad_previous_bg', array(
            'default' => '',
            'type' => 'theme_mod',
            'capability' => 'edit_theme_options',
            'sanitize_callback' => 'absint',
        ) );
        $wp_customize->add_control(
                new WP_Customize_Media_Control( $wp_customize, 'digitalnomad_previous_bg', array(
                    'label' => __( 'Background Image For Previous Pager', 'digital-nomad' ),
                    'description' => __( 'Image used as bacground in "previous" post(s) navigation.', 'digital-nomad' ),
                    'section' => 'digitalnomad_options',
                    'settings' => 'digitalnomad_previous_bg',
                    'priority' => 11,
                ) ) );

        // digitalnomad_next_bg
        $wp_customize->add_setting( 'digitalnomad_next_bg', array(
            'default' => '',
            'type' => 'theme_mod',
            'capability' => 'edit_theme_options',
            'sanitize_callback' => 'absint',
        ) );
        $wp_customize->add_control(
                new WP_Customize_Media_Control( $wp_customize, 'digitalnomad_next_bg', array(
                    'label' => __( 'Background Image For Next Pager', 'digital-nomad' ),
                    'description' => __( 'Image used as bacground in "next" post(s) navigation.', 'digital-nomad' ),
                    'section' => 'digitalnomad_options',
                    'settings' => 'digitalnomad_next_bg',
                    'priority' => 12,
                ) ) );

        // digitalnomad_short_about
        $wp_customize->add_setting( 'digitalnomad_short_about', array(
            'capability' => 'edit_theme_options',
            'type' => 'theme_mod',
            'default' => false,
            'sanitize_callback' => 'digitalnomad_sanitize_checkbox',
        ) );
        $wp_customize->add_control(
                new WP_Customize_Control( $wp_customize, 'digitalnomad_short_about', array(
                    'label' => __( 'Bio in header has 3 lines', 'digital-nomad' ),
                    'description' => __( 'Enable this if your "about/menu" text in the header has only 3 lines. This option will fit logo/gravatar size with the bio length.', 'digital-nomad' ),
                    'section' => 'digitalnomad_options',
                    'type' => 'checkbox',
                    'settings' => 'digitalnomad_short_about',
                ) ) );

        // digitalnomad_layout_width
        $wp_customize->add_setting( 'digitalnomad_layout_width', array(
            'capability' => 'edit_theme_options',
            'type' => 'theme_mod',
            'default' => 56,
            'sanitize_callback' => 'absint',
        ) );
        $wp_customize->add_control(
                new WP_Customize_Control( $wp_customize, 'digitalnomad_layout_width', array(
                    'label' => __( 'Layout width', 'digital-nomad' ),
                    'description' => __( 'You can set custom layout width here. Default value is 56rem (1rem is usually 16px).', 'digital-nomad' ),
                    'section' => 'digitalnomad_options',
                    'type' => 'number',
                    'settings' => 'digitalnomad_layout_width',
                ) ) );

        // digitalnomad_categories_position
        $wp_customize->add_setting( 'digitalnomad_categories_position', array(
            'capability' => 'edit_theme_options',
            'type' => 'theme_mod',
            'default' => false,
            'sanitize_callback' => 'digitalnomad_sanitize_checkbox',
        ) );
        $wp_customize->add_control(
                new WP_Customize_Control( $wp_customize, 'digitalnomad_categories_position', array(
                    'label' => __( 'Move categories and tags to end of the post', 'digital-nomad' ),
                    'description' => __( 'This setting displays categories and tags on the end of the post. If your average post has 10+ tags, you want to use this option.', 'digital-nomad' ),
                    'section' => 'digitalnomad_options',
                    'type' => 'checkbox',
                    'settings' => 'digitalnomad_categories_position',
                ) ) );

    }

}

add_action( 'customize_register', 'digitalnomad_customizer_register' );

if (!function_exists( 'digitalnomad_sanitize_checkbox' )) {

    /**
     * Sanitize checbox
     * @param type $value
     * @return type
     */
    function digitalnomad_sanitize_checkbox( $value ) {
        return ( ( isset( $value ) && true == $value ) ? true : false );

    }

}

if (!function_exists( 'digitalnomad_sanitize_archive_page' )) {

    /**
     * Validate archive page ID
     * @param type $value
     * @return type
     */
    function digitalnomad_sanitize_archive_page( $page_id, $setting ) {
        // ensure value is positive integer.
        $page_id = absint( $page_id );
        // get list of possible values
        $pages = get_pages();
        foreach ($pages as $page) {
            $all_possible_pages[] = $page->ID;
        }
        return ( in_array( $page_id, $all_possible_pages ) ? $page_id : $setting->default );

    }

}