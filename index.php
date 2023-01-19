<?php
/**
 * The main blog template file
 *
 * @package Rmcc_Theme
*/

// namespace & use
namespace Rmcc;
use Timber as Theme;
use Timber\PostQuery;
use Timber\Post;
use Timber\Helper;
use Timber\Term;

// set templates variable as an array
// $templates = array('blog.twig', 'archive.twig', 'base.twig');
$templates = array( 'index.twig', 'base.twig' );
if (is_home()) array_unshift( $templates, 'archive.twig' ); // if the blog IS NOT the homepage
if (is_home() && is_front_page()) array_unshift( $templates, 'sections.twig' ); // if the blog IS the homepage. if the blog is homepage is static, the controller will be singular.php

// set the context
$context = Theme::context();

// set the title for the blog page
$post = new Post();
$title = get_the_title($post->id); // title of page itself
if (is_home() && is_front_page()) $title = get_bloginfo('name'); // site title if blog is homepage

if(is_home() && !is_front_page()){

  // pre posts stuff (top of page)
  $the_stickies_args = array(
    'post__in'   => get_option('sticky_posts'), // should get the sticky posts from the current Query. check!!!
    // 'ignore_sticky_posts' => 1,
  );
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


// & render the template with the context
Theme::render($templates, $context);
