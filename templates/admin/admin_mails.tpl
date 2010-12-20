{include file="$admingentemplates/admin_top.tpl"}
<table cellpadding="0" cellspacing="0" border="0" width="100%">
	<tr>
		<td class="header" align="left">{$lang.menu.site_parameters} <font class="subheader">| {$lang.menu.alerts} | {if $section=="admin"}{$lang.menu.admin_alerts}{elseif $section=="user"}{$lang.menu.member_alerts}{/if}{if $edit} | {$lang.content.mails_edit}{/if}</font></td>
	</tr>
	<tr>
		<td><div class="help_text"><span class="help_title">{$lang.default_select.help}</span>{if $edit}{$lang.content.edit_help}{else}{if $section=="admin"}{$lang.content.admin_help_1}<a href="./admin_settings.php?section=admin">{$lang.content.admin_help_2}</a>{$lang.content.admin_help_3}{elseif $section=="user"}{$lang.content.user_help}{/if}{/if}</div></td>
	</tr>
{if $edit}
	<tr>
		<td>
			<table cellpadding="3" cellspacing="1" border="0" width="100%">
			<tr>
				<td>
				<div style="padding-bottom: 10px;">{strip}
					{$lang.default_select.interface_lang}:
					{section name=m loop=$admin_lang_menu}
						<span class="space">
							{if $admin_lang_menu[m].id_lang == $current_lang_id}
								<b>{$admin_lang_menu[m].value}</b>
							{else}
								<a href="#" onclick="javascript: document.location.href='{$file_name}?section={$section}&edit={$edit}&xml={$xml_file}&language_id={$admin_lang_menu[m].id_lang}';">{$admin_lang_menu[m].value}</a>
							{/if}
						</span>
						{if $admin_lang_menu[m].vis eq 0} ({$lang.default_select.unactive_lang}){/if}
					{/section}
				{/strip}</div>
				</td>
			</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>{strip}
			<table cellpadding="3" cellspacing="1" border="0" width="100%">
				<tr>
					<td><b>{$lang.content.mail_text} &quot;{$lang.content[$xml_file]}&quot;</b>{if $unsubscribe_possible} ({$lang.content.unsubscribe_possible}){/if}</td>
				</tr>
				<tr>
					<td>
						<textarea rows="20" readonly class="mail_preview">
						{include file="$gentemplates/$tpl_name.tpl"}
						</textarea>
					</td>
				</tr>
			</table>
			{/strip}
		</td>
	</tr>
	<tr>
		<td style="padding-top: 15px; padding-left: 5px;"><b>{$lang.content.edit_lang_files}</b></td>
	</tr>
	{foreach from=$content item=cont}
	<tr>
		<td>
			<table cellspacing="1" cellpadding="3" width="100%">
				<form name="{$cont.name}" action="{$server}{$site_root}/admin/admin_mails.php" method="POST">
				<input type="hidden" name="section" value="{$section}">
				<input type="hidden" name="language_id" value="{$current_lang_id}">
				<input type="hidden" name="edit" value="{$edit}">
				<input type="hidden" name="save" value="1">
				<input type="hidden" name="edit_file" value="{$cont.file}">
				<input type="hidden" name="xml" value="{$xml_file}">
				<tr>
					<td style="padding-top: 15px;">
						<div class="table_title">{$lang.content[$cont.name]}:</div>
						{assign var="help_name" value=$cont.name|cat:"_help"}
						<div class="help_text_table"><span class="help_title">{$lang.default_select.help}</span>{$lang.content[$help_name]}</div>
					</td>
				</tr>
				{section name=i loop=$cont.values.values}
    			<tr>
    				<td align='left'>
    				{if $cont.values.descr[i]}
    				{$cont.values.descr[i]}:<br>
    				{/if}
    				<textarea rows="2" name='lang[{$cont.values.name[i]}]' class="whole_width">{$cont.values.values[i]}</textarea>
    				</td>
    			</tr>
    			{/section}
    			<tr><td><input type="submit" name="submit" value="{$lang.buttons.save}"></td></tr>
    			</form>
    		</table>
		</td>
	</tr>
	{/foreach}
	<tr>
		<td style="padding-top: 15px; padding-left: 5px;">
			<input type="button" value="{$lang.buttons.back}" onclick="javascript: document.location.href='{$file_name}?section={$section}';">
		</td>
	</tr>
{else}
	{if $section=="user"}
	<tr>
		<td>{$lang.content.sys_alerts_from}:
		<ul>
			<li>{$lang.content.alerts_from_email}: <b>{$data.site_email}</b> ({$lang.content.sys_from_email_help_1}<a href="./admin_settings.php?section=admin">{$lang.content.sys_from_email_help_2}</a>{$lang.content.sys_from_email_help_3})</li>
			<li>{$lang.content.alerts_from_name}: <b>{$data.site_name}</b> ({$lang.content.sys_from_name_help})</li>
		</ul>
		</td>
	</tr>
	{/if}
	{if $section=="admin"}
	<tr>
		<td>{$lang.content.user_alerts_from}:
		<ul>
			<li>{$lang.content.alerts_from_name}: <b>{$data.site_name}</b> ({$lang.content.sys_from_name_help})</li>
		</ul>
		{$lang.content.user_alerts_help}:
		<ul>
			<li>{$lang.content.alerts_from_email}: <b>{$data.site_email}</b> ({$lang.content.sys_from_email_help_1}<a href="./admin_settings.php?section=admin">{$lang.content.sys_from_email_help_2}</a>{$lang.content.sys_from_email_help_3})</li>
			<li>{$lang.content.alerts_from_name}: <b>{$data.admin_name}</b> ({$lang.content.admin_name_help_1}<a href="./admin_settings.php?section=admin">{$lang.content.admin_name_help_2}</a>)</li>
		</ul>
		</td>
	</tr>
	{/if}
	<tr>
		<td>
			<table class="table_main" cellspacing=1 cellpadding=3 width="100%" border="0">
				<tr>
					<td class="main_header_text" align="center" width="1%">{$lang.default_select.number}</td>
					<td class="main_header_text" align="center">{$lang.content.mail_description}</td>
					{if $section != "admin"}
					<td class="main_header_text" align="center">{$lang.content.users_cnt}</td>
					{/if}
					<td class="main_header_text" align="center" width="5%">{$lang.default_select.title_edit}</td>
				</tr>
			{foreach from=$mails item=mail name=mails key=key}
				<tr>
					<td align="center" valign="top">{$key+1}</td>
					<td>{$lang.content[$mail.xml]}{if $mail.unsubscribe_possible} ({$lang.content.unsubscribe_possible}){/if}</td>
					{if $section != "admin"}
					<td align="center">{$mail.users_cnt}</td>
					{/if}
					<td align="center"><input type="button" value="{$lang.buttons.edit}" onclick="javascript: document.location.href='{$file_name}?section={$section}&edit=1&xml={$mail.xml}';">
					</td>
				</tr>
			{/foreach}
			</table>
		</td>
	</tr>
{/if}
</table>
{literal}
<script language="javascript">
function ShowDeveloperHelp(){
	document.getElementById("developer_help").style.display = "";
	document.getElementById("show_dev_help").style.display = "none";
}
function HideDeveloperHelp(){
	document.getElementById("show_dev_help").style.display = "";
	document.getElementById("developer_help").style.display = "none";
}
</script>
{/literal}
{include file="$admingentemplates/admin_bottom.tpl"}