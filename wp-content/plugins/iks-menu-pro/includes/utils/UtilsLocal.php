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

namespace IksStudio\IKSM\utils;

use IksStudio\IKSM_CORE\Plugin;
use IksStudio\IKSM_CORE\utils\Utils;

/**
 * @subpackage UtilsLocal
 */
class UtilsLocal {

	static function get_source_options() {
		return [
			[
				"id"    => "taxonomy",
				"label" => Utils::t( "Taxonomy" ),
			],
			[
				"id"    => "menu",
				"label" => Utils::t( "Custom WP menu" ),
			],
		];
	}

	static function get_images_support() {
		return get_option( Plugin::$slug . "_images_support", null );
	}

	static function get_images_support_taxonomies() {
		return Utils::get( self::get_images_support(), "taxonomies", [] );
	}

	static function get_images_support_custom_menus() {
		return Utils::get( self::get_images_support(), "is_custom_menus", false );
	}

	static function taxonomy_has_images_support( $taxonomy ) {
		if ( $taxonomy === Utils::$woo_taxonomy_id ) {
			return true;
		} else {
			$taxonomies = self::get_images_support_taxonomies();

			return in_array( $taxonomy, $taxonomies );
		}
	}

	static function custom_menus_has_images_support() {
		return self::get_images_support_custom_menus() === true;
	}
}