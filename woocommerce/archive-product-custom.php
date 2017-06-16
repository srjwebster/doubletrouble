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
    <div class="custom-page">
		<?php
		/**
		 * woocommerce_before_main_content hook.
		 *
		 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
		 * @hooked woocommerce_breadcrumb - 20
		 * @hooked WC_Structured_Data::generate_website_data() - 30
		 */
		do_action( 'woocommerce_before_main_content' );
		if ( isset( get_queried_object()->term_id ) ) {
			global $post;
			$category_name = get_queried_object()->term_id;
			if ( have_rows( 'banner_box', 'product_cat_' . $category_name ) ) {
				echo '<div class="custom-page-header-row">';
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
				echo '</div>';
			}

			?>
			<?php
			if ( have_rows( 'product_row', 'product_cat_' . $category_name ) ) {
				while ( have_rows( 'product_row', 'product_cat_' . $category_name ) ) {
					the_row();
					$post_object = get_sub_field( 'product_link' );
					$post        = $post_object;
					setup_postdata( $post_object );
					// check current row layout
					if ( get_row_layout() == 'left-image-right-text' ) { ?>
                        <div class="left-image-right-text-row custom-page-row">
                            <div class="content-box left-box-image">
                                <a href="<?php the_permalink(); ?>"><img src="<?php echo get_sub_field( 'product_image' ) ?>"/></a>
                            </div>
                            <div class="content-box right-box-text"
                                 style="background:<?php the_sub_field( 'background_colour' ); ?>;">
                                <a href="<?php the_permalink(); ?>">
                                    <span class="position-center-middle"><p><?php the_sub_field( 'text' ); ?></p></span>
                                    <span class="position-center-bottom"><p><?php the_sub_field( 'link_text' ); ?></p></span>
                                </a>
                            </div>
                        </div>
						<?php
						wp_reset_postdata();
					} elseif ( get_row_layout() == 'left-text-right-image' ) { ?>
                        <div class="left-text-right-image-row custom-page-row hidden-sm-down">
                            <div class="content-box left-box-text"
                                 style="background:<?php the_sub_field( 'background_colour' ); ?>;">
                                <a href="<?php the_permalink(); ?>">
                                    <span class="position-center-middle"><p><?php the_sub_field( 'text' ); ?></p></span>
                                    <span class="position-center-bottom"><p><?php the_sub_field( 'link_text' ); ?></p></span>
                                </a>
                            </div>
                            <div class="content-box right-box-image">
                                <a href="<?php the_permalink(); ?>"><img src="<?php echo get_sub_field( 'product_image' ) ?>"/></a>
                            </div>
                        </div>
                        <div class="left-image-right-text-row custom-page- hidden-md-up">
                            <div class="content-box left-box-image">
                                <a href="<?php the_permalink(); ?>"><img src="<?php echo get_sub_field( 'product_image' ) ?>"/></a>
                            </div>
                            <div class="content-box right-box-text"
                                 style="background:<?php the_sub_field( 'background_colour' ); ?>;">
                                <a href="<?php the_permalink(); ?>">
                                    <span class="position-center-middle"><p><?php the_sub_field( 'text' ); ?></p></span>
                                    <span class="position-center-bottom"><p><?php the_sub_field( 'link_text' ); ?></p></span>
                                </a>
                            </div>
                        </div>
						<?php
						wp_reset_postdata();
					}
				}
			}
		}
		?>

		<?php
		/**
		 * woocommerce_after_shop_loop hook.
		 *
		 * @hooked woocommerce_pagination - 10
		 */
		do_action( 'woocommerce_after_shop_loop' );
		?>
    </div>
<?php
/**
 * woocommerce_after_main_content hook.
 *
 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
 */
do_action( 'woocommerce_after_main_content' );