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

require_once( config_get( 'plugin_path' ) . 'EventLog' . DIRECTORY_SEPARATOR . 'EventLog_api.php' ); 

access_ensure_global_level( plugin_config_get( 'view_threshold' ) ); 

$f_page_id = gpc_get_int( 'page_id', 1 );

html_page_top1( plugin_lang_get( 'title' ) );
html_page_top2();

$t_per_page = 100;
$t_total_count = EventLog_get_events_count();
$t_total_pages_count = (integer)(( $t_total_count + ( $t_per_page - 1 ) ) / $t_per_page);

$t_events = EventLog_get_page( $f_page_id, $t_per_page );

echo '<br /><div align="center">';

if ( $f_page_id > 1 ) {
	echo '[ <a href="', plugin_page( 'index' ), '&amp;page_id=', (int)($f_page_id) - 1, '">', plugin_lang_get( 'newer_events' ), '</a> ]&nbsp;';
} else {
	echo '[ ', plugin_lang_get( 'newer_events' ), ' ]&nbsp;';
}

if ( $f_page_id < $t_total_pages_count ) {
	echo '[ <a href="', plugin_page( 'index' ), '&amp;page_id=', (int)( $f_page_id ) + 1, '">', plugin_lang_get( 'older_events' ), '</a> ]';
} else {
	echo '[ ', plugin_lang_get( 'older_events' ), ' ]';
}
echo '</div>';

echo '<div align="right">';
echo '<form method="post" action="', plugin_page ( 'eventlog_clear' ), '">';
echo '<input type="submit" name="submit" value="', plugin_lang_get( 'clear_events' ), '" />';
echo '</form>';
echo '</div>';

echo '<table class="width100">';
echo '<tr class="row-category">';
echo '<th>', lang_get( 'timestamp' ), '</th>';
echo '<th>', lang_get( 'username' ), '</th>';
echo '<th>', plugin_lang_get( 'event' ), '</th>';
echo '</tr>';

foreach ( $t_events as $t_event ) {
	$t_event_text = $t_event->event;
	$t_event_text = string_display( $t_event_text );
	$t_event_text = string_process_generic_link( $t_event_text, '@U', 'user' ); 
	$t_event_text = string_process_generic_link( $t_event_text, '@P', 'project' ); 

	echo '<tr ', helper_alternate_class(), '>';
	echo '<td width="10%">', date( config_get( 'complete_date_format' ), $t_event->timestamp ), '</td>';
	echo '<td width="10%">', print_user( $t_event->user_id ), '</td>';
	echo '<td>', $t_event_text, '</td>';
	echo '</tr>';
}

echo '</table>';
html_page_bottom1( __FILE__ );