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

namespace IksStudio\IKSM_CORE\utils;

class RemoteFetchController {

	private $server = null;

	protected function __construct( $server ) {
		$this->server = $server;
	}

	/**
	 * @param string $params
	 *
	 * @return array
	 */
	protected function fetch( $params = '' ) {
		if ( ! function_exists( 'curl_version' ) ) {
			return [
				"success" => false,
				"error"   => "Please, enable \"curl\" in PHP to use remote requests.",
			];
		}

		$curl = curl_init();
		if ( ! empty( $params ) ) {
			curl_setopt( $curl, CURLOPT_URL, $this->server . '?' . http_build_query( $params ) );
		}
		curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt( $curl, CURLOPT_HEADER, 0 );
		$out = curl_exec( $curl );
		curl_close( $curl );

		return [
			"success" => ! ! $out,
			"data"    => $out,
			"error"   => $out === false ? "An error occurred while fetching" : null,
		];
	}

}