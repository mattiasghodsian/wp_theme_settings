# 1. Installation

Download [WPTS](https://github.com/mattiasghodsian/wp_theme_settings/archive/master.zip) and place all files in your theme/plugin directory `/wp-content/themes/your-theme/`, `/wp-content/plugins/your-plugin/`

Open your WordPress themes functions.php or plugin main file and add the following code

```php
require_once('wp_theme_settings.php'); 
```

Add both CSS & JS file to Wordpress with [admin_enqueue_scripts](https://codex.wordpress.org/Plugin_API/Action_Reference/admin_enqueue_scripts) action hook.

```php
add_action('admin_enqueue_scripts', 'wpts_enqueue_scripts');
function wpts_enqueue_scripts(){
    wp_enqueue_style('wp_theme_settings', get_template_directory_uri().'/wp_theme_settings.css');
    wp_register_script('wp_theme_settings',get_template_directory_uri() . '/wp_theme_settings.js', array('jquery'));
    wp_enqueue_script('wp_theme_settings');
}
```

Replace [get_template_directory_uri()](https://developer.wordpress.org/reference/functions/get_template_directory_uri/) function with [plugin_dir_url()](https://codex.wordpress.org/Function_Reference/plugin_dir_url) if you're writing a plugin.
