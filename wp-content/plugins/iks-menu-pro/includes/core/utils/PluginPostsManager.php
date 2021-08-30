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

use IksStudio\IKSM_CORE\Plugin;

class PluginPostsManager {

	/**
	 * @var array|null
	 */
	private $posts = null;

	/**
	 * Initialize the widget
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		$this->posts = get_posts( array(
			'numberposts' => - 1,
			'post_type'   => Plugin::$post_type,
			'post_status' => 'any'
		) );
	}

	private static function create_or_update_post( $data ) {
		$post_data = $data + [
				'post_type'   => Plugin::$post_type,
				'post_status' => 'publish',
			];

		$new_post_id = wp_insert_post( $post_data );
		if ( $new_post_id ) {
			$post = get_post( $new_post_id );
			if ( $post ) {
				return $post;
			}
		}

		return null;
	}

	public static function update_post( $ID, $data ) {
		return $ID ? PluginPostsManager::create_or_update_post( $data + [ 'ID' => $ID ] ) : null;
	}

	public static function create_post( $data ) {
		return PluginPostsManager::create_or_update_post( $data );
	}

	/**
	 * @return string|null
	 */
	public function get_posts() {
		return $this->posts;
	}

}