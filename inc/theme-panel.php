<?php
# theme page #
if ( !function_exists( 'digitalnomad_add_theme_page' ) ) {

	/**
	 * Adds custom page for digitalnomad
	 */
	function digitalnomad_add_theme_page() {
		add_theme_page( __( 'Digital Nomad', 'digital-nomad' ) . ' ' . wp_get_theme()->get( 'Version' ), 'Digital Nomad', 'edit_theme_options', 'digitalnomad-panel', 'digitalnomad_panel_content' );
	}

}
add_action( 'admin_menu', 'digitalnomad_add_theme_page' );

if ( !function_exists( 'digitalnomad_panel_content' ) ) {

	function digitalnomad_panel_content() {
		?>
		<div class="wrap about-wrap full-width-layout">
			<h1><?php _e( 'Welcome to Digital Nomad', 'digital-nomad' ); ?> <?php echo wp_get_theme()->get( 'Version' ); ?></h1>
			<p class="about-text">
				<?php _e( 'Congrats on choosing Digital Nomad, theme for digital nomads like you.', 'digital-nomad' ); ?>
				<br>
				<br>
				<a href="https://www.jasom.net/digital-nomad-wordpress-theme/?utm_source=digitalnomadtheme&utm_medium=jasomdotnet&utm_campaign=themepanel" class="button button-primary button-large"><?php _e( 'Theme Homepage', 'digital-nomad' ); ?></a>
			</p>
			<h3><?php _e( 'Configuration', 'digital-nomad' ); ?></h3>
			<p><?php _e( 'Follow these steps to set up theme correctly:', 'digital-nomad' ); ?></p>
			<ol>
				<li><?php printf( __( 'Install using a child theme to avoid custom overrides lost. Learn more about child themes on <a href="%s">Wordpress.org</a>.', 'digital-nomad' ), 'https://developer.wordpress.org/themes/advanced-topics/child-themes/' ); ?></li>
				<li><?php printf( __( 'Under <a href="%s">Widgets</a> add menu/about text for Header sidebar. Use "Custom HTML" widget.', 'digital-nomad' ), admin_url( 'widgets.php' ) ); ?></li>
				<li><?php printf( __( 'In <a href="%s">Customizer</a> set:', 'digital-nomad' ), admin_url( 'customize.php?return=%2Fwp-admin%2Fthemes.php' ) ); ?>
					<ol>
						<li><?php _e( 'Under "Site Identity" set square "Logo" and "Site Icon".', 'digital-nomad' ); ?></li>
						<li><?php _e( 'Under "Header Image" set background picture for header.', 'digital-nomad' ); ?></li>
						<li><?php _e( 'Under "Homepage Settings" use "Your latest posts" as homepage display.', 'digital-nomad' ); ?></li>
						<li><?php _e( 'Under "Digital Nomad Options":', 'digital-nomad' ); ?>
							<ol>
								<li><?php _e( 'Set "Background Image For Footer"', 'digital-nomad' ); ?></li>
								<li><?php _e( 'Set "Default Featured Image", image used in post archives when no feature image is selected.', 'digital-nomad' ); ?></li>
								<li><?php _e( 'Select static "Archive Page" which will be used as an archive for all posts. Date archives are disabled by default and will return 404 if no archive page is selected.', 'digital-nomad' ); ?></li>
								<li><?php _e( 'Enable "Infinite Scroll" for latest posts, categories and tags. Custom post types and custom taxonomies are also supported.', 'digital-nomad' ); ?></li>
								<li><?php _e( 'Enable "Lazyload" for latest posts, categories and tags. NOTE: Images in posts are not nativelly lazyloaded', 'digital-nomad' ); ?></li>
								<li><?php _e( 'Set "Background Image For Next & Previous Pager" for &lt;noscript&gt; support.', 'digital-nomad' ); ?></li>
							</ol>
						</li>
					</ol>
				</li>
				<li><?php _e( 'That\'s it!', 'digital-nomad' ); ?></li>
			</ol>
			<hr>
			<h3><?php _e( 'Recommended Plugins', 'digital-nomad' ); ?></h3>
			<div class="has-2-columns is-fullwidth">
				<div class="column">
					<h4><a href="https://wordpress.org/plugins/resmushit-image-optimizer/"><?php _e( 'reSmush.it Image Optimizer', 'digital-nomad' ); ?></a></h4>
					<p><?php _e( 'Digital Nomad theme relies heavy on images. reSmush.it is free service providing image size reduction based on several advanced algorithms. The API accept JPG, PNG and GIF files up to 5MB. Set reSmush,it quality to 82.', 'digital-nomad' ); ?></p>
				</div>
				<div class="column">
					<h4><a href="https://wordpress.org/plugins/wordpress-seo/"><?php _e( 'Yoast SEO', 'digital-nomad' ); ?></a></h4>
					<p><?php _e( 'My favorite option when it comes to SEO plugin for WordPress. Yoast SEO generates sitemap.xml, sets page titles, creates metatags, etc.', 'digital-nomad' ); ?></p>
				</div>
			</div>
			<div class="has-2-columns is-fullwidth">
				<div class="column">
					<h4><a href="https://wordpress.org/plugins/code-syntax-block/"><?php _e( 'Code Syntax Block', 'digital-nomad' ); ?></a></h4>
					<p><?php _e( 'A plugin to extend Gutenberg code block with syntax highlighting.', 'digital-nomad' ); ?></p>
				</div>
				<div class="column">
					<h4><a href="https://wordpress.org/plugins/wp-super-cache/"><?php _e( 'WP Super Cache', 'digital-nomad' ); ?></a></h4>
					<p><?php _e( 'Don not leave your site without enabled cache. WP Super Cache from Automattic does the job well.', 'digital-nomad' ); ?></p>
				</div>
			</div>
			<hr>
			<h3><?php _e( 'Post-installation Actions', 'digital-nomad' ); ?></h3>
			<div class="has-2-columns is-fullwidth">
				<div class="column">
					<h4><?php _e( 'Leave a review', 'digital-nomad' ); ?></h4>
					<p><?php _e( 'If you like Digital Nomad, review the theme on WordPress.org. Leave a review even if you don\'t like it.', 'digital-nomad' ); ?></p>
				</div>
				<div class="column">
					<h4><a href="https://www.jasom.net/contact/"><?php _e( 'Feature Requests', 'digital-nomad' ); ?></a></h4>
					<p><?php _e( 'Send feature request for Digital Nomad theme.', 'digital-nomad' ); ?></p>
				</div>
			</div>
			<div class="has-2-columns is-fullwidth">
				<div class="column">
					<h4><a href="https://twitter.com/jasomdotnet"><?php _e( 'Follow me on Twitter', 'digital-nomad' ); ?></a></h4>
					<p><?php _e( 'If you like my work, follow me on Twitter.', 'digital-nomad' ); ?></p>
				</div>
			</div>
			<hr>
			<h4><?php _e( 'What theme does not', 'digital-nomad' ); ?></h4>
			<ul>
				<li><?php _e( 'Theme doesn\'t require jQuery so (after additional optimalization) you can easily reach out high score in Google\'s PageSpeed Insights.', 'digital-nomad' ); ?></li>
				<li><?php _e( 'Theme doesn\'t support post-formats. I have personally never used this WordPress feature and that\'s why I didn\'t add support.', 'digital-nomad' ); ?></li>
				<li><?php _e( 'Theme doesn\'t use navigation menus. Instead, it uses short about-me text in the header which can be hotlinked to various sections of the blog.', 'digital-nomad' ); ?></li>
			</ul>
			<h4><?php _e( '@TODO', 'digital-nomad' ); ?></h4>
			<ul>
				<li><?php _e( 'Add native lazyloading for post images.', 'digital-nomad' ); ?></li>
				<li><?php _e( 'Add Medium like zoom for post images.', 'digital-nomad' ); ?></li>
			</ul>
			<hr>
			<p class="about-text"><?php _e( 'It\'s not in theme, it\'s in the content! But good theme also helps...', 'digital-nomad' ); ?></p>
		</div>
		<?php
	}

}
# theme page ends #
# activation #
if ( !function_exists( 'digitalnomad_disable_previous_widgets' ) ) {

	/**
	 * Disable previous widgets
	 * https://wordpress.stackexchange.com/a/344632 <- this
	 * https://wordpress.stackexchange.com/a/102727
	 */
	function digitalnomad_disable_previous_widgets() {
		$sidebar_widgets = wp_get_sidebars_widgets();

		foreach ( $sidebar_widgets[ 'sidebar' ] as $i => $widget ) {
			unset( $sidebars_widgets[ 'sidebar' ][ $i ] );
		}
		wp_set_sidebars_widgets( $sidebars_widgets );
	}

}
if ( !function_exists( 'digitalnomad_admin_notice__success' ) ) {

	/**
	 * Welcome message
	 */
	function digitalnomad_admin_notice__success() {
		?>
		<div class="notice notice-success is-dismissible">
			<p><?php printf( __( 'Digital Nomad theme activated. <a href="%s">Visit configuration instructions here</a>.', 'digital-nomad' ), esc_url( admin_url( 'themes.php?page=digitalnomad-panel' ) ) ); ?></p>
		</div>
		<?php
	}

}

if ( !function_exists( 'digitalnomad_do_on_theme_activation' ) ) {

	/**
	 * After theme installation hook
	 */
	function digitalnomad_do_on_theme_activation() {
		if ( !get_option( 'theme_mods_digital-nomad' ) ) {
			digitalnomad_disable_previous_widgets();
		}
		add_action( 'admin_notices', 'digitalnomad_admin_notice__success' );
	}

}
add_action( 'after_switch_theme', 'digitalnomad_do_on_theme_activation' );
# activation ends #