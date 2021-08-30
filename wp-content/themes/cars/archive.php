<?php

get_header();
?>

<div id="content">

	<?php if (have_posts()) : ?>

		<div class="page-header">
		<?php
						if ( function_exists('yoast_breadcrumb') ) {
						yoast_breadcrumb( '<p id="breadcrumbs">','</p>' );
						}
					?>
		</div><!-- .page-header -->

		
		<div class="page-wrapper">
			<?php
		/* Start the Loop */
		while (have_posts()) :
			the_post();

			/*
				 * Include the Post-Type-specific template for the content.
				 * If you want to override this in a child theme, then include a file
				 * called content-___.php (where ___ is the Post Type name) and that will be used instead.
				 */
			get_template_part('template-parts/content', get_post_type());

		endwhile;

		the_posts_navigation(); ?>
		</div>
	<?php	
	else :

		get_template_part('template-parts/content', 'none');

	endif;
		?>
		
		

</div>


<?php
get_footer();
?>