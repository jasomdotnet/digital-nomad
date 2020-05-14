<footer id="footer" class="basic_bg">

    <div id="footer_inside" class="text_shadow">
		<span id="spinner"></span>
		<?php
			if (has_nav_menu( 'footer' )) {
				wp_nav_menu( array(
					'theme_location' => 'footer',
					'container' => 'nav',
					'container_class' => 'footer_menu dn_menu',
				) );
			}
			do_action( 'digitalnomad_for_footer' );
		?>
    </div>

</footer><!-- #footer -->

<div id="back-top"><a href="#top-row"></a></div>

<?php wp_footer(); ?>
<!-- 011010100110000101110011011011110110110100101110011011100110010101110100 -->
</body>
</html>