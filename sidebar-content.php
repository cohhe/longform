<?php
/**
 * The Content Sidebar
 *
 * @package WordPress
 * @subpackage Longform
 * @since Longform 1.0
 */

if ( LONGFORM_LAYOUT == 'sidebar-right' && is_active_sidebar( 'sidebar-2' ) ) {
?>
<div id="content-sidebar" class="content-sidebar widget-area col-sm-4 col-md-4 col-lg-4" role="complementary">
	<?php dynamic_sidebar( 'sidebar-2' ); ?>
</div><!-- #content-sidebar -->
<?php
}