<?php

/**
*
* The default template for displaying all singlular items (posts, pages & cpt singles)
*
* @package Rmcc_Theme
*
*/

// namespace & use
namespace Rmcc;

use Timber\Post;

// set the context
$context = Theme::context();

// set some context vars
$context['post'] = new Post(); // the singlualr post object
$context['description'] = false;

// $context['post']->post_excerpt = ($context['post']->post_excerpt) ? $context['post']->post_excerpt
$context['post']->the_excerpt = $context['post']->post_excerpt ?: false;
// ($context['post']->post_excerpt) : $context['post']->the_excerpt = $context['post']->post_excerpt;
// var_dump($context['post']->post_excerpt);
// $context['excerpt'] = the_excerpt();

// set templates variable as an array (requires $context['post'])
$templates  = array(
	'single-' . $context['post']->ID . '.twig',
  'single-' . $context['post']->slug . '.twig',
	'single-' . $context['post']->post_type . '.twig',
	'single.twig',
  'base.twig'
);

// & render the template with the context
Theme::render($templates, $context);
