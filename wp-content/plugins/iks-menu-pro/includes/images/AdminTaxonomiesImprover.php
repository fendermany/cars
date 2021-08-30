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

/**
 * AdminTaxonomiesImprover class.
 */
class AdminTaxonomiesImprover {

	/**
	 * Class instance.
	 *
	 * @var AdminTaxonomiesImprover instance
	 */
	protected static $instance = false;

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

	/**
	 * Generator for Id of item meta
	 */
	public static function get_meta_key() {
		return Plugin::$slug . "_image_id";
	}

	public static function get_taxonomies_ids() {
		return UtilsLocal::get_images_support_taxonomies();
	}

	/**
	 * Handle WP actions and filters.
	 */
	public function do_hooks() {
		$taxonomies = AdminTaxonomiesImprover::get_taxonomies_ids();

		if ( is_array( $taxonomies ) && ! empty( $taxonomies ) ) {

			foreach ( $taxonomies as $taxonomy ) {
				// Add form inputs
				add_action( "{$taxonomy}_edit_form_fields", array( $this, "edit_term_fields" ) );
				add_action( "{$taxonomy}_add_form_fields", array( $this, "add_term_fields" ) );

				// Add columns
				add_filter( "manage_edit-{$taxonomy}_columns", array( $this, "term_column_value_header" ) );
				add_filter( "manage_{$taxonomy}_custom_column", array( $this, "term_column_value" ), 10, 3 );
			}

			// Save actions
			add_action( "created_term", array( $this, "save_category_fields" ), 10, 3 );
			add_action( "edit_term", array( $this, "save_category_fields" ), 10, 3 );
			// Scripts
			add_action( "admin_enqueue_scripts", array( $this, "enqueue_admin_scripts" ), 999 );
		}
	}

	public function enqueue_admin_scripts() {
		$screen     = get_current_screen();
		$taxonomies = AdminTaxonomiesImprover::get_taxonomies_ids();
		$do_enqueue = in_array( $screen->taxonomy, $taxonomies );

		if ( $do_enqueue ) {
			// Custom CSS
			$custom_css = "
                table.wp-list-table .column-image {
                    width: 52px;
                    text-align: center;
                    white-space: nowrap;
                }
            ";

			$handle = Plugin::$slug . "_admin_taxonomies_improver_styles";
			wp_register_style( $handle, false );
			wp_enqueue_style( $handle );
			wp_add_inline_style( $handle, $custom_css );

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

	/**
	 * Category thumbnail fields.
	 */
	public function add_term_fields() {
		?>
        <div class="form-field term-image-wrap">
            <label><?php echo ImproverUtils::get_image_picker_label() ?></label>
			<?php echo ImproverUtils::render_image_picker( "iksm_term_image_id" ); ?>
            <script>
                jQuery(document).ajaxComplete(function (event, request, options) {
                    if (request && 4 === request.readyState && 200 === request.status
                        && options.data && 0 <= options.data.indexOf('action=add-tag')) {

                        var res = wpAjax.parseAjaxResponse(request.responseXML, 'ajax-response');
                        if (!res || res.errors) {
                            return;
                        }
                        // Clear Image fields on submit
                        jQuery('.iksm-image-picker img').attr('src', '<?php echo esc_js( Utils::get_placeholder_image() ); ?>');
                        jQuery('.iksm-image-picker input').val('');
                        jQuery('.iksm-image-picker .remove-image-button').hide();
                        return;
                    }
                });
            </script>
        </div>
		<?php
	}

	/**
	 * Edit category thumbnail field.
	 *
	 * @param mixed $term Term (category) being edited.
	 */
	public function edit_term_fields( $term ) {
		$thumbnail_id = get_term_meta( $term->term_id, self::get_meta_key(), true );
		?>
        <tr class="form-field term-image-wrap">
            <th scope="row" valign="top"><label><?php echo ImproverUtils::get_image_picker_label(); ?></label></th>
            <td>
				<?php echo ImproverUtils::render_image_picker( "iksm_term_image_id", $thumbnail_id ); ?>
            </td>
        </tr>
		<?php
	}

	/**
	 * Save category fields
	 *
	 * @param mixed $term_id Term ID being saved.
	 * @param mixed $tt_id Term taxonomy ID.
	 * @param string $taxonomy Taxonomy slug.
	 */
	public function save_category_fields( $term_id, $tt_id = '', $taxonomy = '' ) {
		$taxonomies = AdminTaxonomiesImprover::get_taxonomies_ids();

		if ( isset( $_POST["iksm_term_image_id"] ) && in_array( $taxonomy, $taxonomies ) ) { // WPCS: CSRF ok, input var ok.
			update_term_meta( $term_id, self::get_meta_key(), absint( $_POST["iksm_term_image_id"] ) ); // WPCS: CSRF ok, input var ok.
		}
	}

	/**
	 * Image column added to category admin.
	 *
	 * @param mixed $columns Columns array.
	 *
	 * @return array
	 */
	public function term_column_value_header( $columns ) {
		$new_columns = array();

		if ( isset( $columns["cb"] ) ) {
			$new_columns["cb"] = $columns["cb"];
			unset( $columns["cb"] );
		}

		$new_columns["image"] = ImproverUtils::get_image_picker_label();

		$columns           = array_merge( $new_columns, $columns );
		$columns["handle"] = "";

		return $columns;
	}

	/**
	 * Image column value added to category admin.
	 *
	 * @param string $columns Column HTML output.
	 * @param string $column Column name.
	 * @param int $id Term ID.
	 *
	 * @return string
	 */
	public function term_column_value( $columns, $column, $id ) {
		if ( "image" === $column ) {
			$thumbnail_id = get_term_meta( $id, self::get_meta_key(), true );
			$image        = false;

			if ( $thumbnail_id ) {
				$image = wp_get_attachment_thumb_url( $thumbnail_id );
				// Prevent esc_url from breaking spaces in urls for image embeds. Ref: https://core.trac.wordpress.org/ticket/23605 .
				$image = str_replace( " ", "%20", $image );
			}

			$columns .= ImproverUtils::render_image( $image, 48, "wp-post-image" );
		}
		if ( "handle" === $column ) {
			$esc_id  = esc_attr( $id );
			$columns .= "<input type='hidden' name='term_id' value='{$esc_id}'/>";
		}

		return $columns;
	}

}