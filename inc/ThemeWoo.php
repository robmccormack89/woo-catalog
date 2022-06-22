<?php
namespace Rmcc;
use Timber\Timber;

// Define paths to Twig templates
array_push(
  Theme::$dirname, 
  'views/woo', 
  // 'views/woo/product', 
  // 'views/woo/shop'
);

// Define Theme Child Class
class ThemeWoo extends Theme {
  
  public function __construct() {
    parent::__construct();
    add_action('after_setup_theme', array($this, 'woo_supports'));
    add_filter('timber/twig', array($this, 'add_to_twig'));
    add_filter('timber/context', array($this, 'add_to_context'));
    // add_action('init', array($this, 'register_woo_taxonomies'));
    // add_action('wp_enqueue_scripts', array($this, 'woo_script_styles'), 99);
    // add_filter('query_vars', array($this, 'gridlist_query_vars_filter'));
    // add_action('theme_before_header', array($this, 'add_custom_demo_store_notice'));
    // add_action( 'theme_above_footer', array($this, 'add_fixednav') );
    // add_action( 'theme_after_menu', array($this, 'add_iconnav') );
    // add_action( 'theme_after_header', array($this, 'add_minicart_popup') );
    
    // add woo to the sites body class; this could be made conditional if necessary
    add_filter('body_class', function($classes){
    	$stack = $classes;
    	array_push($stack, 'woocommerce');
    	return $stack;
    });
    
    /**
    *
    * Site-wide
    *
    */
    
    remove_action('wp_footer', 'woocommerce_demo_store', 10); // remove demo store notice
    
    /**
    *
    * Woo-genral
    *
    */
    
    remove_action('woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10); // remove unneccesary wrapping
    remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20); // remove woo breads
    remove_action('woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10); // remove unneccesary wrapping
    
    /**
    *
    * Shop & teases
    *
    */
    
    remove_action('woocommerce_before_shop_loop', 'woocommerce_result_count', 20); // remove result count (shop)
    
    remove_action('woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10); // remove unneccesary link wrapping (tease)
    remove_action('woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10); // remove default featured image (tease)
    remove_action('woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10); // remove title (tease)
    remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5); // remove star rating (tease)
    remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5); // remove unneccesary link wrapping (tease)
    
    remove_action('woocommerce_after_shop_loop', 'woocommerce_pagination', 10); // remove pagination (shop)
    
    /**
    *
    * Single product
    *
    */
    
    remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 ); // remove upsells
    remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 ); // remove relateds
    
  }
  
  public function add_minicart_popup($context) {
    // $context = Timber::context();
    Timber::render( 'minicart-popup.twig', $context );
  }
  public function add_fixednav($context) {
    // $context = Timber::context();
    Timber::render( 'fixednav.twig', $context );
  }
  public function add_iconnav($context) {
    // $context = Timber::context();
    Timber::render( 'iconnav.twig', $context );
  }
  public function gridlist_query_vars_filter($vars) {
    $vars[] .= 'grid_list';
    return $vars;
  }
  public function add_custom_demo_store_notice() {
      
    if (!is_store_notice_showing()) return;
    
    $notice = get_option('woocommerce_demo_store_notice'); 
    
    if (empty($notice)) $notice = _x( 'This is a demo store for testing purposes — no orders shall be fulfilled.', 'Store notice: default', 'base-theme' ); 

    echo apply_filters(
      'woocommerce_demo_store', 
      '<div class="woocommerce-store-notice demo_store uk-position-z-index theme-border-top"><div class="store-notice-wrap">'.wp_kses_post($notice).' <a href="#" class="woocommerce-store-notice__dismiss-link">' . esc_html_x( 'Dismiss', 'Store notice: dismiss button', 'base-theme' ) . ' <i class="fas fa-times"></i></a></div></div>', 
      $notice
   ); 
  }
  public function woo_script_styles() {
    
    remove_action('wp_head', array( $GLOBALS['woocommerce'], 'generator' ) );
    wp_dequeue_style( 'woocommerce_frontend_styles' );
    wp_dequeue_style( 'woocommerce-general');
    wp_dequeue_style( 'woocommerce-layout' );
    wp_dequeue_style( 'woocommerce-smallscreen' );
    wp_dequeue_style( 'woocommerce_fancybox_styles' );
    wp_dequeue_style( 'woocommerce_chosen_styles' );
    wp_dequeue_style( 'woocommerce_prettyPhoto_css' );
    wp_dequeue_script( 'selectWoo' );
    wp_deregister_script( 'selectWoo' );
    wp_dequeue_script( 'select2' );
    wp_deregister_script( 'select2' );
    
    // jquery
    wp_enqueue_script('jquery');
    
    // global (requires jQuery)
    wp_enqueue_script(
      'globalwoo',
      get_template_directory_uri() . '/assets/js/globalwoo.js',
      'jquery',
      '1.0.0',
      true
    );
   
    // localize global for ajax
    wp_localize_script(
      'globalwoo',
      'myAjax',
      array(
        'ajaxurl' => admin_url('admin-ajax.php')
      )
    );
    
    wp_enqueue_style(
      'globalwoostyles',
      get_template_directory_uri() . '/assets/css/woo.css'
    );
    
  }
  public function woo_supports() {
    // woo supports
    add_theme_support('woocommerce');
    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-lightbox');
    add_theme_support('wc-product-gallery-slider');
  }
  public function add_to_twig($twig) {
    return $twig;
  }
  public function add_to_context($context) {
    
    $context['WC'] = WC();
    
    // woo my account endpoints
    $context['dashboard_endpoint'] = wc_get_account_endpoint_url('dashboard');
    $context['address_endpoint'] = wc_get_account_endpoint_url('edit-address');
    $context['edit_endpoint'] = wc_get_account_endpoint_url('edit-account');
    $context['payment_endpoint'] = wc_get_account_endpoint_url('payment-methods');
    $context['lost_endpoint'] = wc_get_account_endpoint_url('lost-password');
    $context['orders_endpoint'] = wc_get_account_endpoint_url('orders');
    $context['logout_endpoint'] = wc_get_account_endpoint_url('customer-logout');
    
    // woo endpoints
    $context['shop_url'] = get_permalink(wc_get_page_id('shop'));
    $context['checkout_url'] = wc_get_checkout_url();
    $context['cart_url'] = wc_get_cart_url();
    
    // woo address fields
    $context['base_address'] = WC()->countries->get_base_address();
    $context['base_address_2'] = WC()->countries->get_base_address_2();
    $context['base_city'] = WC()->countries->get_base_city();
    $context['base_eircode'] = WC()->countries->get_base_postcode();
    $context['base_county'] = WC()->countries->get_base_state();
    $context['base_country'] = WC()->countries->get_base_country();
    
    $context['_get'] = $_GET;
    
    return $context;    
  }
  public function register_woo_taxonomies() {
    
    // series models (for products)
    $labels_series = array(
      'name'                       => _x( 'Series/Models', 'Series/Models: plural name', 'base-theme' ),
  		'singular_name'              => _x( 'Series/Model', 'Series/Models: singular name', 'base-theme' ),
  		'menu_name'                  => _x( 'Series/Models', 'Series/Models: plural name', 'base-theme' ),
  		// 'all_items'                  => __('All Series/Models', 'ned-murphy-tractors-theme'),
  		// 'parent_item'                => __('Parent (Series)', 'ned-murphy-tractors-theme'),
  		// 'parent_item_colon'          => __('Parent (Series):', 'ned-murphy-tractors-theme'),
  		// 'new_item_name'              => __('New Series/Model Name', 'ned-murphy-tractors-theme'),
  		// 'add_new_item'               => __('Add New Series/Model', 'ned-murphy-tractors-theme'),
  		// 'edit_item'                  => __('Edit Series/Model', 'ned-murphy-tractors-theme'),
  		// 'update_item'                => __('Update Series/Model', 'ned-murphy-tractors-theme'),
  		// 'view_item'                  => __('View Series/Model', 'ned-murphy-tractors-theme'),
  		// 'separate_items_with_commas' => __('Separate items with commas', 'ned-murphy-tractors-theme'),
  		// 'add_or_remove_items'        => __('Add or remove Series/Model', 'ned-murphy-tractors-theme'),
  		// 'choose_from_most_used'      => __('Choose from the most used', 'ned-murphy-tractors-theme'),
  		// 'popular_items'              => __('Popular Series/Models', 'ned-murphy-tractors-theme'),
  		// 'search_items'               => __('Search Series/Models', 'ned-murphy-tractors-theme'),
  	);
  	$rewrite_series = array(
  		'slug'                       => _x( 'product-series-model', 'Series/Models: archive slug', 'base-theme' ),
  		'with_front'                 => true,
  		'hierarchical'               => true,
  	);
  	$args_series = array(
  		'labels'                     => $labels_series,
  		'hierarchical'               => true,
  		'public'                     => true,
  		'show_ui'                    => true,
  		'show_admin_column'          => true,
  		'show_in_nav_menus'          => true,
  		'show_tagcloud'              => true,
  		'rewrite'                    => $rewrite_series,
  		'update_count_callback'      => 'count_product_series',
  		'show_in_rest'               => true,
      'update_count_callback' => '_update_post_term_count',
  	);
  	register_taxonomy('product_series', array('product'), $args_series);
    
  }

}