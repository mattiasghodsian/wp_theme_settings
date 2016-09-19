# wp_theme_settings
**A custom WordPress class for creating theme settings page (Design looks identical to WP About page)**

![Extras](http://i.imgur.com/nK4XI4M.png)

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
  'buttons' => array('text' => 'Buttons'),
  'tabFields' > array()
)
```
dashicon & tabFields is optional


**Badge** _(optional)_
```php
'badge' => array(
  'bg-image' => get_template_directory_uri().'/logo.png', // required
  'bg-color' => '#1d6b8e', // optional
  'version' => false // optional
),
```
version is true as default


tabFields (text,color,select,radio,checkbox)
------------
Text box
```php
array(
    'type' => 'text', 
    'label' => 'Label name', 
    'name' => 'wpts_title' ,
    'class' => 'test-class test-class-2',
    'description' => 'This is a text input.'
  )
```
Color Picker
```php
array(
    'type' => 'color', 
    'label' => 'Text Color', 
    'name' => 'wpts_text_color',
  )
```
Select (Dropdown)
```php
array(
    'type' => 'select', 
    'label' => 'Travel', 
    'name' => 'wpts_travel' ,
    'options' => array('car' => 'Car', 'airplane' => 'Airplane'),
  )
```
Radio option
```php
array(
    'type' => 'radio', 
    'label' => 'Gender', 
    'name' => 'wpts_gender' ,
    'options' => array('male' => 'Male', 'female' => 'Female'),
  )
```
Checkbox
```php
array(
    'type' => 'checkbox', 
    'name' => 'wpts_checkbox',
    'text' => 'Anyone can view',
    'value' => 1,
  ),
```

Toggle
```php
array(
  'type' => 'toggle', 
  'label' => 'Show timestamp', 
  'name' => 'wpts_toggle',
  'description' => 'Toggle On/Off',
  'value' => 1,
),
```

Extra
------------
WP Color Picker
```html
<input type="text" value="" class="wpts_color_field" />
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
    'settingFields' => array('wpts_test_title'), 
    'tabs' => array(
      'general' => array(
        'text' => 'General', 'dashicon' => 'dashicons-admin-generic' ,
        'tabFields' => array(
            array(
              'type' => 'text', 
              'label' => 'Title', 
              'name' => 'wpts_title' ,
              'class' => 'test-class test-class-2',
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
    <input type="text" name="wpts_test_title" value="<?php echo esc_attr( get_option('wpts_test_title') ); ?>" />
    <p class="description">This is a text input.</p>
  </td>
</tr>

<?php
}
```

Changelog
------------
**2.3.2**
+ Toggle Switch for tabFields.

**2.3.1**
+ color field class renamed from wp_theme_settings_color_field to wpts_color_field.
+ tabFields (Auto build fields).
+ Action hook added for table wpts_tab_NameHere_table

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
