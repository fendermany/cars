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
use WP_Error;
use WP_REST_Request;

/**
 * @subpackage REST_Controller
 */
class AdminAPI_Base {

	private $base_endpoint = "/admin/";

	/**
	 * Initialize the object
	 *
	 * @param $routes array
	 * @param $spec_endpoint string
	 *
	 * @since     1.0.0
	 *
	 */
	public function __construct( $routes, $spec_endpoint ) {
		$this->register_routes( $routes, $spec_endpoint );
	}

	/**
	 * Register the routes for the objects of the controller.
	 *
	 * @param $routes array
	 * @param $spec_endpoint string
	 */
	private function register_routes( $routes, $spec_endpoint ) {
		$spec_endpoint = $spec_endpoint . "/";
		$endpoint      = $this->base_endpoint . $spec_endpoint;
		$version       = "1";
		$namespace     = Plugin::$slug . "/v" . $version;

		foreach ( $routes as $i => $route ) {
			$result_endpoint = isset( $route["sub_endpoint"] ) ? ( $endpoint . $route["sub_endpoint"] . "/" ) : $endpoint;
			register_rest_route( $namespace, $result_endpoint, array(
				array(
					"methods"             => $route["methods"],
					"callback"            => $route["callback"],
					"permission_callback" => array( $this, "admin_permissions_check" ),
					"args"                => array(),
				),
			) );
		}
	}

	/**
	 * Check if a given request has access to update a setting
	 *
	 * @param WP_REST_Request $request Full data about the request.
	 *
	 * @return WP_Error|bool
	 */
	public function admin_permissions_check( $request ) {
		return current_user_can( "manage_options" );
	}
}
