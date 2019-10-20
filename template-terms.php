<?php
/**
 * Template Name: Terms of Use
 * Description: A Page Template for terms of use.
 *
 * To generate specific templates for your pages you can use:
 * /mytheme/views/page-mypage.twig
 * (which will still route through this PHP file)
 * OR
 * /mytheme/page-mypage.php
 * (in which case you'll want to duplicate this file and save to the above path)
 *
 * Methods for TimberHelper can be found in the /lib sub-directory
 *
 * @package  WordPress
 * @subpackage  Timber
 * @since    Timber 0.1
 */

$site_logo          = get_theme_mod( 'site_logo' );
$footer_logo        = get_theme_mod( 'footer_logo' );
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

$context                            = Timber::get_context();
$post                               = new TimberPost();
$context['post']                    = $post;
$context['disclaimer']              = get_field( 'page_disclaimer' );
$context['company_name']            = get_theme_mod( 'company_name' );
$context['company_address']         = get_theme_mod( 'company_address' );
$context['site_logo']               = $site_logo;
$context['footer_logo']             = $footer_logo;
$context['show_footer_social']      = $show_footer_social;
$context['social_networks']         = $social_networks;
$context['footer_copyrights']       = $footer_copyrights;
$context['footer_main_widget_area'] = Timber::get_widgets( 'footer_main_widget_area' );


Timber::render( array( 'page-' . $post->post_name . '.twig', 'page.twig' ), $context );
