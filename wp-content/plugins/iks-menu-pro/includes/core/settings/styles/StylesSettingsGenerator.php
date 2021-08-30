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

namespace IksStudio\IKSM_CORE\settings\styles;

use IksStudio\IKSM_CORE\Plugin;
use IksStudio\IKSM_CORE\utils\Utils;

class StylesSettingsGenerator {

	public static function generate_settings( $settings_map, $settings_by_type ) {
		$settings = [];

		foreach ( $settings_map as $main_key => $data ) {
			$type = $data["type"];

			$settings[ $main_key ] = array_merge( $data, [
				"states" => []
			] );

			/* Filtering "exclude" settings */
			$settings_to_generate = $settings_by_type[ $type ];
			if ( isset( $data["exclude"] ) ) {
				$settings_to_generate = array_filter( $settings_to_generate, function ( $item ) use ( $data ) {
					return ! in_array( $item["key"], $data["exclude"] );
				} );
			}

			foreach ( $data["states"] as $state_key => $state_data ) {
				$state_title      = $state_data["title"];
				$selector         = $state_data["selector"];
				$defaults         = Utils::get( $state_data, "defaults" );
				$custom_selectors = Utils::get( $state_data, "custom_selectors" );
				// Result
				$result_settings = null;

				/* Generating main settings by type */
				$result_settings = StylesSettingsGenerator::transform_settings( $settings_to_generate, $main_key, $state_key, $selector, $custom_selectors, $defaults );

				/* Custom settings */
				$custom_settings = Utils::get( $state_data, "custom_settings" );
				if ( $custom_settings ) {
					$result_settings = array_merge(
						$result_settings,
						StylesSettingsGenerator::transform_settings( $custom_settings, $main_key, $state_key, $selector, $custom_selectors )
					);
				}

				/* Additional settings */
				$additional_settings = Utils::get( $state_data, "additional_settings" );
				if ( $additional_settings ) {
					$result_settings = array_merge(
						$additional_settings,
						$result_settings
					);
				}

				$settings[ $main_key ]["states"][ $state_key ] = [
					"title"    => $state_title,
					"selector" => $selector,
					"settings" => $result_settings,
				];
			}
		}

		return $settings;
	}

	/**
	 * @param $settings
	 * @param $main_key
	 * @param $state_key
	 * @param $selector
	 * @param $custom_selectors
	 * @param $defaults
	 *
	 * @return array
	 */
	private static function transform_settings( $settings, $main_key, $state_key, $selector, $custom_selectors, $defaults = null ) {
		$result = [];

		foreach ( $settings as $index => $setting ) {
			$key              = $setting["key"];
			$selector_postfix = Utils::get( $setting, "selector_postfix", "" );
			$new_key          = StylesSettingsGenerator::generate_setting_key( $main_key, $state_key, $key );
			$setting["key"]   = $new_key;

			if ( isset( $setting["property"] ) ) {
				if ( isset( $custom_selectors[ $key ] ) ) {
					$setting["selector"] = $custom_selectors[ $key ];
				} else {
					$setting["selector"] = $selector . $selector_postfix;
				}
			}
			if ( isset( $setting["depends_on"] ) ) {
				$setting["depends_on"] = StylesSettingsGenerator::generate_setting_key( $main_key, $state_key, $setting["depends_on"] );
			}
			if ( isset( $defaults[ $key ] ) ) {
				$setting["default"] = $defaults[ $key ];
			}
			if ( isset( $setting["pro_only"] ) ) {
				if ( Plugin::$fs->is_plan__premium_only( "pro", true ) ) {
					unset( $setting["pro_only"] );
				}
			}
			$result[ $new_key ] = $setting;
		}

		return $result;
	}

	public static function generate_setting_key( $main_key, $state_key, $inner_key ) {
		return "{$main_key}_{$state_key}_{$inner_key}";
	}

}