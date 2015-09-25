<?php
/**
 * The Header for our theme
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package WordPress
 * @subpackage Longform
 * @since Longform 1.0
 */
?><!DOCTYPE html>
<!--[if IE 7]>
<html class="ie ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html class="ie ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 7) | !(IE 8) ]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width">
	<title><?php wp_title( '|', true, 'right' ); ?></title>
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	<!--[if lt IE 9]>
	<script src="<?php echo get_template_directory_uri(); ?>/js/html5.js"></script>
	<![endif]-->
	<?php
		$favicon = get_theme_mod('longform_favicon', array());

		if (!empty($favicon)) {
			echo '<link rel="shortcut icon" href="' . esc_url( $favicon ) . '" />';
		}
	?>
	
	<?php wp_head(); ?>
</head>
<?php
global $longform_site_width;

$form_class    = '';
$class         = '';
$search_string = '';
$longform_site_width    = 'col-sm-12 col-md-12 col-lg-12';
$layout_type   = get_post_meta(get_the_id(), 'layouts', true);

if ( is_archive() || is_search() || is_404() ) {
	$layout_type = 'full';
} elseif (empty($layout_type)) {
	$layout_type = get_theme_mod('longform_layout', 'full');
}

switch ($layout_type) {
	case 'right':
		define('LONGFORM_LAYOUT', 'sidebar-right');
		break;
	case 'full':
		define('LONGFORM_LAYOUT', 'sidebar-no');
		break;
	case 'left':
		define('LONGFORM_LAYOUT', 'sidebar-left');
		break;
}

if ( ( ( LONGFORM_LAYOUT == 'sidebar-left' && is_active_sidebar( 'sidebar-1' ) ) || ( LONGFORM_LAYOUT == 'sidebar-right' && is_active_sidebar( 'sidebar-2' ) ) )  && is_single() ) {
	$longform_site_width = 'col-sm-8 col-md-8 col-lg-8';
}
?>
<body <?php body_class(); ?>>
<?php do_action('ase_theme_body_inside_top'); ?>
<div id="page" class="hfeed site">
	<?php
		 $logo = get_theme_mod('longform_logo', array());
	?>
	<header id="masthead" class="site-header" role="banner">
		<div class="search-toggle">
			<div class="search-content container">
				<form action="<?php echo home_url(); ?>" method="get" class="<?php echo $form_class; ?>">
					<input type="text" name="s" class="<?php echo $class; ?>" value="<?php echo $search_string; ?>" placeholder="<?php echo __('Search', 'longform'); ?>"/>
				</form>
			</div>
		</div>
		<div class="header-content">
			<div class="header-main">
				<div class="site-title col-xs-5 col-sm-5 col-md-3">
					<?php
					if ( ! empty ( $logo ) ) {?>
						<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><img src="<?php echo $logo; ?>"></a>
						<?php
					} else { ?>
						<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" class="site-title"><?php bloginfo( 'name' ); ?></a>
						<?php
						$description = get_bloginfo( 'description', 'display' );

						if ( ! empty ( $description ) ) { ?>
							<p class="site-description"><?php echo esc_html( $description ); ?></p>
						<?php
						}
					}
					?>
				</div>
				<button type="button" class="navbar-toggle visible-xs visible-sm" data-toggle="collapse" data-target=".site-navigation">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<div class="main-header-right-side col-xs-5 col-sm-5 col-md-9">
					<div class="header-search">
						<form action="" method="get" class="header-search-form">
							<input type="text" name="s" value="" placeholder="<?php _e( 'Search', 'longform' ); ?>">
						</form>
						<a href="" class="search-button"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></a>
					</div>
					<nav id="primary-navigation" class="col-xs-12 col-sm-10 col-md-8 site-navigation primary-navigation navbar-collapse collapse" role="navigation">
						<?php
							wp_nav_menu(
								array(
									'theme_location' => 'primary',
									'menu_class'     => 'nav-menu',
									'depth'          => 3,
									'walker'         => new Longform_Header_Menu_Walker
								)
							);
						?>
					</nav>
				</div>
				<div class="reading-header-right-side col-xs-5 col-sm-5 col-md-8">
					<div class="menu-toggle">
						<a href="javascript:void(0);">
							<span class="bg">
								<em></em>
								<em></em>
								<em></em>
							</span>
						</a>
					</div>
					<div class="hidden-on-menu">
						<?php
						// Previous/next post navigation.
						if ( !in_array('aesop-story-front', get_body_class()) ) {
							longform_post_nav();
						}

						if ( function_exists('longform_aesop_component_exists') ) {

							// Only add if chapter componen has been added to page
							if ( in_array('aesop-story-front', get_body_class()) ) {
								$current_ID = get_option('asf_story_id');
								$highlight_id = get_option('asf_story_id');
							} else {
								$current_ID = '';
								$highlight_id = get_the_ID();
							}

							if ( longform_aesop_component_exists( $current_ID , 'chapter' ) == true ) { ?>
								<div class="header_chapter_container">
									<a href="javascript:void(0);" class="chapters-link" id="trigger-chapters-overlay"><span class="glyphicon glyphicon-list" aria-hidden="true"></span> <?php _e('Chapters', 'longform'); ?></a>
									<div class="header_chapter_wrapper">
										<button type="button" class="overlay-close"><?php _e('Close', 'longform'); ?></button>
										<div class="header_chapter_open chapter_overlay"></div>
									</div>
								</div>
						<?php
							}

						$story_highlights = get_post_meta( $highlight_id, 'aesop_story_highlights' );
						if ( !empty($story_highlights) ) { ?>
							<div class="header_highlight_container">
								<a href="javascript:void(0);" class="highlight-link" id="trigger-highlight-overlay"><span class="genericon genericon-book" aria-hidden="true"></span> <?php _e('Highlights', 'longform'); ?></a>
								<div class="header_highlight_wrapper">
									<button type="button" class="overlay-close"><?php _e('Close', 'longform'); ?></button>
									<div class="header_highlight_open highlight_overlay">
										<nav class="scroll-nav" role="navigation">
											<div class="scroll-nav__wrapper">
												<ol class="scroll-nav__list">
												<?php
													$counter = 100;
													foreach ($story_highlights as $highlight_value) {
														echo '<li class="scroll-nav__item">'.$highlight_value.'</li>';
														$counter++;
													}
												?>
												</ol>
											</div>
										</nav>
									</div>
								</div>
							</div>
						<?php }
						}
						?>
					</div>
					<nav id="primary-navigation" class="col-xs-12 col-sm-10 col-md-8 site-navigation primary-navigation navbar-collapse collapse" role="navigation">
						<?php
							wp_nav_menu(
								array(
									'theme_location' => 'primary',
									'menu_class'     => 'nav-menu',
									'depth'          => 3,
									'walker'         => new Longform_Header_Menu_Walker
								)
							);
						?>
					</nav>
				</div>
			</div>
		</div>
		<div class="clearfix"></div>
	</header><!-- #masthead -->
	<?php
		if ( in_array('aesop-story-front', get_body_class()) ) {
			$story_id = get_option('asf_story_id');
			?>
			<div class="intro-effect-bg-img-container container">
				<div class="intro-effect-bg-img" style="">
					<?php echo get_the_post_thumbnail($story_id, 'longform-huge-width' ); ?>
				</div>
				<?php echo '<h1 class="entry-title">' . get_the_title( $story_id ) . '</h1>'; ?>
				<div class="entry-meta">
					<?php
						longform_posted_on( $story_id );

						if ( in_array( 'category', get_object_taxonomies( get_post_type( $story_id ) ) ) && longform_categorized_blog() ) : ?>
							<span class="cat-links">/<?php echo get_the_category_list( _x( ', ', 'Used between list items, there is a space after the comma.', 'longform' ), '', $story_id ); ?></span>
					<?php
						endif;

						if ( ! post_password_required( $story_id ) && ( comments_open( $story_id ) || get_comments_number( $story_id ) ) ) :
					?>
					<span class="comments-link">/<?php comments_popup_link( __( 'No Comments', 'longform' ), __( '1 Comment', 'longform' ), __( '% Comments', 'longform' ) ); ?></span>
					<?php
						endif;
					?>
				</div><!-- .entry-meta -->
			</div>
		<?php } else if ( is_single() && has_post_thumbnail() ) { ?>
			<div class="intro-effect-bg-img-container container">
				<div class="intro-effect-bg-img" style="background: url(<?php echo wp_get_attachment_url( get_post_thumbnail_id(get_the_ID()) ); ?>) no-repeat">
					<?php longform_post_thumbnail(); ?>
				</div>
				<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
				<div class="entry-meta">
					<?php
						if ( 'post' == get_post_type() )
							longform_posted_on();

						if ( in_array( 'category', get_object_taxonomies( get_post_type() ) ) && longform_categorized_blog() ) : ?>
							<span class="cat-links">/<?php echo get_the_category_list( _x( ', ', 'Used between list items, there is a space after the comma.', 'longform' ) ); ?></span>
					<?php
						endif;

						if ( ! post_password_required() && ( comments_open() || get_comments_number() ) ) :
					?>
					<span class="comments-link">/<?php comments_popup_link( __( 'No Comments', 'longform' ), __( '1 Comment', 'longform' ), __( '% Comments', 'longform' ) ); ?></span>
					<?php
						endif;
					?>
				</div><!-- .entry-meta -->
			</div>
		<?php
		} else if ( is_single() ) { ?>
			<div class="container without_thumbnail">
				<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
				<div class="entry-meta">
					<?php
						if ( 'post' == get_post_type() )
							longform_posted_on();

						if ( in_array( 'category', get_object_taxonomies( get_post_type() ) ) && longform_categorized_blog() ) : ?>
							<span class="cat-links">/<?php echo get_the_category_list( _x( ', ', 'Used between list items, there is a space after the comma.', 'longform' ) ); ?></span>
					<?php
						endif;

						if ( ! post_password_required() && ( comments_open() || get_comments_number() ) ) :
					?>
					<span class="comments-link">/<?php comments_popup_link( __( 'No Comments', 'longform' ), __( '1 Comment', 'longform' ), __( '% Comments', 'longform' ) ); ?></span>
					<?php
						endif;
					?>
				</div><!-- .entry-meta -->
			</div>
		<?php } ?>
	<?php
		if ( is_front_page() && longform_has_featured_posts() && !in_array('aesop-story-front', get_body_class()) ) {
			// Include the featured content template.
			get_template_part( 'featured-content' );
		}
	?>
	<div id="main" class="site-main container">
