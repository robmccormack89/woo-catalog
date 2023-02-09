<?php

/**
*
* Theme helper functions
* Functions to be called from elsehwre to do different things.
* Actions & filters should go in Theme.php or a new class altogether
* In most cases actions & filters are used to add or change core features of a theme at setup, so this makes sense.
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

// check see if queried object is for a parent archive
function is_child_term_archive($queried_object) {

  if($queried_object->parent) {
    if($queried_object->parent == '0'){

      return false;

    } else {

      return true;

    }
  }

  return false;

}
