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

namespace IksStudio\IKSM_CORE\settings;

use IksStudio\IKSM_CORE\utils\EditorUtils;
use IksStudio\IKSM_CORE\utils\Utils;

class SettingsManager {

	/**
	 * @var array|null
	 */
	private $settings = [];

	/**
	 * @var array
	 */
	private $validators_handles = [];

	/**
	 * @var array|null
	 */
	private $prepares_handles = [];

	/**
	 * @var array|null
	 */
	private $post_settings = null;

	/**
	 * SettingsManager constructor.
	 *
	 * @param $post_settings
	 * @param $settings
	 */
	public function __construct( $post_settings, $settings ) {
		$this->post_settings = $post_settings;
		// Init settings
		$this->settings = $settings;
		// Init prepares functions
		$this->init_prepares_handles();
		// Init validators functions
		$this->init_validators_handles();
	}

	/**
	 * @param $key
	 * @param bool $save_structure For example return objects for size and color
	 *
	 * @return mixed|null
	 */
	public function get_value( $key, $save_structure = false ) {
		$setting = Utils::get( $this->settings, $key );

		if ( $setting ) {
			$type         = $setting["type"];
			$defaultValue = null;

			if ( $type === SettingsTypes::$select ) {
				$defaultValue = Utils::get( $setting, "default", null );
			}

			$value = Utils::get( $this->post_settings, $setting["key"], $defaultValue );

			if ( $value !== null ) {
				if ( isset( $this->prepares_handles[ $type ] ) ) {
					$value = $this->prepares_handles[$type]( $value, $save_structure );
				}
				if ( isset( $setting["validate_value"] ) && isset( $this->validators_handles[ $type ] ) ) {
					$value = $this->validators_handles[$type]( $value, $setting["validate_value"] );
				}
				if ( $value !== null ) {
					return $value;
				}
			}
		}

		return null;
	}

	private function init_validators_handles() {
		$this->validators_handles[ SettingsTypes::$checkbox ] = function ( $value, $validator ) {
			return $validator[ (int) $value ];
		};
	}

	private function init_prepares_handles() {
		$this->prepares_handles[ SettingsTypes::$number ] = function ( $value ) {
			return is_numeric( $value ) ? (int) $value : null;
		};

		$this->prepares_handles[ SettingsTypes::$color ] = function ( $value, $save_structure ) {
			return $this->prepare_color_value( $value, $save_structure );
		};

		$this->prepares_handles[ SettingsTypes::$size ] = function ( $value, $save_structure ) {
			return $this->prepare_size_value( $value, $save_structure );
		};

		$this->prepares_handles[ SettingsTypes::$animation ] = function ( $value ) {
			return $this->prepare_animation_value( $value );
		};
	}

	public function prepare_color_value( $value, $save_structure = false ) {
		if ( is_array( $value ) && isset( $value['r'] ) && isset( $value['g'] ) && isset( $value['b'] ) ) {
			$a = Utils::get( $value, "a", 1 );

			if ( $save_structure ) {
				return [ "r" => $value['r'], "g" => $value['g'], "b" => $value['b'], "a" => $a ];
			} else {
				return "rgba({$value['r']},{$value['g']},{$value['b']},{$a})";
			}
		} else {
			return null;
		}
	}

	public function prepare_size_value( $value, $save_structure = false ) {
		if ( is_array( $value ) && isset( $value["number"] ) ) {
			$number = $value["number"];

			if ( $number !== "" ) {
				$int_number = (int) $number;

				if ( is_numeric( $int_number ) ) {
					if ( $int_number === 0 ) {
						if ( $save_structure ) {
							return [ "number" => 0, "postfix" => "" ];
						} else {
							return "0";
						}
					} else {
						$postfix = Utils::get( $value, "postfix", "px" );
						if ( $save_structure ) {
							return [ "number" => $int_number, "postfix" => $postfix ];
						} else {
							return "{$int_number}{$postfix}";
						}
					}
				}
			}
		}

		return null;
	}

	public function prepare_animation_value( $value ) {
		if ( is_array( $value ) && isset( $value['name'] ) && ( (int) $value['duration'] ) > 0 ) {
			return $value;
		}

		return null;
	}

	public function get_settings() {
		return $this->settings;
	}

	public function get_defaults() {
		$defaults = [];

		foreach ( $this->get_settings() as $key => $setting ) {
			if ( isset( $setting["default"] ) ) {
				$defaults[ $key ] = $setting["default"];
			}
		}

		return $defaults;
	}

	public static function check_settings( $plugin_version, $post_settings, $settings ) {
		$do_check = version_compare( $plugin_version, Utils::get( $post_settings, "__plugin_version_update", "0.0.0" ), ">" );
		if ( $do_check ) {
			// Merge with defaults values
			$empty_post_settings = [];
			$defaults            = ( new SettingsManager( $empty_post_settings, $settings ) )->get_defaults();
			$post_settings       = array_merge( $defaults, $post_settings );

			// Set versions
			if ( ! isset( $post_settings["__plugin_version_init"] ) ) {
				$post_settings["__plugin_version_init"] = $plugin_version;
			}
			$post_settings["__plugin_version_update"] = $plugin_version;
		}

		return $post_settings;
	}

	public function get_post_appearance_settings() {
		$result = [];

		$appearance_settings = $this->get_all_appearance_settings();
		foreach ( $appearance_settings as $key ) {
			if ( isset( $this->post_settings[ $key ] ) ) {
				$result[ $key ] = $this->post_settings[ $key ];
			}
		};

		return $result;
	}

	public function get_all_appearance_settings() {
		$result = [];

		foreach ( $this->get_settings() as $setting ) {
			if ( EditorUtils::is_appearance_setting( $setting ) ) {
				array_push( $result, $setting["key"] );
			}
		};

		return $result;
	}
}