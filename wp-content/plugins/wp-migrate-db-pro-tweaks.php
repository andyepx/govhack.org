<?php
/*
Plugin Name: WP Migrate DB Pro Tweaks
Plugin URI: http://github.com/deliciousbrains/wp-migrate-db-pro-tweaks
Description: Examples of using WP Migrate DB Pro's filters
Author: Delicious Brains
Version: 0.2
Author URI: http://deliciousbrains.com
*/

// Copyright (c) 2013 Delicious Brains. All rights reserved.
//
// Released under the GPL license
// http://www.opensource.org/licenses/gpl-license.php
//
// **********************************************************************
// This program is distributed in the hope that it will be useful, but
// WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
// **********************************************************************

class WP_Migrate_DB_Pro_Tweaks {
	function __construct() {
		// Uncomment the following lines to initiate an action / filter
		add_filter( 'wpmdb_preserved_options', array( $this, 'preserved_options' ) );
	}
	/**
	 * By default, 'wpmdb_settings' and 'wpmdb_error_log' are preserved when the database is overwritten in a migration.
	 * This filter allows you to define additional options (from wp_options) to preserve during a migration.
	 * The example below preserves the 'blogname' value though any number of additional options may be added.
	 */
	function preserved_options( $options ) {
        $options[] = 'blogname';    // Don't overwrite names
        $options[] = 'blog_public'; // discourage search engines setting
		return $options;
	}
}

new WP_Migrate_DB_Pro_Tweaks();