# wp_theme_settings
**A custom WordPress class for creating theme settings page (Design looks identical to WP About page)**

![Extras](http://i.imgur.com/02BYGw2.png)

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

Usage
------------
Call wp_theme_settings class in your theme **functions.php** file like below

```php
$theme_settings = new wp_theme_settings(
  array(
    'general' => array('description' => 'A custom WordPress class for creating theme settings page')
    'settingsID' => 'wp_theme_settings',
    'settingFields' => array('wp_theme_settings_title'), 
    'tabs' => array(
      'general' => array('text' => 'General', 'dashicon' => 'dashicons-admin-generic' ),
      'buttons' => array('text' => 'Buttons')
      ),
  )
);
```


To add content to each tab declare **add_action('key' , 'function name')**
```php
add_action('wpts_tab_general' , 'general');
function general(){
?>
<p><label>Title</label></p>
<input type="text" name="wp_theme_settings_title" value="<?php echo esc_attr( get_option('wp_theme_settings_title') ); ?>" />
<?php
}
```
Each add_action key will start with **wpts_tab_**

All wp_theme_settings options
------------

**General** (contains 4 keys) _(optional)_
```php
'title' => 'Theme Settings',
'description' => 'A custom WordPress class for creating theme settings page',
'menu_title' => 'Theme Settings',
'menu_slug' => 'theme-settings'
```

**settingsID** (A settings group name) _(required)_
```php
'settingsID' => 'my-theme-settings'
```

**settingFields** (Array with names of options) _(required)_
```php
'settingFields' => array('option_name','option_name_two')
```

**tabs** (Array with tabs) _(required)_
```php
'tabs' => array(
  'general' => array('text' => 'General', 'dashicon' => 'dashicons-admin-generic' ),
  'buttons' => array('text' => 'Buttons')
)
```
dashicon is optional

**Badge** _(optional)_
```php
'badge' => array(
  'bg-image' => get_template_directory_uri().'/logo.png', // required
  'bg-color' => '#1d6b8e', // optional
  'version' => false // optional
),
```
version is true as default

Extra
------------
WP Color Picker
```html
<input type="text" value="" class="wp_theme_settings_color_field" />
```

FontAwesome Array
```php
<select>
  <?php 
  foreach ($class_name->FontAwesomeArray() as $key => $value) {
    echo '<option value="'.$key.'">'.$value.'</option>';
  }
  ?>
<select>
```

Full example
------------
```php
include 'wp_theme_settings.php';

$theme_settings = new wp_theme_settings(
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

Changelog
------------
**2.1.3**
+ scrollTop after tab clicked.

**2.1.2**
+ wpColorPicker added (Add wp_theme_settings_color_field class to input text).
+ FontAwesomeArray 4.6.3 added.
+ FontAwesome 4.6.3 css (Back End).

**2.1.0**
+ Style for input,select.
+ Return to selected tab after save.

**2.0**
+ Class was completly re-written & more options added.

**1.1**
+ checkTabs() function replaced with keyEntity().
+ WP Dashicons added for tabs.
+ Reworked tab array.

**1.0**
+ Release.
