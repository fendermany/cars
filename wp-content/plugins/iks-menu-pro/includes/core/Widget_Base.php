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

use IksStudio\IKSM_CORE\utils\PluginPostsManager;
use IksStudio\IKSM_CORE\utils\Utils;
use WP_Widget;

/**
 * @subpackage Widget
 */
class Widget_Base extends WP_Widget {

	/**
	 * Initialize the widget
	 *
	 * @param $description string
	 *
	 * @since 1.0.0
	 */
	public function __construct( $description ) {
		$widget_ops = array(
			'classname'   => Plugin::$slug . '-widget',
			'description' => Plugin::$name . ' - ' . $description,
		);

		parent::__construct( Plugin::$slug . '-widget', Plugin::$name, $widget_ops );
		$this->do_hooks();
	}

	/**
	 * Handle WP actions and filters.
	 *
	 * @since    1.0.0
	 */
	private function do_hooks() {
		add_action( 'wp_head', array( $this, 'print_dynamic_styles' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_public_styles' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_public_scripts' ) );
	}

	/**
	 * Prints empty styles block
	 *
	 * @return    null
	 * @since     1.0.0
	 *
	 */
	public function print_dynamic_styles() {
		echo '<style type="text/css" id="' . Plugin::$slug . '-dynamic-style' . '"></style>';
	}

	/**
	 * Enqueue public-specific style sheet.
	 *
	 * @return    null    Return early if no settings page is registered.
	 * @since     1.0.0
	 *
	 */
	public function enqueue_public_styles() {
		Utils::enqueue_style( "public" );
	}

	/**
	 * Register public-specific scripts
	 *
	 * @since     1.0.0
	 */
	public function enqueue_public_scripts() {
		Utils::enqueue_script( "public" );
		Utils::enqueue_project_public_scripts();
	}

	protected function render_widget_before( $args, $instance ) {
		// WordPress core before_widget hook
		echo Utils::get( $args, "before_widget", "" );
		// Display widget title if defined
		if ( isset( $instance["title"] ) && strlen( $instance["title"] ) > 0 ) {
			echo Utils::get( $args, "before_title", "" ) .
			     Utils::get( $instance, "title" ) .
			     Utils::get( $args, "after_title", "" );
		}
	}

	protected function render_widget_after( $args, $instance ) {
		// WordPress core after_widget hook
		echo Utils::get( $args, "after_widget", "" );
	}

	/**
	 * Outputs the options form on admin
	 *
	 * @param array $instance
	 *
	 * @return string|void
	 */
	public function form( $instance ) {
		$posts_manager = new PluginPostsManager();
		$posts         = $posts_manager->get_posts();

		$title = ( ! empty( $instance['title'] ) ) ? $instance['title'] : '';
		$id    = ( ! empty( $instance['id'] ) ) ? (int) $instance['id'] : null;
		?>
        <p>
            <label for="<?php echo esc_html( $this->get_field_id( 'title' ) ); ?>">
				<?php echo Utils::t( "Title", true ) . ":"; ?>
            </label>
            <input class="widefat" id="<?php echo esc_html( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_html( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>"/>

            <label for="<?php echo $this->get_field_id( 'id' ); ?>">
				<?php echo Utils::t( "ID", true ) . ":"; ?>
            </label>
            <select class="widefat" id="<?php echo $this->get_field_id( 'id' ); ?>" name="<?php echo $this->get_field_name( 'id' ); ?>">
				<?php
				foreach ( $posts as $post ) {
					?>
                    <option value="<?php echo $post->ID ?>" <?php echo( $id === $post->ID ? 'selected' : '' ) ?>>
						<?php echo esc_html( $post->post_title ) . ' (id = ' . $post->ID . ')' ?>
                    </option>
					<?php
				}
				?>
            </select>

        </p>
		<?php
	}

	/**
	 * Processing widget options on save
	 *
	 * @param array $new_instance The new options
	 * @param array $old_instance The previous options
	 *
	 * @return array
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		$instance['title'] = wp_strip_all_tags( Utils::get( $new_instance, "title", "" ) );
		$instance['id']    = $new_instance['id'];

		return $instance;
	}
}
