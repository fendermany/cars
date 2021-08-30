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

namespace IksStudio\IKSM_CORE\render;

use IksStudio\IKSM_CORE\Plugin;
use IksStudio\IKSM_CORE\settings\SettingsManager;
use IksStudio\IKSM_CORE\settings\SettingsTypes;
use IksStudio\IKSM_CORE\utils\Utils;

class StylesGenerator {

	/**
	 * @var string|null
	 */
	private $settings = null;

	/**
	 * @var integer|null
	 */
	private $post_id = null;

	/**
	 * @var array|null
	 */
	private $post_settings = null;

	/**
	 * @var SettingsManager|null
	 */
	private $settings_manager = null;

	/**
	 * @var object
	 */
	private $animation_value_generators = [];

	/**
	 * @var object
	 */
	private $quadruple_dirs = [];

	/**
	 * @var object
	 */
	private $quadruple_property_generators = [];

	/**
	 * StylesGenerator constructor.
	 *
	 * @param $post_id
	 * @param $post_settings
	 * @param $settings
	 */
	public function __construct( $post_id, $post_settings, $settings ) {
		$this->init_vars();
		$this->post_id       = $post_id;
		$this->post_settings = $post_settings;

		$this->settings_manager = new SettingsManager( $post_settings, $settings );

		$this->settings = $this->group_by_selector( $settings );
	}

	/**
	 * Generates styles string from array of settings' values
	 *
	 * @return string
	 */
	public function generate_styles() {
		$styles = "";

		foreach ( $this->settings as $selector => $group ) {
			$block_styles = "";

			foreach ( $group as $index => $setting ) {
				if ( ! $this->check_depends_on( $setting, $this->post_settings ) ) {
					$value = $this->settings_manager->get_value( $setting["key"] );

					if ( $value !== null ) {
						$property = Utils::get( $setting, "property" );

						if ( isset( $setting['validate_to_style'] ) ) {

							$result       = $this->process_validate_to_value( $setting, $value );
							$block_styles .= $result['block_styles'];
							$styles       .= $result['styles'];

						} else if ( $property ) {
							$type = $setting["type"];

							if ( $type === SettingsTypes::$quadruple_size ) {
								$block_styles .= $this->generate_quadruple_size_styles( $property, $value, $setting );
							} else if ( $type === SettingsTypes::$abs_pos ) {
								$block_styles .= $this->generate_quadruple_size_styles( $property, $value, $setting );
								if ( isset( $value["centers"] ) ) {
									$is_vertical    = Utils::get( $value["centers"], "vertical" ) === true;
									$is_horizontal  = Utils::get( $value["centers"], "horizontal" ) === true;
									$translate_hor  = $is_horizontal ? "-50%" : 0;
									$translate_vert = $is_vertical ? "-50%" : 0;

									$block_styles .= $is_horizontal || $is_vertical ? "transform:translate({$translate_hor},{$translate_vert});" : "";
									$block_styles .= $is_horizontal ? "left:50%!important;right:auto!important;" : "";
									$block_styles .= $is_vertical ? "top:50%!important;bottom:auto!important;" : "";
								}
							} else if ( $type === SettingsTypes::$animation ) {
								$block_styles .= $this->generate_style_line(
									$property,
									$this->animation_value_generators[$property]( $value ),
									$setting
								);
								if ( $property === "transform" ) {
									$styles .= $this->generate_style_block(
										$setting["transition_selector"],
										"transition:transform {$value["duration"]}ms"
									);
								}
							} else {
								$block_styles .= $this->generate_style_line( $property, $value, $setting );
							}
							$block_styles .= $this->generate_duplicate_properties_styles( $setting, $value );
						}
					}
				}
			}

			if ( strlen( $block_styles ) ) {
				$styles .= $this->generate_style_block( $selector, $block_styles );
			}
		}

		return $styles;
	}

	private function process_validate_to_value( $setting, $value ) {
		$result      = [ 'styles' => '', 'block_styles' => '' ];
		$resultStyle = $value;
		$vts         = $setting['validate_to_style'];
		$byValue     = Utils::get( $vts, 'by_value' );

		if ( $byValue ) {
			$resultStyle = Utils::get( $byValue, $value, Utils::get( $setting, "default" ) );
		}
		$resultStyle = Utils::get( $vts, 'before_value', '' ) . (string) ( $resultStyle ) . Utils::get( $vts, 'after_value', '' );

		if ( Utils::get( $vts, 'with_selector' ) ) {
			$result['styles'] = str_replace( "%ID%", $this->generate_post_selector(), $resultStyle );
		} else {
			$result['block_styles'] = "{$resultStyle};";
		}

		return $result;
	}

	private function generate_duplicate_properties_styles( $setting, $value ) {
		$styles = "";

		$properties = Utils::get( $setting, "duplicate_properties" );
		if ( $properties ) {
			if ( is_string( $properties ) ) {
				$properties = [ $properties ];
			}
			foreach ( $properties as $property ) {
				$styles .= $this->generate_style_line( $property, $value );
			}
		}

		return $styles;
	}

	private function generate_quadruple_size_styles( $property, $value, $setting ) {
		$dirs   = $this->quadruple_dirs[ $property ];
		$styles = "";

		foreach ( $dirs as $dir ) {
			if ( isset( $value[ $dir ] ) ) {
				$dir_value = $this->settings_manager->prepare_size_value( $value[ $dir ] );
				if ( $dir_value !== null ) {
					$result_property = $this->quadruple_property_generators[$property]( $property, $dir );
					$styles          .= $this->generate_style_line( $result_property, $dir_value, $setting );
				}
			}
		}

		return $styles;
	}

	/**
	 * Generates single style line
	 *
	 * @param $property
	 * @param $value
	 * @param $setting
	 *
	 * @return string
	 */
	private function generate_style_line( $property, $value, $setting = null ) {
		if ( $property === "__custom" ) {
			return "{$value};";
		} else {
			$prefix  = Utils::get( $setting, "value_prefix", "" );
			$postfix = Utils::get( $setting, "value_postfix", "" );

			return "{$property}:{$prefix}{$value}{$postfix};";
		}
	}

	/**
	 * Generates style block
	 *
	 * @param $selector
	 * @param $styles
	 *
	 * @return string
	 */
	private function generate_style_block( $selector, $styles ) {
		return "{$this->generate_post_selector()}{$selector}{{$styles}}";
	}

	/**
	 * Generates selector with plugin's post prefix
	 *
	 * @return string
	 */
	private function generate_post_selector() {
		$prefix = Plugin::$slug;

		return ".{$prefix}-{$this->post_id} ";
	}

	/**
	 * Groups settings by selector
	 *
	 * @param $settings
	 *
	 * @return array
	 */
	private function group_by_selector( $settings ) {
		$grouped = [];
		foreach ( $settings as $setting ) {
			$selector = Utils::get( $setting, "selector" );
			if ( $selector ) {
				$grouped[ $selector ][] = $setting;
			}
		}

		return $grouped;
	}

	/**
	 * Skip setting due to depends_on value
	 *
	 * @param $setting
	 * @param $settingsValues
	 *
	 * @return bool
	 */
	private function check_depends_on( $setting, $settingsValues ) {
		$depends_on = Utils::get( $setting, "depends_on" );

		if ( $depends_on && isset( $settingsValues[ $depends_on ] ) ) {
			$value = Utils::get( $settingsValues, $depends_on );

			if ( isset( $setting["hide_if"] ) ) {
				return $this->depends_on_value_equals( $value, $setting['hide_if'] );
			} else if ( isset( $setting["show_if"] ) ) {
				return ! $this->depends_on_value_equals( $value, $setting['show_if'] );
			}
		}

		return false;
	}

	private function depends_on_value_equals( $value, $checkValue ) {
		if ( is_array( $checkValue ) ) {
			return in_array( $value, $checkValue );
		} else {
			return $checkValue === $value;
		}
	}

	private function init_vars() {
		$this->animation_value_generators = [
			"transform" => function ( $value ) {
				return $value["name"];
			},
			"animation" => function ( $value ) {
				return "{$value["name"]} {$value["duration"]}ms";
			},
		];

		$this->quadruple_dirs = [
			"margin"        => [ "top", "right", "bottom", "left" ],
			"padding"       => [ "top", "right", "bottom", "left" ],
			"border-radius" => [ "top-left", "top-right", "bottom-right", "bottom-left" ],
			"__abs_pos"     => [ "top", "right", "bottom", "left" ],
		];

		$this->quadruple_property_generators = [
			"margin"        => function ( $property, $dir ) {
				return "{$property}-{$dir}";
			},
			"padding"       => function ( $property, $dir ) {
				return "{$property}-{$dir}";
			},
			"border-radius" => function ( $property, $dir ) {
				return "border-{$dir}-radius";
			},
			"__abs_pos"     => function ( $property, $dir ) {
				return $dir;
			},
		];
	}
}