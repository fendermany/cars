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

if ( ! function_exists( 'iks_autoloader' ) ) {

	function iks_autoloader( $class, $namespace, $dir ) {
		// project-specific namespace prefix
		$prefix      = $namespace;
		$core_prefix = 'IksStudio\IKSM_CORE';

		// base directory for the namespace prefix
		$base_dir = $dir . '/includes/';

		$is_core_file  = strncmp( $core_prefix, $class, strlen( $core_prefix ) ) === 0;
		$is_local_file = strncmp( $prefix, $class, strlen( $prefix ) ) === 0;

		// does the class use the namespace prefix?
		if ( ! $is_core_file && ! $is_local_file ) {
			// no, move to the next registered autoloader
			return;
		}

		$result_prefix = $is_core_file ? $core_prefix : $prefix;
		if ( $is_core_file ) {
			$base_dir .= 'core/';
		}

		// get the relative class name
		$relative_class = substr( $class, strlen( $result_prefix ) );

		// replace the namespace prefix with the base directory, replace namespace
		// separators with directory separators in the relative class name, append
		// with .php
		$file = $base_dir . str_replace( '\\', '/', $relative_class ) . '.php';

		// if the file exists, require it
		if ( file_exists( $file ) ) {
			require $file;
		}
	}

}