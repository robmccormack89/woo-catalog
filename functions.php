<?php
/**
*
* Rmcc Theme functions and definitions...
*
* @package Rmcc_Theme
*
*/

require get_template_directory() . '/inc/extra/helpers.php';
$configs = require_once(get_template_directory() . '/inc/extra/configs.php');


/* plugin activation

  using tgm-plugin-activation

*/
require_once get_template_directory() . '/inc/lib/plugin-activation.php';

/* composer autoloader of classes

  timber should be included in packages

*/
if (file_exists($composer_autoload = __DIR__.'/vendor/autoload.php')) require_once $composer_autoload;

/* init the main theme class

  Timber should be available via composer autoload

*/
if (class_exists('Timber\Timber')) new Rmcc\Theme;

/* if Woocommerce is available (plugin is installed), do the ThemeWoo class & all the includes

  Woocommerce should be installed via required plugins

*/
if (class_exists('Timber\Timber') && class_exists('Woocommerce')) {
  function timber_set_product( $post ) {
    global $product;
    $product = wc_get_product( $post->ID );
  }
  function so44271444_woocommerce_taxonomy_args_pa_range($data) {
    $data['hierarchical'] = true;
    return $data;
  }
  add_filter('woocommerce_taxonomy_args_pa_range', 'so44271444_woocommerce_taxonomy_args_pa_range');
  // add_action( 'rest_api_init', function () {
  //   register_rest_route( 'myplugin/v1', '/authorrrr', array(
  //     'methods' => 'GET',
  //     'callback' => 'my_awesome_func',
  //   ) );
  // } );
  // function my_awesome_func( $data ) {
  //   $posts = get_posts( array(
  //     'author' => $data['id'],
  //   ) );
  //
  //   if ( empty( $posts ) ) {
  //     return null;
  //   }
  //
  //   return $posts[0]->post_title;
  // }
  new Rmcc\ThemeWoo;
  require_once get_template_directory() . '/inc/woo/helpers.php';
  require_once get_template_directory() . '/inc/woo/functions.php';
}

/* if ACF is available (plugin is installed), do the Theme ACF class

  ACf should be installed via required plugins

*/
if(class_exists('ACF')) new Rmcc\Fields;
