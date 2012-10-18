<?php
/**
 * Product taxonomy template
 * Add sorting feature (by price and date):
 * 	- sort by price lowest to highest and highest to lowest
 * 	- sort by date newest to latest and latest to newest
 *
 * /!\ If you are using variable products, to make the sorting works:
 * set Regular price on every variable products ( whend editing a product,
 * in the Product Data area, navigate to the Variations tab and 
 * select "Set All: Regular Prices" in the Bulk Actions drop-down menu and apply )
 *
 * DISCLAIMER
 *
 * Do not edit or add directly to this file if you wish to upgrade Jigoshop to newer
 * versions in the future. If you wish to customise Jigoshop core for your needs,
 * please use our GitHub repository to publish essential changes for consideration.
 *
 * @file		templates/product_taxonomy.php
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
				  
	<div class="sort-products">
		<?php
		//Create URLs for sorting:
		$protocol =(isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"]=="on") ? "https://" : "http://";
		$base_url = explode('?', $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"]);
		$base_url = $protocol . $base_url[0];

		$sort_price_highest_lowest = array_merge($_GET, array(
			'orderby'	=>	'price',
			'order'		=>	'DESC'
		));
		$url_sort_price_highest_lowest = $base_url . '?' . http_build_query($sort_price_highest_lowest);
							
		$sort_price_lowest_highest = array_merge($_GET, array(
			'orderby'	=>	'price',
			'order'		=>	'ASC'
		));
		$url_sort_price_lowest_highest = $base_url . '?' . http_build_query($sort_price_lowest_highest);
		
		$sort_date_newest_latest = array_merge($_GET, array(
			'orderby'	=>	'post_date',
			'order'		=>	'DESC'
		));
		$url_sort_date_newest_latest = $base_url . '?' . http_build_query($sort_date_newest_latest);
		
		$sort_date_latest_newest = array_merge($_GET, array(
			'orderby'	=>	'post_date',
			'order'		=>	'ASC'
		));
		$url_sort_date_latest_newest = $base_url . '?' . http_build_query($sort_date_latest_newest);
		
		?>
		Sort products by price: <a href="<?php echo $url_sort_price_lowest_highest; ?>">lowest to highest</a> | <a href="<?php echo $url_sort_price_highest_lowest; ?>">highest to lowest</a> - Sort products from <a href="<?php echo $url_sort_date_latest_newest; ?>">latest to newest</a> | <a href="<?php echo $url_sort_date_newest_latest; ?>">newest to latest</a>
	</div>
	
	<?php
	$jigoshop_options = Jigoshop_Base::get_options();
	
	//Default query:
	$args_default = array(
		'post_type'          => 'product',
		'post_status'        => 'publish',
		'ignore_sticky_posts'=> 1,
		'posts_per_page'     => $jigoshop_options->get_option('jigoshop_catalog_per_page'),
		'meta_query'         => array(
			array(
				'key'    => 'visibility',
				'value'  => array( 'catalog', 'visible' ),
				'compare'=> 'IN'
			)
		)
	);
	
	global $wp_query;
	$args = $wp_query->query_vars;
	
	//Sort depending of the query:
	$orderby = (isset($_GET['orderby'])) ? $_GET['orderby'] : 'price';
	$order = (isset($_GET['order'])) ? $_GET['order'] : 'ASC';
	switch($orderby) {
		case 'price':
			$args_orderby = array(
				'orderby'	=> 'meta_value_num',
				'order'     => $order,
				'meta_key'	=> 'regular_price'
			);
			break;
		case 'post_date':
		default:
			$args_orderby = array(
				'orderby'	=> 'post_date',
				'order'     => $order
			);
			break;
	}
	$args = extend_array($args, $args_orderby);
					
	//Function to extend array:
	function extend_array($array1, $array2){
		foreach($array2 as $k => $v) {
			if(array_key_exists($k, $array1) && is_array($v))
				$array1[$k] = extend_array($array1[$k], $array2[$k]);
			else
				$array1[$k] = $v;
		}
		return $array1;
	}
	
	//Do the query:
	query_posts( $args );
	?>
	
	<?php jigoshop_get_template_part( 'loop', 'shop' ); ?>
	<?php do_action('jigoshop_pagination'); ?>
	
	<?php				
	wp_reset_query();
	?>

</div>

<?php do_action('jigoshop_after_main_content'); ?>

<?php do_action('jigoshop_sidebar'); ?>

<?php get_footer('shop'); ?>