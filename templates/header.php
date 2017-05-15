<header>
    <div class="full-width-container">
        <div class="shipping-banner">Free Shipping Worldwide</div>
    </div>
    <div class="container">
        <nav class="nav-top">
            <!-- left side of the navigation menu -->
			<?php
			if ( has_nav_menu( 'top_left_navigation' ) ) :
				wp_nav_menu( [
					'theme_location' => 'top_left_navigation',
					'menu_class'     => 'hidden-sm-down',
					'fallback_cb'    => false
				] );
			endif;
			?>
            <!-- space for centered logo -->
            <div class="branding"><img class="main-logo" src="<?php echo \Roots\Sage\Assets\asset_path( 'images/dt-logo-min.jpg' ); ?>">
            </div>

            <!-- right side of the navigation menu -->
			<?php
			if ( has_nav_menu( 'top_right_navigation' ) ) :
				wp_nav_menu( [
					'theme_location' => 'top_right_navigation',
					'menu_class'     => 'hidden-sm-down',
					'fallback_cb'    => false
				] );
			endif;
			?>

        </nav>
    </div>
</header>