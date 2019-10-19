<?php
/**
 * Timber starter-theme
 * https://github.com/timber/starter-theme
 *
 * @package  WordPress
 * @subpackage  Timber
 * @since   Timber 0.1
 */

define('TEXT_DOMAIN', 'pogo');

if ( ! class_exists( 'Timber' ) ) {
	add_action( 'admin_notices', function () {
		echo '<div class="error"><p>Timber not activated. Make sure you activate the plugin in <a href="' . esc_url( admin_url( 'plugins.php#timber' ) ) . '">' . esc_url( admin_url( 'plugins.php' ) ) . '</a></p></div>';
	} );

	add_filter( 'template_include', function ( $template ) {
		return get_stylesheet_directory() . '/static/no-timber.html';
	} );

	return;
}

/**
 * Sets the directories (inside your theme) to find .twig files
 */
Timber::$dirname = array( 'templates', 'views' );

/**
 * By default, Timber does NOT autoescape values. Want to enable Twig's autoescape?
 * No prob! Just set this value to true
 */
Timber::$autoescape = false;

/**
 * Creates a cache busting string for styles and scripts
 *
 */
function get_version( $src ) {
	$src = get_template_directory() . $src;

	$file = realpath( $src );
	if ( file_exists( $file ) ) {
		return filemtime( $file );
	}

	return false;
}

/* Social Media icon helper functions
 *
 * @return array
 *
 *
 * @package    pogo
 * @since      1.0.0
 * @version    1.0.0
 */
function pogo_get_social_sites() {

	// Store social site names in array
	$social_sites = array(
		'facebook',
		'twitter',
		'instagram',
		'linkedin',
		'youtube',
	);

	return $social_sites;
}


/**
 * We're going to configure our theme inside of a subclass of Timber\Site
 * You can move this to its own file and include here via php's include("MySite.php")
 */
class StarterSite extends Timber\Site {
	/** Add timber support. */
	public function __construct() {
		add_filter( 'timber/context', array( $this, 'add_to_context' ) );
		add_filter( 'timber/twig', array( $this, 'add_to_twig' ) );
		add_filter( 'body_class', array( $this, 'custom_body_classes' ) );
		add_action( 'after_setup_theme', array( $this, 'theme_supports' ) );
		add_action( 'customize_register', array( $this, 'register_customizer_options' ) );
		add_action( 'init', array( $this, 'register_post_types' ) );
		add_action( 'init', array( $this, 'register_taxonomies' ) );
		add_action( 'init', array( $this, 'register_taxonomies' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts_and_styles' ) );
		parent::__construct();
	}

	/**
	 * Adding browser specific classes
	 * to be used in CSS to target browser-specific cases
	 *
	 * @use This one is used in body
	 *
	 * @return void
	 * @since  1.0.0
	 * @access public
	 */
	function custom_body_classes( $classes ) {
		global $is_lynx, $is_gecko, $is_IE, $is_opera, $is_NS4, $is_safari, $is_chrome, $is_iphone;

		if ( $is_lynx ) {
			$classes[] = 'lynx';
		} elseif ( $is_gecko ) {
			$classes[] = 'gecko';
		} elseif ( $is_opera ) {
			$classes[] = 'opera';
		} elseif ( $is_NS4 ) {
			$classes[] = 'ns4';
		} elseif ( $is_safari ) {
			$classes[] = 'safari';
		} elseif ( $is_chrome ) {
			$classes[] = 'chrome';
		} elseif ( $is_IE ) {
			$browser = $_SERVER['HTTP_USER_AGENT'];
			$browser = substr( "$browser", 25, 8 );
			if ( $browser == "MSIE 7.0" ) {
				$classes[] = 'ie7';
				$classes[] = 'ie';
			} elseif ( $browser == "MSIE 6.0" ) {
				$classes[] = 'ie6';
				$classes[] = 'ie';
			} elseif ( $browser == "MSIE 8.0" ) {
				$classes[] = 'ie8';
				$classes[] = 'ie';
			} elseif ( $browser == "MSIE 9.0" ) {
				$classes[] = 'ie9';
				$classes[] = 'ie';
			} else {
				$classes[] = 'ie';
			}
		} else {
			$classes[] = 'unknown';
		}

		if ( $is_iphone ) {
			$classes[] = 'iphone';
		}

		if ( stristr( $_SERVER['HTTP_USER_AGENT'], "Edge" ) ) {
			$classes[] = 'ms_edge';
		}

		if ( stristr( $_SERVER['HTTP_USER_AGENT'], "Trident" ) ) {
			$classes[] = 'ie11';
		}

		if ( stristr( $_SERVER['HTTP_USER_AGENT'], "mac" ) ) {
			$classes[] = 'osx';
		} elseif ( stristr( $_SERVER['HTTP_USER_AGENT'], "linux" ) ) {
			$classes[] = 'linux';
		} elseif ( stristr( $_SERVER['HTTP_USER_AGENT'], "windows" ) ) {
			$classes[] = 'windows';
		}

		return $classes;
	}

	public function enqueue_scripts_and_styles() {

		wp_register_script( 'scriptjs', get_stylesheet_directory_uri() . '/static/script.js', array( 'jquery' ), get_version( get_stylesheet_directory_uri() . '/static/script.js' ), true );
		wp_register_script( 'events', get_stylesheet_directory_uri() . '/static/events.js', array( 'jquery' ), get_version( get_stylesheet_directory_uri() . '/static/events.js' ), true );

		wp_enqueue_style( 'fullpage', get_stylesheet_directory_uri() . '/fullpage.css', false, get_version( '/fullpage.css' ), 'all' );
		wp_enqueue_style( 'fonts', get_stylesheet_directory_uri() . '/fonts/fonts.css', false, get_version( '/fonts/fonts.css' ), 'all' );
		wp_enqueue_style( 'style', get_stylesheet_directory_uri() . '/style.css', false, '1.0.0', 'all' );

		if(is_front_page()) {
//			wp_enqueue_script('scriptjs');
//			wp_enqueue_script('events');
		}

	}


	/** This is where you can register custom post types. */
	public function register_post_types() {

	}

	/** This is where you can register custom taxonomies. */
	public function register_taxonomies() {

	}


	/**
	 * Customizer Options.
	 *
	 * This function holds all Customizer Options
	 * meant for use within the theme
	 *
	 * @package   Phononic
	 * @link      https://pogo.com
	 */
	function register_customizer_options( $wp_customize ) {


		/**
		 * Add Script Tags section and fields
		 *
		 * @link: https://codex.wordpress.org/Theme_Customization_API
		 * @link: https://codex.wordpress.org/Class_Reference/WP_Customize_Manager/add_section
		 *
		 * @package    world-schools
		 */
		$wp_customize->add_section( 'pogo_scripts_section', array(
			'title'    => esc_html__( 'Script Tags', TEXT_DOMAIN ),
			'priority' => 120,
		) );


		/**
		 * GA Tags setting example.
		 *
		 * - Setting: GA Tags
		 * - Control: text
		 * - Sanitization: css
		 *
		 * Uses a text to configure the user-defined Script Tags for the Theme.
		 *
		 * @uses $wp_customize->add_setting() https://developer.wordpress.org/reference/classes/wp_customize_manager/add_setting/
		 * @link $wp_customize->add_setting() https://codex.wordpress.org/Class_Reference/WP_Customize_Manager/add_setting
		 */
		$wp_customize->add_setting(
		// $id
			'pogo_ga_script',
			// $args
			array(
				'default' => 'UA-75344479-1',
			)
		);


		/**
		 * Basic Text control.
		 *
		 * - Control: Basic: Text
		 * - Setting: GA Tags
		 *
		 * Register the core "textarea" control to be used to configure the GA Settings
		 *
		 * @uses $wp_customize->add_control() https://developer.wordpress.org/reference/classes/wp_customize_manager/add_control/
		 * @link $wp_customize->add_control() https://codex.wordpress.org/Class_Reference/WP_Customize_Manager/add_control
		 */
		$wp_customize->add_control(
		// $id
			'pogo_ga_script',
			// $args
			array(
				'settings'    => 'pogo_ga_script',
				'section'     => 'pogo_scripts_section',
				'type'        => 'text',
				'label'       => __( 'GA Tracking Code', TEXT_DOMAIN ),
				'description' => __( 'Add GA Tracking code, example UA-XXXXXXXX-X', TEXT_DOMAIN ),
			)
		);


		/**
		 * GTM Tags setting example.
		 *
		 * - Setting: GTM Tags
		 * - Control: text
		 *
		 * Uses a text to configure the user-defined Script Tags for the Theme.
		 *
		 * @uses $wp_customize->add_setting() https://developer.wordpress.org/reference/classes/wp_customize_manager/add_setting/
		 * @link $wp_customize->add_setting() https://codex.wordpress.org/Class_Reference/WP_Customize_Manager/add_setting
		 */
		$wp_customize->add_setting(
		// $id
			'pogo_gtm_script',
			// $args
			array(
				'default' => 'UA-XXXXXXXX-X',
			)
		);


		/**
		 * Basic Text control.
		 *
		 * - Control: Basic: Text
		 * - Setting: GTM Tags
		 *
		 * Register the core "text" control to be used to configure the GTM Settings
		 *
		 * @uses $wp_customize->add_control() https://developer.wordpress.org/reference/classes/wp_customize_manager/add_control/
		 * @link $wp_customize->add_control() https://codex.wordpress.org/Class_Reference/WP_Customize_Manager/add_control
		 */
		$wp_customize->add_control(
		// $id
			'pogo_gtm_script',
			// $args
			array(
				'settings'    => 'pogo_gtm_script',
				'section'     => 'pogo_scripts_section',
				'type'        => 'text',
				'label'       => __( 'GTM Tracking Code ', TEXT_DOMAIN ),
				'description' => __( 'Add Google Tag Manager Tracking code, example GTM-XXXXXXX', TEXT_DOMAIN ),
			)
		);


		/**
		 * Facebook Pixel Code setting example.
		 *
		 * - Setting: Facebook Pixel Code
		 * - Control: text
		 *
		 * Uses a text to configure the user-defined Facebook Pixel Code for the Theme.
		 *
		 * @uses $wp_customize->add_setting() https://developer.wordpress.org/reference/classes/wp_customize_manager/add_setting/
		 * @link $wp_customize->add_setting() https://codex.wordpress.org/Class_Reference/WP_Customize_Manager/add_setting
		 */
		$wp_customize->add_setting(
		// $id
			'pogo_fbp_script',
			// $args
			array(
				'default' => 'XXXXXXXXXXXXXXX',
			)
		);


		/**
		 * Basic Text control.
		 *
		 * - Control: Basic: Text
		 * - Setting: Facebook Pixel Code
		 *
		 * Register the core "text" control to be used to configure the Facebook Pixel Code Settings
		 *
		 * @uses $wp_customize->add_control() https://developer.wordpress.org/reference/classes/wp_customize_manager/add_control/
		 * @link $wp_customize->add_control() https://codex.wordpress.org/Class_Reference/WP_Customize_Manager/add_control
		 */
		$wp_customize->add_control(
		// $id
			'pogo_fbp_script',
			// $args
			array(
				'settings'    => 'pogo_fbp_script',
				'section'     => 'pogo_scripts_section',
				'type'        => 'text',
				'label'       => __( 'Facebook Pixel Code ', TEXT_DOMAIN ),
				'description' => __( 'Add Facebook Pixel code, example XXXXXXXXXXXXXXX', TEXT_DOMAIN ),
			)
		);

		/**
		 * Social site icons for Quick Menu bar
		 *
		 * @link: https://www.competethemes.com/social-icons-wordpress-menu-theme-customizer/
		 */
		$wp_customize->add_section( 'social_settings', array(
			'title'    => __( 'Social Media Icons', 'aow' ),
			'priority' => 120,
		) );


		/**
		 * Custom Toggle Checkbox to Show Social Icons
		 **/
		$wp_customize->add_setting( 'show_footer_social', array(
			'default' => '1'
		) );

		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				'show_footer_social',
				array(
					'label'    => __( 'Show Social Media Icons', TEXT_DOMAIN ),
					'section'  => 'social_settings',
					'settings' => 'show_footer_social',
					'type'     => 'checkbox',
					'priority' => 3,
				)
			)
		);


		/**
		 * Add social icon settings and controls
		 **/
		$social_sites = pogo_get_social_sites();
		$priority     = 5;

		foreach ( $social_sites as $social_site ) {

			$wp_customize->add_setting( "$social_site", array(
				'type'              => 'theme_mod',
				'capability'        => 'edit_theme_options',
				'sanitize_callback' => 'esc_url_raw',
			) );

			$wp_customize->add_control( $social_site, array(
				'label'    => ucwords( __( "$social_site URL:", 'social_icon' ) ),
				'section'  => 'social_settings',
				'type'     => 'text',
				'priority' => $priority,
			) );

			$priority += 5;
		}

	}



	/** This is where you add some context
	 *
	 * @param string $context context['this'] Being the Twig's {{ this }}.
	 */
	public function add_to_context( $context ) {
//		$context['foo']   = 'bar';
//		$context['stuff'] = 'I am a value set in your functions.php file';
//		$context['notes'] = 'These values are available everytime you call Timber::context();';
		$context['menu']  = new Timber\Menu();
		$context['site']  = $this;

		return $context;
	}

	public function theme_supports() {
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

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support(
			'html5', array(
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
			)
		);

		/*
		 * Enable support for Post Formats.
		 *
		 * See: https://codex.wordpress.org/Post_Formats
		 */
		add_theme_support(
			'post-formats', array(
				'aside',
				'image',
				'video',
				'quote',
				'link',
				'gallery',
				'audio',
			)
		);

		add_theme_support( 'menus' );
	}

	/** This Would return 'foo bar!'.
	 *
	 * @param string $text being 'foo', then returned 'foo bar!'.
	 */
	public function myfoo( $text ) {
		$text .= ' bar!';

		return $text;
	}

	/** This is where you can add your own functions to twig.
	 *
	 * @param string $twig get extension.
	 */
	public function add_to_twig( $twig ) {
		$twig->addExtension( new Twig_Extension_StringLoader() );
		$twig->addFilter( new Twig_SimpleFilter( 'myfoo', array( $this, 'myfoo' ) ) );

		return $twig;
	}

}

new StarterSite();
