# wp_theme_settings
**A custom WordPress class for creating theme settings page (Design looks identical to WP About page)**

![Extras](http://i.imgur.com/g17MJIo.png)

NOTE
----
This is a utility class intended to create a theme settings page. Compatible Wordpress 4.5+

Installation
------------
Place **wp_theme_settings.php**, **wp_theme_settings.js** and **wp_theme_settings.css**  in your WordPress theme folder `/wp-content/your-theme/`

Open your WordPress themes **functions.php** file  `/wp-content/your-theme/functions.php` add the following code:

```php
include 'wp_theme_settings.php';
```

Add both CSS & JS file to Wordpress with **admin_enqueue_scripts**

```php
add_action('admin_enqueue_scripts', 'wp_theme_settings_add_stylesheet');
function wp_theme_settings_add_stylesheet(){
  wp_enqueue_style('wp_theme_settings', get_template_directory_uri().'/wp_theme_settings.css');
  wp_register_script('wp_theme_settings',get_template_directory_uri() . '/wp_theme_settings.js', array('jquery'));
  wp_enqueue_script('wp_theme_settings');
}
```

Read full [docs](wpts.nexxoz.com) here

Full example
------------
```php
include 'wp_theme_settings.php';

$wpts = new wp_theme_settings(
  array(
    'general' => array('description' => 'A custom WordPress class for creating theme settings page'),
    'settingsID' => 'wp_theme_settings',
    'settingFields' => array('wp_theme_settings_title'), 
    'tabs' => array(
      'general' => array('text' => 'General', 'dashicon' => 'dashicons-admin-generic' ),
      'buttons' => array('text' => 'Buttons')
      ),
  )
);

add_action('wpts_tab_general' , 'general');
function general(){
?>
<p><label>Title</label></p>
<input type="text" name="wp_theme_settings_title" value="<?php echo esc_attr( get_option('wp_theme_settings_title') ); ?>" />
<?php

}

add_action('admin_enqueue_scripts', 'wp_theme_settings_add_stylesheet');
function wp_theme_settings_add_stylesheet(){
  wp_enqueue_style('wp_theme_settings', get_template_directory_uri().'/wp_theme_settings.css');
  wp_register_script('wp_theme_settings',get_template_directory_uri() . '/wp_theme_settings.js', array('jquery'));
  wp_enqueue_script('wp_theme_settings');
}
```

Example with tabFields
------------
```php
include 'wp_theme_settings.php';

$wpts = new wp_theme_settings(
  array(
    'general' => array('description' => 'A custom WordPress class for creating theme settings page'),
    'settingsID' => 'wp_theme_settings',
    'tabs' => array(
      'general' => array(
        'text' => 'General', 'dashicon' => 'dashicons-admin-generic' ,
        'tabFields' => array(
            array(
              'type' => 'text', 
              'label' => 'Title', 
              'name' => 'wpts_title' ,
              'description' => 'This is a text input.'
            ),
            array(
              'type' => 'color', 
              'label' => 'Title Color', 
              'name' => 'wpts_text_color',
            ),
            array(
              'type' => 'toggle', 
              'label' => 'Show timestamp', 
              'name' => 'wpts_toggle',
              'description' => 'Toggle On/Off',
              'value' => 1,
            )
        )
      )
    ),
  )
);

add_action('wpts_tab_general_table' , 'general_table');
function general_table(){
?>
<tr>
  <th scope="row">
  <label>Extra</label>
  </th>
  <td>
    <input type="text" name="wpts_test_title" value="<?php echo apply_filters('wpts_option', 'wpts_test_title'); ?>" />
    <p class="description">This is a text input.</p> 
  </td>  
</tr>

<?php
}

```