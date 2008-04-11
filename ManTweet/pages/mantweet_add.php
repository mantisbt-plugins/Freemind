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

require_once( config_get( 'plugin_path' ) . 'ManTweet' . DIRECTORY_SEPARATOR . 'mantweet_api.php' ); 

access_ensure_global_level( plugin_config_get( 'post_threshold' ) ); 

$f_status = gpc_get_string( 'status' );

$t_status_update = new MantweetUpdate();
$t_status_update->author_id = auth_get_current_user_id();
$t_status_update->project_id = helper_get_current_project();
$t_status_update->status = $f_status;
#$t_status_update->date_submitted = date();
#$t_status_update->date_updated = date();

mantweet_add( $t_status_update );

print_successful_redirect( plugin_page( 'index', true ) );
