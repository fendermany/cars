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

class StylesRenderer {

	/**
	 * @var string|null
	 */
	private $post_settings = null;

	/**
	 * @var integer|null
	 */
	private $post_id = null;

	/**
	 * StylesRenderer constructor.
	 *
	 * @param $post_settings
	 * @param $post_id
	 */
	public function __construct( $post_settings, $post_id ) {
		$this->post_settings = $post_settings;
		$this->post_id       = $post_id;
	}

	public function render( $settings ) {
		// Generating styles
		$styles_generator = new StylesGenerator( $this->post_id, $this->post_settings, $settings );
		$styles           = $styles_generator->generate_styles();

		// Generating styles copier script
		return $this->generate_styles_copier_script( $styles );
	}

	/**
	 * Inserts styles to element on page
	 *
	 * @param $styles
	 *
	 * @return string
	 */
	private function generate_styles_copier_script( $styles ) {
		$script_id         = uniqid( 'styles-copier-' );
		$styles_element_id = Plugin::$slug . '-dynamic-style';

		return '<script id="' . $script_id . '">
			// Finding styles element
	        var element = document.getElementById("' . $styles_element_id . '");
	        if (!element) { // If no element (Cache plugins can remove tag), then creating a new one
                element = document.createElement(\'style\');
                var head = document.getElementsByTagName("head")
                if (head && head[0]) {
                	head[0].appendChild(element);
                } else {
                  	console.warn("' . Plugin::$name . ' | Error while printing styles. Please contact technical support.");
                }
	        }
			// Copying styles to <styles> tag
	        element.innerHTML += "' . $styles . '";
	        // Removing this script
            var this_script = document.getElementById("' . $script_id . '");
            if (this_script) { // Cache plugins can remove tag
	            this_script.outerHTML = "";
	            if (this_script.parentNode) {
	                this_script.parentNode.removeChild(this_script);
	            }
            }
        </script>';
	}
}