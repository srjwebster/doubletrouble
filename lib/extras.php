<?php

namespace Roots\Sage\Extras;

use Roots\Sage\Setup;

/**
 * Add <body> classes
 *
 * @param $classes
 *
 * @return array
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

	acf_add_options_page( [
		'page_title' => 'Theme General Settings',
		'menu_title' => 'Theme Settings',
		'menu_slug'  => 'theme-general-settings',
		'capability' => 'edit_posts',
		'redirect'   => false,
	] );
	acf_add_options_sub_page( [
		'page_title'  => 'Theme Header Settings',
		'menu_title'  => 'Header',
		'parent_slug' => 'theme-general-settings',
	] );
	acf_add_options_sub_page( [
		'page_title'  => 'Theme Sale Banner Settings',
		'menu_title'  => 'Sale Banner',
		'parent_slug' => 'theme-general-settings',
	] );
	acf_add_options_sub_page( [
		'page_title'  => 'Theme Footer Settings',
		'menu_title'  => 'Footer',
		'parent_slug' => 'theme-general-settings',
	] );
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
add_filter( 'loop_shop_columns', __NAMESPACE__ . '\\shop_loops' );
function shop_loops() {
	$loops = 3;

	return $loops;
}

// stop login form failing if using autocomplete
add_action( "login_form", function () {
	global $error;
	$error = true;
} );

add_action( 'right_navigation_with_cart', __NAMESPACE__ . '\\nav_account_login' );
function nav_account_login() {
	echo '<ul class="cart-area"><li class="nav-account-login  hidden-sm-down">';
	if ( is_user_logged_in() ) {
		echo '<a href="' . get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ) . '">My Account</a>';
	} else {
		echo '<a href="' . get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ) . '">Login</a>';
	}
	echo '</li>';
}

add_action( 'right_navigation_with_cart', __NAMESPACE__ . '\\nav_cart_total' );
function nav_cart_total() {
	echo '<li><a class="nav-cart" href="' . wc_get_cart_url() . '">Cart ';
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
add_filter( 'wc_add_to_cart_message_html', __NAMESPACE__ . '\\wc_add_to_cart_message_filter', 10, 2 );
function wc_add_to_cart_message_filter( $message, $product_id = null ) {
    unset($message);
	$message = 'You bagged the ' . get_the_title( key( $product_id ) ) . '! <a href="' . wc_get_page_permalink( 'checkout' )
	           . '" class=""><br>Checkout Now?</a>';

	return $message;
}

// Custom embroidery colour functions
add_action( 'woocommerce_add_cart_item_data', __NAMESPACE__ . '\\save_in_cart_custom_embroidery_colour', 10, 2 );
function save_in_cart_custom_embroidery_colour( $cart_item_data, $product_id ) {
	if ( isset( $_POST['custom-embroidery-colour'] ) ) {
		$cart_item_data['custom-embroidery-colour'] = $_POST['custom-embroidery-colour'];
		// When add to cart action make a unique line item
		$cart_item_data['unique_key'] = md5( microtime() . rand() );
		WC()->session->set( 'custom_data', $_POST['custom-embroidery-colour'] );
	}

	return $cart_item_data;
}

// Add the the custom product field as item meta data in the order
add_action( 'woocommerce_add_order_item_meta', __NAMESPACE__ . '\\custom_embroidery_colour_meta_handler', 10, 3 );
function custom_embroidery_colour_meta_handler( $item_id, $cart_item, $cart_item_key ) {
	$custom_field_value = $cart_item['custom-embroidery-colour'];
	if ( ! empty( $custom_field_value ) ) {
		wc_update_order_item_meta( $item_id, 'pa_custom-embroidery-colour', $custom_field_value );
	}
}

// Render the custom product field in cart and checkout
add_filter( 'woocommerce_get_item_data', __NAMESPACE__ . '\\show_custom_embroidery_colour_on_cart_and_checkout', 10,
	2 );
function show_custom_embroidery_colour_on_cart_and_checkout( $cart_data, $cart_item ) {

	$custom_items = [];

	if ( ! empty( $cart_data ) ) {
		$custom_items = $cart_data;
	}

	if ( $custom_field_value = $cart_item['custom-embroidery-colour'] ) {
		$custom_items[] = [
			'name'    => __( 'Embroidery Colour', 'woocommerce' ),
			'value'   => $custom_field_value,
			'display' => $custom_field_value,
		];
	}

	return $custom_items;
}

// Custom embroidery text functions
add_action( 'woocommerce_add_cart_item_data', __NAMESPACE__ . '\\save_in_cart_my_custom_embroidery_text', 10, 2 );
function save_in_cart_my_custom_embroidery_text( $cart_item_data, $product_id ) {
	if ( isset( $_POST['custom-embroidery-text'] ) ) {

		$cart_item_data['custom-embroidery-text'] = $_POST['custom-embroidery-text'];

		// When add to cart action make a unique line item
		$cart_item_data['unique_key'] = md5( microtime() . rand() );
		WC()->session->set( 'custom_data', $_POST['custom-embroidery-text'] );
	}

	return $cart_item_data;
}

// Add the the custom product field as item meta data in the order
add_action( 'woocommerce_add_order_item_meta', __NAMESPACE__ . '\\custom_embroidery_text_meta_handler', 10, 3 );
function custom_embroidery_text_meta_handler( $item_id, $cart_item, $cart_item_key ) {
	$custom_field_value = $cart_item['custom-embroidery-text'];
	if ( ! empty( $custom_field_value ) ) {
		wc_update_order_item_meta( $item_id, 'pa_custom-embroidery-text', $custom_field_value );
	}
}

// Render the custom product field in cart and checkout
add_filter( 'woocommerce_get_item_data', __NAMESPACE__ . '\\show_custom_embroidery_text_on_cart_and_checkout', 10, 2 );
function show_custom_embroidery_text_on_cart_and_checkout( $cart_data, $cart_item ) {

	$custom_items = [];

	if ( ! empty( $cart_data ) ) {
		$custom_items = $cart_data;
	}

	if ( $custom_field_value = $cart_item['custom-embroidery-text'] ) {
		$custom_items[] = [
			'name'    => __( 'Custom Embroidery', 'woocommerce' ),
			'value'   => $custom_field_value,
			'display' => $custom_field_value,
		];
	}

	return $custom_items;
}

// step 1 add a location rule type
add_filter( 'acf/location/rule_types', __NAMESPACE__ . '\\acf_wc_product_type_rule_type' );
function acf_wc_product_type_rule_type( $choices ) {
	// first add the "Product" Category if it does not exist
	// this will be a place to put all custom rules assocaited with woocommerce
	// the reason for checking to see if it exists or not first
	// is just in case another custom rule is added
	if ( ! isset( $choices['Product'] ) ) {
		$choices['Product'] = [];
	}
	// now add the 'Category' rule to it
	if ( ! isset( $choices['Product']['product_cat'] ) ) {
		// product_cat is the taxonomy name for woocommerce products
		$choices['Product']['product_cat_term'] = 'Product Category Term';
	}

	return $choices;
}

// step 2 skip custom rule operators, not needed


// step 3 add custom rule values
add_filter( 'acf/location/rule_values/product_cat_term', __NAMESPACE__ . '\\acf_wc_product_type_rule_values' );
function acf_wc_product_type_rule_values( $choices ) {
	// basically we need to get an list of all product categories
	// and put the into an array for choices
	$args  = [
		'taxonomy'   => 'product_cat',
		'hide_empty' => false,
	];
	$terms = get_terms( $args );
	foreach ( $terms as $term ) {
		$choices[ $term->term_id ] = $term->name;
	}

	return $choices;
}

// step 4, rule match
add_filter( 'acf/location/rule_match/product_cat_term', __NAMESPACE__ . '\\acf_wc_product_type_rule_match', 10, 3 );
function acf_wc_product_type_rule_match( $match, $rule, $options ) {
	if ( ! isset( $_GET['tag_ID'] ) ) {
		// tag id is not set
		return $match;
	}
	if ( $rule['operator'] == '==' ) {
		$match = ( $rule['value'] == $_GET['tag_ID'] );
	} else {
		$match = ! ( $rule['value'] == $_GET['tag_ID'] );
	}

	return $match;
}

// Hook in
add_filter( 'woocommerce_checkout_fields', __NAMESPACE__ . '\\custom_override_checkout_fields' );

// Our hooked in function - $fields is passed via the filter!
function custom_override_checkout_fields( $fields ) {
	$fields['billing']['billing_address_1']['placeholder']   = '';
	$fields['billing']['billing_address_2']['placeholder']   = '';
	$fields['shipping']['shipping_address_1']['placeholder'] = '';
	$fields['shipping']['shipping_address_2']['placeholder'] = '';
	$fields['account']['account_password']['placeholder']    = 'Choose a Password';
	$fields['account']['account_password-2']['placeholder']  = 'Re-Type Password';
	$fields['order']['order_comments']['placeholder']        = 'Delivery Notes, Special Requests. Whatever You Want';

	return $fields;
}

function register_order_shipped_order_status() {
	register_post_status( 'wc-order-shipped', [
		'label'                     => 'Order Shipped',
		'public'                    => true,
		'exclude_from_search'       => false,
		'show_in_admin_all_list'    => true,
		'show_in_admin_status_list' => true,
		'label_count'               => _n_noop( '<span class="count">(%s)</span> Order Shipped.',
			'Orders Shipped <span class="count">(%s)</span>' ),
	] );
}

add_action( 'init', __NAMESPACE__ . '\\register_order_shipped_order_status' );

// Add to list of WC Order statuses
function add_order_shipped_to_order_statuses( $order_statuses ) {

	$new_order_statuses = [];

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

use WP_Query;

function update_prices() {
	if ( isset( $_POST['submit'] ) ) {
		$args = [
			'post_type' => 'product',
		];
		$loop = new WP_Query( $args );
		if ( $loop->have_posts() ) {
			while ( $loop->have_posts() ) : $loop->the_post();
				wc_get_product();
				global $product;

				$name = $product->get_title();
				if ( preg_match( '/Tee/', $name ) ) {
					$postid   = $product->get_ID();
					$meta_ids = [];
					foreach ( $product->get_available_variations() as $variations ) {
						array_push( $meta_ids, $variations['id'] );
					}
					$price = '';
					foreach ( array_keys( \WCPBC()->get_regions() ) as $region ) {
						if ( $region == 'united-kingdom' ) {
							$price = $_POST['price_tees_united-kingdom'];
						} elseif ( $region == 'united-states' ) {
							$price = $_POST['price_tees_united-states'];
						} elseif ( $region == 'australia' ) {
							$price = $_POST['price_tees_australia'];
						} elseif ( $region == 'canada' ) {
							$price = $_POST['price_tees_canada'];
						} elseif ( $region == 'euro' ) {
							$price = $_POST['price_tees_euro'];
						} elseif ( $region == 'swedish-krona' ) {
							$price = $_POST['price_tees_swedish-krona'];
						} elseif ( $region == 'danish-krone' ) {
							$price = $_POST['price_tees_danish-krone'];
						} elseif ( $region == 'japan' ) {
							$price = $_POST['price_tees_japan'];
						} elseif ( $region == 'china' ) {
							$price = $_POST['price_tees_china'];
						} elseif ( $region == 'switzerland' ) {
							$price = $_POST['price_tees_switzerland'];
						}
						if ( ! empty( $price ) ) {
							update_post_meta( $postid, '_' . $region . '_min_variation_price', $price );
							update_post_meta( $postid, '_' . $region . '_max_variation_price', $price );
							update_post_meta( $postid, '_' . $region . '_min_variation_regular_price', $price );
							update_post_meta( $postid, '_' . $region . '_max_variation_regular_price', $price );
							update_post_meta( $postid, '_' . $region . '_price', $price );
							echo '<p>' . $name . ' for ' . $region . ' has been set to ' . $price . ' for variations ' . implode( ',',
									$meta_ids ) . '</p>';
							foreach ( $meta_ids as $meta_id ) {
								update_post_meta( $meta_id, '_' . $region . '_price', $price );
								update_post_meta( $meta_id, '_' . $region . '_regular_price', $price );
								update_post_meta( $meta_id, '_' . $region . '_price_method', 'manual' );
							}
						}

					}

				} elseif ( preg_match( '/Sweater/', $name ) ) {
					$postid   = $product->get_ID();
					$meta_ids = [];
					foreach ( $product->get_available_variations() as $variations ) {
						array_push( $meta_ids, $variations['id'] );
					}

					foreach ( array_keys( \WCPBC()->get_regions() ) as $region ) {
						if ( $region == 'united-kingdom' ) {
							$price = $_POST['price_sweaters_united-kingdom'];
						} elseif ( $region == 'united-states' ) {
							$price = $_POST['price_sweaters_united-states'];
						} elseif ( $region == 'australia' ) {
							$price = $_POST['price_sweaters_australia'];
						} elseif ( $region == 'canada' ) {
							$price = $_POST['price_sweaters_canada'];
						} elseif ( $region == 'euro' ) {
							$price = $_POST['price_sweaters_euro'];
						} elseif ( $region == 'swedish-krona' ) {
							$price = $_POST['price_sweaters_swedish-krona'];
						} elseif ( $region == 'danish-krone' ) {
							$price = $_POST['price_sweaters_danish-krone'];
						} elseif ( $region == 'japan' ) {
							$price = $_POST['price_sweaters_japan'];
						} elseif ( $region == 'china' ) {
							$price = $_POST['price_sweaters_china'];
						} elseif ( $region == 'switzerland' ) {
							$price = $_POST['price_sweaters_switzerland'];
						}
						if ( ! empty( $price ) ) {
							update_post_meta( $postid, '_' . $region . '_min_variation_price', $price );
							update_post_meta( $postid, '_' . $region . '_max_variation_price', $price );
							update_post_meta( $postid, '_' . $region . '_min_variation_regular_price', $price );
							update_post_meta( $postid, '_' . $region . '_max_variation_regular_price', $price );
							update_post_meta( $postid, '_' . $region . '_price', $price );
							echo '<p>' . $name . ' for ' . $region . ' has been set to ' . $price . ' for variations ' . implode( ',',
									$meta_ids ) . '</p>';
							foreach ( $meta_ids as $meta_id ) {
								update_post_meta( $meta_id, '_' . $region . '_price', $price );
								update_post_meta( $meta_id, '_' . $region . '_regular_price', $price );
								update_post_meta( $meta_id, '_' . $region . '_price_method', 'manual' );
							}
						}
					}

				} elseif ( preg_match( '/Hoodie/', $name ) ) {
					$postid   = $product->get_ID();
					$meta_ids = [];
					foreach ( $product->get_available_variations() as $variations ) {
						array_push( $meta_ids, $variations['id'] );
					}

					foreach ( array_keys( \WCPBC()->get_regions() ) as $region ) {
						if ( $region == 'united-kingdom' ) {
							$price = $_POST['price_hoodies_united-kingdom'];
						} elseif ( $region == 'united-states' ) {
							$price = $_POST['price_hoodies_united-states'];
						} elseif ( $region == 'australia' ) {
							$price = $_POST['price_hoodies_australia'];
						} elseif ( $region == 'canada' ) {
							$price = $_POST['price_hoodies_canada'];
						} elseif ( $region == 'euro' ) {
							$price = $_POST['price_hoodies_euro'];
						} elseif ( $region == 'swedish-krona' ) {
							$price = $_POST['price_hoodies_swedish-krona'];
						} elseif ( $region == 'danish-krone' ) {
							$price = $_POST['price_hoodies_danish-krone'];
						} elseif ( $region == 'japan' ) {
							$price = $_POST['price_hoodies_japan'];
						} elseif ( $region == 'china' ) {
							$price = $_POST['price_hoodies_china'];
						} elseif ( $region == 'switzerland' ) {
							$price = $_POST['price_hoodies_switzerland'];
						}
						if ( ! empty( $price ) ) {
							update_post_meta( $postid, '_' . $region . '_min_variation_price', $price );
							update_post_meta( $postid, '_' . $region . '_max_variation_price', $price );
							update_post_meta( $postid, '_' . $region . '_min_variation_regular_price', $price );
							update_post_meta( $postid, '_' . $region . '_max_variation_regular_price', $price );
							update_post_meta( $postid, '_' . $region . '_price', $price );
							echo '<p>' . $name . ' for ' . $region . ' has been set to ' . $price . ' for variations ' . implode( ',',
									$meta_ids ) . '</p>';
							foreach ( $meta_ids as $meta_id ) {
								update_post_meta( $meta_id, '_' . $region . '_price', $price );
								update_post_meta( $meta_id, '_' . $region . '_regular_price', $price );
								update_post_meta( $meta_id, '_' . $region . '_price_method', 'manual' );
							}
						}
					}
				}
			endwhile;
		}
		wp_reset_postdata();
	}
}

add_action( 'admin_menu', __NAMESPACE__ . '\\set_product_prices_menu' );

function set_product_prices_menu() {
	add_menu_page( 'Set Product Prices Page', 'Set Product Prices', 'manage_options', 'set-product-prices',
		__NAMESPACE__ . '\\set_product_prices_admin_page' );

}

function set_product_prices_admin_page() {

	if ( ! isset( $_POST['submit'] ) ) {
		?>
        <h3>Set Prices Automatically</h3>
        <form method="post" action="<?php echo basename( get_permalink() ); ?>">
            <table>
                <tr>
                    <th>Countries</th>
                    <th>Tees</th>
                    <th>Sweaters</th>
                    <th>Hoodies</th>
                </tr>
				<?php
				foreach ( array_keys( \WCPBC()->get_regions() ) as $region ) {
					echo '<tr><td>' . $region . '</td><td><input name="price_tees_' . $region . '" type="text"></td>';
					echo '<td><input name="price_sweaters_' . $region . '" type="text"></td>';
					echo '<td><input name="price_hoodies_' . $region . '" type="text"></td></tr>';
				}
				?>
            </table>
            <span><label for="setprice-exclusions">Post IDs to exclude seperated by commas</label><input id="setprice-exclusions" type="text" name="exclusions"></span><br/>
            <span><input type="submit" value="Set Prices" name="submit"></span>
        </form>
		<?php
	} else {
		update_prices();
	}
}

// Remove WP Version From Styles
add_filter( 'style_loader_src', __NAMESPACE__ . '\\sdt_remove_ver_css_js', 9999 );
// Remove WP Version From Scripts
add_filter( 'script_loader_src', __NAMESPACE__ . '\\sdt_remove_ver_css_js', 9999 );

// Function to remove version numbers
function sdt_remove_ver_css_js( $src ) {
	if ( strpos( $src, 'ver=' ) ) {
		$src = remove_query_arg( 'ver', $src );
	}

	return $src;
}

add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\\google_scripts', 100 );
function google_scripts() {
	wp_register_script( 'google-analytics', 'https://www.googletagmanager.com/gtag/js?id=UA-70693713-1', [ 'sage/js' ] );
	wp_enqueue_script( 'google-analytics' );

	if ( is_singular( 'product' ) ) {
		wp_localize_script( 'sage/js', 'itemVariables',
			[
				'ID'       => get_the_ID(),
				'Category' => wc_get_product_cat_ids( get_the_ID() )[0],
				'User'     => get_current_user_id(),
			] );
	}
}

add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\\loupe_script', 100 );

function loupe_script() {
	if ( is_singular( 'product' ) && ! in_array( 'custom', wc_get_product_cat_ids( get_the_ID() ) ) ) {
		wp_register_script( 'loupe', \Roots\Sage\Assets\asset_path( 'scripts/loupe.js' ), [ 'jquery' ], null,
			true );
		wp_enqueue_script( 'loupe' );
	}
}

add_filter( 'script_loader_tag', __NAMESPACE__ . '\\add_async_attribute', 10, 2 );

function add_async_attribute( $tag, $handle ) {
	// add script handles to the array below
	$scripts_to_async = [ 'loupe' ];

	foreach ( $scripts_to_async as $async_script ) {
		if ( 'google-analytics' === $handle ) {
			return '<!-- Global site tag (gtag.js) - Google Analytics -->' . str_replace( ' src', ' async="async" src',
					$tag );
		} elseif ( $async_script === $handle ) {
			return str_replace( ' src', ' async="async" src', $tag );
		}
	}

	return $tag;
}

function payment_gateway_disable_country( $available_gateways ) {
	global $woocommerce;
	if ( is_admin() ) {
		return $available_gateways;
	} elseif ( isset( $available_gateways['afterpay'] ) && $woocommerce->customer->get_billing_country() !== 'AU' ) {
		unset( $available_gateways['afterpay'] );
	}

	return $available_gateways;
}

add_filter( 'woocommerce_available_payment_gateways', __NAMESPACE__ . '\\payment_gateway_disable_country' );

add_filter( 'loop_shop_per_page', __NAMESPACE__ . '\\new_loop_shop_per_page', 20 );

function new_loop_shop_per_page( $cols ) {
    unset($cols);
	// $cols contains the current number of products per page based on the value stored on Options -> Reading
	// Return the number of products you wanna show per page.
	$cols = 1000;
	return $cols;
}