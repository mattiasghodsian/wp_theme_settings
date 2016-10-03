<?php 
/**
 * Class Name: wp_theme_settings
 * GitHub URI: http://github.com/mattiasghodsian/wp_theme_settings
 * Description: A custom WordPress class for creating theme settings page (Design looks identical to WP About page)
 * Version: 2.3.4
 * Author: Mattias Ghodsian
 * Author URI: http://www.nexxoz.com
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

class wp_theme_settings{

	private $tabs;
	private $theme;
	private $general;
	private $badge;
	private $settingsID;
	private $settingFields;

	function __construct($args){
		/*
		 * @ Needs an array of args to instanciate.
		 */
		if (!is_array($args))
            return;

        /*
		 * @ Set variables
		 */
		$this->tabs = (array_key_exists('tabs', $args)) ? $args['tabs'] : array();
		$this->theme = wp_get_theme();
		$this->general = (array_key_exists('general', $args)) ? $args['general'] : array();
		$this->badge = (array_key_exists('badge', $args)) ? $args['badge'] : '';
		$this->settingsID = (array_key_exists('settingsID', $args)) ? $this->keyEntity($args['settingsID']).'-settings-group' : '';
		$this->settingFields = (array_key_exists('settingFields', $args)) ? $args['settingFields'] : array();

		/*
		 * @ Add tabfields to settingsfield
		 */
		foreach ($this->tabs as $key => $data) {
			if (array_key_exists('tabFields', $data)) {			
				foreach ($data["tabFields"] as $key => $value) {
					array_push($this->settingFields, $value['name']);
				}
			}
			
		}

		/*
		 * @ call register theme_settings function
		 */
		add_action('admin_init', array($this,'theme_settings'));
        /*
		 * @ call register menu
		 */
		add_action('admin_menu', array($this,'menu'));
		/*
		 * @ call js & css
		 */
		add_action('admin_enqueue_scripts', array($this, 'wp_theme_settings_js_css'));
		/*
		 * @ call option function
		 */
		add_filter( 'wpts_option', array($this, 'wpts_option'));
	}

	/*
	 * @ jQuery & Css
	 */
	public function wp_theme_settings_js_css(){
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script( 'wp-color-picker');
		wp_enqueue_style('fontawesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css');
		wp_register_script('js-yaml','https://cdnjs.cloudflare.com/ajax/libs/js-yaml/3.6.1/js-yaml.js', array('jquery'));
  		wp_enqueue_script('js-yaml');
	}
	/*
	 * @ Register theme menu.
	 */
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
	 * @ Build table for tabs
	 */
	private function tab_container($array, $parent){ 

		echo '<table class="form-table"><tbody>';

		foreach ($array["tabFields"] as $key => $data) {

			echo '<tr>';

				echo '<th scope="row">';
					if (array_key_exists('label', $data)) {
						echo '<label>'.$data['label'].'</label>';
					}
				echo '</th>';

				echo '<td>';
					echo $this->binput($data);
					if (array_key_exists('description', $data)) {
						echo '<p class="description">'.$data['description'].'</p>';
					}
				echo '</td>';

			echo '</tr>';

			array_push($this->settingFields, $data['name']);
		}

		do_action('wpts_tab_'.$parent.'_table');
		echo '</tbody></table>';

	}
	/*
	 * @ Build inputs
	 */
	private function binput($array){ 
		if (array_key_exists('class', $array)) {
			$html_class = $array['class'];
		}else{
			$html_class = '';
		}

		switch ($array['type']) {

			// Build text
			case 'text':
				echo '<input type="text" class="'.$html_class.'" name="'.$array['name'].'" value="'.$this->wpts_option($array['name']).'" />';
				break;
			// Build fontawesome selector 
			case 'fa':
				echo '<input type="text" class="wpts_fa_field '.$html_class.'" name="'.$array['name'].'" value="'.$this->wpts_option($array['name']).'" />';
				break;
			// Build Color
			case 'color':
				echo '<input type="text" class="'.$html_class.' wpts_color_field" name="'.$array['name'].'" value="'.$this->wpts_option($array['name']).'" />';
				break;
			// Build Select
			case 'select':
				echo '<select name="'.$array['name'].'" class="'.$html_class.'">';
				
					foreach ($array['options'] as $key => $value) {
						echo '<option value="'.$this->keyEntity($key).'" '.($this->wpts_option($array['name']) == $key ? 'selected' : '').'>'.$value.'</option>';
					}
				echo '</select>';
				break;
			// Build Radio
			case 'radio':
				foreach ($array['options'] as $key => $value) {
					echo '
						<label>
							<input type="radio" name="'.$array['name'].'" value="'.$this->keyEntity($key).'" '.($this->wpts_option($array['name']) == $key ? 'checked="checked" ' : '').'> 
							<span>'.$value.'</span>
						</label>';
				}
				break;
			// Build Checkbox
			case 'checkbox': 
					echo '
						<fieldset><label><input name="'.$array['name'].'" type="checkbox" value="'.$array['value'].'" '.($this->wpts_option($array['name']) ? 'checked="checked" ' : '').'>'.$array['text'].'</label>
						</fieldset>';
				break;
			// Build Toggle Switch
			case 'toggle': 
					echo '
					<label class="switch">
				      <input type="checkbox" name="'.$array['name'].'" value="'.$array['value'].'" '.($this->wpts_option($array['name']) ? 'checked="checked" ' : '').'>
				      <div class="slider round"></div>
				    </label>
					';
				break;
			// default return false
			default:
				return false;
				break;
		}
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

		echo '<form method="post" class="nav-rtab-form" action="options.php">';
		settings_fields($this->settingsID);
		echo '<div class="nav-rtabs">';
		
			foreach ($this->tabs as $key => $tab) {
				echo '<div class="nav-rtab-holder" id="'.$this->keyEntity($key).'">';

				$this->tab_container($tab, $this->keyEntity($key));

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

			if (array_key_exists('bg-image', $this->badge)) {
				echo '<div class="wp-badge wp-rbadge" style="background: url('.$this->badge['bg-image'].') center 25px no-repeat '.(array_key_exists('bg-color', $this->badge) ? $this->badge['bg-color'] : '#0073AA').'; ">';
			}else{
				echo '<div class="wp-badge wp-rbadge" style="background: url(http://i.imgur.com/AvANSYy.png) center 25px no-repeat '.(array_key_exists('bg-color', $this->badge) ? $this->badge['bg-color'] : '#0073AA').'; ">';
			}

			if (array_key_exists('version', $this->badge) && $this->badge['version'] == false) {
				// do nothing
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
    		register_setting($this->settingsID, $value, array($this, 'sanitize'));
    	}
	}
	/*
	 * @ Sanitize inputs
	 */
	public function sanitize($input){
		return sanitize_text_field($input);
    }
    /*
	 * @ Get Option value
	 */
	public function wpts_option($key){
		return esc_attr( get_option($key) );
	}
}
?>