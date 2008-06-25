<?php
# Mantis - a php based bugtracking system

# Copyright (C) 2000 - 2002  Kenzaburo Ito - kenito@300baud.org
# Copyright (C) 2002 - 2008  Mantis Team   - mantisbt-dev@lists.sourceforge.net

# Mantis is free software: you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation, either version 2 of the License, or
# (at your option) any later version.
#
# Mantis is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License
# along with Mantis.  If not, see <http://www.gnu.org/licenses/>.

require_once( config_get( 'absolute_path' ) . 'core.php' );
require_once( config_get( 'class_path' ) . 'MantisPlugin.class.php' );

class ManTweetPlugin extends MantisPlugin {
	/**
	 *  A method that populates the plugin information and minimum requirements.
	 */
	function register() {
		$this->name		= lang_get( 'plugin_ManTweet_title' );
		$this->description	= lang_get( 'plugin_ManTweet_description' );
		$this->page		= 'config';

		$this->version		= '1.0';
		$this->requires		= array(
			'MantisCore' => '1.2.0',
		);

		$this->author		= 'Victor Boctor';
		$this->contact		= 'vboctor@users.sourceforge.net';
		$this->url		= 'http://www.mantisbt.org';
	}

	/**
	 * Default plugin configuration.
	 */
	function config() {
		return array(
			'view_threshold'	=>	DEVELOPER,
			'post_threshold'	=>	DEVELOPER,
			'avatar_size'		=>	48,
			'post_to_twitter_threshold'	=> NOBODY,
		);
	}

	function schema() {
		return array(
			array( 'CreateTableSQL',
				array( plugin_table( 'updates' ), "
					id				I		NOTNULL UNSIGNED AUTOINCREMENT PRIMARY,
					author_id		I		NOTNULL UNSIGNED DEFAULT '0',
					project_id		I		NOTNULL UNSIGNED DEFAULT '0',
					status			C(250)	NOTNULL,
					date_submitted	T		NOTNULL,
					date_updated	T		NOTNULL
				" )
			),
		);
	}

	/**
	 * Event hook declaration.
	 */
	function hooks() {
		return array(
			'EVENT_MENU_MAIN' => 'process_main_menu' # Main Menu
		);
	}

	function process_main_menu() {
		# return plugin_page( 'index.php' );
		if ( access_has_global_level( plugin_config_get( 'view_threshold' ) ) ) {
			return array( '<a href="' . plugin_page( 'index.php' ) . '">' . lang_get( 'plugin_ManTweet_menu_item' ) . '</a>' );
		}

		return array();
	}
}
