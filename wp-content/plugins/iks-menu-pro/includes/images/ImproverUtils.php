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
 * @since     1.5.0
 */

namespace IksStudio\IKSM\images;

use IksStudio\IKSM_CORE\Plugin;
use IksStudio\IKSM_CORE\utils\Utils;

/**
 * ImproverUtils class.
 */
class ImproverUtils {

	public static function get_image_picker_label() {
		$plugin_name = Plugin::$name;
		return Utils::t( "Image ($plugin_name)", true );
	}

	public static function render_image( $url, $width, $class = "" ) {
		$width = $width . "px";
		$url   = esc_url( $url ? $url : Utils::get_placeholder_image() );
		$alt   = self::get_image_picker_label();

		return "<img src='$url' alt='$alt' style='width: $width; height: auto;' class='$class'/>";
	}

	public static function render_image_picker( $id, $thumbnail_id = null ) {
		$image_url = false;
		if ( $thumbnail_id ) {
			$image_url = wp_get_attachment_thumb_url( $thumbnail_id );
		}
		$render_image = self::render_image( $image_url, 60 );
		$value        = esc_attr( $thumbnail_id );

		return "
		<div class='iksm-image-picker'>
	        <div style='float: left; margin-right: 10px;'>
				{$render_image}
	        </div>
	        <div style='line-height: 60px;'>
	            <input
		            type='hidden'
		            id='{$id}'
	                name='{$id}'
	                value='{$value}'
	            />
	            <button type='button' class='upload-image-button button'>" . Utils::t( 'Upload image', true ) . "</button>
	            <button type='button' class='remove-image-button button'>" . Utils::t( 'Remove image', true ) . "</button>
	        </div>
	        <div class='clear'></div>
        </div>
		";
	}

}