<?php
/**
 * Longform 1.0 functions and definitions
 *
 * Set up the theme and provides some helper functions, which are used in the
 * theme as custom template tags. Others are attached to action and filter
 * hooks in WordPress to change core functionality.
 *
 * When using a child theme you can override certain functions (those wrapped
 * in a function_exists() call) by defining them first in your child theme's
 * functions.php file. The child theme's functions.php file is included before
 * the parent theme's file, so the child theme functions would be used.
 *
 * @link http://codex.wordpress.org/Theme_Development
 * @link http://codex.wordpress.org/Child_Themes
 *
 * Functions that are not pluggable (not wrapped in function_exists()) are
 * instead attached to a filter or action hook.
 *
 * For more information on hooks, actions, and filters,
 * @link http://codex.wordpress.org/Plugin_API
 *
 * @package WordPress
 * @subpackage Longform
 * @since Longform 1.0
 */

/**
 * Required: include TGM.
 */
require_once( get_template_directory() . '/functions/tgm-activation/class-tgm-plugin-activation.php' );

/**
 * Set up the content width value based on the theme's design.
 *
 * @see longform_content_width()
 *
 * @since Longform 1.0
 */
if ( ! isset( $content_width ) ) {
	$content_width = 800;
}

/**
 * Longform 1.0 only works in WordPress 3.6 or later.
 */
if ( version_compare( $GLOBALS['wp_version'], '3.6', '<' ) ) {
	require get_template_directory() . '/inc/back-compat.php';
}

if ( ! function_exists( 'longform_setup' ) ) :
	/**
	 * Longform 1.0 setup.
	 *
	 * Set up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support post thumbnails.
	 *
	 * @since Longform 1.0
	 */
	function longform_setup() {
		require(get_template_directory() . '/inc/metaboxes/layouts.php');

		/*
		 * Make Longform 1.0 available for translation.
		 *
		 * Translations can be added to the /languages/ directory.
		 * If you're building a theme based on Longform 1.0, use a find and
		 * replace to change 'longform' to the name of your theme in all
		 * template files.
		 */
		load_theme_textdomain( 'longform', get_template_directory() . '/languages' );

		// This theme styles the visual editor to resemble the theme style.
		add_editor_style( array( 'css/editor-style.css' ) );

		// Add RSS feed links to <head> for posts and comments.
		add_theme_support( 'automatic-feed-links' );

		// Enable support for Post Thumbnails, and declare two sizes.
		add_theme_support( 'post-thumbnails' );
		set_post_thumbnail_size( 672, 372, true );
		add_image_size( 'longform-full-width', 1170, 600, true );
		add_image_size( 'longform-huge-width', 1800, 1200, true );
		add_image_size( 'longform-thumbnail', 490, 318, true );
		add_image_size( 'longform-thumbnail-large', 650, 411, true );

		// This theme uses wp_nav_menu() in two locations.
		register_nav_menus( array(
			'primary'   => __( 'Top primary menu', 'longform' ),
			'footer'    => __( 'Footer menu', 'longform' ),
		) );

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support( 'html5', array(
			'search-form', 'comment-form', 'comment-list',
		) );

		/*
		 * Enable support for Post Formats.
		 * See http://codex.wordpress.org/Post_Formats
		 */
		add_theme_support( 'post-formats', array(
			'aside', 'image', 'video', 'audio', 'quote', 'link', 'gallery',
		) );

		// This theme allows users to set a custom background.
		add_theme_support( 'custom-background', apply_filters( 'longform_custom_background_args', array(
			'default-color' => 'f5f5f5',
		) ) );

		// Add support for featured content.
		add_theme_support( 'featured-content', array(
			'featured_content_filter' => 'longform_get_featured_posts',
			'max_posts' => 6,
		) );

		// This theme uses its own gallery styles.
		add_filter( 'use_default_gallery_style', '__return_false' );
	}
endif; // longform_setup
add_action( 'after_setup_theme', 'longform_setup' );

// Admin CSS
function vh_admin_css() {
	wp_enqueue_style( 'vh-admin-css', get_template_directory_uri() . '/css/wp-admin.css' );
}

add_action('admin_head','vh_admin_css');

/**
 * Adjust content_width value for image attachment template.
 *
 * @since Longform 1.0
 *
 * @return void
 */
function longform_content_width() {
	if ( is_attachment() && wp_attachment_is_image() ) {
		$GLOBALS['content_width'] = 810;
	}
}
add_action( 'template_redirect', 'longform_content_width' );

/**
 * Prevent page scroll when clicking the More link
 *
 * @since Longform 1.0
 *
 * @return void
 */
function remove_more_link_scroll( $link ) {
	$link = preg_replace( '|#more-[0-9]+|', '', $link );
	return $link;
}
add_filter( 'the_content_more_link', 'remove_more_link_scroll' );

/**
 * Getter function for Featured Content Plugin.
 *
 * @since Longform 1.0
 *
 * @return array An array of WP_Post objects.
 */
function longform_get_featured_posts() {
	/**
	 * Filter the featured posts to return in Longform 1.0.
	 *
	 * @since Longform 1.0
	 *
	 * @param array|bool $posts Array of featured posts, otherwise false.
	 */
	return apply_filters( 'longform_get_featured_posts', array() );
}

/**
 * A helper conditional function that returns a boolean value.
 *
 * @since Longform 1.0
 *
 * @return bool Whether there are featured posts.
 */
function longform_has_featured_posts() {
	return ! is_paged() && (bool) longform_get_featured_posts();
}

/**
 * Register three Longform 1.0 widget areas.
 *
 * @since Longform 1.0
 *
 * @return void
 */
function longform_widgets_init() {
	// register_sidebar( array(
	// 	'name'          => __( 'Primary Sidebar', 'longform' ),
	// 	'id'            => 'sidebar-1',
	// 	'class'			=> 'col-sm-4 col-md-4 col-lg-4',
	// 	'description'   => __( 'Main sidebar that appears on the left.', 'longform' ),
	// 	'before_widget' => '<aside id="%1$s" class="widget %2$s">',
	// 	'after_widget'  => '</aside>',
	// 	'before_title'  => '<div class="divider"><h3 class="widget-title">',
	// 	'after_title'   => '</h3><div class="separator"></div></div>',
	// ) );
	register_sidebar( array(
		'name'          => __( 'Content Sidebar', 'longform' ),
		'id'            => 'sidebar-2',
		'class'			=> 'col-sm-4 col-md-4 col-lg-4',
		'description'   => __( 'Additional sidebar that appears on the right.', 'longform' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<div class="divider"><h3 class="widget-title">',
		'after_title'   => '</h3><div class="separator"></div></div>',
	) );
	register_sidebar( array(
		'name'          => __( 'Footer Widget Area 1', 'longform' ),
		'id'            => 'sidebar-3',
		'description'   => __( 'Appears in the footer section of the site.', 'longform' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<div class="divider"><h3 class="widget-title">',
		'after_title'   => '</h3><div class="separator"></div></div>',
	) );
	register_sidebar( array(
		'name'          => __( 'Footer Widget Area 2', 'longform' ),
		'id'            => 'sidebar-4',
		'description'   => __( 'Appears in the footer section of the site.', 'longform' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<div class="divider"><h3 class="widget-title">',
		'after_title'   => '</h3><div class="separator"></div></div>',
	) );
	register_sidebar( array(
		'name'          => __( 'Footer Widget Area 3', 'longform' ),
		'id'            => 'sidebar-5',
		'description'   => __( 'Appears in the footer section of the site.', 'longform' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<div class="divider"><h3 class="widget-title">',
		'after_title'   => '</h3><div class="separator"></div></div>',
	) );
}
add_action( 'widgets_init', 'longform_widgets_init' );

/**
 * Register Lato Google font for Longform 1.0.
 *
 * @since Longform 1.0
 *
 * @return string
 */
function longform_font_url() {
	$font_url = '';
	/*
	 * Translators: If there are characters in your language that are not supported
	 * by Lato, translate this to 'off'. Do not translate into your own language.
	 */
	$font_url = add_query_arg( 'family', urlencode( 'Roboto:400,100,300' ), "//fonts.googleapis.com/css" );

	return $font_url;
}

/**
 * Enqueue scripts and styles for the front end.
 *
 * @since Longform 1.0
 *
 * @return void
 */
function longform_scripts() {

	wp_enqueue_style( 'bootstrap', get_template_directory_uri() . '/css/bootstrap.css', array() );

	// Add Google fonts
	wp_register_style('googleFonts', '//fonts.googleapis.com/css?family=Old+Standard+TT:400,700|PT+Serif:400|Merriweather:400,700|Open+Sans:700,400&subset=latin');
	wp_enqueue_style( 'googleFonts');

	// Add Genericons font, used in the main stylesheet.
	wp_enqueue_style( 'genericons', get_template_directory_uri() . '/genericons/genericons.css', array(), '3.0.2' );

	// Load our main stylesheet.
	wp_enqueue_style( 'longform-style', get_stylesheet_uri(), array( 'genericons' ) );

	// Load the Internet Explorer specific stylesheet.
	wp_enqueue_style( 'longform-ie', get_template_directory_uri() . '/css/ie.css', array( 'longform-style', 'genericons' ), '20131205' );
	wp_style_add_data( 'longform-ie', 'conditional', 'lt IE 9' );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	if ( is_singular() && wp_attachment_is_image() ) {
		wp_enqueue_script( 'longform-keyboard-image-navigation', get_template_directory_uri() . '/js/keyboard-image-navigation.js', array( 'jquery' ), '20130402' );
	}

	if ( is_active_sidebar( 'sidebar-3' ) ) {
		wp_enqueue_script( 'jquery-masonry' );
	}

	// if ( is_front_page() && 'slider' == get_theme_mod( 'featured_content_layout' ) ) {
	// 	wp_enqueue_script( 'longform-slider', get_template_directory_uri() . '/js/slider.js', array( 'jquery' ), '20131205', true );
	// }

	wp_enqueue_script( 'longform-script', get_template_directory_uri() . '/js/functions.js', array( 'jquery' ), '20131209', true );
	wp_enqueue_script( 'bootstrap', get_template_directory_uri() . '/js/bootstrap.js', array( 'jquery' ), '20131209', true );

	wp_enqueue_style( 'animate', get_template_directory_uri() . '/css/animate.min.css', array() );
}
add_action( 'wp_enqueue_scripts', 'longform_scripts' );

// Admin Javascript
add_action( 'admin_enqueue_scripts', 'longform_admin_scripts' );
function longform_admin_scripts() {
	wp_register_script('master', get_template_directory_uri() . '/inc/js/admin-master.js', array('jquery'));
	wp_enqueue_script('master');
}

if ( ! function_exists( 'longform_the_attached_image' ) ) :
	/**
	 * Print the attached image with a link to the next attached image.
	 *
	 * @since Longform 1.0
	 *
	 * @return void
	 */
	function longform_the_attached_image() {
		$post                = get_post();
		/**
		 * Filter the default Longform 1.0 attachment size.
		 *
		 * @since Longform 1.0
		 *
		 * @param array $dimensions {
		 *     An array of height and width dimensions.
		 *
		 *     @type int $height Height of the image in pixels. Default 810.
		 *     @type int $width  Width of the image in pixels. Default 810.
		 * }
		 */
		$attachment_size     = apply_filters( 'longform_attachment_size', array( 810, 810 ) );
		$next_attachment_url = wp_get_attachment_url();

		/*
		 * Grab the IDs of all the image attachments in a gallery so we can get the URL
		 * of the next adjacent image in a gallery, or the first image (if we're
		 * looking at the last image in a gallery), or, in a gallery of one, just the
		 * link to that image file.
		 */
		$attachment_ids = get_posts( array(
			'post_parent'    => $post->post_parent,
			'fields'         => 'ids',
			'numberposts'    => -1,
			'post_status'    => 'inherit',
			'post_type'      => 'attachment',
			'post_mime_type' => 'image',
			'order'          => 'ASC',
			'orderby'        => 'menu_order ID',
		) );

		// If there is more than 1 attachment in a gallery...
		if ( count( $attachment_ids ) > 1 ) {
			foreach ( $attachment_ids as $attachment_id ) {
				if ( $attachment_id == $post->ID ) {
					$next_id = current( $attachment_ids );
					break;
				}
			}

			// get the URL of the next image attachment...
			if ( $next_id ) {
				$next_attachment_url = get_attachment_link( $next_id );
			}

			// or get the URL of the first image attachment.
			else {
				$next_attachment_url = get_attachment_link( array_shift( $attachment_ids ) );
			}
		}

		printf( '<a href="%1$s" rel="attachment">%2$s</a>',
			esc_url( $next_attachment_url ),
			wp_get_attachment_image( $post->ID, $attachment_size )
		);
	}
endif;

/**
 * Extend the default WordPress body classes.
 *
 * Adds body classes to denote:
 * 1. Single or multiple authors.
 * 2. Presence of header image.
 * 3. Index views.
 * 5. Presence of footer widgets.
 * 6. Single views.
 * 7. Featured content layout.
 *
 * @since Longform 1.0
 *
 * @param array $classes A list of existing body class values.
 * @return array The filtered body class list.
 */
function longform_body_classes( $classes ) {
	$longform_layout = '';

	if ( is_multi_author() ) {
		$classes[] = 'group-blog';
	}

	if ( is_single() || in_array('aesop-story-front', $classes) ) {
		$classes[] = 'intro-effect-fadeout';
	}

	if ( is_archive() || is_search() || is_home() ) {
		$classes[] = 'list-view';
	}

	if ( is_active_sidebar( 'sidebar-3' ) ) {
		$classes[] = 'footer-widgets';
	}

	if ( is_singular() && ! is_front_page() ) {
		$classes[] = 'singular';
	}

	$longform_layout = LONGFORM_LAYOUT;
	if ( !empty($longform_layout) ) {
		$classes[] = $longform_layout;
	}

	if ( is_front_page() && 'slider' == get_theme_mod( 'featured_content_layout' ) ) {
		$classes[] = 'slider';
	} elseif ( is_front_page() ) {
		$classes[] = 'grid';
	}

	return $classes;
}
add_filter( 'body_class', 'longform_body_classes' );

/**
 * Extend the default WordPress post classes.
 *
 * Adds a post class to denote:
 * Non-password protected page with a post thumbnail.
 *
 * @since Longform 1.0
 *
 * @param array $classes A list of existing post class values.
 * @return array The filtered post class list.
 */
function longform_post_classes( $classes ) {
	if ( ! post_password_required() && has_post_thumbnail() ) {
		$classes[] = 'has-post-thumbnail';
	}

	$classes[] = 'aesop-entry-content';

	return $classes;
}
add_filter( 'post_class', 'longform_post_classes' );


/* Related posts */
function longform_the_related_posts() {
	global $post;
	$tags = wp_get_post_tags($post->ID);
	  
	if ($tags) {
		$tag_ids = array();

		foreach($tags as $individual_tag) {
			$tag_ids[] = $individual_tag->term_id;
		}

		$args = array(
			'tag__in'             => $tag_ids,
			'post__not_in'        => array($post->ID),
			'posts_per_page'      => 8, // Number of related posts to display.
			'ignore_sticky_posts' => 1
		);

		$my_query = new wp_query( $args ); ?>

		<h2 class="related-articles-title"><?php _e( 'Related articles', 'longform' ); ?></h2>
		<div class="related-articles">
			<?php
			while( $my_query->have_posts() ) {
				$my_query->the_post(); ?>

				<div class="related-thumb col-sm-3 col-md-3 col-lg-3">
					<a rel="external" href="<?php the_permalink(); ?>">
						<?php the_post_thumbnail(array(500,350)); ?>
						<div class="related-content">
							<h2><?php the_title(); ?></h2>
						</div>
					</a>
				</div>
			<?php } ?>
			<div class="clearfix"></div>
		</div>
    <?php
	}
	wp_reset_postdata();
	wp_reset_query();
}

/**
 * Create a nicely formatted and more specific title element text for output
 * in head of document, based on current view.
 *
 * @since Longform 1.0
 *
 * @param string $title Default title text for current view.
 * @param string $sep Optional separator.
 * @return string The filtered title.
 */
function longform_wp_title( $title, $sep ) {
	global $paged, $page;

	if ( is_feed() ) {
		return $title;
	}

	// Add the site name.
	$title .= get_bloginfo( 'name' );

	// Add the site description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) ) {
		$title = "$title $sep $site_description";
	}

	// Add a page number if necessary.
	if ( $paged >= 2 || $page >= 2 ) {
		$title = "$title $sep " . sprintf( __( 'Page %s', 'longform' ), max( $paged, $page ) );
	}

	return $title;
}
add_filter( 'wp_title', 'longform_wp_title', 10, 2 );

// Custom template tags for this theme.
require get_template_directory() . '/inc/template-tags.php';

// Add Theme Customizer functionality.
require get_template_directory() . '/inc/customizer.php';

/*
 * Add Featured Content functionality.
 *
 * To overwrite in a plugin, define your own Featured_Content class on or
 * before the 'setup_theme' hook.
 */
if ( ! class_exists( 'Featured_Content' ) && 'plugins.php' !== $GLOBALS['pagenow'] ) {
	require get_template_directory() . '/inc/featured-content.php';
}

/**
 * Create HTML list of nav menu items.
 * Replacement for the native Walker, using the description.
 *
 * @see    http://wordpress.stackexchange.com/q/14037/
 * @author toscho, http://toscho.de
 */
class Longform_Header_Menu_Walker extends Walker_Nav_Menu {

	/**
	 * Start the element output.
	 *
	 * @param  string $output Passed by reference. Used to append additional content.
	 * @param  object $item   Menu item data object.
	 * @param  int $depth     Depth of menu item. May be used for padding.
	 * @param  array $args    Additional strings.
	 * @return void
	 */
	function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
		$classes         = empty ( $item->classes ) ? array () : (array) $item->classes;
		$has_description = '';

		$class_names = join(
			' '
		,   apply_filters(
				'nav_menu_css_class'
			,   array_filter( $classes ), $item
			)
		);

		// insert description for top level elements only
		// you may change this
		$description = ( ! empty ( $item->description ) )
			? '<small>' . esc_attr( $item->description ) . '</small>' : '';

		$has_description = ( ! empty ( $item->description ) )
			? 'has-description ' : '';

		! empty ( $class_names )
			and $class_names = ' class="' . $has_description . esc_attr( $class_names ) . '"';

		$output .= "<li id='menu-item-$item->ID' $class_names>";

		$attributes  = '';

		if ( !isset($item->target) ) {
			$item->target = '';
		}

		if ( !isset($item->attr_title) ) {
			$item->attr_title = '';
		}

		if ( !isset($item->xfn) ) {
			$item->xfn = '';
		}

		if ( !isset($item->url) ) {
			$item->url = '';
		}

		if ( !isset($item->title) ) {
			$item->title = '';
		}

		if ( !isset($item->ID) ) {
			$item->ID = '';
		}

		if ( !isset($args->link_before) ) {
			$args = new stdClass();
			$args->link_before = '';
		}

		if ( !isset($args->before) ) {
			$args->before = '';
		}

		if ( !isset($args->link_after) ) {
			$args->link_after = '';
		}

		if ( !isset($args->after) ) {
			$args->after = '';
		}

		! empty( $item->attr_title )
			and $attributes .= ' title="'  . esc_attr( $item->attr_title ) .'"';
		! empty( $item->target )
			and $attributes .= ' target="' . esc_attr( $item->target     ) .'"';
		! empty( $item->xfn )
			and $attributes .= ' rel="'    . esc_attr( $item->xfn        ) .'"';
		! empty( $item->url )
			and $attributes .= ' href="'   . esc_attr( $item->url        ) .'"';

		$title = apply_filters( 'the_title', $item->title, $item->ID );

		$item_output = $args->before
			. "<a $attributes>"
			. $args->link_before
			. '<span>' . $title . '</span>'
			. $description
			. '</a> '
			. $args->link_after
			. $args->after;

		// Since $output is called by reference we don't need to return anything.
		$output .= apply_filters(
			'walker_nav_menu_start_el'
		,   $item_output
		,   $item
		,   $depth
		,   $args
		);
	}
}

// Aesop chapter navigation
add_filter('aesop_chapter_scroll_nav', 'longform_change_aesop_chapter_class');
function longform_change_aesop_chapter_class() {
	return '.header_chapter_open';
}

add_filter("aesop_chapter_scroll_offset", "longform_theme_aesop_chapter_offset");
function longform_theme_aesop_chapter_offset() {
	return 50;
}

/**
 	* Creates a styled quote with large type
 	*
 	* @since    1.0.0
*/
if (!function_exists('aesop_quote_shortcode')){

	function aesop_quote_shortcode($atts, $content = null) {

		$defaults = array(
			'width'		=> '100%',
			'background' => '#222222',
			'img'		=> '',
			'text' 		=> '#FFFFFF',
			'height'	=> 'auto',
			'align'		=> 'left',
			'size'		=> '4',
			'parallax'  => '',
			'direction' => '',
			'quote'		=> '',
			'cite'		=> '',

		);
		$atts = apply_filters('aesop_quote_defaults',shortcode_atts($defaults, $atts));

		// let this be used multiple times
		static $instance = 0;
		$instance++;
		$unique = sprintf('%s-%s',get_the_ID(), $instance);

		// set component to content width
		$contentwidth = 'content' == $atts['width'] ? 'aesop-content' : false;

		// set size
		$size_unit 	= apply_filters( 'aesop_quote_size_unit', 'em', $unique );
		$size 		= $atts['size'] ? sprintf( '%s%s', $atts['size'], $size_unit ) : false;

		//bg img
		$bgimg = $atts['img'] ? sprintf('background-image:url(%s);background-size:cover;background-position:center center',esc_url( $atts['img'] )) : false;

		// set styles
		$style = $atts['background'] || $atts['text'] || $atts['height'] || $atts['width'] ? sprintf('style="background-color:%s;%s;color:%s;height:%s;width:%s;"',esc_attr( $atts['background'] ), $bgimg, esc_attr( $atts['text'] ), esc_attr( $atts['height'] ), esc_attr( $atts['width'] )) : false;

		$isparallax = 'on' == $atts['parallax'] ? 'quote-is-parallax' : false;
		$lrclass	= 'left' == $atts['direction'] || 'right' == $atts['direction'] ? 'quote-left-right' : false;

		// custom classes
		$classes = function_exists('aesop_component_classes') ? aesop_component_classes( 'quote', '' ) : null;

		// cite
		$cite = $atts['cite'] ? apply_filters('aesop_quote_component_cite',sprintf('<cite class="aesop-quote-component-cite">%s</cite>',esc_html( $atts['cite'] ))) : null;

		//align
		$align = $atts['align'] ? sprintf('aesop-component-align-%s', esc_attr($atts['align'])) : null;

		ob_start();

		do_action('aesop_quote_before'); //action
		?>
			<div id="aesop-quote-component-<?php echo esc_attr( $unique );?>" class="aesop-component aesop-quote-component <?php echo sanitize_html_class( $classes ).' '.sanitize_html_class( $align ).' '.sanitize_html_class( $contentwidth ).' '.sanitize_html_class( $isparallax ).' '.sanitize_html_class( $lrclass ).' ';?>" <?php echo $style;?>>

				<!-- Aesop Core | Quote -->
				<script>
					// <![CDATA[
					jQuery(document).ready(function(){

						var moving 		= jQuery('#aesop-quote-component-<?php echo esc_attr( $unique );?> blockquote'),
							component   = jQuery('#aesop-quote-component-<?php echo esc_attr( $unique );?>');

						// if parallax is on and we're not on mobile
						<?php if ( 'on' == $atts['parallax'] && !wp_is_mobile() ) { ?>

					       	function scrollParallax(){
					       	    var height 			= jQuery(component).height(),
        	        				offset 			= jQuery(component).offset().top,
						       	    scrollTop 		= jQuery(window).scrollTop(),
						       	    windowHeight 	= jQuery(window).height(),
						       	    position 		= Math.round( scrollTop * 0.1 );

						       	// only run parallax if in view
						       	if (offset + height <= scrollTop || offset >= scrollTop + windowHeight) {
									return;
								}

					            jQuery(moving).css({'transform':'translate3d(0px,-' + position + 'px, 0px)'});

					       	    <?php if ('left' == $atts['direction']){ ?>
					            	jQuery(moving).css({'transform':'translate3d(-' + position + 'px, 0px, 0px)'});
					            <?php } elseif ( 'right' == $atts['direction'] ) { ?>
									jQuery(moving).css({'transform':'translate3d(' + position + 'px, 0px, 0px)'});
					            <?php } ?>
					       	}
					       	jQuery(component).waypoint({
								offset: '100%',
								handler: function(direction){
						   			jQuery(this).toggleClass('aesop-quote-faded');

						   			// fire parallax
						   			scrollParallax();
									jQuery(window).scroll(function() {scrollParallax();});
							   	}
							});

						<?php } else { ?>

							jQuery(moving).waypoint({
								offset: '90%',
								handler: function(direction){
							   		jQuery(this).toggleClass('aesop-quote-faded');

							   	}
							});
						<?php } ?>

					});
					// ]]>
				</script>

				<?php do_action('aesop_quote_inside_top'); //action ?>

				<blockquote class="<?php echo sanitize_html_class( $align );?>" style="font-size:<?php echo esc_attr( $size);?>;">
					<?php echo $atts['quote'];?>

					<?php echo $cite;?>
				</blockquote>

				<?php do_action('aesop_quote_inside_bottom'); //action ?>

			</div>
		<?php
		do_action('aesop_quote_after'); //action

		return ob_get_clean();
	}
}

/**
 * Register the required plugins for this theme.
 *
 * In this example, we register two plugins - one included with the TGMPA library
 * and one from the .org repo.
 *
 * The variable passed to tgmpa_register_plugins() should be an array of plugin
 * arrays.
 *
 * This function is hooked into tgmpa_init, which is fired within the
 * TGM_Plugin_Activation class constructor.
 */
function vh_register_required_plugins() {

	/**
	 * Array of plugin arrays. Required keys are name and slug.
	 * If the source is NOT from the .org repo, then source is also required.
	 */
	$plugins = array(
	);

	/**
	 * Array of configuration settings. Amend each line as needed.
	 * If you want the default strings to be available under your own theme domain,
	 * leave the strings uncommented.
	 * Some of the strings are added into a sprintf, so see the comments at the
	 * end of each line for what each argument will be.
	 */
	$config = array(
		'domain'       		=> 'longform',         	// Text domain - likely want to be the same as your theme.
		'default_path' 		=> '',                         	// Default absolute path to pre-packaged plugins
		'parent_menu_slug' 	=> 'themes.php', 				// Default parent menu slug
		'parent_url_slug' 	=> 'themes.php', 				// Default parent URL slug
		'menu'         		=> 'install-required-plugins', 	// Menu slug
		'has_notices'      	=> true,                       	// Show admin notices or not
		'is_automatic'    	=> true,					   	// Automatically activate plugins after installation or not
		'message' 			=> '',							// Message to output right before the plugins table
		'strings'      		=> array(
			'page_title'                       			=> __( 'Install Required Plugins', 'longform' ),
			'menu_title'                       			=> __( 'Install Plugins', 'longform' ),
			'installing'                       			=> __( 'Installing Plugin: %s', 'longform' ), // %1$s = plugin name
			'oops'                             			=> __( 'Something went wrong with the plugin API.', 'longform' ),
			'notice_can_install_required'     			=> _n_noop( 'This theme requires the following plugin: %1$s.', 'This theme requires the following plugins: %1$s.', 'longform' ), // %1$s = plugin name(s)
			'notice_can_install_recommended'			=> _n_noop( 'This theme recommends the following plugin: %1$s.', 'This theme recommends the following plugins: %1$s.', 'longform' ), // %1$s = plugin name(s)
			'notice_cannot_install'  					=> _n_noop( 'Sorry, but you do not have the correct permissions to install the %s plugin. Contact the administrator of this site for help on getting the plugin installed.', 'Sorry, but you do not have the correct permissions to install the %s plugins. Contact the administrator of this site for help on getting the plugins installed.', 'longform' ), // %1$s = plugin name(s)
			'notice_can_activate_required'    			=> _n_noop( 'The following required plugin is currently inactive: %1$s.', 'The following required plugins are currently inactive: %1$s.', 'longform' ), // %1$s = plugin name(s)
			'notice_can_activate_recommended'			=> _n_noop( 'The following recommended plugin is currently inactive: %1$s.', 'The following recommended plugins are currently inactive: %1$s.', 'longform' ), // %1$s = plugin name(s)
			'notice_cannot_activate' 					=> _n_noop( 'Sorry, but you do not have the correct permissions to activate the %s plugin. Contact the administrator of this site for help on getting the plugin activated.', 'Sorry, but you do not have the correct permissions to activate the %s plugins. Contact the administrator of this site for help on getting the plugins activated.', 'longform' ), // %1$s = plugin name(s)
			'notice_ask_to_update' 						=> _n_noop( 'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.', 'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.', 'longform' ), // %1$s = plugin name(s)
			'notice_cannot_update' 						=> _n_noop( 'Sorry, but you do not have the correct permissions to update the %s plugin. Contact the administrator of this site for help on getting the plugin updated.', 'Sorry, but you do not have the correct permissions to update the %s plugins. Contact the administrator of this site for help on getting the plugins updated.', 'longform' ), // %1$s = plugin name(s)
			'install_link' 					  			=> _n_noop( 'Begin installing plugin', 'Begin installing plugins', 'longform' ),
			'activate_link' 				  			=> _n_noop( 'Activate installed plugin', 'Activate installed plugins', 'longform' ),
			'return'                           			=> __( 'Return to Required Plugins Installer', 'longform' ),
			'plugin_activated'                 			=> __( 'Plugin activated successfully.', 'longform' ),
			'complete' 									=> __( 'All plugins installed and activated successfully. %s', 'longform' ), // %1$s = dashboard link
			'nag_type'									=> 'updated' // Determines admin notice type - can only be 'updated' or 'error'
		)
	);

	tgmpa( $plugins, $config );
}
add_action( 'tgmpa_register', 'vh_register_required_plugins' );

/**
 * Initialize the options before anything else. 
 */
add_action( 'admin_init', 'longform_theme_options', 1 );

/**
 * Build the custom settings & update OptionTree.
 */
function longform_theme_options() {
	/**
	* Get a copy of Option Tree and Customizer settings if they exist.
	*/
	$ot_settings = get_option( 'longform_option_tree', array() );
	$customizer_settings = get_option( 'theme_mods_longform', array() );
	$settings_imported = get_option( 'longform_settings_imported', false );

	if ( !empty($ot_settings) && !$settings_imported ) {
		if ( $ot_settings['website_logo'] != '' ) {
			$customizer_settings['longform_logo'] = $ot_settings['website_logo'];
		}

		if ( $ot_settings['copyright_text'] != '' ) {
			$customizer_settings['longform_copyright'] = $ot_settings['copyright_text'];
		}

		if ( $ot_settings['show__scroll_to_top__button']['0'] != '' ) {
			$customizer_settings['longform_scrolltotop'] = (bool)$ot_settings['show__scroll_to_top__button']['0'];
		}

		if ( $ot_settings['favicon'] != '' ) {
			$customizer_settings['longform_favicon'] = $ot_settings['favicon'];
		}

		if ( $ot_settings['longform_layout_style'] != '' ) {
			$customizer_settings['longform_layout'] = $ot_settings['longform_layout_style'];
		}

		if ( $ot_settings['grid-tag'] != '' ) {
			$customizer_settings['longform_stories_tag'] = $ot_settings['grid-tag'];
		}

		if ( $ot_settings['grid-stories-count'] != '' ) {
			$customizer_settings['longform_stories_per_page'] = $ot_settings['grid-stories-count'];
		}

		update_option('longform_settings_imported', true);
		update_option('theme_mods_longform', $customizer_settings);
	}

	if ( function_exists('ot_get_option') ) {
		add_action('admin_notices', 'longform_admin_notice');
	}
}

function longform_aesop_component_exists( $post_id = '', $component = '' ) {
	if ( $post_id == '' ) {
		global $post;
	} else {
		$post = get_post( $post_id );
	}

	// bail if has_shortcode isn't present (pre wp 3.6)
	if ( ! function_exists( 'has_shortcode' ) ) {
		return;
	}

	// check the post content for the passed shortcode
	if ( isset( $post->post_content ) && has_shortcode( $post->post_content, 'aesop_'.$component ) ) {

		return true;

	} else {

		return false;
	}
}

function longform_allowed_tags() {
	global $allowedposttags;
	$allowedposttags['script'] = array(
		'type' => true,
		'src' => true
	);
}
add_action( 'init', 'longform_allowed_tags' );

function longform_change_aesop_social_text() {
	return __( 'STORIES ARE MEANT TO BE SHARED...', 'longform' );
}
add_filter( 'aesop_social_message', 'longform_change_aesop_social_text' );

function longform_admin_notice(){
	$customizer_url = get_admin_url().'customize.php';
	echo '<div class="notice updated">
		<p>We made changes to Theme Options, your theme settings are now at <a href="'.$customizer_url.'">customizer</a> with your saved values and Option Tree is no longer needed.</p>
	</div>';
}