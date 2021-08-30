<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package cars
 */

get_header();
?>

	<div id="content" class="site-main">

		<section class="error-404 not-found">
			<header class="page-header">
				<h1 class="page-title"><?php esc_html_e( 'Страница не найдена.', 'cars' ); ?></h1>
			</header><!-- .page-header -->

			
		</section><!-- .error-404 -->

	</div><!-- #main -->

<?php
get_footer();
