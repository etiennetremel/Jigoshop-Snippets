<?php
/**
 * Product taxonomy template
 * Display sub-categories instead of products when available:
 *
 * DISCLAIMER
 *
 * Do not edit or add directly to this file if you wish to upgrade Jigoshop to newer
 * versions in the future. If you wish to customise Jigoshop core for your needs,
 * please use our GitHub repository to publish essential changes for consideration.
 *
 * @file		templates/taxonomy-product_cat.php
 * @author		Etienne Tremel
 * @website		etienne.tremel@orange.fr
 * @version     0.1
 * @jigoshop	1.4.2
 */
 ?>

<?php get_header('shop'); ?>

	<?php do_action('jigoshop_before_main_content'); ?>

	<?php $term = get_term_by( 'slug', get_query_var($wp_query->query_vars['taxonomy']), $wp_query->query_vars['taxonomy']); ?>

		<h1 class="page-title"><?php echo wptexturize($term->name); ?></h1>

		<?php echo wpautop(wptexturize($term->description)); ?>

		<?php $categories = get_term_children( $term->term_id, 'product_cat' ); ?>
			<?php if ( sizeof( $categories ) ) : ?>

				<ul class="categories">
				
					<?php
					foreach ( $categories as $category ) {
						$term = get_term_by( 'id', $category, 'product_cat' );

						$title = $term->name;
						
						$thumb = jigoshop_product_cat_image( $term->term_id );
						if ( $thumb['thumb_id'] )
							$thumb_image = wp_get_attachment_image( $thumb['thumb_id'], 'shop_small' );
						else
							$thumb_image = jigoshop_get_image_placeholder();

						?>
						<li class="category">
							<a href="<?php echo get_term_link( $term->slug, 'product_cat' ); ?>" title="<?php echo $title; ?>">
								<div class="thumb"><?php echo $thumb_image; ?></div>
								<div class="title"><strong><?php echo ( strlen( $title ) > 70 ) ? substr( $title , 0, 70) . '...' : $title; ?></strong></div>
							</a>
							<a href="<?php echo get_term_link( $term->slug, 'product_cat' ); ?>" class="button" title="<?php echo __('View', 'jigoshop') . ' ' . $title; ?>"><?php echo __('View', 'jigoshop'); ?></a>
						</li>
						<?php
					}
					?>

				</ul>

				<div class="clear"></div>

			<?php else: ?>

				<?php jigoshop_get_template_part( 'loop', 'shop' ); ?>

				<?php do_action('jigoshop_pagination'); ?>

			<?php endif; ?>

	<?php do_action('jigoshop_after_main_content'); ?>
	
	<?php do_action('jigoshop_sidebar'); ?>

<?php get_footer('shop'); ?>