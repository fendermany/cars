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

class StylesSettingsImage {

	private $settings = [];

	public function __construct() {
		$this->settings = [
			StylesSettingsTypes::$width + [
				"duplicate_properties" => [ "min-width" ]
			],
			StylesSettingsTypes::$height + [
				"duplicate_properties" => [ "min-height" ]
			],
			StylesSettingsTypes::$background_size + [
				"default" => "contain",
			],
			StylesSettingsTypes::$background_position + [
				"default" => "center",
			],
			StylesSettingsTypes::$background_repeat + [
				"default" => "no-repeat",
			],
			StylesSettingsTypes::$background_color,
			StylesSettingsTypes::$border_radius,
			StylesSettingsTypes::$margin,
			StylesSettingsTypes::$padding,
			StylesSettingsTypes::$custom,
		];
	}

	/**
	 * @return array
	 */
	public function get_settings() {
		return $this->settings;
	}

}