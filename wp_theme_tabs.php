<?php 
/**
 * Class Name: wp_theme_tabs
 * GitHub URI: https://github.com/nexxoz/wp_theme_tabs
 * Description: A custom WordPress tab class for creating simple theme settings.
 * Version: 1.0.0 Beta
 * Author: Mattias Ghodsian - @twittem
 * Author Site: Nexxoz.com
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */
class wp_theme_tabs{

	private $tabs;
	private $theme;
	private $description;
	private $title;
	private $badge;

	function __construct($args)
	{
		// Needs an array of args to instanciate.
		if (!is_array($args))
            return;

        // Set variables
		$this->tabs = (array_key_exists('tabs', $args)) ? $this->checkTabs($args['tabs']) : '';
		$this->theme = wp_get_theme();
		$this->description = (array_key_exists('description', $args)) ? $args['description'] : '';
		$this->title = (array_key_exists('title', $args)) ? $args['title'] : '';
		$this->badge = (array_key_exists('badge', $args)) ? $args['badge'] : '';

		// Display tabs.
		return $this->tabs();
	}

	private function tabs(){
		echo '<div class="wrap about-wrap">';
		$this->navHeader();
		$this->navTabs();
		echo '</div>';
	}

	private function navTabs(){
		$i = 0;
		echo '<h2 class="nav-tab-wrapper nav-rtab-wrapper wp-clearfix">';
			foreach ($this->tabs as $key => $value) {
				echo '<a href="#'.$key.'" class="nav-tab '.($i == 0 ? 'nav-tab-active' : '').' ">'.$value.'</a>';
				$i++;
			}
		echo '</h2>';

		echo '<div class="nav-rtabs">';
			foreach ($this->tabs as $key => $value) {
				echo '<div class="nav-rtab-holder" id="'.$key.'">';
				do_action('nav_tab_'.$key);
				echo '</div>';
			}
		echo '</div>';
	}

	private function navHeader(){
		echo '<h1>'.(!empty($this->title) ? $this->title : ucfirst($this->theme->get( 'Name' )).' Theme Settings').'</h1>'; 

		if (!empty($this->description)) {
			echo '<div class="about-text">'.$this->description.'</div>';
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

	private function checkTabs($args){
		$tabs = array();
		foreach ($args as $key => $value) {
			$key = strtolower(str_replace(' ', '_', $key));
			$tabs[$key] = $value;
		}
		return $tabs;
	}
}
?>