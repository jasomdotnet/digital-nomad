<div class="archive_header text_shadow inner_content">
	<h1 class="archive_title">
		<?php
		if ( is_category() ) :
			echo single_cat_title( '', false );

		elseif ( is_tag() ) :
			echo single_tag_title( '', false );

		elseif ( is_page() ) :
			the_title();

		elseif ( is_search() ) :
			/* translators: %s: search query string */
			printf( esc_html__( 'Search Results for: %s', 'digital-nomad' ), '<em>' . get_search_query() . '</em>' );

		elseif ( is_404() ):
			 esc_html_e( 'Page not found', 'digital-nomad' );

		elseif ( is_tax( 'post_format', 'post-format-aside' ) ) :
			 esc_html_e( 'Asides', 'digital-nomad' );

		elseif ( is_tax( 'post_format', 'post-format-image' ) ) :
			 esc_html_e( 'Images', 'digital-nomad' );

		elseif ( is_tax( 'post_format', 'post-format-video' ) ) :
			 esc_html_e( 'Videos', 'digital-nomad' );

		elseif ( is_tax( 'post_format', 'post-format-quote' ) ) :
			 esc_html_e( 'Quotes', 'digital-nomad' );

		elseif ( is_tax( 'post_format', 'post-format-link' ) ) :
			 esc_html_e( 'Links', 'digital-nomad' );

		else :
			echo '';

		endif;
		?>
	</h1>
	<?php
	if ( is_category() ) :
		$category_description = category_description();
		if ( !empty( $category_description ) ) :
			echo wp_kses_post( apply_filters( 'digitalnomad_category_archive_meta', '<div class="header_text archive_desc">' . $category_description . '</div>' ) );
		endif;

	elseif ( is_tag() ) :
		$tag_description = tag_description();
		if ( !empty( $tag_description ) ) :
			echo wp_kses_post( apply_filters( 'digitalnomad_tag_archive_meta', '<div class="header_text archive_desc">' . $tag_description . '</div>' ) );
		endif;

	endif;
	?>
</div><!-- /inner_content -->
