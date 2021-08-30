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

/**
 * @subpackage EditorUtils
 */
class EditorUtils {

	static function is_instant_setting( $setting ) {
		return isset( $setting["selector"] ) && ! isset( $setting["need_update"] );
	}

	static function is_appearance_setting( $setting ) {
		return isset( $setting["property"] ) && ! isset( $setting["not_appearance"] ) || isset( $setting["is_appearance"] );
	}

}