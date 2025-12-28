<?php

declare(strict_types=1);

/**
 * Redirect Handler
 *
 * Handles redirection logic for headless mode, including
 * URL validation and sanitization.
 *
 * @package HeadlessMode
 * @since 0.5.0
 */
class Headless_Mode_Redirect_Handler {

	/**
	 * Default client URL constant name.
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
	 * Get the client URL from constant or return default.
	 *
	 * @return string Client URL or default URL.
	 */
	public function get_client_url(): string {
		if ( ! defined( self::CLIENT_URL_CONSTANT ) ) {
			return $this->get_default_url();
		}

		$url = constant( self::CLIENT_URL_CONSTANT );

		return is_string( $url ) ? $url : $this->get_default_url();
	}

	/**
	 * Check if redirection is enabled (has a valid client URL).
	 *
	 * @return bool True if redirection is enabled, false otherwise.
	 */
	public function is_redirect_enabled(): bool {
		$client_url = $this->get_client_url();

		// Redirection is enabled if we have a valid, non-empty URL.
		return ! empty( $client_url ) && $this->is_valid_url( $client_url );
	}

	/**
	 * Build the redirect URL by appending the current request path.
	 *
	 * @param string $request_path The current request path.
	 * @return string Complete redirect URL.
	 */
	public function build_redirect_url( string $request_path ): string {
		$client_url = $this->get_client_url();
		$base_url   = trailingslashit( $client_url );

		return $base_url . ltrim( $request_path, '/' );
	}

	/**
	 * Validate if a URL is safe for redirection.
	 *
	 * @param string $url URL to validate.
	 * @return bool True if URL is valid, false otherwise.
	 */
	public function is_valid_url( string $url ): bool {
		if ( empty( $url ) ) {
			return false;
		}

		// Parse URL to check structure.
		$parsed = wp_parse_url( $url );

		if ( false === $parsed || empty( $parsed['scheme'] ) || empty( $parsed['host'] ) ) {
			return false;
		}

		// Allow http and https schemes.
		if ( ! in_array( $parsed['scheme'], array( 'http', 'https' ), true ) ) {
			return false;
		}

		// Use WordPress built-in URL validation for external URLs.
		// For localhost, we allow it for development purposes.
		if ( in_array( $parsed['host'], array( 'localhost', '127.0.0.1' ), true ) ) {
			return true;
		}

		$validated = wp_http_validate_url( $url );

		return $validated !== false;
	}

	/**
	 * Sanitize URL for redirection.
	 *
	 * @param string $url URL to sanitize.
	 * @return string Sanitized URL.
	 */
	public function sanitize_url( string $url ): string {
		return esc_url_raw( $url );
	}

	/**
	 * Perform the redirect.
	 *
	 * @param string  $url        URL to redirect to.
	 * @param bool    $permanent  Whether to use 301 (permanent) or 302 (temporary) redirect.
	 * @return void
	 */
	public function redirect( string $url, bool $permanent = false ): void {
		if ( ! $this->is_redirect_enabled() ) {
			return;
		}

		$sanitized_url = $this->sanitize_url( $url );

		if ( ! $this->is_valid_url( $sanitized_url ) ) {
			return;
		}

		$status_code = $permanent ? 301 : 302;
		header( 'Location: ' . $sanitized_url, true, $status_code );
		exit;
	}

	/**
	 * Check if redirect should occur based on filters.
	 *
	 * @param string $url URL that would be redirected to.
	 * @return bool True if redirect should occur, false otherwise.
	 */
	public function should_redirect( string $url ): bool {
		/**
		 * Filters whether redirect should occur.
		 *
		 * @since 0.3.0
		 *
		 * @param bool   $will_redirect If truthy redirect will happen. If not, it will not.
		 * @param string $url           The URL that would be redirected to.
		 */
		return (bool) apply_filters( 'headless_mode_will_redirect', true, $url );
	}
}
