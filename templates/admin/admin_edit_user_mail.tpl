{include file="$admingentemplates/admin_top.tpl"}
	<table cellpadding="0" cellspacing="0" border="0" width="100%">
		<tr>
			<td class="header" align="left">{$lang.menu.users} <font class="subheader">| {$lang.menu.users} | {$lang.menu.users_list} | {$user.login} | {$lang.content.mail_link}</font></td>
		</tr>
		<tr>
			<td><div class="help_text"><span class="help_title">{$lang.default_select.help}</span>{$lang.content.user_mail_help}</div></td>
		</tr>
		<tr>
			<td width="100%" class="main_content_text">
				<table cellpadding="5" cellspacing="1" class="table_main" cellspacing="0" width="100%" border="0">
				<tr bgcolor="#FFFFFF">
					<td class="main_header_text" width="150" align="center">
						<div style="cursor: pointer;" onclick="javascript:location.href='{$file_name}?sel={$sel}&id_user={$id_user}&sorter=1&order={$order}';">{$lang.content.from}</div>
					</td>
					<td class="main_header_text" width="150" align="center">
						<div style="cursor: pointer;" onclick="javascript:location.href='{$file_name}?sel={$sel}&id_user={$id_user}&sorter=2&order={$order}';">{$lang.content.to}</div>
					</td>
					<td class="main_header_text" width="120" align="center">
						<div style="cursor: pointer;" onclick="javascript:location.href='{$file_name}?sel={$sel}&id_user={$id_user}&sorter=3&order={$order}';">{$lang.content.time}</div>
					</td>
					<td class="main_header_text" align="center">{$lang.content.body}</td>
				</tr>
				{section loop=$mail_log name=m}
				<tr bgcolor="#FFFFFF">
					<td class="main_content_text" align="center"><a href="{$mail_log[m].edit_from_link}">{$mail_log[m].from_name}</a></td>
					<td class="main_content_text" align="center"><a href="{$mail_log[m].edit_to_link}">{$mail_log[m].to_name}</a></td>
					<td class="main_content_text" align="center">{$mail_log[m].mail_time}</td>
					<td class="main_content_text">{$mail_log[m].body}</td>
				</tr>
				{/section}
				</table>
			</td>
		</tr>
	</table>
	<br><input type="button" class="button_3" value="{$lang.buttons.back}" onclick="document.location.href='{$file_name}';">
{include file="$admingentemplates/admin_bottom.tpl"}