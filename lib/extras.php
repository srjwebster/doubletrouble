<?php

namespace Roots\Sage\Extras;

use Roots\Sage\Setup;

/**
 * Add <body> classes
 */
function body_class( $classes ) {
	// Add page slug if it doesn't exist
	if ( is_single() || is_page() && ! is_front_page() ) {
		if ( ! in_array( basename( get_permalink() ), $classes ) ) {
			$classes[] = basename( get_permalink() );
		}
	}

	// Add class if sidebar is active
	if ( Setup\display_sidebar() ) {
		$classes[] = 'sidebar-primary';
	}

	return $classes;
}

add_filter( 'body_class', __NAMESPACE__ . '\\body_class' );

/**
 * Clean up the_excerpt()
 */
function excerpt_more() {
	return ' &hellip; <a href="' . get_permalink() . '">' . __( 'Continued', 'sage' ) . '</a>';
}

add_filter( 'excerpt_more', __NAMESPACE__ . '\\excerpt_more' );


/**
 *  Adding Options Pages for ACF
 */
if ( function_exists( 'acf_add_options_page' ) ) {

	acf_add_options_page( array(
		'page_title' => 'Theme General Settings',
		'menu_title' => 'Theme Settings',
		'menu_slug'  => 'theme-general-settings',
		'capability' => 'edit_posts',
		'redirect'   => false
	) );
	acf_add_options_sub_page( array(
		'page_title'  => 'Theme Header Settings',
		'menu_title'  => 'Header',
		'parent_slug' => 'theme-general-settings',
	) );
	acf_add_options_sub_page( array(
		'page_title'  => 'Theme Sale Banner Settings',
		'menu_title'  => 'Sale Banner',
		'parent_slug' => 'theme-general-settings',
	) );
	acf_add_options_sub_page( array(
		'page_title'  => 'Theme Footer Settings',
		'menu_title'  => 'Footer',
		'parent_slug' => 'theme-general-settings',
	) );
}

// removing actions as per https://roots.io/using-woocommerce-with-sage/ guide

add_filter( 'woocommerce_show_page_title', '__return_false' );
// remove product meta information (because it's ugly)
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
// Gettin' rid of these breadcrumbs. They look bad, and aren't necessary for this build.
remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );


// Removes certain elements from the categories page that weren't wanted.
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );
// Any updates to link opening structure, place here.
remove_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10 );
add_action( 'woocommerce_before_shop_loop_item', function () {
	echo '<a href="' . get_the_permalink() . '" class="woocommerce-LoopProduct-link">';
}, 10 );

// This stops the table of additional content showing up, which includes weight, dimensions, etc.
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10 );
// Removing related product and upsell areas.
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );

// Change number or products per row to 3
add_filter( 'loop_shop_columns', __NAMESPACE__ . '\\shop_loops') ;
function shop_loops() {
	$loops = 3;
	return $loops;
};

// stop login form failing if using autocomplete
add_action( "login_form", function () {
	global $error;
	$error = true;
} );

//adds a span with the colour variations of a product as a class. Use to create swatches on the product archive pages.
add_action( 'woocommerce_after_shop_loop_item', __NAMESPACE__ . '\\colour_swatch', 15 );
function colour_swatch() {
	global $product;
	$variations = $product->get_variation_attributes();
	$colours    = [];
	foreach ( $variations as $variation ) {
		if ( isset( $variation['attributes']['attribute_pa_colour'] ) ) {
			$colours[] = $variation['attributes']['attribute_pa_colour'];
		};
	}
	if ( ! empty( $colours ) ) {
		$colours = array_unique( $colours );
		echo '<div class="colour_swatch_row">';
		foreach ( $colours as $colour ) {
			echo '<a href="' . get_the_permalink() . '?attribute_pa_colour=' . $colour . '"><span class="colour_swatch ' . $colour . '"></span></a>';
		};
		echo '</div>';
	}
}


add_action( 'right_navigation_with_cart', __NAMESPACE__ . '\\nav_account_login' );
function nav_account_login() {
	echo '<ul class="cart-area hidden-sm-down"><li class="nav-account-login">';
	if ( is_user_logged_in() ) {
		echo '<a href="' . get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ) . '">My Account</a>';
	}else{
		echo '<a href="' . get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ) . '">Login</a>';
	}
	echo '</li>';
}

add_action( 'right_navigation_with_cart', __NAMESPACE__ . '\\nav_cart_total' );
function nav_cart_total() {
	echo '<li><a class="nav-cart" href="' . wc_get_cart_url() . '">Shopping Bag ';
	if ( WC()->cart->cart_contents_total + WC()->cart->tax_total != 0 ) {
		echo '<p class="nav-cart-total"> (' . WC()->cart->get_cart_total() . ')</p>';
	}
	echo '</a></li></ul>';
}

add_filter( 'woocommerce_add_to_cart_fragments', __NAMESPACE__ . '\\woocommerce_header_add_to_cart_fragment', 10, 1 );
function woocommerce_header_add_to_cart_fragment( $fragments ) {

	$fragments['p.nav-cart-total'] = '<p class="nav-cart-total"> (' . WC()->cart->get_cart_total() . ')</p>';

	return $fragments;
}

// Changing the Add to Cart Success Message

function wc_add_to_cart_message_filter($message, $product_id = null) {
	$titles[] = get_the_title( key($product_id) );
	$titles = array_filter( $titles );
	$added_text = '';
	$message = 'You bagged the ' . get_the_title(key($product_id)) . '! <a href="' . wc_get_page_permalink( 'checkout' ) . '" class=""><br>Checkout Now?</a>';
	return $message;
}
add_filter ( 'wc_add_to_cart_message_html',  __NAMESPACE__ . '\\wc_add_to_cart_message_filter', 10, 2 );

// add_action(, __NAMESPACE__ . '\\', 10);