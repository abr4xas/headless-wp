=== Headless Mode ===
Contributors: abr4xas
Donate link: https://www.paypal.com/paypalme/soyangelcruz
Tags: headless, static, gatsby, JAMstack, nextjs, react, frontend
Requires at least: 5.0
Tested up to: 6.6
Stable tag: 1.0.0
Requires PHP: 7.4
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Once you take the head off of WordPress, nobody needs to see it. This plugin hides the front end by redirecting to the shiny static (etc) site.

== Description ==

Headless Mode completely blocks access to the WordPress frontend for all users (authenticated and non-authenticated) and redirects them to your headless frontend application. This plugin is perfect for JAMstack architectures, Next.js, Gatsby, and other headless WordPress setups.

**Key Features:**

* Blocks all frontend access - No one can access the WordPress frontend, including administrators
* Automatic redirects - All frontend requests are redirected to your headless client URL
* API access preserved - REST API and GraphQL endpoints remain fully accessible
* Admin panel access - WordPress admin (wp-admin) remains accessible for content management
* CRON support - WordPress scheduled tasks continue to work normally
* Developer-friendly - Default URL set to `http://localhost:3000` for local development
* Production-ready - Easy configuration via wp-config.php constant

**What Gets Blocked:**
* All frontend pages and posts
* All frontend archives and taxonomies
* All frontend custom post types

**What Remains Accessible:**
* WordPress REST API (`/wp-json/`)
* WP GraphQL endpoints
* WordPress admin panel (`/wp-admin/`)
* WordPress CRON jobs
* All backend functionality

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/headless-mode` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress.
3. Add the following line to your `wp-config.php` file (change the URL to match your frontend application):

`define( 'HEADLESS_MODE_CLIENT_URL', 'http://localhost:3000' );`

For production, use your production frontend URL:

`define( 'HEADLESS_MODE_CLIENT_URL', 'https://your-frontend-domain.com' );`

4. Save `wp-config.php` and your site will now redirect all frontend traffic to your headless application.

== Frequently Asked Questions ==

= How do I set it up? =

Once you've activated the plugin, add the following line to your `wp-config.php` file, and save it:

`define( 'HEADLESS_MODE_CLIENT_URL', 'http://localhost:3000' );`

Be sure to change the URL to the actual URL for the front end of your site. For local development, `http://localhost:3000` is the default. For production, use your production frontend URL.

= Can I allow certain users to access the WordPress frontend? =

Yes! You can use the `headless_mode_disable_front_end` filter to customize access. For example:

`add_filter( 'headless_mode_disable_front_end', function( $disabled ) { return ! current_user_can( 'some_capability' ); });`

= What happens to REST API and GraphQL requests? =

REST API and GraphQL requests are automatically allowed and will not be redirected. Your headless frontend can continue to fetch data from WordPress normally.

= Can I customize the redirect behavior? =

Yes! Use the `headless_mode_will_redirect` filter to control when redirects occur:

`add_filter( 'headless_mode_will_redirect', function( $will_redirect, $url ) { // Your custom logic return $will_redirect; }, 10, 2 );`

= Does this affect WordPress admin? =

No. The WordPress admin panel (`/wp-admin/`) remains fully accessible for content management and administration.

= What about WordPress CRON jobs? =

WordPress scheduled tasks (CRON jobs) continue to work normally and are not affected by this plugin.

= Can I use this in development and production? =

Yes! The plugin defaults to `http://localhost:3000` for local development. Simply change the `HEADLESS_MODE_CLIENT_URL` constant in `wp-config.php` for your production environment.

== Screenshots ==

1. Admin settings page showing current redirect configuration
2. Example of redirect configuration in wp-config.php

== Changelog ==

= 1.0.0 =
* Complete code refactoring following WordPress best practices
* Converted to Object-Oriented Programming (OOP) architecture
* Added strict type declarations (`declare(strict_types=1)`)
* Separated concerns into modular classes (`/includes/` and `/admin/` directories)
* Improved security with proper URL validation and sanitization
* Enhanced escape functions for all output (using `esc_html_e()` instead of `_e()`)
* Changed default behavior to block ALL users from frontend (including administrators)
* Updated default URL to `http://localhost:3000` for local development
* Added proper text domain loading
* Added activation and deactivation hooks
* Improved code documentation with complete PHPDoc
* Better error handling and URL validation
* Performance optimizations with early returns
* Maintained backward compatibility with existing filters

= 0.4.0 =
* Checked for compatibility with the latest WP version
* Read several hot takes from Josh on Twitter. Inaccurately attributed them to Elon Musk.
* 33 points and several attaboys to Alex Standiford for testing this and submitting a PR. You're the wind beneath several wings.
* I checked again, and nobody has donated money toward the maintenance of this plugin. I'm not mad, I'm just disappointed. https://benlikes.us/donate

= 0.3.0 =
* Adds a filter named `headless_mode_will_redirect` so that individual requests can make it through to WP if needed.
* Fixed an issue with new installs showing a white screen for users not logged in, until a constant is set.
* 27 additional non-refundable points for Josh starting a new job since the last release.
* 37 points for Jason also starting a new job. Jason's additional points are for lack of hair, and totally arbitrary.
* I feel like more of you should be donating. Just saying. https://benlikes.us/donate

= 0.2.0 =
* Adds a filter for `headless_mode_disable_front_end` so that access to the front end can be granted on a per-user basis.
* Tested up to WordPress 5.5
* 17 total new points for a relatively long time with no support issues.
* The plugin still works like a CHARM, and none of you people have donated.

= 0.1.0 =
* Resolve PHP notice due to missing parentheses.
* Tally of 14 total points based on previous issues.
* Shameless pointing out that the points are all made up.

= 0.0.4 =
* More changes to the readme for display on wordpress.org
* 73% more #HiRoy. Have you said Hi to Roy today? https://hiroy.club
* Change the author URL to not be so self-serving, Ben.
* A much needed Points review by Meagan Hanes based on the above changes is pending.

= 0.0.3 =
* Modifications for the .org readme authors, etc.

= 0.0.2 =
* Enhancements to get it ready for the .org repo

== Upgrade Notice ==

= 1.0.0 =
Major refactoring release. The plugin now blocks ALL users from the frontend by default (including administrators). If you need to allow specific users, use the `headless_mode_disable_front_end` filter. Default URL changed to `http://localhost:3000` for local development.

= 0.4.0 =
Compatibility update for latest WordPress version.

= 0.3.0 =
Adds filter for redirect control and fixes white screen issue on new installs.

= 0.2.0 =
Adds filter to control frontend access on per-user basis.

== Developer Notes ==

**Filters Available:**

* `headless_mode_disable_front_end` - Control whether frontend should be disabled (default: `true`)
* `headless_mode_will_redirect` - Control whether redirect should occur (default: `true`)

**Constants:**

* `HEADLESS_MODE_CLIENT_URL` - The URL to redirect frontend traffic to (default: `http://localhost:3000`)
* `HEADLESS_MODE_DEFAULT_URL` - Internal constant for default URL value

**Architecture:**

The plugin follows WordPress best practices with:
* Object-Oriented Programming (OOP)
* Separation of concerns (`/includes/` for core logic, `/admin/` for admin functionality)
* Strict type declarations
* Proper security (sanitization, validation, escaping)
* Complete PHPDoc documentation

== Credits ==

Original plugin by [Josh Pollock, Jason Bahl, and Ben Meredith](https://github.com/Shelob9/headless-mode).

== Support ==

For support, feature requests, or contributions, please visit the [GitHub repository](https://github.com/Shelob9/headless-mode).
