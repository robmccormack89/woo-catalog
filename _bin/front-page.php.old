<?php

/**
*
* The frontpage template for displaying all singlular items (posts, pages & cpt singles)
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

// set templates variable as an array (requires $context['post'])
$templates  = array(
	'frontpage.twig',
  'base.twig'
);

// & render the template with the context
Theme::render($templates, $context);