<?php

/**
*
* The 404 template
*
* @package Rmcc_Theme
*
*/

// namespace & use
namespace Rmcc;

// set templates variable as an array
$templates = array('404.twig', 'base.twig');

// set the context
$context = Theme::context();

// set some context vars
$context['title'] = _x( 'Error: Page not found', '404', 'base-theme' );
$context['description'] = _x( 'Sorry, there has been an error locating a resource for your query.', '404', 'base-theme' );

// & render the template with the context
Theme::render($templates, $context);
