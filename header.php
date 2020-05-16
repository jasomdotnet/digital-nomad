<!DOCTYPE html>
<html <?php language_attributes(); ?>>
    <head>
        <meta charset="<?php bloginfo( 'charset' ); ?>">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=3.0" />
		<?php wp_head(); ?>
    </head>

    <body <?php body_class(); ?>>
		<?php wp_body_open(); ?>
		<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'digital-nomad' ); ?></a>
		<?php do_action( 'digitalnomad_before_header_opening' ); ?>
		<header id="placeholder" class="basic_bg">
			<div id="header" class="basic_bg"> 
				<div id="headelements">
					<div id="cell">
						<div class="inner_content">
							<div class="stabilize header_text text_shadow">
								<div class="main_header_block">
									<?php get_template_part( 'templates/header', 'logo' ); ?>
									<?php do_action( 'digitalnomad_header_middle_action' ); ?>
									<?php get_template_part( 'templates/header', 'text' ); ?>
								</div><!-- .main_header_block -->
								<noscript id="general"><?php esc_html_e( 'Some features may not work without JavaScript', 'digital-nomad' ); ?></noscript>
								<?php
									if ( is_active_sidebar( 'newsletter' ) ) {
										dynamic_sidebar( 'newsletter' );
									}
								?>
							</div><!-- .stabilize -->
							<?php if ( is_front_page() ): ?>
								<div id="jump_down">
									<a href="#content"><?php esc_html_e( 'Skip to content', 'digital-nomad' ); ?></a>
								</div>
							<?php endif; ?>
						</div><!-- .inner_content -->
					</div><!-- #cell -->
					<?php
					if ( is_archive() || is_search() || is_page() || is_404() ):
						get_template_part( 'templates/archive', 'title' );
					endif;
					?>
				</div><!-- #headelements -->
			</div><!-- #real_bg -->
		</header><!-- #header -->

		<?php do_action( 'digitalnomad_after_header_closing' ); ?>