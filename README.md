# wp_theme_tabs
**A custom WordPress tab class for creating simple theme settings (Design looks identical to WP About page)**

![Extras](http://i.imgur.com/YTjQZCe.png)

NOTE
----
This is a utility class intended to create Theme settings page.

Installation
------------
Place **wp_theme_tabs.php**, **nav-rtabs.js** and **nav-rtabs.css**  in your WordPress theme folder `/wp-content/your-theme/`

Open your WordPress themes **functions.php** file  `/wp-content/your-theme/functions.php` add the following code:

```php
include 'wp_theme_tabs.php';
```

Add both CSS & JS file to Wordpress **admin_enqueue_scripts**

```php
add_action('admin_enqueue_scripts', 'wp_theme_tabs_add_stylesheet');
function wp_theme_tabs_add_stylesheet(){

  wp_enqueue_style('nav-rtabs', get_template_directory_uri().'css/nav-rtabs.css');
  wp_register_script('nav-rtabs',get_template_directory_uri() . 'js/nav-rtabs.js', array('jquery'));
  wp_enqueue_script('nav-rtabs');
}
```

Usage
------------
Create a hook to admin_menu like below to your theme **functions.php** file

```php
add_action('admin_menu', 'extra_menus');
function extra_menus() {
  add_theme_page('Theme Settings', 'Theme Settings', 'edit_theme_options', 'ThemeCP', 'ThemeCP');
}
```

and then name of the function we declared in the add_theme_page (ThemeCP)
```php
function ThemeCP(){

  $wp_theme_tabs = new wp_theme_tabs(
    array(
      'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit',
      'tabs' => array(
        'credits' => 'Credits'
        )
    )
  );

}
```

To add content to each tab declare **add_action('key' , 'function name')** (each add_action key will start with **nav_tab_**)
```php
add_action('nav_tab_**credits**' , 'credits');
function credits(){
  echo '<p class="about-description">WordPress is created by a worldwide team of passionate individuals.</p>';
}
```

wp_theme_tabs Arguments
------------
* First ordered list item
* Another item
⋅⋅⋅You can have properly indented paragraphs within list items. Notice the blank line above, and the leading spaces (at least one, but we'll use three here to also align the raw Markdown).
* Actual numbers don't matter, just that it's a number
* And another item.


Changelog
------------
**1.0**
+ First release.
