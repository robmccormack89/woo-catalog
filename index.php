<?php
/**
 * The main blog template file
 *
 * @package Rmcc_Theme
*/

// namespace & use
namespace Rmcc;

use Timber\PostQuery;
use Timber\Post;

// set templates variable as an array
// $templates = array('blog.twig', 'archive.twig', 'base.twig');
$templates = array( 'base.twig' );
// if (is_home()) array_unshift( $templates, 'archive.twig' ); // if the blog IS NOT the homepage

// set the context
$context = Theme::context();

// set the title for the blog page
$post = new Post();
$title = get_the_title($post->id); // title of page itself
if (is_home() && is_front_page()) $title = get_bloginfo('name'); // site title if blog is homepage

// create the archive object, and fill it
$context['archive'] = (object) [
  "posts" => $context['posts'],
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
