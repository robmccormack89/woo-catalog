<?php

function get_parent_term_slug_from_child($child, $tax = 'product_cat'){
	$parent_id = ($child) ? $child->parent : null;
	if($parent_id){
		$parent_term = get_term($parent_id, $tax);
		$parent_slug = $parent_term->slug;
		return $parent_slug;
	}
	return '';
}

function get_values_from_array_using_key($array, $key = 'term_id'){
	if($array && count($array) > 0) {
		$new_arr = array();
		foreach($array as $item){
			if($item->$key){
				$new_arr[] = $item->$key;
			} else {
				return null;
			}
		}
		return $new_arr;
	}
	return null;
}

function get_parents_of_terms_no_repeat($terms){
	if($terms && count($terms) > 0) {
		$parents = array();
		foreach($terms as $term){
			if(!($term->parent == '0')){
				$parents[] = get_term($term->parent);
			}
		}
		// removes duplicates in multi-dimensional array  - https://stackoverflow.com/questions/307674/how-to-remove-duplicate-values-from-a-multi-dimensional-array-in-php
		return array_map("unserialize", array_unique(array_map("serialize", $parents)));
	}
}

function get_children_terms_from_terms_list($terms){
	if($terms && count($terms) > 0) {
		$arr = array();
		foreach($terms as $term){
			if(!($term->parent == '0')){
				$arr[] = $term;
			}
		}
		return $arr;
	}
}

function if_terms_contains_child($terms, $tax_key = 'product_cat') {
	if($terms && count($terms) > 0) {
		foreach($terms as $term){
			if(!($term->parent == '0')){
				return true;
			}
		}
	}
	return false;
};

function does_term_have_children($term_id, $tax_key = 'product_cat') {
	// check to see if 'product_cat' has children
  if (count(get_term_children($term_id, $tax_key)) > 0) return true;
  return false;
};

function is_term_active($slug, $q_var = null){
	if(!$q_var) $q_var = get_query_var('product_cat');
	if(is_array($q_var)){
		if(in_array($slug, $q_var)){
			return true;
		}
	} elseif(is_string($q_var)){
		if(str_contains($q_var, $slug)){
			return true;
		}
	}
	return false;
}
function is_term_parent_of_active($id, $var = 'product_cat'){
	$q_var = get_query_var($var);
	$child_term = get_term_by('slug', $q_var, $var);
	if(term_is_ancestor_of($id, $child_term, $var)){
		return true;
	}
	return false;
}

/**
*
* labels for translations (translatable strings)
*
*/

function product_posttype_labels(){

	$new_options['singular'] = _x( 'Tractor Part', 'Product post-type label: singular', 'base-theme' );
	$new_options['plural'] = _x( 'Tractor Parts', 'Product post-type label: plural', 'base-theme' );

	return $new_options;

}
function shop_sorting_options_labels(){

	$new_options['price'] = _x( 'Sort by price: low to high', 'Shop sorting options', 'base-theme' );
	$new_options['price-desc'] = _x( 'Sort by price: high to low', 'Shop sorting options', 'base-theme' );
	$new_options['menu_order'] = _x( 'Default sorting', 'Shop sorting options', 'base-theme' );
	$new_options['popularity'] = _x( 'Sort by popularity', 'Shop sorting options', 'base-theme' );
	$new_options['rating'] = _x( 'Sort by rating', 'Shop sorting options', 'base-theme' );
	$new_options['date'] = _x( 'Sort by latest', 'Shop sorting options', 'base-theme' );

	return $new_options;

}
function gridlist_labels(){

	$new_options['grid'] = _x( 'Grid View', 'Grid/List: grid label', 'base-theme' );
	$new_options['list'] = _x( 'List View', 'Grid/List: list label', 'base-theme' );

	return $new_options;

}

/**
*
* Used mainly for either clickthru system or shop filtering
*
*/

/* used for custom filters options

*/
function gridlist_badge_name() {

	$labels = gridlist_labels();

	if (get_query_var('grid_list') == 'grid-view') {
    $name = $labels['grid'];
  }
	elseif (get_query_var('grid_list') == 'list-view') {
    $name = $labels['list'];
  }

  return $name;
}
function orderby_badge_name() {

	$new_options = shop_sorting_options_labels();

	if ($_GET['orderby'] == 'price') {
    $name = $new_options['price'];
  }
	elseif ($_GET['orderby'] == 'price-desc') {
    $name = $new_options['price-desc'];
  }
	elseif ($_GET['orderby'] == 'menu_order') {
    $name = $new_options['menu_order'];
  }
	elseif ($_GET['orderby'] == 'popularity') {
    $name = $new_options['popularity'];
  }
	elseif ($_GET['orderby'] == 'rating') {
    $name = $new_options['rating'];
  }
	elseif ($_GET['orderby'] == 'date') {
    $name = $new_options['date'];
  }

  return $name;
}

/* Getting the current query var value/s with given key/s

*/
function current_product_cat_var() {
  if (isset($_GET['product_cat'])) {
    return $_GET['product_cat'];
  }
};
function current_product_series_var() {
  if (isset($_GET['product_series'])) {
    return $_GET['product_series'];
  }
};
function current_product_orderby_var() {
  if (isset($_GET['orderby'])) {
    return $_GET['orderby'];
  }
};
function current_product_gridlist_var() {
  if (isset($_GET['grid_list'])) {
    return $_GET['grid_list'];
  }
};

/* Getting the data for the product taxonomy filters (category & series)

	& when subs exists, getting them too

*/
function product_cats_for_filters() {
	// get the product cat filters
  $cats_args = array(
    'taxonomy' => 'product_cat',
    'hide_empty' => true,
    'orderby' => 'slug',
    'parent' => 0,
  );
  return get_terms($cats_args);
}
function product_cat_has_children($term_id) {
	// check to see if product cat has children
  if ( count( get_term_children( $term_id, 'product_cat' ) ) > 0 ) return true;
  return false;
};
function sub_cats_for_filters($term_id) {
	// get the sub product_cat filters based on parent term_id
  $subs_cats_args = array(
    'taxonomy' => 'product_cat',
    'hide_empty' => true,
    'orderby' => 'slug',
    'parent' => $term_id,
  );
  return get_terms($subs_cats_args);
}
function product_series_for_filters() {
	// get the product series filters
  $cats_args = array(
    'taxonomy' => 'product_series',
    'hide_empty' => true,
    'orderby' => 'slug',
    'parent' => 0,
  );
  return get_terms($cats_args);
}
function product_series_has_children($term_id) {
	// check to see if product series has children
  if ( count( get_term_children( $term_id, 'product_series' ) ) > 0 ) return true;
  return false;
};
function sub_series_for_filters($term_id) {
	// get the sub series filters based on parent term_id
  $subs_cats_args = array(
    'taxonomy' => 'product_series',
    'hide_empty' => true,
    'orderby' => 'slug',
    'parent' => $term_id,
  );
  return get_terms($subs_cats_args);
}

function product_ranges_for_filters() {
	// get the product cat filters
  $cats_args = array(
    'taxonomy' => 'pa_range',
    'hide_empty' => true,
    'orderby' => 'slug',
    'parent' => 0,
  );
  return get_terms($cats_args);
}
function product_tags_for_filters() {
	// get the product cat filters
  $cats_args = array(
    'taxonomy' => 'product_tag',
    'hide_empty' => true,
    'orderby' => 'slug',
  );
  return get_terms($cats_args);
}

/* Getting the links for the product filters using add_query_args/remove_query-args;

	Checks whether current url is an archive for a taxonomy (category & series),
	then when using filters, adds/removes the correct query-args, with the shop base, minus the taxonomy archive path,
	e.g: /product-category/clothing/hoodies -> /shop/?product_cat=hoodies

*/
function add_query_arg_product_cats_for_filters($cat_slug) {
	// add query arg link for product_cats in filters & escape the url
  // set the query arg url for product_cat from the product_cat->slug
  $query_arg_product_cats_args = array(
    'product_cat' => $cat_slug,
  );
  $the_url = add_query_arg($query_arg_product_cats_args);
  // parse the url into an array
  $parsed_url = wp_parse_url($the_url);
  // get the page path from the array
  $url_path = $parsed_url['path'];
  // the value we will use to search for in string
  $key_value = 'category';
  // find key_value string in the page path
  $found = strpos_recursive($url_path, $key_value);
  // if the 'category' exists in the current page path
  if($found) {
    // before new path
    $new_path = get_permalink(wc_get_page_id('shop'));
    // remove the current url page path from url string & set new path
    $new_path .= str_replace($url_path,'/',$the_url);
  } else {
    // if 'category' doesnt exist in current page url, set the add_query_arg url to the default state abouve
    $new_path = $the_url;
  }
	// remove_query_arg('paged', $new_path);
	$new_path = preg_replace("/page\/.\//", '', $new_path);
  // return the new path
  return esc_url($new_path);
}
function remove_query_arg_product_cats_for_filters() {
	// add query arg link for product_cats in filters & escape the url
  // paths
  $current_uri = home_url( add_query_arg( NULL, NULL ) ); // full current url with params
  $current_url_page_path = strtok($_SERVER["REQUEST_URI"], '?'); // with https & host
  // $current_url_page_path = strtok($current_uri, '?'); // without https & host
  $current_url_query_string = $_SERVER['QUERY_STRING']; // isolated query string: key=value
  // shop base url
  $shop_base = get_permalink(wc_get_page_id('shop'));
  // key values
  $query_key_value = 'product_cat';
  $cat_key_value = 'product-category';
  // check keys in paths
  $found_in_query = strpos_recursive($current_url_query_string, $query_key_value);
  $found_in_path = strpos_recursive($current_url_page_path, $cat_key_value);
	$output = $current_uri;
  // if query_string exists & contains product_cat, remove product_cat query value
  if(($found_in_query)&&(!empty($current_url_query_string))) {
    $output = remove_query_arg('product_cat');
  };
  // if page path exists & contains product-category
  if(($found_in_path)&&(!empty($current_url_page_path))) {
    $strp_path = substr_replace($current_url_page_path, '', strrpos($current_url_page_path, '/'), 1);
    $output = str_replace(home_url().$strp_path, rtrim($shop_base, '/'), rtrim($current_uri, '/'));
  };
  // return url with esc
  return esc_url($output);
}
function add_query_arg_product_series_for_filters($cat_slug) {
	// add query arg link for product_series in filters & escape the url
  // set the query arg url for product_cat from the product_cat->slug, removing _pjax
  $query_arg_product_cats_args = array(
    'product_series' => $cat_slug,
  );
  $the_url = add_query_arg($query_arg_product_cats_args);
  // parse the url into an array
  $parsed_url = wp_parse_url($the_url);
  // get the page path from the array
  $url_path = $parsed_url['path'];
  // the value we will use to search for in string
  $key_value = 'product-series-model';
  // find key_value string in the page path
  $found = strpos_recursive($url_path, $key_value);
  // if the 'category' exists in the current page path
  if($found) {
    // before new path
    $new_path = get_permalink(wc_get_page_id('shop'));
    // remove the current url page path from url string & set new path
    $new_path .= str_replace($url_path,'/',$the_url);
  } else {
    // if 'category' doesnt exist in current page url, set the add_query_arg url to the default state abouve
    $new_path = $the_url;
  }
	// remove_query_arg('paged', $new_path);
	$new_path = preg_replace("/page\/.\//", '', $new_path);
  // return the new path
  return esc_url($new_path);
}
function remove_query_arg_product_series_for_filters() {
	// add query arg link for product_series in filters & escape the url
  // paths
  $current_uri = home_url( add_query_arg( NULL, NULL ) ); // full current url with params
  $current_url_page_path = strtok($_SERVER["REQUEST_URI"], '?'); // with https & host
  // $current_url_page_path = strtok($current_uri, '?'); // without https & host
  $current_url_query_string = $_SERVER['QUERY_STRING']; // isolated query string: key=value
  // shop base url
  $shop_base = get_permalink(wc_get_page_id('shop'));
  // key values
  $query_key_value = 'product_series';
  $cat_key_value = 'product-series-model';
  // check keys in paths
  $found_in_query = strpos_recursive($current_url_query_string, $query_key_value);
  $found_in_path = strpos_recursive($current_url_page_path, $cat_key_value);
	$output = $current_uri;
  // if query_string exists & contains product_cat, remove product_cat query value
  if(($found_in_query)&&(!empty($current_url_query_string))) {
    $output = remove_query_arg('product_series');
  };
  // if page path exists & contains product-category
  if(($found_in_path)&&(!empty($current_url_page_path))) {
		$strp_path = substr_replace($current_url_page_path, '', strrpos($current_url_page_path, '/'), 1);
    $output = str_replace(home_url().$strp_path, rtrim($shop_base, '/'), rtrim($current_uri, '/'));
  };
  // return url with esc
  return esc_url($output);
}

/* Checks to see if we are on a taxonomy archive; as opposed to query var archive

*/
function is_product_series() {
	// check if is product-series via uri paramaters
  $current_url_page_path = strtok($_SERVER["REQUEST_URI"], '?');
  $cat_key_value = 'product-series-model';
  $found_in_path = strpos_recursive($current_url_page_path, $cat_key_value);
  if($found_in_path) {
    return true;
  };
}
function is_product_cat() {
	// check if is product-cat via uri paramaters
  $current_url_page_path = strtok($_SERVER["REQUEST_URI"], '?');
  $cat_key_value = 'product-category';
  $found_in_path = strpos_recursive($current_url_page_path, $cat_key_value);
  if($found_in_path) {
    return true;
  };
}

/* Get the parent terms ONLY from a given taxonomy

*/
function get_parent_product_terms($tax = 'product_cat') {
	$terms = get_terms([
	  'taxonomy'    => $tax,
	  'hide_empty'  => true,
	  'parent' => 0,
	]);
	return $terms;
}

/* Getting the thumb attachments of a product term, like 'Series ABC'

	product_series uses acf field & product_cat uses built-in meta field
	this function will be called when getting the thumbnail attachment of a given term

*/
function get_product_term_attachments($term_id, $key = 'product_cat', $thumb_key = 'thumbnail_id') {

	$thumb_src = null;
  $thumb_alt = null;

  // we will only ever be using this function to get term attachments for product_cats or product_series
  // in each case we must target different term meta: thumbnail_id & series_thumbnail

  // if term_id exists in given taxonomy, we will get the thumbnail_id using the thumb_key
  if (term_exists($term_id, $key)) {

    $thumbnail_id = get_term_meta($term_id, $thumb_key, true);

    if($thumbnail_id){
      $thumb_src = wp_get_attachment_url($thumbnail_id);
      $thumb_alt = get_post_meta($thumbnail_id, '_wp_attachment_image_alt', true);
    }

    // when no thumbnail_id (when no thumbnail is set, we will default to parent's)
    if(!$thumbnail_id) {

      // if no thumb set, get the whole term first
      $term = get_term_by('id', $term_id, $key);

      // if term has parent,
      if ($term->parent > 0) {
        $parent = get_term_by('id', $term->parent, $key); // set the parent term
        $parent_thumbnail_id = get_term_meta($parent->term_id, $thumb_key, true); // get the thumbnail id from that instead
        $thumb_src = wp_get_attachment_url($parent_thumbnail_id);
        $thumb_alt = get_post_meta($parent_thumbnail_id, '_wp_attachment_image_alt', true);
			} else {
				// what should we default to when its a child??
			}

    }

  }

  $data['src'] = $thumb_src;
  $data['alt'] = $thumb_alt;

  return $data;

}

/**
*
* Generalized helper function/s
*
*/

function strpos_recursive($haystack, $needle, $offset = 0, &$results = array()) {
	// helper function: finding strings; see below
  $offset = strpos($haystack, $needle, $offset);
  if($offset === false) {
    return $results;
  } else {
    $results[] = $offset;
    return strpos_recursive($haystack, $needle, ($offset + 1), $results);
  }
}

/**
*
* DEFUNct/REDUNDANT
*
*/

function __product_cat_has_children($term_id) {
  if ( count( get_term_children( $term_id, 'product_cat' ) ) > 0 ) {
    return true;
  } else {
    return false;
  };
};
function __product_series_has_children($term_id) {
  if ( count( get_term_children( $term_id, 'product_series' ) ) > 0 ) {
    return true;
  } else {
    return false;
  };
};
