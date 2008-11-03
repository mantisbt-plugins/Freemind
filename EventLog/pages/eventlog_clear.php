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

EventLog_clear();

$t_redirect_url = plugin_page( 'index', /* redirect */ true );

html_page_top1();
html_meta_redirect( $t_redirect_url );
html_page_top2();

echo '<br /><div align="center">';
echo lang_get( 'operation_successful' ).'<br />';
print_bracket_link( $t_redirect_url, lang_get( 'proceed' ) );
echo '</div>';

html_page_bottom1( __FILE__ );
