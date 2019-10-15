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
			printf( __( 'Search Results for: %s', 'digital-nomad' ), '<em>' . get_search_query() . '</em>' );

		elseif ( is_404() ):
			_e( 'Page not found', 'digital-nomad' );

		elseif ( is_tax( 'post_format', 'post-format-aside' ) ) :
			_e( 'Asides', 'digital-nomad' );

		elseif ( is_tax( 'post_format', 'post-format-image' ) ) :
			_e( 'Images', 'digital-nomad' );

		elseif ( is_tax( 'post_format', 'post-format-video' ) ) :
			_e( 'Videos', 'digital-nomad' );

		elseif ( is_tax( 'post_format', 'post-format-quote' ) ) :
			_e( 'Quotes', 'digital-nomad' );

		elseif ( is_tax( 'post_format', 'post-format-link' ) ) :
			_e( 'Links', 'digital-nomad' );

		else :
			echo '';

		endif;
		?>
	</h1>
	<?php
	if ( is_category() ) :
		$category_description = category_description();
		if ( !empty( $category_description ) ) :
			echo apply_filters( 'category_archive_meta', '<div class="header_text archive_desc">' . $category_description . '</div>' );
		endif;

	elseif ( is_tag() ) :
		$tag_description = tag_description();
		if ( !empty( $tag_description ) ) :
			echo apply_filters( 'tag_archive_meta', '<div class="header_text archive_desc">' . $tag_description . '</div>' );
		endif;

	endif;
	?>
</div><!-- /inner_content -->
