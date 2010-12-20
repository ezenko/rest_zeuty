{include file="$admingentemplates/admin_popup_top.tpl" header=$header}
{if $nothing_to_search}
	<font class="error">{$lang.content.nothing_to_search}</font><br><br><br>
{else}
	<table cellpadding="3" cellspacing="1" border="0" width="100%" class="table_main">
		<tr>
			<th>{$lang.content.number}</th>
			<th>{$lang.content.file_name}</th>
			<th>{$lang.content.find_in_strings}</th>
			<th>{$lang.default_select.title_edit}</th>
		</tr>
	{foreach from=$search_result item=res name=find_in_files}
		<tr>		
			<td align="center" class="vtop">{$smarty.foreach.find_in_files.iteration}</td>
			<td  class="vtop" nowrap>{$res.short_file_path}</td>
			<td>
				{foreach from=$res.strings item=str name=str}
				{$smarty.foreach.str.iteration}. {$str}<br>
				{/foreach}
			</td>
			<td align="center" class="vtop">
				<input id="open_button" type="button" value="{$lang.buttons.edit}" onclick="javascript: window.open('admin_editfile.php?edit={$lang_folder}&file_path={$res.short_file_path}&search_string={$search_string}', 'langfile', 'height=600, resizable=yes, scrollbars=yes, width=800, menubar=no, status=no, left=20, top=20, screenX=20, screenY=20');">			
			</td>
		</tr>	
	{foreachelse}
		<tr>
			<td colspan="4" class="error" align="center">{$lang.content.no_matches}</td>
		</tr>
	{/foreach}
	</table>
{/if}	
<input type="button" value="{$lang.buttons.close}" onclick="javascript: window.close(); opener.focus();">
{include file="$admingentemplates/admin_popup_bottom.tpl"}