<?php
# Mantis - a php based bugtracking system
# Copyright (C) 2000 - 2002  Kenzaburo Ito - kenito@300baud.org
# Copyright (C) 2002 - 2008  Mantis Team   - mantisbt-dev@lists.sourceforge.net
# This program is distributed under the terms and conditions of the GPL
# See the README and LICENSE files for details

# export the currently filtered list of bugs as a freemind mindmap
# see https://freemind.sourceforge.net
# it works with freemind version 0.8.0
# Copyright (C) 2006-2007 Peter Tandler
#    http://www.digital-moderation.com
#
# Note: to link it directly to the Juan Pedro's 
# freemind flash browser, use the parameter
#     type_page=flash
#
# see also: 
#   freemind.html
#   http://www.efectokiwano.net/mm/
#   http://freemind.sourceforge.net/wiki/index.php/Flash_browser

# --------------------------------------------------------
# $Id: $
# --------------------------------------------------------

$t_plugin_path = config_get_global( 'plugin_path' );

require_once( $t_plugin_path . 'Freemind' . DIRECTORY_SEPARATOR . 'freemind_api.php' );  

auth_ensure_user_authenticated();

helper_begin_long_process();

$f_type_page = gpc_get_string( 'type_page', 'freemind' );

$t_filename = helper_get_default_export_filename( '.mm' );

# Send headers to browser to activate mime loading

# Make sure that IE can download the attachments under https.
header( 'Pragma: public' );

if ( $f_type_page != 'flash' ) {
	# the headers confuse the freemind flash browser
	header( 'Content-Type: text/plain; name=' . urlencode( $t_filename ) );
	header( 'Content-Transfer-Encoding: BASE64;' );

	# Added Quotes (") around file name.
	header( 'Content-Disposition: attachment; filename="' . urlencode( $t_filename ) .'"');
}

freemind_export_map();
