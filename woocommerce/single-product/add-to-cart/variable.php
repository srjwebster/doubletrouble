<?php
/**
 * Variable product add to cart
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/add-to-cart/variable.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see    https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 3.0.0
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $product;

$attribute_keys = array_keys( $attributes );

do_action( 'woocommerce_before_add_to_cart_form' ); ?>

    <form class="variations_form cart" method="post" enctype='multipart/form-data'
          data-product_id="<?php echo absint( $product->get_id() ); ?>"
          data-product_variations="<?php echo htmlspecialchars( wp_json_encode( $available_variations ) ) ?>">
		<?php do_action( 'woocommerce_before_variations_form' ); ?>

		<?php if ( empty( $available_variations ) && false !== $available_variations ) : ?>
            <p class="stock out-of-stock"><?php _e( 'This product is currently out of stock and unavailable.', 'woocommerce' ); ?></p>
		<?php else : ?>
            <table class="variations" cellspacing="0">
                <tbody>
				<?php
				if ( preg_match( '/Custom/', get_the_title() ) ) { ?>
                    <tr>
                        <td class="custom-box">
                            <label for="custom-embroidery">Write it out</label>
                            <textarea data-price name="custom-embroidery-text" maxlength="30" placeholder=""
                                      rows="3"></textarea>
                            <select id="custom-embroidery-colour" class="product-options-dropdown"
                                    name="custom-embroidery-colour"
                                    data-attribute_name="attribute_pa_custom-embroidery-colour"
                                    data-show_option_none="yes">
                                <option value="">Embroidery Colour</option>
                                <option selected value="black" class="attached enabled">Black</option>
                                <option value="white" class="attached enabled">White</option>
                                <option value="pale-pink" class="attached enabled">Pale Pink</option>
                                <option value="red" class="attached enabled">Red</option>
                                <option value="silver" class="attached enabled">Silver</option>
                                <option value="gold" class="attached enabled">Gold</option>
                            </select>
                        </td>
                    </tr>
				<?php } ?>
				<?php foreach ( $attributes as $attribute_name => $options ) : ?>
                    <tr>
                        <td class="value">
							<?php
							$selected = isset( $_REQUEST[ 'attribute_' . sanitize_title( $attribute_name ) ] ) ? wc_clean( stripslashes( urldecode( $_REQUEST[ 'attribute_' . sanitize_title( $attribute_name ) ] ) ) ) : $product->get_variation_default_attribute( $attribute_name );
							wc_dropdown_variation_attribute_options( array(
								'options'          => $options,
								'attribute'        => $attribute_name,
								'product'          => $product,
								'selected'         => $selected,
								'class'            => 'product-options-dropdown',
								'show_option_none' => wc_attribute_label( $attribute_name )
							) );
							?>
                        </td>
                    </tr>
				<?php endforeach; ?>

				<?php
				if ( isset( $attributes['pa_colour'] ) ) {

				    ?>
                    <tr class="colour-example-row">

                    <td>
						<?php echo '<span style="text-transform: uppercase">' . str_replace('Custom ','', $product->get_title()) . ' COLOUR</span>';
						foreach ( $attributes['pa_colour'] as $colour ) {
							echo '<i class="fa fa-heart colour-example garment-colour-' . $colour . '" name="' . $colour . '"></i>';
						}
						?>
                    </td>
                    </tr><?php
				}

	            if ( preg_match( '/Custom/', get_the_title() ) ) { ?>
                <tr class="embroidery-colour-example-row">
                    <td>
                        <span>EMBROIDERY COLOUR</span>
                        <i class="fa fa-heart embroidery-colour-example embroidery-colour-white" name="white"></i>
                        <i class="fa fa-heart embroidery-colour-example embroidery-colour-pale-pink"
                           name="pale-pink"></i>
                        <i class="fa fa-heart embroidery-colour-example embroidery-colour-red" name="red"></i>
                        <i class="fa fa-heart embroidery-colour-example embroidery-colour-silver" name="silver"></i>
                        <i class="fa fa-heart embroidery-colour-example embroidery-colour-gold" name="gold"></i>
                        <i class="fa fa-heart embroidery-colour-example embroidery-colour-black" name="black"></i>
                    </td>
                </tr>
	            <?php } ?>
                </tbody>
            </table>

			<?php do_action( 'woocommerce_before_add_to_cart_button' ); ?>

            <div class="single_variation_wrap">
				<?php
				/**
				 * woocommerce_before_single_variation Hook.
				 */
				do_action( 'woocommerce_before_single_variation' );

				/**
				 * woocommerce_single_variation hook. Used to output the cart button and placeholder for variation data.
				 * @since 2.4.0
				 * @hooked woocommerce_single_variation - 10 Empty div for variation data.
				 * @hooked woocommerce_single_variation_add_to_cart_button - 20 Qty and cart button.
				 */
				do_action( 'woocommerce_single_variation' );

				/**
				 * woocommerce_after_single_variation Hook.
				 */
				do_action( 'woocommerce_after_single_variation' );
				?>
            </div>

			<?php do_action( 'woocommerce_after_add_to_cart_button' ); ?>
		<?php endif; ?>

		<?php do_action( 'woocommerce_after_variations_form' ); ?>
    </form>

<?php
do_action( 'woocommerce_after_add_to_cart_form' );
