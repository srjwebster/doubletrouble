<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/archive-product.php.
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
 * @version     2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
use Roots\Sage\Extras;

?>
<?php wc_get_template_part( 'content', 'archive-navigation' ); ?>

<?php
/**
 * woocommerce_before_main_content hook.
 *
 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
 * @hooked woocommerce_breadcrumb - 20
 * @hooked WC_Structured_Data::generate_website_data() - 30
 */
do_action( 'woocommerce_before_main_content' );
$counter          = 0; //set counter for acf division row looping.
$row_counter      = 1;
$divider_position = [];
$divider_content  = [];
$category_name = '';
(isset(get_queried_object()->term_id) ? $category_name = get_queried_object()->term_id : '')
?>
    <div class="archive-page-row">
		<?php
		if ( isset( get_queried_object()->term_id ) ) {
			if ( have_rows( 'banner_box', 'product_cat_' . $category_name ) ) {
				while ( have_rows( 'banner_box', 'product_cat_' . $category_name ) ) {
					the_row();
					if ( get_row_layout() == 'left_box' ) { ?>
                        <div class="content-box left-box-text"
                             style="background-color: <?php the_sub_field( 'background_colour' ); ?>">
                            <span class="position-center-middle"><?php echo get_sub_field( 'text' ); ?></span>
                        </div>
					<?php }
					if ( get_row_layout() == 'right_box' ) { ?>
                        <div class="content-box right-box-text hidden-sm-down"
                             style="background-color: <?php the_sub_field( 'background_colour' ); ?>">
                            <span class="position-center-middle"><?php echo get_sub_field( 'text' ); ?></span>
                        </div>
						<?php
					}
				}
			}
		}
		?>
    </div>
<?php

if ( isset( get_queried_object()->term_id ) ) {
	if ( have_rows( 'row_divider', 'product_cat_' . $category_name ) ) {
		while ( have_rows( 'row_divider', 'product_cat_' . $category_name ) ) {
			the_row();
			array_push( $divider_content, [
				'divider_position' => get_sub_field( 'row_divider_position' ),
				'divider_text'     => get_sub_field( 'row_divider_text' )
			] );
			$divider_position[] += get_sub_field( 'row_divider_position' );
		}
	}
}

?>
<?php if ( have_posts() ) :
	/**
	 * woocommerce_before_shop_loop hook.
	 *
	 * @hooked wc_print_notices - 10
	 * @hooked woocommerce_result_count - 20
	 * @hooked woocommerce_catalog_ordering - 30
	 */
	do_action( 'woocommerce_before_shop_loop' );
	?>
	<?php woocommerce_product_loop_start(); ?>

	<?php woocommerce_product_subcategories(); ?>


	<?php while ( have_posts() ) : the_post(); ?>

	<?php
	/**
	 * woocommerce_shop_loop hook.
	 *
	 * @hooked  - 10
	 */
	do_action( 'woocommerce_shop_loop' );
	$counter ++;

	if ( $counter % ( Extras\shop_loops() + 1 ) == 0 ) {
		$row_counter ++;
		$counter = 1;
	}

	wc_get_template_part( 'content', 'product' );
	if ( in_array( $row_counter, $divider_position ) ) {
		foreach ( $divider_content as $row_content ) {
			if ( $row_content['divider_position'] == $row_counter ) {
				if ( $counter % 3 == 0 ) {
					echo "<div class='archive-row-divider'>" . $row_content['divider_text'] . "</div>";
					$removerow = $row_counter - 1;
					unset( $divider_content[ $removerow ] );
				}
			}
		}
	}

endwhile; // end of the loop.
	?>

	<?php woocommerce_product_loop_end(); ?>

	<?php
	/**
	 * woocommerce_after_shop_loop hook.
	 *
	 * @hooked woocommerce_pagination - 10
	 */
	do_action( 'woocommerce_after_shop_loop' );
	?>

<?php elseif
( ! woocommerce_product_subcategories( array(
	'before' => woocommerce_product_loop_start( false ),
	'after'  => woocommerce_product_loop_end( false )
) )
) : ?>

	<?php
	/**
	 * woocommerce_no_products_found hook.
	 *
	 * @hooked wc_no_products_found - 10
	 */
	do_action( 'woocommerce_no_products_found' );
	?>

<?php endif; ?>

<?php
/**
 * woocommerce_after_main_content hook.
 *
 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
 */
do_action( 'woocommerce_after_main_content' );
?>