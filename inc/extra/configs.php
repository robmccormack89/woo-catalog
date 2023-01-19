<?php

/**
*
* Global theme configs to enable & disable various shit
*
* @package Rmcc_Theme
*
*/

$configs['theme_preloader'] = false;
$configs['theme_post_comments'] = true;
$configs['theme_post_share'] = false;
$configs['theme_post_paging'] = true;

$configs['acf_local_json'] = true;
$configs['acf_blocks'] = true;
$configs['acf_template_settings'] = false;
$configs['acf_options_page'] = false;

$configs['theme_defaults'] = (object) [
  "thumbnail" => [
    "src" => _x( 'https://picsum.photos/1920/540', 'Theme Featured Image - src', 'base-theme' ),
    "alt" => _x( '', 'Theme Featured Image - alt', 'base-theme' ),
    "caption" => _x( '', 'Theme Featured Image - caption', 'base-theme' )
  ]
];

return $configs;
