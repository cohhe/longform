<?php
/**
 * The template for displaying 404 pages (Not Found)
 *
 * @package WordPress
 * @subpackage Longform
 * @since Longform 1.0
 */

get_header(); ?>

<div id="main-content" class="main-content row page_404">
	<div id="primary" class="content-area col-sm-12 col-md-12 col-lg-12">
		<div id="content" class="site-content" role="main">

			<header class="page-header col-sm-6 col-md-6 col-lg-6">
				<h1 class="page-title animated bounceIn"><?php _e( '404', 'longform' ); ?></h1>
			</header>

			<div class="page-content col-sm-6 col-md-6 col-lg-6">
				<p class="animated bounceIn"><?php _e( 'It looks like nothing was found at this location. Maybe try a search?', 'longform' ); ?></p>

				<?php get_search_form(); ?>
			</div><!-- .page-content -->

		</div><!-- #content -->
	</div><!-- #primary -->
</div><!-- #main-content -->

<?php
get_footer();
