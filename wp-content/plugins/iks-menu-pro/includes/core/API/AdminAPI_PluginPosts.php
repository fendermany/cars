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
use IksStudio\IKSM_CORE\settings\SettingsManager;
use IksStudio\IKSM_CORE\utils\PluginPostManager;
use IksStudio\IKSM_CORE\utils\PluginPostsManager;
use IksStudio\IKSM_CORE\utils\Utils;
use WP_Error;
use WP_REST_Request;
use WP_REST_Response;
use WP_REST_Server;

/**
 * @subpackage REST_Controller
 */
class AdminAPI_PluginPosts extends AdminAPI_Base {

	/**
	 * Spec Endpoint
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	private $spec_endpoint = "plugin-posts";

	/**
	 * Callback to get data for response
	 *
	 * @var null|callable
	 */
	private $get_post_creation_data_callback = null;

	/**
	 * Initialize the object
	 *
	 * @param $get_post_creation_data_callback callable
	 *
	 * @since     1.0.0
	 */
	public function __construct( $get_post_creation_data_callback ) {
		$this->get_post_creation_data_callback = $get_post_creation_data_callback;

		$routes = [
			[
				"methods"  => WP_REST_Server::CREATABLE,
				"callback" => [ $this, "create_or_update_plugin_post" ],
			],
			[
				"methods"  => WP_REST_Server::EDITABLE,
				"sub_endpoint" => "update",
				"callback" => [ $this, "create_or_update_plugin_post" ],
			],
			[
				"methods"      => WP_REST_Server::READABLE,
				"sub_endpoint" => "creation-options",
				"callback"     => [ $this, "get_plugin_post_creation_options" ],
			],
			[
				"methods"      => WP_REST_Server::CREATABLE,
				"sub_endpoint" => "duplicate",
				"callback"     => [ $this, "duplicate_plugin_post" ],
			],
			[
				"methods"      => WP_REST_Server::DELETABLE,
				"sub_endpoint" => "remove",
				"callback"     => [ $this, "remove_plugin_post" ],
			],
			[
				"methods"      => WP_REST_Server::EDITABLE,
				"sub_endpoint" => "import",
				"callback"     => [ $this, "import_plugin_post_settings" ],
			],
		];

		parent::__construct( $routes, $this->spec_endpoint );
	}

	/**
	 * Create or Update Plugin post
	 *
	 * @param WP_REST_Request $request Full data about the request.
	 *
	 * @return WP_Error|WP_REST_Response
	 */
	public function create_or_update_plugin_post( $request ) {
		// Getting title from request
		$post_title = wp_strip_all_tags( $request["post_title"] );
		$post_data  = [
			"post_title" => ( ! empty( $post_title ) ) ? $post_title : Utils::t( "Untitled" )
		];

		// Getting content from request
		if ( isset( $request["post_content"] ) ) {
			$post_content = html_entity_decode( $request["post_content"] );
		} else {
			$post_content = '';
		}
		$post_content = Utils::safe_json_parse( $post_content );
		// Checking settings
		$settings     = Plugin::$SettingsStore->get_settings();
		$post_content = SettingsManager::check_settings( Plugin::$version, $post_content, $settings );
		// Set post content in post data
		$post_data["post_content"] = json_encode( $post_content );

		$post_manager = new PluginPostManager( (int) $request["ID"] );
		if ( $post_manager->is_exists() ) {
			/* Updating post */
			$result = $post_manager->update( $post_data );

			return new WP_REST_Response( [
				"success" => $result["success"],
				"data"    => $result["data"],
				"error"   => $result["error"]
			], 200 );

		} else {
			/* Creating new post */
			$new_post = PluginPostsManager::create_post( $post_data );
			$error    = null;
			if ( ! $new_post ) {
				$error = Utils::t( "Cannot create post" );
			}

			return new WP_REST_Response( [
				"success" => ! ! $new_post,
				"data"    => $new_post,
				"error"   => $error
			], 200 );
		}
	}

	/**
	 * Get options for plugin post creation
	 *
	 * @param WP_REST_Request $request Full data about the request.
	 *
	 * @return WP_Error|WP_REST_Response
	 */
	public function get_plugin_post_creation_options( $request ) {
		$data = call_user_func( $this->get_post_creation_data_callback, $request );

		return new WP_REST_Response( $data, 200 );
	}

	/**
	 * Duplicate Plugin post
	 *
	 * @param WP_REST_Request $request Full data about the request.
	 *
	 * @return WP_Error|WP_REST_Response
	 */
	public function duplicate_plugin_post( $request ) {
		$post_id      = (int) $request["ID"];
		$post_manager = new PluginPostManager( $post_id );
		$result       = $post_manager->duplicate();

		return new WP_REST_Response( [
			"success" => $result["success"],
			"data"    => $result["data"],
			"error"   => $result["error"]
		], 200 );

	}

	/**
	 * Remove Plugin post
	 *
	 * @param WP_REST_Request $request Full data about the request.
	 *
	 * @return WP_Error|WP_REST_Response
	 */
	public function remove_plugin_post( $request ) {
		$post_id      = (int) $request["ID"];
		$post_manager = new PluginPostManager( $post_id );
		$result       = $post_manager->delete();

		return new WP_REST_Response( [
			"success" => $result["success"],
			"data"    => $result["data"],
			"error"   => $result["error"]
		], 200 );
	}

	/**
	 * Import Plugin post settings
	 *
	 * @param WP_REST_Request $request Full data about the request.
	 *
	 * @return WP_Error|WP_REST_Response
	 */
	public function import_plugin_post_settings( $request ) {
		$post_id      = (int) $request["ID"];
		$post_manager = new PluginPostManager( $post_id );
		$result       = $post_manager->import_settings(
			Utils::get( $request, "settings" ),
			Utils::get( $request, "appearance_only" )
		);

		return new WP_REST_Response( $result, 200 );
	}

}
