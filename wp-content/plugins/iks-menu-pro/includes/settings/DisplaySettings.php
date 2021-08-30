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

namespace IksStudio\IKSM\settings;

use IksStudio\IKSM_CORE\settings\SettingsTypes;
use IksStudio\IKSM_CORE\utils\Utils;
use IksStudio\IKSM;

class DisplaySettings {

	/**
	 * Settings
	 * @var array
	 */
	private $settings = [];

	/**
	 * DisplaySettings constructor.
	 */
	public function __construct() {
		$this->settings += $this->get_image_settings();
		$this->settings += $this->get_toggle_settings();
		$this->settings += $this->get_posts_count_settings();
	}

	public function get_image_settings() {
		$placeholder_type_options = $this->get_image_placeholder_type_options();

		return [
			"display_term_image"     => [
				"key"   => "display_term_image",
				"type"  => SettingsTypes::$checkbox,
				"label" => Utils::t( "Display image" ),
			],
			"image_placeholder_type" => [
				"key"     => "image_placeholder_type",
				"type"    => SettingsTypes::$select,
				"label"   => Utils::t( "Placeholder" ),
				"options" => $placeholder_type_options,
				"default" => $placeholder_type_options[0]['id']
			],
		];
	}

	public function get_toggle_settings() {
		$toggle_options_object = $this->get_toggle_icon_options();
		$toggle_options = $toggle_options_object["options"];
		$toggle_default = $toggle_options_object["default"];

		$settings = [
			"display_toggle"           => [
				"key"     => "display_toggle",
				"type"    => SettingsTypes::$checkbox,
				"label"   => Utils::t( "Display toggle icon" ),
				"default" => true
			],
			"toggle_icon"              => [
				"key"     => "toggle_icon",
				"type"    => SettingsTypes::$select,
				"label"   => Utils::t( "Icon" ),
				"options" => $toggle_options,
				"default" => $toggle_default
			],
			"toggle_icon_custom_class" => [
				"key"         => "toggle_icon_custom_class",
				"type"        => SettingsTypes::$text,
				"label"       => Utils::t( "Icon - custom class name" ),
				"description" => Utils::t( "For example, FontAwesome icons need classes like \"fa fa-angle-down\". " )
				                 . Utils::t( "Note: you should include custom icons pack in your theme by yourself" ),
				"depends_on"  => "toggle_icon",
				"show_if"     => "custom_class",
				"pro_only"    => true
			],
			"toggle_icon_custom_text" => [
				"key"         => "toggle_icon_custom_text",
				"type"        => SettingsTypes::$text,
				"label"       => Utils::t( "Icon - custom text" ),
				"description" => Utils::t( "For example, any text or symbol like \">\"" ),
				"depends_on"  => "toggle_icon",
				"show_if"     => "custom_text",
				"pro_only"    => true
			],
			"toggle_expand_animation"  => [
				"key"                 => "toggle_expand_animation",
				"type"                => SettingsTypes::$animation,
				"label"               => Utils::t( "Expand animation" ),
				"description"         => Utils::t( "Animation of icon, while expanding it's term" ),
				"default"             => [
					"name"     => "rotate(180deg)",
					"duration" => 400,
				],
				"duration_input"      => [
					"min" => 0,
					"max" => 10000
				],
				"options"             => $this->get_toggle_expand_animation_options(),
				"selector"            => ".iksm-term--expanded > .iksm-term__inner > .iksm-term__toggle > .iksm-term__toggle__inner",
				"transition_selector" => ".iksm-term__toggle__inner",
				"property"            => "transform",
				"not_appearance"      => true,
				"pro_only"            => true
			],
		];

		if ( IKSM\iks_menu_fs()->is_plan__premium_only( "pro", true ) ) {
			$settings = Utils::clear_settings_pro_flag__premium_only( $settings );
		}

		return $settings;
	}

	public function get_posts_count_settings() {
		return [
			"display_posts_count" => [
				"key"     => "display_posts_count",
				"type"    => SettingsTypes::$checkbox,
				"label"   => Utils::t( "Display posts count" ),
				"default" => false
			],
			"posts_count_format"  => [
				"key"         => "posts_count_format",
				"type"        => SettingsTypes::$text,
				"label"       => Utils::t( "Format" ),
				"description" => Utils::t( "You can format output with %VALUE%. For example \"(%VALUE%)\" or \"Count: %VALUE%\"" ),
				"default"     => "%VALUE%"
			],
			"stretch_text"        => [
				"key"               => "stretch_text",
				"type"              => SettingsTypes::$select,
				"label"             => Utils::t( "Position" ),
				"options"           => [
					[ "id" => "yes", "label" => Utils::t( "At the side" ) ],
					[ "id" => "no", "label" => Utils::t( "Next to the text" ) ],
				],
				"validate_to_style" => [
					"with_selector" => true,
					"by_value"      => [
						"yes" => "%ID% .iksm-term__text{flex: 1}",
						"no"  => "%ID% .iksm-term__text{flex: unset}",
					]
				],
				"selector"          => "any",
				"default"           => "no",
				"not_appearance"    => true
			],
		];
	}

	/* Utils */

	private function get_image_placeholder_type_options() {
		$options = [
			[ "id" => "none", "label" => Utils::t( "Do not display" ) ],
			[ "id" => "default", "label" => Utils::t( "Show default" ) ]
		];

		return $options;
	}

	private function get_toggle_icon_options() {
		$generate = [
			[ "sub_id" => "chevron", "label" => Utils::t( "Chevron" ), "count" => 7 ],
			[ "sub_id" => "plus", "label" => Utils::t( "Plus" ), "count" => 10 ],
			[ "sub_id" => "arrow", "label" => Utils::t( "Arrow" ), "count" => 15, "pro_only" => true ],
			[ "sub_id" => "triangle", "label" => Utils::t( "Triangle" ), "count" => 4, "pro_only" => true ],
			[ "sub_id" => "arrows", "label" => Utils::t( "Arrows" ), "count" => 2, "pro_only" => true ],
			[ "sub_id" => "check", "label" => Utils::t( "Check" ), "count" => 13, "pro_only" => true ],
			[ "sub_id" => "ellipsis", "label" => Utils::t( "Ellipsis" ), "count" => 2, "pro_only" => true ],
		];
		$options  = [];

		foreach ( $generate as $type ) {
			for ( $i = 1; $i <= $type["count"]; $i ++ ) {
				$item          = [];
				$id            = "iks-icon-{$type['sub_id']}-{$i}";
				$item["id"]    = $id;
				$item["icon"]  = $id;
				$item["label"] = "{$type['label']} - {$i}";
				if ( Utils::get( $type, "pro_only" ) === true ) {
					$item["pro_only"] = true;
				}
				array_push( $options, $item );
			}
		}

		$default = $options[0]["id"];

		/**
		 * @since 1.4.0
		 */
		array_unshift( $options, [
			"id"    => "custom_class",
			"label" => Utils::t( "Custom class name" ),
		] );

		/**
		 * @since 1.7.6
		 */
		array_unshift( $options, [
			"id"    => "custom_text",
			"label" => Utils::t( "Custom text" ),
		] );

		if ( IKSM\iks_menu_fs()->is_plan__premium_only( "pro", true ) ) {
			$options = Utils::clear_options_pro_flag__premium_only( $options );
		}

		return [
			"options" => $options,
			"default" => $default
		];
	}

	private function get_toggle_expand_animation_options() {
		$options = [
			[ "id" => "none", "label" => Utils::t( "None" ) ],
			[ "id" => "rotate(180deg)", "label" => Utils::t( "Rotate - 180 deg" ) ],
			[ "id" => "rotate(90deg)", "label" => Utils::t( "Rotate - 90 deg left" ), "pro_only" => true ],
			[ "id" => "rotate(-90deg)", "label" => Utils::t( "Rotate - 90 deg right" ), "pro_only" => true ],
			[ "id" => "rotate(360deg)", "label" => Utils::t( "Rotate - 360 deg" ), "pro_only" => true ],
			[ "id" => "scale(0.7)", "label" => Utils::t( "Zoom - out" ), "pro_only" => true ],
			[ "id" => "scale(1.3)", "label" => Utils::t( "Zoom - in" ), "pro_only" => true ],
		];

		if ( IKSM\iks_menu_fs()->is_plan__premium_only( "pro", true ) ) {
			$options = Utils::clear_options_pro_flag__premium_only( $options );
		}

		return $options;
	}

	/**
	 * @return array|null
	 */
	public function get_settings() {
		return $this->settings;
	}

}