<header class="banner">
  <div class="full-width-container">
      <div class="toggle-mobile-nav hidden-md-up">
          <div class="icon-mobile_menu"></div>
      </div>
      <div class="shipping-banner">Free Shipping Worldwide</div>
  </div>
  <div class="container">
    <a class="brand" href="<?= esc_url(home_url('/')); ?>"><?php bloginfo('name'); ?></a>
    <nav class="nav-top">

<!-- left side of the navigation menu -->
        <?php
      if (has_nav_menu('top_left_navigation')) :
        wp_nav_menu(['theme_location' => 'top_left_navigation', 'menu_class' => 'nav hidden-sm-down', 'fallback_cb' => false]);
      endif;
      ?>
<!-- space for centered logo -->


<!-- right side of the navigation menu -->
      <?php
      if (has_nav_menu('top_right_navigation')) :
	      wp_nav_menu(['theme_location' => 'top_right_navigation', 'menu_class' => 'nav hidden-sm-down', 'fallback_cb' => false]);
      endif;
      ?>

    </nav>
  </div>
</header>