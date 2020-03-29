<?php
# theme page #
if (!function_exists( 'digitalnomad_add_theme_page' )) {

    /**
     * Adds custom page for digitalnomad
     */
    function digitalnomad_add_theme_page() {
        add_theme_page( __( 'Digital Nomad', 'digital-nomad' ) . ' ' . wp_get_theme( get_template() )->get( 'Version' ), 'Digital Nomad', 'edit_theme_options', 'digitalnomad-panel', 'digitalnomad_panel_content' );

    }

}
add_action( 'admin_menu', 'digitalnomad_add_theme_page' );

if (!function_exists( 'digitalnomad_panel_content' )) {

    function digitalnomad_panel_content() {

        ?>
        <div class="wrap about-wrap full-width-layout">
            <h1><?php esc_html_e( 'Welcome to Digital Nomad', 'digital-nomad' ); ?> <?php echo esc_html( wp_get_theme( get_template() )->get( 'Version' ) ); ?></h1>
            <p class="about-text">
                <?php esc_html_e( 'Congrats on choosing Digital Nomad, theme for digital nomads like you.', 'digital-nomad' ); ?>
                <br>
                <br>
                <a href="https://www.jasom.net/digital-nomad-wordpress-theme/?utm_source=digitalnomadtheme&utm_medium=jasomdotnet&utm_campaign=themepanel" class="button button-primary button-large"><?php esc_html_e( 'Theme Homepage', 'digital-nomad' ); ?></a>
            </p>
            <h3><?php esc_html_e( 'Configuration', 'digital-nomad' ); ?></h3>
            <p><?php
                    /* translators: %s: url pointing to configuration videoturorial */
                    printf( wp_kses_post( 'Watch <a href="%s">this tutorial on YouTube</a> or follow these steps to set up theme correctly:', 'digital-nomad' ), esc_url( 'https://www.youtube.com/watch?v=M-YgCZrRtzQ' ) );

                    ?></p>
            <ol>
                <li><?php
                    /* translators: %s: url pointing to official documentation about child themes */
                    printf( wp_kses_post( 'Install using a child theme to avoid custom overrides lost. Learn more about child themes on <a href="%s">Wordpress.org</a>.', 'digital-nomad' ), esc_url( 'https://developer.wordpress.org/themes/advanced-topics/child-themes/' ) );

                    ?></li>
                <li><?php
                    /* translators: %s: admin url pointing to widget settings */
                    printf( wp_kses_post( 'Under <a href="%s">Widgets</a> add what I call "menu/about text" for Header sidebar. Use "Custom HTML" widget with following sample code:', 'digital-nomad' ), esc_url( admin_url( 'widgets.php' ) ) );
                    ?>
                    <blockquote>
                        <em>
                        <?php
                             /* translators: %s: home_url pointing to front page and archive page */
                            printf( __( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut mollis posuere massa, ac porta risus. Sed elit turpis, imperdiet &lt;a href="%1$s"&gt;home&lt;/a&gt; lectus vel, eleifend tempor diam. &lt;a href="%2$s"&gt;Archive&lt;/a&gt; finibus aliquam est, sed tincidunt ipsum congue et. Integer a gravida enim, vitae tempor leo.', 'digital-nomad' ), home_url(), home_url() . '/archive/' );
                        ?>
                        </em>
                    </blockquote>
                </li>
                <li><?php
                    /* translators: %s: admin url pointing to theme customizer */
                    printf( wp_kses_post( 'In <a href="%s">Customizer</a> set:', 'digital-nomad' ), esc_url( admin_url( 'customize.php?return=%2Fwp-admin%2Fthemes.php' ) ) );

                    ?>
                    <ol>
                        <li><?php esc_html_e( 'Under "Site Identity" set square "Logo" and "Site Icon".', 'digital-nomad' ); ?></li>
                        <li><?php esc_html_e( 'Under "Header Image" set background picture for header.', 'digital-nomad' ); ?></li>
                        <li><?php esc_html_e( 'Under "Homepage Settings" use "Your latest posts" as homepage display.', 'digital-nomad' ); ?></li>
                        <li><?php esc_html_e( 'Under "Digital Nomad Options":', 'digital-nomad' ); ?>
                            <ol>
                                <li><?php esc_html_e( 'Set "Background Image For Footer"', 'digital-nomad' ); ?></li>
                                <li><?php esc_html_e( 'Set "Default Featured Image", image used in post archives as post preview when no feature image is selected.', 'digital-nomad' ); ?></li>
                                <li><?php esc_html_e( 'Select static "Archive Page" which will be used as an archive for all posts. Date archives are disabled by default and will return 404 if no archive page is selected.', 'digital-nomad' ); ?></li>
                                <li><?php esc_html_e( 'Enable "Infinite Scroll" for latest posts, categories and tags. Custom post types and custom taxonomies are also supported.', 'digital-nomad' ); ?></li>
                                <li><?php esc_html_e( 'Enable "Lazyload" for latest posts, categories and tags. NOTE: Images in posts are not nativelly lazyloaded', 'digital-nomad' ); ?></li>
                                <li><?php esc_html_e( 'Set "Background Image For Next & Previous Pager" for &lt;noscript&gt; support.', 'digital-nomad' ); ?></li>
                            </ol>
                        </li>
                    </ol>
                </li>
                <li><?php esc_html_e( 'That\'s it!', 'digital-nomad' ); ?></li>
            </ol>
            <p><?php esc_html_e( 'NOTE: Everytime you install new theme disable cache plugins since they add new layer of complexity. Turn cache plugins back on after everything is configured correctly.', 'digital-nomad' ); ?></p>
            <hr>
            <h3><?php esc_html_e( 'Recommended Plugins', 'digital-nomad' ); ?></h3>
            <div class="has-2-columns is-fullwidth">
                <div class="column">
                    <h4><a href="<?php echo esc_url( 'https://wordpress.org/plugins/resmushit-image-optimizer/' ); ?>"><?php esc_html_e( 'reSmush.it Image Optimizer', 'digital-nomad' ); ?></a></h4>
                    <p><?php esc_html_e( 'Digital Nomad theme relies heavy on images. reSmush.it is free service providing image size reduction based on several advanced algorithms. The API accept JPG, PNG and GIF files up to 5MB. Set reSmush.it quality to 82.', 'digital-nomad' ); ?></p>
                </div>
                <div class="column">
                    <h4><a href="<?php echo esc_url( 'https://wordpress.org/plugins/wordpress-seo/' ); ?>"><?php esc_html_e( 'Yoast SEO', 'digital-nomad' ); ?></a></h4>
                    <p><?php esc_html_e( 'My favorite option when it comes to SEO plugin for WordPress. Yoast SEO generates sitemap.xml, sets page titles, creates metatags, etc.', 'digital-nomad' ); ?></p>
                </div>
            </div>
            <div class="has-2-columns is-fullwidth">
                <div class="column">
                    <h4><a href="<?php echo esc_url( 'https://wordpress.org/plugins/code-syntax-block/' ); ?>"><?php esc_html_e( 'Code Syntax Block', 'digital-nomad' ); ?></a></h4>
                    <p><?php esc_html_e( 'A plugin to extend Gutenberg code block with syntax highlighting.', 'digital-nomad' ); ?></p>
                </div>
                <div class="column">
                    <h4><a href="<?php echo esc_url( 'https://wordpress.org/plugins/wp-super-cache/' ); ?>"><?php esc_html_e( 'WP Super Cache', 'digital-nomad' ); ?></a></h4>
                    <p><?php esc_html_e( 'Do not leave your site without enabled cache. WP Super Cache from Automattic does a great job.', 'digital-nomad' ); ?></p>
                </div>
            </div>
            <hr>
            <h3><?php esc_html_e( 'Post-installation Actions', 'digital-nomad' ); ?></h3>
            <div class="has-2-columns is-fullwidth">
                <div class="column"> 
                    <h4><a href="<?php echo esc_url( 'https://wordpress.org/themes/digital-nomad/' ); ?>"><?php esc_html_e( 'Leave a review', 'digital-nomad' ); ?></a></h4>
                    <p><?php esc_html_e( 'If you like Digital Nomad, review the theme on WordPress.org. Leave a review even if you don\'t like it.', 'digital-nomad' ); ?></p>
                </div>
                <div class="column">
                    <h4><a href="<?php echo esc_url( 'https://www.jasom.net/contact/' ); ?>"><?php esc_html_e( 'Feature Requests', 'digital-nomad' ); ?></a></h4>
                    <p><?php esc_html_e( 'Send feature request for Digital Nomad theme.', 'digital-nomad' ); ?></p>
                </div>
            </div>
            <div class="has-2-columns is-fullwidth">
                <div class="column">
                    <h4><a href="<?php echo esc_url( 'https://twitter.com/jasomdotnet' ); ?>"><?php esc_html_e( 'Follow me on Twitter', 'digital-nomad' ); ?></a></h4>
                    <p><?php esc_html_e( 'If you like my work, follow me on Twitter.', 'digital-nomad' ); ?></p>
                </div>
            </div>
            <hr>
            <h4><?php esc_html_e( 'What theme does and does not', 'digital-nomad' ); ?></h4>
            <ul>
                <li><?php esc_html_e( 'Theme doesn\'t require jQuery so (after additional optimalization) you can easily reach out high score in Google\'s PageSpeed Insights.', 'digital-nomad' ); ?></li>
                <li><?php esc_html_e( 'Theme doesn\'t support post-formats. I have personally never used this WordPress feature and that\'s why I didn\'t add support.', 'digital-nomad' ); ?></li>
                <li><?php esc_html_e( 'Theme lacks built-in feature for GoogleFonts. Our motivation for working on open-source projects is mostly internal satisfaction, right? GoogleFonts harms my internal satisfaction. They mean additional requests, additional bandwidth, another privacy leak. I don\'t like them, I don\'t want them. But I understand that some of you like them, some of you want them. We can make a deal: Create a snipped code which can be inserted into child\'s function.php and I will write documentation. If you read this sentence, so far nobody did so :-)', 'digital-nomad' ); ?></li>
                <li><?php esc_html_e( 'Within the concept of the design, "Site Title" and "Tagline" are printed in the code in the form of logo\'s (gravatar\'s) "ALT" and "TITLE" tag instead of to be displayed as <H1> text somewhere in the header. Bots can still scrap the information.', 'digital-nomad' ); ?></li>
                <li><?php esc_html_e( 'Theme supports navigation menus (first level only, no sub-items). You can also use short "menu/about text" in the header which can be hotlinked to various sections within or outside of the site. This is the prefered way of navigational hotlinking for the Digital Nomad theme.', 'digital-nomad' ); ?></li>
            </ul>
            <h4><?php esc_html_e( '@TODO', 'digital-nomad' ); ?></h4>
            <ul>
                <li><?php esc_html_e( 'Add native lazyloading for post images.', 'digital-nomad' ); ?></li>
                <li><?php esc_html_e( 'Add Medium like zoom for post images.', 'digital-nomad' ); ?></li>
            </ul>
            <hr>
            <p class="about-text"><?php esc_html_e( 'It\'s not in theme, it\'s in the content! But good theme also helps...', 'digital-nomad' ); ?></p>
        </div>
        <?php

    }

}
# theme page ends #
# activation #
if (!function_exists( 'digitalnomad_disable_previous_widgets' )) {

    /**
     * Disable previous widgets
     * https://wordpress.stackexchange.com/a/344632 <- this
     * https://wordpress.stackexchange.com/a/102727
     */
    function digitalnomad_disable_previous_widgets() {
        $sidebar_widgets = wp_get_sidebars_widgets();

        foreach ($sidebar_widgets[ 'sidebar' ] as $i => $widget) {
            unset( $sidebars_widgets[ 'sidebar' ][ $i ] );
        }
        wp_set_sidebars_widgets( $sidebars_widgets );

    }

}
if (!function_exists( 'digitalnomad_admin_notice__success' )) {

    /**
     * Welcome message
     */
    function digitalnomad_admin_notice__success() {

        ?>
        <div class="notice notice-success is-dismissible">
            <p><?php
                /* translators: %s: admin url pointing to configuration instructions for Digital Nomad theme */
                printf( wp_kses_post( 'Digital Nomad theme activated. <a href="%s">Visit configuration instructions here</a>.', 'digital-nomad' ), esc_url( admin_url( 'themes.php?page=digitalnomad-panel' ) ) );

                ?></p>
        </div>
        <?php

    }

}

if (!function_exists( 'digitalnomad_do_on_theme_activation' )) {

    /**
     * After theme installation hook
     */
    function digitalnomad_do_on_theme_activation() {
        if (!get_option( 'theme_mods_digital-nomad' )) {
            digitalnomad_disable_previous_widgets();
        }
        add_action( 'admin_notices', 'digitalnomad_admin_notice__success' );

    }

}
add_action( 'after_switch_theme', 'digitalnomad_do_on_theme_activation' );
# activation ends #