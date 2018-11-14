<div class="front-page">
    <?php
    if (have_rows('reorderable_content')) {
        while (have_rows('reorderable_content')) {
            the_row();
            if (get_row_layout() == 'two_image_row') { ?>
                <div class="two-image-row front-page-row">
                    <div class="content-box left-box-image">
                        <a href="<?php echo get_sub_field('left_link'); ?>" class="">
                            <img src="<?php echo get_sub_field('left_image')['url']; ?>"/>
                            <span class="position-<?php echo get_sub_field('left_text_1_position'); ?>"><?php echo get_sub_field('left_text_1'); ?></span>
                            <span class="position-<?php echo get_sub_field('left_text_2_position'); ?>"><?php echo get_sub_field('left_text_2'); ?></span>
                        </a>
                    </div>
                    <div class="content-box right-box-image">
                        <a href="<?php echo get_sub_field('right_link'); ?>" class="">
                            <img src="<?php echo get_sub_field('right_image')['url']; ?>"/>
                            <span class="position-<?php echo get_sub_field('right_text_1_position'); ?>"><?php echo get_sub_field('right_text_1'); ?></span>
                            <span class="position-<?php echo get_sub_field('right_text_2_position'); ?>"><?php echo get_sub_field('right_text_2'); ?></span>
                        </a>
                    </div>
                </div>
            <?php } elseif (get_row_layout() == 'left_image_right_text_row') { ?>
                <div class="left-image-right-text-row front-page-row">
                    <div class="content-box left-box-image">
                        <a href="<?php echo get_sub_field('left_link'); ?>" class="">
                            <img src="<?php echo get_sub_field('left_image')['url']; ?>"/>
                            <span class="position-<?php echo get_sub_field('left_text_1_position'); ?>"><?php echo get_sub_field('left_text_1'); ?></span>
                            <span class="position-<?php echo get_sub_field('left_text_2_position'); ?>"><?php echo get_sub_field('left_text_2'); ?></span>
                        </a>
                    </div>
                    <div class="content-box right-box-text"
                         style="background:<?php echo get_sub_field('right_background_colour'); ?>">
                        <a href="<?php echo get_sub_field('right_link'); ?>" class="text-box">
                            <span class="position-center-middle right-text-1"><h3><?php echo get_sub_field('right_title'); ?></h3><?php echo get_sub_field('right_text_1'); ?></span>
                            <span class="position-center-bottom"><?php echo get_sub_field('right_link_text'); ?></span>
                        </a>
                    </div>
                </div>
            <?php } elseif (get_row_layout() == 'right_image_left_text_row') { ?>
                <div class="right-image-left-text-row front-page-row">
                    <div class="content-box left-box-text"
                         style="background:<?php echo get_sub_field('left_background_colour'); ?>">
                        <a href="<?php echo get_sub_field('left_link'); ?>" class="text-box">
                            <span class="position-center-middle left-text-1"><h3><?php echo get_sub_field('left_title'); ?></h3><?php echo get_sub_field('left_text_1'); ?></span>
                            <span class="position-center-bottom"><?php echo get_sub_field('left_link_text'); ?></span>
                        </a>
                    </div>
                    <div class="content-box right-box-image">
                        <a href="<?php echo get_sub_field('right_link'); ?>" class="">
                            <img src="<?php echo get_sub_field('right_image')['url']; ?>"/>
                            <span class="position-<?php echo get_sub_field('right_text_1_position'); ?>"><?php echo get_sub_field('right_text_1'); ?></span>
                            <span class="position-<?php echo get_sub_field('right_text_2_position'); ?>"><?php echo get_sub_field('right_text_2'); ?></span>
                        </a>
                    </div>
                </div>
            <?php } elseif (get_row_layout() == 'full_width_image') { ?>
                <div class="full-width-image front-page-row">
                    <div class="content-row">
                        <h2><?php echo get_sub_field('image')['url']; ?></h2>
                    </div>
                </div>
            <?php } elseif (get_row_layout() == 'full_width_text') { ?>
                <div class="full-width-text front-page-row">
                    <div class="content-row">
                        <h2><?php echo get_sub_field('text'); ?></h2>
                    </div>
                </div>
                <?php
            }
        }
    } ?>
</div>