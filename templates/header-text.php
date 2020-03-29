
<?php

if (has_nav_menu( 'main' )) {
    wp_nav_menu( array(
        'theme_location' => 'main',
            'container' => 'nav',
            'container_class' => 'main_menu dn_menu',
    ) );
} else {
    if (is_active_sidebar( 'header-text' )) {
        dynamic_sidebar( 'header-text' );
    }
}
