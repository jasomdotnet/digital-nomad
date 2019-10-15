<?php get_header(); ?>
<section id="main">
	<div id="content" <?php post_class( 'digitalnomad_post_container' ); ?>>
		<?php
		do_action( 'digitalnomad_before_inner_content' );

		if ( have_posts() ) :
			while ( have_posts() ) :

				the_post();
				get_template_part( 'templates/post', 'single' );

			endwhile;
		else:
			get_template_part( 'templates/post', 'none' );
		endif;

		do_action( 'digitalnomad_after_inner_content' );
		?>
	</div>
</section><!-- #main -->
<?php
get_sidebar();
get_footer();
