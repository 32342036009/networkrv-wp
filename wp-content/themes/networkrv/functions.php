<?php
/**
 * networkrv functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package networkrv
 */

if ( ! function_exists( 'networkrv_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function networkrv_setup() {
	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on networkrv, use a find and replace
	 * to change 'networkrv' to the name of your theme in all the template files.
	 */
	load_theme_textdomain( 'networkrv', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
	 */
	add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'menu-1' => esc_html__( 'Primary', 'networkrv' ),
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
	) );

	// Set up the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'networkrv_custom_background_args', array(
		'default-color' => 'ffffff',
		'default-image' => '',
	) ) );

	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );
}
endif;
add_action( 'after_setup_theme', 'networkrv_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function networkrv_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'networkrv_content_width', 640 );
}
add_action( 'after_setup_theme', 'networkrv_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function networkrv_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar', 'networkrv' ),
		'id'            => 'sidebar-1',
		'description'   => esc_html__( 'Add widgets here.', 'networkrv' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );


	register_sidebar( array(
		'name'          => __( 'Social Media', 'networkrv' ),
		'id'            => 'social',
		'description'   => __( 'Add widgets here.', 'networkrv' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
	
	register_sidebar( array(
		'name'          => __( 'Advertisement', 'networkrv' ),
		'id'            =>  'add',
		'description'   => __( 'Add widgets here.', 'networkrv' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
}
 

add_action( 'widgets_init', 'networkrv_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function networkrv_scripts() {
	wp_enqueue_style( 'networkrv-style', get_stylesheet_uri() );

	wp_enqueue_script( 'networkrv-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '20151215', true );

	wp_enqueue_script( 'networkrv-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20151215', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'networkrv_scripts' );

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Additional features to allow styling of the templates.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';

function wpdocs_custom_excerpt_length( $length ) {
    return 150;
}
add_filter( 'excerpt_length', 'wpdocs_custom_excerpt_length', 999 );

function wpdocs_excerpt_more( $more ) {
    return '....';
}

add_filter( 'excerpt_more', 'wpdocs_excerpt_more' );
 function __get_xml_data($xml=null,$length){
         if(empty($xml)){ return false;  }
     $ch = curl_init();
    curl_setopt ($ch, CURLOPT_URL, $xml);
    curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 5);
    curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
    $contents = curl_exec($ch);
    if (curl_errno($ch)) {
      echo curl_error($ch);
      echo "\n<br />";
      $contents = '';
    } else {
      curl_close($ch);
    }
    
    if (!is_string($contents) || !strlen($contents)) {
    echo "Failed to get contents.";
    $contents = '';
    }
                              $XmlString=simplexml_load_string($contents);
                              $XmlArray=array();
                              $ArrChnl=(array)$XmlString->channel;
                              $ind=0;
                              foreach($ArrChnl['item'] as $key=>$DataArray){
                                   $XmlArray['title'][]=$DataArray->title ; 
                                   $XmlArray['link'][]=$DataArray->link ; 
                                   $XmlArray['pubDate'][]=$DataArray->pubDate ; 
                                   $XmlArray['guid'][]=$DataArray->guid ; 
                                   $XmlArray['description'][]=$DataArray->description ; 
                                     if($length!=false){
                                      if($length==$key){ break; }
                                   }
                                  
                               }
                              
                                  return $XmlArray;
}