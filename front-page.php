<?php
/**
 * The main template file
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists
 *
 * Methods for TimberHelper can be found in the /lib sub-directory
 *
 * @package  WordPress
 * @subpackage  Timber
 * @since   Timber 0.1
 */

global $post;

$site_logo          = get_theme_mod( 'site_logo' );
$footer_logo        = get_theme_mod( 'footer_logo' );
$header_home_text   = get_theme_mod( 'pogo_header_home_text' );
$header_home_link   = get_theme_mod( 'pogo_header_home_link' );
$header_login_text  = get_theme_mod( 'pogo_header_login_text' );
$header_login_link  = get_theme_mod( 'pogo_header_login_link' );
$footer_copyrights  = get_theme_mod( 'pogo_footer_copyrights' );
$gtm_script_tag     = get_theme_mod( 'pogo_gtm_script' );
$show_footer_social = get_theme_mod( 'show_footer_social' ); // Show footer social checkbox
$social_sites       = pogo_get_social_sites(); // Get all social sites as an array

// Any inputs that aren't empty are stored in $social_networks array
foreach ( $social_sites as $key => $social_site ) {
	if ( strlen( get_theme_mod( $social_site ) ) > 0 ) {
		$social_networks[ $key ]['name'] = $social_site;
		$social_networks[ $key ]['url']  = get_theme_mod( $social_site );
	}
}
$context                            = Timber::context();
$timber_post                        = new Timber\Post();
$context['post']                    = $timber_post;
$context['sections']                = get_field( 'section', $post->ID );
$context['disclaimer']              = get_field( 'page_disclaimer', $post->ID );
$context['site_logo']               = $site_logo;
$context['footer_logo']             = $footer_logo;
$context['header_home_text']        = $header_home_text;
$context['header_home_link']        = $header_home_link;
$context['header_login_text']       = $header_login_text;
$context['header_login_link']       = $header_login_link;
$context['show_footer_social']      = $show_footer_social;
$context['social_networks']         = $social_networks;
$context['footer_copyrights']       = $footer_copyrights;
$context['footer_main_widget_area'] = Timber::get_widgets( 'footer_main_widget_area' );
$templates                          = array( 'index.twig' );

if ( is_home() ) {
//	array_unshift( $templates, 'front-page.twig', 'home.twig' );
}


//var_dump($post->ID);
//var_dump($timber_post);
//var_dump($context);
//print_r($templates);

Timber::render( $templates, $context );
