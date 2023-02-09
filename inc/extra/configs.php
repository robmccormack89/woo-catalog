<?php

/**
*
* Global theme configs to enable & disable various shit
*
* @package Rmcc_Theme
*
*/

$configs['theme_preloader'] = true;
$configs['theme_post_comments'] = true;
$configs['theme_post_share'] = false;
$configs['theme_post_paging'] = true;

$configs['acf_local_json'] = true;
$configs['acf_blocks'] = true;
$configs['acf_template_settings'] = false;
$configs['acf_options_page'] = false;

$configs['shop_filters'] = true;
$configs['inf_pagination'] = true;

$configs['theme_defaults'] = (object) [
  "thumbnail" => [
    "src" => _x( 'https://robertmccormack.com/wp-content/uploads/2023/02/solaris-blinds-day-night-1920x1200-1.jpg', 'Theme Featured Image - src', 'base-theme' ),
    "alt" => _x( '', 'Theme Featured Image - alt', 'base-theme' ),
    "caption" => _x( '', 'Theme Featured Image - caption', 'base-theme' )
  ]
];

return $configs;
