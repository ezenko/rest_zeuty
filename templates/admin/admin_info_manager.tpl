{include file="$admingentemplates/admin_top.tpl"}
	<table cellpadding="0" cellspacing="0" border="0" width="100%">
		<tr>
			<td class="header" align="left">{$lang.menu.content_management} <font class="subheader">| {$lang.menu.sections_management} | {$lang.menu.info_manager}</font></td>
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
					<div align="right">
						<span class="space">{$lang.content.preview}:</span>
						<span class="space"><a href="{$server}{$site_root}/index.php?view_from_admin=1&for_unreg_user=1&lang_from_admin={$current_lang_id}" target="_blank">{$lang.content.preview_index}</a></span>
						<span class="space"><a href="{$server}{$site_root}/homepage.php?view_from_admin=1&lang_from_admin={$current_lang_id}" target="_blank">{$lang.content.preview_homepage}</a></span>
					</div>
				</div>
				{/strip}
				<div class="table_title">{$lang.content.top_menu}:</div>
				<input type="button" value="{$lang.content.add_section}" onclick="javascript: document.location='{$file_name}?sel=add_section&language_id={$current_lang_id}&menu_position=top';" />
				{if $top_sections}
					<table width="100%" cellpadding="5" cellspacing="1" border="0" class="table_main" style="margin: 0px; margin-top:10px; margin-bottom:10px;">
					<FORM name="status_top" action="{$file_name}" method="POST" enctype="multipart/form-data">
					<INPUT type="hidden" name="sel" value="change_status">
					<INPUT type="hidden" name="language_id" value="{$current_lang_id}">
					<INPUT type="hidden" name="menu_position" value="top">
					<tr>
						<th colspan="2">{$lang.content.position_number}</th>
						<th width="50%">{$lang.content.caption}</th>
						<th>{$lang.content.subsections}</th>
						<th>{$lang.content.publish_title}</th>
						<th>{$lang.content.move}</th>
						{if $langs_cnt > 1}
						<th>{$lang.content.copy_to_langs}</th>
						{/if}
						<th>{$lang.content.delete}</th>
					</tr>
					{foreach from=$top_sections item=page name=top_sections}
					<tr>
						<td align="center" {if $smarty.foreach.top_sections.total==1}colspan="2"{/if}>{$smarty.foreach.top_sections.iteration}</td>
						{if $smarty.foreach.top_sections.total>1}
							<td align="center" nowrap>
							{if $smarty.foreach.top_sections.iteration != $smarty.foreach.top_sections.first}
							&nbsp;<A href="{$file_name}?section_move_up={$page.id}&language_id={$current_lang_id}" class="sort_link" title="{$lang.content.move_up}">&uarr;</A>
							{/if}
							{if $smarty.foreach.top_sections.iteration != $smarty.foreach.top_sections.total}
							&nbsp;<A href="{$file_name}?section_move_down={$page.id}&language_id={$current_lang_id}" class="sort_link" title="{$lang.content.move_down}">&darr;</A>
							{/if}
							</td>
						{/if}
						<td class="row_info"><a href='{$file_virt_name}?sel=edit_section&id={$page.id}&language_id={$current_lang_id}'>{$page.caption}</a></td>
						<td class="row_button"><input type="button" onclick="document.location.href='{$file_virt_name}?sel=subsection_list&id={$page.id}&language_id={$current_lang_id}';" value="{$lang.buttons.edit}: {$page.subsections_cnt}"></td>
						<td class="row_button"><input type="checkbox" name="status[{$page.id}]" value="1" {if $page.status == 1}checked{/if}></td>
						<td class="row_button"><input type="button" onclick="document.location.href='{$file_virt_name}?section_menu_move={$page.id}&menu_position=bottom&language_id={$current_lang_id}';" value="{$lang.content.to_bottom_menu}"></td>
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
					<input type="button" value="{$lang.buttons.save}" onclick="javascript: document.status_top.submit();"/>
					</FORM>
				{/if}

				<div class="table_title" style="margin: 0px; margin-top:20px;">{$lang.content.bottom_menu}:</div>
				<input type="button" value="{$lang.content.add_section}" onclick="javascript: document.location='{$file_name}?sel=add_section&language_id={$current_lang_id}&menu_position=bottom';" />
				{if $bottom_sections}
					<table width="100%" cellpadding="5" cellspacing="1" border="0" class="table_main" style="margin: 0px; margin-top:10px; margin-bottom:10px;">
					<FORM name="status_bottom" action="{$page_path}" method="POST" enctype="multipart/form-data">
					<INPUT type="hidden" name="sel" value="change_status">
					<INPUT type="hidden" name="language_id" value="{$current_lang_id}">
					<INPUT type="hidden" name="menu_position" value="bottom">
					<tr>
						<th colspan="2">{$lang.content.position_number}</th>
						<th width="50%">{$lang.content.caption}</th>
						<th>{$lang.content.subsections}</th>
						<th>{$lang.content.publish_title}</th>
						<th>{$lang.content.move}</th>
						{if $langs_cnt > 1}
						<th>{$lang.content.copy_to_langs}</th>
						{/if}
						<th>{$lang.content.delete}</th>
					</tr>
					{foreach from=$bottom_sections item=page name=bottom_sections}
					<tr>
						<td align="center" {if $smarty.foreach.bottom_sections.total==1}colspan="2"{/if}>{$smarty.foreach.bottom_sections.iteration}</td>
						{if $smarty.foreach.bottom_sections.total>1}
							<td align="center" nowrap>
							{if $smarty.foreach.bottom_sections.iteration != $smarty.foreach.bottom_sections.first}
							&nbsp;<A href="{$page_path}?section_move_up={$page.id}&language_id={$current_lang_id}" class="sort_link" title="{$lang.content.move_up}">&uarr;</A>
							{/if}
							{if $smarty.foreach.bottom_sections.iteration != $smarty.foreach.bottom_sections.total}
							&nbsp;<A href="{$page_path}?section_move_down={$page.id}&language_id={$current_lang_id}" class="sort_link" title="{$lang.content.move_down}">&darr;</A>
							{/if}
							</td>
						{/if}
						<td class="row_info"><a href='{$file_virt_name}?sel=edit_section&id={$page.id}&language_id={$current_lang_id}'>{$page.caption}</a></td>
						<td class="row_button"><input type="button" onclick="document.location.href='{$file_virt_name}?sel=subsection_list&id={$page.id}&language_id={$current_lang_id}';" value="{$lang.buttons.edit}: {$page.subsections_cnt}"></td>
						<td align="center"><input type="checkbox" name="status[{$page.id}]" value="1" {if $page.status == 1}checked{/if}></td>
						<td class="row_button"><input type="button" onclick="document.location.href='{$file_virt_name}?section_menu_move={$page.id}&menu_position=top&language_id={$current_lang_id}';" value="{$lang.content.to_top_menu}"></td>
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
					</FORM>
				{/if}

			{elseif $sel == "add_section" || $sel == "edit_section" || $sel == "add_subsection" || $sel == "edit_subsection"}
				{$tinymce}
				<table cellpadding="0" cellspacing="0" class="form_table">
				{if $form}
				<FORM action="{$form.action}" method="POST" name="{$form.name}">
					{if $form.hiddens}
						{foreach from=$form.hiddens item=hidden}
						<INPUT type="hidden" name="{$hidden.name}" value="{$hidden.value}">
						{/foreach}
					{/if}
				{/if}
				<tr>
					<td>{$lang.content.caption}:&nbsp;<INPUT type="text" name="caption" size="100" value="{$caption}"></td>
					<td><INPUT type="checkbox" class="checkbox" name="status" value="1" {if $status == 1}checked{/if}></td>
					<td>{$lang.content.to_publish}</td>
				</tr>
				</table>
				<table width="100%" border="0" cellpadding="0" cellspacing="0" class="form_table">
				<tr>
					<td colspan="2" align="left" width="100%" style="padding-bottom: 10px;">
						<textarea id="content" name="content" rows="23" cols="40" class="whole_width">{$content}</textarea>
					</td>
				</tr>
				{if $sel == "add_section" || $sel == "edit_section"}
				<tr>
					<td align="left" colspan="2">{$lang.content.metatags}:</td>
				</tr>
				<tr>
					<td width="10%">{$lang.content.description}:</td>
					<td><input type="text" name="description" value="{$description}" class="whole_width"></td>
				</tr>
				<tr>
					<td>{$lang.content.keywords}:</td>
					<td><input type="text" name="keywords" value="{$keywords}" class="whole_width"></td>
				</tr>
				{/if}
				<tr>
					<td align="left" colspan="2">
						{if $sel == "add_section" || $sel == "edit_section"}
							<input type="button" value="{$lang.buttons.back}" onclick="javascript: document.location='{$file_name}?sel=main&language_id={$current_lang_id}';" />
						{elseif $sel == "add_subsection" || $sel == "edit_subsection"}
							<input type="button" value="{$lang.buttons.back}" onclick="javascript: document.location='{$file_name}?sel=subsection_list&id={$section_id}';" />
						{/if}
						<input type="button" value="{$lang.buttons.save}" onclick="javascript: document.{$form.name}.submit();" />
					</td>
				</tr>
				{if $form}
				</FORM>
				{/if}
				</table>

			{elseif $sel == "subsection_list"}
				<div class="table_title">{$lang.content.sect_subsections} &quot;{$section.caption}&quot;:</div>
				<input type="button" value="{$lang.content.add_subsection}" onclick="javascript: document.location='{$file_name}?sel=add_subsection&section_id={$section_id}';" />

				{if $subsections}
					<table width="100%" cellpadding="5" cellspacing="1" border="0" class="table_main" style="margin: 0px; margin-top:10px; margin-bottom:10px;">
					<FORM name="status_top" action="{$file_name}" method="POST" enctype="multipart/form-data">
					<INPUT type="hidden" name="sel" value="change_subsections_status">
					<INPUT type="hidden" name="section_id" value="{$section_id}">
					<tr>
						<th colspan="2">{$lang.content.position_number}</th>
						<th width="98%">{$lang.content.subsect_caption}</th>
						<th width="1%">{$lang.content.publish_title}</th>
						<th width="1%">{$lang.content.delete}</th>
					</tr>
					{foreach from=$subsections item=page name=subsections}
					<tr>
						<td align="center" {if $smarty.foreach.subsections.total==1}colspan="2"{/if}>{$smarty.foreach.subsections.iteration}</td>
						{if $smarty.foreach.subsections.total>1}
							<td align="center" nowrap>
							{if $smarty.foreach.subsections.iteration != $smarty.foreach.subsections.first}
							&nbsp;<A href="{$file_name}?subsection_move_up={$page.id}&section_id={$section_id}" class="sort_link" title="{$lang.content.move_up}">&uarr;</A>
							{/if}
							{if $smarty.foreach.subsections.iteration != $smarty.foreach.subsections.total}
							&nbsp;<A href="{$file_name}?subsection_move_down={$page.id}&section_id={$section_id}" class="sort_link" title="{$lang.content.move_down}">&darr;</A>
							{/if}
							</td>
						{/if}
						<td class="row_info"><a href='{$file_virt_name}?sel=edit_subsection&id={$page.id}&section_id={$section_id}'>{$page.caption}</a></td>
						<td align="center"><input type="checkbox" name="status[{$page.id}]" value="1" {if $page.status == 1}checked{/if}></td>
						<td class="row_button">
							<input type="button" onclick="javascript: if (confirm('{$lang.content.del_subsection_confirm} &quot;{$page.caption}&quot;?')) document.location.href='{$file_virt_name}?subsection_delete={$page.id}&section_id={$section_id}';" value="{$lang.buttons.delete}">
						</td>
					</tr>
					{/foreach}
					</table>
					<input type="button" value="{$lang.buttons.back}" onclick="javascript: document.location='{$file_name}?sel=main&language_id={$current_lang_id}';" />
					<input type="button" value="{$lang.buttons.save}" onclick="javascript: document.status_top.submit();" />
					</FORM>
				{/if}
			{/if}
			</td>
		</tr>
	</table>
{include file="$admingentemplates/admin_bottom.tpl"}