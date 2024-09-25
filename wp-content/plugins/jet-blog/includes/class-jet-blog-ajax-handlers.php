<?php
/**
 * Class description
 *
 * @package   package_name
 * @author    Cherry Team
 * @license   GPL-2.0+
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Jet_Blog_Ajax_Handlers' ) ) {

	/**
	 * Define Jet_Blog_Ajax_Handlers class
	 */
	class Jet_Blog_Ajax_Handlers {

		/**
		 * A reference to an instance of this class.
		 *
		 * @since 1.0.0
		 * @var   object
		 */
		private static $instance = null;

		/**
		 * Constructor for the class
		 */
		public function init() {

			if ( ! empty( $_GET['jet_blog_ajax'] ) && ! empty( $_REQUEST['action'] ) ) {

				add_action( 'wp_ajax_jet_blog_smart_listing_get_posts', array( $this, 'get_listing_posts' ) );
				add_action( 'wp_ajax_nopriv_jet_blog_smart_listing_get_posts', array( $this, 'get_listing_posts' ) );

				add_action( 'parse_request', array( $this, 'setup_front_referrer' ) );
			}

		}

		/**
		 * Setup AJAX request
		 * 
		 * @param  [type] $wp [description]
		 * @return [type]     [description]
		 */
		public function setup_front_referrer( $wp ) {
			
			$wp->query_posts();
			$wp->register_globals();

			define( 'DOING_AJAX', true );

			if ( is_user_logged_in() ) {
				do_action( 'wp_ajax_' . $_REQUEST['action'] );
			} else {
				do_action( 'wp_ajax_nopriv_' . $_REQUEST['action'] );
			}

			die();

		}

		/**
		 * Request Smart Listing posts callback
		 *
		 * @return void
		 */
		public function get_listing_posts() {

			// JetEngine profile builder compatibility
			do_action( 'jet-engine/profile-builder/query/maybe-setup-props' );

			if ( ! class_exists( 'Elementor\Jet_Blog_Base' ) ) {
				require jet_blog()->plugin_path( 'includes/base/class-jet-blog-base.php' );
			}

			if ( ! class_exists( 'Elementor\Jet_Blog_Smart_Listing' ) ) {
				require jet_blog()->plugin_path( 'includes/addons/jet-blog-smart-listing.php' );
			}

			$widget = new Elementor\Jet_Blog_Smart_Listing();

			add_filter( 'jet-blog/smart-listing/query-args', array( $this, 'set_posts_number' ), 10 );

			$widget->_get_posts();
			$widget->_context = 'render';

			ob_start();
			$widget->_render_posts();
			$posts = ob_get_clean();

			ob_start();
			$widget->_get_arrows();
			$arrows = ob_get_clean();

			wp_send_json_success( array(
				'posts'  => $posts,
				'arrows' => $arrows,
			) );

		}

		public function set_posts_number( $query_args ) {
			$current_posts_number = $_POST['jet_request_data'];

			if ( isset( $current_posts_number['posts_per_page'] ) ) {
				$query_args['posts_per_page'] = $current_posts_number['posts_per_page'];
			}

			if ( isset( $_REQUEST['jet_widget_settings'] ) && isset( $_REQUEST['jet_widget_settings']['posts_offset'] ) ) {
			   $page   = absint( $query_args['paged'] );
			   $offset = absint( $_REQUEST['jet_widget_settings']['posts_offset'] );

			   $query_args['offset'] = $offset + ( ( $page - 1 ) * absint( $query_args['posts_per_page'] ) );
			}

			return $query_args;
		}

		/**
		 * Returns the instance.
		 *
		 * @since  1.0.0
		 * @return object
		 */
		public static function get_instance() {

			// If the single instance hasn't been set, set it now.
			if ( null == self::$instance ) {
				self::$instance = new self;
			}
			return self::$instance;
		}
	}

}

/**
 * Returns instance of Jet_Blog_Ajax_Handlers
 *
 * @return object
 */
function jet_blog_ajax_handlers() {
	return Jet_Blog_Ajax_Handlers::get_instance();
}
