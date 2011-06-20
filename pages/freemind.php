<?php
# MantisBT - a php based bugtracking system
# Copyright (C) 2002 - 2010  MantisBT Team - mantisbt-dev@lists.sourceforge.net
# MantisBT is free software: you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation, either version 2 of the License, or
# (at your option) any later version.
#
# MantisBT is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License
# along with MantisBT.  If not, see <http://www.gnu.org/licenses/>.
#
# --------------------------------------------------------
# $Id$
# --------------------------------------------------------

# export the currently filtered list of bugs as a freemind mindmap
# see https://freemind.sourceforge.net
# it works with freemind version 0.8.0
# Copyright (C) 2006-2010 Peter Tandler
#    http://www.teambits.de
#
# see also:
#   http://freemind.sourceforge.net/wiki/index.php/Flash_browser


require_once( 'core.php' );
require_once( dirname( dirname( __FILE__ ) ) . '/freemind_api.php' );

auth_ensure_user_authenticated( );
helper_begin_long_process( );


# Get bug rows according to the current filter
$t_result = filter_get_bug_rows( $t_page_number, $t_per_page, $t_page_count, $t_bug_count );
if( $t_result === false ) {
	$t_result = array( );
}

// $t_filename = helper_get_default_export_filename('.mm');
$t_filename = "exported_issues.mm";

# Send headers to browser to activate mime loading
# Make sure that IE can download the attachments under https.
header( 'Pragma: public' );

freemind_export_map();
