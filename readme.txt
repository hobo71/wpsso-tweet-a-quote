=== WPSSO Tweet a Quote - Easily Add a Twitter-style Quote with a Tweet Share Link ===
Plugin Name: WPSSO Tweet a Quote (WPSSO TAQ)
Plugin Slug: wpsso-tweet-a-quote
Text Domain: wpsso-tweet-a-quote
Domain Path: /languages
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl.txt
Donate Link: https://www.paypal.me/surniaulula
Assets URI: https://surniaulula.github.io/wpsso-tweet-a-quote/assets/
Tags: twitter, tweet, re-tweet, social, share, sharing, button, link, quote, text, highlight, shortcode, widget
Contributors: jsmoriss
Requires At Least: 3.8
Tested Up To: 4.7.3
Stable Tag: 1.1.4-1

WPSSO extension to easily add Twitter-style quoted text &mdash; with a Tweet share link &mdash; in your post and page content.

== Description ==

<p><img src="https://surniaulula.github.io/wpsso-tweet-a-quote/assets/icon-256x256.png" width="256" height="256" style="width:33%;min-width:128px;max-width:256px;float:left;margin:0 40px 20px 0;" /><strong>Add Twitter-style quoted text to your content that's easily Tweeted by readers.</strong></p>

<p>WPSSO TAQ features an easy "Tweet a Quote" toolbar button in the Visual Editor &mdash; along with a simple shortcode for the Text Editor &mdash; to create Tweetable quotes quickly and easily.</p>

<p>WPSSO TAQ uses your existing WPSSO settings to shorten URLs, add the Twitter Business @username, and recommend the author's @username after sharing.</p>

<p>Developers and advanced users will appreciate the ability to completely re-style the quote text and Tweet link.</p>

<blockquote>
<p><strong>Prerequisite</strong> &mdash; WPSSO Tweet a Quote (WPSSO TAQ) is an extension for the <a href="https://wordpress.org/plugins/wpsso/">WordPress Social Sharing Optimization (WPSSO)</a> plugin, which <em>automatically</em> generates complete and accurate meta tags + Schema markup from your content for Social Sharing Optimization (SSO) and Search Engine Optimization (SEO).</p>
</blockquote>

== Installation ==

= Install and Uninstall =

* [Install the Plugin](https://wpsso.com/codex/plugins/wpsso-tweet-a-quote/installation/install-the-plugin/)
* [Uninstall the Plugin](https://wpsso.com/codex/plugins/wpsso-tweet-a-quote/installation/uninstall-the-plugin/)

== Frequently Asked Questions ==

= Frequently Asked Questions =

== Other Notes ==

= Additional Documentation =

* [TAQ Shortcode](https://wpsso.com/codex/plugins/wpsso-tweet-a-quote/notes/taq-shortcode/)

== Screenshots ==

01. WPSSO Tweet a Quote example quote using the default CSS.
02. WPSSO Tweet a Quote features an easy to use toolbar button in the Visual Editor.
03. WPSSO Tweet a Quote settings page.

== Changelog ==

= Free / Basic Version Repository =

* [GitHub](https://surniaulula.github.io/wpsso-tweet-a-quote/)
* [WordPress.org](https://wordpress.org/plugins/wpsso-tweet-a-quote/developers/)

= Version Numbering Scheme =

Version components: `{major}.{minor}.{bugfix}-{stage}{level}`

* {major} = Major code changes / re-writes or significant feature changes.
* {minor} = New features / options were added or improved.
* {bugfix} = Bugfixes or minor improvements.
* {stage}{level} = dev &lt; a (alpha) &lt; b (beta) &lt; rc (release candidate) &lt; # (production).

Note that the production stage level can be incremented on occasion for simple text revisions and/or translation updates. See [PHP's version_compare()](http://php.net/manual/en/function.version-compare.php) documentation for additional information on "PHP-standardized" version numbering.

= Changelog / Release Notes =

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
	* Added a [TAQ Shortcode](http://wpsso.com/codex/plugins/wpsso-tweet-a-quote/notes/taq-shortcode/) documentation page.
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

**Version 1.0.0-1 (2016/11/25)**

* *New Features*
	* Initial Release.
* *Improvements*
	* None
* *Bugfixes*
	* None
* *Developer Notes*
	* None

== Upgrade Notice ==

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

