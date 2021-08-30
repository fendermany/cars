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

namespace IksStudio\IKSM_CORE;

use IksStudio\IKSM_CORE\settings\AbstractSettingsStore;

/**
 * @subpackage Plugin
 */
class Plugin {

	/**
	 * Freemius instance
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	public static $fs = null;

	/**
	 * The plugin's __FILE__
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	public static $main_file = null;

	/**
	 * Localhost port for developing
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	public static $dev_port = null;

	/**
	 * The plugin's dirname(__FILE__)
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	public static $dir_name = null;

	/**
	 * The plugin's plugin_dir_url(__FILE__)
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	public static $dir_url = null;

	/**
	 * The main plugin's name
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	public static $name = null;

	/**
	 * The variable name is used as the text domain when internationalizing strings
	 * of text. Its value should match the Text Domain file header in the main
	 * plugin file.
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	public static $slug = null;

	/**
	 * Shortcodes
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	public static $shortcodes = null;

	/**
	 * Post type for plugin posts
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	public static $post_type = null;

	/**
	 * Version of plugin
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	public static $version = null;

	/**
	 * Minimum supported version of WP by plugin
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	public static $supported_WP_version = null;

	/**
	 * Servers for skins
	 *
	 * @since    1.0.0
	 *
	 * @var array
	 */
	public static $skins_servers = [
		'prod' => null,
		'dev'  => null,
	];

	/**
	 * Scripts names to enqueue at public
	 *
	 * @since    1.0.0
	 *
	 * @var array
	 */
	public static $public_scripts = null;

	/**
	 * Settings Store object
	 *
	 * @since    1.0.0
	 *
	 * @var      AbstractSettingsStore
	 */
	public static $SettingsStore = null;

	/**
	 * Initializes instance of this class.
	 *
	 * @since     1.0.0
	 *
	 */
	public static function init(
		$fs,
		$main_file,
		$dev_port,
		$name,
		$slug,
		$shortcodes,
		$post_type,
		$supported_WP_version,
		$skins_servers,
		$public_scripts,
		$SettingsStore
	) {
		if ( ! ! self::$name ) {
			return; // Already initialized
		}
		if ( ! function_exists( 'get_plugin_data' ) ) {
			require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		}

		self::$fs                   = $fs;
		self::$main_file            = $main_file;
		self::$dev_port             = $dev_port;
		self::$dir_name             = dirname( $main_file ) . '/';
		self::$dir_url              = plugin_dir_url( $main_file );
		self::$name                 = $name;
		self::$slug                 = $slug;
		self::$shortcodes           = $shortcodes;
		self::$post_type            = $post_type;
		self::$version              = get_plugin_data( $main_file )['Version'];
		self::$supported_WP_version = $supported_WP_version;
		self::$skins_servers        = $skins_servers;
		self::$public_scripts       = $public_scripts;
		self::$SettingsStore        = $SettingsStore;
	}
}
