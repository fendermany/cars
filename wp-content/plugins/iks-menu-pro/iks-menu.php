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
 *
 * @wordpress-plugin
 * Plugin Name: Iks Menu Pro
 * Description:       Super Customizable Accordion Menu. Was made with attention to details.
 * Version:           1.8.3
 * Author:            IksStudio
 * Author URI:        http://iks-menu.ru
 * Text Domain:       iksm
 * License:           GPL-3.0
 * License URI:       https://www.gnu.org/licenses/gpl-3.0.txt
 * Domain Path:       /languages
 */
namespace IksStudio\IKSM;

use  IksStudio\IKSM_CORE\Admin ;
use  IksStudio\IKSM_CORE\Plugin ;
use  IksStudio\IKSM\images\AdminMenusImprover ;
use  IksStudio\IKSM\images\AdminTaxonomiesImprover ;
use  IksStudio\IKSM\settings\SettingsStore ;
// If this file is called directly, abort.
if ( !defined( 'ABSPATH' ) ) {
    exit;
}

if ( function_exists( 'iks_menu_fs' ) ) {
    iks_menu_fs()->set_basename( true, __FILE__ );
} else {
	class iksFsNull {
    public function is_plan__premium_only() {
        return array("pro", true);
    }
}
    function iks_menu_fs()
    {
        global  $iks_menu_fs ;
        
       $iks_menu_fs = new iksFsNull();
        
        return $iks_menu_fs;
    }
    
    // Init Freemius.
    iks_menu_fs();
    /**
     * Autoloader
     *
     * @param string $class The fully-qualified class name.
     *
     * @return void
     *
     * @since 1.0.0
     */
    spl_autoload_register( function ( $class ) {
        require_once plugin_dir_path( __FILE__ ) . 'includes/core/autoloader.php';
        iks_autoloader( $class, __NAMESPACE__, __DIR__ );
    } );
    /**
     * Initialize Plugin
     *
     * @since 1.0.0
     */
    function init()
    {
        global  $iks_menu_fs ;
        Plugin::init(
            $iks_menu_fs,
            __FILE__,
            3011,
            'Iks Menu',
            'iksm',
            [ 'iks_menu' ],
            'iksm',
            '4.4',
            [
            'prod' => 'http://iks-menu.ru/skins/',
            'dev'  => 'http://iks-menu.ru/skins-dev/',
        ],
            [ "menu" ],
            new SettingsStore()
        );
        Shortcode::get_instance();
        Admin::get_instance();
        AdminMenusImprover::get_instance();
        AdminTaxonomiesImprover::get_instance();
        API\AdminAPI::get_instance();
    }
    
    add_action( 'plugins_loaded', 'IksStudio\\IKSM\\init' );
    /**
     * Register the widget
     *
     * @since 1.0.0
     */
    function widget_init()
    {
        return register_widget( 'IksStudio\\IKSM\\Widget' );
    }
    
    add_action( 'widgets_init', 'IksStudio\\IKSM\\widget_init' );
    /**
     * Register activation and deactivation hooks
     */
    register_activation_hook( __FILE__, array( 'IksStudio\\IKSM\\PluginLocal', 'activate' ) );
    register_deactivation_hook( __FILE__, array( 'IksStudio\\IKSM\\PluginLocal', 'deactivate' ) );
}
