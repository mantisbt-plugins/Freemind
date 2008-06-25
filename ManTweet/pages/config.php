<?php
# Mantis - a php based bugtracking system

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

auth_reauthenticate();
access_ensure_global_level( config_get( 'manage_plugin_threshold' ) );

html_page_top1( lang_get( 'plugin_ManTweet_title_full' ) );
html_page_top2();

print_manage_menu();

	# --------------------
	# select the proper enum values based on the input parameter
	# $p_enum_name - name of enumeration (eg: status)
	# $p_val: current value
	function print_enum_string_option_list_with_nobody( $p_enum_name, $p_val = 0 ) {
		$t_config_var_name = $p_enum_name.'_enum_string';
		$t_config_var_value = config_get( $t_config_var_name );

		$t_arr  = explode_enum_string( $t_config_var_value );
		$t_arr[] = '100:nobody';
		$t_enum_count = count( $t_arr );
		for ( $i = 0; $i < $t_enum_count; $i++) {
			$t_elem  = explode_enum_arr( $t_arr[$i] );
			$t_key = trim( $t_elem[0] );

			if ( $t_key == 100 ) {
				$t_elem2 = lang_get( 'plugin_ManTweet_access_level_nobody' );
			} else {
				$t_elem2 = get_enum_element( $p_enum_name, $t_key );
			}

			echo "<option value=\"$t_key\"";
			check_selected( $p_val, $t_key );
			echo ">$t_elem2</option>";
		} # end for
	}
?>

<br/>
<form action="<?php echo plugin_page( 'config_edit' ) ?>" method="post">
<table align="center" class="width50" cellspacing="1">

<tr>
	<td class="form-title" colspan="2">
		<?php echo lang_get( 'plugin_ManTweet_config_title' ) ?>
	</td>
</tr>

<tr <?php echo helper_alternate_class() ?>>
	<td class="category" width="60%">
		<?php echo lang_get( 'plugin_ManTweet_view_threshold' ) ?>
	</td>
	<td class="center" width="40%">
		<select name="view_threshold">
			<?php print_enum_string_option_list_with_nobody( 'access_levels', plugin_config_get( 'view_threshold' ) ) ?>
		</select>
	</td>
</tr>

<tr <?php echo helper_alternate_class() ?>>
	<td class="category">
		<?php echo lang_get( 'plugin_ManTweet_post_threshold' ) ?>
	</td>
	<td class="center" width="40%">
		<select name="post_threshold">
			<?php print_enum_string_option_list_with_nobody( 'access_levels', plugin_config_get( 'post_threshold' ) ) ?>
		</select>
	</td>
</tr>

<tr <?php echo helper_alternate_class() ?>>
	<td class="category">
		<?php echo lang_get( 'plugin_ManTweet_avatar_size' ) ?>
	</td>
	<td class="center" width="40%">
		<input type="text" name="avatar_size" value="<?php echo plugin_config_get( 'avatar_size' ) ?>" maxlength="3" size="3" />
	</td>
</tr>

<tr <?php echo helper_alternate_class() ?>>
	<td class="category">
		<?php echo lang_get( 'plugin_ManTweet_post_to_twitter_threshold' ) ?>
	</td>
	<td class="center" width="40%">
		<select name="post_to_twitter_threshold">
			<?php print_enum_string_option_list_with_nobody( 'access_levels', plugin_config_get( 'post_to_twitter_threshold' ) ) ?>
		</select>
	</td>
</tr>

<tr>
	<td class="center" colspan="2">
		<input type="submit" class="button" value="<?php echo lang_get( 'change_configuration' ) ?>" />
	</td>
</tr>

</table>
<form>

<?php
html_page_bottom1( __FILE__ );

