<?php
/**
 * Serisans Theme Customizer
 *
 * @package Serisans
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function serisans_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';

	if ( isset( $wp_customize->selective_refresh ) ) {
		$wp_customize->selective_refresh->add_partial( 'blogname', array(
			'selector'        => '.site-title a',
			'render_callback' => 'serisans_customize_partial_blogname',
		) );
		$wp_customize->selective_refresh->add_partial( 'blogdescription', array(
			'selector'        => '.site-description',
			'render_callback' => 'serisans_customize_partial_blogdescription',
		) );
	}

    $wp_customize->add_section('sitestyle', array (
		'title' => __('Site Style', 'serisans'),
		'priority' => 130
	));
	
	$wp_customize->add_setting('color_scheme', array(
		'default' => 'dark',		
		'type' => 'theme_mod',
	));
	
	$wp_customize->add_control('color_scheme', array(
		'label' => __('Scheme', 'serisans'),
		'section' => 'sitestyle',
		'type' => 'radio',
		'choices' => array(
			'dark' => 'Dark',
			'light' => 'Light',
            'auto' => 'Auto switch at sunset',	
		),
		'priority' => 1
	));

    $wp_customize->add_setting('font_style', array(
		'default' => 'serif',		
		'type' => 'theme_mod',
	));
	
	$wp_customize->add_control('font_style', array(
		'label' => __('Font Style', 'serisans'),
		'section' => 'sitestyle',
		'type' => 'radio',
		'choices' => array(
			'serif' => 'Serif',
			'sans-serif' => 'Sans Serif',			
		),
		'priority' => 1
	));

    $wp_customize->add_section('custom_footer_text', array (
		'title' => __('Footer Text/Credits', 'serisans'),
		'priority' => 150
	));
	
	$wp_customize->add_setting('footer_text_block', array(
		'default' => __('Serisans Theme · By: <a href="https://www.sidneysacchi.com" target="_blank" title="Web Design &amp; Web Consulting">Sidney Sacchi</a>' , 'serisans'),
		#'sanitize_callback' => 'sanitize_text'		
	));
	
	$wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'custom_footer_text', array( 
		'label' => __( 'Footer Text/Credits', 'serisans' ), 
		'section' => 'custom_footer_text', 
		'settings' => 'footer_text_block', 		
		'type' => 'text'
		))); 
		
	// Sanitize text 
	
		function sanitize_text( $text ) {
		return sanitize_text_field( $text ); 
		}
}
add_action( 'customize_register', 'serisans_customize_register' );

function serisans_customizer_css() {
    if( in_array(get_theme_mod('color_scheme'), array('dark'))) { 
    ?>
    <style type="text/css">
        body, .main-navigation ul ul { background: #333!important; color: #fff; }        
        h1, h2, h3, h4, h5, h6, p, a, ul, ol, li, blockquote { color: #fff; }
        a:hover { color: #ccc; }
        a:visited { color: #999; }
    </style>
    <?php
    } elseif( in_array(get_theme_mod('color_scheme'), array('light'))) {
    ?>
    <style type="text/css">
        body, .main-navigation ul ul { background: #fff; color: #000;}        
        h1, h2, h3, h4, h5, h6, p, a, ul, ol, li, blockquote { color: #000; }
        a:hover { color: #999; }
        a:visited { color: #666; }
    </style>
    <?php
    } else {
        $t = date('H:i:s');
        #if ($t < "18:00:00") {
		// server time is one hour behind so I've set the below timing one hour early. Please amend to your needs.
		if ($t >= "08:00:00" && $t <= "17:00:00") {
    ?>
            <style type="text/css">
                body, .main-navigation ul ul { background: #fff; color: #000;}        
                h1, h2, h3, h4, h5, h6, p, a, ul, ol, li, blockquote { color: #000; }
                a:hover { color: #999; }
                a:visited { color: #666; }
            </style>
    <?php } else { ?>
            <style type="text/css">
                body, .main-navigation ul ul { -webkit-transition: background 1s ease;
-moz-transition: background 1s ease;
-ms-transition: background 1s ease;
-o-transition: background 1s ease;
transition: background 1s ease;
                background: #333!important; color: #fff; }        
                h1, h2, h3, h4, h5, h6, p, a, ul, ol, li, blockquote { color: #fff; }
                a:hover { color: #ccc; }
                a:visited { color: #999; }
            </style>
    <?php }    
    }

    if( in_array(get_theme_mod('font_style'), array('serif'))) { 
    ?>
    <style type="text/css">
        body, h1, h2, h3, h4, h5, h6, p, a, ul, ol, li, blockquote { font-family: georgia, serif; }
    </style>
    <?php
    } else {
    ?>
    <style type="text/css">
        body, h1, h2, h3, h4, h5, h6, p, a, ul, ol, li, blockquote { font-family: verdana, Geneva, sans-serif; }
    </style>
    <?php
    }
}
add_action( 'wp_head', 'serisans_customizer_css' );

/**
 * Render the site title for the selective refresh partial.
 *
 * @return void
 */
function serisans_customize_partial_blogname() {
	bloginfo( 'name' );
}

/**
 * Render the site tagline for the selective refresh partial.
 *
 * @return void
 */
function serisans_customize_partial_blogdescription() {
	bloginfo( 'description' );
}

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function serisans_customize_preview_js() {
	wp_enqueue_script( 'serisans-customizer', get_template_directory_uri() . '/js/customizer.js', array( 'customize-preview' ), '20151215', true );
}
add_action( 'customize_preview_init', 'serisans_customize_preview_js' );
