<?php

declare(strict_types=1);

/**
 * Admin Settings
 *
 * Handles the admin settings page for Headless Mode plugin.
 *
 * @package HeadlessMode
 * @since 0.5.0
 */
class Headless_Mode_Admin_Settings {

	/**
	 * Default URL constant name.
	 */
	private const CLIENT_URL_CONSTANT = 'HEADLESS_MODE_CLIENT_URL';

	/**
	 * Get default URL from constant or fallback.
	 *
	 * @return string Default URL.
	 */
	private function get_default_url(): string {
		return defined( 'HEADLESS_MODE_DEFAULT_URL' )
			? HEADLESS_MODE_DEFAULT_URL
			: 'http://localhost:3000';
	}

	/**
	 * Redirect handler instance.
	 *
	 * @var Headless_Mode_Redirect_Handler
	 */
	private Headless_Mode_Redirect_Handler $redirect_handler;

	/**
	 * Constructor.
	 *
	 * @param Headless_Mode_Redirect_Handler $redirect_handler Redirect handler instance.
	 */
	public function __construct( Headless_Mode_Redirect_Handler $redirect_handler ) {
		$this->redirect_handler = $redirect_handler;
	}

	/**
	 * Initialize admin settings.
	 *
	 * @return void
	 */
	public function init(): void {
		add_action( 'admin_menu', array( $this, 'add_settings_page' ) );
	}

	/**
	 * Add settings page to WordPress admin menu.
	 *
	 * @return void
	 */
	public function add_settings_page(): void {
		add_submenu_page(
			'options-general.php',
			__( 'Headless Mode set up', 'headless-mode' ),
			__( 'Headless Mode', 'headless-mode' ),
			'manage_options',
			'headless-mode',
			array( $this, 'render_settings_page' )
		);
	}

	/**
	 * Render the settings page.
	 *
	 * @return void
	 */
	public function render_settings_page(): void {
		$client_url = $this->redirect_handler->get_client_url();
		$is_enabled = $this->redirect_handler->is_redirect_enabled();

		?>
		<div class="wrap">
			<h2><?php esc_html_e( 'Headless Mode', 'headless-mode' ); ?></h2>

			<?php if ( $is_enabled ) : ?>
				<div class="notice notice-success">
					<p>
						<?php esc_html_e( 'Your site is currently set to redirect to:', 'headless-mode' ); ?>
						<code><?php echo esc_html( $client_url ); ?></code>
					</p>
				</div>
			<?php else : ?>
				<div class="notice notice-warning">
					<p>
						<strong><?php esc_html_e( 'Your site is not redirecting.', 'headless-mode' ); ?></strong>
					</p>
				</div>
			<?php endif; ?>

			<p>
				<?php esc_html_e( 'Add the following to your wp-config.php file to redirect all traffic to the new front end of the site (change the URL before pasting!):', 'headless-mode' ); ?>
			</p>
			<p>
				<code>define( '<?php echo esc_html( self::CLIENT_URL_CONSTANT ); ?>', 'http://localhost:3000' );</code>
			</p>
			<p>
				<em>
					<?php
					printf(
						/* translators: %s: Default URL placeholder */
						esc_html__( 'If after saving the wp-config.php file, your site is still not redirecting, make sure you\'ve replaced %s above with your front end web address.', 'headless-mode' ),
						'<code>http://localhost:3000</code>'
					);
					?>
				</em>
			</p>
		</div>
		<?php
	}
}
