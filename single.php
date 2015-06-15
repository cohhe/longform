<?php
/**
 * The Template for displaying all single posts
 *
 * @package WordPress
 * @subpackage Longform
 * @since Longform 1.0
 */

get_header();

global $longform_site_width;
?>

<div id="main-content" class="main-content row">
	<?php
		get_sidebar();
	?>
	<div id="primary" class="content-area <?php echo $longform_site_width; ?>">
		<div id="content" class="site-content" role="main">
			<?php
				// Start the Loop.
				while ( have_posts() ) : the_post();

					/*
					 * Include the post format-specific template for the content. If you want to
					 * use this in a child theme, then include a file called called content-___.php
					 * (where ___ is the post format) and that will be used instead.
					 */
					get_template_part( 'content', get_post_format() );

					?><div class="clearfix"></div><?php

					the_tags( '<footer class="entry-meta"><span class="tag-links">', '', '</span></footer>' );

					if ( defined('BA_AESOPSOCIAL_ITEM_NAME') && BA_AESOPSOCIAL_ITEM_NAME == 'aesop-social' ) {
						do_action('ase_addon_social_links');
					} else {
						echo do_shortcode( '[ssba]' );
					}

					if ( get_the_author_meta( 'description' ) ) :
						get_template_part( 'author-bio' );
					endif;

					// More posts like this
					echo longform_the_related_posts();

					// If comments are open or we have at least one comment, load up the comment template.
					if ( comments_open() || get_comments_number() ) {
						comments_template();
					}
				endwhile;
			?>
		</div><!-- #content -->
	</div><!-- #primary -->
	<?php get_sidebar( 'content' ); ?>
</div><!-- #main-content -->

<?php
get_sidebar();
get_footer();