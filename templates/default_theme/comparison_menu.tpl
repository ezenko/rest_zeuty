<tr id="comparison_menu" {if !$comparison_ids}style="visibility: hidden;"{/if}>
	<td align="left">
		<table cellpadding="0" cellspacing="0" class="comparison_menu" {if !$registered}style="margin-top: 25px; margin-left: 12px;"{/if}>
			<tr>
				<td><b>{$lang.default_select.comparison_list}</b>:</td>
			</tr>
			<tr>
				<td id="comparison_list">
				{strip}
				{foreach from=$comparison_ids item=cid}
				<div class="comp_list_item">
				<a href="{$server}{$site_root}/viewprofile.php?id={$cid.id}">{$cid.fname}</a><a href="#" onclick="DeleteFromComparisonList('comparison_list', {$cid.id});" title="{$lang.default_select.delete_from_comparison}"><img src="{$server}{$site_root}{$template_root}{$template_images_root}/delete.gif" alt="{$lang.default_select.delete_from_comparison}" class="comp_list_icon"></a><br>{$lang.default_select[$cid.type]} {$cid.realty_type}
				</div>
				{/foreach}
				{/strip}
				</td>
			</tr>
			<tr>
				<td><a href="{$server}{$site_root}/compare.php">{$lang.default_select.compare}</a>&nbsp;&nbsp;<a href="#" onclick="ClearComparisonList('comparison_list');">{$lang.default_select.clear_comparison_list}</a></td>
			</tr>
		</table>
	</td>
</tr>