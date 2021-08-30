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

use IksStudio\IKSM_CORE\API\AdminAPI_Base;
use IksStudio\IKSM_CORE\Plugin;
use IksStudio\IKSM_CORE\utils\Utils;
use WP_Error;
use WP_REST_Response;
use WP_REST_Server;

/**
 * @subpackage REST_Controller
 */
class AdminAPI_ImagesSupport extends AdminAPI_Base {

	/**
	 * Spec Endpoint
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	private $spec_endpoint = "images-support";

	/**
	 * Initialize the object
	 *
	 * @since     1.0.0
	 */
	public function __construct() {
		$routes = [
			[
				"methods"      => WP_REST_Server::EDITABLE,
				"callback"     => [ $this, "save_images_support" ]
			]
		];

		parent::__construct( $routes, $this->spec_endpoint );
	}

	/**
	 * Save Images Support
	 *
	 * @param $request
	 *
	 * @return WP_Error|WP_REST_Response
	 */
	public function save_images_support( $request ) {
		$taxonomies      = Utils::get( $request, "taxonomies", [] );
		$is_custom_menus = Utils::get( $request, "is_custom_menus", false );
		$option          = Plugin::$slug . "_images_support";
		$error           = null;
		$value           = [
			"taxonomies"      => $taxonomies,
			"is_custom_menus" => $is_custom_menus
		];

		$result = update_option( $option, $value, false /* no autoload */ );

		if ( ! $result ) {
			$error = Utils::t( "Cannot set option for images support" );
		}

		return new WP_REST_Response( [
			"success" => ! ! $result,
			"data"    => $value,
			"error"   => $error
		], 200 );
	}

}
