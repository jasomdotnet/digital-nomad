<?php
comment_form();

if ( have_comments() ) :
	?>
	<div class="post_comments">
		<?php
		wp_list_comments( array(
			'style'			 => 'div',
			'short_ping'	 => true,
			'avatar_size'	 => null,
			'walker'		 => new Digitalnomad_Walker_Comment, 
			'max_depth'		 => 3,
		) );
		?>
	</div>
	<?php

endif;