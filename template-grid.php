<?php
/**
 *
 * Template Name: Stories grid
 *
 * The template for displaying all posts in a grid layout
 *
 * @package WordPress
 * @subpackage Longform
 * @since Longform 1.0
 */

get_header();

global $longform_site_width, $longform_featured_post_class, $longform_featured_post_thumbnail;

$longform_featured_post_class     = 'col-sm-4 col-md-4 col-lg-4';
$longform_featured_post_thumbnail = 'longform-thumbnail-large';
$grid_tag                         = get_theme_mod('longform_stories_tag', '');
$grid_stories_count               = get_theme_mod('longform_stories_per_page', '');

// If tag isn't specified then use default one
if ( empty($grid_tag) ) {
	$grid_tag = 'featured';
}

// By default 15
if ( empty($grid_stories_count) ) {
	$grid_stories_count = 15;
}

$args = array(
	'posts_per_page'   => $grid_stories_count,
	'tag'              => $grid_tag,
	'orderby'          => 'order',
	'order'            => 'ASC',
	'post_type'        => 'post',
	'post_status'      => 'publish',
	'suppress_filters' => true
);
$grid_posts = get_posts( $args );
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

			if ( have_posts() ) :
				// Start the Loop.
				foreach ( $grid_posts as $post ) {
					setup_postdata( $post );

					/*
					 * Include the post format-specific template for the content. If you want to
					 * use this in a child theme, then include a file called called content-___.php
					 * (where ___ is the post format) and that will be used instead.
					 */
					get_template_part( 'content', 'featured-post' );

				}
				// Previous/next post navigation.
				longform_paging_nav();

			else :
				// If no content, include the "No posts found" template.
				get_template_part( 'content', 'none' );

			endif;
			wp_reset_query();
		?>
		<div class="clearfix"></div>
	</div><!-- #featured-content .featured-content -->
</div><!-- .featured-slider -->
<?php
$longform_featured_post_class = '';

get_footer();
