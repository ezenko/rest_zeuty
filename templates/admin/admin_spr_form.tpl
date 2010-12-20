{include file="$admingentemplates/admin_top.tpl"}
	<table cellpadding="0" cellspacing="0" width="100%"><tr>
		<td valign="top">
		<table cellpadding="0" cellspacing="0" width="100%">
		<tr>
			<td class="header">{$lang.content.page_header}|&nbsp;<font class="subheader">{$lang.content.references}|&nbsp;{if $section == "type"}{$lang.content.spr_type}{elseif $section == "apartment"}{$lang.content.spr_apartment}{elseif $section == "description"}{$lang.content.spr_description}{elseif $section == "period"}{$lang.content.spr_period}{elseif $section == "deactivate"}{$lang.content.spr_deactivate}{elseif $section == "gender"}{$lang.content.spr_gender}{elseif $section == "people"}{$lang.content.spr_people}{elseif $section == "language"}{$lang.content.spr_language}{/if}|&nbsp;{$lang.content.page_edit_subheader}:&nbsp;{$reference_name}</font></td>
		</tr>
		<tr>
			<td><div class="help_text"><span class="help_title">{$lang.default_select.help}</span>{$lang.content.edit_subref_help}<br><br>{if ($section == "gender" || $section == "people" || $section == "language")}{$lang.content.my_preferences_help}{/if}{if !$editable}{$lang.content.not_editable_help}{/if}</div></td>
		</tr>
		</table>
		<table border=0 class="table_main" cellspacing=1 cellpadding=5 width="100%">
		<form method="POST" action="{$form.action}"  enctype="multipart/form-data" name="descr_form">
		{$form.hiddens}		
		{if $form.par eq "edit"}
		<tr>
			<td align="right" width="15%" class="main_header_text">{$lang.content.name}:&nbsp;</td>
			<td class="main_content_text" align="left" nowrap>{$lang.content.about_me}:<br><input type="text" name="name_1" value="{$name_1}" size=30 style="margin:0px; margin-top:5px;"></td>
			<td class="main_content_text" align="left" nowrap>{$lang.content.about_search_item}:<br><input type="text" name="name_2" value="{$name_2}" size=30 style="margin:0px; margin-top:5px;"></td>
		</tr>
		{else}
		<tr>
			<td align="right" width="15%" class="main_header_text">{$lang.content.name}:&nbsp;</td>
			<td class="main_content_text" align="left" ><input type="text" name="name" value="{$name}" size=30></td>
			<td class="main_header_text" align="left" width="70%">&nbsp;</td>
		</tr>
		{/if}
		<tr>
			<td align="right" class="main_header_text">{$lang.content.type}:&nbsp;</td>
			<td class="main_content_text" align="left">
				<select name="type" {if !$editable}disabled{/if}>
				<option value="1" {if $type eq 1}selected{/if}>{$lang.content.one_variant}</option>
				<option value="2" {if $type eq 2}selected{/if}>{$lang.content.multi}</option>
				</select>
			</td>
			<td class="main_header_text" align="left" width="70%">&nbsp;</td>
		</tr>
		<tr>
			<td align="right" class="main_header_text">{$lang.content.des_type}:&nbsp;</td>
			<td class="main_content_text" align="left">
				<select name="des_type" {if !$editable}disabled{/if}>
				<option value="1" {if $des_type eq 1}selected{/if}>{$lang.content.checkbox}</option>
				<option value="2" {if $des_type eq 2}selected{/if}>{$lang.content.select}</option>
				</select>
			</td>
			<td class="main_header_text" align="left" width="70%">&nbsp;</td>
		</tr>
		<tr>
			<td align="right" width="15%" class="main_header_text">{$lang.content.sorter}:&nbsp;</td>
			<td class="main_content_text" align="left">
			<select name="sorter" {if !$editable}disabled{/if}>
			{section name=s loop=$sorter}
			<option value="{$smarty.section.s.index_next}" {if $sorter[s].sel}selected{/if}>{$smarty.section.s.index_next}</option>
			{/section}
			</select>
			</td>
            <td class="main_header_text" align="left" width="70%">&nbsp;</td>
        </tr>
        </form>
        </table>
        <table><tr height="40">
        	<td><input type="button" class="button_3" onclick="javascript: location.href='{$form.back}'" value="{$lang.buttons.back}"></td>
        	{if $form.par eq "edit"}
			<td><input type="button" class="button_3" onclick="javascript:document.descr_form.submit();" value="{$lang.buttons.save}"></td>
			{if $editable}
			<td><input type="button" class="button_3" onclick="{literal}javascript: if(confirm({/literal}'{$lang.content.confirm}'{literal})){location.href={/literal}'{$form.delete}'{literal}}{/literal}" value="{$lang.buttons.delete}"></td>
			{/if}
			{else}
			<td><input type="button" class="button_3" onclick="javascript:document.descr_form.submit();" value="{$lang.buttons.add}"></td>
			{/if}			
		</tr></table>
		</td>
	</tr></table>
{include file="$admingentemplates/admin_bottom.tpl"}