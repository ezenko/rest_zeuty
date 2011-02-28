{include file="$gentemplates/site_top.tpl"}

<div id="middle-container">
  <h2>{if $category_title}{$category_title}{else}Результат поиска{/if} :</h2>
  <div id="middle-holder">
	<img src="img/fake/pict.jpg" alt="Сочи - город курорт" class="img-border" />
	<br/>
    {if $empty_result eq 1}
		<table cellpadding="0" cellspacing="0">
			<tr>				
				<td width="5" height="27">&nbsp;</td>
				<td class="error_div">*&nbsp;{$lang.content.empty_result} <a href="{$back_link}">{$lang.content.empty_res_search_criteria}</a></td>			
			</tr>
		</table>
	{else}
		{if $feature_ad}
		  {include file="$gentemplates/feature_user.tpl"}
		{/if}
		  {include file="$gentemplates/search_results_users.tpl"}
	{/if}
	
    
  </div>
  
</div>
{*
<table cellpadding="0" cellspacing="0" border="0" width="100%">
<tr valign="top">
	<td class="left">
	{include file="$gentemplates/search_menu.tpl"}
	</td>
	<td class="delimiter">&nbsp;</td>
	<td class="main">
		<!--QUICK SEARCH RESULTS CONTENT -->
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
			{if ($search_result || $feature_ad) && $use_maps_in_search_results && $sel && $map.name == "google"}
			{assign var="show_map" value="1"}
				<tr>
					<td colspan="2" align="right" style="padding-top: 10px;">
						<div id="map_container" {if $map.name == "microsoft_dont_work"}style="position: relative; width: 700px; height: 400px;"{/if}></div>
					</td>
				</tr>
				<tr><td>&nbsp;</td></tr>
			{/if}	
			{if $empty_result ne 1}
			<tr>
				<td height="33" style="padding-bottom: 3px;">&nbsp;</td>
				{if $empty_result ne 1 && $no_user_ad ne 1}
				<td height="33" valign="top" align="right" {if $show_map!=1}style="padding-top: 5px;"{/if}>{$lang.default_select.order_by}:&nbsp;
				<a href="#" {if $sorter eq 3} class="user_menu_link_active" {/if} onclick="document.location.href='{$file_name}?sorter=3{if $sorter eq 3}{$order_active_link}{else}{$order_link}{/if}'">{$lang.default_select.sorter_by_date_move}{if $sorter eq 3}{$order_icon}{/if}</a>&nbsp;|&nbsp;
				<a href="#" {if $sorter eq 4} class="user_menu_link_active" {/if} onclick="document.location.href='{$file_name}?sorter=4{if $sorter eq 4}{$order_active_link}{else}{$order_link}{/if}'">{$lang.default_select.cost}{if $sorter eq 4}{$order_icon}{/if}</a>&nbsp;|&nbsp;
				<a href="#" {if $sorter eq 2} class="user_menu_link_active" {/if} onclick="document.location.href='{$file_name}?sorter=2{if $sorter eq 2}{$order_active_link}{else}{$order_link}{/if}'">{$lang.content.date_last}{if $sorter eq 2}{$order_icon}{/if}</a>&nbsp;|&nbsp;
				<a href="#" {if $sorter eq 1} class="user_menu_link_active" {/if} onclick="document.location.href='{$file_name}?sorter=1{if $sorter eq 1}{$order_active_link}{else}{$order_link}{/if}'">{$lang.content.nickname}{if $sorter eq 1}{$order_icon}{/if}</a>&nbsp;|&nbsp;
				<a href="#" {if $sorter eq 6} class="user_menu_link_active" {/if} onclick="document.location.href='{$file_name}?sorter=6{if $sorter eq 6}{$order_active_link}{else}{$order_link}{/if}'">{$lang.default_select.sorter_by_max_view}{if $sorter eq 6}{$order_icon}{/if}</a>&nbsp;|&nbsp;
				<a href="#" {if $sorter eq 0} class="user_menu_link_active" {/if} onclick="document.location.href='{$file_name}?sorter=0{if $sorter eq 0}{$order_active_link}{else}{$order_link}{/if}'">{$lang.default_select.date_reg}{if $sorter eq 0}{$order_icon}{/if}</a>
				</td>
				{/if}
			</tr>
			{/if}
		</table>
		{if $empty_result eq 1}
		<table cellpadding="0" cellspacing="0">
			<tr>				
				<td width="5" height="27">&nbsp;</td>
				<td class="error_div">*&nbsp;{$lang.content.empty_result} <a href="{$back_link}">{$lang.content.empty_res_search_criteria}</a></td>			
			</tr>
		</table>
		{else}
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
			<table cellpadding="0" cellspacing="0">
				<tr><td>&nbsp;</td></tr>
			</table>
		{/if}
		{if !$empty_result}
		<table cellpadding="0" cellspacing="0" style="margin: 0px; margin-bottom: 10px;">
		<tr>
			<td width="15">&nbsp;</td>
			<td><a href="{$back_link}">{$lang.content.back_to_search}</a></td>
		</tr>
		</table>
		{/if}
		{if $registered}
			<table cellpadding="0" cellspacing="0">
			<tr>
				<td style="padding-left: 15px;"><a href="./rentals.php?sel=list_ads"><img src="{$site_root}{$template_root}{$template_images_root}/add_listing.png" border="0"></a>&nbsp;</td>
				<td><a href="./rentals.php?sel=list_ads">{$lang.content.add}</a></td>
			</tr>
			</table>
		{else}
			{if !$mhi_registration}
			<table cellpadding="0" cellspacing="0">
			<tr>
				<td style="padding-left: 15px;"><a href="./registration.php?from=sresults&amp;c={$post_data.hidden_choise}"><img src="{$site_root}{$template_root}{$template_images_root}/add_listing.png" border="0"></a>&nbsp;</td>
				<td><a href="./registration.php?from=sresults&amp;c={$post_data.hidden_choise}">{$lang.content.add}</a></td>
			</tr>
			</table>
			{/if}
		{/if}		
	<!--END OF QUICK SEARCH RESULTS CONTENT -->
	</td>
</tr>
</table>
*}
{include file="$gentemplates/site_footer.tpl"}