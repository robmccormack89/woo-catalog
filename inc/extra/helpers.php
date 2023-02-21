<?php

// general helper function: check see if queried object is for a parent archive
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

// check if yoast_breadcrumbs are enabled
function yoast_breadcrumb_enabled() {

  if(class_exists('WPSEO_Options')){
    if(WPSEO_Options::get('breadcrumbs-enable', false)){
      return true;
    }
  }

  return false;
}

// add svg support
function check_filetype($data, $file, $filename, $mimes) {

  global $wp_version;
  if ($wp_version !== '4.7.1') {
    return $data;
  }

  $filetype = wp_check_filetype($filename, $mimes);

  return [
    'ext'             => $filetype['ext'],
    'type'            => $filetype['type'],
    'proper_filename' => $data['proper_filename']
  ];

}
function cc_mime_types( $mimes ){
  $mimes['svg'] = 'image/svg+xml';
  return $mimes;
}
function fix_svg() {
  echo '<style type="text/css"> .attachment-266x266, .thumbnail img { width: 100%!important; height: auto!important; } </style>';
}

// Disable Wordpress comments from backend & posts etc
function disable_comments_hide_existing_comments($comments) {
  $comments = array();
  return $comments;
}
function disable_comments_admin_menu() {
  remove_menu_page('edit-comments.php');
}
function disable_comments_admin_menu_redirect() {
  global $pagenow;
  if ($pagenow === 'edit-comments.php') {
    wp_redirect(admin_url()); exit;
  }
}
function disable_comments_dashboard() {
  remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal');
}
function disable_comments_admin_bar() {
  if (is_admin_bar_showing()) {
    remove_action('admin_bar_menu', 'wp_admin_bar_comments_menu', 60);
  }
}

// Add uk-active class to wordpress'active menu items
function uikit_active_menu_items($classes, $item) {
  if (in_array('current-menu-item', $classes) ){
    $classes[] = 'uk-active ';
  }
  return $classes;
}

// Theme preloader mody CSS classes
function theme_preloader_body_class($classes){ // Add some classes to the body classes array
  array_push($classes, 'no-overflow');
  return $classes;
}

// Yoast breadcrumbs - customize the sep icon
function filter_wpseo_breadcrumb_separator($this_options_breadcrumbs_sep) {
  return '<i uk-icon="icon: chevron-double-right; ratio: .8"></i>';
}