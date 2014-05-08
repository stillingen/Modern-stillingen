<?php
//* Start the engine
include_once( get_template_directory() . '/lib/init.php' );

//* Set Localization (do not remove)
load_child_theme_textdomain( 'mpp', apply_filters( 'child_theme_textdomain', get_stylesheet_directory() . '/languages', 'mpp' ) );


//* Child theme (do not remove)
define( 'CHILD_THEME_NAME', __( 'Modern Stillingen', 'MS' ) );
define( 'CHILD_THEME_URL', 'http://my.studiopress.com/themes/modern-portfolio/' );
define( 'CHILD_THEME_VERSION', '2.0.0' );

//* Add HTML5 markup structure
add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list' ) );

//* Add viewport meta tag for mobile browsers
add_theme_support( 'genesis-responsive-viewport' );

//* Enqueue Lato and Merriweather Google fonts
add_action( 'wp_enqueue_scripts', 'mpp_google_fonts' );
function mpp_google_fonts() {

	wp_enqueue_style( 'google-fonts', '//fonts.googleapis.com/css?family=Lato:300,400|Merriweather:400,300', array(), CHILD_THEME_VERSION );
	
}

//* Enqueue Responsive Menu Script
add_action( 'wp_enqueue_scripts', 'mpp_enqueue_responsive_script' );
function mpp_enqueue_responsive_script() {

	wp_enqueue_script( 'mpp-responsive-menu', get_bloginfo( 'stylesheet_directory' ) . '/js/responsive-menu.js', array( 'jquery' ), '1.0.0' );

}

//* Add new image sizes
add_image_size( 'blog', 340, 140, TRUE );
add_image_size( 'portfolio', 340, 230, TRUE );

//* Add support for custom header
add_theme_support( 'custom-header', array(
	'header_image'    => '',
	'header-selector' => '.site-title a',
	'header-text'     => false,
	'height'          => 90,
	'width'           => 300,
) );

//* Add support for additional color style options
add_theme_support( 'genesis-style-selector', array(
	'modern-portfolio-pro-blue'   => __( 'Modern Portfolio Pro Blue', 'mpp' ),
	'modern-portfolio-pro-orange' => __( 'Modern Portfolio Pro Orange', 'mpp' ),
	'modern-portfolio-pro-red'    => __( 'Modern Portfolio Pro Red', 'mpp' ),
	'modern-portfolio-pro-purple' => __( 'Modern Portfolio Pro Purple', 'mpp' ),
) );

//* Add support for 3-column footer widgets
add_theme_support( 'genesis-footer-widgets', 3 );

//* Unregister layout settings
genesis_unregister_layout( 'content-sidebar-sidebar' );
genesis_unregister_layout( 'sidebar-content-sidebar' );
genesis_unregister_layout( 'sidebar-sidebar-content' );

//* Unregister secondary sidebar
unregister_sidebar( 'sidebar-alt' );

//* Add metabox for site initial option
add_action( 'genesis_theme_settings_metaboxes', 'mpp_theme_settings_metaboxes', 10, 1 );
function mpp_theme_settings_metaboxes( $pagehook ) {

    add_meta_box( 'mpp-custom-initial', __( 'Modern Portfolio - Site initial', 'mpp' ), 'mpp_custom_initial_metabox', $pagehook, 'main', 'high' );

}

//* Content for the site initial metabox
function mpp_custom_initial_metabox() {

    $val = ( $opt = genesis_get_option( 'mpp_custom_initial' ) ) ? $opt[0] : '';

    printf( '<p><label for="%s[%s]" />' . __( 'Enter custom site initial:', 'mpp') . '<br />', GENESIS_SETTINGS_FIELD, 'mpp_custom_initial' );
    printf( '<input type="text" name="%1$s[%2$s]" id="%1$s[%1$s]" size="6" value="%3$s" /></p>', GENESIS_SETTINGS_FIELD, 'mpp_custom_initial', $val );
    printf( '<p><span class="description">' . __( 'This will be displayed beside the site title and is limited to 1 character', 'mpp') . '</span></p>' );

}

//* Add custom site initial CSS
add_action( 'wp_enqueue_scripts', 'modern_portfolio_set_icon' );
function modern_portfolio_set_icon() {

    $handle  = defined( 'CHILD_THEME_NAME' ) && CHILD_THEME_NAME ? sanitize_title_with_dashes( CHILD_THEME_NAME ) : 'child-theme';

    $icon = genesis_get_option( 'mpp_custom_initial' );

    if( empty( $icon ) || get_header_image() )
        return;

    $css = sprintf( '.site-title a::before{ content: \'%s\'; }', $icon[0] );

    wp_add_inline_style( $handle, $css );

}

//* Hook after post widget after the entry content
add_action( 'genesis_after_entry', 'mpp_after_entry', 5 );
function mpp_after_entry() {

	if ( is_singular( 'post' ) )
		genesis_widget_area( 'after-entry', array(
			'before' => '<div class="after-entry widget-area">',
			'after'  => '</div>',
		) );

}

//* Modify the size of the Gravatar in author box
add_filter( 'genesis_author_box_gravatar_size', 'mpp_author_box_gravatar_size' );
function mpp_author_box_gravatar_size( $size ) {

	return 80;
	
}

//* Remove comment form allowed tags
add_filter( 'comment_form_defaults', 'mpp_remove_comment_form_allowed_tags' );
function mpp_remove_comment_form_allowed_tags( $defaults ) {
	
	$defaults['comment_notes_after'] = '';
	return $defaults;

}

//* Register widget areas
genesis_register_sidebar( array(
	'id'          => 'home-about',
	'name'        => __( 'Home - About','mpp' ),
	'description' => __( 'This is the about section of the homepage.','mpp' ),
) );
genesis_register_sidebar( array(
	'id'          => 'home-portfolio',
	'name'        => __( 'Home - Portfolio','mpp' ),
	'description' => __( 'This is the portfolio section of the homepage.','mpp' ),
) );
genesis_register_sidebar( array(
	'id'          => 'home-services',
	'name'        => __( 'Home - Services','mpp' ),
	'description' => __( 'This is the Services section of the homepage.','mpp' ),
) );
genesis_register_sidebar( array(
	'id'          => 'home-blog',
	'name'        => __( 'Home - Blog','mpp' ),
	'description' => __( 'This is the Blog section of the homepage.','mpp' ),
) );
genesis_register_sidebar( array(
	'id'          => 'after-entry',
	'name'        => __( 'After Entry', 'mpp' ),
	'description' => __( 'This is the after entry widget area.', 'mpp' ),
) );
