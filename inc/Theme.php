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
  'views/single',
);

// set the $autoescape value
Timber::$autoescape = false;

// Define Theme Child Class
class Theme extends Timber {
  
  public function __construct() {
    parent::__construct();
    
    /**
    *
    * Class properties & globals
    *
    */
    
    global $configs;
    $this->configs = $configs;
    $this->logo_width = '223';
    $this->logo_height = '36';
    
    /**
    *
    * theme & twig
    *
    */
    
    add_action('after_setup_theme', array($this, 'theme_supports'));
		add_filter('timber/context', array($this, 'add_to_context'));
		add_filter('timber/twig', array($this, 'add_to_twig'));
		add_action('init', array($this, 'register_post_types'));
		add_action('init', array($this, 'register_taxonomies'));
    add_action('init', array($this, 'register_widget_areas'));
    add_action('init', array($this, 'register_navigation_menus'));
    add_action('enqueue_block_assets', array($this, 'theme_enqueue_assets')); // use 'theme_enqueue_assets' for frontend-only
    
    /**
    *
    * remove <p> tags from archive descriptions & other stuff
    *
    */
    
    remove_filter('term_description', 'wpautop');
    remove_filter('the_content', 'wpautop');
    remove_filter('the_excerpt', 'wpautop');
    remove_filter('widget_text_content', 'wpautop');
    remove_filter('widget_custom_html', 'wpautop' , 10, 3 );
    
    /**
    *
    * svgs
    *
    */
    
    add_filter('wp_check_filetype_and_ext', array($this, 'check_filetype'), 10, 4);
    add_filter('upload_mimes', array($this, 'cc_mime_types'));
    add_action('admin_head', array($this, 'fix_svg'));
    
    /**
    *
    * Yoast breadcrumbs
    *
    */
    
    if(yoast_breadcrumb_enabled()) add_filter('wpseo_breadcrumb_separator', array($this, 'filter_wpseo_breadcrumb_separator'), 10, 1);
        
    /**
    *
    * Theme's CSS classes
    *
    */
    
    add_filter('nav_menu_css_class', array($this, 'special_nav_class'), 10, 2);
    if($configs['theme_preloader']) add_filter('body_class', array($this, 'add_body_classes'));
    
    /**
    *
    * Singular-only
    *
    */
    
    add_action('parse_query', array($this, 'redirect_all_archives_to_home'));
    $this->set_page_to_front(get_page_by_path('homepage'));
    
    /**
    *
    * Disable comments
    *
    */
    
    add_filter('comments_array', array($this, 'disable_comments_hide_existing_comments'), 10, 2);
    add_action('admin_menu', array($this, 'disable_comments_admin_menu'));
    add_action('admin_init', array($this, 'disable_comments_admin_menu_redirect'));
    add_action('admin_init', array($this, 'disable_comments_dashboard'));
    add_action('init', array($this, 'disable_comments_admin_bar'));
    
  }
  
  /**
  *
  * Disable Wordpress comments from backend & posts etc
  *
  */
  
  function disable_comments_hide_existing_comments($comments) {
    $comments = array();
    return $comments;
  }
  function disable_comments_admin_menu() {
    remove_menu_page('edit-comments.php');
  }
  function disable_comments_admin_menu_redirect() {
    global $pagenow;
    if ($pagenow === 'edit-comments.php') {
      wp_redirect(admin_url()); exit;
    }
  }
  function disable_comments_dashboard() {
    remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal');
  }
  function disable_comments_admin_bar() {
    if (is_admin_bar_showing()) {
      remove_action('admin_bar_menu', 'wp_admin_bar_comments_menu', 60);
    }
  }
  
  /**
  *
  * Singular-only
  *
  */
  
  public function set_page_to_front($page){ // set a page as the frontpage (requires a page object) 
    if($page){
      update_option('page_on_front', $page->ID);
      update_option('show_on_front', 'page');
    }
  }
  public function redirect_all_archives_to_home($query){ // Redirect all archives to the homepage (disable archives) 
    if(is_archive()) {
      wp_redirect( home_url() );
      exit;
    }
  }
  
  /**
  *
  * Theme's CSS classes
  *
  */
  
  public function add_body_classes($classes){ // Add some classes to the body classes array 
    if($this->configs['theme_preloader']) array_push($classes, 'no-overflow');
    return $classes;
  }
  public function special_nav_class($classes, $item) { // Add uk-active class to wordpress' active menu items  
    if (in_array('current-menu-item', $classes) ){
      $classes[] = 'uk-active ';
    }
    return $classes;
  }
  
  /**
  *
  * Yoast breadcrumbs
  *
  */
  
  public function filter_wpseo_breadcrumb_separator($this_options_breadcrumbs_sep) {
  	return '<i class="fas fa-circle fa-xs"></i>';
  }
  
  /**
  *
  * svg support
  *
  */
  
  public function check_filetype($data, $file, $filename, $mimes) {
  
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
  
  }
  public function cc_mime_types( $mimes ){
    $mimes['svg'] = 'image/svg+xml';
    return $mimes;
  }
  public function fix_svg() {
    echo '<style type="text/css"> .attachment-266x266, .thumbnail img { width: 100%!important; height: auto!important; } </style>';
  }
  
  /**
  *
  * theme & twig setups
  *
  */
  
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