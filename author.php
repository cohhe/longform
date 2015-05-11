<?php
/**
 * The template for displaying Author archive pages
 *
 * @link http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Longform
 * @since Longform 1.0
 */

get_header();

global $longform_site_width;

$sm_author_id          = get_the_author_meta( 'ID' );
$sm_authot_description = get_the_author_meta( 'description', $sm_author_id );

if ( function_exists('get_cimyFieldValue') ) {
	$author_style                   = '';
	$author_class                   = '';
	$social_content                 = '';
	$sm_author_social['twitter']    = get_cimyFieldValue($sm_author_id, 'USER-TWITTER');
	$sm_author_social['facebook']   = get_cimyFieldValue($sm_author_id, 'USER-FACEBOOK');
	$sm_author_social['pinterest']  = get_cimyFieldValue($sm_author_id, 'USER-PINTEREST');
	$sm_author_social['googleplus'] = get_cimyFieldValue($sm_author_id, 'USER-GOOGLE');
	$sm_author_background           = get_cimyFieldValue($sm_author_id, 'USER-BACKGROUND');

	if ( !empty($sm_author_background) ) {
		$author_class = ' with_bg_image';
		$author_style = ' style="background-image: url(\'' . $sm_author_background . '\');"';
	}

	$social_content = '<ul class="author-social-links">';
	foreach ($sm_author_social as $key => $value) {
		if ( !empty($value)) {
			$social_content .= '<li class="genericon genericon-' . $key . ' author-s-' . $key . '"><a href="' . $value . '" target="_blank">&nbsp;</a></li>';
		}
	}
	$social_content .= '</ul>';
}

?>

<div id="main-content" class="main-content row<?php echo $author_class; ?>">
	<?php
		get_sidebar();
	?>
	<section id="primary" class="content-area <?php echo $longform_site_width; ?>">
		<div id="content" class="site-content" role="main">

			<?php if ( have_posts() ) : ?>

			<header class="archive-header" <?php echo $author_style; ?>>
				<div class="author-info-container">
					<h1 class="archive-title">
						<?php
							/*
							 * Queue the first post, that way we know what author
							 * we're dealing with (if that is the case).
							 *
							 * We reset this later so we can run the loop properly
							 * with a call to rewind_posts().
							 */
							the_post();

							the_author();
						?>
					</h1>
					<p class="author-desc"><?php echo $sm_authot_description; ?></p>
					<div class="author-social">
						<?php echo $social_content; ?>
					</div>
				</div>
			</header><!-- .archive-header -->

			<?php
					/*
					 * Since we called the_post() above, we need to rewind
					 * the loop back to the beginning that way we can run
					 * the loop properly, in full.
					 */
					rewind_posts();

					// Start the Loop.
					while ( have_posts() ) : the_post();

						/*
						 * Include the post format-specific template for the content. If you want to
						 * use this in a child theme, then include a file called called content-___.php
						 * (where ___ is the post format) and that will be used instead.
						 */
						get_template_part( 'content', get_post_format() );

					endwhile;
					// Previous/next page navigation.
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
