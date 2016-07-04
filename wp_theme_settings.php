<?php 
/**
 * Class Name: wp_theme_tabs
 * GitHub URI: github.com/mattiasghodsian/wp_theme_settings
 * Description: A custom WordPress class for creating theme settings page (Design looks identical to WP About page)
 * Version: 2.0.0 
 * Author: Mattias Ghodsian
 * Author URI: Nexxoz.com
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */
class wp_theme_settings{

	private $tabs;
	private $theme;
	private $general;
	private $badge;
	private $settingsID;
	private $settingFields;

	function __construct($args){

		// Needs an array of args to instanciate.
		if (!is_array($args))
            return;

        // Set variables
		$this->tabs = (array_key_exists('tabs', $args)) ? $args['tabs'] : array();
		$this->theme = wp_get_theme();
		$this->general = (array_key_exists('general', $args)) ? $args['general'] : array();
		$this->badge = (array_key_exists('badge', $args)) ? $args['badge'] : '';
		$this->settingsID = (array_key_exists('settingsID', $args)) ? $this->keyEntity($args['settingsID']).'-settings-group' : '';
		$this->settingFields = (array_key_exists('settingFields', $args)) ? $args['settingFields'] : '';

		// call register theme_settings function
		add_action('admin_init', array($this,'theme_settings'));
        // call register menu
		add_action('admin_menu', array($this,'menu'));
	}

	public function menu() {
		$page_title = (array_key_exists('title', $this->general) ? $this->general['title'] : 'Theme Settings');
		$menu_title = (array_key_exists('menu_title', $this->general) ? $this->general['menu_title'] : 'Theme Settings');
		$menu_slug =  (array_key_exists('menu_slug', $this->general) ? $this->general['menu_slug'] : 'wp-theme-settings');
		add_theme_page($page_title, $menu_title, 'edit_theme_options', $menu_slug, array($this, 'tabs'));
	}

	/*
	* @ Generate Display.
	*/
	public function tabs(){
		echo '<div class="wrap about-wrap">';
		$this->navHeader();
		$this->navTabs();
		echo '</div>';
	}

	/*
	* @ Generate tabs
	*/
	private function navTabs(){
		$i = 0;
		echo '<h2 class="nav-tab-wrapper nav-rtab-wrapper wp-clearfix">';
			foreach ($this->tabs as $key => $tab) {
				echo '<a href="#'.$this->keyEntity($key).'" class="nav-tab '.($i == 0 ? 'nav-tab-active' : '').' ">'.(array_key_exists('dashicon', $tab) ? '<span class="dashicons '.$tab['dashicon'].'"></span>' : '').$tab['text'].'</a>';
				$i++;
			}
		echo '</h2>';

		echo '<form method="post" action="options.php">';
		settings_fields($this->settingsID);
		echo '<div class="nav-rtabs">';
		
			foreach ($this->tabs as $key => $tab) {
				echo '<div class="nav-rtab-holder" id="'.$this->keyEntity($key).'">';
				do_action('wpts_tab_'.$this->keyEntity($key));
				echo '</div>';
			}
	    
		submit_button(); 
		echo '</div></form>';
	}
 
	/*
	* @ Tab Head
	*/
	private function navHeader(){

		if (array_key_exists('title', $this->general)) {
			echo '<h1>'.ucfirst($this->general['title']).'</h1>';
		}else{
			echo '<h1>'.ucfirst($this->theme->get( 'Name' )).' Theme Settings</h1>';
		}

		if (array_key_exists('description', $this->general)) {
			echo '<div class="about-text">'.$this->general['description'].'</div>';
		}

		if (!empty($this->badge)) {
			echo '<div class="wp-badge wp-rbadge" style="background: url('.$this->badge['bg-image'].') center 25px no-repeat '.(array_key_exists('bg-color', $this->badge) ? $this->badge['bg-color'] : '#0073AA').'; ">';
			if (array_key_exists('version', $this->badge) && $this->badge['version'] == false) {
				// nothing
			}else{
				echo 'Version '.$this->theme->get('Version');
			}
			echo '</div>';
		}
	} 

	/*
	* @ Remove special chars
	*/
	private function keyEntity($key){
		$key = preg_replace('/[^a-zA-Z0-9\']/', '_', $key);
		return rtrim($key, '_');
	}

	/*
	* @ Register Settings
	*/
    public function theme_settings(){
    	foreach ($this->settingFields as $value) {
    		register_setting($this->settingsID, $value, array( $this, 'sanitize' ));
    	}
	}

	/*
	* @ Sanitize inputs
	*/
	public function sanitize($input){
		return sanitize_text_field($input);
    }

}
?>