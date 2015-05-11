<?php
/**
 * The template for displaying Search Results pages
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
	<section id="primary" class="content-area <?php echo $longform_site_width; ?>">
		<div id="content" class="site-content" role="main">

			<?php if ( have_posts() ) : ?>

			<header class="page-header">
				<h1 class="page-title"><?php printf( __( 'Search Results for: <span>%s</span>', 'longform' ), get_search_query() ); ?></h1>
			</header><!-- .page-header -->

				<?php
					// Start the Loop.
					while ( have_posts() ) : the_post();

						/*
						 * Include the post format-specific template for the content. If you want to
						 * use this in a child theme, then include a file called called content-___.php
						 * (where ___ is the post format) and that will be used instead.
						 */
						get_template_part( 'content', get_post_format() );

					endwhile;
					// Previous/next post navigation.
					longform_paging_nav();

				else :
					// If no content, include the "No posts found" template.
					get_template_part( 'content', 'none' );

				endif;
			?>

		</div><!-- #content -->
	</section><!-- #primary -->
	<?php get_sidebar( 'content' ); ?>
</div><!-- #main-content -->

<?php
get_footer();
