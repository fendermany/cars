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

namespace IksStudio\IKSM;

use IksStudio\IKSM_CORE\Plugin;
use IksStudio\IKSM_CORE\render\StylesRenderer;
use IksStudio\IKSM_CORE\Shortcode_Base;
use IksStudio\IKSM_CORE\utils\PluginPostManager;
use IksStudio\IKSM_CORE\utils\Utils;
use IksStudio\IKSM\render\MenuRenderer;

/**
 * @subpackage Shortcode
 */
class Shortcode extends Shortcode_Base {

	/**
	 * Instance of this class.
	 *
	 * @since    1.0.0
	 *
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 * Shortcode constructor.
	 */
	protected function __construct() {
		parent::__construct( array( $this, 'shortcode' ) );
	}

	/**
	 * Main render function
	 *
	 * @param $atts
	 *
	 * @return string
	 */
	public function shortcode( $atts ) {
		$output  = '';
		$post_id = (int) Utils::get( $atts, "id" );

		$time   = 0;
		$memory = 0;

		if ( ! Utils::is_production() ) {
			$time   = microtime( true );
			$memory = memory_get_usage();
		}

		/*
		 * Render
		 */
		$post_manager  = new PluginPostManager( $post_id );
		$post_settings = $post_manager->get_settings();
		$renderer      = new MenuRenderer( $post_settings, $post_id );
		$output        .= $renderer->render();

		/*
		 * Styles
		 */
		// TODO: Maybe add action?
		$settings = Plugin::$SettingsStore->get_settings();
		$output   .= ( new StylesRenderer( $post_settings, $post_id ) )->render( $settings );

		if ( ! Utils::is_production() ) {
			$time   = microtime( true ) - $time;
			$memory = memory_get_usage() - $memory;
			$output .= sprintf( 'Time: %1$s sec', round( $time, 4 ) );
			$output .= sprintf( '<br>Memory: %1$s Mb', round( ( ( $memory / 1024 ) / 1024 ), 3 ) );
		}

		return $output;
	}

	/**
	 * Return an instance of this class.
	 *
	 * @return    object    A single instance of this class.
	 * @since     1.0.0
	 *
	 */
	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}
}
