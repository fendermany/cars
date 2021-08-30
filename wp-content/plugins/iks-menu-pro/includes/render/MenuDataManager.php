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
use IksStudio\IKSM_CORE\utils\Utils;
use IksStudio\IKSM;
use WP_Post;

class MenuDataManager {

	/**
	 * @var array
	 */
	private $data = [];

	/**
	 * @var string
	 */
	private $error = null;

	/**
	 * @var array
	 */
	private $args = [];

	/**
	 * @var string
	 */
	private $source = null;

	/**
	 * @var SettingsManager|null
	 */
	private $settings_manager = null;

	/**
	 * @var string|null
	 */
	private $page_url = null;

	/**
	 * @var string|null
	 */
	private $permalink = null;

	/**
	 * @var object|null
	 */
	private $queried_object = null;

	/**
	 * MenuDataManager constructor.
	 *
	 * @param $settings_manager SettingsManager
	 */
	public function __construct( $settings_manager ) {
		$this->settings_manager = $settings_manager;
		$this->source           = $this->settings_manager->get_value( "source" );
		global $wp;
		$this->page_url       = Utils::url_with_slash( home_url( $wp->request ) );
		$this->permalink      = Utils::url_with_slash( get_permalink( get_the_ID() ) );
		$this->queried_object = get_queried_object();

		if ( $this->is_source_menu() ) {
			$this->get_WP_menu();
		} else if ( $this->is_source_taxonomy() ) {
			$this->get_WP_terms();
		}

		if ( ! $this->get_error() ) {
			$this->append_children();

			if ( IKSM\iks_menu_fs()->is_plan__premium_only( "pro", true ) ) {
				$this->process_expands__premium_only();
			}

			$this->process_includes();
		}
//		Utils::pretty_dump( $this->get_data() );
	}

	private function get_WP_menu() {
		$menu_id = $this->settings_manager->get_value( "menu_id" );

		if ( $menu_id ) {
			$this->args = [
				"output"     => ARRAY_A,
				'output_key' => 'menu_order',
			];

			$items = wp_get_nav_menu_items( $menu_id, $this->args );

			if ( is_array( $items ) ) {
				foreach ( $items as $key => $item ) {
					$id   = (int) $item->ID;
					$link = $item->url;

					$item_data = [
						"id"                    => $id,
						"object_id"             => (int) $item->object_id,
						"title"                 => $item->title,
						"link"                  => $link,
						"parent"                => (int) $item->menu_item_parent,
						"is_current"            => $this->is_current_item( $link, $id ),
						"is_term_includes_post" => false,
						"is_page_includes_post" => false,
						"is_expanded"           => false,
						"posts_count"           => null,
						"is_post"               => false,
						"target"                => $item->target,
					];

					if ( Plugin::$fs->is_plan__premium_only( "pro", true ) ) {
						if ( $this->settings_manager->get_value( 'expand_pages_includes_post' ) ) {
							$item_data["is_page_includes_post"] = $this->is_page_includes_post__premium_only( $item_data );
						}
					}

					$this->data[] = $item_data;
				}
			} else {
				$this->error = Utils::t( "Cannot get WordPress menu" ) . " (id = {$menu_id})";
			}
		}
	}

	private function get_WP_terms() {
		$taxonomy = $this->get_taxonomy();

		if ( $taxonomy ) {
			$this->args = [
				"taxonomy" => $taxonomy,
			];
			$this->save_args( [
				"orderby",
				"order",
				"hide_empty",
				"hierarchical",
				"include",
				"exclude",
				"search",
				"child_of",
				"parent",
				"childless",
			] );

			if ( version_compare( get_bloginfo( 'version' ), '4.5', '>=' ) ) {
				$items = get_terms( $this->args );
			} else {
				$items = get_terms( $taxonomy, $this->args );
			}

			if ( is_array( $items ) ) {
				$index      = 0;
				$show_posts = $this->settings_manager->get_value( "show_posts" );

				/* Posts */
				if ( $show_posts ) {
					$post_type = Utils::get_post_type_by_taxonomy( $taxonomy );
					$posts     = get_posts( [
						'post_type'        => $post_type,
						'posts_per_page'   => - 1,
						'orderby'          => 'date',
						'order'            => 'DESC',
						'tax_query'        => [
							[
								'taxonomy' => $taxonomy,
								'operator' => 'EXISTS',
							]
						],
						'suppress_filters' => false // [Fix] WPML compatibility
					] );

					$posts_by_terms = [];
					foreach ( $posts as $post ) {
						$terms = get_the_terms( $post, $taxonomy );
						if ( ! empty( $terms ) ) {
							foreach ( $terms as $term ) {
								if ( ! isset( $posts_by_terms[ $term->term_id ] ) ) {
									$posts_by_terms[ $term->term_id ] = [];
								}
								$posts_by_terms[ $term->term_id ][] = $post;
							}
						}
					}
				}

				foreach ( $items as $key => $item ) {
					$id   = (int) $item->term_id;
					$link = get_term_link( $id );

					$item_data = [
						"id"                    => $id,
						"title"                 => $item->name,
						"link"                  => $link,
						"parent"                => (int) $item->parent,
						"is_current"            => $this->is_current_item( $link, $id ),
						"is_term_includes_post" => $this->is_term_includes_post( $id ),
						"is_page_includes_post" => false,
						"index"                 => $index,
						"is_expanded"           => false,
						"posts_count"           => $item->count,
						"is_post"               => false,
						"target"                => null,
					];
					$index ++;

					$this->data[] = $item_data;

					if ( $show_posts ) {
						if ( isset( $posts_by_terms[ $id ] ) ) {
							$result_posts = $posts_by_terms[ $id ];
							foreach ( $result_posts as $post ) {
								$post_link    = get_permalink( $post->ID );
								$this->data[] = [
									"id"                    => $post->ID,
									"title"                 => $post->post_title,
									"link"                  => $post_link,
									"parent"                => $id,
									"is_current"            => $this->is_current_item( $post_link, $id ),
									"is_term_includes_post" => false,
									"is_page_includes_post" => false,
									"index"                 => $index,
									"is_expanded"           => false,
									"posts_count"           => false,
									"is_post"               => true,
									"target"                => null,
								];
								$index ++;
							}
						}
					}
				}

				/*
				 * Processing exclude without children
				 * @since 1.8.0
				 */
				$this->process_exclude_without_children();

			} else {
				$this->error = Utils::t( "Cannot get WordPress terms for taxonomy" ) . " ({$taxonomy})";
			}
		}

		/* Sorting items */
		usort( $this->data, function ( $a, $b ) {
			$aIsPost = Utils::get( $a, "is_post" ) === true;
			$bIsPost = Utils::get( $b, "is_post" ) === true;

			if ( $aIsPost === $bIsPost ) {
				// Saving order
				return Utils::get( $a, "index" ) - Utils::get( $b, "index" );
			} else {
				return $aIsPost - $bIsPost; // Terms first
				// TODO: New Setting for ordering posts and terms + posts
				//return $bIs - $aIs; // Posts first
			}
		} );

		// Removing unnecessary index
		foreach ( $this->data as $index => $item ) {
			unset( $this->data[ $index ]["index"] );
		}
	}

	/**
	 * Exclude without children
	 * @since 1.8.0
	 */
	private function process_exclude_without_children() {
		$exclude_without_children = Utils::split_numbers_by_comma_space(
			$this->settings_manager->get_value( "exclude_without_children" )
		);

		if ( ! empty( $exclude_without_children ) ) {
			foreach ( $this->data as $index => $item ) {
				$id           = $item["id"];
				$exclude_term = in_array( $id, $exclude_without_children );

				if ( $exclude_term ) {
					foreach ( $this->data as $inner_index => $inner_item ) {
						if ( (int) $inner_item["parent"] === $id ) { // For each child
							if ( $inner_item["is_post"] ) {
								// Removing posts
								unset( $this->data[ $inner_index ] );
							} else {
								// New parent for children - is not excluded parent of item
								$new_parent_id                        = $this->get_not_excluded_parent_id( $item, $exclude_without_children );
								$this->data[ $inner_index ]["parent"] = $new_parent_id;
							}
						}
					}
					unset( $this->data[ $index ] ); // Removing item
				}
			}
		}
	}

	/**
	 * Recursively finds parent of item, that is not in $excludes array
	 *
	 * @param array $item
	 * @param array $excludes
	 *
	 * @return int|mixed
	 * @since 1.8.0
	 */
	private function get_not_excluded_parent_id( $item, $excludes ) {
		$parent_id = $item["parent"];

		if ( in_array( $parent_id, $excludes ) ) {
			$parent = $this->find_term( $parent_id );
			if ( $parent ) {
				return $this->get_not_excluded_parent_id( $parent, $excludes );
			} else {
				return 0;
			}
		} else {
			return $parent_id;
		}
	}

	private function append_children() {
		foreach ( $this->data as $index => $item ) {
			$children = $this->get_term_children( $item["id"], true );

			$this->data[ $index ]["children"] = count( $children ) > 0 ? $children : null;
		}
	}

	private function process_expands__premium_only() {
		// Current term Expansion
		$expand_current_term        = ! ! $this->settings_manager->get_value( 'expand_current_term' );
		$expand_pages_includes_post = ! ! $this->settings_manager->get_value( 'expand_pages_includes_post' );
		// Initial Expansion
		$ie_type  = $this->settings_manager->get_value( 'initial_expansion_type' );
		$ie_ids   = $this->settings_manager->get_value( 'initial_expansion_ids' );
		$ie_level = $this->settings_manager->get_value( 'initial_expansion_level' );

		if ( $ie_type === "certain_ids" && ! empty( $ie_ids ) ) {
			$ie_ids = array_map( 'intval', explode( ",", $ie_ids ) );
		}

		foreach ( $this->data as $index => $item ) {
			if ( ! $item["is_expanded"] ) {
				if (
					$expand_current_term && ( $item["is_current"] || $item["is_term_includes_post"] ) ||
					$expand_pages_includes_post && $item["is_page_includes_post"]
				) {
					// Expand Current Term
					// [hint] If item includes current post, then do not expand it's children, only parents until this item
					$this->process_term_expand__premium_only( $item, $index, true, $item["is_term_includes_post"] );
				} else if ( $ie_type && $ie_type !== "none" ) {
					// Expand by Type
					switch ( $ie_type ) {
						case "all": // Expand All
							$this->process_term_expand__premium_only( $item, $index, false );
							break;
						case "certain_ids": // Expand with Certain IDs
							if ( ! empty( $ie_ids ) && in_array( $item["id"], $ie_ids ) ) {
								$this->process_term_expand__premium_only( $item, $index, false );
							}
							break;
						case "certain_levels": // Expand with Certain Levels
							if ( is_numeric( $ie_level ) ) {
								$level = $this->get_term_level( $item );
								if ( $level <= $ie_level ) {
									$this->process_term_expand__premium_only( $item, $index, false );
								}
							}
							break;
					}
				}
			}
		}
	}

	private function process_term_expand__premium_only( $item, $item_index, $is_current, $expand_only_parents = false ) {
		if ( ! $item["is_expanded"] ) {
			if ( ! $expand_only_parents && $this->is_term_has_children( $item ) ) {
				$key = $is_current ? "is_expanded_current" : "is_expanded_initial";
				// This item will be expanded
				$this->data[ $item_index ]["is_expanded"] = true;
				$this->data[ $item_index ][ $key ]        = true;
			}
			// Finding item's parent
			$parent       = $this->find_term( $item["parent"] );
			$parent_index = $this->find_term( $item["parent"], true );
			if ( $parent ) {
				// Recursively expand item's parent
				$this->process_term_expand__premium_only( $parent, $parent_index, $is_current, false );
			}
		}
	}

	/**
	 * Checks "include" setting: If the term doesn't have a retrieved parent, then change it's parent to "0"
	 *
	 * @since 1.7.7
	 */
	private function process_includes() {
		$include = $this->settings_manager->get_value( "include" );

		if ( $include ) {
			$include_array = Utils::split_numbers_by_comma_space( $include );

			foreach ( $include_array as $id ) {
				$term = $this->find_term( $id );

				if ( $term && $term["parent"] !== 0 && ! $this->is_term_has_retrieved_parent( $term ) ) {
					$this->change_term_data( $id, [ "parent" => 0 ] );
				}
			}
		}
	}

	public function get_term_level( $item, $current_level = 1 ) {
		$parent_id = $item["parent"];

		if ( ! $parent_id ) {
			return $current_level;
		} else {
			$parent = $this->find_term( $parent_id );
			if ( $parent ) {
				return $this->get_term_level( $parent, $current_level + 1 );
			}
		}

		return null;
	}

	public function get_term_children( $item_id, $only_ids = false ) {
		$children = [];

		foreach ( $this->data as $item ) {
			if ( $item["parent"] === $item_id ) {
				array_push( $children, $only_ids ? $item["id"] : $item );
			}
		}

		return $children;
	}

	public function is_term_has_retrieved_parent( $term ) {
		return ! ! $this->find_term( $term["parent"] );
	}

	public function is_term_has_children( $term ) {
		return ! empty( $term["children"] );
	}

	/**
	 * Finds term data or index by id
	 *
	 * @param int $id
	 * @param bool $return_index
	 *
	 * @return int|string|array|null
	 */
	public function find_term( $id, $return_index = false ) {
		foreach ( $this->data as $index => $item ) {
			if ( $item["id"] === $id ) {
				return $return_index ? $index : $item;
			}
		}

		return null;
	}

	/**
	 * Checks, page is parent of current post (WP_Post)
	 *
	 * @param object $item_data
	 *
	 * @return bool
	 * @since 1.8.0
	 */
	private function is_page_includes_post__premium_only( $item_data ) {
		if ( ! is_page() ) {
			return false;
		} else {
			global $post;
			$page_parent_id = wp_get_post_parent_id( $post );

			return $page_parent_id && $page_parent_id === Utils::get( $item_data, "object_id" );
		}
	}

	/**
	 * Checks, term is parent of current post (WP_Post)
	 *
	 * @param string $id
	 *
	 * @return bool
	 * @since 1.8.0
	 */
	private function is_term_includes_post( $id ) {
		global $wp_query;
		$queried_object = $wp_query->get_queried_object();

		if ( $queried_object instanceof WP_Post ) {
			$post_id   = $queried_object->ID;
			$terms     = wp_get_post_terms( $post_id, $this->get_taxonomy() );
			$terms_ids = array_map( function ( $item ) {
				return $item->term_id;
			}, $terms );

			return in_array( $id, $terms_ids );
		}

		return false;
	}

	/**
	 * Checks, is menu item is current
	 *
	 * @param string $link
	 * @param integer $id
	 *
	 * @return bool
	 */
	private function is_current_item( $link, $id ) {
		$result_link = Utils::url_with_slash( $link );

		return $this->page_url === $result_link || $this->permalink === $result_link
		       || Utils::object_has_property( $this->queried_object, 'term_id' )
		          && $this->queried_object->term_id === $id;
	}

	public function is_source_menu() {
		return $this->source === "menu";
	}

	public function is_source_taxonomy() {
		return $this->source === "taxonomy";
	}

	public function get_taxonomy() {
		return $this->settings_manager->get_value( "taxonomy" );
	}

	public function get_source() {
		return $this->source;
	}

	/**
	 * @param $id integer
	 * @param $changes array
	 */
	public function change_term_data( $id, $changes ) {
		$index = $this->find_term( $id, true );

		$this->data[ $index ] = array_merge( $this->data[ $index ], $changes );
	}

	private function save_args( $args ) {
		foreach ( $args as $key ) {
			$value = $this->settings_manager->get_value( $key );
			if ( $value !== null ) {
				$this->args[ $key ] = $value;
			}
		}
	}

	/**
	 * @return array
	 */
	public function get_args() {
		return $this->args;
	}

	/**
	 * @return array
	 */
	public function get_data() {
		return $this->data;
	}

	/**
	 * @return string
	 */
	public function get_error() {
		return $this->error;
	}
}