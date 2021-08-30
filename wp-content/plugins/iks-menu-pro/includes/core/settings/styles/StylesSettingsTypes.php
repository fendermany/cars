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

use IksStudio\IKSM_CORE\settings\SettingsTypes;
use IksStudio\IKSM_CORE\utils\Utils;

class StylesSettingsTypes {

	static $font_size = [];
	static $line_height = [];
	static $font_weight = [];
	static $text_align = [];
	static $text_transform = [];
	static $text_decoration = [];
	static $width = [];
	static $max_width = [];
	static $height = [];
	static $min_height = [];
	static $color = [];
	static $background_color = [];
	static $margin = [];
	static $padding = [];
	static $border_radius = [];
	static $box_shadow = [];
	static $transition = [];
	static $background_size = [];
	static $background_position = [];
	static $background_repeat = [];
	static $abs_pos = [];
	static $custom = [];

	static function init() {
		self::$font_size           = [
			"key"      => "font_size",
			"type"     => SettingsTypes::$size,
			"label"    => Utils::t( "Size" ),
			"property" => "font-size",
			"input"    => [
				"min" => 1,
				"max" => 200
			]
		];
		self::$line_height         = [
			"key"      => "line_height",
			"type"     => SettingsTypes::$size,
			"label"    => Utils::t( "Line height" ),
			"property" => 'line-height',
			"input"    => [
				"min" => 1,
				"max" => 200
			]
		];
		self::$font_weight         = [
			"key"      => "font_weight",
			"type"     => SettingsTypes::$select,
			"label"    => Utils::t( "Font weight" ),
			"property" => 'font-weight',
			"options"  => StylesSettingsTypes::get_font_weight_options()
		];
		self::$text_align          = [
			"key"      => "text_align",
			"type"     => SettingsTypes::$text_align,
			"label"    => Utils::t( "Text align" ),
			"property" => "text-align",
		];
		self::$text_transform      = [
			"key"      => "text_transform",
			"type"     => SettingsTypes::$text_transform,
			"label"    => Utils::t( "Text transform" ),
			"property" => 'text-transform',
		];
		self::$text_decoration     = [
			"key"      => "text_decoration",
			"type"     => SettingsTypes::$select,
			"label"    => Utils::t( "Text decoration" ),
			"property" => 'text-decoration',
			"options"  => StylesSettingsTypes::get_text_decoration_options()
		];
		self::$width               = [
			"key"      => "width",
			"type"     => SettingsTypes::$size,
			"label"    => Utils::t( "Width" ),
			"property" => "width",
			"input"    => [
				"min" => 1,
				"max" => 1000
			]
		];
		self::$max_width           = [
			"key"      => "max_width",
			"type"     => SettingsTypes::$size,
			"label"    => Utils::t( "Max width" ),
			"property" => "max-width",
			"input"    => [
				"min" => 1,
				"max" => 1000
			]
		];
		self::$height              = [
			"key"      => "height",
			"type"     => SettingsTypes::$size,
			"label"    => Utils::t( "Height" ),
			"property" => "height",
			"input"    => [
				"min" => 1,
				"max" => 300
			]
		];
		self::$min_height          = [
			"key"      => "min_height",
			"type"     => SettingsTypes::$size,
			"label"    => Utils::t( "Minimal height" ),
			"property" => "min-height",
			"input"    => [
				"min" => 0,
				"max" => 1000
			],
		];
		self::$color               = [
			"key"      => "color",
			"type"     => SettingsTypes::$color,
			"label"    => Utils::t( "Color" ),
			"property" => "color",
		];
		self::$background_color    = [
			"key"      => "background_color",
			"type"     => SettingsTypes::$color,
			"label"    => Utils::t( "Background color" ),
			"property" => "background-color",
		];
		self::$margin              = [
			"key"      => "margin",
			"type"     => SettingsTypes::$quadruple_size,
			"label"    => Utils::t( "Margin" ),
			"property" => "margin",
		];
		self::$padding             = [
			"key"      => "padding",
			"type"     => SettingsTypes::$quadruple_size,
			"label"    => Utils::t( "Padding" ),
			"property" => "padding",
		];
		self::$border_radius       = [
			"key"      => "border_radius",
			"type"     => SettingsTypes::$quadruple_size,
			"label"    => Utils::t( "Border Radius" ),
			"property" => "border-radius",
		];
		self::$box_shadow          = [
			"key"      => "box_shadow",
			"type"     => SettingsTypes::$text,
			"label"    => Utils::t( "Box Shadow" ),
			"property" => "box-shadow",
		];
		self::$transition          = [
			"key"            => "transition",
			"type"           => SettingsTypes::$number,
			"label"          => Utils::t( "Transition (in ms)" ),
			"description"    => Utils::t( "Specifies how many milliseconds the transition effect takes to complete" ),
			"property"       => "transition",
			"input"          => [
				"min" => 0,
				"max" => 10000
			],
			"value_prefix"   => "all ",
			"value_postfix"  => "ms",
			"default"        => 400,
			"not_appearance" => true,
		];
		self::$background_size     = [
			"key"            => "background_size",
			"type"           => SettingsTypes::$select,
			"options"        => [
				[ "id" => "contain", "label" => Utils::t( "Contain" ) ],
				[ "id" => "cover", "label" => Utils::t( "Cover" ) ],
			],
			"label"          => Utils::t( "Image type" ),
			"property"       => "background-size",
			"not_appearance" => true,
		];
		self::$background_position = [
			"key"            => "background_position",
			"type"           => SettingsTypes::$select,
			"options"        => [
				[ "id" => "center", "label" => Utils::t( "Center" ) ],
				[ "id" => "top", "label" => Utils::t( "Top" ) ],
				[ "id" => "bottom", "label" => Utils::t( "Bottom" ) ],
				[ "id" => "left", "label" => Utils::t( "Left" ) ],
				[ "id" => "right", "label" => Utils::t( "Right" ) ],
			],
			"label"          => Utils::t( "Position" ),
			"property"       => "background-position",
			"not_appearance" => true,
		];
		self::$background_repeat   = [
			"key"            => "background_repeat",
			"type"           => SettingsTypes::$select,
			"options"        => [
				[ "id" => "no-repeat", "label" => Utils::t( "No repeat" ) ],
				[ "id" => "repeat", "label" => Utils::t( "Repeat" ) ],
				[ "id" => "repeat-x", "label" => Utils::t( "Repeat X" ) ],
				[ "id" => "repeat-y", "label" => Utils::t( "Repeat Y" ) ],
			],
			"label"          => Utils::t( "Repeat" ),
			"property"       => "background-repeat",
			"not_appearance" => true,
		];
		self::$abs_pos             = [
			"key"         => "abs_pos",
			"type"        => SettingsTypes::$abs_pos,
			"label"       => Utils::t( "Absolute position" ),
			"description" => Utils::t( "Set \"0\" to stretch an element to the edge." ),
			"property"    => "__abs_pos",
		];
		self::$custom              = [
			"key"      => "custom",
			"type"     => SettingsTypes::$textarea,
			"label"    => Utils::t( "Custom styles" ),
			"property" => "__custom",
			"pro_only" => true
		];
	}

	private static function get_font_weight_options() {
		return [
			[ "id" => "100", "label" => "100" ],
			[ "id" => "200", "label" => "200" ],
			[ "id" => "300", "label" => "300" ],
			[ "id" => "400", "label" => "400 (" . Utils::t( "Normal" ) . ")" ],
			[ "id" => "500", "label" => "500" ],
			[ "id" => "600", "label" => "600" ],
			[ "id" => "700", "label" => "700 (" . Utils::t( "Bold" ) . ")" ],
			[ "id" => "800", "label" => "800" ],
			[ "id" => "900", "label" => "900" ],
			[ "id" => "lighter", "label" => Utils::t( "Lighter" ) ],
			[ "id" => "bolder", "label" => Utils::t( "Bolder" ) ],
			[ "id" => "inherit", "label" => Utils::t( "Inherit" ) ],
			[ "id" => "initial", "label" => Utils::t( "Initial" ) ],
			[ "id" => "unset", "label" => Utils::t( "Unset" ) ],
		];
	}

	private static function get_text_decoration_options() {
		return [
			[ "id" => "none", "label" => Utils::t( "None" ) ],
			[ "id" => "underline", "label" => Utils::t( "Underline" ) ],
			[ "id" => "line-through", "label" => Utils::t( "Line-through" ) ],
			[ "id" => "overline", "label" => Utils::t( "Overline" ) ],
			[ "id" => "blink", "label" => Utils::t( "Blink" ) ],
			[ "id" => "inherit", "label" => Utils::t( "Inherit" ) ],
		];
	}
}

StylesSettingsTypes::init();