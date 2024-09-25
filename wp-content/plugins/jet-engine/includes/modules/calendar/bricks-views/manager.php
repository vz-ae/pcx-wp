<?php
/**
 * Bricks views manager
 */
namespace Jet_Engine\Modules\Calendar\Bricks_Views;

if ( ! defined( 'WPINC' ) ) {
	die;
}

class Manager {
	/**
	 * Elementor Frontend instance
	 *
	 * @var null
	 */
	public $frontend = null;

	/**
	 * Constructor for the class
	 */
	function __construct() {
		add_action( 'jet-engine/bricks-views/init', array( $this, 'init' ), 10 );
	}

	public function init() {
		add_action( 'jet-engine/bricks-views/register-elements', array( $this, 'register_elements' ), 11 );
	}

	public function register_elements() {
		\Bricks\Elements::register_element( $this->module_path( 'calendar.php' ) );
	}

	public function module_path( $relative_path = '' ) {
		return jet_engine()->plugin_path( 'includes/modules/calendar/bricks-views/' . $relative_path );
	}
}