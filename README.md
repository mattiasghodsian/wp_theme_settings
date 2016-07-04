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

Add both CSS & JS file to Wordpress **admin_enqueue_scripts**

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


To add content to each tab declare **add_action('key' , 'function name')** (each add_action key will start with **wpts_tab_**)
```php
add_action('wpts_tab_general' , 'general');
function general(){
?>
<p><label>Title</label></p>
<input type="text" name="wp_theme_settings_title" value="<?php echo esc_attr( get_option('wp_theme_settings_title') ); ?>" />
<?php
}
```

All wp_theme_tabs options
------------
```php
$theme_settings = new wp_theme_settings(
  array(
    'general' => array(
      'title' => 'Theme Settings', // optional
      'description' => 'A custom WordPress class for creating theme settings page', // optional
      'menu_title' => 'Theme Settings', // optional
      'menu_slug' => 'theme-settings' // optional
      ), // String | new 
    'settingsID' => 'test', // String | new 
    'settingFields' => array('wp_theme_settings_title', 'wp_theme_settings_description'), // array | new 
    'tabs' => array(
      'general' => array('text' => 'General', 'dashicon' => 'dashicons-admin-generic' ),
      'buttons' => array('text' => 'Buttons')
      ),
      'badge' => array(
      'bg-image' => get_template_directory_uri().'/logo.png', 
      'bg-color' => '#1d6b8e',// optional
      // 'version' => false // optional
      ), // optional
  )
);
```

Changelog
------------
**2.0**
+ Class was completly re-written & more options added.

**1.1**
+ checkTabs() function replaced with keyEntity().
+ WP Dashicons added for tabs.
+ Reworked tab array.

**1.0**
+ First release.
