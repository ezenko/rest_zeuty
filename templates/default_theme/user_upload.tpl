{if $upload}						
	<table cellpadding="0" cellspacing="0" border="0" width="100%">
		{section name=f loop=$upload}
		<tr>
		<td>{$smarty.section.f.iteration}.</td>							
		<td>
			{if !$smarty.section.f.first}<a class="sorter" onclick="UpdateUserUpload('file_up', '{$id_ad}', 'user_upload_{$upload_type_link}', '{$choise}', '{$upload_type}', '{$upload[f].id}');">&uarr;</a>{/if}
			{if !$smarty.section.f.last}<a onclick="UpdateUserUpload('file_down', '{$id_ad}', 'user_upload_{$upload_type_link}', '{$choise}', '{$upload_type}', '{$upload[f].id}');" class="sorter">&darr;</a>{/if}
		</td>
		<td height="{$thumb_height+10}" width="{$thumb_width+10}" valign="middle" align="center" style="border: 1px solid #cccccc;">
			{if $upload[f].view_link}
				{if $upload_type_link == 'video'}
					<a href="#" onclick="javascript: var top_pos = (window.screen.height - {$upload[f].height+70})/2; var left_pos = (window.screen.width - {$upload[f].width})/2; window.open('{$upload[f].view_link}','photo_view','top='+top_pos+', left='+left_pos+',menubar=0, resizable=1, scrollbars=0,status=0,toolbar=0, width={$upload[f].width},height={$upload[f].height+70}');return false;">
				{else}
					<a href="{$upload[f].file}" rel="lightbox[photo]" title="{$upload[f].user_comment}">
				{/if}	
			{/if}
			<img src='{if $upload_type_link == "video"}{$upload[f].video_icon}{else}{$upload[f].thumb_file}{/if}' style="border: none;">{if $upload[f].view_link}</a>{/if}
		</td>
		<td valign="top" style="padding-left: 7px;">
			<table cellpadding="0" cellspacing="0" border="0" width="100%">
			<tr>
				<td><textarea name="edit_upload_comment[{$upload[f].id}]" id="edit_upload_comment_{$upload[f].id}" cols="40" rows="5" onkeyup="document.getElementById('save_link_{$upload[f].id}').style.display='inline';document.getElementById('save_status_{$upload[f].id}').style.display='none';">{$upload[f].user_comment}</textarea></td>
				<td valign="top" align="right">
					{strip}
					{if ($data.use_photo_approve && ($upload_type_link != 'video')) || ($data.use_video_approve && ($upload_type_link == 'video'))}
						{if $upload[f].admin_approve == 0}
							<font class="error">{$lang.content.admin_approve_not_complete}</font>
						{elseif $upload[f].admin_approve == 1}
							<!--{$lang.content.admin_approve_acepted}-->
						{elseif $upload[f].admin_approve == 2}
							<font class="error">{$lang.content.admin_approve_decline}</font>
						{/if}
					{/if}
					{/strip}
				</td>
			</tr>
			<tr>
				<td style="padding-top: 5px;" colspan="2"><a id="save_link_{$upload[f].id}" onclick="UpdateUserUpload('edit_comment', '{$id_ad}', 'user_upload_{$upload_type_link}', '{$choise}', '{$upload_type}', '{$upload[f].id}');" class="action_link">{$lang.buttons.save}</a><span id='save_status_{$upload[f].id}' style="display:none;">{$lang.content.saved}</span>&nbsp;&nbsp;|&nbsp;&nbsp;{if $upload[f].status == 1}<a onclick="UpdateUserUpload('upload_deactivate', '{$id_ad}', 'user_upload_{$upload_type_link}', '{$choise}', '{$upload_type}', '{$upload[f].id}');">{$lang.content.deactivate_file}</a>{else}<a onclick="UpdateUserUpload('upload_activate', '{$id_ad}', 'user_upload_{$upload_type_link}', '{$choise}', '{$upload_type}', '{$upload[f].id}');">{$lang.content.activate_file}</a>{/if}&nbsp;&nbsp;|&nbsp;&nbsp;<a onclick="UpdateUserUpload('upload_del', '{$id_ad}', 'user_upload_{$upload_type_link}', '{$choise}', '{$upload_type}', '{$upload[f].id}');" class="action_link">{$lang.content.delete}</a></td>
			</tr>
			</table>
		</td>
		</tr>
		{if !($smarty.section.f.last && $upload_count == $data.limit)}
		<tr><td colspan="5"><hr class="listing"></td></tr>
		{/if}
		{/section}
	</table>
{else}						
	<font class="error">{if $upload_type_link == 'video'}{$lang.content.no_video}{else}{$lang.content.no_photos}{/if}</font>
{/if}