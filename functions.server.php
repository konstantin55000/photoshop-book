<?php
/**
 * thim functions and definitions
 *
 * @package thim
 */

define( 'THIM_DIR', trailingslashit( get_template_directory() ) );
define( 'THIM_URI', trailingslashit( get_template_directory_uri() ) );
define( 'THIM_THEME_VERSION', '3.2.0' );

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) ) {
	$content_width = 640; /* pixels */
}
/**
 * Translation ready
 */

load_theme_textdomain( 'eduma', get_template_directory() . '/languages' );

if ( ! function_exists( 'thim_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function thim_setup() {

		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on thim, use a find and replace
		 * to change 'eduma' to the name of your theme in all the template files
		 */
		load_theme_textdomain( 'eduma', get_template_directory() . '/languages' );
		add_theme_support( 'title-tag' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
		 */
		add_theme_support( 'post-thumbnails' );

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus( array(
			'primary' => esc_html__( 'Primary Menu', 'eduma' ),
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
		/* Add WooCommerce support */
		add_theme_support( 'woocommerce' );
		add_theme_support( 'thim-core' );

		add_theme_support( 'eduma-demo-data' );

		/*
		 * Enable support for Post Formats.
		 * See http://codex.wordpress.org/Post_Formats
		 */
		add_theme_support( 'post-formats', array(
			'aside',
			'image',
			'video',
			'quote',
			'link',
			'gallery',
			'audio'
		) );
	}
endif; // thim_setup
add_action( 'after_setup_theme', 'thim_setup' );

/**
 * Register widget area.
 *
 * @link http://codex.wordpress.org/Function_Reference/register_sidebar
 */
if ( ! function_exists( 'thim_widgets_inits' ) ) {
	function thim_widgets_inits() {
		register_sidebar( array(
			'name'          => esc_html__( 'Sidebar', 'eduma' ),
			'id'            => 'sidebar',
			'description'   => esc_html__( 'Right Sidebar', 'eduma' ),
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget'  => '</aside>',
			'before_title'  => '<h4 class="widget-title">',
			'after_title'   => '</h4>',
		) );

		register_sidebar( array(
			'name'          => esc_html__( 'Toolbar', 'eduma' ),
			'id'            => 'toolbar',
			'description'   => esc_html__( 'Toolbar Header', 'eduma' ),
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget'  => '</aside>',
			'before_title'  => '<h4 class="widget-title">',
			'after_title'   => '</h4>',
		) );

		register_sidebar( array(
			'name'          => esc_html__( 'Menu Right', 'eduma' ),
			'id'            => 'menu_right',
			'description'   => esc_html__( 'Menu Right', 'eduma' ),
			'before_widget' => '<li id="%1$s" class="widget %2$s">',
			'after_widget'  => '</li>',
			'before_title'  => '<h4>',
			'after_title'   => '</h4>',
		) );

		register_sidebar( array(
			'name'          => esc_html__( 'Menu Top', 'eduma' ),
			'id'            => 'menu_top',
			'description'   => esc_html__( 'Menu top only display with header version 2', 'eduma' ),
			'before_widget' => '<li id="%1$s" class="widget %2$s">',
			'after_widget'  => '</li>',
			'before_title'  => '<h4>',
			'after_title'   => '</h4>',
		) );

		register_sidebar( array(
			'name'          => esc_html__( 'Footer', 'eduma' ),
			'id'            => 'footer',
			'description'   => esc_html__( 'Footer Sidebar', 'eduma' ),
			'before_widget' => '<aside id="%1$s" class="widget %2$s footer_widget">',
			'after_widget'  => '</aside>',
			'before_title'  => '<h4 class="widget-title">',
			'after_title'   => '</h4>',
		) );

		register_sidebar( array(
			'name'          => esc_html__( 'Footer Bottom', 'eduma' ),
			'id'            => 'footer_bottom',
			'description'   => esc_html__( 'Footer Bottom Sidebar', 'eduma' ),
			'before_widget' => '<aside id="%1$s" class="widget %2$s footer_bottom_widget">',
			'after_widget'  => '</aside>',
			'before_title'  => '<h4 class="widget-title">',
			'after_title'   => '</h4>',
		) );

		register_sidebar( array(
			'name'          => esc_html__( 'Copyright', 'eduma' ),
			'id'            => 'copyright',
			'description'   => esc_html__( 'Copyright', 'eduma' ),
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget'  => '</aside>',
			'before_title'  => '<h4 class="widget-title">',
			'after_title'   => '</h4>',
		) );

		if ( class_exists( 'WooCommerce' ) ) {
			register_sidebar( array(
				'name'          => esc_html__( 'Sidebar Shop', 'eduma' ),
				'id'            => 'sidebar_shop',
				'description'   => esc_html__( 'Sidebar Shop', 'eduma' ),
				'before_widget' => '<aside id="%1$s" class="widget %2$s">',
				'after_widget'  => '</aside>',
				'before_title'  => '<h4 class="widget-title">',
				'after_title'   => '</h4>',
			) );
		}

		if ( class_exists( 'LearnPress' ) ) {
			register_sidebar( array(
				'name'          => esc_html__( 'Sidebar Courses', 'eduma' ),
				'id'            => 'sidebar_courses',
				'description'   => esc_html__( 'Sidebar Courses', 'eduma' ),
				'before_widget' => '<aside id="%1$s" class="widget %2$s">',
				'after_widget'  => '</aside>',
				'before_title'  => '<h4 class="widget-title">',
				'after_title'   => '</h4>',
			) );
		}

		if ( class_exists( 'TP_Event' ) || class_exists( 'WPEMS' ) ) {
			register_sidebar( array(
				'name'          => esc_html__( 'Sidebar Events', 'eduma' ),
				'id'            => 'sidebar_events',
				'description'   => esc_html__( 'Sidebar Events', 'eduma' ),
				'before_widget' => '<aside id="%1$s" class="widget %2$s">',
				'after_widget'  => '</aside>',
				'before_title'  => '<h4 class="widget-title">',
				'after_title'   => '</h4>',
			) );
		}

		register_sidebar( array(
			'name'          => esc_html__( 'Header', 'eduma' ),
			'id'            => 'header',
			'description'   => esc_html__( 'Sidebar display on header version 3', 'eduma' ),
			'before_widget' => '<aside id="%1$s" class="widget %2$s footer_bottom_widget">',
			'after_widget'  => '</aside>',
			'before_title'  => '<h4 class="widget-title">',
			'after_title'   => '</h4>',
		) );

		/**
		 * Feature create sidebar in wp-admin.
		 * Do not remove this.
		 */
		$sidebars = apply_filters( 'thim_core_list_sidebar', array() );
		if ( count( $sidebars ) > 0 ) {
			foreach ( $sidebars as $sidebar ) {
				$new_sidebar = array(
					'name'          => $sidebar['name'],
					'id'            => $sidebar['id'],
					'description'   => esc_html__( 'Custom widgets area.', 'eduma' ),
					'before_widget' => '<aside id="%1$s" class="widget %2$s footer_bottom_widget">',
					'after_widget'  => '</aside>',
					'before_title'  => '<h4 class="widget-title">',
					'after_title'   => '</h4>',
				);

				register_sidebar( $new_sidebar );
			}
		}
	}
}

add_action( 'widgets_init', 'thim_widgets_inits' );

/**
 * Enqueue styles.
 */
if ( ! function_exists( 'thim_styles' ) ) {
	function thim_styles() {
		if ( is_multisite() ) {
			wp_enqueue_style( 'thim-style', THIM_URI . 'style.css', array(), THIM_THEME_VERSION );
		} else {
			wp_enqueue_style( 'thim-style', get_stylesheet_uri(), array(), THIM_THEME_VERSION );
		}

		if( get_theme_mod( 'thim_header_style', 'header_v1' ) == 'header_v4' ) {
			wp_enqueue_style( 'thim-linearicons-font', THIM_URI . 'assets/css/linearicons.css', array(), THIM_THEME_VERSION );
		}

		//Load style for page builder Visual Composer
		$page_builder = get_theme_mod( 'thim_page_builder_chosen', '' );
		if ( $page_builder === 'visual_composer' ) {
			wp_enqueue_style( 'thim-custom-vc', THIM_URI . 'assets/css/custom-vc.css', array(), THIM_THEME_VERSION );
		}

		if ( get_theme_mod( 'thim_rtl_support', false ) ) {
			wp_enqueue_style( 'thim-rtl', THIM_URI . 'rtl.css', array(), THIM_THEME_VERSION );
		}


	}
}
add_action( 'wp_enqueue_scripts', 'thim_styles', 1001 );

/**
 * Enqueue scripts.
 */
if ( ! function_exists( 'thim_scripts' ) ) {
	function thim_scripts() {

		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}

		// New script update from resca,sailing
		wp_enqueue_script( 'thim-main', THIM_URI . 'assets/js/main.min.js', array( 'jquery' ), THIM_THEME_VERSION, true );

		if ( get_theme_mod( 'thim_smooth_scroll', true ) ) {
			wp_enqueue_script( 'thim-smooth-scroll', THIM_URI . 'assets/js/smooth_scroll.min.js', array( 'jquery' ), THIM_THEME_VERSION, true );
		}

		if ( thim_is_new_learnpress( '2.0' ) ) {
			wp_enqueue_script( 'thim-custom-script', THIM_URI . 'assets/js/custom-script-v2.js', array( 'jquery' ), THIM_THEME_VERSION, true );
		} else if ( thim_is_new_learnpress( '1.0' ) ) {
			wp_enqueue_script( 'thim-custom-script', THIM_URI . 'assets/js/custom-script-v1.js', array( 'jquery' ), THIM_THEME_VERSION, true );
		} else {
			wp_enqueue_script( 'thim-custom-script', THIM_URI . 'assets/js/custom-script.js', array( 'jquery' ), THIM_THEME_VERSION, true );
		}

		// Localize the script with new data
		wp_localize_script( 'thim-custom-script', 'thim_js_translate', array(
			'login'    => esc_attr__( 'Username', 'eduma' ),
			'password' => esc_attr__( 'Password', 'eduma' ),
			'close'    => esc_html__( 'Close', 'eduma' ),
		) );

		if ( get_post_type() == 'portfolio' && ( is_category() || is_archive() || is_singular( 'portfolio' ) ) ) {
			wp_enqueue_script( 'thim-portfolio-appear', THIM_URI . 'assets/js/jquery.appear.js', array( 'jquery' ), THIM_THEME_VERSION, true );
			wp_enqueue_script( 'thim-portfolio-widget', THIM_URI . 'assets/js/portfolio.js', array(
				'jquery',
				'thim-main'
			), THIM_THEME_VERSION, true );
		}

		wp_dequeue_script( 'framework-bootstrap' );

		wp_dequeue_script( 'thim-flexslider' );

		// Remove some scripts LearnPress
		wp_dequeue_style( 'lpr-print-rate-css' );
		wp_dequeue_style( 'tipsy' );
		wp_dequeue_style( 'certificate' );
		wp_dequeue_style( 'fib' );
		wp_dequeue_style( 'sorting-choice' );
		wp_dequeue_style( 'course-wishlist-style' );
		wp_dequeue_script( 'tipsy' );
		wp_dequeue_script( 'lpr-print-rate-js' );
		wp_dequeue_script( 'course-wishlist-script' );
		wp_dequeue_script( 'course-review' );
		wp_dequeue_style( 'course-review' );
		wp_dequeue_style( 'learn-press-pmpro-style' );
		wp_dequeue_style( 'learn-press-jalerts' );

		if ( ! is_single( 'lpr_course' ) && ! is_single( 'lpr_quiz' ) ) {
			wp_dequeue_script( 'sorting-choice' );
			wp_deregister_script( 'block-ui' );
		}

		if ( is_front_page() ) {

			wp_dequeue_script( 'webfont' );
			wp_dequeue_script( 'fabric-js' );
			wp_dequeue_script( 'certificate' );

			wp_dequeue_script( 'thim-event-countdown-plugin-js' );
			wp_dequeue_script( 'thim-event-countdown-js' );
			wp_dequeue_script( 'tp-event-auth' );

			if ( ! is_user_logged_in() ) {
				wp_dequeue_style( 'dashicons' );
			}
		}

		//Plugin tp-event
		wp_dequeue_style( 'thim-event' );
		wp_dequeue_style( 'tp-event-auth' );
		wp_dequeue_script( 'thim-event-owl-carousel-js' );
		wp_dequeue_script( 'tp-event-site-js-events.js' );
		wp_dequeue_style( 'thim-event-countdown-css' );
		wp_dequeue_style( 'thim-event-owl-carousel-css' );
		wp_dequeue_style( 'tp-event-fronted-css' );
		wp_dequeue_style( 'tp-event-owl-carousel-css' );
		wp_dequeue_style( 'tp-event-magnific-popup-css' );

		wp_dequeue_style( 'mo_openid_admin_settings_style' );
		wp_dequeue_style( 'mo_openid_admin_settings_phone_style' );
		//wp_dequeue_style( 'mo-wp-bootstrap-social' );
		wp_dequeue_style( 'mo-wp-bootstrap-main' );
		wp_dequeue_style( 'mo-wp-font-awesome' );

		//Woocommerce
		wp_dequeue_script( 'jquery-cookie' );

		//Miniorange-login
		wp_dequeue_script( 'js-cookie-script' );
		wp_dequeue_script( 'mo-social-login-script' );

		if ( ! thim_use_bbpress() ) {
			wp_dequeue_style( 'bbp-default' );
			wp_dequeue_script( 'bbpress-editor' );
		}


		//LearnPress 2.0
		wp_dequeue_style( 'owl_carousel_css' );
		wp_dequeue_style( 'learn-press-coming-soon-course' );
		wp_dequeue_script( 'learn-press-jquery-mb-coming-soon' );

	}
}
add_action( 'wp_enqueue_scripts', 'thim_scripts', 1000 );


if ( class_exists( 'WooCommerce' ) ) {
	add_action( 'wp_enqueue_scripts', 'thim_manage_woocommerce_styles', 9999 );
}

if ( ! function_exists( 'thim_manage_woocommerce_styles' ) ) {
	function thim_manage_woocommerce_styles() {
		//remove generator meta tag
		remove_action( 'wp_head', array( $GLOBALS['woocommerce'], 'generator' ) );

		//first check that woo exists to prevent fatal errors
		if ( function_exists( 'is_woocommerce' ) ) {
			//dequeue scripts and styles
			/*
			if ( !is_woocommerce() && !is_cart() && !is_checkout() ) {
				wp_dequeue_style( 'woocommerce_frontend_styles' );
				wp_dequeue_style( 'woocommerce_fancybox_styles' );
				wp_dequeue_style( 'woocommerce_chosen_styles' );
				wp_dequeue_style( 'woocommerce_prettyPhoto_css' );
				wp_dequeue_style( 'woocommerce-layout' );
				wp_dequeue_style( 'woocommerce-general' );
				wp_dequeue_script( 'wc_price_slider' );
				wp_dequeue_script( 'wc-single-product' );
				wp_dequeue_script( 'wc-add-to-cart' );
				wp_dequeue_script( 'wc-cart-fragments' );
				wp_dequeue_script( 'wc-checkout' );
				wp_dequeue_script( 'wc-add-to-cart-variation' );
				wp_dequeue_script( 'wc-single-product' );
				wp_dequeue_script( 'wc-cart' );
				wp_dequeue_script( 'wc-chosen' );
				wp_dequeue_script( 'woocommerce' );
			}
			*/
		}

		if ( is_post_type_archive( 'product' ) ) {
			wp_enqueue_script( 'wc-add-to-cart-variation' );
		}
	}
}


function thim_custom_admin_scripts() {
	wp_enqueue_script( 'thim-admin-custom-script', THIM_URI . 'assets/js/admin-custom-script.js', array( 'jquery' ), THIM_THEME_VERSION, true );
	wp_enqueue_style( 'thim-admin-theme-style', THIM_URI . 'assets/css/thim-admin.css', array(), THIM_THEME_VERSION );
	wp_enqueue_style( 'thim-admin-font-icon7', THIM_URI . 'assets/css/font-pe-icon-7.css', array(), THIM_THEME_VERSION );
	$thim_mod                 = get_theme_mods();
	$thim_page_builder_chosen = ! empty( $thim_mod['thim_page_builder_chosen'] ) ? $thim_mod['thim_page_builder_chosen'] : '';
	wp_localize_script( 'thim-admin-custom-script', 'thim_theme_mods', array(
		'thim_page_builder_chosen' => $thim_page_builder_chosen,
	) );
}

add_action( 'admin_enqueue_scripts', 'thim_custom_admin_scripts' );

// Require library
require THIM_DIR . 'inc/libs/theme-wrapper.php';
require THIM_DIR . 'inc/libs/aq_resizer.php';


// Custom functions.
require get_template_directory() . '/inc/custom-functions.php';

/**
 * Custom template tags for this theme.
 */
require THIM_DIR . 'inc/template-tags.php';


if ( class_exists( 'WooCommerce' ) ) {
	require THIM_DIR . 'woocommerce/woocommerce.php';
}

if ( class_exists( 'BuddyPress' ) ) {
	require THIM_DIR . 'buddypress/bp-custom.php';
}

//logo
require_once THIM_DIR . 'inc/header/logo.php';

//custom logo mobile
require_once THIM_DIR . 'inc/header/logo-mobile.php';


//Visual composer shortcodes
if ( class_exists( 'Vc_Manager' ) && thim_plugin_active( 'js_composer/js_composer.php' ) ) {
	require THIM_DIR . 'vc-shortcodes/vc-shortcodes.php';
}

// Remove references to SiteOrigin Premium
add_filter( 'siteorigin_premium_upgrade_teaser', '__return_false' );

//For use thim-core
require_once THIM_DIR . 'inc/thim-core-function.php';

require_once THIM_DIR . 'inc/upgrade.php';


/**
 * Testing
 */
function xxx( $x ) {
	echo '<pre>';
	if ( is_array( $x ) || is_object( $x ) ) {
		print_r( $x );
	} else {
		echo $x;
	}
	echo '</pre>';
}




 /*** Photoshop book styles ***/
function replace_jquery() {

	//if (!is_admin()) {

		wp_deregister_script('jquery');
		wp_register_script('jquery', 'http://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js', false, '1.11.3');
		wp_enqueue_script('jquery');
	//}
}
add_action('init', 'replace_jquery');


function thim_child_enqueue_styles() {
	if ( is_multisite() ) {
		wp_enqueue_style( 'thim-child-style', get_stylesheet_uri() );
	} else {
		wp_enqueue_style( 'thim-parent-style', get_template_directory_uri() . '/style.css' );
	}
   
}

add_action( 'wp_enqueue_scripts', 'thim_child_enqueue_styles', 1000 );

function include_phbstyles(){
    wp_enqueue_style( 'theme-child-style', get_stylesheet_directory_uri() . '/style.css' );
     wp_enqueue_style( 'photoshop-book-style', get_stylesheet_directory_uri() . '/photoshop-book-theme.css' );
}

add_action( 'include_phbook_styles', 'include_phbstyles', 1000 );

// функция дополнительного фильтра в разделе записи
function true_taxonomy_filter() {
	global $typenow; // тип поста
	if( $typenow == 'post' ){ // для каких типов постов отображать
		$taxes = array('platform', 'game'); // таксономии через запятую
		foreach ($taxes as $tax) {
			$current_tax = isset( $_GET[$tax] ) ? $_GET[$tax] : '';
			$tax_obj = get_taxonomy($tax);
			$tax_name = mb_strtolower($tax_obj->labels->name);
			// функция mb_strtolower переводит в нижний регистр
			// она может не работать на некоторых хостингах, если что, убирайте её отсюда
			$terms = get_terms($tax);
			if(count($terms) > 0) {
				echo "<select name='$tax' id='$tax' class='postform'>";
				echo "<option value=''>Все $tax_name</option>";
				foreach ($terms as $term) {
					echo '<option value='. $term->slug, $current_tax == $term->slug ? ' selected="selected"' : '','>' . $term->name .' (' . $term->count .')</option>';
				}
				echo "</select>";
			}
		}
	}
}


//add_action( 'restrict_manage_posts', 'true_taxonomy_filter' );

// плагин счетчика количества просмотров
add_action( 'after_setup_theme', function() {
    add_theme_support( 'pageviews' );
});

// подключаем ajax обработчик формы
function include_formscript() {
 	wp_enqueue_script( 'formscript', get_stylesheet_directory_uri() . '/js/main.js',  array( 'jquery' ) );
     //wp_localize_script( 'formscript', 'my_ajax_object',
           // array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
}

/***
    Счетчик просмотров постов без плагинов :)
**/    
//регcитрируем группу полей acf
   
//add_action('acf/init', 'my_acf_add_local_field_groups');


 

// инкремент счетчика для поста
function postViews($postID, $setviews = false) {
    $count_key = 'zviews_count';
    $count = get_post_meta($postID, $count_key); 
    $count = $count[0]; 
    
    if(!isset($count)){  
        $count = 0;  
        add_post_meta($postID, $count_key,  $count); 
    } else {
         if ($setviews){
             $count = intval($count) + 1; 
             update_post_meta($postID, $count_key,  $count);
         } 
    } 
//    var_dump('### post id', $postID);
//    var_dump('###postviews 1', $count );
    return $count; 
}

//wrapper инкрементируем счетчик для поста
//возвращает обновленный postviews
function setPostViews($postID){
    return postViews($postID, true);
}
//возвращает колво просмотров поста
function getPostViews($postID){
     return postViews($postID);
}
 

add_action( 'wp_enqueue_scripts', 'include_formscript' );

 function lesson_filter_function(){


    $confirmFiltering = false;
    //выполнить все (AND) условия фильтра

      $confirmFiltering = true;
      $meta_query_args = array( 
      );
      $args = array(
           
      );
    
    
   if ( isset($_POST['mainpage']) ) {
    if ( $_POST['mainpage'] == true ) {
        $meta_query_args[] = array(
        'key' => 'type_informacii_posta',
        'value' => array('Текстовый урок', 'Видео урок','Цикл уроков'),
        'compare' => 'IN'
    );
    $args['meta_query'] = $meta_query_args;
       
    }
   }
    //checkbox compare
    if (isset($_POST['lesson_begginer']) || isset($_POST['lesson_middle']) || isset($_POST['lesson_hard']))
    {
               //добавляем аргументы в мета квери
                $meta_query_args['relation'] = 'AND';  

                $tmpArr = array();

                if(isset($_POST['lesson_begginer'])){
                    $tmpArr[] =  'Для начинающих'  ;
                }

                if(isset($_POST['lesson_middle']) ){
                     $tmpArr[] = 'Средний' ;
                }

                if(isset($_POST['lesson_hard']) ){
                    $tmpArr[] =  'Сложный' ;
                }

                $meta_query_args[] =  array (                      
                            'key' => 'slojnost_acf',
                            'value' => $tmpArr,
                            'compare' => 'IN'
                );
        $args['meta_query'] = $meta_query_args;
      }


 	// прменяем фильтр, только если есть по чему фильтровать.
     
	if(isset($_POST['lessons']))
    {
         $confirmFiltering = true;
        // Фильтр по значению поля тип урока
        $meta_query_args['relation'] = 'AND';  

        // Все уроки по умолчанию
        if( $_POST['lessons'] == 'text_lessons' )
		$meta_query_args[] = array(
			'key' => 'type_informacii_posta',
			'value' => 'Текстовый урок'
		);

        if( $_POST['lessons'] == 'video_lessons')
		$meta_query_args[] = array(
			'key' => 'type_informacii_posta',
			'value' => 'Видео урок'
		);

	   //Цикл уроков
	   if($_POST['lessons'] == 'kurs_lessons' )
		$meta_query_args[] = array(
			'key' => 'type_informacii_posta',
			'value' => 'Цикл уроков'
		);
        
        $args['meta_query'] = $meta_query_args;

    }

        //var_dump($args['meta_query']);
        if (!confirmFiltering)
           exit();
 
          //Сортировка по просмотрам и колву комментариев
        if ( isset($_POST['ratio']) ){
                if ($_POST['ratio'] == 'ratio_new'){
                    $args['orderby'] = 'date';
                    $args['order'] = 'DESC';
                }
                if ($_POST['ratio'] == 'ratio_mostcomment'){
                        $args['orderby'] = 'comment_count';
                        $args['order'] = 'DESC';
                }
                if ($_POST['ratio'] == 'ratio_mostview'){ 
                        $args['meta_key'] = 'zviews_count';
                        $args['orderby'] =  'meta_value_num';
                        $args['order'] = 'DESC';
                     
              } 
        }
    
        //Создаем запрос  с нашими аргументами
 
         if (isset($_POST['current_category']) && $_POST['current_category'] != 0){
             $args['cat'] = $_POST['current_category']; 
         } 
        
        if (isset($_POST['curr_page']) ){
            $paged = $_POST['curr_page'];
        }
        else {
            $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
        }
         
         $args['posts_per_page'] =  2;
         $args['paged'] = $paged;
        $isJson = true;
    
        
        //unset($args['cat']); //@todo: check this
     
       //this must be dynamic $paged
     
         
        $wp_query =  new WP_Query( $args );
        $posts = $wp_query->posts;
       // var_dump('*********************', $args); exit;
        
        $GLOBALS['wp_query']->max_num_pages = ceil($wp_query->found_posts / $args['posts_per_page']);
         
        $navLinks = k_paging_nav(isJson, $paged);
         
        
        foreach ($posts as $index => $post){
            
            //добавляем нужные поля для вывода в js

            $id = $post->ID;
            $post->post_type_video = get_field('photoshop_video', $id);
            $post->post_type_text = get_field('photoshop_tekst', $id);
            $post->postviews =  getPostViews($id);
//            var_dump('** post id', $id);
//            var_dump('***postviews2', $post->postviews );
            $post->comments_count = get_comments_number($id);
            $post->cat = get_the_category($id);
            $first_cat = $post->cat[0];
            $category_slug = $first_cat->slug;
            $postname = $post->post_name;
            $dt  = (explode(" ", $post->post_date));
            $post->formatted_date = $dt[0];
            $post->posthref = "http://".$_SERVER['HTTP_HOST']."/$category_slug/$postname"; 
            //"http://".$_SERVER['HTTP_HOST']."/category/$category_slug/$postname";

            $post->post_thumbnail =  get_the_post_thumbnail_url( $id, 'full');
            $post->type_informacii_posta  = get_post_meta( $id, 'type_informacii_posta' );
            $post->slojnost_acf  = get_post_meta( $id, 'slojnost_acf' );
        }
    
        $posts[]['nav_links'] = $navLinks; 
     
       // var_dump('*********',  $navLinks); exit;
        wp_send_json($posts);


	exit();

}


add_action('wp_ajax_lessonfilter', 'lesson_filter_function');
add_action('wp_ajax_nopriv_lessonfilter', 'lesson_filter_function');



/* Переопределяем функцию thim_paging_nav для использования в фильтре */
function k_paging_nav($json = false, $paged = 1) {
         
		if ( $GLOBALS['wp_query']->max_num_pages < 2 ) {
			return;
		}
         
		//$paged        = get_query_var( 'paged' ) ? intval( get_query_var( 'paged' ) ) : 1;
    
		$pagenum_link = html_entity_decode( get_pagenum_link() );
        
		$query_args = array();
		$url_parts  = explode( '?', $pagenum_link );

		if ( isset( $url_parts[1] ) ) {
			wp_parse_str( $url_parts[1], $query_args );
		}

		$pagenum_link = remove_query_arg( array_keys( $query_args ), $pagenum_link );
   
		$pagenum_link = trailingslashit( $pagenum_link ) . '%_%';
//        var_dump('pagenun link', $pagenum_link);
//        exit;
		$format = $GLOBALS['wp_rewrite']->using_index_permalinks() && !strpos( $pagenum_link, 'index.php' ) ? 'index.php/' : '';
		$format .= $GLOBALS['wp_rewrite']->using_permalinks() ? user_trailingslashit( 'page/%#%', 'paged' ) : '?paged=%#%';
        
        $big = 999999999; // уникальное число для замены
 
	 
		// Set up paginated links.
		$links = paginate_links( array(
			'base'      => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
			'format'    => '?paged=%#%',
			'total'     => $GLOBALS['wp_query']->max_num_pages,
			'current'   => $paged,
			'mid_size'  => 2,
            'end_size'  => 2,
			'add_args'  => array_map( 'urlencode', $query_args ),
			'prev_text' => esc_html__( '<', 'eduma' ),
			'next_text' => esc_html__( '>', 'eduma' ),
			'type'      => 'list'
		) );
         
        if ($json == true){
            
            return  ent2ncr( $links );
        }
		if ( $links ) :
			?>
			<div class="pagination loop-pagination">
				<?php echo ent2ncr( $links ); ?>
			</div>
			<!-- .pagination -->
			<?php
		endif;
	}
/**
 * Enable ACF 5 early access
 * Requires at least version ACF 4.4.12 to work
 */
define('ACF_EARLY_ACCESS', '5');

//add_action( 'after_setup_theme', function() {
//    add_theme_support( 'pageviews' );
//});
