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

namespace IksStudio\IKSM_CORE\API;

use IksStudio\IKSM_CORE\Plugin;
use IksStudio\IKSM_CORE\utils\PluginPostsManager;
use WP_Error;
use WP_REST_Response;
use WP_REST_Server;

/**
 * @subpackage REST_Controller
 */
class AdminAPI_Plugin extends AdminAPI_Base {

	/**
	 * Spec Endpoint
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	private $spec_endpoint = "plugin";

	/**
	 * Callback to get data for response
	 *
	 * @var null|callable
	 */
	private $get_data_callback = null;

	/**
	 * Initialize the object
	 *
	 * @param $get_data_callback callable
	 *
	 * @since     1.0.0
	 */
	public function __construct( $get_data_callback = null ) {
		$this->get_data_callback = $get_data_callback;

		$routes = [
			[
				"methods"  => WP_REST_Server::READABLE,
				"callback" => [ $this, "get_admin_data" ],
			],
		];

		parent::__construct( $routes, $this->spec_endpoint );
	}

	/**
	 * Get admin data
	 *
	 * @param $request
	 *
	 * @return WP_Error|WP_REST_Response
	 * @since 1.2.0
	 */
	public function get_admin_data( $request ) {
		$posts       = ( new PluginPostsManager() )->get_posts();
		$settings    = Plugin::$SettingsStore->get_editor_settings();
		$custom_data = call_user_func( $this->get_data_callback );

		$response = array_merge(
			[
				"success"          => is_array( $posts ) && $settings,
				"posts"            => $posts,
				"settings"         => $settings,
				"plugin_version"   => Plugin::$version,
				"plugin_name"      => Plugin::$name,
				"plugin_shortcode" => Plugin::$shortcodes[0],
			],
			is_array( $custom_data ) ? $custom_data : []
		);

		if ( Plugin::$fs->is_plan__premium_only( "pro", true ) ) {
			$response["is_pro"] = true;
		}

		return new WP_REST_Response( $response, 200 );
	}
}
