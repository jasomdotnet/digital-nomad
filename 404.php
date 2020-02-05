<?php get_header(); ?>
<section id="main">
	<article id="content" <?php post_class( 'digitalnomad_post_container' ); ?>>

		<?php do_action( 'digitalnomad_before_inner_content' ); ?>

		<div class="inner_content">		

			<?php do_action( 'digitalnomad_inner_content_starts' ); ?>

			<header>
				<h2 class="post_title main_title"><?php esc_html_e( 'This is famous Error 404!', 'digital-nomad' ); ?></h2>
			</header>

			<div class="post_content">
				<p>
					<?php
					esc_html_e( 'Goob job, you found a page that doesn\'t exist.', 'digital-nomad' );
					$article = digitalnomad_get_latest_article(); // data for article already escaped in digitalnomad_get_latest_article() function
					if ( !empty( $article ) ) {
						echo ' ' . esc_html__( 'Try to use search form below or read my latest post:', 'digital-nomad' ) . ' ' . $article; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					}
					?>
				</p>
				<?php get_search_form(); ?>
			</div>

			<?php do_action( 'digitalnomad_after_inner_content' ); ?>

		</div><!-- #inner_content -->

	<?php do_action( 'digitalnomad_after_inner_content' ); ?>

	</article><!-- #digitalnomad_post_container -->
</section><!-- #main -->
<?php
get_sidebar();
get_footer();
// https://stackoverflow.com/a/45485934