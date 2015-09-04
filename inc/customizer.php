<?php
/**
 * Longform 1.0 Theme Customizer support
 *
 * @package WordPress
 * @subpackage Longform
 * @since Longform 1.0
 */

/**
 * Implement Theme Customizer additions and adjustments.
 *
 * @since Longform 1.0
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function longform_customize_register( $wp_customize ) {
	// Add custom description to Colors and Background sections.
	$wp_customize->get_section( 'colors' )->description           = __( 'Background may only be visible on wide screens.', 'longform' );
	$wp_customize->get_section( 'background_image' )->description = __( 'Background may only be visible on wide screens.', 'longform' );

	// Add postMessage support for site title and description.
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';

	// Rename the label to "Site Title Color" because this only affects the site title in this theme.
	$wp_customize->get_control( 'header_textcolor' )->label = __( 'Site Title Color', 'longform' );

	// Rename the label to "Display Site Title & Tagline" in order to make this option extra clear.
	$wp_customize->get_control( 'display_header_text' )->label = __( 'Display Site Title &amp; Tagline', 'longform' );

	// Add the featured content section in case it's not already there.
	$wp_customize->add_section( 'featured_content', array(
		'title'       => __( 'Featured Content', 'longform' ),
		'description' => sprintf( __( 'Use a <a href="%1$s">tag</a> to feature your posts. If no posts match the tag, <a href="%2$s">sticky posts</a> will be displayed instead.', 'longform' ), admin_url( '/edit.php?tag=featured' ), admin_url( '/edit.php?show_sticky=1' ) ),
		'priority'    => 130,
	) );

	// Add the featured content layout setting and control.
	$wp_customize->add_setting( 'featured_content_layout', array(
		'default'           => 'slider',
		'sanitize_callback' => 'longform_sanitize_layout',
	) );

	$wp_customize->add_control( 'featured_content_layout', array(
		'label'   => __( 'Layout', 'longform' ),
		'section' => 'featured_content',
		'type'    => 'select',
		'choices' => array(
			'slider' => __( 'Slider', 'longform' ),
		),
	) );

	// Add General setting panel and configure settings inside it
	$wp_customize->add_panel( 'longform_general_panel', array(
		'priority'       => 250,
		'capability'     => 'edit_theme_options',
		'title'          => __( 'General settings' , 'longform'),
		'description'    => __( 'You can configure your general theme settings here' , 'longform')
	) );

	// Website logo
	$wp_customize->add_section( 'longform_general_logo', array(
		'priority'       => 10,
		'capability'     => 'edit_theme_options',
		'title'          => __( 'Website logo' , 'longform'),
		'description'    => __( 'Please upload your logo, recommended logo size should be between 262x80' , 'longform'),
		'panel'          => 'longform_general_panel'
	) );

	$wp_customize->add_setting( 'longform_logo', array( 'sanitize_callback' => 'esc_url_raw' ) );

	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'longform_logo', array(
		'label'    => __( 'Website logo', 'longform' ),
		'section'  => 'longform_general_logo',
		'settings' => 'longform_logo',
	) ) );

	// Copyright
	$wp_customize->add_section( 'longform_general_copyright', array(
		'priority'       => 20,
		'capability'     => 'edit_theme_options',
		'title'          => __( 'Copyright' , 'longform'),
		'description'    => __( 'Please provide short copyright text which will be shown in footer.' , 'longform'),
		'panel'          => 'longform_general_panel'
	) );

	$wp_customize->add_setting( 'longform_copyright', array( 'sanitize_callback' => 'sanitize_text_field' ) );

	$wp_customize->add_control(
		'longform_copyright',
		array(
			'label'      => 'Copyright',
			'section'    => 'longform_general_copyright',
			'type'       => 'text',
		)
	);

	// Scroll to top
	$wp_customize->add_section( 'longform_general_scrolltotop', array(
		'priority'       => 30,
		'capability'     => 'edit_theme_options',
		'title'          => __( 'Scroll to top' , 'longform'),
		'description'    => __( 'Do you want to enable "Scroll to Top" button?' , 'longform'),
		'panel'          => 'longform_general_panel'
	) );

	$wp_customize->add_setting( 'longform_scrolltotop', array( 'sanitize_callback' => 'longform_sanitize_checkbox' ) );

	$wp_customize->add_control(
		'longform_scrolltotop',
		array(
			'label'      => 'Scroll to top',
			'section'    => 'longform_general_scrolltotop',
			'type'       => 'checkbox',
		)
	);

	// Favicon
	$wp_customize->add_section( 'longform_general_favicon', array(
		'priority'       => 40,
		'capability'     => 'edit_theme_options',
		'title'          => __( 'Favicon' , 'longform'),
		'description'    => __( 'Do you have favicon? You can upload it here.' , 'longform'),
		'panel'          => 'longform_general_panel'
	) );

	$wp_customize->add_setting( 'longform_favicon', array( 'sanitize_callback' => 'esc_url_raw' ) );

	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'longform_favicon', array(
		'label'    => __( 'Favicon', 'longform' ),
		'section'  => 'longform_general_favicon',
		'settings' => 'longform_favicon',
	) ) );

	// Page layout
	$wp_customize->add_section( 'longform_general_layout', array(
		'priority'       => 50,
		'capability'     => 'edit_theme_options',
		'title'          => __( 'Layout' , 'longform'),
		'description'    => __( 'Choose a layout for your theme pages. Note that a widget has to be inside widget are, or the layout won\'t change.' , 'longform'),
		'panel'          => 'longform_general_panel'
	) );

	$wp_customize->add_setting(
		'longform_layout',
		array(
			'default'           => 'full',
			'sanitize_callback' => 'sanitize_text_field'
		)
	);

	$wp_customize->add_control(
		'longform_layout',
		array(
			'type' => 'radio',
			'label' => 'Layout',
			'section' => 'longform_general_layout',
			'choices' => array(
				'full' => 'Full',
				'right' => 'Right'
			)
		)
	);

	// Add Stories grid setting panel and configure settings inside it
	$wp_customize->add_panel( 'longform_stories_panel', array(
		'priority'       => 260,
		'capability'     => 'edit_theme_options',
		'title'          => __( 'Stories grid' , 'longform'),
		'description'    => __( 'You can configure your themes stories grid here.' , 'longform')
	) );

	// Grid tag
	$wp_customize->add_section( 'longform_stories_tag', array(
		'priority'       => 10,
		'capability'     => 'edit_theme_options',
		'title'          => __( 'Grid tag' , 'longform'),
		'description'    => __( 'Please provide tag name of the posts which you want to show in "Stories grid" page.' , 'longform'),
		'panel'          => 'longform_stories_panel'
	) );

	$wp_customize->add_setting( 'longform_stories_tag', array( 'sanitize_callback' => 'sanitize_text_field' ) );

	$wp_customize->add_control(
		'longform_stories_tag',
		array(
			'label'      => 'Grid tag',
			'section'    => 'longform_stories_tag',
			'type'       => 'text',
		)
	);

	// Stories per page
	$wp_customize->add_section( 'longform_stories_perpage', array(
		'priority'       => 20,
		'capability'     => 'edit_theme_options',
		'title'          => __( 'Stories per page' , 'longform'),
		'description'    => __( 'How much stories should be showed per page?' , 'longform'),
		'panel'          => 'longform_stories_panel'
	) );

	$wp_customize->add_setting( 'longform_stories_per_page', array( 'sanitize_callback' => 'sanitize_text_field' ) );

	$wp_customize->add_control(
		'longform_stories_per_page',
		array(
			'label'      => 'Stories per page',
			'section'    => 'longform_stories_perpage',
			'type'       => 'text',
		)
	);

	// Social links
	$wp_customize->add_section( new longform_Customized_Section( $wp_customize, 'longform_social_links', array(
		'priority'       => 300,
		'capability'     => 'edit_theme_options'
		) )
	);

	$wp_customize->add_setting( 'longform_fake_field', array( 'sanitize_callback' => 'sanitize_text_field' ) );

	$wp_customize->add_control(
		'longform_fake_field',
		array(
			'label'      => '',
			'section'    => 'longform_social_links',
			'type'       => 'text'
		)
	);
}
add_action( 'customize_register', 'longform_customize_register' );

if ( class_exists( 'WP_Customize_Section' ) && !class_exists( 'longform_Customized_Section' ) ) {
	class longform_Customized_Section extends WP_Customize_Section {
		public function render() {
			$classes = 'accordion-section control-section control-section-' . $this->type;
			?>
			<li id="accordion-section-<?php echo esc_attr( $this->id ); ?>" class="<?php echo esc_attr( $classes ); ?>">
				<style type="text/css">
					.cohhe-social-profiles {
						padding: 14px;
					}
					.cohhe-social-profiles li:last-child {
						display: none !important;
					}
					.cohhe-social-profiles li i {
						width: 20px;
						height: 20px;
						display: inline-block;
						background-size: cover !important;
						margin-right: 5px;
						float: left;
					}
					.cohhe-social-profiles li i.twitter {
						background: url(<?php echo get_template_directory_uri().'/images/icons/twitter.png'; ?>);
					}
					.cohhe-social-profiles li i.facebook {
						background: url(<?php echo get_template_directory_uri().'/images/icons/facebook.png'; ?>);
					}
					.cohhe-social-profiles li i.googleplus {
						background: url(<?php echo get_template_directory_uri().'/images/icons/googleplus.png'; ?>);
					}
					.cohhe-social-profiles li i.cohhe_logo {
						background: url(<?php echo get_template_directory_uri().'/images/icons/cohhe.png'; ?>);
					}
					.cohhe-social-profiles li a {
						height: 20px;
						line-height: 20px;
					}
					#customize-theme-controls>ul>#accordion-section-longform_social_links {
						margin-top: 10px;
					}
					.cohhe-social-profiles li.documentation {
						text-align: right;
						margin-bottom: 10px;
					}
					.cohhe-social-profiles li.gopremium {
						text-align: right;
						margin-bottom: 60px;
					}
				</style>
				<ul class="cohhe-social-profiles">
					<li class="documentation"><a href="http://documentation.cohhe.com/longform" class="button button-primary button-hero" target="_blank"><?php _e( 'Documentation', 'longform' ); ?></a></li>
					<li class="gopremium"><a href="http://cohhe.com/project-view/longform-pro/" class="button button-secondary button-hero" target="_blank"><?php _e( 'Go Premium', 'longform' ); ?></a></li>
					<li class="social-twitter"><i class="twitter"></i><a href="https://twitter.com/Cohhe_Themes" target="_blank"><?php _e( 'Follow us on Twitter', 'longform' ); ?></a></li>
					<li class="social-facebook"><i class="facebook"></i><a href="https://www.facebook.com/cohhethemes" target="_blank"><?php _e( 'Join us on Facebook', 'longform' ); ?></a></li>
					<li class="social-googleplus"><i class="googleplus"></i><a href="https://plus.google.com/+Cohhe_Themes/posts" target="_blank"><?php _e( 'Join us on Google+', 'longform' ); ?></a></li>
					<li class="social-cohhe"><i class="cohhe_logo"></i><a href="http://cohhe.com/" target="_blank"><?php _e( 'Cohhe.com', 'longform' ); ?></a></li>
				</ul>
			</li>
			<?php
		}
	}
}

function longform_sanitize_checkbox( $input ) {
	// Boolean check 
	return ( ( isset( $input ) && true == $input ) ? true : false );
}

/**
 * Sanitize the Featured Content layout value.
 *
 * @since Longform 1.0
 *
 * @param string $layout Layout type.
 * @return string Filtered layout type (grid|slider).
 */
function longform_sanitize_layout( $layout ) {
	if ( ! in_array( $layout, array( 'slider' ) ) ) {
		$layout = 'slider';
	}

	return $layout;
}

/**
 * Bind JS handlers to make Theme Customizer preview reload changes asynchronously.
 *
 * @since Longform 1.0
 */
function longform_customize_preview_js() {
	wp_enqueue_script( 'longform_customizer', get_template_directory_uri() . '/js/customizer.js', array( 'customize-preview' ), '20131205', true );
}
add_action( 'customize_preview_init', 'longform_customize_preview_js' );

/**
 * Add contextual help to the Themes and Post edit screens.
 *
 * @since Longform 1.0
 *
 * @return void
 */
function longform_contextual_help() {
	if ( 'admin_head-edit.php' === current_filter() && 'post' !== $GLOBALS['typenow'] ) {
		return;
	}

	get_current_screen()->add_help_tab( array(
		'id'      => 'longform',
		'title'   => __( 'Longform 1.0', 'longform' ),
		'content' =>
			'<ul>' .
				'<li>' . sprintf( __( 'The home page features your choice of up to 6 posts prominently displayed in a grid or slider, controlled by the <a href="%1$s">featured</a> tag; you can change the tag and layout in <a href="%2$s">Appearance &rarr; Customize</a>. If no posts match the tag, <a href="%3$s">sticky posts</a> will be displayed instead.', 'longform' ), admin_url( '/edit.php?tag=featured' ), admin_url( 'customize.php' ), admin_url( '/edit.php?show_sticky=1' ) ) . '</li>' .
				'<li>' . sprintf( __( 'Enhance your site design by using <a href="%s">Featured Images</a> for posts you&rsquo;d like to stand out (also known as post thumbnails). This allows you to associate an image with your post without inserting it. Longform 1.0 uses featured images for posts and pages&mdash;above the title&mdash;and in the Featured Content area on the home page.', 'longform' ), 'http://codex.wordpress.org/Post_Thumbnails#Setting_a_Post_Thumbnail' ) . '</li>' .
				'<li>' . sprintf( __( 'For an in-depth tutorial, and more tips and tricks, visit the <a href="%s">Longform 1.0 documentation</a>.', 'longform' ), 'http://codex.wordpress.org/Longform' ) . '</li>' .
			'</ul>',
	) );
}
add_action( 'admin_head-themes.php', 'longform_contextual_help' );
add_action( 'admin_head-edit.php',   'longform_contextual_help' );
