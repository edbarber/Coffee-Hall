<?php
add_action( 'after_setup_theme', 'dark_shop_lite_setup' );
function dark_shop_lite_setup() {
	add_theme_support( 'post-thumbnails' );add_theme_support( 'title-tag' );
	set_post_thumbnail_size(166, 124, TRUE); 
	global $content_width;
	if ( ! isset( $content_width ) )
	$content_width = 960;
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'custom-background' );						// background
	$defaults = array(												// background
		'default-color'          => '',
		'default-image'          => '',
		'wp-head-callback'       => '_custom_background_cb',
		'admin-head-callback'    => '',
		'admin-preview-callback' => ''	);
	add_action( 'wp_enqueue_scripts', 'dark_shop_lite_frontend' );
	add_editor_style( 'editor-style.css' );
	add_theme_support( 'woocommerce' );
	add_image_size( 'dark-shop-lite-logo-size', 330, 100, true );
    add_theme_support( 'site-logo', array( 'size' => 'dark-shop-lite-logo-size' ) );
    load_theme_textdomain( 'dark-shop-lite', get_template_directory() . '/languages' );}
add_action('wp_enqueue_scripts' , 'dark_shop_lite_enqueue_resources');function dark_shop_lite_enqueue_resources() { if ( is_singular() ) wp_enqueue_script( "comment-reply" );}
function register_my_menu() {
  		register_nav_menu('header-menu', __( 'Header Menu', 'dark-shop-lite' ));
	}
add_action( 'init', 'register_my_menu' );
function dark_shop_lite_widgets() {
		register_sidebar(		array(
			'id' => 'sidebar-left',
			 	'name' => __( 'sidebar-left', 'dark-shop-lite' ),			)		);
		register_sidebar(		array(			'id' => 'sidebar-header',
			 	'name' => __( 'sidebar-header', 'dark-shop-lite' ),
			)		);
		register_sidebar(		array(
			'id' => 'sidebar-footer1',							'name' => __( 'sidebar-footer1', 'dark-shop-lite' ),			)		);
}
add_action( 'widgets_init', 'dark_shop_lite_widgets' );
add_filter('loop_shop_per_page', create_function('$cols', 'return 12;'));
add_filter('loop_shop_columns', 'dark_shop_lite_loop_columns');
if (!function_exists('dark_shop_lite_loop_columns')) {
	function dark_shop_lite_loop_columns() {
		return 3;
	}
}
function woocommerce_output_related_products() {
    $args = array('posts_per_page' => 3, 'columns' => 3,'orderby' => 'rand' );
    woocommerce_related_products( apply_filters( 'woocommerce_output_related_products_args', $args ) );}
function dark_shop_lite_frontend() {
 	wp_enqueue_style( 'dark-shop-lite-style', get_stylesheet_uri() );
 }
function dark_shop_lite_wp_title( $title, $sep ) {
	global $paged, $page;
	if ( is_feed() )
		return $title;
	$title .= get_bloginfo( 'name' );
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		$title = "$title $sep $site_description";
	if ( $paged >= 3 || $page >= 3 )
		$title = "$title $sep " . sprintf( __( 'Page %s', 'dark-shop-lite' ), max( $paged, $page ) );
	return $title;
}
add_filter( 'wp_title', 'dark_shop_lite_wp_title', 10, 3 );
add_filter( 'wp_tag_cloud', 'dark_shop_lite_tag_cloud' );
function dark_shop_lite_tag_cloud( $tags ){
    return preg_replace(
        "~ style='font-size: (\d+)pt;'~",
        ' class="tag-cloud-size-\10"',
        $tags
    );
}
?>