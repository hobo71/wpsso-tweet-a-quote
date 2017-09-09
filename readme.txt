=== WPSSO Tweet a Quote - Twitter-style Quoted Text with Tweet Share Icon / Link and Customizable CSS ===
Plugin Name: WPSSO Tweet a Quote
Plugin Slug: wpsso-tweet-a-quote
Text Domain: wpsso-tweet-a-quote
Domain Path: /languages
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl.txt
Assets URI: https://surniaulula.github.io/wpsso-tweet-a-quote/assets/
Tags: twitter, tweet, re-tweet, social, share, sharing, button, link, quote, text, highlight, shortcode, widget
Contributors: jsmoriss
Requires At Least: 3.7
Tested Up To: 4.8.1
Requires PHP: 5.3
Stable Tag: 1.1.9

WPSSO extension to add CSS Twitter-style quoted text with a Tweet share link to post and page content (uses easily customizable CSS).

== Description ==

<img class="readme-icon" src="https://surniaulula.github.io/wpsso-tweet-a-quote/assets/icon-256x256.png">

<p><strong>Add Twitter-style quoted text to your content that's easily Tweeted by your readers.</strong></p>

<p><strong>Features an easy "Tweet a Quote" toolbar button in the Visual Editor</strong> &mdash; along with a simple shortcode for the Text Editor &mdash; to create Tweetable quotes quickly and easily.</p>

<p><strong>Uses your existing WPSSO settings to shorten URLs</strong>, add a Twitter business @username, and recommend the author's @username after sharing.</p>

<p>Developers and advanced users will appreciate the ability to easily re-style the CSS that WPSSO TAQ uses for the quote and Tweet link.</p>

<blockquote>
<p><strong>Prerequisite</strong> &mdash; WPSSO Tweet a Quote is an extension for the <a href="https://wordpress.org/plugins/wpsso/">WPSSO core plugin</a>, which <em>automatically</em> generates complete and accurate meta tags and Schema markup from your content for social media optimization (SMO) and SEO.</p>
</blockquote>

== Installation ==

= Install and Uninstall =

* [Install the WPSSO TAQ Plugin](https://wpsso.com/docs/plugins/wpsso-tweet-a-quote/installation/install-the-plugin/)
* [Uninstall the WPSSO TAQ Plugin](https://wpsso.com/docs/plugins/wpsso-tweet-a-quote/installation/uninstall-the-plugin/)

== Frequently Asked Questions ==

= Frequently Asked Questions =

== Other Notes ==

= Additional Documentation =

* [TAQ Shortcode](https://wpsso.com/docs/plugins/wpsso-tweet-a-quote/notes/taq-shortcode/)

== Screenshots ==

01. WPSSO TAQ example quote to tweet using the default stylesheet CSS.
02. WPSSO TAQ features an easy to use toolbar button in the Visual Editor for adding tweets to your content.
03. WPSSO TAQ settings page.

== Changelog ==

= Free / Basic Version Repository =

* [GitHub](https://surniaulula.github.io/wpsso-tweet-a-quote/)
* [WordPress.org](https://wordpress.org/plugins/wpsso-tweet-a-quote/developers/)

= Version Numbering =

Version components: `{major}.{minor}.{bugfix}[-{stage}.{level}]`

* {major} = Major structural code changes / re-writes or incompatible API changes.
* {minor} = New functionality was added or improved in a backwards-compatible manner.
* {bugfix} = Backwards-compatible bug fixes or small improvements.
* {stage}.{level} = Pre-production release: dev < a (alpha) < b (beta) < rc (release candidate).

= Changelog / Release Notes =

**Version 1.1.10-rc.1 (2017/09/09)**

* *New Features*
	* None
* *Improvements*
	* None
* *Bugfixes*
	* None
* *Developer Notes*
	* Minor code refactoring for WPSSO v3.46.0.

**Version 1.1.9 (2017/04/30)**

* *New Features*
	* None
* *Improvements*
	* None
* *Bugfixes*
	* None
* *Developer Notes*
	* Code refactoring to rename the $is_avail array to $avail for WPSSO v3.42.0.
	* Replaced WPSSO_VARY_USER_AGENT_DISABLE constant checks by $avail array checks.

**Version 1.1.8 (2017/04/22)**

* *New Features*
	* None
* *Improvements*
	* None
* *Bugfixes*
	* None
* *Developer Notes*
	* Added support for the new WPSSO_VARY_USER_AGENT_DISABLE constant in WPSSO v3.41.0.

**Version 1.1.7 (2017/04/16)**

* *New Features*
	* None
* *Improvements*
	* None
* *Bugfixes*
	* None
* *Developer Notes*
	* Refactored the plugin init filters and moved/renamed the registration boolean from `is_avail[$name]` to `is_avail['p_ext'][$name]`.

**Version 1.1.6 (2017/04/08)**

* *New Features*
	* None
* *Improvements*
	* None
* *Bugfixes*
	* None
* *Developer Notes*
	* Minor revision to move URLs in the extension config to the main WPSSO core plugin config.
	* Dropped the package number from the production version string.

**Version 1.1.5-1 (2017/04/05)**

* *New Features*
	* None
* *Improvements*
	* Updated the plugin icon images and the documentation URLs.
* *Bugfixes*
	* None
* *Developer Notes*
	* None

**Version 1.1.4-1 (2017/03/06)**

* *New Features*
	* None
* *Improvements*
	* None
* *Bugfixes*
	* None
* *Developer Notes*
	* Added support for SucomUtil::is_amp() in WPSSO v3.40.2-1.

**Version 1.1.3-1 (2017/01/08)**

* *New Features*
	* None
* *Improvements*
	* None
* *Bugfixes*
	* None
* *Developer Notes*
	* Added a 'plugins_loaded' action hook to load the plugin text domain.

**Version 1.1.2-1 (2016/12/12)**

* *New Features*
	* None
* *Improvements*
	* None
* *Bugfixes*
	* None
* *Developer Notes*
	* Excluded the Twitter URL from the JavaScript popup protection for mobile devices.

**Version 1.1.1-1 (2016/12/09)**

* *New Features*
	* None
* *Improvements*
	* Added a [TAQ Shortcode](http://wpsso.com/docs/plugins/wpsso-tweet-a-quote/notes/taq-shortcode/) documentation page.
* *Bugfixes*
	* None
* *Developer Notes*
	* Renamed the "text" shortcode argument to "tweet".

**Version 1.1.0-3 (2016/12/05)**

Official announcement: [New Plugin – WPSSO Tweet a Quote](https://surniaulula.com/2016/12/05/new-plugin-wpsso-tweet-a-quote/)

* *New Features*
	* None
* *Improvements*
	* Added three new options to the TAQ settings page:
		* Link the Quote Text (default is disabled).
		* Append a Tweet Icon (default is enabled).
		* Tweet Window Size (default is 580x255).
	* Removed the HTML, CSS, and JS customization options.
* *Bugfixes*
	* None
* *Developer Notes*
	* None

== Upgrade Notice ==

= 1.1.10-rc.1 =

(2017/09/09) Minor code refactoring for WPSSO v3.46.0.

= 1.1.9 =

(2017/04/30) Code refactoring to rename the $is_avail array to $avail, and replace the WPSSO_VARY_USER_AGENT_DISABLE constant checks by $avail array checks for WPSSO v3.42.0.

= 1.1.8 =

(2017/04/22) Added support for a new constant in WPSSO v3.41.0.

= 1.1.7 =

(2017/04/16) Refactored the plugin init filters and moved/renamed the registration boolean.

= 1.1.6 =

(2017/04/08) Minor revision to move URLs in the extension config to the main WPSSO core plugin config.

= 1.1.5-1 =

(2017/04/05) Updated the plugin icon images and the documentation URLs.

= 1.1.4-1 =

(2017/03/06) Added support for SucomUtil::is_amp() in WPSSO v3.40.2-1.

= 1.1.3-1 =

(2017/01/08) Added a 'plugins_loaded' action hook to load the plugin text domain.

= 1.1.2-1 =

(2016/12/12) Excluded the Twitter URL from the JavaScript popup protection for mobile devices.

= 1.1.1-1 =

(2016/12/09) Renamed the "text" shortcode argument to "tweet". Added a TAQ Shortcode documentation page.

= 1.1.0-3 =

(2016/12/05) Added three new options to the TAQ settings page. Removed the HTML, CSS, and JS customization options.

