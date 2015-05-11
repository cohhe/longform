<?php
/**
 * The template for displaying Author bios.
 *
 * @package WordPress
 * @subpackage Longform
 * @since Longform 1.0
 */
?>

<div class="author-info">
	<div class="author-avatar">
		<?php echo get_avatar( get_the_author_meta( 'user_email' ), apply_filters( 'longform_author_bio_avatar_size', 74 ) ); ?>
	</div><!-- .author-avatar -->
	<div class="author-description">
		<p class="author-the-author"><?php _e( 'The author', 'longform' ); ?></p>
		<h2 class="author-title"><a class="author-link" href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" rel="author"><?php echo get_the_author(); ?></a></h2>
		<p class="author-bio">
			<?php the_author_meta( 'description' ); ?>
		</p>
	</div><!-- .author-description -->
	<div class="clearfix"></div>
</div><!-- .author-info -->