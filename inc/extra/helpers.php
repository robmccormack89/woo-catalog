<?php

/**
*
* Theme helper functions
*
* @package Rmcc_Theme
*
*/

// check if yoast_breadcrumbs are enabled
function yoast_breadcrumb_enabled() {
  
  if(class_exists('WPSEO_Options')){
    if(WPSEO_Options::get('breadcrumbs-enable', false)){
      return true;
    }
  }
  
  return false;
}

// add uk-active to active menu items
function special_nav_class ($classes, $item) {
  if (in_array('current-menu-item', $classes) ){
    $classes[] = 'uk-active ';
  }
  return $classes;
}
add_filter('nav_menu_css_class' , 'special_nav_class' , 10 , 2);