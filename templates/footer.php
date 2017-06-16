<footer class="content-info">
    <div class="footer-nav hidden-sm-down">
        <ul>
            <li><a href="/returns"><p>Returns</p></a></li>
            <li><a href="/shipping"><p>Shipping</p></a></li>
            <li><a href="/terms"><p>Term & Conditions</p></a></li>
            <li><a href="/size"><p>Size Guide</p></a></li>
        </ul>
    </div>
    <div class="mobile-nav hidden-md-up">
        <ul>
            <li><a href="/shop/tees"><i class="fa fa-heart" aria-hidden="true"></i>
                    <p>T-Shirts</p></a></li>
            <li><a href="/shop/sweaters"><i class="fa fa-heart" aria-hidden="true"></i>
                    <p>Sweaters</p></a></li>
            <li><a href="/shop/hoodies"><i class="fa fa-heart" aria-hidden="true"></i>
                    <p>Hoodies</p></a></li>
            <li><a href="/shop/custom"><i class="fa fa-heart" aria-hidden="true"></i>
                    <p>Custom</p></a></li>
            <li><a href="#inline" data-lity><i class="fa fa-heart" aria-hidden="true"></i>
                    <p>More</p></a></li>
        </ul>
        <div id="inline" style="background:#fff" class="lity-hide">
            <ul class="mobile-nav-more">
                <li><a href="<?php echo home_url( "/", "relative" ); ?>"><i class="fa fa-heart" aria-hidden="true"></i>
                        <p>Home</p></a></li>
                <li><a href="/shop/tees"><i class="fa fa-heart" aria-hidden="true"></i>
                        <p>T-Shirts</p></a></li>
                <li><a href="/shop/sweaters"><i class="fa fa-heart" aria-hidden="true"></i>
                        <p>Sweaters</p></a></li>
                <li><a href="/shop/hoodies"><i class="fa fa-heart" aria-hidden="true"></i>
                        <p>Hoodies</p></a></li>
                <li><a href="/shop/custom"><i class="fa fa-heart" aria-hidden="true"></i>
                        <p>Custom</p></a></li>
                <li><a href="/shop/vouchers"><i class="fa fa-heart" aria-hidden="true"></i>
                        <p>Gift Vouchers</p></a></li>
                <li><a href="/returns"><i class="fa fa-heart" aria-hidden="true"></i>
                        <p>Returns</p></a></li>
                <li><a href="/shipping"><i class="fa fa-heart" aria-hidden="true"></i>
                        <p>Shipping</p></a></li>
                <li><a href="/terms"><i class="fa fa-heart" aria-hidden="true"></i>
                        <p>Term & Conditions</p></a></li>
                <li><a href="/size"><i class="fa fa-heart" aria-hidden="true"></i>
                        <p>Size Guide</p></a></li>
                <li class="nav-account-login">
					<?php if ( is_user_logged_in() ) {
						echo '<a href="' . get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ) . '"><i class="fa fa-heart" aria-hidden="true"></i>
<p>My Account</p></a>';
					} else {
						echo '<a href="' . get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ) . '"><i class="fa fa-heart" aria-hidden="true"></i>
<p>Login</p></a>';
					} ?>
                </li>
            </ul>
        </div>
    </div>
</footer>