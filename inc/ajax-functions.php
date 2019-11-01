<?php

if ( !function_exists( 'digitalnomad_infinite_load' ) ) {

	/**
	 * Main AJAX function
	 */
	function digitalnomad_infinite_load() {

		$arr = array();



		$page		 = digitalnomad_sanitize_validate_page( $_POST[ 'page' ] );
		$what_kind	 = digitalnomad_sanitize_what_kind( $_POST[ 'what_kind' ] );


		// extends query array
		if ( $what_kind == 'archive' ) {
			$post_type = digitalnomad_sanitize_cpt_for_archive( $_POST[ 'object' ] );
			if ( $post_type ) {
				$args = array(
					'post_status'	 => 'publish',
					'paged'			 => $page,
					'post_type'		 => $post_type,
				);
			} else {
				// force empty query on return
				$args[ 'post__in' ] = array( 0 );
			}
		} elseif ( $what_kind == 'taxonomy' ) {
			$term = digitalnomad_sanitize_cpt_for_taxonomy( $_POST[ 'object' ] );
			if ( $term ) {
				$args = array(
					'post_status'	 => 'publish',
					'paged'			 => $page,
					'tax_query'		 => array(
						array(
							'taxonomy'	 => $term->taxonomy,
							'field'		 => 'name',
							'terms'		 => $term->name,
						)
					)
				);
			} else {
				// force empty query on return
				$args[ 'post__in' ] = array( 0 );
			}
		} else {
			// force empty query on return
			$args[ 'post__in' ] = array( 0 );
		}

		/**
		 * Do a query and prints results
		 */
		$query = new WP_Query( $args );
		if ( $query->have_posts() ) :
			$arr[ 'page' ]		 = $page;
			$arr[ 'content' ]	 = '';
			while ( $query->have_posts() ) :
				$query->the_post();
				$arr[ 'content' ] .= digitalnomad_post_preview_layout( true );
			endwhile;
		else:
			// no content
			$arr[ 'noMoreContent' ] = 1;
		endif;

		wp_reset_postdata();

		echo json_encode( $arr );

		die();
	}

}
add_action( 'wp_ajax_nopriv_digitalnomad_infinite_load', 'digitalnomad_infinite_load' );
add_action( 'wp_ajax_digitalnomad_infinite_load', 'digitalnomad_infinite_load' );

if ( !function_exists( 'digitalnomad_sanitize_validate_page' ) ) {

	/**
	 * Sanitize and validate somehow $page
	 * @param type $page
	 */
	function digitalnomad_sanitize_validate_page( $page ) {
		$page = sanitize_text_field( $page );
		return ( is_numeric( $page ) && $page > 0 && $page == round( $page, 0 ) ) ? $page : 2;
	}

}
if ( !function_exists( 'digitalnomad_sanitize_validate_type' ) ) {

	/**
	 * Sanitize and validate cpt when archive
	 * @param type $type
	 */
	function digitalnomad_sanitize_cpt_for_archive( $string ) {
		$string = sanitize_text_field( $string );
		return ( in_array( $string, get_post_types() ) ) ? $string : null;
	}

}
if ( !function_exists( 'digitalnomad_sanitize_cpt_for_taxonomy' ) ) {

	/**
	 * Sanitize and validate cpt when taxonomy requested
	 * @param type $type
	 */
	function digitalnomad_sanitize_cpt_for_taxonomy( $string ) {
		$string		 = sanitize_text_field( $string );
		$term_query	 = new WP_Term_Query( array( 'name' => array( $string ) ) );
		if ( !empty( $term_query ) && !is_wp_error( $term_query ) ) {
			foreach ( $term_query->get_terms() as $term ) {
				return $term;
			}
		} else {
			return false;
		}
	}

}
if ( !function_exists( 'digitalnomad_sanitize_what_kind' ) ) {

	/**
	 * Sanitize and validate somehow $what_kind
	 * @param type $what_kind
	 */
	function digitalnomad_sanitize_what_kind( $what_kind ) {
		$what_kind = sanitize_text_field( $what_kind );
		return $what_kind;
	}

}

if ( !function_exists( 'digitalnomad_get_what_kind_details' ) ) {

	/**
	 * Returns archive kind
	 */
	function digitalnomad_get_what_kind_details() {
		if ( is_home() || is_post_type_archive() ) {
			return 'archive';
		} else {
			return 'taxonomy';
		}
	}

}

if ( !function_exists( 'digitalnomad_get_archive_post_type_object' ) ) {

	/**
	 * Return post type name or taxonomy name
	 * @return type
	 */
	function digitalnomad_get_archive_post_type_object() {
		$type = is_archive() ? get_queried_object()->name : false;
		return empty( $type ) ? 'post' : $type;
	}

}
