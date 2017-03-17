<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://alex.stocker.info
 * @since      1.0.0
 *
 * @package    Newsmodule
 * @subpackage Newsmodule/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Newsmodule
 * @subpackage Newsmodule/admin
 * @author     Alexander Stocker <alex@stocker.info>
 */
class Newsmodule_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {}

	/**
	 * Add Plugin admin menu item
	 */
	public function add_plugin_admin_menu() {
		add_options_page( 'Newmodule ShortCode setup', 'Newsmodul', 'manage_options', $this->plugin_name, array($this, 'display_plugin_setup_page')
		);
	}

	/**
	 * @param $links
	 * @return array
	 */
	public function add_action_links($links ) {
		$settings_link = array(
			'<a href="' . admin_url( 'options-general.php?page=' . $this->plugin_name ) . '">' . __('Settings', $this->plugin_name) . '</a>',
		);
		return array_merge(  $settings_link, $links );

	}

	/**
	 * Add shortcode Button to Editor
	 */
	public function recentnews_buttons(){
		if( current_user_can('edit_posts') &&  current_user_can('edit_pages') ) {
			add_filter( 'mce_external_plugins', array($this, 'newsmodule_add_buttons' ));
			add_filter( 'mce_buttons', array($this, 'newsmodule_register_buttons' ));
		}
	}

	/**
	 * @param $plugin_array
	 * @return mixed
	 */
	public function newsmodule_add_buttons($plugin_array ) {
		$plugin_array['newsmodule_button'] = plugins_url( 'js/newsmodule-admin.js', __FILE__ );
		return $plugin_array;
	}

	/**
	 * @param $buttons
	 * @return mixed
	 */
	public function newsmodule_register_buttons($buttons ) {
		array_push( $buttons, 'newsmodule_button');
		return $buttons;
	}

	/**
	 * Display the plugins setup page
	 */
	public function display_plugin_setup_page() {
		include_once( 'partials/newsmodule-admin-display.php' );
	}

	/**
	 * Save plugin setup
	 */
	public function options_update() {
		register_setting($this->plugin_name, $this->plugin_name, array($this, 'validate'));
	}

	/**
	 * @param $input
	 * @return array
	 */
	public function validate($input) {
		$valid = array();
		$valid['post_count'] = (isset($input['post_count']) && !empty($input['post_count'])) ? $input['post_count'] : 0;
		return $valid;
	}

}
