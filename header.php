<!DOCTYPE html>
<html <?php language_attributes(); ?>>
    <head>
        <meta charset="<?php bloginfo( 'charset' ); ?>">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=3.0" />
		<?php wp_head(); ?>
    </head>

    <body <?php body_class(); ?>>
		<a class="skip-link screen-reader-text" href="#content"><?php _e( 'Skip to content', 'digital-nomad' ); ?></a>
		<?php do_action( 'digitalnomad_before_header_opening' ); ?>
		<header id="placeholder" class="basic_bg">
			<div id="header" class="basic_bg"> 
				<div id="headelements">
					<div id="cell">
						<div class="inner_content">
							<div class="stabilize">
								<?php get_template_part( 'templates/header', 'logo' ); ?>
								<?php get_template_part( 'templates/header', 'text' ); ?>
								<noscript id="general"><?php _e( 'Some features may not work without JavaScript', 'digital-nomad' ); ?></noscript>
							</div>
							<?php if ( is_front_page() ): ?>
								<div id="jump_down">
									<a href="#content"><?php _e( 'Jump Down', 'digital-nomad' ); ?></a>
								</div>
							<?php endif; ?>
						</div>
					</div>
					<?php
					if ( is_archive() || is_search() || is_page() || is_404() ):
						get_template_part( 'templates/archive', 'title' );
					endif;
					?>
				</div><!-- #headelements -->
			</div><!-- #real_bg -->
		</header><!-- #header -->

		<?php do_action( 'digitalnomad_after_header_closing' ); ?>