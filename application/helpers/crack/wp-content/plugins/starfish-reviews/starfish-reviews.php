<?php
/*
Plugin Name: Starfish Reviews
Plugin URI: https://starfish.reviews/
Description: Encourage your customers to leave 5-star reviews on Google, Facebook, Yellow Pages, and more. See responses, monitor your reputation rating, and create multiple funnels with Starfish, the #1 reputation management plugin for WordPress!
Author: Starfish
Version: 1.7.2
Author URI: https://starfish.reviews/
Copyright: © 2017 - 2018 Starfish.
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: starfish
Domain Path: /languages
*/

if ( ! defined( 'ABSPATH' ) ) {
exit;
}


// Create a helper function for easy SDK access.
function starfish_fs() {
    global $starfish_fs;

    if ( ! isset( $starfish_fs ) ) {
        // Include Freemius SDK.
        require_once dirname(__FILE__) . '/freemius/start.php';

        $starfish_fs = fs_dynamic_init( array(
            'id'                  => '2029',
            'slug'                => 'starfish-reviews',
            'type'                => 'plugin',
            'public_key'          => 'pk_ada90fa5edfa2bbaa34f177f505b6',
            'is_premium'          => true,
            // If your plugin is a serviceware, set this option to false.
            'has_premium_version' => false,
            'is_premium_only'     => true,
            'has_addons'          => false,
            'has_paid_plans'      => true,
            'menu'                => array(
                'slug'           => 'starfish-settings',
                'first-path'     => 'edit.php?post_type=funnel',
								'parent'         => array(
                    'slug' => 'edit.php?post_type=starfish_review',
                ),
                'support'        => false,
                'pricing' => false,
                'contact' => false,
            ),
        ) );
    }

    return $starfish_fs;
}

/**
 * Main StarfishRM clas set up for us
 */
class StarfishRM{

	/**
	 * Constructor
	 */
	public function __construct() {
		define( 'SRM_VERSION', '1.7.1' );
		//WooCommerce variation product id for business subscription
		define( 'SRM_BUSINESS_PLAN', 'business' );
		//WooCommerce variation product id for web master subscription
		define( 'SRM_MASTER_PLAN', 'webmaster' );
		define( 'SRM_PLUGIN_URL', untrailingslashit( plugins_url( basename( plugin_dir_path( __FILE__ ) ), basename( __FILE__ ) ) ) );
		define( 'SRM_MAIN_FILE', __FILE__ );
		define( 'SRM_BASE_FOLDER', dirname( __FILE__ ) );

		// Actions
		add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'plugin_action_links' ) );
    add_action( 'plugins_loaded', array( $this, 'init' ), 0 );
    add_action( 'admin_menu', array( $this, 'srm_plugin_admin_menu' ) );
		add_action( 'single_template', array( $this, 'srm_process_review_page_template' ) );
	}

	/**
	 * Init localisations and hook
	 */
	public function init() {
		if ( ! function_exists( 'starfish_fs' ) ) {
			return;
		}

		// Init Freemius.
		starfish_fs();
		// Signal that SDK was initiated.
		do_action( 'starfish_fs_loaded' );

		starfish_fs()->add_filter( 'license_key', array( $this, 'starfish_wc_license_key_filter' ) );
		starfish_fs()->add_filter( 'license_key_maxlength', array( $this, 'starfish_wc_license_key_maxlength_filter' ) );
    starfish_fs()->add_filter( 'hide_license_key', '__return_true' );
    starfish_fs()->add_filter( 'permissions_list', array( $this, 'add_helpscount_permission' ) );

		//Starfish Misc Functions
		require_once('inc/starfish-misc-functions.php');
		//Starfish Settngs Saved
		require_once('inc/starfish-settings-process.php');
		//Register StarFish Review features
		require_once('inc/starfish-reviews.php');
		//Register StarFish Review process
		require_once('inc/starfish-reviews-process.php');
		//Register funnel features
		if ( starfish_fs()->can_use_premium_code() ) {
			require_once('inc/starfish-funnels.php');
		}
		// Localisation
		load_plugin_textdomain( 'starfish', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );

	}

	public function starfish_wc_license_key_filter( $license_key ) {

    if ( 0 === strpos( $license_key, 'wc_order_' ) ) {
      return substr( $license_key, -32);
    }

    return $license_key;
  }

  public function starfish_wc_license_key_maxlength_filter($maxlength) {
      return 38;
  }

  public function add_helpscount_permission( $permissions ) {
    $permissions['helpscout'] = array(
      'icon-class' => 'dashicons dashicons-testimonial',
      'label'      => starfish_fs()->get_text_inline( 'Help Scout', 'helpscout' ),
      'desc'       => starfish_fs()->get_text_inline( 'Rendering Help Scout\'s beacon for easy support access', 'permissions-helpscout' ),
      'priority'   => 16,
    );
  }

	/**
	 * Add relevant links to plugins page
	 * @param  array $links
	 * @return array
	 */
	public function plugin_action_links( $links ) {
		$plugin_links = array(
			'<a href="' . admin_url( 'edit.php?post_type=starfish_review&page=starfish-settings' ) . '">' . esc_html__( 'Settings', 'starfish' ) . '</a>',
		);
		return array_merge( $plugin_links, $links );
	}

	/**
	 * Install default data
	 */
	static function srm_plugin_install(){
		$starfish_pro = plugin_basename( __FILE__ ); // 'starfish_pro'
		if ( is_plugin_active( 'starfish-reviews-lite/starfish-reviews-lite.php' ) ) {
				// Plugin was active, do hook for 'myplugin'
				wp_die('Sorry, but deactive starfish reviews lite version and then activate pro plugin. <br><a href="' . admin_url( 'plugins.php' ) . '">&laquo; Return to Plugins</a>');
		}
		require_once('inc/default-settings.php');
	}

	/**
	 * Uninstall default data
	 */
	static function srm_plugin_uninstall(){
		require_once('inc/uninstall-default-settings.php');
	}


	/**
	 * Add admin settings menu
	 */
	public function srm_process_review_page_template($single_template) {
			global $post;
			if ($post->post_type == 'funnel' ) {
				$single_template = SRM_BASE_FOLDER . '/template/srm-page-template.php';
			}
			return $single_template;
			wp_reset_postdata();
	}

  /**
	 * Add admin settings menu
	 */
	public function srm_plugin_admin_menu() {
		add_submenu_page( 'edit.php?post_type=starfish_review', esc_html__( 'Starfish Settings', 'starfish' ), esc_html__( 'Settings', 'starfish' ), 'administrator', 'starfish-settings', array(	$this,	'starfish_settings_plugin_page'));
	}

	public function starfish_settings_plugin_page(){
		//Plugin settings
		require_once('inc/starfish-settings.php');
	}


	public function starfish_set_all_funnel_to_draft(){
		$srm_funnel_args = array( 'post_type' => 'funnel', 'posts_per_page' => '-1');
		$srm_funnel_query = new WP_Query( $srm_funnel_args );
		if ( $srm_funnel_query->have_posts() ) {
				while ( $srm_funnel_query->have_posts() ) {
					$srm_funnel_query->the_post();
					$funnel_id = get_the_ID();
					$post_funnel = array( 'ID' => $funnel_id, 'post_status' => 'draft' );
					wp_update_post($post_funnel);
				}
				wp_reset_postdata();
		}
	}


	public static function srm_starfish_shortcode_func( $atts, $content = "" ) {
		extract(shortcode_atts(array(
			'funnel' => '',
		), $atts));
		ob_start();
		require('inc/starfish-shortcode.php');
		$content = ob_get_clean();
		return $content;
	}


}

new StarfishRM();
add_shortcode( 'starfish', array( 'StarfishRM', 'srm_starfish_shortcode_func' ) );
register_activation_hook( __FILE__, array( 'StarfishRM', 'srm_plugin_install' ) );
register_deactivation_hook( __FILE__, array( 'StarfishRM', 'srm_plugin_uninstall' ) );
