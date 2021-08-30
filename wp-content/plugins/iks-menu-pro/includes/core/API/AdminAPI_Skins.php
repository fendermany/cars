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

use IksStudio\IKSM_CORE\skins\SkinsManager;
use IksStudio\IKSM_CORE\utils\PluginPostManager;
use IksStudio\IKSM_CORE\utils\Utils;
use WP_REST_Request;
use WP_REST_Response;
use WP_REST_Server;

/**
 * @subpackage REST_Controller
 */
class AdminAPI_Skins extends AdminAPI_Base {

	/**
	 * Spec Endpoint
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	private $spec_endpoint = "skins";

	/**
	 * Initialize the object
	 *
	 * @since     1.0.0
	 */
	public function __construct() {
		$routes = [
			[
				"methods"  => WP_REST_Server::READABLE,
				"callback" => [ $this, "get_skins" ],
			],
			[
				"methods"  => WP_REST_Server::EDITABLE,
				"callback" => [ $this, "import_skin" ],
			],
		];

		parent::__construct( $routes, $this->spec_endpoint );
	}

	/**
	 * Get Skins
	 *
	 * @param WP_REST_Request $request Full data about the request.
	 *
	 * @return WP_REST_Response
	 */
	public function get_skins( $request ) {
		$skins_manager = new SkinsManager();
		$response      = $skins_manager->get_skins();

		return new WP_REST_Response( $response, 200 );
	}

	/**
	 * Import Skin
	 *
	 * @param WP_REST_Request $request Full data about the request.
	 *
	 * @return WP_REST_Response
	 */
	public function import_skin( $request ) {
		$id      = isset( $request["id"] ) ? intval( $request["id"] ) : null;
		$post_id = isset( $request["post_id"] ) ? intval( $request["post_id"] ) : null;

		if ( $id && $post_id ) {
			$skins_manager = new SkinsManager();
			$response      = $skins_manager->get_skin( $id );

			if ( $response["success"] ) {
				$skin = Utils::safe_json_parse( $response["data"] );

				$post_manager = new PluginPostManager( $post_id );
				$response     = $post_manager->import_settings( $skin["settings"], true );

				return new WP_REST_Response( $response, 200 );
			} else {
				return new WP_REST_Response( [
					"success" => false,
					"error"   => Utils::t( "Cannot get skin with such ID" . " ({$id})" )
				], 200 );
			}
		} else {
			return new WP_REST_Response( [
				"success" => false,
				"error"   => Utils::t( "ID of skin or post ID were not provided" )
			], 200 );
		}
	}
}