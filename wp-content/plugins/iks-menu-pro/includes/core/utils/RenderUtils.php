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
 * @subpackage RenderUtils
 */
class RenderUtils {

	/**
	 * @param $sub_name string
	 *
	 * @return string
	 */
	static function gen_class( $sub_name ) {
		$prefix = Plugin::$slug;

		return "{$prefix}-{$sub_name}";
	}

	/**
	 * @param $sub_name string
	 *
	 * @return string
	 */
	static function gen_selector( $sub_name ) {
		return "." . self::gen_class( $sub_name );
	}

	/**
	 * @param $element_class string
	 * @param $sub_class string
	 *
	 * @return string
	 */
	static function sub_class( $element_class, $sub_class ) {
		return " {$element_class}--{$sub_class}";
	}

	/**
	 * @param $element_class string
	 * @param $inner_class string
	 *
	 * @return string
	 */
	static function inner_class( $element_class, $inner_class ) {
		if ( is_array( $inner_class ) ) {
			$inner_class = implode( '__', $inner_class );
		}

		return "{$element_class}__{$inner_class}";
	}

	static function generate_container_args( $post_id, $has_content, $additional_classes = "" ) {
		$prefix  = Plugin::$slug;
		$is_pro  = Plugin::$fs->is_plan__premium_only( "pro", true );

		$id      = "{$prefix}-{$post_id}";
		$classes = "{$prefix} {$prefix}-{$post_id} {$prefix}-container" . ( $has_content ? "" : " {$prefix}--no-data" ) . " " . $additional_classes;
		$data    = " data-id='{$post_id}' data-is-pro='{$is_pro}'";

		return "id='{$id}' class='{$classes}' {$data}";
	}

	static function render_data_args( $key_values ) {
		$output = '';
		foreach ( $key_values as $key => $value ) {
			$output .= " data-{$key}='{$value}'";
		}

		return '<div id="' . Plugin::$slug . '_data_args" class="data-args" ' . $output . '></div>';
	}

	static function render_no_data( $error ) {
		$output = '';
		ob_start();
		?>

        <div class="iks-no-data" style="padding: 30px; text-align: center; color: rgba(0, 0, 0, 0.5);">
            <div class='iks-no-data__heading' style="font-size: 18px;font-weight: bold;">
                <?php echo Plugin::$name; ?>
            </div>
            <div class='iks-no-data__sub-heading' style="font-size: 16px;margin-top: 10px;">
                <?php echo $error ? $error : Utils::t( "No data found" ); ?>
            </div>
        </div>

		<?php
		$output .= ob_get_contents();
		ob_end_clean();

		return $output;
	}
}