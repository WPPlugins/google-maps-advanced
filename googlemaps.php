<?php
/*
Plugin Name: Inline Google Maps
Plugin URI: http://avi.alkalay.net/2006/11/google-maps-plugin-for-wordpress.html
Description: This plugin shows google maps anywhere on blogpage. Just add a permalink of google map to any text (with images) in a page, set title="googlemap" and you're done. Also works with complex multimarker maps and KML-based maps.
Author: Avi Alkalay
Version: 5.11
Author URI: http://avi.alkalay.net/
*/

/* $Id: googlemaps.php 81498 2008-12-21 11:31:48Z avibrazil $ */

/*
Inline Google Maps - This plugin shows google maps anywhere on blogpage.

This code is licensed under the MIT License.
http://www.opensource.org/licenses/mit-license.php
Copyright (c) 2006 Mikhail Kornienko
Copyright (c) 2006 Avi Alkalay

Permission is hereby granted, free of charge, to any person
obtaining a copy of this software and associated
documentation files (the "Software"), to deal in the
Software without restriction, including without limitation
the rights to use, copy, modify, merge, publish,
distribute, sublicense, and/or sell copies of the Software,
and to permit persons to whom the Software is furnished to
do so, subject to the following conditions:

The above copyright notice and this permission notice shall
be included in all copies or substantial portions of the
Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY
KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE
WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR
PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS
OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR
OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR
OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE
SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
*/

/**
 * Function to be executed by WordPress to include the plugin in your pages/posts.
 *
 */
function googlemaps_init() {
	$googlemaps_googleMapsKey   = get_option("googlemaps_googleMapsKey");
	$googlemaps_defaultWidth    = get_option("googlemaps_defaultWidth");
	$googlemaps_defaultHeight   = get_option("googlemaps_defaultHeight");
	$googlemaps_domain          = get_option("googlemaps_domain");
	$googlemaps_useRelAttribute = get_option("googlemaps_useRelAttribute");
	$googlemaps_onHeader        = get_option("googlemaps_onHeader");
	
	if (! $googlemaps_defaultWidth) {
		$googlemaps_defaultWidth = 500;
		add_option("googlemaps_defaultWidth", $googlemaps_defaultWidth,
			"Default width for maps rendered by the Google Maps WP plugin");
	}

	if (! $googlemaps_defaultHeight) {
		$googlemaps_defaultHeight = 300;
		add_option("googlemaps_defaultHeight", $googlemaps_defaultHeight,
			"Default height for maps rendered by the Google Maps WP plugin");
	}

	if (! $googlemaps_domain) $googlemaps_domain = "com";

	if (! $googlemaps_useRelAttribute) $googlemaps_useRelAttribute = "false";

	$rand = rand();

	$script = get_settings('siteurl') . '/wp-content/plugins/'. dirname(plugin_basename(__FILE__)) . '/googlemapsPlugin.js';


	echo <<<EOF

	<!-- Google Maps Plugin (begin) -->
	<!-- http://avi.alkalay.net/2006/11/google-maps-plugin-for-wordpress.html -->

	<!-- Google Maps API -->
	<script type="text/javascript"
		src="http://maps.google.{$googlemaps_domain}/maps?file=api&amp;v=2.x&amp;key={$googlemaps_googleMapsKey}">
	</script>

	<!-- Google Maps Plugin logic -->
	<script type="text/javascript" src="$script"></script>

	<!-- Google Maps Plugin initialization -->
	<script type="text/javascript">
		//<![CDATA[

		MapPluginInit(
			/* Default map width  */          $googlemaps_defaultWidth,
			/* Default map height */          $googlemaps_defaultHeight,
			/* Use rel instad of title? */    $googlemaps_useRelAttribute);

		//]]>
	</script>

	<!-- Google Maps Plugin SEO -->
	<noscript>
		<link href="http://avi.alkalay.net/2006/11/google-maps-plugin-for-wordpress.html" rel="uses" type="text/html"/>
	</noscript>
	<!-- Google Maps Plugin (end) -->

EOF;
}

/**
 * Creates a hook in the WordPress admin system for our configuration form.
 *
 */
function googlemaps_adminPanel() {
	add_options_page('Inline Google Maps', 'Inline Google Maps', 10,
		basename(__FILE__), 'googlemaps_optionsSubpanel');
}


/**
 * The configuration form builder and logic for WordPress admin system.
 *
 */
function googlemaps_optionsSubpanel() {
	echo <<<EOF
	<div class="wrap" style="text-align:center">
	<h2>Inline Google Maps setup</h2>
EOF;

	if($_POST['action'] == "save") {
		echo "<div class=\"updated fade\" id=\"limitcatsupdatenotice\"><p>" . __("Configuration <strong>updated</strong>.") . "</p></div>";
		//updating stuff..
		update_option("googlemaps_googleMapsKey", $_POST["key"]);
		update_option("googlemaps_defaultWidth", $_POST["map_w"]);
		update_option("googlemaps_defaultHeight", $_POST["map_h"]);
		update_option("googlemaps_domain", $_POST["lang"]);
		update_option("googlemaps_useRelAttribute", $_POST["userel"]=="on"?"true":"false");
		update_option("googlemaps_onHeader", $_POST["onheader"]=="on"?"true":"false");
	}
	

	$googlemaps_googleMapsKey   = get_option("googlemaps_googleMapsKey");
	$googlemaps_defaultWidth    = get_option("googlemaps_defaultWidth");
	$googlemaps_defaultHeight   = get_option("googlemaps_defaultHeight");
	$googlemaps_domain          = get_option("googlemaps_domain");
	$googlemaps_useRelAttribute = get_option("googlemaps_useRelAttribute");
	$googlemaps_onHeader        = get_option("googlemaps_onHeader");
	
	if (! $googlemaps_defaultWidth) {
		$googlemaps_defaultWidth = 500;
		add_option("googlemaps_defaultWidth", $googlemaps_defaultWidth,
			"Default width for maps rendered by the Google Maps WP plugin");
	}

	if (! $googlemaps_defaultHeight) {
		$googlemaps_defaultHeight = 300;
		add_option("googlemaps_defaultHeight", $googlemaps_defaultHeight,
			"Default height for maps rendered by the Google Maps WP plugin");
	}

	if (! $googlemaps_domain) $googlemaps_domain = "com";

	$langs = array("com" =>  "English", "co.jp" => "Japanese");
	
	if (! $googlemaps_useRelAttribute || $googlemaps_useRelAttribute == "false")
		$googlemaps_useRelAttribute = "";
	else $googlemaps_useRelAttribute = 'checked="checked"';

	if (! $googlemaps_onHeader || $googlemaps_onHeader == "false")
		$googlemaps_onHeader = "";
	else $googlemaps_onHeader = 'checked="checked"';

	echo <<<EOF
	<form method="post">
	Your Google Maps API Key:<br><input type="text" name="key" size="90" value="$googlemaps_googleMapsKey"><br>
	<blockquote>
	<table align="center" border="0">
	<tr>
	<td align="right">
	<select name="lang">
EOF;
	foreach ($langs as $key=>$value) {
		$sel = $googlemaps_domain==$key?'selected="selected"':'';
		echo <<<EOF
		<option value="{$key}" {$sel}>{$value}</option>	
EOF;
	}
	echo <<<EOF
	</select>
	</td>
	<td align="left">Interface language:</td>
	</tr>
	<tr>
	<td align="right"><input type="text" size="4" name="map_w" value="{$googlemaps_defaultWidth}"/></td>
	<td align="left">Default map width in pixels</td>
	</tr>
	<tr>
	<td align="right"><input type="text" size="4" name="map_h" value="{$googlemaps_defaultHeight}"/></td>
	<td align="left">Default map height in pixels</td>
	</tr>
	<tr>
	<td align="right" valign="top"><input type="checkbox" name="userel" $googlemaps_useRelAttribute /></td>
	<td align="left" valign="top">Get parameters from rel="" attribute instead of title=""<br/>(for compatibility with other plugins)</td>
	</tr>
	<tr>
	<td align="right" valign="top"><input type="checkbox" name="onheader" $googlemaps_onHeader /></td>
	<td align="left" valign="top">Activate plugin in page's header instead of footer<br/>(less performantic, but sometimes more compatible)</td>
	</tr>
	</table>
	</blockquote>
	<center>
	<input type="hidden" name="action" value="save">
	<input type="submit" value="Save">
	</center>
	</form>
	<br><br>
	You can sign up for Google Maps API key <a href="http://www.google.com/apis/maps/signup.html" target="_blank">here</a>
	
	</div>
EOF;
}

//user hooks
if (get_option("googlemaps_onHeader") == "true") {
	add_action('wp_head', 'googlemaps_init');
} else {
	add_action('wp_footer', 'googlemaps_init');
}

//admin hooks
add_action('admin_menu', 'googlemaps_adminPanel');

?>
