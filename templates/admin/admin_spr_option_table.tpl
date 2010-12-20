{include file="$admingentemplates/admin_top.tpl"}
	<table cellpadding="0" cellspacing="0" width="100%"><tr>
		<td valign="top">
		<table cellpadding="0" cellspacing="0" width="100%">
		<tr>
			<td class="header">{$lang.content.page_header}|&nbsp;<font class="subheader">{$lang.content.references}|&nbsp;{if $section == "type"}{$lang.content.spr_type}{elseif $section == "apartment"}{$lang.content.spr_apartment}{elseif $section == "description"}{$lang.content.spr_description}{elseif $section == "period"}{$lang.content.spr_period}{elseif $section == "deactivate"}{$lang.content.spr_deactivate}{elseif $section == "gender"}{$lang.content.spr_gender}{elseif $section == "people"}{$lang.content.spr_people}{elseif $section == "language"}{$lang.content.spr_language}{/if}|&nbsp;{$lang.content.page_editopt_subheader}:&nbsp;{$reference_name}</font></td>
		</tr>
		<tr>
			<td><div class="help_text"><span class="help_title">{$lang.default_select.help}</span>{$lang.content.edit_subrefopt_help}</div></td>
		</tr>
		</table>
		<table border=0 class="table_main" cellspacing=1 cellpadding=5 width="100%">
		<tr class="table_header">
			<td class="main_header_text" align="center" width="10">{$lang.content.number}</td>
			<td class="main_header_text" align="center">{$lang.content.option} ({$lang.content.about_me})</td>
			<td class="main_header_text" align="center">{$lang.content.option} ({$lang.content.about_search_item})</td>
			<td class="main_header_text" align="center" width="100">{$lang.content.delete}</td>
		</tr>
		{if $references}
		<form method="POST" action="{$form.action}" name="update_form">
		<input type="hidden" name="sel" value="">
		<input type="hidden" name="page" value="{$page}">
		<input type="hidden" name="id_spr" value="{$id_spr}">
		{section name=spr loop=$references}
		<tr bgcolor="#FFFFFF">
			<td class="main_content_text" align="center">{$references[spr].number}</td>
			<!--<td class="main_content_text" align="center"><input type="text" value="{$references[spr].name}" name="spr_options[{$references[spr].id_ref}]" style="width: 270px;"></td>-->
			<td class="main_content_text" align="center"><input type="text" value="{$references[spr].name_1}" name="spr_options[1][{$references[spr].id_ref}]" style="width: 270px;"></td>
			<td class="main_content_text" align="center"><input type="text" value="{$references[spr].name_2}" name="spr_options[2][{$references[spr].id_ref}]" style="width: 270px;"></td>
			<td class="main_content_text" align="center" width="100"><input type="button" class="button_2" value="{$lang.buttons.delete}" onclick="javascript: location.href='{$references[spr].dellink}'">
			</td>
		</tr>
		{/section}
		<tr bgcolor="#FFFFFF">
			<td colspan="4"><input type="button" class="button_1" value="{$lang.buttons.save}" onclick="document.update_form.sel.value='update'; document.update_form.submit();"></td>
		</tr>
		{else}
		<tr bgcolor="#FFFFFF" height="40">
			<td class="error" align="left" colspan="3" bgcolor="#FFFFFF">{$lang.content.empty}</td>
		</tr>
		{/if}
		</form>
		</table>
<br>
		<table border=0 cellspacing=1 cellpadding=5>
		<form method="post" action="{$form.action}"  enctype="multipart/form-data" name=add_form>
		{$form.hiddens}
		<tr bgcolor="#FFFFFF">
			<td><b>{$lang.content.add_option}:</b></td>
			<td><input type="text" name="name" value="{$name}" size=60></td>
			<td align="left"><input type="button" class="button_1" value="{$lang.buttons.add}" onclick="javascript: document.add_form.submit();"></td>
		</tr>
		</form>
		</table>
<br>
<div><input type="button" class="button_3" value="{$lang.buttons.back}" onclick="javascript: location.href='{$back_link}'">
</div>
		</td>
	</tr></table>
{include file="$admingentemplates/admin_bottom.tpl"}