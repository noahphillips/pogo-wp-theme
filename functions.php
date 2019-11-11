<?php
/**
 * Timber starter-theme
 * https://github.com/timber/starter-theme
 *
 * @package  WordPress
 * @subpackage  Timber
 * @since   Timber 0.1
 */

define( 'TEXT_DOMAIN', 'pogo' );

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


//
//
//

/**
 * Change ACF Gutenberg blocks default block path
 * This is the callback that displays the blocks.
 *
 * @param array  $block The block settings and attributes.
 * @param string $content The block content (emtpy string).
 * @param bool   $is_preview True during AJAX preview.
 */
function my_acf_block_render_callback( $block, $content = '', $is_preview = false ) {
	$context = Timber::get_context();

	// convert name ("acf/testimonial") into path friendly slug ("testimonial")
	$slug = str_replace( 'acf/', '', $block['name'] );


	// Store block values.
	$context['block'] = $block;

	// Store field values.
	$context['fields'] = get_fields();

	// Store $is_preview value.
	$context['is_preview'] = $is_preview;

	// Render the block.
	Timber::render( 'templates/blocks/block-' . $slug . '.twig', $context );
}

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
 * Allow SVG Upload Support
 *
 * @link   https://kinsta.com/blog/wordpress-svg/
 * @link   https://css-tricks.com/snippets/wordpress/allow-svg-through-wordpress-media-uploader/
 * @since  1.0.0
 * @access public
 * @return void
 */


/**
 * Render Contact Details
 *
 * @return void
 * @since  1.0.0
 * @access public
 */
function render_contact_details() {
	$company_name    = get_theme_mod( 'company_name' ) ?: get_theme_mod( 'company_name' );
	$company_address = get_theme_mod( 'company_address' ) ?: get_theme_mod( 'company_address' );
	$company_state   = get_theme_mod( 'company_state' ) ?: get_theme_mod( 'company_state' );
	$company_phone   = get_theme_mod( 'company_phone' ) ?: get_theme_mod( 'company_phone' );
	$company_email   = get_theme_mod( 'company_email' ) ?: get_theme_mod( 'company_email' );
	$company_map     = get_theme_mod( 'company_map' ) ?: get_theme_mod( 'company_email' );
	?>
	<address class="font-body">
		<?php if ( $company_name ) { ?>
			<?php echo $company_name; ?><br>
		<?php } ?>
		<?php if ( $company_address ) { ?>
			<?php echo $company_address; ?><br>
		<?php } ?>
		<?php if ( $company_state ) { ?>
			<?php echo $company_state; ?><br>
		<?php } ?>
		<?php if ( $company_name ) { ?>
			<a href="tel:<?php echo $company_phone; ?>">Phone: <?php echo $company_phone; ?></a><br>
		<?php } ?>
		<?php if ( $company_email ) { ?>
			<a href="mailto:<?php echo $company_email; ?>"><?php echo $company_email; ?></a>
		<?php } ?>
	</address>
	<?php if ( $company_map ) { ?>
		<section data-map="google">
			<?php echo $company_map; ?>
		</section>
	<?php } ?>

	<?php
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
		add_action( 'widgets_init', array( $this, 'register_widgetized_areas' ) );
		add_action( 'customize_register', array( $this, 'register_customizer_options' ) );
		add_action( 'acf/init', array( $this, 'register_gutenberg_blocks' ) );
		add_action( 'gform_enqueue_scripts', array( $this, 'deregister_scripts' ) );
		add_action( 'init', array( $this, 'register_post_types' ) );
		add_action( 'init', array( $this, 'register_taxonomies' ) );
		add_action( 'init', array( $this, 'register_taxonomies' ) );
		add_action( 'wp_head', array( $this, 'register_gtm' ) );
		add_action( 'wp_head', array( $this, 'register_facebook_pixel' ) );
		add_action( 'wp_head', array( $this, 'register_ga_tracking_code' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts_and_styles' ) );
		parent::__construct();
	}


	/**
	 * Disable Gravity Forms styles
	 *
	 *
	 * @return void
	 * @since  1.0.0
	 * @access public
	 */
	function deregister_scripts() {
		wp_deregister_style( 'gforms_formsmain_css' );
		wp_deregister_style( 'gforms_reset_css' );
		wp_deregister_style( 'gforms_ready_class_css' );
		wp_deregister_style( 'gforms_browsers_css' );
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

		wp_register_script( 'fullpage', get_stylesheet_directory_uri() . '/static/fullpage.js', array( 'jquery' ), get_version( get_stylesheet_directory_uri() . '/static/fullpage.js' ), false );
		// wp_register_script( 'fullpage', 'https://rawgit.com/alvarotrigo/fullPage.js/dev/src/fullpage.js', array( 'jquery' ), get_version( 'https://rawgit.com/alvarotrigo/fullPage.js/dev/src/fullpage.js' ), false );
		wp_register_script( 'scriptjs', get_stylesheet_directory_uri() . '/static/script.js', array( 'jquery' ), get_version( get_stylesheet_directory_uri() . '/static/script.js' ), true );
		wp_register_script( 'events', get_stylesheet_directory_uri() . '/static/events.js', array( 'jquery' ), get_version( get_stylesheet_directory_uri() . '/static/events.js' ), true );
		wp_register_script( 'custom', get_stylesheet_directory_uri() . '/assets/js/custom.js', array( 'jquery' ), get_version( get_stylesheet_directory_uri() . '/assets/js/custom.js' ), true );

		wp_enqueue_style( 'fontawesome', '//stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css', 'all' );
		wp_enqueue_style( 'fonts', get_stylesheet_directory_uri() . '/static/fonts/fonts.css', false, get_version( '/fonts/fonts.css' ), 'all' );
		wp_enqueue_style( 'style', get_stylesheet_directory_uri() . '/style.css', false, '1.0.0', 'all' );
		wp_enqueue_style( 'custom', get_stylesheet_directory_uri() . '/css/custom.css', false, '1.0.0', 'all' );

		wp_enqueue_script( 'fullpage' );
		wp_enqueue_script( 'events' );
		wp_enqueue_script( 'custom' );

		if ( is_front_page() ) {
			wp_enqueue_script( 'scriptjs' );
		}

	}


	/**
	 * Register Google Analytics Tracking Code
	 *
	 * @return void
	 * @since  1.0.0
	 * @access public
	 */
	function register_ga_tracking_code() {
		?>
		<?php if ( get_theme_mod( 'pogo_ga_script' ) ) : ?>
			<script>
				(function (i, s, o, g, r, a, m) {
					i['GoogleAnalyticsObject'] = r;
					i[r] = i[r] || function () {
						(i[r].q = i[r].q || []).push(arguments)
					}, i[r].l = 1 * new Date();
					a = s.createElement(o),
						m = s.getElementsByTagName(o)[0];
					a.async = 1;
					a.src = g;
					m.parentNode.insertBefore(a, m)
				})(window, document, 'script', 'https://www.google-analytics.com/analytics.js', 'ga');

				ga('create', '<?php echo get_theme_mod( 'pogo_ga_script' ); ?>', 'auto');

				ga('send', 'pageview');

			</script>
		<?php endif; ?>
		<?php
	}


	/**
	 * Register GTM
	 *
	 * @return void
	 * @since  1.0.0
	 * @access public
	 */
	function register_gtm() {
		?>
		<?php if ( get_theme_mod( 'pogo_gtm_script' ) ) : ?>
			<!-- Google Tag Manager -->
			<script>(function (w, d, s, l, i) {
					w[l] = w[l] || [];
					w[l].push({
						'gtm.start':
							new Date().getTime(), event: 'gtm.js'
					});
					var f = d.getElementsByTagName(s)[0],
						j = d.createElement(s), dl = l != 'dataLayer' ? '&l=' + l : '';
					j.async = true;
					j.src =
						'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
					f.parentNode.insertBefore(j, f);
				})(window, document, 'script', 'dataLayer', '<?php echo get_theme_mod( 'pogo_gtm_script' ); ?>');
			</script>
			<!-- End Google Tag Manager -->
		<?php endif; ?>
		<?php
	}

	/**
	 * Register Facebook Pixel
	 *
	 * @return void
	 * @since  1.0.0
	 * @access public
	 */
	function register_facebook_pixel() {
		?>
		<?php // if ( get_theme_mod( 'pogo_fbp_script' ) ) : ?>
		<!-- Facebook Pixel Code -->
		<script>
			!function (f, b, e, v, n, t, s) {
				if(f.fbq) return;
				n = f.fbq = function () {
					n.callMethod ?
						n.callMethod.apply(n, arguments) : n.queue.push(arguments)
				};
				if(!f._fbq) f._fbq = n;
				n.push = n;
				n.loaded = !0;
				n.version = '2.0';
				n.queue = [];
				t = b.createElement(e);
				t.async = !0;
				t.src = v;
				s = b.getElementsByTagName(e)[0];
				s.parentNode.insertBefore(t, s)
			}(window, document, 'script',
				'https://connect.facebook.net/en_US/fbevents.js');
			fbq('init', '<?php echo get_theme_mod( 'pogo_fbp_script' ); ?>');
			fbq('track', 'PageView');
		</script>
		<noscript><img height="1" width="1" style="display:none"
					   src="https://www.facebook.com/tr?id=<?php echo get_theme_mod( 'pogo_fbp_script' ); ?>"
					   &ev=PageView&noscript=1"
			/></noscript>
		<!-- End Facebook Pixel Code -->
		<?php // endif;
	}


	/**
	 * Register sidebars.
	 *
	 * @link   https://developer.wordpress.org/reference/functions/register_sidebar/
	 * @link   https://developer.wordpress.org/reference/functions/register_sidebars/
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	function register_widgetized_areas() {

		$args = [
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h3 class="widget__title">',
			'after_title'   => '</h3>'
		];


		$my_sidebars = array(
			array(
				'name'        => 'Footer Main Widget Area',
				'id'          => 'footer_main_widget_area',
				'description' => 'Widgets shown in the footer',
			),
		);

		$defaults = array(
			'name'          => 'Awesome Sidebar',
			'id'            => 'awesome-sidebar',
			'description'   => 'This Sidebar is shown as the default of the theme',
			'class'         => '',
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h3 class="widget__title">',
			'after_title'   => '</h3>'
		);

		foreach ( $my_sidebars as $sidebar ) {
			$args = wp_parse_args( $sidebar, $defaults );
			register_sidebar( $args );
		}

	}


	/** This is where you can register custom post types. */
	public function register_post_types() {

		# Register custom post type Client Logo
		/**
		 * Registers post types needed by the theme.
		 *
		 * @return void
		 * @since  1.1.0
		 * @access public
		 */
		$brand_labels = array(
			'name'                  => _x( 'Leadership', 'Post Type General Name', TEXT_DOMAIN ),
			'singular_name'         => _x( 'Leadership', 'Post Type Singular Name', TEXT_DOMAIN ),
			'menu_name'             => __( 'Leadership', TEXT_DOMAIN ),
			'name_admin_bar'        => __( 'Leadership', TEXT_DOMAIN ),
			'archives'              => __( 'Leadership Archives', TEXT_DOMAIN ),
			'attributes'            => __( 'Leadership Attributes', TEXT_DOMAIN ),
			'parent_item_colon'     => __( 'Parent Leadership:', TEXT_DOMAIN ),
			'all_items'             => __( 'All members', TEXT_DOMAIN ),
			'add_new_item'          => __( 'Add New member', TEXT_DOMAIN ),
			'add_new'               => __( 'Add New', TEXT_DOMAIN ),
			'new_item'              => __( 'New Leadership member', TEXT_DOMAIN ),
			'edit_item'             => __( 'Edit Leadership member', TEXT_DOMAIN ),
			'update_item'           => __( 'Update Leadership member', TEXT_DOMAIN ),
			'view_item'             => __( 'View Leadership member', TEXT_DOMAIN ),
			'view_items'            => __( 'View Leadership members', TEXT_DOMAIN ),
			'search_items'          => __( 'Search Leadership member', TEXT_DOMAIN ),
			'not_found'             => __( 'Not found', TEXT_DOMAIN ),
			'not_found_in_trash'    => __( 'Not found in Trash', TEXT_DOMAIN ),
			'featured_image'        => __( 'Featured Image', TEXT_DOMAIN ),
			'set_featured_image'    => __( 'Set featured image', TEXT_DOMAIN ),
			'remove_featured_image' => __( 'Remove featured image', TEXT_DOMAIN ),
			'use_featured_image'    => __( 'Use as featured image', TEXT_DOMAIN ),
			'insert_into_item'      => __( 'Insert into Leadership', TEXT_DOMAIN ),
			'uploaded_to_this_item' => __( 'Uploaded to this Leadership', TEXT_DOMAIN ),
			'items_list'            => __( 'Leadership members list', TEXT_DOMAIN ),
			'items_list_navigation' => __( 'Leadership members list navigation', TEXT_DOMAIN ),
			'filter_items_list'     => __( 'Filter Leadership members list', TEXT_DOMAIN ),
		);
		$brand_args   = array(
			'label'               => __( 'Leadership Member', TEXT_DOMAIN ),
			'description'         => __( 'Leadership Member', TEXT_DOMAIN ),
			'labels'              => $brand_labels,
			'supports'            => array( 'title', 'editor', 'thumbnail' ),
			'menu_icon'           => 'dashicons-groups',
			'taxonomies'          => array(),
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'menu_position'       => 10,
			'show_in_admin_bar'   => true,
			'show_in_nav_menus'   => true,
			'show_in_rest'        => true,
			'can_export'          => true,
			'has_archive'         => true,
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			'capability_type'     => 'page',
		);
		register_post_type( 'leadership', $brand_args );


		# Register custom post type Client Logo
		/**
		 * Registers post types needed by the theme.
		 *
		 * @return void
		 * @since  1.1.0
		 * @access public
		 */
		$brand_labels = array(
			'name'                  => _x( 'Career', 'Post Type General Name', TEXT_DOMAIN ),
			'singular_name'         => _x( 'Career', 'Post Type Singular Name', TEXT_DOMAIN ),
			'menu_name'             => __( 'Career', TEXT_DOMAIN ),
			'name_admin_bar'        => __( 'Career', TEXT_DOMAIN ),
			'archives'              => __( 'Career Archives', TEXT_DOMAIN ),
			'attributes'            => __( 'Career Attributes', TEXT_DOMAIN ),
			'parent_item_colon'     => __( 'Parent Career:', TEXT_DOMAIN ),
			'all_items'             => __( 'All members', TEXT_DOMAIN ),
			'add_new_item'          => __( 'Add New member', TEXT_DOMAIN ),
			'add_new'               => __( 'Add New', TEXT_DOMAIN ),
			'new_item'              => __( 'New Career member', TEXT_DOMAIN ),
			'edit_item'             => __( 'Edit Career member', TEXT_DOMAIN ),
			'update_item'           => __( 'Update Career member', TEXT_DOMAIN ),
			'view_item'             => __( 'View Career member', TEXT_DOMAIN ),
			'view_items'            => __( 'View Career members', TEXT_DOMAIN ),
			'search_items'          => __( 'Search Career member', TEXT_DOMAIN ),
			'not_found'             => __( 'Not found', TEXT_DOMAIN ),
			'not_found_in_trash'    => __( 'Not found in Trash', TEXT_DOMAIN ),
			'featured_image'        => __( 'Featured Image', TEXT_DOMAIN ),
			'set_featured_image'    => __( 'Set featured image', TEXT_DOMAIN ),
			'remove_featured_image' => __( 'Remove featured image', TEXT_DOMAIN ),
			'use_featured_image'    => __( 'Use as featured image', TEXT_DOMAIN ),
			'insert_into_item'      => __( 'Insert into Career', TEXT_DOMAIN ),
			'uploaded_to_this_item' => __( 'Uploaded to this Career', TEXT_DOMAIN ),
			'items_list'            => __( 'Career members list', TEXT_DOMAIN ),
			'items_list_navigation' => __( 'Career members list navigation', TEXT_DOMAIN ),
			'filter_items_list'     => __( 'Filter Career members list', TEXT_DOMAIN ),
		);
		$brand_args   = array(
			'label'               => __( 'Career Member', TEXT_DOMAIN ),
			'description'         => __( 'Career Member', TEXT_DOMAIN ),
			'labels'              => $brand_labels,
			'supports'            => array( 'title', 'editor', 'thumbnail' ),
			'menu_icon'           => 'dashicons-businessperson',
			'taxonomies'          => array(),
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'menu_position'       => 10,
			'show_in_admin_bar'   => true,
			'show_in_nav_menus'   => true,
			'show_in_rest'        => true,
			'can_export'          => true,
			'has_archive'         => true,
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			'capability_type'     => 'page',
		);
		register_post_type( 'career', $brand_args );

	}

	/** This is where you can register custom taxonomies. */
	public function register_taxonomies() {

	}

	/**
	 * Registers ACF Gutenberg Blocks
	 *
	 * @return void
	 * @since  1.0.0
	 * @access public
	 */
	public function register_gutenberg_blocks() {

		// check function exists otherwise return
		if ( ! function_exists( 'acf_register_block' ) ) {
			return;
		}

		/**
		 * Registers Leadership Members ACF Gutenberg block
		 *
		 * @return void
		 * @since  1.0.0
		 * @access public
		 */
		acf_register_block( array(
			'name'            => 'leadership-members',
			'title'           => __( 'Leadership Members' ),
			'description'     => __( 'a custom Gutenberg Block to display Leadership members.' ),
			'render_callback' => 'my_acf_block_render_callback',
			'category'        => 'formatting',
			'icon'            => 'admin-comments',
			'keywords'        => array( 'member', 'members', 'leadership' ),
		) );

		/**
		 * Registers Careers List ACF Gutenberg block
		 *
		 * @return void
		 * @since  1.0.0
		 * @access public
		 */
		acf_register_block( array(
			'name'            => 'careers-list',
			'title'           => __( 'Careers List' ),
			'description'     => __( 'a custom Gutenberg Block to display Career Items.' ),
			'render_callback' => 'my_acf_block_render_callback',
			'category'        => 'formatting',
			'icon'            => 'admin-comments',
			'keywords'        => array( 'career', 'careers' ),
		) );

		/**
		 * Registers Small List ACF Gutenberg block
		 *
		 * @return void
		 * @since  1.0.0
		 * @access public
		 */
		acf_register_block( array(
			'name'            => 'small-list',
			'title'           => __( 'Small List' ),
			'description'     => __( 'a custom Gutenberg Block to displays a small icon list.' ),
			'render_callback' => 'my_acf_block_render_callback',
			'category'        => 'formatting',
			'icon'            => 'admin-comments',
			'keywords'        => array( 'list', 'lists', 'small' ),
		) );

		/**
		 * Registers Contact Details ACF Gutenberg block
		 *
		 * @return void
		 * @since  1.0.0
		 * @access public
		 */
		acf_register_block( array(
			'name'            => 'contact-details',
			'title'           => __( 'Contact Details' ),
			'description'     => __( 'a custom Gutenberg Block to display company contact details.' ),
			'render_callback' => 'my_acf_block_render_callback',
			'category'        => 'formatting',
			'icon'            => 'admin-comments',
			'keywords'        => array( 'list', 'lists', 'small' ),
		) );

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

		// Add setting for mobile menu logo uploader
		$wp_customize->add_setting( 'site_logo' );

		// Add control for mobile menu logo uploader (actual uploader)
		/**
		 * Site Logo control.
		 *
		 * - Control: Image: Site Logo
		 * - Setting: Site Logo
		 * - Sanitization: input
		 *
		 * Register the core "input" file upload control to be used to configure the Site Logo setting.
		 *
		 * @uses $wp_customize->add_control() https://developer.wordpress.org/reference/classes/wp_customize_manager/add_control/
		 * @link $wp_customize->add_control() https://codex.wordpress.org/Class_Reference/WP_Customize_Manager/add_control
		 * */
		$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'site_logo', array(
			'label'    => __( 'Site Logo', TEXT_DOMAIN ),
			'section'  => 'title_tagline',
			'settings' => 'site_logo',
			'priority' => 9,
		) ) );


		// Add setting for footer logo uploader
		$wp_customize->add_setting( 'footer_logo' );

		// Add control for footer logo uploader (actual uploader)
		/**
		 * Footer Logo control.
		 *
		 * - Control: Image: Footer Logo
		 * - Setting: Footer Logo
		 * - Sanitization: input
		 *
		 * Register the core "input" file upload control to be used to configure the Footer Logo setting.
		 *
		 * @uses $wp_customize->add_control() https://developer.wordpress.org/reference/classes/wp_customize_manager/add_control/
		 * @link $wp_customize->add_control() https://codex.wordpress.org/Class_Reference/WP_Customize_Manager/add_control
		 * */
		$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'footer_logo', array(
			'label'    => __( 'Footer Logo', TEXT_DOMAIN ),
			'section'  => 'title_tagline',
			'settings' => 'footer_logo',
			'priority' => 9,
		) ) );


		/**
		 * Footer Copyright setting example.
		 *
		 * - Setting: Footer Copyright
		 * - Control: text
		 *
		 * Uses a text to configure the user-defined Facebook Pixel Code for the Theme.
		 *
		 * @uses $wp_customize->add_setting() https://developer.wordpress.org/reference/classes/wp_customize_manager/add_setting/
		 * @link $wp_customize->add_setting() https://codex.wordpress.org/Class_Reference/WP_Customize_Manager/add_setting
		 */
		$wp_customize->add_setting(
		// $id
			'pogo_footer_copyrights',
			// $args
			array(
				'default' => '&copy; Intuity Medical, Inc. All rights reserved.',
			)
		);


		/**
		 * Basic Text control.
		 *
		 * - Control: Basic: Text
		 * - Setting: Footer Copyrights
		 *
		 * Register the core "text" control to be used to configure the Footer Copyrights Settings
		 *
		 * @uses $wp_customize->add_control() https://developer.wordpress.org/reference/classes/wp_customize_manager/add_control/
		 * @link $wp_customize->add_control() https://codex.wordpress.org/Class_Reference/WP_Customize_Manager/add_control
		 */
		$wp_customize->add_control(
		// $id
			'pogo_footer_copyrights',
			// $args
			array(
				'settings' => 'pogo_footer_copyrights',
				'section'  => 'title_tagline',
				'type'     => 'text',
				'label'    => __( 'Footer Copyrights ', TEXT_DOMAIN ),
			)
		);


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
		 * General Settings Section
		 *
		 * @link: https://www.competethemes.com/social-icons-wordpress-menu-theme-customizer/
		 */
		$wp_customize->add_section( 'general_settings', array(
			'title'    => __( 'General Settings', TEXT_DOMAIN ),
			'priority' => 105,
		) );

		/**
		 * Careers Page setting example.
		 *
		 * - Setting: Careers Page
		 * - Control: text
		 *
		 * Uses a text to configure the user-defined Careers Page for the Theme.
		 *
		 * @uses $wp_customize->add_setting() https://developer.wordpress.org/reference/classes/wp_customize_manager/add_setting/
		 * @link $wp_customize->add_setting() https://codex.wordpress.org/Class_Reference/WP_Customize_Manager/add_setting
		 */
		$wp_customize->add_setting( 'pogo_careers_page',
			array(
				'default'   => $this->defaults['pogo_careers_page'],
				'transport' => 'refresh',
//				'sanitize_callback' => 'absint'
			)
		);

		/**
		 * Basic Text control.
		 *
		 * - Control: Basic: Text
		 * - Setting: Careers Page
		 *
		 * Register the core "text" control to be used to configure the Careers Page Settings
		 *
		 * @uses $wp_customize->add_control() https://developer.wordpress.org/reference/classes/wp_customize_manager/add_control/
		 * @link $wp_customize->add_control() https://codex.wordpress.org/Class_Reference/WP_Customize_Manager/add_control
		 */
		$wp_customize->add_control( 'pogo_careers_page',
			array(
				'label'   => __( 'Careers Page', TEXT_DOMAIN ),
				'section' => 'general_settings',
				'type'    => 'dropdown-pages'
			)
		);


		/**
		 * Contact Details Section
		 *
		 * @link: https://www.competethemes.com/social-icons-wordpress-menu-theme-customizer/
		 */
		$wp_customize->add_section( 'contact_details', array(
			'title'    => __( 'Contact Details', TEXT_DOMAIN ),
			'priority' => 110,
		) );

		/**
		 * Company Name setting example.
		 *
		 * - Setting: Company Name
		 * - Control: text
		 *
		 * Uses a text to configure the user-defined Company Name for the Theme.
		 *
		 * @uses $wp_customize->add_setting() https://developer.wordpress.org/reference/classes/wp_customize_manager/add_setting/
		 * @link $wp_customize->add_setting() https://codex.wordpress.org/Class_Reference/WP_Customize_Manager/add_setting
		 */
		$wp_customize->add_setting(
		// $id
			'company_name',
			// $args
			array(
				'default' => 'Intuity Medical, Inc.',
			)
		);


		/**
		 * Basic Text control.
		 *
		 * - Control: Basic: Text
		 * - Setting: Company Name
		 *
		 * Register the core "text" control to be used to configure the Company Name Settings
		 *
		 * @uses $wp_customize->add_control() https://developer.wordpress.org/reference/classes/wp_customize_manager/add_control/
		 * @link $wp_customize->add_control() https://codex.wordpress.org/Class_Reference/WP_Customize_Manager/add_control
		 */
		$wp_customize->add_control(
		// $id
			'company_name',
			// $args
			array(
				'settings' => 'company_name',
				'section'  => 'contact_details',
				'type'     => 'text',
				'label'    => __( 'Company Name ', TEXT_DOMAIN ),
			)
		);


		/**
		 * Address setting example.
		 *
		 * - Setting: Address
		 * - Control: text
		 *
		 * Uses a text to configure the user-defined Address for the Theme.
		 *
		 * @uses $wp_customize->add_setting() https://developer.wordpress.org/reference/classes/wp_customize_manager/add_setting/
		 * @link $wp_customize->add_setting() https://codex.wordpress.org/Class_Reference/WP_Customize_Manager/add_setting
		 */
		$wp_customize->add_setting(
		// $id
			'company_address',
			// $args
			array(
				'default' => 'Fermonth iuhrgiuhriu 65',
			)
		);


		/**
		 * Basic Text control.
		 *
		 * - Control: Basic: Text
		 * - Setting: Address
		 *
		 * Register the core "text" control to be used to configure the Address Settings
		 *
		 * @uses $wp_customize->add_control() https://developer.wordpress.org/reference/classes/wp_customize_manager/add_control/
		 * @link $wp_customize->add_control() https://codex.wordpress.org/Class_Reference/WP_Customize_Manager/add_control
		 */
		$wp_customize->add_control(
		// $id
			'company_address',
			// $args
			array(
				'settings' => 'company_address',
				'section'  => 'contact_details',
				'type'     => 'text',
				'label'    => __( 'Address ', TEXT_DOMAIN ),
			)
		);


		/**
		 * State setting example.
		 *
		 * - Setting: State
		 * - Control: text
		 *
		 * Uses a text to configure the user-defined State for the Theme.
		 *
		 * @uses $wp_customize->add_setting() https://developer.wordpress.org/reference/classes/wp_customize_manager/add_setting/
		 * @link $wp_customize->add_setting() https://codex.wordpress.org/Class_Reference/WP_Customize_Manager/add_setting
		 */
		$wp_customize->add_setting(
		// $id
			'company_state',
			// $args
			array(
				'default' => 'Fremont, CA 94538',
			)
		);


		/**
		 * Basic Text control.
		 *
		 * - Control: Basic: Text
		 * - Setting: State
		 *
		 * Register the core "text" control to be used to configure the State Settings
		 *
		 * @uses $wp_customize->add_control() https://developer.wordpress.org/reference/classes/wp_customize_manager/add_control/
		 * @link $wp_customize->add_control() https://codex.wordpress.org/Class_Reference/WP_Customize_Manager/add_control
		 */
		$wp_customize->add_control(
		// $id
			'company_state',
			// $args
			array(
				'settings' => 'company_state',
				'section'  => 'contact_details',
				'type'     => 'text',
				'label'    => __( 'State ', TEXT_DOMAIN ),
			)
		);

		/**
		 * Phone Number setting example.
		 *
		 * - Setting: Phone Number
		 * - Control: text
		 *
		 * Uses a text to configure the user-defined Phone Number for the Theme.
		 *
		 * @uses $wp_customize->add_setting() https://developer.wordpress.org/reference/classes/wp_customize_manager/add_setting/
		 * @link $wp_customize->add_setting() https://codex.wordpress.org/Class_Reference/WP_Customize_Manager/add_setting
		 */
		$wp_customize->add_setting(
		// $id
			'company_phone',
			// $args
			array(
				'default' => '510.946.8800',
			)
		);


		/**
		 * Basic Text control.
		 *
		 * - Control: Basic: Text
		 * - Setting: Phone Number
		 *
		 * Register the core "text" control to be used to configure the Phone Number Settings
		 *
		 * @uses $wp_customize->add_control() https://developer.wordpress.org/reference/classes/wp_customize_manager/add_control/
		 * @link $wp_customize->add_control() https://codex.wordpress.org/Class_Reference/WP_Customize_Manager/add_control
		 */
		$wp_customize->add_control(
		// $id
			'company_phone',
			// $args
			array(
				'settings' => 'company_phone',
				'section'  => 'contact_details',
				'type'     => 'text',
				'label'    => __( 'Phone Number ', TEXT_DOMAIN ),
			)
		);


		/**
		 * Email Address setting example.
		 *
		 * - Setting: Email Address
		 * - Control: text
		 *
		 * Uses a text to configure the user-defined Email Address for the Theme.
		 *
		 * @uses $wp_customize->add_setting() https://developer.wordpress.org/reference/classes/wp_customize_manager/add_setting/
		 * @link $wp_customize->add_setting() https://codex.wordpress.org/Class_Reference/WP_Customize_Manager/add_setting
		 */
		$wp_customize->add_setting(
		// $id
			'company_email',
			// $args
			array(
				'default' => 'info@intuitymedical.com
',
			)
		);


		/**
		 * Basic Text control.
		 *
		 * - Control: Basic: Text
		 * - Setting: Email Address
		 *
		 * Register the core "text" control to be used to configure the Email Address Settings
		 *
		 * @uses $wp_customize->add_control() https://developer.wordpress.org/reference/classes/wp_customize_manager/add_control/
		 * @link $wp_customize->add_control() https://codex.wordpress.org/Class_Reference/WP_Customize_Manager/add_control
		 */
		$wp_customize->add_control(
		// $id
			'company_email',
			// $args
			array(
				'settings' => 'company_email',
				'section'  => 'contact_details',
				'type'     => 'text',
				'label'    => __( 'Email Address ', TEXT_DOMAIN ),
			)
		);


		$wp_customize->add_setting( 'company_map', array(
			'capability' => 'edit_theme_options',
			'default'    => '',
//			'sanitize_callback' => 'sanitize_textarea_field',
		) );

		$wp_customize->add_control( 'company_map', array(
			'type'        => 'textarea',
			'section'     => 'contact_details', // // Add a default or your own section
			'label'       => __( 'Map' ),
			'description' => __( 'Add your map as an iframe through Google Maps' ),
		) );


		/**
		 * Social site icons for Quick Menu bar
		 *
		 * @link: https://www.competethemes.com/social-icons-wordpress-menu-theme-customizer/
		 */
		$wp_customize->add_section( 'social_settings', array(
			'title'    => __( 'Social Media Icons', TEXT_DOMAIN ),
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
		$context['menu'] = new Timber\Menu();
		$context['site'] = $this;

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

