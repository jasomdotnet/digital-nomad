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
				<p><?php printf( __( 'Goob job, you found a page that doesn\'t exist. Try to use search form below or read my latest post: %s.', 'digital-nomad' ), digitalnomad_get_latest_article() ); ?></p>
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