<?php
/**
 * Template Name: Parts by Category Template
 *
 * @package Rmcc_Theme
 */

$context = Timber::context();
$post = Timber::query_post();
$context['post'] = $post;

// Get all cats
$terms = get_terms([
  'taxonomy'    => 'product_cat',
  'hide_empty'  => true,
  'parent' => 0,
]);
if(!empty($terms)){ // if we have terms again, loop thru & set term thumb data & link
  foreach($terms as $item) {
    $item->thumbnail = get_product_term_attachments($item->term_id);
    $item->link = get_term_link($item->term_id);
  };
};
$context['terms'] = $terms;
$context['title'] = _x( 'Parts by Category', 'Parts by Category template: Title', 'base-theme' ); // get title

Timber::render(  'shop-collection.twig' , $context );