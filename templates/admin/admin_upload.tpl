{include file="$admingentemplates/admin_top.tpl"}
	<table cellpadding="0" cellspacing="0" border="0" width="100%">
		<tr>
			<td class="header" align="left">{$lang.content.page_header}<font class="subheader">| {$lang.content.page_subheader}</font></td>
		</tr>
		<tr>
			<td><div class="help_text"><span class="help_title">{$lang.default_select.help}</span>{$lang.content.help}</div></td>
		</tr>
		<tr>
			<td>
			{strip}
			<b>{$lang.content.file_types}:</b>&nbsp;&nbsp;&nbsp;
			{if $type_upload == "rent_photo"}<b>{else}<a href="{$file_name}?type_upload=rent_photo">{/if}
			{$lang.content.rent_photo_type} ({$data.rent_photo_count})
			{if $type_upload == "rent_photo"}</b>{else}</a>{/if}
			&nbsp;&nbsp;&nbsp;
			{if $type_upload == "rent_plan"}<b>{else}<a href="{$file_name}?type_upload=rent_plan">{/if}
			{$lang.content.rent_plan_type} ({$data.rent_plan_count})
			{if $type_upload == "rent_plan"}</b>{else}</a>{/if}
			&nbsp;&nbsp;&nbsp;
			{if $type_upload == "rent_video"}<b>{else}<a href="{$file_name}?type_upload=rent_video">{/if}
			{$lang.content.rent_video_type} ({$data.rent_video_count})
			{if $type_upload == "rent_video"}</b>{else}</a>{/if}
			&nbsp;&nbsp;&nbsp;
			{if $type_upload == "user_photo"}<b>{else}<a href="{$file_name}?type_upload=user_photo">{/if}
			{$lang.content.user_photo_type} ({$data.user_photo_count})
			{if $type_upload == "user_photo"}</b>{else}</a>{/if}
			{/strip}
			</td>
		</tr>
		<tr>
			<td>
			{if $files}
				<form method="post" action="{$file_name}?sel=save_status" name="banner_status">
				<input type="hidden" name="type_upload" value="{$type_upload}">
				<table class="table_main" cellspacing=1 cellpadding=3 border="0" width="100%" style="margin-top: 10px;">
				<tr>
					<td class="main_header_text" align="center" nowrap>{$lang.content.number}</td>
					<td class="main_header_text" align="center" nowrap>{$lang.content.file}</td>
					<td class="main_header_text" align="center" nowrap>{$lang.content.comment}</td>
					<td class="main_header_text" align="center" nowrap>{$lang.content.relate}</td>
					<td class="main_header_text" align="center" nowrap>{$lang.content.approve}</td>
					<td class="main_header_text" align="center" nowrap>{$lang.content.decline}</td>
				</tr>
				{foreach from=$files item=file name=files}
					<tr>
						<td valign="top" width="1%" align="center">{$smarty.foreach.files.iteration+$counter_start}</td>
						<td valign="top" width="35%" align="center">
						
						<a href="#" onclick="javascript: window.open('{if $type_upload eq 'rent_video'}{$file.view_link}{else}{$view_link}{/if}{if $file.uid == 2}{$file.uid}_{$file.id}{else}{$file.id}{/if}','view_file','top=10, left=10,menubar=0, resizable=1, scrollbars=1,status=0,toolbar=0, width={$file.width+20}, height={$file.height+70}');return false;" target="_blank" style="text-decoration: none;"><img src="{$server}{$site_root}/{$folder}/{$file.icon}" style="border-style: solid; border-width: 1px; border-color: #cccccc; padding-top: 3px; padding-bottom: 3px; padding-left: 3px; padding-right: 3px;"></a>
						</td>
						<td valign="top" width="44%" align="center">{$file.user_comment}</td>
						<td valign="top" width="15%" align="center"><input type="button" value="{$lang.buttons.view}" onclick="javascript: window.open('{$file.relate_link}','file_relate','menubar=1, resizable=1, scrollbars=1, status=0, toolbar=1; return false;');"></td>
						{if $type_upload != "user_photo"}
						<td valign="top" width="5%" align="center"><input type="checkbox" name="approve[]" value="{$file.id}" id="approve_{$file.id}" onClick="javascript: ChangeStatus('approve', '{$file.id}');"></td>
						<td valign="top" width="5%" align="center"><input type="checkbox" name="decline[]" value="{$file.id}" id="decline_{$file.id}" onClick="javascript: ChangeStatus('decline', '{$file.id}');"></td>						
						{else}
						<td valign="top" width="5%" align="center"><input type="checkbox" name="approve[]" value="{$file.uid}_{$file.id}" id="approve_{$file.uid}_{$file.id}" onClick="javascript: ChangeStatus('approve', '{$file.uid}_{$file.id}');"></td>
						<td valign="top" width="5%" align="center"><input type="checkbox" name="decline[]" value="{$file.uid}_{$file.id}" id="decline_{$file.uid}_{$file.id}" onClick="javascript: ChangeStatus('decline', '{$file.uid}_{$file.id}');"></td>						
						{/if}
						
					</tr>
				{/foreach}
				</table>
				<table cellpadding="0" cellspacing="0" width="100%">
					<tr>
					{if $links}
						<td>
						{foreach item=item from=$links}
							<a href="{$item.link}" class="page_link" {if $item.selected} style=" font-weight: bold; text-decoration: none;" {/if}>{$item.name}</a>
						{/foreach}
						</td>
					{/if}
					<td align="right"><input type="submit" value="{$lang.buttons.save}"></td>
					</tr>
				</table>
			{else}
				<div style="margin: 20px 0px 20px 0px;">{$lang.content.no_waiting_files}</div>
			{/if}
			</td>
		</tr>


	</table>
{literal}
<script language="javascript">
	function ChangeStatus(type, file_id) {
		var elem_approve_id = "approve_" + file_id;
		var elem_decline_id = "decline_" + file_id;
		if (type == 'approve') {
			if (document.getElementById(elem_approve_id).checked) {
				document.getElementById(elem_decline_id).disabled = true;
			} else {
				document.getElementById(elem_decline_id).disabled = false;
			}
		}
		if (type == 'decline') {
			if (document.getElementById(elem_decline_id).checked) {
				document.getElementById(elem_approve_id).disabled = true;
			} else {
				document.getElementById(elem_approve_id).disabled = false;
			}
		}
	}
</script>
{/literal}
{include file="$admingentemplates/admin_bottom.tpl"}