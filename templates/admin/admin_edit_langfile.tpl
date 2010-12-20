{include file="$admingentemplates/admin_popup_top.tpl" header=$lang.menu.content_management subheader=$subheader}
<table cellSpacing="0" cellPadding="0" border="0" id="table4" width="100%">
	<tr>
		<td>
		{if $file_path}
				<b>{$lang.content.file_name}</b>: {$file_path}<br><br>
		{else}
			<table cellpadding="0" cellspacing="0">
				<tr>
					<td>
					<font class="text">{$lang.content.select_langfile}:&nbsp;</font>
					</td>
					<td>
					<form id="sections_form" name="sections_form" action="{$form.action}" method="post">
					<select name="section" id="section" style="width:200" onchange="javascript:document.sections_form.submit();">
					{section name=s loop=$sections}
						<option value="{$sections[s].val}"{if $sections[s].sel eq 1 } selected{/if}>{$sections[s].name}</option>
					{/section}
					</select>
					</form>
					</td>
				</tr>
			</table>
		{/if}	
		</td>
	</tr>
	<tr valign=top>
		<td>
		<form name="lang_form" id="lang_form" method="POST" action='{$file_name}?part={$part}'>
		<input type='hidden' name='save_purum' id='save_purum' value='1'>
		<input type='hidden' name='section' id='section' value=''>
		{if $file_path}			
			<input type='hidden' name='file_path' id='file_path' value='{$file_path}'>
		{/if}	
		<table cellpadding="2" cellspacing="0" border="0" width='100%'>
			<tr>
				<!--<td align='center' width='300px' class='main_header_text'>{if $is_menu_part eq 1}{$lang.content.link_on}{else}{$lang.content.description}{/if}:&nbsp;</td>-->
				<td align='left' class='main_header_text' style="padding-bottom: 10px;">{$lang.content.value}:&nbsp;</td>
			</tr>
			{section name=i loop=$values}
			<tr>
				<!--<td align='center' class="main_content_text"><p>{$descr[i]}</p></td>-->
				<td align='left'>
					<textarea cols="80" rows="2" id='{$name[i]}' name='{$name[i]}' class="{if $marked[i]}whole_width_marked{else}whole_width{/if}">{$values[i]}</textarea>
				</td>
			</tr>
			{/section}
			<tr>
				<td colspan="2">
				{if $noempty}
					<input type="submit" class="button_3" value="{$lang.buttons.save}" {if $sections}onclick="javascript: document.lang_form.section.value = document.sections_form.section.value; "{/if}>&nbsp;
				{/if}
				<input type="button" class="button_3" value="{$lang.buttons.close}" onclick="javascript: window.close(); opener.focus();">
				</td>
			</tr>
		</table>
		</form>
		</td>
	</tr>
</table>
{include file="$admingentemplates/admin_popup_bottom.tpl"}