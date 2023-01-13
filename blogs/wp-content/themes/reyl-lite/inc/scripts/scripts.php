<?php

	wp_enqueue_script( 'jquery' );
	
	//HTML5 Shiv ==============================================
	wp_enqueue_script( 'reyl_lite_html5shiv', get_template_directory_uri() . '/js/html5shiv.js', array(), '3.7.3', true );
	//=================================================================

	//Modernizr Plugin ================================================
	wp_enqueue_script( 'reyl_lite_modernizr', get_template_directory_uri() . '/js/modernizr.custom.67069.js', '2.8.3', true );
	//=================================================================
	
	//Pace  ===========================================================
	wp_enqueue_script( 'reyl_lite_pace', get_template_directory_uri() . '/js/pace.js', array(), '0.2.0', true);
	//=================================================================
	
	//Bootstrap JS ========================================
	wp_enqueue_script( 'reyl_lite_bootstrap', get_template_directory_uri() . '/js/bootstrap.js', array(), '3.3.5', true);
	//=================================================================
	
	//Comment Reply ===================================================
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
	//=================================================================


	
	//Customs Scripts =================================================
	wp_enqueue_script( 'reyl_lite_theme-custom', get_template_directory_uri() . '/js/script.js', array( 'jquery', 'reyl_lite_bootstrap' ), '1.0', true );
	//=================================================================

?>