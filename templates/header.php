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
            <div class="branding"><a href="<?php echo home_url( "/", "relative" ); ?>"><img
                            src="<?php echo \Roots\Sage\Assets\asset_path( 'images/dt-logo-min.jpg' ); ?>"
                            class="main-logo"
                    ></a>
            </div>

            <!-- right side of the navigation menu -->
			<?php
			do_action('right_navigation_with_cart');
			?>
        </nav>
    </div>
</header>