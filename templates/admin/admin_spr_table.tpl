{include file="$admingentemplates/admin_top.tpl"}
	<table cellpadding="0" cellspacing="0" width="100%"><tr>
		<td valign="top">
		<table cellpadding="0" cellspacing="0" width="100%">
		<tr>
			<td class="header">{$lang.content.page_header}|&nbsp;<font class="subheader">{$lang.content.references}|&nbsp;{if $section == "type"}{$lang.content.spr_type}{elseif $section == "apartment"}{$lang.content.spr_apartment}{elseif $section == "description"}{$lang.content.spr_description}{elseif $section == "period"}{$lang.content.spr_period}{elseif $section == "deactivate"}{$lang.content.spr_deactivate}{elseif $section == "gender"}{$lang.content.spr_gender}{elseif $section == "people"}{$lang.content.spr_people}{elseif $section == "language"}{$lang.content.spr_language}{/if}</font></td>
		</tr>
		<tr>
			<td><div class="help_text"><span class="help_title">{$lang.default_select.help}</span>{$lang.content.list_help}{if !$editable}<br><br>{$lang.content.not_editable_help}{/if}</div></td>
		</tr>
		</table>
		<table border="0" class="table_main" cellspacing=1 cellpadding=5 width="100%">
		{if $links}
		<tr bgcolor="#FFFFFF"><td colspan="6">
			<table cellpadding="2" cellspacing="2" border="0">
			<tr>
				<td class="main_content_text">{$lang.content.pages}</td>
				{foreach item=item from=$links}
				<td><a href="{$item.link}" class="page_link" {if $item.selected} style=" font-weight: bold;" {/if}>{$item.name}</a></td>
				{/foreach}
			</td></tr>
			</table>
		</td></tr>
		{/if}
		<tr class="table_header">
			<td class="main_header_text" align="center" width="10">{$lang.content.number}</td>
			<td class="main_header_text" align="center" >{$lang.content.subreference}</td>
			<td class="main_header_text" align="center">{$lang.content.type}</td>
			<td class="main_header_text" align="center">{$lang.content.des_type}</td>
			<!--<td class="main_header_text" align="center">{$lang.content.visible_in}</td>-->
			<td class="main_header_text" align="center" width="100">&nbsp;</td>
		</tr>
		{if $references}
		{section name=spr loop=$references}
		<tr bgcolor="#FFFFFF">
			<td class="main_content_text" align="center">{$references[spr].number}</td>
			<td class="main_content_text" align="center"><a href="{$references[spr].editlink}" class="table_link">{$references[spr].name}</a></td>
			<td class="main_content_text" align="center">{if $references[spr].type eq 1}{$lang.content.one_variant}{elseif $references[spr].type eq 2}{$lang.content.multi}{/if}</td>
			<td class="main_content_text" align="center">{if $references[spr].des_type eq 1}{$lang.content.checkbox}{elseif $references[spr].des_type eq 2}{$lang.content.select}{/if}</td>
			<td class="main_content_text" align="center"><input type="button" class="button_2" onclick="javascript: location.href='{$references[spr].editoptionlink}'" value="{$lang.buttons.options}"></td>
		</tr>
		{/section}
		{else}
		<tr height="40">
			<td class="main_error_text" align="left" colspan="4" bgcolor="#FFFFFF">{$lang.content.empty}</td>
		</tr>
		{/if}
		{if $links}
		<tr bgcolor="#FFFFFF"><td colspan="6">
			<table cellpadding="2" cellspacing="2" border="0">
			<tr>
				<td class="main_content_text">{$lang.content.pages}</td>
				{foreach item=item from=$links}
				<td><a href="{$item.link}" class="page_link" {if $item.selected} style=" font-weight: bold;" {/if}>{$item.name}</a></td>
				{/foreach}
			</td></tr>
			</table>
		</td></tr>
		{/if}
		</form>
		</table>
		{if $editable}
		<table>
		<tr height="40">
			<td><input type="button" class="button_1" onclick="javascript: location.href='{$add_link}'" value="{$lang.buttons.add}"></td>
		</tr></table>
		{/if}
		</td>
	</tr></table>
{include file="$admingentemplates/admin_bottom.tpl"}