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

namespace IksStudio\IKSM_CORE\settings;

abstract class AbstractSettingsStore {

	/**
	 * @var array
	 */
	protected $all_settings = [];

	/**
	 * @var array
	 */
	protected $editor_settings = [];

	/**
	 * Initializes settings
	 */
	protected abstract function init_settings();

	/**
	 * Get all settings
	 *
	 * @return array
	 */
	public function get_settings() {
		if ( ! $this->all_settings ) {
			$this->init_settings();
		}

		return $this->all_settings;
	}

	/**
	 * Get settings for editor
	 *
	 * @return array
	 */
	public function get_editor_settings() {
		if ( ! $this->editor_settings ) {
			$this->init_settings();
		}

		return $this->editor_settings;
	}

}