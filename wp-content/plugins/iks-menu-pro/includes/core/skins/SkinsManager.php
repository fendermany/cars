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

namespace IksStudio\IKSM_CORE\skins;

use IksStudio\IKSM_CORE\Plugin;
use IksStudio\IKSM_CORE\utils\RemoteFetchController;
use IksStudio\IKSM_CORE\utils\Utils;

class SkinsManager extends RemoteFetchController {

	/**
	 * @var string|null
	 */
	private $server = null;

	/**
	 * SkinsManager constructor.
	 *
	 */
	public function __construct() {
		$servers      = Plugin::$skins_servers;
		$this->server = Utils::is_production() ? $servers['prod'] : $servers['dev'];

		parent::__construct( $this->server );
	}

	/**
	 * @param $id number id of skin
	 *
	 * @return array
	 */
	public function get_skin( $id ) {
		$request = [ "id" => $id ];

		if ( Plugin::$fs->is_plan__premium_only( "pro", true ) ) {
			$request["is_fr___us_p_r_o"] = "fds61jdAAdi1"; // TODO: Random string
		}

		return $this->fetch( $request );
	}

	/**
	 * @param $tag string tag to filter skins
	 *
	 * @return array skins
	 */
	public function get_skins( $tag = null ) {
		$response = $this->fetch( [
			"data"           => "1",
			"tag"            => $tag,
			"plugin_version" => Plugin::$version
		] );

		if ( $response["success"] ) {
			$data  = Utils::safe_json_parse( $response["data"] );
			$skins = Utils::get( $data, "skins" );

			foreach ( $skins as $index => $skin ) {
				$skin = (array) $skin;
				$id   = $skin["id"];

				$skin["image"]   = "{$this->server}images/{$id}.png?version=" . Plugin::$version;
				$skins[ $index ] = $skin;
			}
			$response["data"]                = $skins;
			$response["recommended_version"] = Utils::get( $data, "recommended_version" );
		}

		return $response;
	}

	/**
	 * @return array tags
	 */
	public function get_tags() {
		return $this->fetch( [ "tags" => "1" ] );
	}

}