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
use IksStudio\IKSM_CORE\utils\PluginPostManager;
use IksStudio\IKSM_CORE\utils\Utils;
use IksStudio\IKSM_CORE\Widget_Base;
use IksStudio\IKSM\render\MenuRenderer;

/**
 * @subpackage Widget
 */
class Widget extends Widget_Base {

	/**
	 * Initialize the widget
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		parent::__construct( Utils::t( "Accordion menu with terms from any taxonomy or custom menu" ) );
	}


	/**
	 * Main render function of the widget
	 *
	 * @param array $args
	 * @param array $instance
	 */
	public function widget( $args, $instance ) {
		$output  = '';
		$post_id = (int) Utils::get( $instance, "id" );

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
		$settings = Plugin::$SettingsStore->get_settings();
		echo ( new StylesRenderer( $post_settings, $post_id ) )->render( $settings );

		/*
		 * Widget output
		 */
		$this->render_widget_before( $args, $instance );
		echo $output;
		$this->render_widget_after( $args, $instance );
	}

}
