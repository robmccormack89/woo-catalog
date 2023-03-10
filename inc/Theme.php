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
    * svg support
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
    * Disable comments
    *
    */
    if($configs['theme_post_comments']) {
      add_filter('comments_array', array($this, 'disable_comments_hide_existing_comments'), 10, 2);
      add_action('admin_menu', array($this, 'disable_comments_admin_menu'));
      add_action('admin_init', array($this, 'disable_comments_admin_menu_redirect'));
      add_action('admin_init', array($this, 'disable_comments_dashboard'));
      add_action('init', array($this, 'disable_comments_admin_bar'));
    }

    // Remove tags support from posts
    // add_action('init', array($this, 'prefix_unregister_tags'));

    // allowed html for wp kses post
    add_action('init', array($this, 'allowed_html_tags_for_wp_kses'), 10);

    // this funtion adds the stickies back to the top of the loop & fixes the pagination after (i think)
    // add_action('pre_get_posts', array($this, 'remove_stickies_from_main_loop_and_put_back'));

    // this function removes stickies from the main loop, altogether
    add_action('pre_get_posts', array($this, 'remove_stickies_from_main_loop_complete'));

  }

  public function remove_stickies_from_main_loop_complete($q){
    if(!$q->is_admin() && !$q->is_search() && !$q->is_woocommerce()){
      if (($q->is_main_query() && $q->is_home()) || ($q->is_archive() && $q->is_category())) {

        // Unset the stickies from being sticky in the order. this doesnt remove them
        // $q->set('ignore_sticky_posts', 1);

        // Get the sticky posts array
        $stickies = get_option('sticky_posts');

        // Make sure we have stickies before continuing, else, return
        if (!$stickies) return;

        // Great, we have stickies, lets continue. Lets remove the stickies from the main query
        $q->set('post__not_in', $stickies);

      }
    }
  }

  /**
  *
  * Removes sticky posts from main loop
  * this function fixes issue of duplicate posts on archive
  * this funtion adds the stickies back to the top of the loop & fixes the pagination after (i think)
  * see https://wordpress.stackexchange.com/questions/225015/sticky-post-from-page-2-and-on
  *
  */
  public function remove_stickies_from_main_loop_and_put_back($q) {

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

  }

  public function allowed_html_tags_for_wp_kses() {

    global $allowedposttags;

    $allowed_atts = array(
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

  }

  // Remove tags support from posts
  public function prefix_unregister_tags() {
    unregister_taxonomy_for_object_type('post_tag', 'post');
  }

  /**
  *
  * Disable Wordpress comments from backend & posts etc
  *
  */

  public function disable_comments_hide_existing_comments($comments) {
    $comments = array();
    return $comments;
  }
  public function disable_comments_admin_menu() {
    remove_menu_page('edit-comments.php');
  }
  public function disable_comments_admin_menu_redirect() {
    global $pagenow;
    if ($pagenow === 'edit-comments.php') {
      wp_redirect(admin_url()); exit;
    }
  }
  public function disable_comments_dashboard() {
    remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal');
  }
  public function disable_comments_admin_bar() {
    if (is_admin_bar_showing()) {
      remove_action('admin_bar_menu', 'wp_admin_bar_comments_menu', 60);
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
  * Yoast breadcrumbs - customize the sep icon
  *
  */

  public function filter_wpseo_breadcrumb_separator($this_options_breadcrumbs_sep) {
  	return '<i uk-icon="icon: chevron-double-right; ratio: .8"></i>';
  }

  /**
  *
  * add svg support
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
    add_post_type_support( 'page', 'excerpt' );
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
    add_image_size( 'single-product-main', 500, 500, true ); // 220 pixels wide by 180 pixels tall, hard crop mode
    load_theme_textdomain('base-theme', get_template_directory() . '/languages');
  }
  public function add_to_twig($twig) {
    $twig->addExtension(new \Twig_Extension_StringLoader());
    $twig->addExtension(new StringExtension());
		return $twig;
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
    $context['menu_footer_one'] = new \Timber\Menu( 'footer_menu_one', $foot_menu_args );
    $context['menu_footer_two'] = new \Timber\Menu( 'footer_menu_two', $foot_menu_args );
    $context['menu_footer_three'] = new \Timber\Menu( 'footer_menu_three', $foot_menu_args );
    $context['menu_footer_four'] = new \Timber\Menu( 'footer_menu_four', $foot_menu_args );
    $context['menu_footer_five'] = new \Timber\Menu( 'footer_menu_five', $foot_menu_args );
    $context['menu_iconnav'] = new \Timber\Menu( 'iconnav_menu', $foot_menu_args );
    $context['menu_contact'] = new \Timber\Menu( 'contact_menu', $foot_menu_args );
    $context['topheader_menu'] = new \Timber\Menu( 'topheader_menu', $foot_menu_args );

    $context['has_menu_main'] = has_nav_menu( 'main_menu' );
    $context['has_menu_secondary'] = has_nav_menu( 'secondary_menu' );
    $context['has_menu_mobile'] = has_nav_menu( 'mobile_menu' );
    $context['has_menu_iconnav'] = has_nav_menu( 'iconnav_menu' );
    $context['has_menu_contact'] = has_nav_menu( 'contact_menu' );
    $context['has_menu_footer_one'] = has_nav_menu( 'footer_menu_one' );
    $context['has_menu_footer_two'] = has_nav_menu( 'footer_menu_two' );
    $context['has_menu_footer_three'] = has_nav_menu( 'footer_menu_three' );
    $context['has_menu_footer_four'] = has_nav_menu( 'footer_menu_four' );
    $context['has_menu_footer_five'] = has_nav_menu( 'footer_menu_five' );
    $context['has_menu_topheader'] = has_nav_menu( 'topheader_menu' );

    $context['sidebar_footer']   = Timber::get_widgets(esc_html__('Footer sidebar', 'base-theme'));

    $context['pageheader_bg_img'] = _x( '/wp-content/themes/base-theme/assets/img/hero_inner_page.jpg', 'Header background image', 'base-theme' );
    $context['pagefooter_bg_img'] = _x( '/wp-content/themes/base-theme/assets/img/background-footer.jpg', 'Footer background image', 'base-theme' );

    // some nice image ids: 1015, 1036, 1038, 1041, 1042, 1044, 1045, 1051, 1056, 1057, 1067, 1069, 1068, 1078, 1080, 1083, 10
    $context['default_theme_img_id'] = _x( '1036', 'Lorem picsum base image id', 'base-theme' );
    $context['default_theme_img'] = 'https://picsum.photos/id/' . _x( '1036', 'Lorem picsum base image id', 'base-theme' ) . '/1920/800';

    $context['theme_defaults'] = $configs['theme_defaults'];

    // return context
    return $context;

  }
  public function register_post_types() {

  }
  public function register_taxonomies() {

  }
  public function register_widget_areas() {

    // Register widget areas
    if (function_exists('register_sidebar')) {

      register_sidebar(array(
        'name' => esc_html__('Footer sidebar', 'base-theme'),
        'id' => 'sidebar-footer',
        'description' => esc_html__('Sidebar Area for the Footer, you can add multiple widgets here.', 'base-theme'),
        'before_widget' => '',
        'after_widget' => '',
        'before_title' => '<h3 class="uk-text-bold widget-title" hidden><span>',
        'after_title' => '</span></h3>'
      ));
    }

  }
  public function register_navigation_menus() {
    register_nav_menus(array(
      'main_menu' => _x( 'Main Menu', 'Menu locations', 'base-theme' ),
      'secondary_menu' => _x( 'Secondary Menu', 'Menu locations', 'base-theme' ),
      'mobile_menu' => _x( 'Mobile Menu', 'Menu locations', 'base-theme' ),
      'topheader_menu' => _x( 'Top Header Menu', 'Menu locations', 'base-theme' ),
      'iconnav_menu' => _x( 'Iconnav Menu', 'Menu locations', 'base-theme' ),
      'contact_menu' => _x( 'Contact Menu', 'Menu locations', 'base-theme' ),
      'footer_menu_one' => _x( 'Footer Nav Area: One', 'Menu locations', 'base-theme' ),
      'footer_menu_two' => _x( 'Footer Nav Area: Two', 'Menu locations', 'base-theme' ),
      'footer_menu_three' => _x( 'Footer Nav Area: Three', 'Menu locations', 'base-theme' ),
      'footer_menu_four' => _x( 'Footer Nav Area: Four', 'Menu locations', 'base-theme' ),
      'footer_menu_five' => _x( 'Footer Nav Area: Five', 'Menu locations', 'base-theme' ),
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
