<?php
include 'wpts/wp_theme_settings.php';

/*
 * Enqueue parent css
 */
add_action( 'wp_enqueue_scripts', 'my_theme_enqueue_styles' );
function my_theme_enqueue_styles() {
	wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
}


/*
 * Enqueue wpts files to admin
 */
add_action('admin_enqueue_scripts', 'wp_theme_settings_add_stylesheet');
function wp_theme_settings_add_stylesheet(){
	wp_enqueue_style('wp_theme_settings', get_template_directory_uri().'-child/wpts/wp_theme_settings.css');
	wp_register_script('wp_theme_settings',get_template_directory_uri() . '-child/wpts/wp_theme_settings.js', array('jquery'));
	wp_enqueue_script('wp_theme_settings');
}

/*
 * WPTS
 */

$wpts_general_fields = [
	[
	    'type' => 'text', 
	    'label' => 'Example Input',
	    'name' => 'example_input' ,
		'description' => 'Example description',
	],
	[
		'type' => 'fa', 
		'label' => 'Example Icons', 
		'name' => 'example_icons' ,
		'description' => 'Example description',
	],
];

$wpts = new wp_theme_settings(
  array(
    'general' => array('description' => 'A custom WordPress class for creating theme settings page'),
    'settingsID' => 'wp_theme_settings',
    'settingFields' => array('wp_theme_settings_title'), 
    'tabs' => array(
      'general' => array('text' => 'General', 'dashicon' => 'dashicons-admin-generic' , 'tabFields' => $wpts_general_fields),
      'buttons' => array('text' => 'Buttons')
     ),
  )
);
?>