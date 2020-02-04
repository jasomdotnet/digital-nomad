<?php
$custom_logo_id	 = get_theme_mod( 'custom_logo' );
$custom_logo_url = wp_get_attachment_image_url( $custom_logo_id, 'medium_large' );
$alt			 = get_bloginfo( 'name' ) . ' - ' . get_bloginfo( 'description' );
?>

<a href="<?php echo esc_url( home_url() ); ?>" rel="home" class="logo_link">

	<?php if ( !empty( $custom_logo_id ) ): ?>

		<img src="<?php echo esc_url( $custom_logo_url ); ?>" alt="<?php echo esc_attr( $alt ); ?>" title="<?php echo esc_attr( $alt ); ?>" class="logo">

	<?php else: ?>

		<?php echo get_avatar( 1, 200, 'mp', $alt, array( 'class' => 'logo', 'extra_attr' => 'title=\'' . esc_attr( $alt ) . '\'' ) ); ?>

	<?php endif; ?>

</a>