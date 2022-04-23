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

// globals
global $snippets; // php text snippets (translatable strings)

// set templates variable as an array
$templates = array('404.twig', 'base.twig');

// set the context
$context = Theme::context();

// set some context vars
$context['title'] = $snippets['404_title'];
$context['description'] = $snippets['404_description'];
$context['message'] = $snippets['404_msg'];

// & render the template with the context
Theme::render($templates, $context);