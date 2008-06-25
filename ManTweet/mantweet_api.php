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

class MantweetUpdate
{
	var $id = 0;
	var $status = '';
	var $author_id = 0;
	var $project_id = 0;
	var $date_submitted = null;
	var $date_updated = null;
}

function mantweet_can_post() {
	return access_has_global_level( plugin_config_get( 'post_threshold' ) );
}

function mantweet_add( $p_mantweet_update ) {
	if ( !mantweet_can_post() ) {
		access_denied();
	}

	if ( is_blank( $p_mantweet_update->status ) ) {
		error_parameters( lang_get( 'plugin_ManTweet_status_update' ) );
		trigger_error( ERROR_EMPTY_FIELD, ERROR );
	}

	$t_updates_table = plugin_table( 'updates' );

	$t_query = "INSERT INTO $t_updates_table ( author_id, status, date_submitted, date_updated ) VALUES (" . db_param( 0 ) . ", " . db_param( 1 ) . ", '" . db_now() . "', '" . db_now() . "')";

	db_query_bound( $t_query, array( $p_mantweet_update->author_id, $p_mantweet_update->status ) );

	$t_twitter_update = user_get_name( $p_mantweet_update->author_id ) . ': ' . $p_mantweet_update->status;
	twitter_update( $t_twitter_update );

	return db_insert_id( $t_updates_table );
}

function mantweet_get_page( $p_page_id, $p_per_page ) {
	$t_updates_table = plugin_table( 'updates' );
	$t_offset = ( $p_page_id - 1 ) * $p_per_page;

	$t_query = "SELECT * FROM $t_updates_table ORDER BY date_submitted DESC";
	$t_result = db_query_bound( $t_query, null, $p_per_page, $t_offset );

	$t_updates = array();
	while ( $t_row = db_fetch_array( $t_result ) ) {
		$t_current_update = new MantweetUpdate();
		$t_current_update->id = (integer)$t_row['id'];
		$t_current_update->author_id = (integer)$t_row['author_id'];
		$t_current_update->project_id = (integer)$t_row['project_id'];
		$t_current_update->status = $t_row['status'];
		$t_current_update->date_submitted = db_unixtimestamp( $t_row['date_submitted'] );
		$t_current_update->date_updated = db_unixtimestamp( $t_row['date_updated'] );

		$t_updates[] = $t_current_update;
	}

	return $t_updates;
}

function mantweet_get_updates_count() {
	$t_updates_table = plugin_table( 'updates' );

	$t_query = "SELECT count(*) FROM $t_updates_table";
	$t_result = db_query_bound( $t_query, null );

	return db_result( $t_result );
}