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

access_ensure_global_level( plugin_config_get( 'view_threshold' ) ); 

$f_page_id = gpc_get_int( 'page_id', 1 );

html_page_top1( lang_get( 'plugin_ManTweet_title' ) );
html_page_top2();

$t_updates_per_page = 10;
$t_total_updates_count = mantweet_get_updates_count();
$t_total_pages_count = (integer)(( $t_total_updates_count + ( $t_updates_per_page - 1 ) ) / $t_updates_per_page);

$t_updates = mantweet_get_page( $f_page_id, $t_updates_per_page );
?>
<br />
<?php if ( mantweet_can_post() ) { ?>
<form name="tweet_form" action="<?php echo plugin_page( 'mantweet_add' ) ?>" method="post">

<table class="width50" align="center" cellspacing="1">

<tr>
	<td class="form-title">
		<?php echo lang_get( 'plugin_ManTweet_post_your_status' ) ?>
	</td>
</tr>

<tr>
	<td><input name="status" value="" size="120" maxlength="250" /></td>
</tr>

<tr>
	<td class="center">
		<input type="submit" value="<?php echo lang_get( 'plugin_ManTweet_post_status' ); ?>" />
	</td>
</tr>

</table>
</form> 
<br />
<?php } ?>

<?php
$t_avatar_size = plugin_config_get( 'avatar_size' );

echo '<center>';

if ( $f_page_id > 1 ) {
	echo '[ <a href="', plugin_page( 'index' ), '&amp;page_id=', (int)($f_page_id) - 1, '">', lang_get( 'plugin_ManTweet_newer_posts' ), '</a> ]&nbsp;';
} else {
	echo '[ ', lang_get( 'plugin_ManTweet_newer_posts' ), ' ]&nbsp;';
}

if ( $f_page_id < $t_total_pages_count ) {
	echo '[ <a href="', plugin_page( 'index' ), '&amp;page_id=', (int)($f_page_id) + 1, '">', lang_get( 'plugin_ManTweet_older_posts' ), '</a> ]';
} else {
	echo '[ ', lang_get( 'plugin_ManTweet_older_posts' ), ' ]';
}

echo '<br /><br /><table border="0" width="50%">';
foreach ( $t_updates as $t_current_update ) {
echo '<tr><td>';
#if ( ON  == config_get("show_avatar") ) {
	print_avatar( $t_current_update->author_id, $t_avatar_size );
#}
echo '</td><td>';
$t_date_format = config_get( 'complete_date_format' );
echo '<b>', user_get_name( $t_current_update->author_id ), '</b> - ', date( $t_date_format, $t_current_update->date_submitted ), '<br />';
echo string_display_links( $t_current_update->status );
echo '</td></tr>';
}
echo '</table>';

echo '</center>';

html_page_bottom1( __FILE__ );
?>

<?php if ( mantweet_can_post() ) { ?>
<!-- Autofocus JS -->
<?php if ( ON == config_get( 'use_javascript' ) ) { ?>
<script type="text/javascript" language="JavaScript">
<!--
	window.document.tweet_form.status.focus();
// -->
</script>
<?php } } ?>
