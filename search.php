<?php get_header(); ?>
<section id="main">
	<article id="content" <?php post_class( 'digitalnomad_post_container' ); ?>>
		<?php
		do_action( 'digitalnomad_before_inner_content' );

		get_template_part( 'templates/search', 'form' );

		if ( have_posts() && trim( get_search_query() ) ) :

			while ( have_posts() ) :

				the_post();
				$archive[ get_the_date( 'Y' ) ][ get_the_ID() ][ 'title' ]		 = esc_attr( get_the_title() );
				$archive[ get_the_date( 'Y' ) ][ get_the_ID() ][ 'link' ]		 = esc_url( get_the_permalink() );
				$archive[ get_the_date( 'Y' ) ][ get_the_ID() ][ 'post_date' ]	 = date_i18n( 'd. M', get_the_date( 'U' ) );

			endwhile;

			digitalnomad_the_archive_table( $archive, false, '<div class="inner_content">', '</div>' );

		else:
			get_template_part( 'templates/post', 'none' );
		endif;

		/* Restore original Post Data */
		wp_reset_postdata();

		do_action( 'digitalnomad_after_inner_content' );
		?>
	</article>
</section><!-- #main -->
<?php
get_sidebar();
get_footer();
