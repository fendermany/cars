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

namespace IksStudio\IKSM\render;

use IksStudio\IKSM_CORE\Plugin;
use IksStudio\IKSM_CORE\settings\SettingsManager;
use IksStudio\IKSM_CORE\settings\styles\StylesSettingsGenerator;
use IksStudio\IKSM_CORE\utils\RenderUtils;
use IksStudio\IKSM_CORE\utils\Utils;
use IksStudio\IKSM;
use IksStudio\IKSM\images\AdminMenusImprover;
use IksStudio\IKSM\images\AdminTaxonomiesImprover;
use IksStudio\IKSM\utils\UtilsLocal;

class MenuRenderer {

	/**
	 * @var string|null
	 */
	private $post_settings = null;

	/**
	 * @var integer|null
	 */
	private $post_id = null;

	/**
	 * @var string|null
	 */
	private $data_error = null;

	/**
	 * @var SettingsManager|null
	 */
	private $settings_manager = null;

	/**
	 * @var MenuDataManager|null
	 */
	private $data_manager = null;

	/**
	 * MenuRenderer constructor.
	 *
	 * @param $post_settings
	 * @param $post_id
	 */
	public function __construct( $post_settings, $post_id ) {
		$this->post_settings = $post_settings;
		$this->post_id       = $post_id;

		if ( ! is_array( $this->post_settings ) ) {
			// TODO: Do really check here?
			$this->data_error = Utils::t( "Menu not found" ) . " (id = {$post_id})";
		} else {
			$settings               = Plugin::$SettingsStore->get_settings();
			$this->settings_manager = new SettingsManager( $this->post_settings, $settings );
			$this->data_manager     = new MenuDataManager( $this->settings_manager );

			$this->data_error = $this->data_manager->get_error();
		}
	}

	public function render() {
		$has_terms      = ! empty( $this->get_data() );
		$container_args = RenderUtils::generate_container_args( $this->post_id, $has_terms );
		$source         = $this->data_manager->get_source();

		$output = "<div {$container_args} data-source='{$source}'>";
		if ( $has_terms ) {
			$parent_terms = $this->data_manager->get_term_children( $this->get_initial_parent(), true );

			$output .= "<div class='iksm-terms'>";
			$output .= $this->render_tree( $parent_terms );
			$output .= "</div>";
			$output .= $this->render_data_args();
		} else {
		    $output .= RenderUtils::render_no_data( $this->data_error );
		}
		$output .= "</div>";

		if ( ! Utils::is_production() ) {
			$output .= '<div id="' . Plugin::$slug . '_public"></div>';
		}

		return $output;
	}

	private function render_tree( $terms_ids, $level = 0, $is_expanded = false ) {
		$output = '';
		$level  = $level + 1;
		$count  = count( $terms_ids );

		if ( $count > 0 ) {
			// Classes
			$class   = "iksm-terms-tree";
			$classes = $class;
			$classes .= RenderUtils::sub_class( $class, "level-{$level}" );
			$classes .= RenderUtils::sub_class( $class, $level > 1 ? "children" : "parents" );
			$style   = $is_expanded ? "display: block;" : ""; // TODO: Redo with CSS classes

			$output .= "<div class='{$classes}' style='{$style}'>";
			$output .= '<div class="' . RenderUtils::inner_class( $class, "inner" ) . '">';
			foreach ( $terms_ids as $term_id ) {
				$output .= $this->render_item( $this->data_manager->find_term( $term_id ), $level );
			}
			$output .= '</div>';
			$output .= '</div>';
		}

		return $output;
	}

	private function render_item( $term, $level ) {
		$output         = '';
		$show_posts     = $this->settings_manager->get_value( "show_posts" );
		$has_children   = $this->data_manager->is_term_has_children( $term );
		$term_link_attr = $this->get_link_attr( $term, $has_children, $level );
		$is_current     = Utils::get( $term, "is_current" ) ||
                          // Element will be also current, only if not showing posts and it includes current post
		                  ! $show_posts && Utils::get( $term, "is_term_includes_post" );
		$is_post        = Utils::get( $term, "is_post" );
		// Classes
		$class   = "iksm-term";
		$classes = $class;
		$classes .= RenderUtils::sub_class( $class, "id-" . $term["id"] );
		$classes .= RenderUtils::sub_class( $class, $level > 1 ? "child" : "parent" );
		$classes .= $has_children ? RenderUtils::sub_class( $class, "has-children" ) : "";
		$classes .= $is_current ? RenderUtils::sub_class( $class, "current" ) : "";
		$classes .= $is_post ? RenderUtils::sub_class( $class, "is-post" ) : "";
		// Expansion
		if ( Utils::get( $term, "is_expanded" ) ) {
			$classes .= RenderUtils::sub_class( $class, "expanded" );
			if ( Utils::get( $term, "is_expanded_current" ) ) {
				$classes .= RenderUtils::sub_class( $class, "expanded-current" );
			} else if ( Utils::get( $term, "is_expanded_initial" ) ) {
				$classes .= RenderUtils::sub_class( $class, "expanded-initial" );
			}
		}

		ob_start();
		?>

        <div class="<?php echo $classes; ?>" data-id="<?php echo $term["id"]; ?>">
            <div class="<?php echo RenderUtils::inner_class( $class, "inner" ); ?>">
                <a class="<?php echo RenderUtils::inner_class( $class, "link" ); ?>" <?php echo $term_link_attr ?>>
					<?php
					$this->render_item_shifts( $level );
					$this->render_item_image( $term );
					$this->render_item_text( $term["title"] );
					if ( IKSM\iks_menu_fs()->is_plan__premium_only( "pro", true ) ) {
						$this->render_item_posts_count__premium_only( $term );
					}
					?>
                </a>
				<?php
				if ( $has_children ) {
					$this->render_item_toggle();
				}
				?>
            </div>
			<?php
			if ( $has_children ) {
				echo $this->render_tree( $term["children"], $level, $term["is_expanded"] );
			}
			?>
        </div>

		<?php
		$output .= ob_get_contents();
		ob_end_clean();

		return $output;
	}

	private function render_item_shifts( $level ) {
		$shift_size = $this->settings_manager->get_value(
			StylesSettingsGenerator::generate_setting_key( "term_container", "main", "level_shift" ),
			true /* Getting value as object */
		);

		if ( is_array( $shift_size ) && $level > 1 ) {
			$class = RenderUtils::inner_class( "iksm-term", "shifts" );
			?>
            <div
              class="<?php echo $class; ?>"
              style="width: <?php echo ( ( $level - 1 ) * $shift_size["number"] ) . $shift_size["postfix"] ?>"></div>
			<?php
		}
	}

	private function render_item_image( $term ) {
		$display = $this->get_setting_value( "display_term_image" );

		if ( $display ) {
			$image_id = null;
			$link     = null;

			$is_images_enabled = false;

			// Getting image
			if ( $this->data_manager->is_source_taxonomy() ) {
				if ( Utils::get( $term, "is_post" ) ) {
					$image_id = get_post_thumbnail_id( $term["id"] );;
				} else {
					$taxonomy = $this->data_manager->get_taxonomy();
					if ( UtilsLocal::taxonomy_has_images_support( $taxonomy ) ) {
						// WooCommerce has its own image
						$meta_key          = $taxonomy === Utils::$woo_taxonomy_id ? "thumbnail_id" : AdminTaxonomiesImprover::get_meta_key();
						$image_id          = get_term_meta( $term["id"], $meta_key, true );
						$is_images_enabled = true;
					}
				}
			} else if ( $this->data_manager->is_source_menu() ) {
				if ( UtilsLocal::custom_menus_has_images_support() ) {
					$image_id          = AdminMenusImprover::get_field_value( $term["id"], "image" );
					$is_images_enabled = true;
				}
			}

			if ( $image_id ) {
				$link = wp_get_attachment_image_url( $image_id, 'thumbnail' );
			}

			// Placeholder
			if ( ! $link && $is_images_enabled ) {
				$image_placeholder_type = $this->get_setting_value( "image_placeholder_type" );

				if ( $image_placeholder_type === "default" ) {
					$link = Utils::get_placeholder_image();
				}
			}

			if ( $link ) {
				?>
                <div class="<?php echo RenderUtils::inner_class( "iksm-term", "image-container" ); ?>">
                    <div
                      class="<?php echo RenderUtils::inner_class( "iksm-term", "image" ); ?>"
                      style="background-image: url(<?php echo $link; ?>)"></div>
                </div>
				<?php
			}
		}
	}

	private function render_item_text( $text ) {
		?>
        <span class="<?php echo RenderUtils::inner_class( "iksm-term", "text" ); ?>"><?php echo esc_html( $text ); ?></span>
		<?php
	}

	/**
	 * @since 1.4.0
	 */
	private function render_item_posts_count__premium_only( $term ) {
		if ( Utils::get( $term, "is_post" ) || ! $this->data_manager->is_source_taxonomy() ) {
			return;
		}

		$display = $this->get_setting_value( "display_posts_count" );

		if ( $display ) {
			$count = $term['posts_count'];

			if ( is_int( $count ) ) {
				$format = $this->get_setting_value( "posts_count_format" );
				if ( ! empty( $format ) ) {
					$count = str_replace( "%VALUE%", $count, $format );
				}

				?>
                <div class="<?php echo RenderUtils::inner_class( "iksm-term", "posts-count" ); ?>">
                    <span
	                    class="<?php echo RenderUtils::inner_class( "iksm-term", [ "posts-count", "text" ] ); ?>"
                    ><?php echo $count ?></span>
                </div>
				<?php
			}
		}
	}

	private function render_item_toggle() {
		$display = $this->get_setting_value( "display_toggle" );

		if ( $display ) {
			$icon     = $this->get_setting_value( "toggle_icon" );
			$is_class = true;

			if ( IKSM\iks_menu_fs()->is_plan__premium_only( "pro", true ) ) {
				if ( $icon === "custom_class" ) {
					$icon = $this->get_setting_value( "toggle_icon_custom_class" );
				} else if ( $icon === "custom_text" ) {
					$icon     = esc_html( $this->get_setting_value( "toggle_icon_custom_text" ) );
					$is_class = false;
				}
			}

			if ( $is_class ) {
				$icon = "<i class='$icon'></i>";
			}

			?>
            <div class="<?php echo RenderUtils::inner_class( "iksm-term", "toggle" ); ?>">
                <span class="<?php echo RenderUtils::inner_class( "iksm-term", [ "toggle", "inner" ] ); ?>"><?php
	                echo $icon;
                ?></span>
            </div>
			<?php
		}
	}

	private function render_data_args() {
		$keys = [
			"collapse_children_terms",
			"collapse_other_terms",
			"collapse_animation_duration",
			"expand_animation_duration",
		];

		if ( IKSM\iks_menu_fs()->is_plan__premium_only( "pro", true ) ) {
			array_push( $keys, "disable_parent_links_level" );
			array_push( $keys, "initial_expansion_disable_screen_width" );
		}

		$key_values = [];
		foreach ( $keys as $key ) {
			if ( $this->has_setting( $key ) ) {
				$key_values[ $key ] = $this->get_setting_value( $key );
			}
		}

		return RenderUtils::render_data_args( $key_values );
	}

	private function get_initial_parent() {
		if ( $this->data_manager->is_source_menu() ) {
			return 0;
		} else if ( $this->data_manager->is_source_taxonomy() ) {
			$parent = 0;
			if ( $this->has_setting( "child_of" ) ) {
				$parent = $this->get_setting_value( "child_of" );
			} else if ( $this->has_setting( "parent" ) ) {
				$parent = $this->get_setting_value( "parent" );
			}

			return $parent;
		}
	}

	private function get_link_attr( $term, $has_children, $level ) {
		if ( IKSM\iks_menu_fs()->is_plan__premium_only( "pro", true ) ) {
			$disable_parent_links_level = $this->get_setting_value( "disable_parent_links_level" );
			if ( $has_children && ( $disable_parent_links_level === 0 || $level <= $disable_parent_links_level ) ) {  // 0 means disable all levels
				return "data-toggle='1'";
			}
		}

		$link = $term["link"];
		$target = $term["target"] ? $term["target"] : "_self";
		return "href='$link' target='$target'";
	}

	/* Helpers */

	private function has_setting( $key ) {
		return isset( $this->post_settings[ $key ] );
	}

	private function get_setting_value( $key ) {
		return $this->settings_manager->get_value( $key );
	}

	/**
	 * @return array|null
	 */
	public function get_data() {
		return $this->data_manager ? $this->data_manager->get_data() : null;
	}

	/**
	 * @return array|null
	 */
	public function get_args() {
		return $this->data_manager ? $this->data_manager->get_args() : null;
	}

}