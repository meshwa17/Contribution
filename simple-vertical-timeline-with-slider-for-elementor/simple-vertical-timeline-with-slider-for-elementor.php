<?php
/**
 * Plugin Name:       Simple Vertical Timeline with Slider for Elementor
 * Plugin URI:        https://wordpress.org/plugins/simple-vertical-timeline-with-slider-for-elementor/
 * Description:       A customizable, responsive vertical timeline widget with a content slider for Elementor. Vertical timeline with a slide panel on desktop and a vertical accordion on mobile.
 * Version:           1.0.0
 * Requires at least: 5.8
 * Requires PHP:      7.4
 * Requires Plugins:  elementor
 * Author:            Meshwa Bhavsar
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       simple-vertical-timeline-with-slider-for-elementor
 * Elementor tested up to: 3.25
 *
 * @package Simple_Vertical_Timeline_With_Slider
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define( 'VTWS_VERSION', '1.0.0' );
define( 'VTWS_FILE', __FILE__ );
define( 'VTWS_PATH', plugin_dir_path( __FILE__ ) );
define( 'VTWS_URL', plugin_dir_url( __FILE__ ) );
define( 'VTWS_MIN_ELEMENTOR', '3.5.0' );
define( 'VTWS_MIN_PHP', '7.4' );

/**
 * Main plugin class (singleton).
 */
final class VTWS_Plugin {

	/**
	 * Single instance.
	 *
	 * @var VTWS_Plugin|null
	 */
	private static $instance = null;

	/**
	 * Get the instance.
	 *
	 * @return VTWS_Plugin
	 */
	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Constructor.
	 */
	private function __construct() {
		add_action( 'plugins_loaded', array( $this, 'init' ) );
	}

	/**
	 * Initialize the plugin after all plugins are loaded.
	 *
	 * @return void
	 */
	public function init() {
		// Check the PHP version.
		if ( version_compare( PHP_VERSION, VTWS_MIN_PHP, '<' ) ) {
			add_action( 'admin_notices', array( $this, 'notice_min_php' ) );
			return;
		}

		// Check that Elementor is active.
		if ( ! did_action( 'elementor/loaded' ) ) {
			add_action( 'admin_notices', array( $this, 'notice_missing_elementor' ) );
			return;
		}

		// Check the Elementor version.
		if ( ! defined( 'ELEMENTOR_VERSION' ) || version_compare( ELEMENTOR_VERSION, VTWS_MIN_ELEMENTOR, '<' ) ) {
			add_action( 'admin_notices', array( $this, 'notice_min_elementor' ) );
			return;
		}

		add_action( 'elementor/elements/categories_registered', array( $this, 'register_category' ) );
		add_action( 'elementor/widgets/register', array( $this, 'register_widgets' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'register_assets' ), 5 );
	}

	/**
	 * Register a custom widget category in the Elementor panel.
	 *
	 * @param \Elementor\Elements_Manager $elements_manager Elements manager.
	 * @return void
	 */
	public function register_category( $elements_manager ) {
		$elements_manager->add_category(
			'vtws-elements',
			array(
				'title' => esc_html__( 'Vertical Timeline', 'simple-vertical-timeline-with-slider-for-elementor' ),
				'icon'  => 'eicon-time-line',
			)
		);
	}

	/**
	 * Register the widget.
	 *
	 * @param \Elementor\Widgets_Manager $widgets_manager Widgets manager.
	 * @return void
	 */
	public function register_widgets( $widgets_manager ) {
		require_once VTWS_PATH . 'includes/class-vtws-widget.php';
		$widgets_manager->register( new \VTWS_Widget() );
	}

	/**
	 * Register (not enqueue) the front-end assets. Elementor enqueues them
	 * only on pages where the widget is actually used.
	 *
	 * @return void
	 */
	public function register_assets() {
		wp_register_style(
			'vtws-vertical-timeline',
			VTWS_URL . 'assets/css/vtws-vertical-timeline.css',
			array(),
			VTWS_VERSION
		);

		wp_register_script(
			'vtws-vertical-timeline',
			VTWS_URL . 'assets/js/vtws-vertical-timeline.js',
			array(),
			VTWS_VERSION,
			true
		);
	}

	/**
	 * Print a warning notice (only for users who can activate plugins).
	 *
	 * @param string $message Message HTML (already escaped, only <strong> allowed).
	 * @return void
	 */
	private function print_notice( $message ) {
		if ( ! current_user_can( 'activate_plugins' ) ) {
			return;
		}
		printf(
			'<div class="notice notice-warning is-dismissible"><p>%s</p></div>',
			wp_kses( $message, array( 'strong' => array() ) )
		);
	}

	/**
	 * Admin notice: Elementor is missing.
	 *
	 * @return void
	 */
	public function notice_missing_elementor() {
		$this->print_notice(
			sprintf(
				/* translators: 1: plugin name, 2: Elementor */
				esc_html__( '%1$s requires %2$s to be installed and activated.', 'simple-vertical-timeline-with-slider-for-elementor' ),
				'<strong>' . esc_html__( 'Simple Vertical Timeline with Slider for Elementor', 'simple-vertical-timeline-with-slider-for-elementor' ) . '</strong>',
				'<strong>' . esc_html__( 'Elementor', 'simple-vertical-timeline-with-slider-for-elementor' ) . '</strong>'
			)
		);
	}

	/**
	 * Admin notice: Elementor version is too old.
	 *
	 * @return void
	 */
	public function notice_min_elementor() {
		$this->print_notice(
			sprintf(
				/* translators: 1: plugin name, 2: Elementor, 3: required version */
				esc_html__( '%1$s requires %2$s version %3$s or greater.', 'simple-vertical-timeline-with-slider-for-elementor' ),
				'<strong>' . esc_html__( 'Simple Vertical Timeline with Slider for Elementor', 'simple-vertical-timeline-with-slider-for-elementor' ) . '</strong>',
				'<strong>' . esc_html__( 'Elementor', 'simple-vertical-timeline-with-slider-for-elementor' ) . '</strong>',
				esc_html( VTWS_MIN_ELEMENTOR )
			)
		);
	}

	/**
	 * Admin notice: PHP version is too old.
	 *
	 * @return void
	 */
	public function notice_min_php() {
		$this->print_notice(
			sprintf(
				/* translators: 1: plugin name, 2: PHP, 3: required version */
				esc_html__( '%1$s requires %2$s version %3$s or greater.', 'simple-vertical-timeline-with-slider-for-elementor' ),
				'<strong>' . esc_html__( 'Simple Vertical Timeline with Slider for Elementor', 'simple-vertical-timeline-with-slider-for-elementor' ) . '</strong>',
				'<strong>PHP</strong>',
				esc_html( VTWS_MIN_PHP )
			)
		);
	}
}

VTWS_Plugin::instance();
