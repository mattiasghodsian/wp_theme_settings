<?php 
/**
 * Class Name: wp_theme_tabs
 * GitHub URI: github.com/mattiasghodsian/wp_theme_settings
 * Description: A custom WordPress class for creating theme settings page (Design looks identical to WP About page)
 * Version: 2.1.3 
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
		$this->settingFields = (array_key_exists('settingFields', $args)) ? $args['settingFields'] : '';
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
	}

	/*
	* @ jQuery & Css
	*/
	public function wp_theme_settings_js_css(){
	  wp_enqueue_style( 'wp-color-picker' );
	  wp_enqueue_script( 'wp-color-picker');
	  wp_enqueue_style('fontawesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css');
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
	* @ FontAwesome 4.6.3 array
	*/
	public function FontAwesomeArray(){
		$array = array(
			"glass" => "Glass",
			"music" => "Music",
			"search" => "Search",
			"envelope-o" => "Envelope Outlined",
			"heart" => "Heart",
			"star" => "Star",
			"star-o" => "Star Outlined",
			"user" => "User",
			"film" => "Film",
			"th-large" => "th-large",
			"th" => "th",
			"th-list" => "th-list",
			"check" => "Check",
			"times" => "Times",
			"search-plus" => "Search Plus",
			"search-minus" => "Search Minus",
			"power-off" => "Power Off",
			"signal" => "signal",
			"cog" => "cog",
			"trash-o" => "Trash Outlined",
			"home" => "home",
			"file-o" => "File Outlined",
			"clock-o" => "Clock Outlined",
			"road" => "road",
			"download" => "Download",
			"arrow-circle-o-down" => "Arrow Circle Outlined Down",
			"arrow-circle-o-up" => "Arrow Circle Outlined Up",
			"inbox" => "inbox",
			"play-circle-o" => "Play Circle Outlined",
			"repeat" => "Repeat",
			"refresh" => "refresh",
			"list-alt" => "list-alt",
			"lock" => "lock",
			"flag" => "flag",
			"headphones" => "headphones",
			"volume-off" => "volume-off",
			"volume-down" => "volume-down",
			"volume-up" => "volume-up",
			"qrcode" => "qrcode",
			"barcode" => "barcode",
			"tag" => "tag",
			"tags" => "tags",
			"book" => "book",
			"bookmark" => "bookmark",
			"print" => "print",
			"camera" => "camera",
			"font" => "font",
			"bold" => "bold",
			"italic" => "italic",
			"text-height" => "text-height",
			"text-width" => "text-width",
			"align-left" => "align-left",
			"align-center" => "align-center",
			"align-right" => "align-right",
			"align-justify" => "align-justify",
			"list" => "list",
			"outdent" => "Outdent",
			"indent" => "Indent",
			"video-camera" => "Video Camera",
			"picture-o" => "Picture Outlined",
			"pencil" => "pencil",
			"map-marker" => "map-marker",
			"adjust" => "adjust",
			"tint" => "tint",
			"pencil-square-o" => "Pencil Square Outlined",
			"share-square-o" => "Share Square Outlined",
			"check-square-o" => "Check Square Outlined",
			"arrows" => "Arrows",
			"step-backward" => "step-backward",
			"fast-backward" => "fast-backward",
			"backward" => "backward",
			"play" => "play",
			"pause" => "pause",
			"stop" => "stop",
			"forward" => "forward",
			"fast-forward" => "fast-forward",
			"step-forward" => "step-forward",
			"eject" => "eject",
			"chevron-left" => "chevron-left",
			"chevron-right" => "chevron-right",
			"plus-circle" => "Plus Circle",
			"minus-circle" => "Minus Circle",
			"times-circle" => "Times Circle",
			"check-circle" => "Check Circle",
			"question-circle" => "Question Circle",
			"info-circle" => "Info Circle",
			"crosshairs" => "Crosshairs",
			"times-circle-o" => "Times Circle Outlined",
			"check-circle-o" => "Check Circle Outlined",
			"ban" => "ban",
			"arrow-left" => "arrow-left",
			"arrow-right" => "arrow-right",
			"arrow-up" => "arrow-up",
			"arrow-down" => "arrow-down",
			"share" => "Share",
			"expand" => "Expand",
			"compress" => "Compress",
			"plus" => "plus",
			"minus" => "minus",
			"asterisk" => "asterisk",
			"exclamation-circle" => "Exclamation Circle",
			"gift" => "gift",
			"leaf" => "leaf",
			"fire" => "fire",
			"eye" => "Eye",
			"eye-slash" => "Eye Slash",
			"exclamation-triangle" => "Exclamation Triangle",
			"plane" => "plane",
			"calendar" => "calendar",
			"random" => "random",
			"comment" => "comment",
			"magnet" => "magnet",
			"chevron-up" => "chevron-up",
			"chevron-down" => "chevron-down",
			"retweet" => "retweet",
			"shopping-cart" => "shopping-cart",
			"folder" => "Folder",
			"folder-open" => "Folder Open",
			"arrows-v" => "Arrows Vertical",
			"arrows-h" => "Arrows Horizontal",
			"bar-chart" => "Bar Chart",
			"twitter-square" => "Twitter Square",
			"facebook-square" => "Facebook Square",
			"camera-retro" => "camera-retro",
			"key" => "key",
			"cogs" => "cogs",
			"comments" => "comments",
			"thumbs-o-up" => "Thumbs Up Outlined",
			"thumbs-o-down" => "Thumbs Down Outlined",
			"star-half" => "star-half",
			"heart-o" => "Heart Outlined",
			"sign-out" => "Sign Out",
			"linkedin-square" => "LinkedIn Square",
			"thumb-tack" => "Thumb Tack",
			"external-link" => "External Link",
			"sign-in" => "Sign In",
			"trophy" => "trophy",
			"github-square" => "GitHub Square",
			"upload" => "Upload",
			"lemon-o" => "Lemon Outlined",
			"phone" => "Phone",
			"square-o" => "Square Outlined",
			"bookmark-o" => "Bookmark Outlined",
			"phone-square" => "Phone Square",
			"twitter" => "Twitter",
			"facebook" => "Facebook",
			"github" => "GitHub",
			"unlock" => "unlock",
			"credit-card" => "credit-card",
			"rss" => "rss",
			"hdd-o" => "HDD",
			"bullhorn" => "bullhorn",
			"bell" => "bell",
			"certificate" => "certificate",
			"hand-o-right" => "Hand Outlined Right",
			"hand-o-left" => "Hand Outlined Left",
			"hand-o-up" => "Hand Outlined Up",
			"hand-o-down" => "Hand Outlined Down",
			"arrow-circle-left" => "Arrow Circle Left",
			"arrow-circle-right" => "Arrow Circle Right",
			"arrow-circle-up" => "Arrow Circle Up",
			"arrow-circle-down" => "Arrow Circle Down",
			"globe" => "Globe",
			"wrench" => "Wrench",
			"tasks" => "Tasks",
			"filter" => "Filter",
			"briefcase" => "Briefcase",
			"arrows-alt" => "Arrows Alt",
			"users" => "Users",
			"link" => "Link",
			"cloud" => "Cloud",
			"flask" => "Flask",
			"scissors" => "Scissors",
			"files-o" => "Files Outlined",
			"paperclip" => "Paperclip",
			"floppy-o" => "Floppy Outlined",
			"square" => "Square",
			"bars" => "Bars",
			"list-ul" => "list-ul",
			"list-ol" => "list-ol",
			"strikethrough" => "Strikethrough",
			"underline" => "Underline",
			"table" => "table",
			"magic" => "magic",
			"truck" => "truck",
			"pinterest" => "Pinterest",
			"pinterest-square" => "Pinterest Square",
			"google-plus-square" => "Google Plus Square",
			"google-plus" => "Google Plus",
			"money" => "Money",
			"caret-down" => "Caret Down",
			"caret-up" => "Caret Up",
			"caret-left" => "Caret Left",
			"caret-right" => "Caret Right",
			"columns" => "Columns",
			"sort" => "Sort",
			"sort-desc" => "Sort Descending",
			"sort-asc" => "Sort Ascending",
			"envelope" => "Envelope",
			"linkedin" => "LinkedIn",
			"undo" => "Undo",
			"gavel" => "Gavel",
			"tachometer" => "Tachometer",
			"comment-o" => "comment-o",
			"comments-o" => "comments-o",
			"bolt" => "Lightning Bolt",
			"sitemap" => "Sitemap",
			"umbrella" => "Umbrella",
			"clipboard" => "Clipboard",
			"lightbulb-o" => "Lightbulb Outlined",
			"exchange" => "Exchange",
			"cloud-download" => "Cloud Download",
			"cloud-upload" => "Cloud Upload",
			"user-md" => "user-md",
			"stethoscope" => "Stethoscope",
			"suitcase" => "Suitcase",
			"bell-o" => "Bell Outlined",
			"coffee" => "Coffee",
			"cutlery" => "Cutlery",
			"file-text-o" => "File Text Outlined",
			"building-o" => "Building Outlined",
			"hospital-o" => "hospital Outlined",
			"ambulance" => "ambulance",
			"medkit" => "medkit",
			"fighter-jet" => "fighter-jet",
			"beer" => "beer",
			"h-square" => "H Square",
			"plus-square" => "Plus Square",
			"angle-double-left" => "Angle Double Left",
			"angle-double-right" => "Angle Double Right",
			"angle-double-up" => "Angle Double Up",
			"angle-double-down" => "Angle Double Down",
			"angle-left" => "angle-left",
			"angle-right" => "angle-right",
			"angle-up" => "angle-up",
			"angle-down" => "angle-down",
			"desktop" => "Desktop",
			"laptop" => "Laptop",
			"tablet" => "tablet",
			"mobile" => "Mobile Phone",
			"circle-o" => "Circle Outlined",
			"quote-left" => "quote-left",
			"quote-right" => "quote-right",
			"spinner" => "Spinner",
			"circle" => "Circle",
			"reply" => "Reply",
			"github-alt" => "GitHub Alt",
			"folder-o" => "Folder Outlined",
			"folder-open-o" => "Folder Open Outlined",
			"smile-o" => "Smile Outlined",
			"frown-o" => "Frown Outlined",
			"meh-o" => "Meh Outlined",
			"gamepad" => "Gamepad",
			"keyboard-o" => "Keyboard Outlined",
			"flag-o" => "Flag Outlined",
			"flag-checkered" => "flag-checkered",
			"terminal" => "Terminal",
			"code" => "Code",
			"reply-all" => "reply-all",
			"star-half-o" => "Star Half Outlined",
			"location-arrow" => "location-arrow",
			"crop" => "crop",
			"code-fork" => "code-fork",
			"chain-broken" => "Chain Broken",
			"question" => "Question",
			"info" => "Info",
			"exclamation" => "exclamation",
			"superscript" => "superscript",
			"subscript" => "subscript",
			"eraser" => "eraser",
			"puzzle-piece" => "Puzzle Piece",
			"microphone" => "microphone",
			"microphone-slash" => "Microphone Slash",
			"shield" => "shield",
			"calendar-o" => "calendar-o",
			"fire-extinguisher" => "fire-extinguisher",
			"rocket" => "rocket",
			"maxcdn" => "MaxCDN",
			"chevron-circle-left" => "Chevron Circle Left",
			"chevron-circle-right" => "Chevron Circle Right",
			"chevron-circle-up" => "Chevron Circle Up",
			"chevron-circle-down" => "Chevron Circle Down",
			"html5" => "HTML 5 Logo",
			"css3" => "CSS 3 Logo",
			"anchor" => "Anchor",
			"unlock-alt" => "Unlock Alt",
			"bullseye" => "Bullseye",
			"ellipsis-h" => "Ellipsis Horizontal",
			"ellipsis-v" => "Ellipsis Vertical",
			"rss-square" => "RSS Square",
			"play-circle" => "Play Circle",
			"ticket" => "Ticket",
			"minus-square" => "Minus Square",
			"minus-square-o" => "Minus Square Outlined",
			"level-up" => "Level Up",
			"level-down" => "Level Down",
			"check-square" => "Check Square",
			"pencil-square" => "Pencil Square",
			"external-link-square" => "External Link Square",
			"share-square" => "Share Square",
			"compass" => "Compass",
			"caret-square-o-down" => "Caret Square Outlined Down",
			"caret-square-o-up" => "Caret Square Outlined Up",
			"caret-square-o-right" => "Caret Square Outlined Right",
			"eur" => "Euro (EUR)",
			"gbp" => "GBP",
			"usd" => "US Dollar",
			"inr" => "Indian Rupee (INR)",
			"jpy" => "Japanese Yen (JPY)",
			"rub" => "Russian Ruble (RUB)",
			"krw" => "Korean Won (KRW)",
			"btc" => "Bitcoin (BTC)",
			"file" => "File",
			"file-text" => "File Text",
			"sort-alpha-asc" => "Sort Alpha Ascending",
			"sort-alpha-desc" => "Sort Alpha Descending",
			"sort-amount-asc" => "Sort Amount Ascending",
			"sort-amount-desc" => "Sort Amount Descending",
			"sort-numeric-asc" => "Sort Numeric Ascending",
			"sort-numeric-desc" => "Sort Numeric Descending",
			"thumbs-up" => "thumbs-up",
			"thumbs-down" => "thumbs-down",
			"youtube-square" => "YouTube Square",
			"youtube" => "YouTube",
			"xing" => "Xing",
			"xing-square" => "Xing Square",
			"youtube-play" => "YouTube Play",
			"dropbox" => "Dropbox",
			"stack-overflow" => "Stack Overflow",
			"instagram" => "Instagram",
			"flickr" => "Flickr",
			"adn" => "App.net",
			"bitbucket" => "Bitbucket",
			"bitbucket-square" => "Bitbucket Square",
			"tumblr" => "Tumblr",
			"tumblr-square" => "Tumblr Square",
			"long-arrow-down" => "Long Arrow Down",
			"long-arrow-up" => "Long Arrow Up",
			"long-arrow-left" => "Long Arrow Left",
			"long-arrow-right" => "Long Arrow Right",
			"apple" => "Apple",
			"windows" => "Windows",
			"android" => "Android",
			"linux" => "Linux",
			"dribbble" => "Dribbble",
			"skype" => "Skype",
			"foursquare" => "Foursquare",
			"trello" => "Trello",
			"female" => "Female",
			"male" => "Male",
			"gratipay" => "Gratipay (Gittip)",
			"sun-o" => "Sun Outlined",
			"moon-o" => "Moon Outlined",
			"archive" => "Archive",
			"bug" => "Bug",
			"vk" => "VK",
			"weibo" => "Weibo",
			"renren" => "Renren",
			"pagelines" => "Pagelines",
			"stack-exchange" => "Stack Exchange",
			"arrow-circle-o-right" => "Arrow Circle Outlined Right",
			"arrow-circle-o-left" => "Arrow Circle Outlined Left",
			"caret-square-o-left" => "Caret Square Outlined Left",
			"dot-circle-o" => "Dot Circle Outlined",
			"wheelchair" => "Wheelchair",
			"vimeo-square" => "Vimeo Square",
			"try" => "Turkish Lira (TRY)",
			"plus-square-o" => "Plus Square Outlined",
			"space-shuttle" => "Space Shuttle",
			"slack" => "Slack Logo",
			"envelope-square" => "Envelope Square",
			"wordpress" => "WordPress Logo",
			"openid" => "OpenID",
			"university" => "University",
			"graduation-cap" => "Graduation Cap",
			"yahoo" => "Yahoo Logo",
			"google" => "Google Logo",
			"reddit" => "reddit Logo",
			"reddit-square" => "reddit Square",
			"stumbleupon-circle" => "StumbleUpon Circle",
			"stumbleupon" => "StumbleUpon Logo",
			"delicious" => "Delicious Logo",
			"digg" => "Digg Logo",
			"pied-piper-pp" => "Pied Piper PP Logo (Old)",
			"pied-piper-alt" => "Pied Piper Alternate Logo",
			"drupal" => "Drupal Logo",
			"joomla" => "Joomla Logo",
			"language" => "Language",
			"fax" => "Fax",
			"building" => "Building",
			"child" => "Child",
			"paw" => "Paw",
			"spoon" => "spoon",
			"cube" => "Cube",
			"cubes" => "Cubes",
			"behance" => "Behance",
			"behance-square" => "Behance Square",
			"steam" => "Steam",
			"steam-square" => "Steam Square",
			"recycle" => "Recycle",
			"car" => "Car",
			"taxi" => "Taxi",
			"tree" => "Tree",
			"spotify" => "Spotify",
			"deviantart" => "deviantART",
			"soundcloud" => "SoundCloud",
			"database" => "Database",
			"file-pdf-o" => "PDF File Outlined",
			"file-word-o" => "Word File Outlined",
			"file-excel-o" => "Excel File Outlined",
			"file-powerpoint-o" => "Powerpoint File Outlined",
			"file-image-o" => "Image File Outlined",
			"file-archive-o" => "Archive File Outlined",
			"file-audio-o" => "Audio File Outlined",
			"file-video-o" => "Video File Outlined",
			"file-code-o" => "Code File Outlined",
			"vine" => "Vine",
			"codepen" => "Codepen",
			"jsfiddle" => "jsFiddle",
			"life-ring" => "Life Ring",
			"circle-o-notch" => "Circle Outlined Notched",
			"rebel" => "Rebel Alliance",
			"empire" => "Galactic Empire",
			"git-square" => "Git Square",
			"git" => "Git",
			"hacker-news" => "Hacker News",
			"tencent-weibo" => "Tencent Weibo",
			"qq" => "QQ",
			"weixin" => "Weixin (WeChat)",
			"paper-plane" => "Paper Plane",
			"paper-plane-o" => "Paper Plane Outlined",
			"history" => "History",
			"circle-thin" => "Circle Outlined Thin",
			"header" => "header",
			"paragraph" => "paragraph",
			"sliders" => "Sliders",
			"share-alt" => "Share Alt",
			"share-alt-square" => "Share Alt Square",
			"bomb" => "Bomb",
			"futbol-o" => "Futbol Outlined",
			"tty" => "TTY",
			"binoculars" => "Binoculars",
			"plug" => "Plug",
			"slideshare" => "Slideshare",
			"twitch" => "Twitch",
			"yelp" => "Yelp",
			"newspaper-o" => "Newspaper Outlined",
			"wifi" => "WiFi",
			"calculator" => "Calculator",
			"paypal" => "Paypal",
			"google-wallet" => "Google Wallet",
			"cc-visa" => "Visa Credit Card",
			"cc-mastercard" => "MasterCard Credit Card",
			"cc-discover" => "Discover Credit Card",
			"cc-amex" => "American Express Credit Card",
			"cc-paypal" => "Paypal Credit Card",
			"cc-stripe" => "Stripe Credit Card",
			"bell-slash" => "Bell Slash",
			"bell-slash-o" => "Bell Slash Outlined",
			"trash" => "Trash",
			"copyright" => "Copyright",
			"at" => "At",
			"eyedropper" => "Eyedropper",
			"paint-brush" => "Paint Brush",
			"birthday-cake" => "Birthday Cake",
			"area-chart" => "Area Chart",
			"pie-chart" => "Pie Chart",
			"line-chart" => "Line Chart",
			"lastfm" => "last.fm",
			"lastfm-square" => "last.fm Square",
			"toggle-off" => "Toggle Off",
			"toggle-on" => "Toggle On",
			"bicycle" => "Bicycle",
			"bus" => "Bus",
			"ioxhost" => "ioxhost",
			"angellist" => "AngelList",
			"cc" => "Closed Captions",
			"ils" => "Shekel (ILS)",
			"meanpath" => "meanpath",
			"buysellads" => "BuySellAds",
			"connectdevelop" => "Connect Develop",
			"dashcube" => "DashCube",
			"forumbee" => "Forumbee",
			"leanpub" => "Leanpub",
			"sellsy" => "Sellsy",
			"shirtsinbulk" => "Shirts in Bulk",
			"simplybuilt" => "SimplyBuilt",
			"skyatlas" => "skyatlas",
			"cart-plus" => "Add to Shopping Cart",
			"cart-arrow-down" => "Shopping Cart Arrow Down",
			"diamond" => "Diamond",
			"ship" => "Ship",
			"user-secret" => "User Secret",
			"motorcycle" => "Motorcycle",
			"street-view" => "Street View",
			"heartbeat" => "Heartbeat",
			"venus" => "Venus",
			"mars" => "Mars",
			"mercury" => "Mercury",
			"transgender" => "Transgender",
			"transgender-alt" => "Transgender Alt",
			"venus-double" => "Venus Double",
			"mars-double" => "Mars Double",
			"venus-mars" => "Venus Mars",
			"mars-stroke" => "Mars Stroke",
			"mars-stroke-v" => "Mars Stroke Vertical",
			"mars-stroke-h" => "Mars Stroke Horizontal",
			"neuter" => "Neuter",
			"genderless" => "Genderless",
			"facebook-official" => "Facebook Official",
			"pinterest-p" => "Pinterest P",
			"whatsapp" => "What's App",
			"server" => "Server",
			"user-plus" => "Add User",
			"user-times" => "Remove User",
			"bed" => "Bed",
			"viacoin" => "Viacoin",
			"train" => "Train",
			"subway" => "Subway",
			"medium" => "Medium",
			"y-combinator" => "Y Combinator",
			"optin-monster" => "Optin Monster",
			"opencart" => "OpenCart",
			"expeditedssl" => "ExpeditedSSL",
			"battery-full" => "Battery Full",
			"battery-three-quarters" => "Battery 3/4 Full",
			"battery-half" => "Battery 1/2 Full",
			"battery-quarter" => "Battery 1/4 Full",
			"battery-empty" => "Battery Empty",
			"mouse-pointer" => "Mouse Pointer",
			"i-cursor" => "I Beam Cursor",
			"object-group" => "Object Group",
			"object-ungroup" => "Object Ungroup",
			"sticky-note" => "Sticky Note",
			"sticky-note-o" => "Sticky Note Outlined",
			"cc-jcb" => "JCB Credit Card",
			"cc-diners-club" => "Diner's Club Credit Card",
			"clone" => "Clone",
			"balance-scale" => "Balance Scale",
			"hourglass-o" => "Hourglass Outlined",
			"hourglass-start" => "Hourglass Start",
			"hourglass-half" => "Hourglass Half",
			"hourglass-end" => "Hourglass End",
			"hourglass" => "Hourglass",
			"hand-rock-o" => "Rock (Hand)",
			"hand-paper-o" => "Paper (Hand)",
			"hand-scissors-o" => "Scissors (Hand)",
			"hand-lizard-o" => "Lizard (Hand)",
			"hand-spock-o" => "Spock (Hand)",
			"hand-pointer-o" => "Hand Pointer",
			"hand-peace-o" => "Hand Peace",
			"trademark" => "Trademark",
			"registered" => "Registered Trademark",
			"creative-commons" => "Creative Commons",
			"gg" => "GG Currency",
			"gg-circle" => "GG Currency Circle",
			"tripadvisor" => "TripAdvisor",
			"odnoklassniki" => "Odnoklassniki",
			"odnoklassniki-square" => "Odnoklassniki Square",
			"get-pocket" => "Get Pocket",
			"wikipedia-w" => "Wikipedia W",
			"safari" => "Safari",
			"chrome" => "Chrome",
			"firefox" => "Firefox",
			"opera" => "Opera",
			"internet-explorer" => "Internet-explorer",
			"television" => "Television",
			"contao" => "Contao",
			"500px" => "500px",
			"amazon" => "Amazon",
			"calendar-plus-o" => "Calendar Plus Outlined",
			"calendar-minus-o" => "Calendar Minus Outlined",
			"calendar-times-o" => "Calendar Times Outlined",
			"calendar-check-o" => "Calendar Check Outlined",
			"industry" => "Industry",
			"map-pin" => "Map Pin",
			"map-signs" => "Map Signs",
			"map-o" => "Map Outlined",
			"map" => "Map",
			"commenting" => "Commenting",
			"commenting-o" => "Commenting Outlined",
			"houzz" => "Houzz",
			"vimeo" => "Vimeo",
			"black-tie" => "Font Awesome Black Tie",
			"fonticons" => "Fonticons",
			"reddit-alien" => "reddit Alien",
			"edge" => "Edge Browser",
			"credit-card-alt" => "Credit Card",
			"codiepie" => "Codie Pie",
			"modx" => "MODX",
			"fort-awesome" => "Fort Awesome",
			"usb" => "USB",
			"product-hunt" => "Product Hunt",
			"mixcloud" => "Mixcloud",
			"scribd" => "Scribd",
			"pause-circle" => "Pause Circle",
			"pause-circle-o" => "Pause Circle Outlined",
			"stop-circle" => "Stop Circle",
			"stop-circle-o" => "Stop Circle Outlined",
			"shopping-bag" => "Shopping Bag",
			"shopping-basket" => "Shopping Basket",
			"hashtag" => "Hashtag",
			"bluetooth" => "Bluetooth",
			"bluetooth-b" => "Bluetooth",
			"percent" => "Percent",
			"gitlab" => "GitLab",
			"wpbeginner" => "WPBeginner",
			"wpforms" => "WPForms",
			"envira" => "Envira Gallery",
			"universal-access" => "Universal Access",
			"wheelchair-alt" => "Wheelchair Alt",
			"question-circle-o" => "Question Circle Outlined",
			"blind" => "Blind",
			"audio-description" => "Audio Description",
			"volume-control-phone" => "Volume Control Phone",
			"braille" => "Braille",
			"assistive-listening-systems" => "Assistive Listening Systems",
			"american-sign-language-interpreting" => "American Sign Language Interpreting",
			"deaf" => "Deaf",
			"glide" => "Glide",
			"glide-g" => "Glide G",
			"sign-language" => "Sign Language",
			"low-vision" => "Low Vision",
			"viadeo" => "Viadeo",
			"viadeo-square" => "Viadeo Square",
			"snapchat" => "Snapchat",
			"snapchat-ghost" => "Snapchat Ghost",
			"snapchat-square" => "Snapchat Square",
			"pied-piper" => "Pied Piper Logo",
			"first-order" => "First Order",
			"yoast" => "Yoast",
			"themeisle" => "ThemeIsle",
			"google-plus-official" => "Google Plus Official",
			"font-awesome" => "Font Awesome"
		);
		asort($array);
		return $array;
	}

}
?>