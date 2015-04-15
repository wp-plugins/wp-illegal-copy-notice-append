<?php

/*
  Plugin Name: WP Illegal Copy Notice Append
  Plugin URI: http://yooplugins.com/
  Description: This plugin appends a notice/warning along with a credit link from the original source, to any content that is illegally copied from your site (including content that is copied from your feeds by content scrapers). Works silently in background. See <a href="options-general.php?page=wpct_options">Settings > WP Illegal Copy Notice Append </a>
  Version: 1.0.1
  Author: RSPublishing
  Author URI: http://yooplugins.com/
  License: GPLv2 or later
  License URI: http://www.gnu.org/licenses/gpl-2.0.html/
 */

/*
  Copyright 2015 Rynaldo Stoltz (email: support@yooplugins.com | web: http://yooplugins.com/)

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License as published by
  the Free Software Foundation; either version 2 of the License, or
  (at your option) any later version.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 */

 if(is_admin()) {
	add_action('admin_menu', 'bld_menu');
}

function bld_menu() {
	add_options_page('WP Illegal Copy Notice', 'WP Illegal Copy Notice Append', 'manage_options', 'wpct_options', 'return_confs');
}

function return_confs() {
	require_once('settings.php');
}

function wpct_conf_link($links) {
  $settings_link = '<a href="options-general.php?page=wpct_options">Settings</a>';
  array_unshift($links, $settings_link);
  return $links;
}

$plugin = plugin_basename(__FILE__);
add_filter("plugin_action_links_$plugin", 'wpct_conf_link' );

function rate_wpct($links, $file) {
	if ($file == plugin_basename(__FILE__)) {
		$rate_url = 'http://wordpress.org/support/view/plugin-reviews/' . basename(dirname(__FILE__)) . '?rate=5#postform';
		$links[] = '<a href="' . $rate_url . '" target="_blank" title="Click here to rate and review this plugin on WordPress.org">Rate this plugin</a>';
	}
	return $links;
}

function add_feed_content($content) {
    $content = $content.'<p>This article belongs to <a href="' . get_bloginfo('url') . '">' . get_bloginfo('name') . '</a> ! The original article can be found here: <a href="' . get_permalink() . '" rel="bookmark" title="Permanent link to \''.get_the_title().'\'">' .get_the_title(). '</a></p><p><a href="' . get_bloginfo('url') . '">' . get_bloginfo('name') . '</a> &copy; ' . date("Y") . ' - All Rights Reserved</p>';
	return $content;
}

add_filter('the_excerpt_rss', 'add_feed_content');
add_filter('the_content_feed', 'add_feed_content');
add_filter('plugin_row_meta', 'rate_wpct', 10, 2);
add_action('wp_head', 'append_copy');

function append_copy() {

?>

<!-- WP Illegal Copy Notice script by Rynaldo Stoltz Starts - http://yooplugins.com/ -->


<script type="text/javascript">
function addTag() {
			var body_element = document.getElementsByTagName('body')[0];
			var selection = window.getSelection();
			var pagelink = "<br></br>This content was illegally copied from <a href='" + document.location.href + "'>" + document.location.href + "</a>";
			var copytext = selection + pagelink;
			var newdiv = document.createElement('div');
			newdiv.style.position = 'absolute';
			newdiv.style.left = '-99999px';
			body_element.appendChild(newdiv);
			newdiv.innerHTML = copytext;
			selection.selectAllChildren(newdiv);
			window.setTimeout(function() {
			body_element.removeChild(newdiv);
			},0);
		}
			document.oncopy = addTag;
</script>

<!-- WP Illegal Copy Notice script by Rynaldo Stoltz Ends - http://yooplugins.com/ -->


<?php } ?>