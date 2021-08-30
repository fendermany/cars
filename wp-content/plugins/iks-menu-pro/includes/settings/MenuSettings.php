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

namespace IksStudio\IKSM\Settings;

use IksStudio\IKSM_CORE\settings\SettingsTypes;
use IksStudio\IKSM_CORE\utils\Utils;
use IksStudio\IKSM;

class MenuSettings {

	/**
	 * Settings
	 * @var array|null
	 */
	private $tab_settings = [];

	/**
	 * MenuSettings constructor.
	 */
	public function __construct() {
		$this->tab_settings = [
			"common"            => [
				"title"    => Utils::t( "Common" ),
				"settings" => $this->get_common_settings(),
			],
			"initial_expansion" => [
				"title"    => Utils::t( "Initial Expansion" ),
				"settings" => $this->get_initial_expansion_settings(),
				"pro_only" => true,
			],
			"animations"        => [
				"title"    => Utils::t( "Animations" ),
				"settings" => $this->get_animation_settings(),
			],
		];

		if ( IKSM\iks_menu_fs()->is_plan__premium_only( "pro", true ) ) {
			$this->tab_settings = Utils::clear_tab_settings_pro_flag__premium_only( $this->tab_settings );
		}
	}

	private function get_common_settings() {
		$settings = [
			"collapse_children_terms"    => [
				"key"         => "collapse_children_terms",
				"type"        => SettingsTypes::$checkbox,
				"label"       => Utils::t( "Collapse children terms" ),
				"description" => Utils::t( "Previously expanded children terms will be collapsed, when collapsing parent" ),
				"default"     => true
			],
			"collapse_other_terms"       => [
				"key"         => "collapse_other_terms",
				"type"        => SettingsTypes::$checkbox,
				"label"       => Utils::t( "Collapse expanded term" ),
				"description" => Utils::t( "Previously expanded term on the same level will be collapsed, when expanding new term" ),
				"default"     => true
			],
			"disable_parent_links_level" => [
				"key"         => "disable_parent_links_level",
				"type"        => SettingsTypes::$number,
				"label"       => Utils::t( "Level to use parent items as toggles" ),
				"description" => Utils::t(
					"Menu items with children won't have links and will only expand children. " .
					"0 - means disable links for parents at all levels, 1 - means only first nesting level, 2 - means first and second nesting levels and etc."
				),
				"input"       => [
					"min" => 0,
					"max" => 100
				],
				"pro_only"    => true
			],
		];

		return $settings;
	}

	private function get_initial_expansion_settings() {
		return [
			"expand_current_term"                    => [
				"key"         => "expand_current_term",
				"type"        => SettingsTypes::$checkbox,
				"label"       => Utils::t( "Expand current item" ),
				"description" => Utils::t(
					"The menu tree of the current item will be expanded when the page is loaded."
				),
				"default"     => true,
			],
			"expand_pages_includes_post"             => [
				"key"         => "expand_children_of_page",
				"type"        => SettingsTypes::$checkbox,
				"label"       => Utils::t( "Expand pages, that includes current page" ),
				"description" => Utils::t(
					"Pages, that are the parents of the current page, will be expanded when the page is loaded."
				),
				"default"     => true,
				"depends_on"  => "source",
				"show_if"     => "menu"
			],
			"initial_expansion_type"                 => [
				"key"         => "initial_expansion_type",
				"type"        => SettingsTypes::$select,
				"label"       => Utils::t( "Additional expansion" ),
				"description" => Utils::t(
					"Set what items need to be expanded when the page is loaded."
				),
				"options"     => MenuSettings::get_initial_expansion_type_options(),
				"default"     => "none"
			],
			"initial_expansion_ids"                  => [
				"key"         => "initial_expansion_ids",
				"type"        => SettingsTypes::$text,
				"label"       => Utils::t( "Certain items" ),
				"description" => Utils::t(
					"Comma-separated string of items IDs, which need to be expanded when the page is loaded"
				),
				"depends_on"  => "initial_expansion_type",
				"show_if"     => "certain_ids"
			],
			"initial_expansion_level"                => [
				"key"         => "initial_expansion_level",
				"type"        => SettingsTypes::$number,
				"label"       => Utils::t( "Certain levels" ),
				"description" => Utils::t(
					"items will be expanded until that nesting level. " .
					"1 - means only first nesting level, 2 - means first and second nesting levels and etc."
				),
				"input"       => [
					"min" => 1,
					"max" => 100
				],
				"depends_on"  => "initial_expansion_type",
				"show_if"     => "certain_levels",
			],
			"initial_expansion_disable_screen_width" => [
				"key"               => "initial_expansion_disable_screen_width",
				"type"              => SettingsTypes::$number,
				"label"             => Utils::t( "Disable (screen width in pixels)" ),
				"description"       => Utils::t(
					"Less than this value (of screen width) the initial expansion will be disabled. " .
					"Note: it's not working for \"Expand current item\" setting, only for additional expansion."
				),
				"input"             => [
					"min" => 1,
					"max" => 3000
				],
				"depends_on"        => "initial_expansion_type",
				"show_if"           => [ "all", "certain_levels", "certain_ids" ],
				"validate_to_style" => [
					"with_selector" => true,
					"before_value"  => "@media (max-width:",
					"after_value"   => "px){%ID% .iksm-term--expanded-initial .iksm-terms-tree {display: none !important;}}"
				],
				"selector"          => "any",
				"need_update"       => true
			],
		];
	}

	private function get_animation_settings() {
		$settings = [
			"expand_animation_duration"   => [
				"key"     => "expand_animation_duration",
				"type"    => SettingsTypes::$number,
				"label"   => Utils::t( "Duration of expand (in ms)" ),
				"default" => 400,
				"input"   => [
					"min" => 0,
					"max" => 10000
				],
			],
			"collapse_animation_duration" => [
				"key"     => "collapse_animation_duration",
				"type"    => SettingsTypes::$number,
				"label"   => Utils::t( "Duration of collapse (in ms)" ),
				"default" => 400,
				"input"   => [
					"min" => 0,
					"max" => 10000
				],
			],
			"container_animation"         => [
				"key"            => "container_animation",
				"type"           => SettingsTypes::$animation,
				"label"          => Utils::t( "Menu IN animation" ),
				"description"    => Utils::t( "This animation will start when the page loads" ),
				"default"        => [
					"name"     => "iks-fade-in",
					"duration" => 1000,
				],
				"duration_input" => [
					"min" => 0,
					"max" => 10000
				],
				"options"        => MenuSettings::get_container_animation_options(),
				"selector"       => ".iksm-terms",
				"property"       => "animation",
				"not_appearance" => true,
			],
			"sub_menu_expand_animation"   => [
				"key"            => "sub_menu_expand_animation",
				"type"           => SettingsTypes::$animation,
				"label"          => Utils::t( "Sub-menu expand animation" ),
				"description"    => Utils::t( "This animation will start when the submenu expands" ),
				"default"        => [
					"name"     => "iks-zoom-in",
					"duration" => 400,
				],
				"duration_input" => [
					"min" => 0,
					"max" => 10000
				],
				"options"        => MenuSettings::get_sub_menu_expand_animation_options(),
				"not_appearance" => true,
				"pro_only"       => true,
			],
			"sub_menu_collapse_animation" => [
				"key"            => "sub_menu_collapse_animation",
				"type"           => SettingsTypes::$animation,
				"label"          => Utils::t( "Sub-menu collapse animation" ),
				"description"    => Utils::t( "This animation will start when the submenu collapses" ),
				"default"        => [
					"name"     => "iks-zoom-out",
					"duration" => 400,
				],
				"duration_input" => [
					"min" => 0,
					"max" => 10000
				],
				"options"        => MenuSettings::get_sub_menu_collapse_animation_options(),
				"not_appearance" => true,
				"pro_only"       => true,
			],
		];

		if ( IKSM\iks_menu_fs()->is_plan__premium_only( "pro", true ) ) {
			$settings["sub_menu_expand_animation"]   = $settings["sub_menu_expand_animation"] + [
					"selector" => ".iksm-terms-tree--children > .iksm-terms-tree__inner",
					"property" => "animation",
				];
			$settings["sub_menu_collapse_animation"] = $settings["sub_menu_collapse_animation"] + [
					"selector" => ".iksm-term--collapsing > .iksm-terms-tree--children > .iksm-terms-tree__inner",
					"property" => "animation",
				];
		}

		return $settings;
	}

	static function get_initial_expansion_type_options() {
		return [
			[ "id" => "none", "label" => Utils::t( "None" ) ],
			[ "id" => "all", "label" => Utils::t( "All items" ) ],
			[ "id" => "certain_ids", "label" => Utils::t( "Certain items" ) ],
			[ "id" => "certain_levels", "label" => Utils::t( "Certain levels" ) ],
		];
	}

	static function get_container_animation_options() {
		$options = [
			"none"           => Utils::t( "None" ),
			"fade"           => Utils::t( "Fade" ),
			"fade-up"        => Utils::t( "Fade - Up" ),
			"fade-right"     => Utils::t( "Fade - Right" ),
			"fade-left"      => Utils::t( "Fade - Left" ),
			"zoom"           => Utils::t( "Zoom" ),
			"zoom-fade"      => Utils::t( "Zoom - Fade" ),
			"slide-right"    => Utils::t( "Slide - Right" ),
			"slide-left"     => Utils::t( "Slide - Left" ),
			"slide-vertical" => Utils::t( "Slide - Up" ),
			"flip-90"        => Utils::t( "Flip - 90 deg" ),
			"flip-180"       => Utils::t( "Flip - 180 deg" ),
		];

		return Utils::generate_animation_options( $options, "in" );
	}

	static function get_sub_menu_expand_animation_options() {
		return MenuSettings::generate_animation_options( "in" );
	}

	static function get_sub_menu_collapse_animation_options() {
		return MenuSettings::generate_animation_options( "out" );
	}

	static function generate_animation_options( $type ) {
		$options = [
			"none"           => Utils::t( "None" ),
			"fade"           => Utils::t( "Fade" ),
			"fade-vertical"  => Utils::t( "Fade - Vertical" ),
			"fade-right"     => Utils::t( "Fade - Right" ),
			"fade-left"      => Utils::t( "Fade - Left" ),
			"zoom"           => Utils::t( "Zoom" ),
			"zoom-fade"      => Utils::t( "Zoom - Fade" ),
			"slide-right"    => Utils::t( "Slide - Right" ),
			"slide-left"     => Utils::t( "Slide - Left" ),
			"slide-vertical" => Utils::t( "Slide - Vertical" ),
			"flip-90"        => Utils::t( "Flip - 90 deg" ),
			"flip-180"       => Utils::t( "Flip - 180 deg" ),
		];

		return Utils::generate_animation_options( $options, $type );
	}

	/**
	 * @return array|null
	 */
	public function get_tab_settings() {
		return $this->tab_settings;
	}

	/**
	 * @return array|null
	 */
	public function get_settings() {
		$tab_settings = $this->get_tab_settings();
		$settings     = [];

		foreach ( $tab_settings as $key => $tab ) {
			$settings = array_merge( $settings, $tab["settings"] );
		}

		return $settings;
	}

}