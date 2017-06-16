<div class="front-page">
	<?php
	if ( have_rows( 'reorderable_content' ) ) {
		while ( have_rows( 'reorderable_content' ) ) {
			the_row();
			if ( get_row_layout() == 'two_image_row' ) { ?>
                <div class="two-image-row front-page-row">
                    <div class="content-box left-box-image">
                        <a href="<?php echo get_sub_field( 'left_link' ); ?>" class="">
                            <img src="<?php echo get_sub_field( 'left_image' )['url']; ?>"/>
                            <span class="position-<?php echo get_sub_field( 'left_text_1_position' ); ?>"><?php echo get_sub_field( 'left_text_1' ); ?></span>
                            <span class="position-<?php echo get_sub_field( 'left_text_2_position' ); ?>"><?php echo get_sub_field( 'left_text_2' ); ?></span>
                        </a>
                    </div>
                    <div class="content-box right-box-image">
                        <a href="<?php echo get_sub_field( 'right_link' ); ?>" class="">
                            <img src="<?php echo get_sub_field( 'right_image' )['url']; ?>"/>
                            <span class="position-<?php echo get_sub_field( 'right_text_1_position' ); ?>"><?php echo get_sub_field( 'right_text_1' ); ?></span>
                            <span class="position-<?php echo get_sub_field( 'right_text_2_position' ); ?>"><?php echo get_sub_field( 'right_text_2' ); ?></span>
                        </a>
                    </div>
                </div>
			<?php } elseif ( get_row_layout() == 'left_image_right_text_row' ) { ?>
                <div class="left-image-right-text-row front-page-row">
                    <div class="content-box left-box-image">
                        <a href="<?php echo get_sub_field( 'left_link' ); ?>" class="">
                            <img src="<?php echo get_sub_field( 'left_image' )['url']; ?>"/>
                            <span class="position-<?php echo get_sub_field( 'left_text_1_position' ); ?>"><?php echo get_sub_field( 'left_text_1' ); ?></span>
                            <span class="position-<?php echo get_sub_field( 'left_text_2_position' ); ?>"><?php echo get_sub_field( 'left_text_2' ); ?></span>
                        </a>
                    </div>
                    <div class="content-box right-box-text"
                         style="background:<?php echo get_sub_field( 'right_background_colour' ); ?>">
                        <a href="<?php echo get_sub_field( 'right_link' ); ?>" class="text-box">
                            <span class="position-center-middle right-text-1"><h3><?php echo get_sub_field( 'right_title' ); ?></h3><?php echo get_sub_field( 'right_text_1' ); ?></span>
                            <span class="position-center-bottom"><?php echo get_sub_field( 'right_link_text' ); ?></span>
                        </a>
                    </div>
                </div>
			<?php } elseif ( get_row_layout() == 'right_image_left_text_row' ) { ?>
                <div class="right-image-left-text-row front-page-row">
                    <div class="content-box left-box-text"
                         style="background:<?php echo get_sub_field( 'left_background_colour' ); ?>">
                        <a href="<?php echo get_sub_field( 'left_link' ); ?>" class="text-box">
                            <span class="position-center-middle left-text-1"><h3><?php echo get_sub_field( 'left_title' ); ?></h3><?php echo get_sub_field( 'left_text_1' ); ?></span>
                            <span class="position-center-bottom"><?php echo get_sub_field( 'left_link_text' ); ?></span>
                        </a>
                    </div>
                    <div class="content-box right-box-image">
                        <a href="<?php echo get_sub_field( 'right_link' ); ?>" class="">
                            <img src="<?php echo get_sub_field( 'right_image' )['url']; ?>"/>
                            <span class="position-<?php echo get_sub_field( 'right_text_1_position' ); ?>"><?php echo get_sub_field( 'right_text_1' ); ?></span>
                            <span class="position-<?php echo get_sub_field( 'right_text_2_position' ); ?>"><?php echo get_sub_field( 'right_text_2' ); ?></span>
                        </a>
                    </div>
                </div>
			<?php } elseif ( get_row_layout() == 'full_width_image' ) { ?>
                <div class="full-width-image front-page-row">
                    <div class="content-row">
                        <h2><?php echo get_sub_field( 'image' )['url']; ?></h2>
                    </div>
                </div>
			<?php } elseif ( get_row_layout() == 'full_width_text' ) { ?>
                <div class="full-width-text front-page-row">
                    <div class="content-row">
                        <h2><?php echo get_sub_field( 'text' ); ?></h2>
                    </div>
                </div>
				<?php
			}
		}
	} ?>
</div>

<?php

print_r();

$args = array(
	'post_type' => 'product'
);
$loop = new WP_Query( $args );
if ( $loop->have_posts() ) {
	while ( $loop->have_posts() ) : $loop->the_post();
	    $name = $product->get_title();
	    if (preg_match('/Tee/', $name)){
            $postid = $product->get_ID();

		    foreach(array_keys(WCPBC()->get_regions()) as $region){

			    update_post_meta($postid, '_euro_min_variation_price', 58);
			    update_post_meta($postid, '_euro_max_variation_price', 58);
			    update_post_meta($postid, '_euro_min_variation_regular_price', 58);
			    update_post_meta($postid, '_euro_man_variation_regular_price', 58);
			    update_post_meta($postid, '_euro_max_sale_price_variation_id', 58);
			    update_post_meta($postid, '_euro_price', 58);
			    $meta_ids = array();
			    foreach ($product->get_available_variations() as $variations){
				    array_push ($meta_ids, $variations['id']);
			    }
			    foreach($meta_ids as $meta_id){
				    update_post_meta($meta_id,'_euro_price',58);
				    update_post_meta($meta_id,'_euro_regular_price',58);
				    update_post_meta($meta_id,'_euro_price_method', 'manual');
			    }

            }

        }
	endwhile;
} else {

}
wp_reset_postdata();
?>