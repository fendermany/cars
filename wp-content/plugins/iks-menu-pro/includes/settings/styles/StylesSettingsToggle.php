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

namespace IksStudio\IKSM\settings\styles;

use IksStudio\IKSM_CORE\settings\SettingsTypes;
use IksStudio\IKSM_CORE\settings\styles\StylesSettingsTypes;
use IksStudio\IKSM_CORE\utils\Utils;

class StylesSettingsToggle {

	private $settings = [];

	public function __construct() {
		$this->settings = [
			StylesSettingsTypes::$color,
			StylesSettingsTypes::$background_color,
			StylesSettingsTypes::$font_size,
			[
				"key"               => "height_type",
				"type"              => SettingsTypes::$select,
				"label"             => Utils::t( "Height type" ),
				"options"           => [
					[ "id" => "full", "label" => "Full (Stretch vertically)" ],
					[ "id" => "custom", "label" => Utils::t( "Custom (Setting at the bottom)" ) ],
				],
				"property"          => "align-self",
				"validate_to_style" => [
					"by_value" => [
						"full"   => "align-self:stretch;height:unset;",
						"custom" => "align-self:unset;"
					],
				],
			],
			StylesSettingsTypes::$height + [
				"depends_on" => "height_type",
				"show_if"    => "custom"
			],
			StylesSettingsTypes::$width,
			StylesSettingsTypes::$border_radius,
			StylesSettingsTypes::$margin,
			StylesSettingsTypes::$padding,
			StylesSettingsTypes::$box_shadow,
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