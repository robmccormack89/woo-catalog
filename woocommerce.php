<?php
 /**
 *
 * The template for making woocommerce work with timber/twig. sets the templates & context for woo's archive & singular views
 *
 * @package Rmcc_Theme
 *
 */
 
namespace Rmcc;
use Timber\Post;
use Timber\PostQuery;

if (!class_exists('Timber')) {
  echo 'Timber not activated. Make sure you activate the plugin in <a href="/wp-admin/plugins.php#timber">/wp-admin/plugins.php</a>';
  return;
}

$context = Theme::context();

if (is_singular('product')) {
  
  $templates = array('product.twig', 'single.twig', 'base.twig');
  
  $context['post'] = new Post();
  $context['product'] = wc_get_product($context['post']->ID);
  
  $related_limit = 12;
  $related_ids = wc_get_related_products($context['post']->id, $related_limit);
  $context['related_products_title'] = apply_filters('woocommerce_product_related_products_heading', _x('Related products', 'Related products: title', 'base-theme'));
  $context['related_products'] = null;
  if ($related_ids) $context['related_products'] = new PostQuery($related_ids);
  
  $upsell_ids = $context['post']->_upsell_ids;
  $context['up_sells_title'] = apply_filters('woocommerce_product_upsells_products_heading', _x('You may also like', 'Upsell products: title', 'base-theme'));
  $context['up_sells'] = null;
  if ($upsell_ids) $context['up_sells'] = new PostQuery($upsell_ids);
  
  wp_reset_postdata();
  
  Theme::render($templates, $context);
  
} 

else {
  
  $context['posts'] = new PostQuery();
  
  $templates = array('shop.twig', 'archive.twig', 'base.twig');
  $tease_template = array('_tease-product.twig');
  $products_grid_columns = wc_get_loop_prop('columns');
  
  if (get_query_var('grid_list') == 'list-view') {
    $products_grid_columns = 1;
  	array_unshift($tease_template, '_tease-product-wide.twig');
  };
  
  if (is_tax()) {
    $queried_object = get_queried_object();
    if(is_object($queried_object)){
      $term_id = $queried_object->term_id;
      $context['term_slug'] = $queried_object->slug;
      $context['term_id'] = $term_id;
    }
    $context['title'] = single_term_title('', false); // e.g: 'Clothing'
  };
  
  if (is_shop()) {
    $shop_page_id = wc_get_page_id('shop'); 
    $context['title'] = get_the_title($shop_page_id);  // e.g: 'Shop'
    // $context['description'] = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam';
  };
  
  $context['tease_template'] = $tease_template; 
  
  if($products_grid_columns == 1) {
    $context['grid_classes'] = 'uk-child-width-1-1';
  }
  elseif($products_grid_columns == 2) {
    $context['grid_classes'] = 'uk-child-width-1-2';
  }
  elseif($products_grid_columns == 3) {
    $context['grid_classes'] = 'uk-child-width-1-2 uk-child-width-1-3@m';
  }
  elseif($products_grid_columns == 4) {
    $context['grid_classes'] = 'uk-child-width-1-2 uk-child-width-1-3@m uk-child-width-1-4@l';
  }
  elseif($products_grid_columns >= 5) {
    $context['grid_classes'] = 'uk-child-width-1-2 uk-child-width-1-3@m uk-child-width-1-4@l uk-child-width-1-'.$products_grid_columns.'@xl';
  }
  
  Theme::render($templates, $context);
  
}