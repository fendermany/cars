<?php

/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package cars
 */

get_header();
?>

<div id="content">
	<div class="post__wrapper">
		<h1 class="post__title"><?php the_title() ?></h1>
		<div class="post__thumbnail">
			<?php if (has_post_thumbnail()) { ?>
				<?php the_post_thumbnail(); ?>
			<?php } ?>


		</div>
		<div class="post__content">
			<?php the_post(); ?>
			<?php the_content(); ?>
		</div>

	</div>


</div><!-- #main -->

<?php
get_footer();
