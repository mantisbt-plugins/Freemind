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
require_once( config_get( 'plugin_path' ) . 'EventLog' . DIRECTORY_SEPARATOR . 'EventLog_api.php' ); 

/**
 * A plugin that manages an event log in the database.
 */
class EventLogPlugin extends MantisPlugin {
	/**
	 *  A method that populates the plugin information and minimum requirements.
	 */
	function register() {
		$this->name		= plugin_lang_get( 'title' );
		$this->description	= plugin_lang_get( 'description' );
		$this->page		= 'config';

		$this->version		= '1.0';
		$this->requires		= array(
			'MantisCore' => '1.2.0',
		);

		$this->author		= 'Victor Boctor';
		$this->contact		= 'vboctor@users.sourceforge.net';
		$this->url			= 'http://www.mantisbt.org';
	}

	/**
	 * Gets the plugin default configuration.
	 */
	function config() {
		return array(
			'view_threshold'	=>	ADMINISTRATOR,
			'manage_threshold'	=>	ADMINISTRATOR,
			'delete_after_in_days' => 1,
		);
	}

	/**
	 * Gets the database schema of the plugin.
	 */
	function schema() {
		return array(
			array( 'CreateTableSQL',
				array( plugin_table( 'events' ), "
					id				I		NOTNULL UNSIGNED AUTOINCREMENT PRIMARY,
					user_id			I		NOTNULL UNSIGNED DEFAULT '0',
					event			C(250)	NOTNULL,
					timestamp		T		NOTNULL
				" )
			),
		);
	}

	/**
	 * Event hook declaration.
	 * 
	 * @returns An associated array that maps event names to handler names.
	 */
	function hooks() {
		return array(
			'EVENT_MENU_MANAGE' => 'process_main_menu', # Main Menu
			'EVENT_LOG' => 'process_log',
		);
	}

	function process_log( $p_event_name, $p_event_string ) {
		EventLog_add( $p_event_string );
	}

	/**
	 * If current logged in user can view EventLog, then add a menu option to the main menu.
	 * 
	 * @returns An array containing the hyper link.
	 */
	function process_main_menu() {
		if ( access_has_global_level( plugin_config_get( 'view_threshold' ) ) ) {
			return array( '<a href="' . plugin_page( 'index.php' ) . '">' . plugin_lang_get( 'menu_item' ) . '</a>' );
		}

		return array();
	}
}
