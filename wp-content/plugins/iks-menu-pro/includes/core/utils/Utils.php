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

/**
 * @subpackage Utils
 */
class Utils {

	static $woo_taxonomy_id = "product_cat";

	static function is_production() {
		return ! file_exists( Plugin::$dir_name . "src" );
	}

	static function get_javascript_file_path( $name ) {
		if ( ! self::is_production() ) {
			return "http://localhost:" . Plugin::$dev_port . "/assets/" . $name . ".js";
		} else {
			$production_file = "assets/js/" . $name . ".js";
			if ( file_exists( Plugin::$dir_name . $production_file ) ) {
				return Plugin::$dir_url . $production_file;
			}
		}

		return null;
	}

	static function enqueue_script( $name ) {
		$handle = Plugin::$slug . "-" . $name . "-script";
		$path   = self::get_javascript_file_path( $name );
		wp_enqueue_script( $handle, $path, array( "jquery" ), Plugin::$version );

		return $handle;
	}

	static function enqueue_public_script( $name, $localize_name = false, $localize_data = false ) {
		$handle = Plugin::$slug . "-" . $name . "-script";
		$path   = Plugin::$dir_url . "assets/js/" . $name . ".js";
		wp_enqueue_script( $handle, $path, array( "jquery" ), Plugin::$version );
		if ( $localize_name ) {
			wp_localize_script( $handle, $localize_name, $localize_data );
		}

		return $handle;
	}

	static function enqueue_project_public_scripts() {
		$handles = [];

		if ( is_array( Plugin::$public_scripts ) ) {
			foreach ( Plugin::$public_scripts as $name ) {
				$handle           = self::enqueue_public_script( $name );
				$handles[ $name ] = $handle;
			}
		}

		return $handles;
	}

	static function enqueue_style( $name ) {
		$handle = null;
		// Styles will be enqueued only in production.
		// In development it will be includes from JS.
		if ( self::is_production() ) {
			$handle = Plugin::$slug . "-" . $name . "-style";
			wp_enqueue_style( $handle, plugins_url( "assets/css/" . $name . ".css", Plugin::$main_file ), array(), Plugin::$version );
		}

		return $handle;
	}

	static function get_assets_image_path( $path_in_assets ) {
		if ( ! self::is_production() ) {
			return "http://localhost:" . Plugin::$dev_port . "/assets/assets/images/{$path_in_assets}";
		} else {
			return plugins_url( "assets/images/{$path_in_assets}", Plugin::$main_file );
		}
	}

	static function get_placeholder_image() {
		return self::get_assets_image_path( "images-placeholder.jpg" );
	}

	static function t( $text, $do_escape = false ) {
		return $do_escape ? esc_html__( $text, Plugin::$slug ) : __( $text, Plugin::$slug );
	}

	static function get( $array, $key, $defaultValue = null ) {
		return isset( $array[ $key ] ) ? $array[ $key ] : $defaultValue;
	}

	static function pretty_dump( $data ) {
		echo "<pre>" . var_export( $data, true ) . "</pre>";
	}

	static function get_taxonomy_options() {
		$taxonomies = get_taxonomies( [ "public" => true ], "objects" );
		$array      = [];
		foreach ( $taxonomies as $key => $taxonomy ) {
			array_push( $array, [
				"id"         => $taxonomy->name,
				"label"      => $taxonomy->label . " (" . $taxonomy->name . ")",
				"only_label" => $taxonomy->label,
			] );
		}

		return $array;
	}

	static function get_menu_options() {
		$terms = get_terms( "nav_menu", [ "hide_empty" => false ] );
		$array = [];
		foreach ( $terms as $key => $term ) {
			array_push( $array, [
				"id"    => $term->term_id,
				"label" => $term->name
			] );
		}

		return $array;
	}

	static function get_post_type_by_taxonomy( $taxonomy ) {
		$post_types = get_post_types();

		foreach ( $post_types as $post_type ) {
			$taxonomies = get_object_taxonomies( $post_type );
			if ( in_array( $taxonomy, $taxonomies ) ) {
				return $post_type;
			}
		}

		return false;
	}

	static function is_json_was_parsed() {
		return ( json_last_error() == JSON_ERROR_NONE );
	}

	static function is_valid_json( $string ) {
		json_decode( $string, true );

		return self::is_json_was_parsed();
	}

	static function safe_json_parse( $string ) {
		$parsed = json_decode( $string, true );

		if ( self::is_json_was_parsed() ) {
			return (array) $parsed;
		} else {
			return [];
		}
	}

	static function generate_animation_options( $options, $type ) {
		$result = [];
		foreach ( $options as $id => $label ) {
			$res_type = $id === "none" ? "" : "-{$type}";
			array_push( $result, [ "id" => "iks-{$id}{$res_type}", "label" => $label ] );
		}

		return $result;
	}

	static function clear_tab_settings_pro_flag__premium_only( $settings ) {
		foreach ( $settings as $tab_key => $tab ) {
			unset( $settings[ $tab_key ]["pro_only"] );

			foreach ( $tab["settings"] as $key => $setting ) {
				unset( $settings[ $tab_key ]["settings"][ $key ]["pro_only"] );
			}
		}

		return $settings;
	}

	static function clear_settings_pro_flag__premium_only( $settings ) {
		foreach ( $settings as $key => $setting ) {
			unset( $settings[ $key ]["pro_only"] );
		}

		return $settings;
	}

	static function clear_options_pro_flag__premium_only( $options ) {
		foreach ( $options as $index => $option ) {
			unset( $options[ $index ]["pro_only"] );
		}

		return $options;
	}

	static function split_numbers_by_comma_space( $string ) {
		$array = ! empty( $string ) ? preg_split( '/[\ \n\,]+/', $string ) : [];

		return array_map( 'intval', $array );
	}

	static function object_has_property( $object, $property ) {
		return is_object( $object ) && property_exists( $object, $property );
	}

	static function url_with_slash( $url ) {
		return urldecode( rtrim( $url, "/" ) . '/' );
	}

}