{include file="$admingentemplates/admin_top.tpl"}
	<table cellpadding="0" cellspacing="0" border="0" width="100%">
	<tr>
		<td class="header" align="left">{$lang.menu.users} <font class="subheader">| {$lang.menu.users} | {$lang.menu.groups_list}</font></td>
	</tr>
	<tr>
		<td><div class="help_text"><span class="help_title">{$lang.default_select.help}</span>{$lang.content.group_list_help}</div></td>
	</tr>
	<tr>
		<td>
			<TABLE cellpadding="0" cellspacing="0" border="0" width="100%">
				<TR><TD>
				<div class="table_title">{$lang.content.name}:</div>
				<table cellpadding="5" cellspacing="1" border="0">
				{section name=g loop=$group_arr}
					<!-- not show administrator and guest group -->
					{if !($group_arr[g].root)}
					<tr>
						<td>{$group_arr[g].name}</td>
						<td><input type="button" class="button_2" value="{$lang.content.permission}" onclick="document.location.href='{$group_arr[g].edit_link}'"></td>
						<td><input type="button" class="button_2" value="{$lang.content.user_list}" onclick="document.location.href='{$group_arr[g].users_link}'"></td>
						<td>{if $group_arr[g].type == "f"}<input type="button" class="button_2" value="{$lang.buttons.delete}" onclick="document.location.href='{$file_name}?sel=delete&id={$group_arr[g].id}'">{/if}</td>
					</tr>
					{/if}
				{/section}
				</table>
				</TD></TR>
			</TABLE>
		</td>
	</tr>
	<tr>
		<td><input type="button" onclick="javascript: document.location.href='{$file_name}?sel=edit'" value="{$lang.buttons.add_group}">
		</td>
	</tr>
	</table>
{include file="$admingentemplates/admin_bottom.tpl"}