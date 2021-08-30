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

use IksStudio\IKSM_CORE\settings\SettingsTypes;
use IksStudio\IKSM_CORE\settings\styles\StylesSettingsContainer;
use IksStudio\IKSM_CORE\settings\styles\StylesSettingsGenerator;
use IksStudio\IKSM_CORE\settings\styles\StylesSettingsImage;
use IksStudio\IKSM_CORE\settings\styles\StylesSettingsText;
use IksStudio\IKSM_CORE\settings\styles\StylesSettingsTypes;
use IksStudio\IKSM_CORE\utils\Utils;
use IksStudio\IKSM;
use IksStudio\IKSM\settings\DisplaySettings;

class StylesSettings {

	/**
	 * @var array|null
	 */
	private $tab_settings = [];

	public function __construct() {
		$settings_by_type = [
			"container"   => ( new StylesSettingsContainer() )->get_settings(),
			"text"        => ( new StylesSettingsText() )->get_settings(),
			"image"       => ( new StylesSettingsImage() )->get_settings(),
			"toggle"      => ( new StylesSettingsToggle() )->get_settings(),
			"posts_count" => ( new StylesSettingsPostsCount() )->get_settings(),
		];

		$this->tab_settings = StylesSettingsGenerator::generate_settings(
			$this->get_settings_map(),
			$settings_by_type
		);
	}

	private function get_settings_map() {
		$display_settings     = new DisplaySettings();
		$image_settings       = $display_settings->get_image_settings();
		$toggle_settings      = $display_settings->get_toggle_settings();
		$posts_count_settings = $display_settings->get_posts_count_settings();

		$image_additional_settings = $image_settings;
		unset( $image_additional_settings["display_term_image"] );

		$toggle_additional_settings = $toggle_settings;
		unset( $toggle_additional_settings["display_toggle"] );

		$posts_count_additional_settings = $posts_count_settings;
		unset( $posts_count_additional_settings["display_posts_count"] );

		// TODO: Take out classes in common file

		$settings = [
			"container"      => [
				"title"              => Utils::t( "Container" ),
				"type"               => "container",
				"exclude"            => [ StylesSettingsTypes::$min_height["key"] ],
				"highlight_selector" => ".iksm-terms",
				"states"             => [
					"main"     => [
						"title"           => Utils::t( "Main" ),
						"selector"        => ".iksm-terms",
						"custom_settings" => [ StylesSettingsTypes::$transition ]
					],
					"children" => [
						"title"    => Utils::t( "Children" ),
						"selector" => ".iksm-terms-tree--children > .iksm-terms-tree__inner",
					],
				],
			],
			"term_container" => [
				"title"              => Utils::t( "Term" ),
				"type"               => "container",
				"exclude"            => [ StylesSettingsTypes::$width["key"], StylesSettingsTypes::$max_width["key"] ],
				"highlight_selector" => ".iksm-term__inner",
				"states"             => [
					"main"          => [
						"title"           => Utils::t( "Main" ),
						"selector"        => ".iksm-term__inner",
						"custom_settings" => [
							StylesSettingsTypes::$transition,
							"level_shift" => [
								"key"           => "level_shift",
								"type"          => SettingsTypes::$size,
								"label"         => Utils::t( "Level shift" ),
								"description"   => Utils::t(
									"Offset for menu items multiplied by level"
								),
								"input"         => [ "min" => 0, "max" => 1000 ],
								"default"       => [ "number" => 15, "postfix" => "px" ],
								"is_appearance" => true
							],
						],
						"defaults"        => [
							"background_color" => [ "r" => "255", "g" => "255", "b" => "255", "a" => "1" ],
							"min_height"       => [ "number" => 44, "postfix" => "px" ],
						],
					],
					"hover"         => [
						"title"    => Utils::t( "Hover" ),
						"selector" => ".iksm-term__inner:hover",
						"defaults" => [
							"background_color" => [ "r" => "236", "g" => "236", "b" => "236", "a" => "1" ],
						],
					],
					"current"       => [
						"title"    => Utils::t( "Current" ),
						"selector" => ".iksm-term--current > .iksm-term__inner",
						"defaults" => [
							"background_color" => [ "r" => "212", "g" => "212", "b" => "212", "a" => "1" ],
						],
					],
					"child"         => [
						"title"    => Utils::t( "Child" ),
						"selector" => ".iksm-term--child .iksm-term__inner",
					],
					"child_hover"   => [
						"title"    => Utils::t( "Child & Hover" ),
						"selector" => ".iksm-term--child .iksm-term__inner:hover",
					],
					"child_current" => [
						"title"    => Utils::t( "Child & Current" ),
						"selector" => ".iksm-term--child.iksm-term--current > .iksm-term__inner",
					],
				]
			],
			"term_text"      => [
				"title"              => Utils::t( "Link" ),
				"type"               => "text",
				"highlight_selector" => ".iksm-term__link",
				"states"             => [
					"main"          => [
						"title"           => Utils::t( "Main" ),
						"selector"        => ".iksm-term__link",
						"custom_settings" => [ StylesSettingsTypes::$transition ],
						"defaults"        => [
							"color"           => [ "r" => "0", "g" => "0", "b" => "0", "a" => "1" ],
							"font_size"       => [ "number" => 15, "postfix" => "px" ],
							"line_height"     => [ "number" => 15, "postfix" => "px" ],
							"font_weight"     => "400",
							"padding"         => [
								"top"    => [ "number" => 5 ],
								"right"  => [ "number" => 15 ],
								"bottom" => [ "number" => 5 ],
								"left"   => [ "number" => 15 ],
							],
							"text_decoration" => "none",
						],
					],
					"hover"         => [
						"title"    => Utils::t( "Hover" ),
						"selector" => ".iksm-term__inner:hover .iksm-term__link",
						"defaults" => [
							"color" => [ "r" => "50", "g" => "50", "b" => "50", "a" => "1" ],
						],
					],
					"current"       => [
						"title"    => Utils::t( "Current" ),
						"selector" => ".iksm-term--current > .iksm-term__inner .iksm-term__link",
					],
					"child"         => [
						"title"    => Utils::t( "Child" ),
						"selector" => ".iksm-term--child .iksm-term__inner .iksm-term__link",
					],
					"child_hover"   => [
						"title"    => Utils::t( "Child & Hover" ),
						"selector" => ".iksm-term--child .iksm-term__inner:hover .iksm-term__link",
					],
					"child_current" => [
						"title"    => Utils::t( "Child & Current" ),
						"selector" => ".iksm-term--child.iksm-term--current > .iksm-term__inner .iksm-term__link",
					],
				],
			],
			"toggle"         => [
				"title"              => Utils::t( "Toggle" ),
				"type"               => "toggle",
				"highlight_selector" => ".iksm-term__toggle",
				"control_setting"    => $toggle_settings["display_toggle"],
				"states"             => [
					"main"                  => [
						"title"               => Utils::t( "Main" ),
						"selector"            => ".iksm-term__toggle",
						"custom_settings"     => [ StylesSettingsTypes::$transition ],
						"defaults"            => [
							"font_size"   => [ "number" => 22, "postfix" => "px" ],
							"width"       => [ "number" => 40, "postfix" => "px" ],
							"color"       => [ "r" => "133", "g" => "133", "b" => "133", "a" => "1" ],
							"height_type" => "full",
						],
						"additional_settings" => $toggle_additional_settings,
					],
					"hover"                 => [
						"title"    => Utils::t( "Hover" ),
						"selector" => ".iksm-term__toggle:hover",
						"defaults" => [
							"color" => [ "r" => "0", "g" => "0", "b" => "0", "a" => "1" ],
						],
					],
					"term_container_hover"  => [
						"title"    => Utils::t( "Term hover" ),
						"selector" => ".iksm-term__inner:hover .iksm-term__toggle",
					],
					"current"               => [
						"title"    => Utils::t( "Term current" ),
						"selector" => ".iksm-term--current > .iksm-term__inner .iksm-term__toggle",
					],
					"child"                 => [
						"title"    => Utils::t( "Term child" ),
						"selector" => ".iksm-term--child .iksm-term__inner .iksm-term__toggle",
					],
					"child_hover"           => [
						"title"    => Utils::t( "Term child & icon hover" ),
						"selector" => ".iksm-term--child .iksm-term__inner .iksm-term__toggle:hover",
					],
					"child_container_hover" => [
						"title"    => Utils::t( "Term child & term hover" ),
						"selector" => ".iksm-term--child .iksm-term__inner:hover .iksm-term__toggle",
					],
					"child_current"         => [
						"title"    => Utils::t( "Term child & term current" ),
						"selector" => ".iksm-term--child.iksm-term--current > .iksm-term__inner .iksm-term__toggle",
					],
				],
			],
			"image"          => [
				"title"              => Utils::t( "Image" ),
				"type"               => "image",
				"highlight_selector" => ".iksm-term__image-container",
				"control_setting"    => $image_settings["display_term_image"],
				"hidden_notice"      => Utils::t( "Image can only be used for menus with source \"Taxonomy\"" ),
				"states"             => [
					"main"                 => [
						"title"               => Utils::t( "Main" ),
						"selector"            => ".iksm-term__image-container",
						"custom_selectors"    => [
							StylesSettingsTypes::$background_size["key"]     => ".iksm-term__image",
							StylesSettingsTypes::$background_position["key"] => ".iksm-term__image",
							StylesSettingsTypes::$background_repeat["key"]   => ".iksm-term__image",
						],
						"custom_settings"     => [ StylesSettingsTypes::$transition ],
						"defaults"            => [
							"width"  => [ "number" => 30, "postfix" => "px" ],
							"height" => [ "number" => 30, "postfix" => "px" ],
							"margin" => [
								"right" => [ "number" => 15 ],
							],
						],
						"additional_settings" => $image_additional_settings,
					],
					"hover"                => [
						"title"    => Utils::t( "Hover" ),
						"selector" => ".iksm-term__image-container:hover",
					],
					"term_container_hover" => [
						"title"    => Utils::t( "Term hover" ),
						"selector" => ".iksm-term__inner:hover .iksm-term__image-container",
					],
					"current"              => [
						"title"    => Utils::t( "Term current" ),
						"selector" => ".iksm-term--current > .iksm-term__inner .iksm-term__image-container",
					],
					"child"                => [
						"title"    => Utils::t( "Term child" ),
						"selector" => ".iksm-term--child .iksm-term__inner .iksm-term__image-container",
					],
					"child_hover"          => [
						"title"    => Utils::t( "Term child & image hover" ),
						"selector" => ".iksm-term--child .iksm-term__inner .iksm-term__image-container:hover",
					],
					"child_current"        => [
						"title"    => Utils::t( "Term child & term current" ),
						"selector" => ".iksm-term--child.iksm-term--current > .iksm-term__inner .iksm-term__image-container",
					],
				],
			],
			"posts_count"    => [
				"pro_only"           => true,
				"title"              => Utils::t( "Posts count" ),
				"type"               => "posts_count",
				"highlight_selector" => ".iksm-term__posts-count",
				"control_setting"    => $posts_count_settings["display_posts_count"],
				"depends_on"         => "source",
				"show_if"            => "taxonomy",
				"hidden_notice"      => Utils::t( "Posts count can only be used for menus with source \"Taxonomy\"" ),
				"states"             => [
					"main"                  => [
						"title"               => Utils::t( "Main" ),
						"selector"            => ".iksm-term__posts-count",
						"custom_settings"     => [ StylesSettingsTypes::$transition ],
						"defaults"            => [
							"font_size"   => [ "number" => 16, "postfix" => "px" ],
							"font_weight" => "400",
							"color"       => [ "r" => "133", "g" => "133", "b" => "133", "a" => "1" ],
							"margin"      => [
								"left" => [ "number" => 12 ],
							],
						],
						"additional_settings" => $posts_count_additional_settings,
					],
					"term_container_hover"  => [
						"title"    => Utils::t( "Term hover" ),
						"selector" => ".iksm-term__inner:hover .iksm-term__posts-count",
					],
					"current"               => [
						"title"    => Utils::t( "Term current" ),
						"selector" => ".iksm-term--current > .iksm-term__inner .iksm-term__posts-count",
					],
					"child"                 => [
						"title"    => Utils::t( "Term child" ),
						"selector" => ".iksm-term--child .iksm-term__inner .iksm-term__posts-count",
					],
					"child_container_hover" => [
						"title"    => Utils::t( "Term child & term hover" ),
						"selector" => ".iksm-term--child .iksm-term__inner:hover .iksm-term__posts-count",
					],
					"child_current"         => [
						"title"    => Utils::t( "Term child & term current" ),
						"selector" => ".iksm-term--child.iksm-term--current > .iksm-term__inner .iksm-term__posts-count",
					],
				],
			]
		];

		if ( IKSM\iks_menu_fs()->is_plan__premium_only( "pro", true ) ) {
			unset( $settings["posts_count"]["pro_only"] );
		}

		return $settings;
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
			foreach ( $tab["states"] as $state_key => $state ) {
				$settings = array_merge( $settings, $state["settings"] );
			}
		}

		return $settings;
	}

}