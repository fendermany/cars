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

use IksStudio\IKSM_CORE\utils\Utils;

class StylesSettingsText {

	private $settings = [];

	public function __construct() {
		$this->settings = [
			array_merge( StylesSettingsTypes::$color, [
				"label" => Utils::t( "Text color" )
			] ),
			StylesSettingsTypes::$font_size,
			StylesSettingsTypes::$line_height,
			StylesSettingsTypes::$font_weight,
			StylesSettingsTypes::$text_align,
			StylesSettingsTypes::$text_transform,
			StylesSettingsTypes::$text_decoration,
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