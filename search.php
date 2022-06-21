<?php

/**
*
* The search
*
* @package Rmcc_Theme
*
*/
 
// namespace & use
namespace Rmcc;
use Timber\PostQuery;
use Timber\Term;
use Timber\Pagination;

// set templates variable as an array
$templates = array('search.twig', 'archive.twig', 'base.twig');

// set the context
$context = Theme::context();

// set some context vars
$context['posts'] = new PostQuery(); // archive posts

// set title & description vars
$context['title'] = _x( 'Search results', 'Search results', 'base-theme' );
$context['description'] = _x( 'You have searched for:', 'Search results', 'base-theme' ) . ' "' . get_search_query() . '"';

// & render the template with the context
Theme::render($templates, $context);