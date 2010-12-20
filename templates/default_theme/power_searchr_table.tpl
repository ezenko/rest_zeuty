{include file="$gentemplates/site_top.tpl"}
<table cellpadding="0" cellspacing="0" border="0" width="100%">
<tr valign="top">
	<td class="left">
		{include file="$gentemplates/search_menu.tpl"}
	</td>
	<td class="delimiter">&nbsp;</td>
	<td class="main">
		<!--POWER SEARCH RESULTS CONTENT -->
		<table cellpadding="0" cellspacing="0" border="0" width="100%">
		{if $banner.center}
		<tr>
			<td colspan="2">
			<!-- banner center -->
		  	
				<div align="left">{$banner.center}</div>
			
			 <!-- /banner center -->
			</td>
		</tr>
		{/if}
		<tr>
			<td colspan="2" class="header"><b>{$lang.headers.search_database}</b></td>
		</tr>
		<tr>
			<td colspan="2" class="subheader"><b>{$lang.content.results}</b>&nbsp;&nbsp;{if $empty_result ne 1 && $no_user_ad ne 1}({$search_size}){/if}</td>
		</tr>
		{if $empty_result eq 1}
		<tr>
			<td height="27" colspan="2" class="error_div" style="padding-left: 15px;">*&nbsp;{$lang.content.empty_result}<a href="power_searchr.php?back=1">&nbsp;{$lang.content.search_criteria}</a></td>
		</tr>
		{/if}
		{if ($search_result || $feature_ad) && $use_maps_in_search_results && $sel && $map.name == "google"}
		{assign var="show_map" value="1"}
			<tr>
				<td colspan="2" align="right" style="padding-top: 10px;">
					<div id="map_container" {if $map.name == "microsoft_dont_work"}style="position: relative; width: 700px; height: 400px;"{/if}></div>
				</td>
			</tr>
			<tr><td>&nbsp;</td></tr>
		{/if}				
		{if $empty_result ne 1 && $no_user_ad ne 1}
		<tr>
			<td height="33" align="right" valign="top" {if $show_map!=1}style="padding-top: 5px;"{/if}>{$lang.default_select.order_by}:&nbsp;
				<a href="#" {if $sorter eq 3} class="user_menu_link_active" {/if} onclick="document.location.href='{$file_name}?sorter=3{if $sorter eq 3}{$order_active_link}{else}{$order_link}{/if}'">{$lang.default_select.sorter_by_date_move}{if $sorter eq 3}{$order_icon}{/if}</a>&nbsp;|&nbsp;
				<a href="#" {if $sorter eq 4} class="user_menu_link_active" {/if} onclick="document.location.href='{$file_name}?sorter=4{if $sorter eq 4}{$order_active_link}{else}{$order_link}{/if}'">{$lang.default_select.cost}{if $sorter eq 4}{$order_icon}{/if}</a>&nbsp;|&nbsp;
				<a href="#" {if $sorter eq 2} class="user_menu_link_active" {/if} onclick="document.location.href='{$file_name}?sorter=2{if $sorter eq 2}{$order_active_link}{else}{$order_link}{/if}'">{$lang.content.date_last}{if $sorter eq 2}{$order_icon}{/if}</a>&nbsp;|&nbsp;
				<a href="#" {if $sorter eq 1} class="user_menu_link_active" {/if} onclick="document.location.href='{$file_name}?sorter=1{if $sorter eq 1}{$order_active_link}{else}{$order_link}{/if}'">{$lang.content.nickname}{if $sorter eq 1}{$order_icon}{/if}</a>&nbsp;|&nbsp;
				<a href="#" {if $sorter eq 6} class="user_menu_link_active" {/if} onclick="document.location.href='{$file_name}?sorter=6{if $sorter eq 6}{$order_active_link}{else}{$order_link}{/if}'">{$lang.default_select.sorter_by_max_view}{if $sorter eq 6}{$order_icon}{/if}</a>&nbsp;|&nbsp;
				<a href="#" {if $sorter eq 0} class="user_menu_link_active" {/if} onclick="document.location.href='{$file_name}?sorter=0{if $sorter eq 0}{$order_active_link}{else}{$order_link}{/if}'">{$lang.default_select.date_reg}{if $sorter eq 0}{$order_icon}{/if}</a>
			</td>
		</tr>
		{/if}
		</table>
		{if $empty_result ne 1}
		<table cellpadding="0" cellspacing="0" width="100%" border="0">
		{if $feature_ad}
		<tr>
			<td>{include file="$gentemplates/feature_user.tpl"}</td>
		</tr>
		{/if}
		<tr>
			<td>{include file="$gentemplates/search_results_users.tpl"}</td>
		</tr>
		</table>
		<div style="padding: 10px 0px 10px 15px;"><a href="./power_searchr.php?back=1">{$lang.content.back_to_search}</a></div>
		{/if}		
		<table cellpadding="0" cellspacing="0">
		<tr>
			<td style="padding-left: 15px;"><a href="./rentals.php?sel=list_ads"><img src="{$site_root}{$template_root}{$template_images_root}/add_listing.png" border="0"></a>&nbsp;</td>
			<td><a href="./rentals.php?sel=list_ads">{$lang.content.add}</a></td>
		</tr>
		</table>
	<!--END OF POWER SEARCH RESULTS CONTENT -->
	</td>
</tr>
</table>
{include file="$gentemplates/site_footer.tpl"}