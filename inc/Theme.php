<?php

/**
*
* the Theme main class
*
* @package Rmcc_Theme
*
*/

// namespace & use
namespace Rmcc;
use Timber\Timber;
use Twig\Extra\String\StringExtension;

// Define paths to Twig templates
Timber::$dirname = array(
  'views',
  'views/archive',
  'views/parts',
  'views/single',
);

// set the $autoescape value
Timber::$autoescape = false;

// Define Theme Child Class
class Theme extends Timber {

  public function __construct() {
    parent::__construct();
    
    // get/set configs and logo properties
    global $configs;
    $this->configs = $configs;
    $this->logo_width = '223';
    $this->logo_height = '36';

    // regular theme stuff
    add_action('after_setup_theme', array($this, 'theme_supports'));
    add_filter('timber/context', array($this, 'add_to_context'));
    add_filter('timber/twig', array($this, 'add_to_twig'));
    add_action('init', array($this, 'register_post_types'));
    add_action('init', array($this, 'register_taxonomies'));
    add_action('init', array($this, 'register_widget_areas'));
    add_action('init', array($this, 'register_navigation_menus'));
    add_action('enqueue_block_assets', array($this, 'theme_enqueue_assets'));

  }

  /**
  *
  * theme & twig setups
  *
  */

  public function theme_supports() {

    // usual theme supports
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('menus');
    add_theme_support('post-formats', array(
      'gallery',
      'quote',
      'video',
      'aside',
      'image',
      'link'
    ));
    add_theme_support('align-wide');
    add_theme_support('responsive-embeds');
    add_theme_support('html5', array(
      'search-form',
      'comment-form',
      'comment-list',
      'gallery',
      'caption'
    ));
    add_theme_support('custom-logo', array(
      'height' => $this->logo_height,
      'width' => $this->logo_width,
      'flex-width' => true,
      'flex-height' => true
    ));

    // Add excerpts to pages
    if($this->configs['enable_page_excerpts']) add_post_type_support( 'page', 'excerpt' );

    // Remove tags support from posts
    if($this->configs['disable_post_tags']) unregister_taxonomy_for_object_type('post_tag', 'post');

    // 220 pixels wide by 180 pixels tall, hard crop mode
    // add_image_size( 'single-product-main', 500, 500, true ); 

    // for translations
    load_theme_textdomain('base-theme', get_template_directory() . '/languages');

    // escaping on some stuff set to wpautop
    remove_filter('term_description', 'wpautop');
    remove_filter('the_content', 'wpautop');
    remove_filter('the_excerpt', 'wpautop');
    remove_filter('widget_text_content', 'wpautop');
    remove_filter('widget_custom_html', 'wpautop' , 10, 3 );

    // svg
    add_action('admin_head', 'fix_svg');
    add_filter('wp_check_filetype_and_ext', 'check_filetype', 10, 4);
    add_filter('upload_mimes', 'cc_mime_types');

    // uikit active nav items
    add_filter('nav_menu_css_class', 'uikit_active_menu_items', 10, 2);
    
    // allow icon for yoast breads
    if(yoast_breadcrumb_enabled()) add_filter('wpseo_breadcrumb_separator', 'filter_wpseo_breadcrumb_separator', 10, 1);

    // preloader animation
    if($this->configs['animated_preloader']) add_filter('body_class', 'theme_preloader_body_class');

    // post comments
    if($this->configs['disable_post_comments']) add_filter('comments_array', 'disable_comments_hide_existing_comments', 10, 2);
    if($this->configs['disable_post_comments']) add_action('admin_menu', 'disable_comments_admin_menu');
    if($this->configs['disable_post_comments']) add_action('admin_init', 'disable_comments_admin_menu_redirect');
    if($this->configs['disable_post_comments']) add_action('admin_init', 'disable_comments_dashboard');
    if($this->configs['disable_post_comments']) add_action('init', 'disable_comments_admin_bar');

    // allowed html for wp kses post
    add_action('init', function(){
      global $allowedposttags;
      $allowed_atts = array (
        'align'      => array(),
        'class'      => array(),
        'type'       => array(),
        'id'         => array(),
        'dir'        => array(),
        'lang'       => array(),
        'style'      => array(),
        'xml:lang'   => array(),
        'src'        => array(),
        'alt'        => array(),
        'href'       => array(),
        'rel'        => array(),
        'rev'        => array(),
        'target'     => array(),
        'novalidate' => array(),
        'type'       => array(),
        'value'      => array(),
        'name'       => array(),
        'tabindex'   => array(),
        'action'     => array(),
        'method'     => array(),
        'for'        => array(),
        'width'      => array(),
        'height'     => array(),
        'data'       => array(),
        'title'      => array(),
        'uk-icon'      => array(),
      );
      $allowedposttags['form']     = $allowed_atts;
      $allowedposttags['label']    = $allowed_atts;
      $allowedposttags['input']    = $allowed_atts;
      $allowedposttags['textarea'] = $allowed_atts;
      $allowedposttags['iframe']   = $allowed_atts;
      $allowedposttags['script']   = $allowed_atts;
      $allowedposttags['style']    = $allowed_atts;
      $allowedposttags['strong']   = $allowed_atts;
      $allowedposttags['small']    = $allowed_atts;
      $allowedposttags['table']    = $allowed_atts;
      $allowedposttags['span']     = $allowed_atts;
      $allowedposttags['abbr']     = $allowed_atts;
      $allowedposttags['code']     = $allowed_atts;
      $allowedposttags['pre']      = $allowed_atts;
      $allowedposttags['div']      = $allowed_atts;
      $allowedposttags['img']      = $allowed_atts;
      $allowedposttags['h1']       = $allowed_atts;
      $allowedposttags['h2']       = $allowed_atts;
      $allowedposttags['h3']       = $allowed_atts;
      $allowedposttags['h4']       = $allowed_atts;
      $allowedposttags['h5']       = $allowed_atts;
      $allowedposttags['h6']       = $allowed_atts;
      $allowedposttags['ol']       = $allowed_atts;
      $allowedposttags['ul']       = $allowed_atts;
      $allowedposttags['li']       = $allowed_atts;
      $allowedposttags['em']       = $allowed_atts;
      $allowedposttags['hr']       = $allowed_atts;
      $allowedposttags['br']       = $allowed_atts;
      $allowedposttags['tr']       = $allowed_atts;
      $allowedposttags['td']       = $allowed_atts;
      $allowedposttags['p']        = $allowed_atts;
      $allowedposttags['a']        = $allowed_atts;
      $allowedposttags['b']        = $allowed_atts;
      $allowedposttags['i']        = $allowed_atts;
    }, 10);

  }

  public function theme_enqueue_assets() {

    // theme base scripts  (uikit, lightgallery, fonts-awesome)
    wp_enqueue_script(
      'base-theme',
      get_template_directory_uri() . '/assets/js/base.js',
      '',
      '',
      false
    );

    // theme base css (uikit, lightgallery, fonts-awesome)
    wp_enqueue_style(
      'base-theme',
      get_template_directory_uri() . '/assets/css/base.css'
    );

    // theme stylesheet (theme)
    wp_enqueue_style(
      'base-theme-styles', get_stylesheet_uri()
    );

    // global
    wp_enqueue_script(
      'global',
      get_template_directory_uri() . '/assets/js/global.js',
      '',
      '1.0.0',
      true
    );

  }
  public function register_post_types() {

  }
  public function register_taxonomies() {

  }
  public function register_widget_areas() {

  }
  public function register_navigation_menus() {
    register_nav_menus(array(
      'main_menu' => _x( 'Main Menu', 'Menu locations', 'base-theme' ),
      'secondary_menu' => _x( 'Secondary Menu', 'Menu locations', 'base-theme' ),
      'mobile_menu' => _x( 'Mobile Menu', 'Menu locations', 'base-theme' ),
      'iconnav_menu' => _x( 'Iconnav Menu', 'Menu locations', 'base-theme' ),
    ));
  }
  public function add_to_context($context) {

    global $configs;

    $context['site'] = new \Timber\Site;
    $context['configs'] = $configs;

    // wp customizer logo
    $theme_logo_src = wp_get_attachment_image_url(get_theme_mod('custom_logo') , 'full');
    if($theme_logo_src){
      $context['theme']->logo = (object)[];
      $context['theme']->logo->src = $theme_logo_src;
      $context['theme']->logo->alt = '';
      $context['theme']->logo->w = $this->logo_width;
      $context['theme']->logo->h = $this->logo_height;
    }

    // menu register & args
    $main_menu_args = array( 'depth' => 3 );
    $context['menu_main'] = new \Timber\Menu( 'main_menu', $main_menu_args );
    $context['menu_secondary'] = new \Timber\Menu( 'secondary_menu', $main_menu_args );
    $context['menu_mobile'] = new \Timber\Menu( 'mobile_menu', $main_menu_args );
    $foot_menu_args = array( 'depth' => 1 );
    $context['menu_iconnav'] = new \Timber\Menu( 'iconnav_menu', $foot_menu_args );
    $context['has_menu_main'] = has_nav_menu( 'main_menu' );
    $context['has_menu_secondary'] = has_nav_menu( 'secondary_menu' );
    $context['has_menu_mobile'] = has_nav_menu( 'mobile_menu' );
    $context['has_menu_iconnav'] = has_nav_menu( 'iconnav_menu' );

    $context['theme_defaults'] = $configs['theme_defaults'];

    // return context
    return $context;

  }
  public function add_to_twig($twig) {
    $twig->addExtension(new \Twig_Extension_StringLoader());
    $twig->addExtension(new StringExtension());
		return $twig;
  }

}
