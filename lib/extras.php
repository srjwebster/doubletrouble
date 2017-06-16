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
	echo '<ul class="cart-area"><li class="nav-account-login  hidden-sm-down">';
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
	if ( WC()->cart->cart_contents_total != 0 ) {
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
add_filter ( 'wc_add_to_cart_message_html', __NAMESPACE__ . '\\wc_add_to_cart_message_filter', 10, 2 );
function wc_add_to_cart_message_filter($message, $product_id = null) {
	$message = 'You bagged the ' . get_the_title(key($product_id)) . '! <a href="' . wc_get_page_permalink( 'checkout' ) . '" class=""><br>Checkout Now?</a>';
	return $message;
}


// add_action(, __NAMESPACE__ . '\\', 10);

add_action( 'woocommerce_add_cart_item_data',  __NAMESPACE__ . '\\save_in_cart_my_custom_product_field', 10, 2 );
function save_in_cart_my_custom_product_field( $cart_item_data, $product_id ) {
	if( isset( $_POST['custom-embroidery'] ) ) {

		$cart_item_data[ 'custom-embroidery' ] = $_POST['custom-embroidery'];

		// When add to cart action make a unique line item
		$cart_item_data['unique_key'] = md5( microtime().rand() );
		WC()->session->set( 'custom_data', $_POST['custom-embroidery'] );
	}
	return $cart_item_data;
}

// Add the the custom product field as item meta data in the order
add_action( 'woocommerce_add_order_item_meta', __NAMESPACE__ . '\\custom_order_meta_handler', 10, 3 );
function custom_order_meta_handler( $item_id, $cart_item, $cart_item_key ) {
	$custom_field_value = $cart_item['custom-embroidery'];
	if( ! empty($custom_field_value) )
		wc_update_order_item_meta( $item_id, 'pa_custom-embroidery', $custom_field_value );
}
// Render the custom product field in cart and checkout
add_filter( 'woocommerce_get_item_data',  __NAMESPACE__ . '\\show_custom_embroidery_text_on_cart_and_checkout', 10, 2 );
function show_custom_embroidery_text_on_cart_and_checkout( $cart_data, $cart_item ) {

	$custom_items = array();

	if( !empty( $cart_data ) )
		$custom_items = $cart_data;

	if( $custom_field_value = $cart_item['custom-embroidery'] )
		$custom_items[] = array(
			'name'      => __( 'Custom Embroidery', 'woocommerce' ),
			'value'     => $custom_field_value,
			'display'   => $custom_field_value,
		);

	return $custom_items;
}

// step 1 add a location rule type
add_filter('acf/location/rule_types', __NAMESPACE__ . '\\acf_wc_product_type_rule_type');
function acf_wc_product_type_rule_type($choices) {
	// first add the "Product" Category if it does not exist
	// this will be a place to put all custom rules assocaited with woocommerce
	// the reason for checking to see if it exists or not first
	// is just in case another custom rule is added
	if (!isset($choices['Product'])) {
		$choices['Product'] = array();
	}
	// now add the 'Category' rule to it
	if (!isset($choices['Product']['product_cat'])) {
		// product_cat is the taxonomy name for woocommerce products
		$choices['Product']['product_cat_term'] = 'Product Category Term';
	}
	return $choices;
}

// step 2 skip custom rule operators, not needed


// step 3 add custom rule values
add_filter('acf/location/rule_values/product_cat_term', __NAMESPACE__ . '\\acf_wc_product_type_rule_values');
function acf_wc_product_type_rule_values($choices) {
	// basically we need to get an list of all product categories
	// and put the into an array for choices
	$args = array(
		'taxonomy' => 'product_cat',
		'hide_empty' => false
	);
	$terms = get_terms($args);
	foreach ($terms as $term) {
		$choices[$term->term_id] = $term->name;
	}
	return $choices;
}

// step 4, rule match
add_filter('acf/location/rule_match/product_cat_term', __NAMESPACE__ . '\\acf_wc_product_type_rule_match', 10, 3);
function acf_wc_product_type_rule_match($match, $rule, $options) {
	if (!isset($_GET['tag_ID'])) {
		// tag id is not set
		return $match;
	}
	if ($rule['operator'] == '==') {
		$match = ($rule['value'] == $_GET['tag_ID']);
	} else {
		$match = !($rule['value'] == $_GET['tag_ID']);
	}
	return $match;
}

// Hook in
add_filter( 'woocommerce_checkout_fields' , __NAMESPACE__ . '\\custom_override_checkout_fields' );

// Our hooked in function - $fields is passed via the filter!
function custom_override_checkout_fields( $fields ) {
	$fields['billing']['billing_address_1']['placeholder'] = '';
	$fields['billing']['billing_address_2']['placeholder'] = '';
	$fields['shipping']['shipping_address_1']['placeholder'] = '';
	$fields['shipping']['shipping_address_2']['placeholder'] = '';
	$fields['account']['account_password']['placeholder'] = 'Choose a Password';
	$fields['account']['account_password-2']['placeholder'] = 'Re-Type Password';
	$fields['order']['order_comments']['placeholder'] = 'Delivery Notes, Special Requests. Whatever You Want';
	return $fields;
}

function register_order_shipped_order_status() {
	register_post_status( 'wc-order-shipped', array(
		'label'                     => 'Order Shipped',
		'public'                    => true,
		'exclude_from_search'       => false,
		'show_in_admin_all_list'    => true,
		'show_in_admin_status_list' => true,
		'label_count'               => _n_noop( '<span class="count">(%s)</span> Order Shipped.', 'Orders Shipped <span class="count">(%s)</span>' )
	) );
}
add_action( 'init', __NAMESPACE__ . '\\register_order_shipped_order_status' );

// Add to list of WC Order statuses
function add_order_shipped_to_order_statuses( $order_statuses ) {

	$new_order_statuses = array();

	// add new order status after processing
	foreach ( $order_statuses as $key => $status ) {

		$new_order_statuses[ $key ] = $status;

		if ( 'wc-processing' === $key ) {
			$new_order_statuses['wc-order-shipped'] = 'Order Shipped';
		}
	}

	return $new_order_statuses;
}
add_filter( 'wc_order_statuses', __NAMESPACE__ . '\\add_order_shipped_to_order_statuses' );

function hide_wc_order_statuses( $order_statuses ) {

	// Hide core statuses
	unset( $order_statuses['wc-on-hold'] );
	unset( $order_statuses['wc-pending'] );
	unset( $order_statuses['wc-completed'] );

	return $order_statuses;
}
add_filter( 'wc_order_statuses', __NAMESPACE__ . '\\hide_wc_order_statuses' );