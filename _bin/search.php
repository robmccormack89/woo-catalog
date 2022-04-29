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

// globals
global $snippets; // php text snippets (translatable strings)

// set templates variable as an array
$templates = array('search.twig', 'archive.twig', 'base.twig');

// set the context
$context = Theme::context();

// set some context vars
$context['posts'] = new PostQuery(); // archive posts

// set title & description vars
$context['title'] = $snippets['search_results_title'];
$context['description'] = $snippets['search_results_description'] . ' "' . get_search_query() . '"';

// & render the template with the context
Theme::render($templates, $context);