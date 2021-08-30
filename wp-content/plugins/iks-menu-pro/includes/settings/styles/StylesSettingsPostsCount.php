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

use IksStudio\IKSM_CORE\settings\styles\StylesSettingsTypes;
use IksStudio\IKSM_CORE\utils\Utils;

class StylesSettingsPostsCount {

	private $settings = [];

	public function __construct() {
		$this->settings = [
			StylesSettingsTypes::$background_color,
			array_merge( StylesSettingsTypes::$color, [
				"label" => Utils::t( "Text color" )
			] ),
			array_merge( StylesSettingsTypes::$font_size, [
				"label" => Utils::t( "Text size" )
			] ),
			StylesSettingsTypes::$border_radius,
			StylesSettingsTypes::$margin,
			StylesSettingsTypes::$padding,
			StylesSettingsTypes::$height,
			StylesSettingsTypes::$width,
			StylesSettingsTypes::$font_weight,
			StylesSettingsTypes::$text_decoration,
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