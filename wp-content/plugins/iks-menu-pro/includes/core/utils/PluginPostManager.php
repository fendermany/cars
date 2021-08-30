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
use IksStudio\IKSM_CORE\settings\SettingsManager;
use WP_Post;

class PluginPostManager {

	/**
	 * @var WP_Post|null
	 */
	protected $post = null;

	/**
	 * @var array|null
	 */
	protected $settings = null;

	/**
	 * PluginPostManager constructor
	 *
	 * @param $post_id integer
	 *
	 * @since 1.0.0
	 */
	public function __construct( $post_id ) {
		$this->post = get_post( $post_id );
		if ( $this->is_exists() ) {
			$this->settings = json_decode( $this->post->post_content, true );
		}
	}

	public function delete() {
		$exist_check = $this->check_exists();

		if ( $exist_check ) {
			$post_data = wp_delete_post( $this->post->ID, true );
			if ( ! ! $post_data ) {
				return [ "success" => true, "data" => $post_data, "error" => null ];
			} else {
				return [ "success" => false, "data" => null, "error" => Utils::t( "Error with deleting post" ) ];
			}
		} else {
			return $exist_check;
		}
	}

	public function duplicate() {
		$exist_check = $this->check_exists();

		if ( $exist_check ) {
			$post_data = array(
				"post_title"   => $this->post->post_title . " (" . Utils::t( "Copy" ) . ")",
				"post_content" => $this->post->post_content,
			);

			$new_post = PluginPostsManager::create_post( $post_data );
			if ( ! ! $new_post ) {
				return [ "success" => true, "data" => $new_post, "error" => null ];
			} else {
				return [ "success" => false, "data" => null, "error" => Utils::t( "Error with duplicating post" ) ];
			}
		} else {
			return $exist_check;
		}
	}

	public function update( $data ) {
		$exist_check = $this->check_exists();

		if ( $exist_check ) {
			if ( ! isset( $data["post_title"] ) && ! isset( $data["post_content"] ) ) {
				return [
					"success" => false,
					"data"    => null,
					"error"   => Utils::t( "No data provided for updating post" )
				];
			}

			// Title
			$new_data = [
				"post_title" => isset( $data["post_title"] ) ? wp_strip_all_tags( $data["post_title"] ) : $this->post->post_title,
			];
			// Content
			if ( isset( $data["post_content"] ) ) {
				$post_content             = str_replace( array( "\n\r", "\n", "\r", '\n' ), '', $data["post_content"] );
				$post_content             = html_entity_decode( $post_content );
				$new_data["post_content"] = $post_content;
			}

			$new_post = PluginPostsManager::update_post( $this->post->ID, $new_data );
			if ( ! ! $new_post ) {
				return [ "success" => true, "data" => $new_post, "error" => null ];
			} else {
				return [ "success" => false, "data" => null, "error" => Utils::t( "Error with updating post" ) ];
			}
		} else {
			return $exist_check;
		}
	}

	/**
	 * @param $settings string
	 * @param bool $appearance_only
	 *
	 * @return array|bool
	 */
	public function import_settings( $settings, $appearance_only = false ) {
		$exist_check = $this->check_exists();

		if ( $exist_check ) {
			if ( ! empty( $settings ) ) {
				$parsed_settings = Utils::safe_json_parse( html_entity_decode( $settings ) ); // Decoding JSON

				if ( ! empty( $parsed_settings ) ) { // If valid and parsed settings is object
					$all_settings = Plugin::$SettingsStore->get_settings();
					// Checking is needed to import only appearance settings
					if ( $appearance_only === true ) {
						$parsed_settings = $this->merge_appearance_settings( $all_settings, $parsed_settings );
					}
					// Checking settings
					$parsed_settings = SettingsManager::check_settings( Plugin::$version, $parsed_settings, $all_settings );
					// Updating post
					$result = $this->update( [ 'post_content' => json_encode( $parsed_settings ) ] );

					return $result;
				} else {
					return [
						"success" => false,
						"error"   => Utils::t( "Not valid settings" )
					];
				}
			} else {
				return [
					"success" => false,
					"error"   => Utils::t( "No settings provided for import" )
				];
			}
		} else {
			return $exist_check;
		}
	}

	private function merge_appearance_settings( $all_settings, $settings ) {
		$post_settings = $this->get_settings();
		$post_settings = $post_settings ? $post_settings : [];

		// Getting all appearance settings keys
		$appearance_settings = ( new SettingsManager( $post_settings, $all_settings ) )->get_all_appearance_settings();

		// Deleting all appearance settings from post settings
		foreach ( $post_settings as $setting_key => $value ) {
			if ( in_array( $setting_key, $appearance_settings ) ) {
				unset( $post_settings[ $setting_key ] );
			}
		}

		// Deleting all NOT appearance settings from importing settings
		foreach ( $settings as $setting_key => $value ) {
			if ( ! in_array( $setting_key, $appearance_settings ) ) {
				unset( $settings[ $setting_key ] );
			}
		}

		// Merging arrays
		return array_merge( $post_settings, $settings );
	}

	/**
	 * @return bool
	 */
	public function is_exists() {
		return ! ! $this->post;
	}

	/**
	 * @return array|bool
	 */
	public function check_exists() {
		return $this->is_exists() ? true : [
			"success" => false,
			"data"    => null,
			"error"   => Utils::t( "Not found post with such ID" )
		];
	}

	/**
	 * @return array|null
	 */
	public function get_settings() {
		return $this->settings;
	}

	/**
	 * @return WP_Post|null
	 */
	public function get_post() {
		return $this->post;
	}
}