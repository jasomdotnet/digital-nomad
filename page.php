<?php get_header(); ?>
<section id="main">
	<article id="content" <?php post_class( 'digitalnomad_post_container' ); ?>>

		<?php do_action( 'digitalnomad_before_inner_content' ); ?>

		<div class="inner_content">			

			<?php
			do_action( 'digitalnomad_inner_content_starts' );
			while ( have_posts() ) :

				the_post();
				if ( get_the_content( '' ) ):
					?>
					<div class="post_content">

						<?php do_action( 'digitalnomad_before_content_itself' ); ?>

						<?php the_content( '' ); ?>

						<?php do_action( 'digitalnomad_after_content_itself' ); ?>

					</div>
					<?php
				endif;

			endwhile;
			do_action( 'digitalnomad_inner_content_ends' );
			?>

		</div><!-- #inner_content -->

		<?php
		if ( comments_open() ):
			comments_template();
		endif;
		?>

		<?php do_action( 'digitalnomad_after_inner_content' ); ?>

	</article><!-- #digitalnomad_post_container -->
</section><!-- #main -->
<?php
get_sidebar();
get_footer();
