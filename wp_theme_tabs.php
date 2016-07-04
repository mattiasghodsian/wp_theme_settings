<?php 
/**
 * Class Name: wp_theme_tabs
 * GitHub URI: https://github.com/nexxoz/wp_theme_tabs
 * Description: A custom WordPress tab class for creating theme settings page (Design looks identical to WP About page)
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
		$this->tabs = (array_key_exists('tabs', $args)) ? $args['tabs'] : '';
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
			foreach ($this->tabs as $key => $tab) {
				echo '<a href="#'.$this->keyEntity($key).'" class="nav-tab '.($i == 0 ? 'nav-tab-active' : '').' ">'.(array_key_exists('dashicon', $tab) ? '<span class="dashicons '.$tab['dashicon'].'"></span>' : '').$tab['text'].'</a>';
				$i++;
			}
		echo '</h2>';

		echo '<div class="nav-rtabs">';
			foreach ($this->tabs as $key => $tab) {
				echo '<div class="nav-rtab-holder" id="'.$this->keyEntity($key).'">';
				do_action('nav_tab_'.$this->keyEntity($key));
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

	private function keyEntity($key){
		$specialChars = array(" ", "`", "’", "!", "/", "[", "]", "(", ")", "!");
		return str_replace($specialChars, "_", $key);
	}
}
?>