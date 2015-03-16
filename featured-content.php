<?php
/**
 * The template for displaying featured content
 *
 * @package WordPress
 * @subpackage Longform
 * @since Longform 1.0
 */
?>

<div class="featured-slider">
	<div id="featured-content" class="featured-content">
		<?php
			/**
			 * Fires before the Longform 1.0 featured content.
			 *
			 * @since Longform 1.0
			 */
			do_action( 'longform_featured_posts_before' );

			$featured_posts = longform_get_featured_posts();
			foreach ( (array) $featured_posts as $order => $post ) :
				setup_postdata( $post );

				 // Include the featured content template.
				get_template_part( 'content', 'featured-post' );
			endforeach;

			/**
			 * Fires after the Longform 1.0 featured content.
			 *
			 * @since Longform 1.0
			 */
			do_action( 'longform_featured_posts_after' );

			wp_reset_postdata();
		?>
		<div class="clearfix"></div>
	</div><!-- #featured-content .featured-content -->
</div><!-- .featured-slider -->