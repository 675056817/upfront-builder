<?php
/*
Plugin Name: Upfront Theme Exporter
Plugin URI: http://premium.wpmudev.com/
Description: Exports upfront page layouts to theme.
Version: 0.0.1
Author: WPMU DEV
Text Domain: upfront_thx
Author URI: http://premium.wpmudev.com
License: GPLv2 or later
WDP ID:
*/

/*
Copyright 2009-2014 Incsub (http://incsub.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License (Version 2 - GPLv2) as published by
the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

require_once dirname(__FILE__) . '/lib/util.php';

define('THX_BASENAME', basename(dirname(__FILE__)));

class UpfrontThemeExporter {

	const DOMAIN = 'upfront_thx';

	/**
	 * Just basic, context-free bootstrap here.
	 */
	private function __construct() {}

	/**
	 * Boot point.
	 */
	public static function serve () {
		$me = new self;
		$me->_add_hooks();
	}

	/**
	 * This is where we dispatch the context-sensitive/global hooks.
	 */
	private function _add_hooks () {
		// Just dispatch specific scope hooks.
		if (upfront_exporter_is_running()) {
			$this->_add_exporter_hooks();
		}

		$this->_add_global_hooks();
	}

	/**
	 * These hooks will *always* trigger.
	 * No need to wait for the rest of Upfront, set our stuff up right now.
	 */
	private function _add_global_hooks () {
		add_action('upfront-admin_bar-process', array($this, 'add_toolbar_item'), 10, 2);
		if (is_admin() && !(defined('DOING_AJAX') && DOING_AJAX)) {
			require_once(dirname(__FILE__) . '/lib/class_thx_admin.php');
			Thx_Admin::serve();
		}
		$this->_load_textdomain();
	}

	private function _load_textdomain () {
		load_plugin_textdomain(self::DOMAIN, false, dirname(plugin_basename(__FILE__)) . '/languages');
	}

	/**
	 * Now, this is exporter-specific.
	 * Wait until Upfront is ready and set us up.
	 */
	private function _add_exporter_hooks () {
		require_once(dirname(__FILE__) . '/lib/class_thx_exporter.php');
		add_action('upfront-core-initialized', array('Thx_Exporter', 'serve'));
	}

	public function add_toolbar_item ($toolbar, $item) {
		if (!Upfront_Permissions::current(Upfront_Permissions::BOOT)) return false;
		if (empty($item['meta'])) return false; // Only actual boot item has meta set

		$toolbar->add_menu(array(
			'id' => 'upfront-create-theme',
			'title' => __('Create New Theme', self::DOMAIN),
			'href' => home_url('/create_new/theme'),
			'meta' => array( 'class' => 'upfront-create_theme' )
		));
	}

}

UpfrontThemeExporter::serve();