<?php
/**
 * Sample implementation of the Custom Header feature.
 *
 * You can add an optional custom header image to header.php like so ...
 *
	<?php if ( get_header_image() ) : ?>
	<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
		<img src="<?php header_image(); ?>" width="<?php echo esc_attr( get_custom_header()->width ); ?>" height="<?php echo esc_attr( get_custom_header()->height ); ?>" alt="">
	</a>
	<?php endif; // End header image check. ?>
 *
 * @link http://codex.wordpress.org/Custom_Headers
 *
 * @package Reyl Lite
 */

/**
 * Set up the WordPress core custom header feature.
 *
 * @uses reyl_lite_header_style()
 */
function reyl_lite_custom_header_setup() {
	add_theme_support( 'custom-header', apply_filters( 'reyl_lite_custom_header_args', array(
		'default-image'          => '',
		'default-text-color'     => '383838',
		'width'                  => 1905,
		'height'                 => 102,
		'flex-height'            => true,
		'flex-width'            => true,
		'wp-head-callback'       => 'reyl_lite_header_style',
	) ) );
}
add_action( 'after_setup_theme', 'reyl_lite_custom_header_setup' );

if ( ! function_exists( 'reyl_lite_header_style' ) ) :
/**
 * Styles the header image and text displayed on the blog
 *
 * @see reyl_lite_custom_header_setup().
 */
function reyl_lite_header_style() {
	$header_text_color = get_header_textcolor();

	// If no custom options for text are set, let's bail
	// get_header_textcolor() options: HEADER_TEXTCOLOR is default, hide text (returns 'blank') or any hex value.
	if ( HEADER_TEXTCOLOR === $header_text_color ) {
		return;
	}

	// If we get this far, we have custom styles. Let's do this.
	?>
	<style type="text/css">
	<?php
		// Has the text been hidden?
		if ( ! display_header_text() ) :
	?>
		.logo_container .ql_logo, .logo_container .site-description{
			position: absolute;
			clip: rect(1px, 1px, 1px, 1px);
		}
	<?php
		// If the user has set a custom color for the text use that.
		else :
	?>
		.logo_container .ql_logo, .logo_container .site-description {
			color: #<?php echo esc_attr( $header_text_color ); ?>;
		}
		#primary-menu a {
			color: #<?php echo esc_attr( $header_text_color ); ?>;
			opacity: 0.8;
		}
	<?php endif; ?>
	</style>
	<?php
}
endif; // reyl_lite_header_style
