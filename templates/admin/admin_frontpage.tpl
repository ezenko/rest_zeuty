{include file="$admingentemplates/admin_top.tpl"}
	<table cellpadding="0" cellspacing="0" border="0" width="100%">
		<tr>
			<td class="header" align="left">{$lang.menu.content_management} <font class="subheader">| {$lang.menu.sections_management} | {$lang.menu.frontpage}</font></td>
		</tr>
		<tr>
			<td><div class="help_text"><span class="help_title">{$lang.default_select.help}</span>{$lang.content.help}</div></td>
		</tr>
		<tr>
			<td>
			{if $sel == "main"}
				{strip}
				<div style="padding-bottom: 10px;">
					{$lang.default_select.interface_lang}:
					{section name=m loop=$admin_lang_menu}
						<span class="space">
							{if $admin_lang_menu[m].id_lang == $current_lang_id}
								<b>{$admin_lang_menu[m].value}</b>
							{else}
								<a href="#" onclick="javascript: document.location.href='{$file_virt_name}?language_id={$admin_lang_menu[m].id_lang}';">{$admin_lang_menu[m].value}</a>
							{/if}
							{if $admin_lang_menu[m].vis eq 0} ({$lang.default_select.unactive_lang}){/if}
						</span>
					{/section}
				</div>
				{/strip}
				
    <div class="table_title" style="margin: 0px; margin-top:20px;">{$lang.content.bottom_menu}:</div>
				<input type="button" value="{$lang.content.add_section}" onclick="javascript: document.location='{$file_name}?sel=add_section&language_id={$current_lang_id}';" />
				{if $frontpage}
					<table width="100%" cellpadding="5" cellspacing="1" border="0" class="table_main" style="margin: 0px; margin-top:10px; margin-bottom:10px;">
					<form name="status_bottom" action="{$page_path}" method="POST" enctype="multipart/form-data">
					<input type="hidden" name="language_id" value="{$current_lang_id}" />
					<tr>
						<th colspan="2">{$lang.content.position_number}</th>
						<th width="50%">{$lang.content.caption}</th>
						<th>{$lang.content.subsections}</th>
						{if $langs_cnt > 1}
						<th>{$lang.content.copy_to_langs}</th>
						{/if}
						<th>{$lang.content.delete}</th>
					</tr>
					{foreach from=$frontpage item=page name=bottom_sections}
					<tr>
						<td align="center" {if $smarty.foreach.bottom_sections.total==1}colspan="2"{/if}>{$smarty.foreach.bottom_sections.iteration}</td>
						{if $smarty.foreach.bottom_sections.total>1}
							<td align="center" nowrap>
							{if $smarty.foreach.bottom_sections.iteration != $smarty.foreach.bottom_sections.first}
							&nbsp;<a href="{$page_path}?section_move_up={$page.id}&language_id={$current_lang_id}" class="sort_link" title="{$lang.content.move_up}">&uarr;</a>
							{/if}
							{if $smarty.foreach.bottom_sections.iteration != $smarty.foreach.bottom_sections.total}
							&nbsp;<a href="{$page_path}?section_move_down={$page.id}&language_id={$current_lang_id}" class="sort_link" title="{$lang.content.move_down}">&darr;</a>
							{/if}
							</td>
						{/if}
						<td class="row_info"><a href='{$file_virt_name}?sel=edit_section&id={$page.id}&language_id={$current_lang_id}'>{$page.caption}</a></td>
						<td class="row_button"><input type="button" onclick="document.location.href='{$file_virt_name}?sel=edit_section&id={$page.id}&language_id={$current_lang_id}';" value="{$lang.buttons.edit}"></td>
						{if $langs_cnt > 1}
						<td class="row_button">
							<input type="button" onclick="document.location.href='{$file_virt_name}?copy={$page.id}&language_id={$current_lang_id}';" value="{$lang.buttons.copy}">
						</td>
						{/if}
						<td class="row_button">
							<input type="button" onclick="javascript: if (confirm('{$lang.content.del_section_confirm} &quot;{$page.caption}&quot;?')) document.location.href='{$file_virt_name}?delete={$page.id}&language_id={$current_lang_id}';" value="{$lang.buttons.delete}">
						</td>
					</tr>
					{/foreach}
					</table>
					<input type="button" value="{$lang.buttons.save}" onclick="javascript: document.status_bottom.submit();" />
					</form>
				{/if}

			{elseif $sel == "add_section" || $sel == "edit_section"}
				{$tinymce}
				<table cellpadding="0" cellspacing="0" class="form_table">
				{if $form}
				<form action="{$form.action}" method="POST" name="{$form.name}" enctype="multipart/form-data">
					{if $form.hiddens}
						{foreach from=$form.hiddens item=hidden}
						  <input type="hidden" name="{$hidden.name}" value="{$hidden.value}"/>
						{/foreach}
					{/if}
				{/if}
				<tr>
					<td>{$lang.content.caption}:&nbsp;<input type="text" name="caption" size="100" value="{$caption}" /></td>
				</tr>
        <tr>
					<td>{$lang.content.link}:&nbsp;<input type="text" name="link" size="100" value="{$link}" /></td>
				</tr>
				</table>
				<table width="100%" border="0" cellpadding="0" cellspacing="0" class="form_table">
				<tr>
					<td colspan="2" align="left" width="100%" style="padding-bottom: 10px;">
						<textarea id="content" name="content" rows="23" cols="40" class="whole_width">{$content}</textarea>
					</td>
				</tr>        
				<tr>
					<td width="10%">{$lang.content.image}:</td>
					<td><input type="file" name="image" class="whole_width" /></td>
				</tr>
                {if $image}
                <tr>
					<td width="10%">&nbsp;</td>
					<td><img src="/uploades/frontpage/{$image}" alt="{$caption}" border="0" /></td>
				</tr>
                {/if}
				<tr>
					<td align="left" colspan="2">
						{if $sel == "add_section" || $sel == "edit_section"}
							<input type="button" value="{$lang.buttons.back}" onclick="javascript: document.location='{$file_name}?sel=main&language_id={$current_lang_id}';" />
						{/if}
						<input type="button" value="{$lang.buttons.save}" onclick="javascript: document.{$form.name}.submit();" />
					</td>
				</tr>
				{if $form}
				</form>
				{/if}
				</table>
			{/if}
			</td>
		</tr>
	</table>
{include file="$admingentemplates/admin_bottom.tpl"}