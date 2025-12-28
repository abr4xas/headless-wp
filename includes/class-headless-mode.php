<?php

declare(strict_types=1);

/**
 * Headless Mode Main Class
 *
 * Main plugin class that initializes and coordinates all components.
 *
 * @package HeadlessMode
 * @since 0.5.0
 */
class Headless_Mode {

	/**
	 * Plugin instance (singleton pattern).
	 *
	 * @var Headless_Mode|null
	 */
	private static ?Headless_Mode $instance = null;

	/**
	 * Redirect handler instance.
	 *
	 * @var Headless_Mode_Redirect_Handler
	 */
	private Headless_Mode_Redirect_Handler $redirect_handler;

	/**
	 * Admin settings instance.
	 *
	 * @var Headless_Mode_Admin_Settings|null
	 */
	private ?Headless_Mode_Admin_Settings $admin_settings = null;

	/**
	 * Get plugin instance (singleton pattern).
	 *
	 * @return Headless_Mode Plugin instance.
	 */
	public static function get_instance(): Headless_Mode {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Constructor.
	 *
	 * Private to enforce singleton pattern.
	 */
	private function __construct() {
		$this->redirect_handler = new Headless_Mode_Redirect_Handler();
	}

	/**
	 * Initialize the plugin.
	 *
	 * @return void
	 */
	public function init(): void {
		$this->load_textdomain();
		$this->init_admin();
		$this->init_redirect_handler();
	}

	/**
	 * Load plugin text domain for translations.
	 *
	 * @return void
	 */
	private function load_textdomain(): void {
		$plugin_dir = dirname( dirname( plugin_basename( __FILE__ ) ) );
		load_plugin_textdomain(
			'headless-mode',
			false,
			$plugin_dir . '/languages'
		);
	}

	/**
	 * Initialize admin settings (only in admin context).
	 *
	 * @return void
	 */
	private function init_admin(): void {
		if ( ! is_admin() ) {
			return;
		}

		$this->admin_settings = new Headless_Mode_Admin_Settings( $this->redirect_handler );
		$this->admin_settings->init();
	}

	/**
	 * Initialize redirect handler.
	 *
	 * @return void
	 */
	private function init_redirect_handler(): void {
		add_action( 'parse_request', array( $this, 'handle_frontend_redirect' ), 99 );
	}

	/**
	 * Handle frontend redirect based on request validation.
	 *
	 * @return void
	 */
	public function handle_frontend_redirect(): void {
		/**
		 * Filters whether the current user has access to the front-end.
		 *
		 * By default, the front-end is disabled for all users (authenticated and non-authenticated).
		 * Set to false to allow specific users to access the front-end.
		 *
		 * @since 0.2.0
		 * @since 0.5.0 Changed default behavior to block all users from frontend.
		 *
		 * @param bool $disabled True to disable front-end access, false to allow access.
		 */
		$disable_front_end = apply_filters(
			'headless_mode_disable_front_end',
			true
		);

		if ( false === $disable_front_end ) {
			return;
		}

		// Early return if request should bypass redirection.
		if ( Headless_Mode_Request_Validator::should_bypass_redirect() ) {
			return;
		}

		// Early return if redirection is not enabled.
		if ( ! $this->redirect_handler->is_redirect_enabled() ) {
			return;
		}

		global $wp;

		$request_path = $wp->request ?? '';
		$redirect_url = $this->redirect_handler->build_redirect_url( $request_path );

		if ( ! $this->redirect_handler->should_redirect( $redirect_url ) ) {
			return;
		}

		$this->redirect_handler->redirect( $redirect_url, true );
	}
}
