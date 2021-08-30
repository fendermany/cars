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

use IksStudio\IKSM_CORE\utils\PluginPostManager;
use WP_REST_Request;
use WP_REST_Response;
use WP_REST_Server;

/**
 * @subpackage REST_Controller
 */
class AdminAPI_Preview extends AdminAPI_Base {

	/**
	 * Spec Endpoint
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	private $spec_endpoint = "preview";

	/**
	 * Callback to render html for response
	 *
	 * @var null|callable
	 */
	private $render_callback = null;

	/**
	 * Initialize the object
	 *
	 * @param $render_callback callable
	 *
	 * @since     1.0.0
	 */
	public function __construct( $render_callback ) {
		$this->render_callback = $render_callback;

		$routes = [
			[
				"methods"  => WP_REST_Server::EDITABLE,
				"callback" => [ $this, "render_preview" ],
			],
		];

		parent::__construct( $routes, $this->spec_endpoint );
	}

	/**
	 * Get Terms
	 *
	 * @param WP_REST_Request $request Full data about the request.
	 *
	 * @return WP_REST_Response
	 */
	public function render_preview( $request ) {
		$post_id = isset( $request["ID"] ) ? $request["ID"] : false;
		$setting = null;

		if ( isset( $request["settings"] ) ) { // If settings were passed, then use it
			$settings = $request["settings"];
		} else if ( $post_id ) { // If post ID was passed, then get settings from it
			$post_manager = new PluginPostManager( $post_id );
			$settings     = $post_manager->get_settings();
		} else {
			return new WP_REST_Response( [
				"success" => false,
				"error"   => "Both post ID and settings were not provided",
			], 200 );
		}

		$data = call_user_func( $this->render_callback, $settings, $post_id );

		$response = [
			"success" => true,
			"preview" => $data["html"],
		];

		return new WP_REST_Response( $response, 200 );
	}
}
