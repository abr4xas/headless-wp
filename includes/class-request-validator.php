<?php

declare(strict_types=1);

/**
 * Request Validator
 *
 * Validates different types of WordPress requests to determine
 * if they should bypass headless mode redirection.
 *
 * @package HeadlessMode
 * @since 0.5.0
 */
class Headless_Mode_Request_Validator {

	/**
	 * Check if the current request is a WordPress CRON job.
	 *
	 * @return bool True if CRON request, false otherwise.
	 */
	public static function is_cron(): bool {
		return defined( 'DOING_CRON' ) && DOING_CRON;
	}

	/**
	 * Check if the current request is a REST API request.
	 *
	 * @return bool True if REST API request, false otherwise.
	 */
	public static function is_rest_api(): bool {
		return defined( 'REST_REQUEST' ) && REST_REQUEST;
	}

	/**
	 * Check if the current request is a GraphQL request.
	 *
	 * @return bool True if GraphQL request, false otherwise.
	 */
	public static function is_graphql(): bool {
		global $wp;

		if ( defined( 'GRAPHQL_HTTP_REQUEST' ) && GRAPHQL_HTTP_REQUEST ) {
			return true;
		}

		// Check for OAuth1 REST requests (used by some GraphQL implementations).
		if ( ! empty( $wp->query_vars['rest_oauth1'] ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Check if the current request is an admin request.
	 *
	 * @return bool True if admin request, false otherwise.
	 */
	public static function is_admin(): bool {
		return is_admin();
	}

	/**
	 * Check if the request should bypass headless mode redirection.
	 *
	 * @return bool True if request should bypass redirection, false otherwise.
	 */
	public static function should_bypass_redirect(): bool {
		return self::is_cron() ||
			self::is_rest_api() ||
			self::is_graphql() ||
			self::is_admin();
	}
}
