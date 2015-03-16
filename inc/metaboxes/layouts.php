<?php
/*
 * Layout options
 */

$config = array(
	'id'       => 'vh_layouts',
	'title'    => __('Layouts', 'longform'),
	'pages'    => array('page', 'post'),
	'context'  => 'normal',
	'priority' => 'high',
);

$options = array(array(
	'name'    => __('Layout type', 'longform'),
	'id'      => 'layouts',
	'type'    => 'layouts',
	'only'    => 'page,post',
	'default' => get_option('default-layout'),
));

require_once(get_template_directory() . '/inc/metaboxes/add_metaboxes.php');
new create_meta_boxes($config, $options);