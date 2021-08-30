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
use IksStudio\IKSM\utils\UtilsLocal;
use WP_Screen;

require_once Plugin::$dir_name . '/vendor/menu-item-custom-fields/menu-item-custom-fields.php';

class AdminMenusImprover {
	/**
	 * Class instance.
	 *
	 * @var AdminTaxonomiesImprover instance
	 */
	protected static $instance = false;

	/**
	 * @var array
	 */
	private static $fields = [];

	/**
	 * Get class instance
	 */
	public static function get_instance() {
		if ( ! self::$instance ) {
			self::$instance = new self();
			self::$instance->do_hooks();
		}

		return self::$instance;
	}

	private function do_hooks() {
		$is_enabled = UtilsLocal::get_images_support_custom_menus();

		if ( $is_enabled ) {
			add_action( 'init', array( $this, 'setup' ) );
			// Scripts
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ), 999 );
		}
	}

	public function setup() {
		if ( ! is_admin() ) {
			return;
		}
		$this->init_fields();

		if ( ! version_compare( get_bloginfo( 'version' ), '5.4', '>=' ) ) {
			add_filter( 'wp_edit_nav_menu_walker', function () {
				return 'Menu_Item_Custom_Fields_Walker';
			}, 99 );
		}

		add_filter( 'wp_nav_menu_item_custom_fields', array( $this, 'render_fields' ), 10, 4 );
		add_filter( 'manage_nav-menus_columns', array( $this, 'manage_columns' ), 99 );
		add_action( 'wp_update_nav_menu_item', array( $this, 'save_fields' ), 10, 3 );
	}

	public function enqueue_admin_scripts() {
		$screen     = get_current_screen();
		$do_enqueue = $screen->id === "nav-menus";

		if ( $do_enqueue ) {
			// Enabling wp.media for Image picker
			wp_enqueue_media();

			// Include image picker
			Utils::enqueue_public_script( "image-picker", "ImagePickerLocalization", [
				"pickerSelector"       => ".iksm-image-picker",
				"uploadButtonSelector" => ".upload-image-button",
				"removeButtonSelector" => ".remove-image-button",
				"placeholderURL"       => esc_js( Utils::get_placeholder_image() ),
				"mediaTitle"           => Utils::t( "Choose an image" ),
				"mediaButtonTitle"     => Utils::t( "Use image" )
			] );
		}
	}

	static function init_fields() {
		self::$fields = [
			'image' => [
				'label'         => ImproverUtils::get_image_picker_label(),
				'type'          => 'image',
				'is_full_width' => true
			],
			//			'badge' => [
			//				'label'         => "Badge",
			//				'is_full_width' => true
			//			],
		];
	}

	/**
	 * Generator for Id of item meta
	 */
	public static function get_item_meta_key( $field_key ) {
		return Plugin::$slug . '_menu_item_' . $field_key;
	}

	/**
	 * Generator for Id of form field name
	 */
	public static function get_form_field_name( $field_key ) {
		return Plugin::$slug . '_' . $field_key;
	}

	/**
	 * Get current field value with definite name for definite menu item
	 */
	static function get_field_value( $menu_item_id, $field_key ) {
		return get_post_meta( $menu_item_id, self::get_item_meta_key( $field_key ), true );
	}

	/**
	 * Print fields
	 *
	 * @wp_hook action wp_nav_menu_item_custom_fields
	 *
	 * @param object $item Menu item data object.
	 * @param int $depth Nav menu depth.
	 * @param array $args Menu item args.
	 * @param int $id Nav menu ID.
	 *
	 * @return string Form fields
	 */
	public static function render_fields( $id, $item, $depth, $args ) {
		foreach ( self::$fields as $key => $field ) {
			echo self::generate_field_html(
				$item->ID,
				self::get_form_field_name( $key ),
				self::get_field_value( $item->ID, $key ),
				$field
			);
		}
	}

	/**
	 * Generate single field html
	 * @return string
	 */
	private static function generate_field_html( $id, $name, $value, $field ) {
		$is_full_width = isset( $field['is_full_width'] ) ? $field['is_full_width'] : false;
		$label         = isset( $field['label'] ) ? $field['label'] : '';
		$type          = isset( $field['type'] ) ? $field['type'] : 'text';
		$class         = $is_full_width ? 'wide' : 'thin';
		$input_name    = "{$name}[{$id}]";

		if ( $type === "image" ) {
			$input = ImproverUtils::render_image_picker( $input_name, $value );
		} else {
			$input = "
                <input
                    type='{$type}'
                    id='edit-menu-item-{$name}-{$id}'
                    class='widefat code edit-menu-item-{$name}'
                    name='{$input_name}'
                    value='{$value}'
                />
				";
		}

		return "
			<div class='field-{$name} description-{$class}'>
				<p class='description'>
					<label>{$label}</label>
				</p>
				{$input}
			</div>
			";
	}

	/**
	 * Add our field to the screen options toggle
	 *
	 * @wp_hook action manage_nav-menus_columns
	 * @link    http://codex.wordpress.org/Plugin_API/Filter_Reference/manage_posts_columns
	 *
	 * @param array $columns Menu item columns
	 *
	 * @return array
	 */
	public static function manage_columns( $columns ) {
		$columns[ self::get_form_field_name( 'image' ) ] = ImproverUtils::get_image_picker_label();

		return $columns;
	}

	/**
	 * Save menu item's icons metadata
	 *
	 * @wp_hook action wp_update_nav_menu_item
	 * @link    http://codex.wordpress.org/Plugin_API/Action_Reference/wp_update_nav_menu_item
	 *
	 * @param int $menu_id Nav menu ID.
	 * @param int $menu_item_id Menu item ID.
	 * @param array $menu_item_args Menu item data.
	 */
	public static function save_fields( $menu_id, $menu_item_id, $menu_item_args ) {
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			return;
		}

		$screen = get_current_screen();
		if ( ! $screen instanceof WP_Screen || 'nav-menus' !== $screen->id ) {
			return;
		}

		check_admin_referer( 'update-nav_menu', 'update-nav-menu-nonce' );

		foreach ( self::$fields as $key => $field ) {
			$form_field_name = self::get_form_field_name( $key );

			if ( isset( $_POST[ $form_field_name ][ $menu_item_id ] ) ) {
				$item_meta_key = self::get_item_meta_key( $key );
				$value         = stripslashes( $_POST[ $form_field_name ][ $menu_item_id ] );
				update_post_meta( $menu_item_id, $item_meta_key, $value );
			}
		}
	}
}