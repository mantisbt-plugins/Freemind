<?php
# Copyright (C) 2008	Victor Boctor
#
# This program is free software: you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation, either version 3 of the License, or
# (at your option) any later version.
#
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.

/**
 * A class that contains all the information relating to a tweet.
 */
class EventLog
{
	/**
	 * The id of the event in the database.
	 */
	var $id = 0;

	/**
	 * The user id.
	 */
	var $user_id = 0;

	/**
	 * The string of the event.
	 */
	var $event = '';

	/**
	 * The timestamp at which the event was generated. 
	 */
	var $timestamp = null;
}

/**
 * Adds a tweet.  This functional sets the submitted / last updated timestamps to now.
 * 
 * @param string $p_event_text   The text of the event log.
 * @returns int  Id of the added event, or -1 if event skipped. 
 */
function EventLog_add( $p_event_text ) {
	if ( is_blank( $p_event_text ) ) {
		return -1;
	}

	$t_events_table = plugin_table( 'events' );

	$t_query = "INSERT INTO $t_events_table ( user_id, event, timestamp ) VALUES (" . db_param( 0 ) . ", " . db_param( 1 ) . ", '" . db_now() . "')";

	db_query_bound( $t_query, array( auth_get_current_user_id(), trim( $p_event_text ) ) );

	return db_insert_id( $t_events_table );
}

/**
 * Clears the event log.
 */
function EventLog_clear() {
	$t_events_table = plugin_table( 'events' );

	$t_query = "DELETE FROM $t_events_table";

	db_query_bound( $t_query, array() );
}

/**
 * Gets the events on a page given the page number (1 based)
 * and the number of events per page.
 * 
 * @param int $p_page_id   A 1-based page number.
 * @param int $p_per_page  The number of eventsto display per page.
 * 
 * @returns Array of EventLog class instances. 
 */
function EventLog_get_page( $p_page_id, $p_per_page ) {
	$t_events_table = plugin_table( 'events' );
	$t_offset = ( $p_page_id - 1 ) * $p_per_page;

	$t_query = "SELECT * FROM $t_events_table ORDER BY timestamp DESC";
	$t_result = db_query_bound( $t_query, null, $p_per_page, $t_offset );

	$t_events = array();

	while ( $t_row = db_fetch_array( $t_result ) ) {
		$t_event = new EventLog();
		$t_event->id = (integer)$t_row['id'];
		$t_event->user_id = (integer)$t_row['user_id'];
		$t_event->event = (string)$t_row['event'];
		$t_event->timestamp = db_unixtimestamp( $t_row['timestamp'] );

		$t_events[] = $t_event;
	}

	return $t_events;
}

/**
 * Gets the total number of events in the database.
 * 
 * @returns the number of events.
 */
function EventLog_get_events_count() {
	$t_events_table = plugin_table( 'events' );

	$t_query = "SELECT count(*) FROM $t_events_table";
	$t_result = db_query_bound( $t_query, null );

	return db_result( $t_result );
}

# --------------------
# Process $p_string, looking for bugnote ID references and creating bug view
#  links for them.
#
# Returns the processed string.
#
# If $p_include_anchor is true, include the href tag, otherwise just insert
#  the URL
#
# The bugnote tag ('~' by default) must be at the beginning of the string or
#  preceeded by a character that is not a letter, a number or an underscore
#
# if $p_include_anchor = false, $p_fqdn is ignored and assumed to true.

$eventlog_string_process_user_link_callback = null;
$eventlog_string_process_project_link_callback = null;

function string_process_generic_link( $p_string, $p_tag, $p_type ) {
	$t_callback = 'eventlog_string_process_' . $p_type . '_link_callback';
	global $$t_callback;

	$t_tag = $p_tag;

	# bail if the link tag is blank
	if ( '' == $t_tag || $p_string == '' ) {
		return $p_string;
	}

	if ( !isset( $$t_callback ) ) {
		$$t_callback = create_function( '$p_array', '
											$t_exists = \'' . $p_type . '_exists\';
											if ( $t_exists( (int)$p_array[2] ) ) {
												$t_get_field = \'' . $p_type . '_get_field\';
												$t_name = $t_get_field( (int)$p_array[2], \'' . ( ( $p_type == 'user' ) ? 'username' : 'name' ) . '\' );
												return \' <b>\' . $t_name . \'</b>\';
											} else {
												return $p_array[2];
											}
											' );
	}

	return preg_replace_callback( '/(^|[^\w])' . preg_quote( $t_tag, '/' ) . '(\d+)\b/', $$t_callback, $p_string );
}

