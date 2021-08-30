<?php
/**
 * Template part for displaying results in search pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package cars
 */

?>



<article id="post-<?php the_ID(); ?>" <?php post_class('taxonomy__item'); ?>>
	<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
		<?php if (has_post_thumbnail()) { ?>
		<?php the_post_thumbnail(); ?>
		<?php } ?>

		<?php
			if (is_singular()) :
				the_title('<h1 class="entry-title">', '</h1>');
			else :
				the_title('<h2 class="entry-title"><a href="' . esc_url(get_permalink()) . '" rel="bookmark">', '</a></h2>');
			endif;

			if ('post' === get_post_type()) :
		?>
		<div class="entry-meta">
			<?php
			cars_posted_on();
			cars_posted_by();
			?>
		</div><!-- .entry-meta -->
		<?php endif; ?>
	</a>
</article>