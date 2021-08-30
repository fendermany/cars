<?php

/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package cars
 */

?>
<aside id="sidebar">
	<div data-da=".header__menu,767.98,2" class="searchwidget">
		<div class="searchwidget__inner">
			<?php get_search_form(); ?>
		</div>
	</div>
	<?php get_sidebar(); ?>
	<div class="widget">
		<?php
		$args = array(
			'smallest'			=> 8,
			'largest'			=> 16,
			'unit'				=> 'pt',
			'number'			=> 45,
			'format'			=> 'flat',
			'separator'			=> ' ',
			'orderby'			=> 'name',
			'order'				=> 'ASC',
			'topic_count_text_callback' => 'true_no_callback_count',
			'exclude'			=> null,
			'include'			=> null,
			'link'				=> 'view',
			'taxonomy' => array('transport_tags'),
			'echo'				=> true,
			'child_of'			=> null
		);

		wp_tag_cloud($args);
		?>
	</div>
</aside>

<!-- /#sidebar -->
</main>
<footer class=" footer">
	<div class="footer__content _container">
		<div class="footer__body">
			<?php
			wp_nav_menu([
				'menu'            => 'Bottom',
				'theme_location'  => 'bottom',
				'container'       => false,
				'menu_class'      => 'footer__list',
				'echo'            => true,
				'fallback_cb'     => 'wp_page_menu',
				'items_wrap'      => '<ul class="footer__list">%3$s</ul>',
				'depth'           => 0,
			]);
			?>
		</div>
	</div>
</footer>
</div>
<div class="popup popup_popup">
	<div class="popup__content">
		<div class="popup__body">
			<div class="popup__close"></div>
		</div>
	</div>
</div>
<div class="popup popup_massagename-message">
	<div class="popup__content">
		<div class="popup__body">
			<div class="popup__close"></div>
		</div>
	</div>
</div>
<div class="popup popup_video">
	<div class="popup__content">
		<div class="popup__body">
			<div class="popup__close popup__close_video"></div>
			<div class="popup__video _video"></div>
		</div>
	</div>
</div>
<?php wp_footer(); ?>
</body>

</html>