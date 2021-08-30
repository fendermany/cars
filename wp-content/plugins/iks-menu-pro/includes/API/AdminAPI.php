<?php
/**
 * Iks Menu
 *
 *
 * @package   Iks Menu
 * @author    IksStudio
 * @license   GPL-3.0
 * @link      http://iks-menu.ru
 * @copyright 2019 IksStudio
 */

namespace IksStudio\IKSM\API;

use IksStudio\IKSM_CORE\API\AdminAPI_Plugin;
use IksStudio\IKSM_CORE\API\AdminAPI_PluginPosts;
use IksStudio\IKSM_CORE\API\AdminAPI_Preview;
use IksStudio\IKSM_CORE\API\AdminAPI_Skins;
use IksStudio\IKSM_CORE\utils\Utils;
use IksStudio\IKSM\render\MenuRenderer;
use IksStudio\IKSM\utils\UtilsLocal;

/**
 * @subpackage REST_Controller
 */
class AdminAPI {
	/**
	 * Instance of this class.
	 *
	 * @since    1.0.0
	 *
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 * Initialize the plugin by setting localization and loading public scripts
	 * and styles.
	 *
	 * @since     1.0.0
	 */
	private function __construct() {
	}

	/**
	 * Set up WordPress hooks and filters
	 *
	 * @return void
	 */
	public function do_hooks() {
		add_action( "rest_api_init", array( $this, "register_routes" ) );
	}

	/**
	 * Register the routes for the objects of the controller.
	 */
	public function register_routes() {
		/* Core */
		new AdminAPI_Plugin( [ $this, "get_plugin_data" ] );
		new AdminAPI_PluginPosts( [ $this, "get_post_creation_data" ] );
		new AdminAPI_Preview( [ $this, "render_preview" ] );
		new AdminAPI_Skins();
		/* Project */
		new AdminAPI_ImagesSupport();
	}

	/*
	 * Callbacks
	 */

	public function get_plugin_data() {
		return [
			"images_support" => UtilsLocal::get_images_support(),
		];
	}

	public function get_post_creation_data() {
		$taxonomies = Utils::get_taxonomy_options();
		$sources    = UtilsLocal::get_source_options();
		$menus      = Utils::get_menu_options();

		return [
			"success" => ! ! $taxonomies,
			"data"    => [
				"sources"    => $sources,
				"taxonomies" => $taxonomies,
				"menus"      => $menus,
			],
		];
	}

	public function render_preview( $settings, $post_id ) {
		$renderer = new MenuRenderer( $settings, $post_id );
		$html     = $renderer->render();

		return [
			"html" => $html,
		];
	}

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
}