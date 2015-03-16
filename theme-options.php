<?php
/**
 * Initialize the options before anything else. 
 */
add_action( 'admin_init', 'longform_theme_options', 1 );

/**
 * Build the custom settings & update OptionTree.
 */
function longform_theme_options() {
  /**
   * Get a copy of the saved settings array. 
   */
  $saved_settings = get_option( 'option_tree_settings', array() );
  
  /**
   * Custom settings array that will eventually be 
   * passes to the OptionTree Settings API Class.
   */
  $longform_settings = array(
	'sections'        => array(
	  array(
		'id'          => 'general',
		'title'       => 'General'
	  ),
	  array(
		'id'          => 'stories_grid',
		'title'       => 'Stories grid'
	  )
	),
	'settings'        => array(
		array(
			'id'           => 'website_logo',
			'label'        => __( 'Website logo', 'longform' ),
			'desc'         => sprintf( __( 'Please upload your logo.', 'longform' ), apply_filters( 'ot_upload_text', __( 'Send to OptionTree', 'longform' ) ), 'FTP' ),
			'std'          => '',
			'type'         => 'upload',
			'section'      => 'general',
			'rows'         => '',
			'post_type'    => '',
			'taxonomy'     => '',
			'min_max_step' => '',
			'class'        => '',
			'condition'    => '',
			'operator'     => 'and'
		),
		array(
			'id'          => 'copyright_text',
			'label'       => __( 'Copyright', 'longform' ),
			'desc'        => __( 'Please provide short copyright text which will be shown in footer.', 'longform' ),
			'std'         => '',
			'type'        => 'text',
			'section'     => 'general',
			'rows'        => '',
			'post_type'   => '',
			'taxonomy'    => '',
			'min_max_step'=> '',
			'class'       => '',
			'condition'   => '',
			'operator'    => 'and'
		),
		array(
			'id'           => 'show__scroll_to_top__button',
			'label'        => __( 'Show "Scroll to Top" button', 'longform' ),
			'desc'         => __( 'Do you want to show "Scroll to Top" button?', 'longform' ),
			'std'          => 'false',
			'type'         => 'checkbox',
			'section'      => 'general',
			'rows'         => '',
			'post_type'    => '',
			'taxonomy'     => '',
			'min_max_step' => '',
			'class'        => '',
			'condition'    => '',
			'operator'     => 'and',
			'choices'      => array( 
				array(
					'value' => 'true',
					'label' => __( 'Show', 'longform' ),
					'src'   => ''
				)
			)
		),
		array(
			'id'           => 'favicon',
			'label'        => __( 'Favicon', 'longform' ),
			'desc'         => sprintf( __( 'Do you have favicon?', 'longform' ), apply_filters( 'ot_upload_text', __( 'Send to OptionTree', 'longform' ) ), 'FTP' ),
			'std'          => '',
			'type'         => 'upload',
			'section'      => 'general',
			'rows'         => '',
			'post_type'    => '',
			'taxonomy'     => '',
			'min_max_step' => '',
			'class'        => '',
			'condition'    => '',
			'operator'     => 'and'
		),
		array(
			'id'          => 'longform_layout_style',
			'label'       => 'Layout',
			'desc'        => 'Choose a layout for your theme',
			'std'         => 'full',
			'type'        => 'radio-image',
			'section'     => 'general',
			'class'       => '',
			'choices'     => array(
				// array(
				// 	'value'   => 'left',
				// 	'label'   => 'Left Sidebar',
				// 	'src'     => OT_URL . '/assets/images/layout/left-sidebar.png'
				// ),
				array(
					'value'   => 'full',
					'label'   => 'Full Width (no sidebar)',
					'src'     => OT_URL . '/assets/images/layout/full-width.png'
				),
				array(
					'value'   => 'right',
					'label'   => 'Right Sidebar',
					'src'     => OT_URL . '/assets/images/layout/right-sidebar.png'
				)
			)
		),
		array(
			'id'          => 'grid-tag',
			'label'       => __( 'Grid tag', 'longform' ),
			'desc'        => __( 'Please provide tag name of the posts which you want to show in "Stories grid" page.', 'longform' ),
			'std'         => '',
			'type'        => 'text',
			'section'     => 'stories_grid',
			'rows'        => '',
			'post_type'   => '',
			'taxonomy'    => '',
			'min_max_step'=> '',
			'class'       => '',
			'condition'   => '',
			'operator'    => 'and'
		),
		array(
			'id'          => 'grid-stories-count',
			'label'       => __( 'Stories per page', 'longform' ),
			'desc'        => __( 'How much stories should be showed per page?', 'longform' ),
			'std'         => '15',
			'type'        => 'text',
			'section'     => 'stories_grid',
			'rows'        => '',
			'post_type'   => '',
			'taxonomy'    => '',
			'min_max_step'=> '',
			'class'       => '',
			'condition'   => '',
			'operator'    => 'and'
		)
	)
  );
  
  /* settings are not the same update the DB */
  if ( $saved_settings !== $longform_settings ) {
	update_option( 'option_tree_settings', $longform_settings ); 
  }
  
}