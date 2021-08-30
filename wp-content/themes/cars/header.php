<?php

/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package cars
 */

?>




<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>

	<meta charset="<?php bloginfo('charset'); ?>">
	<meta name="format-detection" content="telephone=no">
	<!-- <meta name="robots" content="noindex, nofollow"> -->
	<!-- <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0"> -->
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<?php wp_head(); ?>
</head>


<body <?php body_class(); ?>>
	<?php wp_body_open(); ?>
	<div class="wrapper">
		<header class="header">
			<div class="header__content _container">
				<div class="header__body">
					<div class="header__logo">
						<?php
						if (is_front_page()) {
						?>
							<img src="<?php
											$custom_logo__url = wp_get_attachment_image_src(get_theme_mod('custom_logo'), 'full');
											echo $custom_logo__url[0];
											?>" alt="logo">
						<?php
						} else {
						?>
							<?php the_custom_logo(); ?>
						<?php
						}
						?>
					</div>
					<div class="header__descr">
						123
					</div>
					<div class="header__links">
						<div data-da=".header__menu,767.98,2" class="header__form">
							<a href="#">
								Заказать починку
							</a>
						</div>
						<div class="header__lang">
							<a href="#"><img src="<?php echo bloginfo('template_url'); ?>/assets/img/nl.gif" class="Vlag"></a>
							<a href="#"><img src="<?php echo bloginfo('template_url'); ?>/assets/img/gb.gif" class="Vlag"></a>
							<a href="#"><img src="<?php echo bloginfo('template_url'); ?>/assets/img/de.gif" class="Vlag"></a>
						</div>
					</div>
					<nav class="header__menu menu">
						<?php
						wp_nav_menu([
							'menu'            => 'Menu',
							'theme_location'  => 'header',
							'container'       => false,
							'menu_class'      => 'header__menu-list',
							'echo'            => true,
							'fallback_cb'     => 'wp_page_menu',
							'items_wrap'      => '<ul class="header__menu-list menu__body">%3$s</ul>',
							'depth'           => 0,
						]);
						?>
						<div class="icon-menu">
							<span></span>
							<span></span>
							<span></span>
						</div>
					</nav>
				</div>
			</div>
		</header>
		<main class="page _container">
			<nav class="leftmenu">
				<div class="leftmenu__inner">
				<?php echo do_shortcode( '[iks_menu id="629"]' ); ?>
				</div>
			</nav>
				