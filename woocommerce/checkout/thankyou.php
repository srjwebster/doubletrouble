<?php
/**
 * Thankyou page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/thankyou.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see        https://docs.woocommerce.com/document/template-structure/
 * @author        WooThemes
 * @package    WooCommerce/Templates
 * @version     3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
  exit;
}
?>

<div class="woocommerce-order">

  <?php if ( $order ) : ?>

    <?php if ( $order->has_status( 'failed' ) ) : ?>

    <p
      class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed"><?php _e( 'Unfortunately your order cannot be processed as the originating bank/merchant has declined your transaction. Please attempt your purchase again.',
        'woocommerce' ); ?></p>

    <p class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed-actions">
      <a href="<?php echo esc_url( $order->get_checkout_payment_url() ); ?>"
         class="button pay"><?php _e( 'Pay', 'woocommerce' ) ?></a>
      <?php if ( is_user_logged_in() ) : ?>
        <a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>"
           class="button pay"><?php _e( 'My account', 'woocommerce' ); ?></a>
      <?php endif; ?>
    </p>
  <?php else : ?>
    <script>
      jQuery(document).ready(function(){
        window.dataLayer = window.dataLayer || [];

        function gtag() {
          dataLayer.push(arguments);
        }

        gtag('js', new Date());
        gtag('event', 'conversion', {
          'send_to': 'AW-860699185/V6m3CJ_svH8QsfS0mgM',
          'value': <?php echo '"' . $order->get_total() . '"' ?>,
          'currency': <?php echo '"' . $order->get_currency() . '"' ?>,
          'transaction_id': <?php echo '"' . $order->get_id() . '"' ?>
        });

        ga('require', 'ecommerce');
        ga('ecommerce:addTransaction', {
          'id': <?php echo '"' . $order->get_id() . '"' ?>,                     // Transaction ID. Required.
          'affiliation': 'Double Trouble Gang',   // Affiliation or store name.
          'revenue': <?php echo '"' . $order->get_total() . '"' ?>,               // Grand Total.
          'shipping': <?php echo '"' . $order->get_shipping_total() . '"' ?>,                  // Shipping.
          'tax': <?php echo '"' .  $order->get_total_tax() . '"' ?>,                 // Tax.
          'currency': <?php echo '"' . $order->get_currency() . '"' ?>
        });
        ga('ecommerce:send');
      });
    </script>
    <p
      class="woocommerce-notice woocommerce-notice--success woocommerce-thankyou-order-received"><?php echo apply_filters( 'woocommerce_thankyou_order_received_text',
        __( 'Thank you. Your order has been received.', 'woocommerce' ), $order ); ?></p>

    <ul class="woocommerce-order-overview woocommerce-thankyou-order-details order_details">

      <li class="woocommerce-order-overview__order order">
        <?php _e( 'Order number:', 'woocommerce' ); ?>
        <strong><?php echo $order->get_order_number(); ?></strong>
      </li>

      <li class="woocommerce-order-overview__date date">
        <?php _e( 'Date:', 'woocommerce' ); ?>
        <strong><?php echo wc_format_datetime( $order->get_date_created() ); ?></strong>
      </li>

      <li class="woocommerce-order-overview__total total">
        <?php _e( 'Total:', 'woocommerce' ); ?>
        <strong><?php echo $order->get_formatted_order_total(); ?></strong>
      </li>

      <?php if ( $order->get_payment_method_title() ) : ?>

        <li class="woocommerce-order-overview__payment-method method">
          <?php _e( 'Payment method:', 'woocommerce' ); ?>
          <strong><?php echo wp_kses_post( $order->get_payment_method_title() ); ?></strong>
        </li>

      <?php endif; ?>

    </ul>

  <?php endif; ?>

  <?php do_action( 'woocommerce_thankyou_' . $order->get_payment_method(), $order->get_id() ); ?>
  <?php do_action( 'woocommerce_thankyou', $order->get_id() ); ?>

  <?php else : ?>

    <p
      class="woocommerce-notice woocommerce-notice--success woocommerce-thankyou-order-received"><?php echo apply_filters( 'woocommerce_thankyou_order_received_text',
        __( 'Thank you. Your order has been received.', 'woocommerce' ), null ); ?></p>

  <?php endif; ?>

</div>
<!-- Event snippet for Sale conversion page -->
