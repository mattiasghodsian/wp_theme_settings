<?php 
/**
 * Class Name: wp_theme_settings
 * GitHub URI: http://github.com/mattiasghodsian/wp_theme_settings
 * Description: A custom WordPress class for creating theme settings page (Design looks identical to WP About page)
 * Version: 2.4.51
 * Author: Mattias Ghodsian
 * Author URI: http://www.nexxoz.com
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

class wp_theme_settings{

	private $tabs;
	private $currversion = "2.4.51";
	private $theme;
	private $general;
	private $badge;
	private $settingsID;
	private $settingFields;
	private $toolbar;
	private $notice;

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
		$this->toolbar = (array_key_exists('toolbar', $args['general'])) ? $args['general']['toolbar'] : array();
		$this->notice = (array_key_exists('notice', $args['general'])) ? $args['general']['notice'] : true;
		/*
		 * @ Add tabfields (tabs & sections) to settingsfield
		 */
		foreach ($this->tabs as $key => $data) {
			if (array_key_exists('tabFields', $data)) {	
				foreach ($data["tabFields"] as $key => $value) {
					array_push($this->settingFields, $value['name']);
				}
			}
			if (array_key_exists('sections', $data)) {	
				foreach ($data["sections"] as $key => $section) {
					if (array_key_exists('tabFields', $section)) {	
						foreach ($section["tabFields"] as $key => $section_tabFields) {
							array_push($this->settingFields, $section_tabFields['name']);
						}
					}
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
		/*
		 * @ call toolbar function
		 */
		if (array_key_exists('toolbar', $args['general']) && $args['general']['toolbar'] != false) {
			add_action('admin_bar_menu', array($this, 'wpts_toolbar'), 999);
		}
		/*
		 * @ check update notice
		if ($this->notice !== false ) {
			if ( $this->get_wpts_git_version() > $this->currversion ) {
				add_action( 'admin_notices', array($this, 'wpts_update_admin_notice') );
			}
		}
		 */
	}

	/*
	 * @ WP Toolbar
	 */
	public function wpts_toolbar($wp_admin_bar) {
		$menu_type 		= (array_key_exists('menu_type', $this->general) ? $this->general['menu_type'] : 'theme');
		$menu_slug 		= (array_key_exists('menu_slug', $this->general) ? $this->general['menu_slug'] : 'wp-theme-settings');
		$menu_parent 	= (array_key_exists('menu_parent', $this->general) ? $this->general['menu_parent'] : '');
		$toolbar_title 	= (array_key_exists('toolbar_title', $this->toolbar) ? $this->toolbar['toolbar_title'] : 'WPTS');
		$toolbar_image 	= (array_key_exists('toolbar_image', $this->toolbar) ? $this->toolbar['toolbar_image'] : 'http://i.imgur.com/3BfjiTf.png');
		$toolbar_href 	= (array_key_exists('toolbar_href', $this->toolbar) ? $this->toolbar['toolbar_href'] : 'https://git.io/vi1Gr');

		if ($toolbar_image) {
			$toolbar_image = '<img src="'.$toolbar_image.'" class="wpts-toolbar-icon" /> ';
		}

		$args = array(
			'id' => 'wpts',
			'title' => $toolbar_image.$toolbar_title, 
			'href' => $toolbar_href, 
			'meta' => array('class' => 'wpts-toolbar'),
		);

		$wp_admin_bar->add_node($args);
		
		switch ($menu_type) {
			case 'submenu':
				$href = home_url().'/wp-admin/'.$menu_parent.'&page='.$menu_slug.'#%%key%%';
				break;
			case 'options':
				$href = home_url().'/wp-admin/options-general.php?page='.$menu_slug.'#%%key%%';
				break;
			default:
				$href = home_url().'/wp-admin/themes.php?page='.$menu_slug.'#%%key%%';
				break;
		}

		foreach ($this->tabs as $key => $tab) {
			$args = array(
				'id' => 'wpts-'.$this->keyEntity($key),
				'title' => $tab['text'], 
				'href' => str_replace( '%%key%%', $this->keyEntity($key), $href ),
				'parent' => 'wpts', 
			);
			$wp_admin_bar->add_node($args);
		}
	}
	

	/*
	 * @ jQuery & Css
	 */
	public function wp_theme_settings_js_css(){
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script( 'wp-color-picker');
		wp_enqueue_style('fontawesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
		wp_register_script('js-yaml','https://cdnjs.cloudflare.com/ajax/libs/js-yaml/3.6.1/js-yaml.js', array('jquery'));
  		wp_enqueue_script('js-yaml');
		wp_enqueue_script('media-upload');
  		wp_enqueue_script('thickbox');
 		wp_enqueue_style('thickbox');
	}
	/*
	 * @ Register theme menu.
	 */
	public function menu() {
		$menu_type 		= (array_key_exists('menu_type', $this->general) ? $this->general['menu_type'] : 'theme');
		$page_title 	= (array_key_exists('title', $this->general) ? $this->general['title'] : 'Theme Settings');
		$menu_title 	= (array_key_exists('menu_title', $this->general) ? $this->general['menu_title'] : 'Theme Settings');
		$menu_slug 		= (array_key_exists('menu_slug', $this->general) ? $this->general['menu_slug'] : 'wp-theme-settings');
		$menu_parent	= (array_key_exists('menu_parent', $this->general) ? $this->general['menu_parent'] : '');
		$capability 	= (array_key_exists('capability', $this->general) ? $this->general['capability'] : 'manage_options');
		switch ($menu_type) {
			case 'submenu':
				add_submenu_page($menu_parent, $page_title, $menu_title, $capability, $menu_slug, array($this, 'tabs') );
				break;
			case 'options':
				add_options_page( $page_title, $menu_title, $capability, $menu_slug, array($this, 'tabs') );
				break;
			default:
				add_theme_page($page_title, $menu_title, $capability, $menu_slug, array($this, 'tabs') );
				break;
		}
	}
	/*
	 * @ Generate Display.
	 */
	public function tabs(){
		echo '<div class="wrap about-wrap wpts-wrap">';
		$this->navHeader();
		$this->navTabs();
		echo '</div>';
	}
	/*
	 * @ Build table for tabs
	 */
	private function tab_container($array, $parent){ 
		echo '<table class="form-table"><tbody>';
		do_action('wpts_tab_'.$parent.'_table_before');
		foreach ($array as $key => $data) {

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

		do_action('wpts_tab_'.$parent.'_table_after');
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
				if (array_key_exists('tooltip', $array)) {
					echo '<div class="wpts-tooltip">!<span class="wpts-tooltiptext wpts-tooltip-right">'.$array['tooltip'].'</span></div>';
				}
				break;
			// Build file
			case 'file':
				if (array_key_exists('preview', $array) && $array['preview'] == true) {
					echo '<img src="'.$this->wpts_option($array['name']).'" class="wpts-file-field-preview">';
				}
				echo '<input type="text" class="'.$html_class.'" id="'.$array['name'].'" name="'.$array['name'].'" value="'.$this->wpts_option($array['name']).'" /><input class="button wpts-file-field" type="button" value="..." />';
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
				if (array_key_exists('tooltip', $array)) {
					echo '<div class="wpts-tooltip">!<span class="wpts-tooltiptext wpts-tooltip-right">'.$array['tooltip'].'</span></div>';
				}
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
				if (array_key_exists('tooltip', $array)) {
					echo '<div class="wpts-tooltip">!<span class="wpts-tooltiptext wpts-tooltip-right">'.$array['tooltip'].'</span></div>';
				}
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
				do_action('wpts_tab_'.$this->keyEntity($key).'_before');

				/** START - Section **/
				if ( array_key_exists('sections', $tab) ) {
					echo '<ul class="wpts-nav-sections">';
					foreach ($tab['sections'] as $section_key => $section) {
						echo '<li><a href="#'.$this->keyEntity($key).'&section='.$section_key.'">'.ucfirst($section['text']).'</a></li>';
					}
					echo '</ul>';

					foreach ($tab['sections'] as $section_key => $section) {
						echo '<div class="wpts-nav-section-holder" id="'.$this->keyEntity($section_key).'">';
						$this->tab_container( $section['tabFields'] , $this->keyEntity($key).'_'.$this->keyEntity($section_key)  , 'sections');
						echo '</div>';
					}
					
				}
				/** END - Section **/

				if ( isset($tab['tabFields']) ) {
					echo '<div class="wpts-nav-section-holder" id="'.$this->keyEntity($key).'_parent">';
					$this->tab_container( $tab['tabFields'] , $this->keyEntity($key) );
					echo '</div>';
				}

				do_action('wpts_tab_'.$this->keyEntity($key).'_after');
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
			echo '<h1>'.ucfirst($this->theme->get( 'Name' )).' Theme Settings </h1>
			';
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
		$key = preg_replace( '/[^a-zA-Z0-9\']/', '_', $key );
		return rtrim( $key, '_' );
	}
	/*
	 * @ Register Settings
	 */
    public function theme_settings(){
    	foreach ( $this->settingFields as $value ) {
    		register_setting( $this->settingsID, $value, array($this, 'sanitize') );
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

	/*
	 * @ Update notice
	 */
	public function wpts_update_admin_notice() {
		$class = 'notice notice-info is-dismissible';
		$message = __( 'New version of WPTS is available ('.$this->get_wpts_git_version().'), click <a href="https://git.io/vi1Gr" target="_new">here</a> to learn more about it.');
		printf( '<div class="%1$s"><p>%2$s</p></div>', $class, $message ); 
	}

	/*
	 * @ Get wpts github version
	 */
	private function get_wpts_git_version(){
		try {
			$url  = "http://wpts.nexxoz.com/changelog.txt";
			$data = file_get_contents($url);
			$t    = preg_split("#\n\s*\n#Uis", $data);
			foreach ($t as $key => $value) {
			  $lines = explode('+', $value);
			  $version = str_replace("**", "", $lines[0]); 
			  unset($lines[0]);
			  $new_data[] = array('version' => $version, 'data' => $lines);
			}
			return trim($new_data[0]['version']);
		} catch (Exception $e) {
			return $this->currversion;
		}
	}

}
?>
