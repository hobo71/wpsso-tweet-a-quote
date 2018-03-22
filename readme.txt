=== WPSSO Tweet a Quote ===
Plugin Name: WPSSO Tweet a Quote
Plugin Slug: wpsso-tweet-a-quote
Text Domain: wpsso-tweet-a-quote
Domain Path: /languages
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl.txt
Assets URI: https://surniaulula.github.io/wpsso-tweet-a-quote/assets/
Tags: twitter, tweet, re-tweet, social, share, sharing, button, link, quote, text, highlight, shortcode, widget
Contributors: jsmoriss
Requires PHP: 5.4
Requires At Least: 3.8
Tested Up To: 4.9.4
Stable Tag: 1.2.0

WPSSO Core add-on to add Twitter-style quoted text to your content with a Tweet share link and customizable CSS.

== Description ==

<img class="readme-icon" src="https://surniaulula.github.io/wpsso-tweet-a-quote/assets/icon-256x256.png">

**WPSSO Core add-on to add Twitter-style quoted text to your content with a Tweet share link and customizable CSS.**

Features an easy "Tweet a Quote" toolbar button in the Visual Editor &mdash; along with a simple shortcode for the Text Editor &mdash; to create Tweetable quotes quickly and easily.

Uses your existing WPSSO Core settings to shorten URLs, add a Twitter business @username, and recommend the author's @username after tweeting the quote.

Developers and advanced users will appreciate the ability to easily re-style the CSS that WPSSO Tweet a Quote (aka WPSSO TAQ) uses for the quote and Tweet link.

WPSSO Tweet a Quote is *incredibly fast* and coded for performance &mdash; WPSSO Core and its add-ons make full use of all available caching techniques (persistent / non-persistent object and disk caching), and load only the PHP library files and object classes they need, keeping their code small, fast, and light. WPSSO Core and its add-ons are also fully tested and compatible with PHP v7.x (PHP v5.4 or better required).

<h3>WPSSO Core Plugin Prerequisite</h3>

WPSSO Tweet a Quote (aka WPSSO TAQ) is an add-on for the WPSSO Core plugin &mdash; which creates complete &amp; accurate meta tags and Schema markup from your existing content for social sharing, Social Media Optimization (SMO), Search Engine Optimization (SEO), Google Rich Cards, Pinterest Rich Pins, etc.

== Installation ==

<h3>Install and Uninstall</h3>

* [Install the WPSSO TAQ Add-on](https://wpsso.com/docs/plugins/wpsso-tweet-a-quote/installation/install-the-plugin/)
* [Uninstall the WPSSO TAQ Add-on](https://wpsso.com/docs/plugins/wpsso-tweet-a-quote/installation/uninstall-the-plugin/)

== Frequently Asked Questions ==

<h3>Frequently Asked Questions</h3>

== Other Notes ==

<h3>Additional Documentation</h3>

* [TAQ Shortcode](https://wpsso.com/docs/plugins/wpsso-tweet-a-quote/notes/taq-shortcode/)

== Screenshots ==

01. WPSSO TAQ example quote to tweet using the default stylesheet CSS.
02. WPSSO TAQ features an easy to use toolbar button in the Visual Editor for adding tweets to your content.
03. WPSSO TAQ settings page.

== Changelog ==

<h3>Version Numbering</h3>

Version components: `{major}.{minor}.{bugfix}[-{stage}.{level}]`

* {major} = Major structural code changes / re-writes or incompatible API changes.
* {minor} = New functionality was added or improved in a backwards-compatible manner.
* {bugfix} = Backwards-compatible bug fixes or small improvements.
* {stage}.{level} = Pre-production release: dev < a (alpha) < b (beta) < rc (release candidate).

<h3>Free / Standard Version Repositories</h3>

* [GitHub](https://surniaulula.github.io/wpsso-tweet-a-quote/)
* [WordPress.org](https://plugins.trac.wordpress.org/browser/wpsso-tweet-a-quote/)

<h3>Changelog / Release Notes</h3>

**Version 1.2.1-b.1 (2018/03/22)**

* *New Features*
	* None
* *Improvements*
	* Renamed plugin "Extensions" to "Add-ons" to avoid confusion and improve / simplify translations.
* *Bugfixes*
	* None
* *Developer Notes*
	* None

**Version 1.2.0 (2018/02/24)**

* *New Features*
	* None
* *Improvements*
	* None
* *Bugfixes*
	* None
* *Developer Notes*
	* Refactored the WpssoTaq `min_version_notice()` method to use PHP's `trigger_error()` and include a notice to refresh plugin update information.

== Upgrade Notice ==

= 1.2.1-b.1 =

(2018/03/22) Renamed plugin "Extensions" to "Add-ons" to avoid confusion and improve / simplify translations.

= l.2.0 =

(2018/02/24) Refactored the WpssoTaq min_version_notice() method to use PHP's trigger_error() and include a notice to refresh plugin update information.

