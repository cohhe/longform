<?php

class create_meta_boxes {
	private $config;
	protected $options;

	public function __construct($config, $options) {
		$this->config = $config;
		$this->options = $options;

		add_action('add_meta_boxes', array(&$this, 'new_meta_boxes'));
		add_action('save_post', array(&$this, 'save_meta_boxes'));
	}

	// Adds a meta box
	public function new_meta_boxes() {
		if (function_exists('add_meta_box')) {
			if (!empty($this->config['callback']) && function_exists($this->config['callback'])) {
				$callback = $this->config['callback'];
			} else {
				$callback = array(&$this, 'render');
			}

			foreach($this->config['pages'] as $page) {
				add_meta_box($this->config['id'], $this->config['title'], $callback, $page, $this->config['context'], $this->config['priority']);
			}
		}
	}

	// When the post is saved, saves our custom data
	public function save_meta_boxes($post_id) {
		if(!isset($_POST[$this->config['id'] . '_noncename']))
			return $post_id;

		// Verify this came from the our screen and with proper authorization,
		// ..because save_post can be triggered at other times
		if(!wp_verify_nonce($_POST[$this->config['id'] . '_noncename'], plugin_basename(__FILE__)))
			return $post_id;

		// Check permissions
		if('page' == $_POST['post_type'] && !current_user_can('edit_page', $post_id)) {
			if(!current_user_can('edit_post', $post_id)) {
				return $post_id;
			}

		} elseif(!current_user_can('edit_post', $post_id)) {
				return $post_id;
		}

		// Verify if this is an auto save routine
		if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
			return $post_id;

		foreach($this->options as $option) {
			if (isset($option['id']) && ! empty($option['id'])) {
				if(!isset($option['only']) || $option['only'] == $_POST['post_type'] || in_array($_POST['post_type'], explode(',', $option['only']))) {
					if(isset($_POST[$option['id']])) {
						$value = $_POST[$option['id']];
					} else {
						$value = false;
					}

					// Find and save the data
					if($value != '') {
						update_post_meta($post_id, $option['id'], $value);
					} else {
						delete_post_meta($post_id, $option['id'], get_post_meta($post_id, $option['id'], true));
					}
				}
			}
		}
	}

	// Render Meta Box content
	public function render() {
		global $post;

		echo '
			<div class="meta-box-container">';

		foreach($this->options as $option) {
			if( !isset($option['only']) || $option['only'] == $post->post_type || in_array($post->post_type, explode(',', $option['only']))) {
				if (isset($option['id'])) {
					$default = get_post_meta($post->ID, $option['id'], true);
					if ($default != "") {
						$option['default'] = $default;
					}
				}

				// Load template file
				$this->load_template($option['type'], $option);
			}
		}
		echo '
				<input type="hidden" name="' . $this->config['id'] . '_noncename" id="' . $this->config['id'] . '_noncename" value="' . wp_create_nonce(plugin_basename(__FILE__)) . '" />
			</div>';
	}

	// Load template file
	function load_template($template, $value) {
		extract($value);

		// Load template
		require_once(get_template_directory() . '/inc/metaboxes/templates/' . $template . '.php');
	}

}