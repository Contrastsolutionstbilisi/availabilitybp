<?php
/*
Plugin Name: WP LESS
Description: LESS extends CSS with variables, mixins, operations and nested rules. This plugin magically parse all your <code>*.less</code> files queued with <code>wp_enqueue_style</code> in WordPress.
Author: Oncle Tom
Version: 1.9.3-3
Author URI: https://oncletom.io/
Plugin URI: http://wordpress.org/extend/plugins/wp-less/

  This plugin is released under version 3 of the GPL:
  http://www.opensource.org/licenses/gpl-3.0.html
*/

if (!class_exists('WPLessPluginLoader'))
{
	require dirname(__FILE__).'/lib/Loader.class.php';
	$WPLessPlugin = WPLessPluginLoader::load(function($WPLessPlugin) {
		register_activation_hook(__FILE__, array($WPLessPlugin, 'install'));
		register_deactivation_hook(__FILE__, array($WPLessPlugin, 'uninstall'));

		$WPLessPlugin->dispatch();
	});
}
