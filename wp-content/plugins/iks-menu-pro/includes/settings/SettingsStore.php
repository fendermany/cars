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

namespace IksStudio\IKSM\settings;

use IksStudio\IKSM_CORE\settings\AbstractSettingsStore;
use IksStudio\IKSM_CORE\utils\Utils;
use IksStudio\IKSM\settings\styles\StylesSettings;

class SettingsStore extends AbstractSettingsStore {

	/**
	 * Initializes settings
	 */
	public function init_settings() {
		$arguments_settings = new ArgumentsSettings();
		$menu_settings      = new MenuSettings();
		$styles_settings    = new StylesSettings();
		$display_settings   = new DisplaySettings(); // TODO: Pass display settings to the StylesSettings

		/*
		 * All settings
		 */
		$this->all_settings += $styles_settings->get_settings();
		$this->all_settings += $arguments_settings->get_settings();
		$this->all_settings += $menu_settings->get_settings();
		$this->all_settings += $display_settings->get_settings();

		/*
		 * Editor settings
		 */
		$this->editor_settings = [
			"data"   => [
				"title"    => Utils::t( "Data" ),
				"settings" => $arguments_settings->get_tab_settings()
			],
			"menu"   => [
				"title" => Utils::t( "Menu" ),
				"tabs"  => $menu_settings->get_tab_settings()
			],
			"styles" => [
				"title" => Utils::t( "Display" ),
				"tabs"  => $styles_settings->get_tab_settings()
			],
		];
	}

}