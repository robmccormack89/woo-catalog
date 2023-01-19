<?php
/**
 * The template for displaying general archive pages
 *
 * @package Rmcc_Theme
 */

// namespace & use
use Timber as Theme;
use Timber\PostQuery;
use Timber\Post;
use Timber\User;
use Timber\Helper;
use Timber\Term;

// set templates variable as an array
$templates = array('index.twig', 'base.twig');

// set the context
$context = Theme::context();

// author archives
if (is_author()) {
  if (isset($wp_query->query_vars['author'])) {
  	$author = new User( $wp_query->query_vars['author'] );
  	$context['author'] = $author;
  	$title  = 'Author Archives: ' . $author->name();
  }
  array_unshift($templates, 'author.twig');
}

// date archives (D)
elseif (is_day()) {
	$title = _x( 'Archive', 'Archives', 'base-theme' ) . ': ' . get_the_date('D M Y');
}

// date archives (M)
elseif (is_month()) {
	$title = _x( 'Archive', 'Archives', 'base-theme' ) . ': ' . get_the_date('M Y');
}

// date archives (Y)
elseif (is_year()) {
	$title = _x( 'Archive', 'Archives', 'base-theme' ) . ': ' . get_the_date('Y');
}

// tag archives
elseif (is_tag()) {
  $title = single_tag_title('', false);
  $term_key = 'tag';
  $term_value = get_queried_object_id();
  array_unshift($templates, 'archive-' . get_query_var( 'tag' ) . '.twig');
}

// category archives
elseif (is_category()) {

  $title = single_cat_title( '', false );
  $term_key = 'cat';
  $term_value = get_queried_object_id();

  // pre posts stuff (top of page)
  $the_stickies_args = array(
    'post_type' => get_post_type(), // for post_type archives
    'post__in'   => get_option('sticky_posts'),
  );
  if($term_key && $term_value) $the_stickies_args[$term_key] = $term_value; // add $term_key & $term_value to the query if they exist (for category & tag archives)
  $the_stickies = Theme::get_posts($the_stickies_args);

  if(get_option('sticky_posts') && !empty($the_stickies)){ // WE HAVE STICKIES AVAILABLE

    $first_sticky = array_slice($the_stickies, 0, 1);
    $highlight_post = new Post($first_sticky[0]->ID); // timber post obj. use get_post() for wp post object

    if(count($the_stickies) > 1){
      $remaining_stickies = array_slice($the_stickies, 1);
      if(!empty($remaining_stickies)){
        foreach ($remaining_stickies as $item) {
          $item = Helper::convert_wp_object($item);
          $remaining_stickies_ids_array[] = $item->id;
        }
        $rest_of_highlights = Theme::get_posts($remaining_stickies_ids_array);
      }
    }

  } else { // THERE ARE NO STICKIES

    // we only wanna do the hightlight post thing if we have more than 3 posts available
    if($context['posts']->found_posts > 3){

      $the_posts = Theme::get_posts();
      $first_post = array_slice($the_posts, 0, 1);
      $highlight_post = new Post($first_post[0]->ID);; // the first post of the main query. a timber post obj. use get_post() for wp post object

      // since we have now taken the first post out of the normal loop...
      // we now need to reset the loop (& pagination) with an offset (of 1)...
      // to take into account the highlighed post above...
      // we do this by modifying the wp_query vars
      global $wp_query;
      $current_page = get_query_var('paged');
      $paged = max(1, $current_page);
      $per_page = get_option('posts_per_page');
      $offset_count = 1;
      $offset = ($paged - 1) * $per_page + $offset_count;
      $wp_query->set('offset', $offset);
      $wp_query->set('posts_per_page', $per_page);
      $wp_query->set('paged', $paged);
      add_filter('found_posts', function($found_posts, $offset_count){ return $found_posts - $offset_count; }, 1, 2); // we also need to modify found_posts with the offset_count

      // finally, we reset the posts object, by doing a new query with the reset query vars...
      // we could use pre_get_posts for offsetting, but then it would be messier getting the first post then...
      // so better to get the main query here, reset it, & use that to do a new posts query
      $context['posts'] = new PostQuery($wp_query->query_vars);

    }

  }

	array_unshift($templates, 'archive-' . get_query_var( 'cat' ) . '.twig', 'category.twig', 'archive.twig');
}

// post_type archives
elseif (is_post_type_archive()) {
  $title = post_type_archive_title( '', false );
	array_unshift( $templates, 'archive-' . get_post_type() . '.twig' );
}

// create the archive object, and fill it
$context['archive'] = (object) [
  // "post" => $post,
  "posts" => $context['posts'],
  "highlight_post" => $highlight_post,
  "rest_of_highlights" => $rest_of_highlights,
  "title" => (is_paged()) ? $title . ' - Page ' . get_query_var('paged') : $title,
  "description" => get_the_archive_description(),
  "thumbnail" => [
    "src" => false,
    "alt" => false,
    "caption" => false
  ]
];

// & render the templates with the context
Theme::render($templates, $context);
