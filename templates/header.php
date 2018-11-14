<header>
  <?php global $woocommerce; ?>
  <div class="full-width-container">
    <?php if ( have_rows( 'banner_text', 'option' ) ) {
      while ( have_rows( 'banner_text', 'option' ) ) {
        the_row();
        if ( ! empty( get_sub_field( 'country' ) ) ) {
          if ( ! in_array( $woocommerce->customer->get_country(), (array) get_sub_field( 'country' ) ) ) {
            continue;
          }
        }
        if ( ! empty( get_sub_field( 'exclude_country' ) ) ) {
          if ( in_array( $woocommerce->customer->get_country(), (array) get_sub_field( 'exclude_country' ) ) ) {
            continue;
          }
        }
        $symbolcodes = explode( ',', get_sub_field( 'symbol_codes' ) );
        ?>
        <div class="shipping-banner">
          <?php
          foreach ( $symbolcodes as $symbolcode ) {
            echo ' <i class="fa ' . $symbolcode . '"  aria-hidden="true" style="color:black;"></i> ';
          }
          echo '<p>' . get_sub_field( 'banner_wording' ) . '</p>';
          foreach ( array_reverse( $symbolcodes ) as $symbolcodereverse ) {
            echo ' <i class="fa ' . $symbolcodereverse . '"  aria-hidden="true" style="color:black;"></i> ';
          }

          ?>
        </div>
      <?php }
    } ?>
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
      do_action( 'right_navigation_with_cart' );
      ?>
    </nav>
  </div>
</header>
