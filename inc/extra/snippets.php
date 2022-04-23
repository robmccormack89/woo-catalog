<?php

/**
*
* Theme text snippets for translation
*
* @package Rmcc_Theme
*
*/

// IMAGES*** Theme.php

// some nice image ids: 1015, 1036, 1038, 1041, 1042, 1044, 1045, 1051, 1056, 1057, 1067, 1069, 1068, 1078, 1080, 1083, 10
$snippets['theme_img_id'] = _x( '1036', 'Lorem picsum base image id', 'base-theme' );

// archive.php
$snippets['general_archive_title'] = _x( 'Archive', 'Arhives', 'base-theme' );

// MENUS*** Theme.php

// Theme.php: menus
$snippets['main_menu_title'] = _x( 'Main Menu', 'Menu locations', 'base-theme' );
$snippets['mobile_menu_title'] = _x( 'Mobile Menu', 'Menu locations', 'base-theme' );
$snippets['footer_menu_title'] = _x( 'Footer Menu', 'Menu locations', 'base-theme' );

// search.php
$snippets['search_results_title'] = _x( 'Search results', 'Search results', 'base-theme' );
$snippets['search_results_description'] = _x( 'You have searched for:', 'Search results', 'base-theme' );

// 404.php
$snippets['404_title'] = _x( 'Error: Page not found', '404', 'base-theme' );
$snippets['404_description'] = _x( 'Sorry, there has been an error locating a resource for your query', '404', 'base-theme' );
$snippets['404_msg'] = _x( 'Try using the search form below to search this website for something else', '404', 'base-theme' );

// return the snippets
return $snippets;