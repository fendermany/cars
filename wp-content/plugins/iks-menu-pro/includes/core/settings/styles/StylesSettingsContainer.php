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

class StylesSettingsContainer {

	private $settings = [];

	public function __construct() {
		$this->settings = [
			StylesSettingsTypes::$background_color,
			StylesSettingsTypes::$border_radius,
			StylesSettingsTypes::$margin,
			StylesSettingsTypes::$padding,
			StylesSettingsTypes::$min_height,
			StylesSettingsTypes::$width,
			StylesSettingsTypes::$max_width,
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