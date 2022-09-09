<?php

/**
*
* Template Name: Brochure Template
* Template Post Type: post, page
*
* @package Rmcc_Theme
*
*/

// namespace & use
namespace Rmcc;
use Timber\Post;

// globals
global $configs; // theme config settings

// set the context
$context = Theme::context();

// set some context vars
$context['post'] = new Post(); // the singular post object

// set templates variable as an array (requires $context['post'])
$templates = array(
  'single-' . $context['post']->post_type . '.twig', 
  'single.twig',
  'base.twig'
);

// add the custom template/s to the start of the templates array
array_unshift($templates, 'brochure-' . $context['post']->post_type . '.twig', 'brochure.twig',);

// & render the template with the context
Theme::render($templates, $context);