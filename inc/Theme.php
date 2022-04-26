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
  'views/single',
);

// set the $autoescape value
Timber::$autoescape = false;

// Define Theme Child Class
class Theme extends Timber {
  
  public function __construct() {
    parent::__construct();
    
    global $configs;
    
    // set some Theme class properties, to be used below or elsewhere
    $this->logo_width = '223';
    $this->logo_height = '36';
    
    // theme & twig stuff
    add_action('after_setup_theme', array($this, 'theme_supports'));
		add_filter('timber/context', array($this, 'add_to_context'));
		add_filter('timber/twig', array($this, 'add_to_twig'));
		add_action('init', array($this, 'register_post_types'));
		add_action('init', array($this, 'register_taxonomies'));
    add_action('init', array($this, 'register_widget_areas'));
    add_action('init', array($this, 'register_navigation_menus'));
    add_action('enqueue_block_assets', array($this, 'theme_enqueue_assets')); // use 'theme_enqueue_assets' for frontend-only
    
    // yoast breadcrumbs mod
    add_filter('wpseo_breadcrumb_separator', array($this, 'filter_wpseo_breadcrumb_separator'), 10, 1);
    
    // remove <p> tags from archive descriptions
    remove_filter('term_description', 'wpautop');
    remove_filter('the_content', 'wpautop');
    remove_filter('the_excerpt', 'wpautop');
    remove_filter('widget_text_content', 'wpautop');
    remove_filter('widget_custom_html', 'wpautop' , 10, 3 );
    
    // adds svg support to theme
    add_filter('wp_check_filetype_and_ext', function($data, $file, $filename, $mimes) {
    
      global $wp_version;
      if ($wp_version !== '4.7.1') {
        return $data;
      }
    
      $filetype = wp_check_filetype($filename, $mimes);
    
      return [
        'ext'             => $filetype['ext'],
        'type'            => $filetype['type'],
        'proper_filename' => $data['proper_filename']
      ];
    
    }, 10, 4 );
    add_filter('upload_mimes', array($this, 'cc_mime_types'));
    add_action('admin_head', array($this, 'fix_svg'));
    
    // add custom classes to the body classes, the WP way
    if($configs['theme_preloader']){
      add_filter('body_class', function($classes){
      	$stack = $classes;
      	array_push($stack, 'no-overflow');
      	return $stack;
      });
    }
    
    // removes sticky posts from main loop
    // this function fixes issue of duplicate posts on archive
    // see https://wordpress.stackexchange.com/questions/225015/sticky-post-from-page-2-and-on
    add_action('pre_get_posts', function ($q) {
      
      // Only target the blog page // Only target the main query
      if ($q->is_home() && $q->is_main_query()) {
        
        // Remove sticky posts
        $q->set('ignore_sticky_posts', 1);
    
        // Get the sticky posts array
        $stickies = get_option('sticky_posts');
    
        // Make sure we have stickies before continuing, else, bail
        if (!$stickies) {
          return;
        }
    
        // Great, we have stickies, lets continue
        // Lets remove the stickies from the main query
        $q->set('post__not_in', $stickies);
    
        // Lets add the stickies to page one via the_posts filter
        if ($q->is_paged()) {
          return;
        }
    
        add_filter('the_posts', function ($posts, $q) use ($stickies) {
          
          // Make sure we only target the main query
          if (!$q->is_main_query()) {
            return $posts;
          }
    
          // Get the sticky posts
          $args = [
            'posts_per_page' => count($stickies),
            'post__in'       => $stickies
          ];
          $sticky_posts = get_posts($args);
    
          // Lets add the sticky posts in front of our normal posts
          $posts = array_merge($sticky_posts, $posts);
    
          return $posts;
            
        }, 10, 2);
        
      }
      
    });
    
  }
  
  // change the seperator for yoast's breadcrumb
  public function filter_wpseo_breadcrumb_separator($this_options_breadcrumbs_sep) {
  	return '<i class="fas fa-circle fa-xs"></i>';
  }
  
  // svg
  public function cc_mime_types( $mimes ){
    $mimes['svg'] = 'image/svg+xml';
    return $mimes;
  }
  public function fix_svg() {
    echo '<style type="text/css"> .attachment-266x266, .thumbnail img { width: 100%!important; height: auto!important; } </style>';
  }
  
  // theme & twig
  public function theme_supports() {
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
    load_theme_textdomain('base-theme', get_template_directory() . '/languages');
  }
  public function add_to_twig($twig) {
    $twig->addExtension(new \Twig_Extension_StringLoader());
    $twig->addExtension(new StringExtension());
		return $twig;
  }
  public function add_to_context($context) {
    
    global $snippets;
    global $configs;
    
    $context['site'] = new \Timber\Site;
    $context['configs'] = $configs;
    
    // some nice image ids: 1015, 1036, 1038, 1041, 1042, 1044, 1045, 1051, 1056, 1057, 1067, 1069, 1068, 1078, 1080, 1083, 10
    $context['theme_img_id'] = $snippets['theme_img_id'];
    $context['theme_img_src'] = 'https://picsum.photos/id/' . $snippets['theme_img_id'] . '/1920/800';

    // wp customizer logo
    $theme_logo_src = wp_get_attachment_image_url(get_theme_mod('custom_logo') , 'full');
    if($theme_logo_src){
      $context['theme']->logo = (object)[];
      $context['theme']->logo->src = $theme_logo_src;
      $context['theme']->logo->alt = '';
      $context['theme']->logo->w = $this->logo_width;
      $context['theme']->logo->h = $this->logo_height;
    }
    // $context['theme']['logo']['alt'] = '';
    // $context['theme_logo_src'] = $theme_logo_src;
    
    // menu register & args
    $context['menu_main'] = new \Timber\Menu('main_menu', array('depth' => 3));
    $context['menu_mobile'] = new \Timber\Menu('mobile_menu', array('depth' => 3));
    $context['menu_footer'] = new \Timber\Menu('footer_menu', array('depth' => 1));
    
    // check menus
    $context['has_menu_main'] = has_nav_menu('main_menu');
    $context['has_menu_mobile'] = has_nav_menu('mobile_menu');
    $context['has_menu_footer'] = has_nav_menu('footer_menu');
    
    // return context
    return $context;    
    
  }
  public function register_post_types() {
    
  }
  public function register_taxonomies() {
    
  }
  public function register_widget_areas() {
    
  }
  public function register_navigation_menus() {
    global $snippets;
    register_nav_menus(array(
      'main_menu' => $snippets['main_menu_title'],
      'mobile_menu' => $snippets['mobile_menu_title'],
      'footer_menu' => $snippets['footer_menu_title'],
    ));
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

}