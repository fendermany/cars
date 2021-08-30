<?php
/**
 * IksStudio Core
 *
 *
 * @package   IksStudio Core
 * @author    IksStudio
 * @license   GPL-3.0
 * @link      https://iks-studio.com
 * @copyright 2019 IksStudio
 */

namespace IksStudio\IKSM_CORE;

use IksStudio\IKSM_CORE\utils\Utils;

/**
 * @subpackage Admin
 */
class Admin {

	/**
	 * Instance of this class.
	 *
	 * @since    1.0.0
	 *
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 * Plugin basename.
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	protected $plugin_basename = null;

	/**
	 * Slug of the plugin screen.
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	protected $plugin_screen_hook_suffix = null;


	/**
	 * Return an instance of this class.
	 *
	 * @return    object    A single instance of this class.
	 * @since     1.0.0
	 *
	 */
	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
			self::$instance->do_hooks();
		}

		return self::$instance;
	}

	/**
	 * Initialize the plugin by loading admin scripts & styles and adding a
	 * settings page and menu.
	 *
	 * @since     1.0.0
	 */
	private function __construct() {
		$this->plugin_basename = plugin_basename( plugin_dir_path( realpath( dirname( __FILE__ ) ) ) . Plugin::$slug . '.php' );
	}


	/**
	 * Handle WP actions and filters.
	 *
	 * @since    1.0.0
	 */
	private function do_hooks() {
		// Register post type
		add_action( 'init', array( $this, 'register_post_type' ) );

		// Load admin style sheet and JavaScript.
		add_action( 'admin_head', array( $this, 'print_dynamic_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );

		// Add the options page and menu item.
		add_action( 'admin_menu', array( $this, 'add_plugin_admin_menu' ) );

		// Add plugin action link point to settings page
		add_filter( 'plugin_action_links_' . $this->plugin_basename, array( $this, 'add_action_links' ) );
	}

	/**
	 * Register post type
	 * @since 1.0.0
	 */
	public function register_post_type() {
		// Set main arguments for new post type
		$args = array(
			'labels'          => array(
				'name'          => Plugin::$name,
				'singular_name' => Plugin::$name,
				'menu_name'     => Plugin::$name
			),
			'singular_label'  => Plugin::$name,
			'public'          => false,
			'capability_type' => 'post',
			'query_var'       => false,
			'rewrite'         => false,
			'show_ui'         => false,
			'show_in_menu'    => true,
			'hierarchical'    => false,
			'supports'        => false,
			'rewrite'         => array(
				'slug'       => Plugin::$slug,
				'with_front' => false
			),
		);

		// Register post type
		register_post_type( Plugin::$post_type, $args );

		// Remove unnecessary post type field
		remove_post_type_support( Plugin::$post_type, 'editor' );
	}

	/**
	 * Register and enqueue admin-specific style sheet.
	 *
	 * @return    null    Return early if no settings page is registered.
	 * @since     1.0.0
	 *
	 */
	public function enqueue_admin_styles() {
		if ( ! isset( $this->plugin_screen_hook_suffix ) ) {
			return;
		}
		$screen = get_current_screen();
		if ( $this->plugin_screen_hook_suffix == $screen->id ) {
			Utils::enqueue_style( 'admin' );
		}
	}

	/**
	 * Prints empty styles block
	 *
	 * @return    null
	 * @since     1.0.0
	 *
	 */
	public function print_dynamic_styles() {
		echo '<style type="text/css" id="' . Plugin::$slug . '-dynamic-style' . '"></style>';
	}

	/**
	 * Register and enqueue admin-specific javascript
	 *
	 * @return    null    Return early if no settings page is registered.
	 * @since     1.0.0
	 *
	 */
	public function enqueue_admin_scripts() {
		if ( ! isset( $this->plugin_screen_hook_suffix ) ) {
			return;
		}

		$screen = get_current_screen();
		if ( $this->plugin_screen_hook_suffix == $screen->id ) {

			$handle = Utils::enqueue_script( "admin" );
			if ( version_compare( get_bloginfo( 'version' ), Plugin::$supported_WP_version, '>=' ) ) {
				wp_localize_script( $handle, Plugin::$slug . "_object", array(
					'api_nonce'      => wp_create_nonce( 'wp_rest' ),
					'api_url'        => rest_url( Plugin::$slug . '/v1/' ),
					'plugin_dir_url' => Plugin::$dir_url
				) );
				Utils::enqueue_project_public_scripts();
			}
		}
	}

	/**
	 * Register the administration menu for this plugin into the WordPress Dashboard menu.
	 *
	 * @since    1.0.0
	 */
	public function add_plugin_admin_menu() {
		/*
		 * Add a settings page for this plugin to the Settings menu.
		 */
		$this->plugin_screen_hook_suffix = add_menu_page(
			Plugin::$name,
			Plugin::$name,
			'manage_options',
			Plugin::$slug,
			array( $this, 'display_plugin_admin_page' ),
			Utils::get_assets_image_path( "menu-icon.png" )
		);
	}

	/**
	 * Render the settings page for this plugin.
	 *
	 * @since    1.0.0
	 */
	public function display_plugin_admin_page() {
		$wp_version = get_bloginfo( 'version' );
		if ( version_compare( $wp_version, Plugin::$supported_WP_version, '>=' ) ) {
			?>
            <div
              id="<?php echo Plugin::$slug . "_admin" ?>"
              class="<?php echo Plugin::$slug ?> iks"></div>
			<?php
		} else {
			?>
            <div class="<?php echo Plugin::$slug ?>-not-supported-version">
                <img
                  class="logo-icon"
                  src="<?php echo Utils::get_assets_image_path( "logo-icon.svg" ) ?>"
                  alt="logo"
                />
                <img
                  class="logo-text"
                  src="<?php echo Utils::get_assets_image_path( "logo-text.svg" ) ?>"
                  alt="logo"
                />
                <div class="heading"><?php echo Utils::t( "Your WordPress version is not supported" ) ?></div>
                <div class="sub-heading">
					<?php echo Utils::t(
						sprintf( "Sorry, but your WordPress version (%s) is not compatible with IksStudio Core.
						Please update your WordPress to version %s or higher.", $wp_version, Plugin::$supported_WP_version )
					) ?>
                </div>
            </div>
			<?php
		}
	}

	/**
	 * Add settings action link to the plugins page.
	 *
	 * @since    1.0.0
	 */
	public function add_action_links( $links ) {
		return array_merge(
			array(
				'settings' => '<a href="' . admin_url( 'options-general.php?page=' . Plugin::$slug ) . '">' . Utils::t( "Settings", true ) . '</a>',
			),
			$links
		);
	}
}
