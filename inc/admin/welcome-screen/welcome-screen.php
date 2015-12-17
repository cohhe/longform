<?php
/**
 * Welcome Screen Class
 */
class longform_Welcome {

	/**
	 * Constructor for the welcome screen
	 */
	public function __construct() {

		/* create dashbord page */
		add_action( 'admin_menu', array( $this, 'longform_welcome_register_menu' ) );

		/* activation notice */
		add_action( 'load-themes.php', array( $this, 'longform_activation_admin_notice' ) );

		/* enqueue script and style for welcome screen */
		add_action( 'admin_enqueue_scripts', array( $this, 'longform_welcome_style_and_scripts' ) );

		/* enqueue script for customizer */
		add_action( 'customize_controls_enqueue_scripts', array( $this, 'longform_welcome_scripts_for_customizer' ) );

		/* load welcome screen */
		add_action( 'longform_welcome', array( $this, 'longform_welcome_getting_started' ), 	    10 );
		add_action( 'longform_welcome', array( $this, 'longform_welcome_github' ), 		            40 );
		add_action( 'longform_welcome', array( $this, 'longform_welcome_free_pro' ), 				60 );

		/* ajax callback for dismissable required actions */
		add_action( 'wp_ajax_longform_dismiss_required_action', array( $this, 'longform_dismiss_required_action_callback') );
		add_action( 'wp_ajax_nopriv_longform_dismiss_required_action', array($this, 'longform_dismiss_required_action_callback') );

	}

	/**
	 * Creates the dashboard page
	 * @see  add_theme_page()
	 * @since 1.8.2.4
	 */
	public function longform_welcome_register_menu() {
		add_theme_page( 'About Longform', 'About Longform', 'activate_plugins', 'longform-welcome-screen', array( $this, 'longform_welcome_screen' ) );
	}

	/**
	 * Adds an admin notice upon successful activation.
	 * @since 1.8.2.4
	 */
	public function longform_activation_admin_notice() {
		global $pagenow;

		if ( is_admin() && ('themes.php' == $pagenow) && isset( $_GET['activated'] ) ) {
			add_action( 'admin_notices', array( $this, 'longform_welcome_admin_notice' ), 99 );
		}
	}

	/**
	 * Display an admin notice linking to the welcome screen
	 * @since 1.8.2.4
	 */
	public function longform_welcome_admin_notice() {
		?>
			<div class="updated notice is-dismissible">
				<p><?php echo sprintf( esc_html__( 'Welcome! Thank you for choosing Longform! To fully take advantage of the best our theme can offer please make sure you visit our %swelcome page%s.', 'longform' ), '<a href="' . esc_url( admin_url( 'themes.php?page=longform-welcome-screen' ) ) . '">', '</a>' ); ?></p>
				<p><a href="<?php echo esc_url( admin_url( 'themes.php?page=longform-welcome-screen' ) ); ?>" class="button" style="text-decoration: none;"><?php _e( 'Get started with Longform', 'longform' ); ?></a></p>
			</div>
		<?php
	}

	/**
	 * Load welcome screen css and javascript
	 * @since  1.8.2.4
	 */
	public function longform_welcome_style_and_scripts( $hook_suffix ) {

		if ( 'appearance_page_longform-welcome-screen' == $hook_suffix ) {
			wp_enqueue_style( 'longform-welcome-screen-screen-css', get_template_directory_uri() . '/inc/admin/welcome-screen/css/welcome.css' );
			wp_enqueue_script( 'longform-welcome-screen-screen-js', get_template_directory_uri() . '/inc/admin/welcome-screen/js/welcome.js', array('jquery') );

			global $longform_required_actions;

			$nr_actions_required = 0;

			/* get number of required actions */
			if( get_option('longform_show_required_actions') ):
				$longform_show_required_actions = get_option('longform_show_required_actions');
			else:
				$longform_show_required_actions = array();
			endif;

			if( !empty($longform_required_actions) ):
				foreach( $longform_required_actions as $longform_required_action_value ):
					if(( !isset( $longform_required_action_value['check'] ) || ( isset( $longform_required_action_value['check'] ) && ( $longform_required_action_value['check'] == false ) ) ) && ((isset($longform_show_required_actions[$longform_required_action_value['id']]) && ($longform_show_required_actions[$longform_required_action_value['id']] == true)) || !isset($longform_show_required_actions[$longform_required_action_value['id']]) )) :
						$nr_actions_required++;
					endif;
				endforeach;
			endif;

			wp_localize_script( 'longform-welcome-screen-screen-js', 'longformWelcomeScreenObject', array(
				'nr_actions_required' => $nr_actions_required,
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
				'template_directory' => get_template_directory_uri(),
				'no_required_actions_text' => __( 'Hooray! There are no required actions for you right now.','longform' )
			) );
		}
	}

	/**
	 * Load scripts for customizer page
	 * @since  1.8.2.4
	 */
	public function longform_welcome_scripts_for_customizer() {

		wp_enqueue_style( 'longform-welcome-screen-screen-customizer-css', get_template_directory_uri() . '/inc/admin/welcome-screen/css/welcome_customizer.css' );
		wp_enqueue_script( 'longform-welcome-screen-screen-customizer-js', get_template_directory_uri() . '/inc/admin/welcome-screen/js/welcome_customizer.js', array('jquery'), '20120206', true );

		global $longform_required_actions;

		$nr_actions_required = 0;

		/* get number of required actions */
		if( get_option('longform_show_required_actions') ):
			$longform_show_required_actions = get_option('longform_show_required_actions');
		else:
			$longform_show_required_actions = array();
		endif;

		if( !empty($longform_required_actions) ):
			foreach( $longform_required_actions as $longform_required_action_value ):
				if(( !isset( $longform_required_action_value['check'] ) || ( isset( $longform_required_action_value['check'] ) && ( $longform_required_action_value['check'] == false ) ) ) && ((isset($longform_show_required_actions[$longform_required_action_value['id']]) && ($longform_show_required_actions[$longform_required_action_value['id']] == true)) || !isset($longform_show_required_actions[$longform_required_action_value['id']]) )) :
					$nr_actions_required++;
				endif;
			endforeach;
		endif;

		wp_localize_script( 'longform-welcome-screen-screen-customizer-js', 'longformWelcomeScreenCustomizerObject', array(
			'nr_actions_required' => $nr_actions_required,
			'aboutpage' => esc_url( admin_url( 'themes.php?page=longform-welcome-screen#actions_required' ) ),
			'customizerpage' => esc_url( admin_url( 'customize.php#actions_required' ) ),
			'themeinfo' => __('View Theme Info','longform'),
		) );
	}

	/**
	 * Dismiss required actions
	 * @since 1.8.2.4
	 */
	public function longform_dismiss_required_action_callback() {

		global $longform_required_actions;

		$longform_dismiss_id = (isset($_GET['dismiss_id'])) ? $_GET['dismiss_id'] : 0;

		echo $longform_dismiss_id; /* this is needed and it's the id of the dismissable required action */

		if( !empty($longform_dismiss_id) ):

			/* if the option exists, update the record for the specified id */
			if( get_option('longform_show_required_actions') ):

				$longform_show_required_actions = get_option('longform_show_required_actions');

				$longform_show_required_actions[$longform_dismiss_id] = false;

				update_option( 'longform_show_required_actions',$longform_show_required_actions );

			/* create the new option,with false for the specified id */
			else:

				$longform_show_required_actions_new = array();

				if( !empty($longform_required_actions) ):

					foreach( $longform_required_actions as $longform_required_action ):

						if( $longform_required_action['id'] == $longform_dismiss_id ):
							$longform_show_required_actions_new[$longform_required_action['id']] = false;
						else:
							$longform_show_required_actions_new[$longform_required_action['id']] = true;
						endif;

					endforeach;

				update_option( 'longform_show_required_actions', $longform_show_required_actions_new );

				endif;

			endif;

		endif;

		die(); // this is required to return a proper result
	}


	/**
	 * Welcome screen content
	 * @since 1.8.2.4
	 */
	public function longform_welcome_screen() {

		require_once( ABSPATH . 'wp-load.php' );
		require_once( ABSPATH . 'wp-admin/admin.php' );
		require_once( ABSPATH . 'wp-admin/admin-header.php' );
		?>

		<ul class="welcome-screen-nav-tabs" role="tablist">
			<li role="presentation" class="active"><a href="#getting_started" aria-controls="getting_started" role="tab" data-toggle="tab"><?php esc_html_e( 'Getting started','longform'); ?></a></li>
			<li role="presentation"><a href="#github" aria-controls="github" role="tab" data-toggle="tab"><?php esc_html_e( 'Contribute','longform'); ?></a></li>
			<li role="presentation"><a href="#free_pro" aria-controls="free_pro" role="tab" data-toggle="tab"><?php esc_html_e( 'Free VS PRO','longform'); ?></a></li>
		</ul>

		<div class="welcome-screen-tab-content">

			<?php
			/**
			 * @hooked longform_welcome_getting_started - 10
			 * @hooked longform_welcome_actions_required - 20
			 * @hooked longform_welcome_child_themes - 30
			 * @hooked longform_welcome_github - 40
			 * @hooked longform_welcome_changelog - 50
			 * @hooked longform_welcome_free_pro - 60
			 */
			do_action( 'longform_welcome' ); ?>

		</div>
		<?php
	}

	/**
	 * Getting started
	 * @since 1.8.2.4
	 */
	public function longform_welcome_getting_started() {
		require_once( get_template_directory() . '/inc/admin/welcome-screen/sections/getting-started.php' );
	}

	/**
	 * Contribute
	 * @since 1.8.2.4
	 */
	public function longform_welcome_github() {
		require_once( get_template_directory() . '/inc/admin/welcome-screen/sections/github.php' );
	}

	/**
	 * Free vs PRO
	 * @since 1.8.2.4
	 */
	public function longform_welcome_free_pro() {
		require_once( get_template_directory() . '/inc/admin/welcome-screen/sections/free_pro.php' );
	}
}

$GLOBALS['longform_Welcome'] = new longform_Welcome();