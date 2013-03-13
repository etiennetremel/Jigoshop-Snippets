<?php
	/**
	 * SHOW MINI-CART
	 * To be added every where in a Jigoshop template
	 */

	$cart_items = jigoshop_cart::$cart_contents_count;
	$cart_total = jigoshop_cart::get_cart_total();
?>

<div class="mini-cart">
	<?php echo $cart_items; ?> item<?php ( $cart_items > 0 ) ? 's' : ''; ?>, <?php echo $cart_total; ?> | <a href="<?php echo jigoshop_cart::get_cart_url(); ?>">View Cart »</a>
</div>