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

$site_logo         = get_theme_mod( 'site_logo' );
$footer_logo       = get_theme_mod( 'footer_logo' );
$footer_copyrights = get_theme_mod( 'pogo_footer_copyrights' );


$context                      = Timber::context();
$context['posts']             = new Timber\PostQuery();
$context['site_logo']         = $site_logo;
$context['footer_logo']       = $footer_logo;
$context['footer_copyrights'] = $footer_copyrights;
$templates                    = array( 'index.twig' );

if ( is_home() ) {
	array_unshift( $templates, 'front-page.twig', 'home.twig' );
}

Timber::render( $templates, $context );
