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

**[General options] Use WPTS for plugins**
```php
'menu_type' => '',  // default: theme | theme, options, submenu
'menu_parent' => '', // only for menu_type: submenu
'capability' => '', // default: manage_options 
```
Read more about [capability](https://codex.wordpress.org/Roles_and_Capabilities#manage_options)

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
  'general' => array(
    'text' => 'General', 
    'dashicon' => 'dashicons-admin-generic', // optional
    'tabFields' => array(), // optional
  ),
  'about' => array(
    'text' => 'About', 
    'dashicon' => 'dashicons-info',// optional
    'tabFields' => array(), // optional
  )
)
```
[Dashicons](https://developer.wordpress.org/resource/dashicons/) & tabFields is optional


**Badge** _(optional)_
```php
'badge' => array(
  'bg-image' => get_template_directory_uri().'/logo.png', // required
  'bg-color' => '#1d6b8e', // optional
  'version' => false // optional
),
```
version is true as default


tabFields
------------
Text box
```php
array(
    'type' => 'text', 
    'label' => '', // Optional
    'name' => '' ,
    'class' => '', // Optional
    'description' => '' // Optional
  )
```
Color Picker
```php
array(
    'type' => 'color', 
    'label' => '', // Optional
    'name' => '',
    'class' => '', // Optional
    'description' => '' // Optional
  )
```
Select (Dropdown)
```php
array(
    'type' => 'select', 
    'label' => '', // Optional
    'name' => '' ,
    'options' => array('key' => 'name'),
    'class' => '', // Optional
    'description' => '' // Optional
  )
```
Radio option
```php
array(
    'type' => 'radio', 
    'label' => '', // Optional
    'name' => '' ,
    'options' => array('key' => 'name'),
    'description' => '' // Optional
  )
```
Checkbox
```php
array(
    'type' => 'checkbox', 
    'label' => '', // Optional
    'name' => '',
    'text' => '',
    'value' => 1,
    'description' => '' // Optional
  ),
```

Toggle
```php
array(
  'type' => 'toggle', 
  'label' => '', // Optional
  'name' => '',
  'value' => 1,
  'description' => '' // Optional
),
```

FontAwesome Selector
```php
array(
  'type' => 'fa', 
  'label' => '',  // Optional
  'name' => '' ,
  'class' => '',  // Optional
  'description' =>  '' // Optional
),
```

Extra
------------
WP Color Picker
```html
<input type="text" value="" class="wpts_color_field" />
```

FontAwesome Selector
```php
<input type="text" name="wpts_fa_field" class="wpts_fa_field" value="" />
```

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

Current version
------------
**2.3.5**
+ Option to use WPTS for plugins.
+ Js changes.
