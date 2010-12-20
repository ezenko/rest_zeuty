{include file="$gentemplates/site_top.tpl"}
<table cellpadding="0" cellspacing="0" border="0" width="100%">
<tr valign="top">
	<td class="left">
		{include file="$gentemplates/homepage_hotlist.tpl"}
	</td>
	<td class="delimiter">&nbsp;</td>
	<td class="main">
	{if $rentals eq 1}
		<table cellpadding="0" cellspacing="0" border="0" width="100%">
		<tr>
			<td class="header"><b>{$lang.headers.match}</b></td>
		</tr>
		<tr><td class="subheader"><b>{$lang.content.page_header_rental}</b></td></tr>
		</table>
		{if $no_user_ad eq 1}
		<table cellpadding="0" cellspacing="0" width="100%" border="0">
		<tr>
			<td width="15">&nbsp;</td>
			<td class="error_div">*&nbsp;{$lang.content.user_ad_err}</td>
		</tr>
		</table>
		{else}
			{include file="$gentemplates/homepage_ads_table.tpl"}
		{/if}
	{else}
	<!--SEARCH RESULTS CONTENT -->
		<table cellpadding="0" cellspacing="0" border="0" width="100%">
		<tr>
			<td colspan="2" class="header"><b>{if $submenu=="matchmyrental_match"}{$lang.content.match}{else}{$lang.headers.homepage}{/if}</b></td>
		</tr>
		{if $submenu=="matchmyrental_match"}
		<tr>
			<td width="100%"><hr></td>
		</tr>
		{else}
		<tr>
			<td colspan="2" class="subheader"><b>{$lang.headers[$submenu]}</b></td>
		</tr>
		{/if}
		{if $empty_result eq 1 && $section != "visited"}
		<tr>
			<td height="27" colspan="2" class="error_div" style="padding-left: 15px;">*&nbsp;{$lang.content.empty_list}</td>
		</tr>		
		<tr>
			<td colspan="2">
				<table cellpadding="0" cellspacing="0" border="0">
					<tr>
						<td style="padding-left: 15px;"><a href="{$server}{$site_root}/rentals.php?sel=add_rent"><img src="{$site_root}{$template_root}{$template_images_root}/add_listing.png" border="0"></a>&nbsp;</td>
						<td><a href="{$server}{$site_root}/rentals.php?sel=add_rent">{$lang.default_select.add_rent_text}</a></td>
					</tr>
				</table>
			</td>
		</tr>
		{else}
		<tr>
			{if $empty_result ne 1 && $no_user_ad ne 1}
			<td height="27" align="right">{$lang.default_select.order_by}:&nbsp;
				{if $users_list}
					<a href="#" {if $sorter eq 0} class="user_menu_link_active" {/if} onclick="document.location.href='{$file_name}?sorter=0{if $sorter eq 0}{$order_active_link}{else}{$order_link}{/if}'">{$lang.content.date_reg}{if $sorter eq 0}{$order_icon}{/if}</a>&nbsp;|&nbsp;
					<a href="#" {if $sorter eq 1} class="user_menu_link_active" {/if} onclick="document.location.href='{$file_name}?sorter=1{if $sorter eq 1}{$order_active_link}{else}{$order_link}{/if}'">{$lang.content.nickname}{if $sorter eq 1}{$order_icon}{/if}</a>&nbsp;|&nbsp;
					<a href="#" {if $sorter eq 2} class="user_menu_link_active" {/if} onclick="document.location.href='{$file_name}?sorter=2{if $sorter eq 2}{$order_active_link}{else}{$order_link}{/if}'">{$lang.content.date_last}{if $sorter eq 2}{$order_icon}{/if}</a>
				{else}
					<a href="#" {if $sorter eq 3} class="user_menu_link_active" {/if} onclick="document.location.href='{$file_name}?sorter=3{if $sorter eq 3}{$order_active_link}{else}{$order_link}{/if}'">{$lang.default_select.sorter_by_date_move}{if $sorter eq 3}{$order_icon}{/if}</a>&nbsp;|&nbsp;
					<a href="#" {if $sorter eq 4} class="user_menu_link_active" {/if} onclick="document.location.href='{$file_name}?sorter=4{if $sorter eq 4}{$order_active_link}{else}{$order_link}{/if}'">{$lang.default_select.cost}{if $sorter eq 4}{$order_icon}{/if}</a>&nbsp;|&nbsp;
					<a href="#" {if $sorter eq 2} class="user_menu_link_active" {/if} onclick="document.location.href='{$file_name}?sorter=2{if $sorter eq 2}{$order_active_link}{else}{$order_link}{/if}'">{$lang.content.date_last}{if $sorter eq 2}{$order_icon}{/if}</a>&nbsp;|&nbsp;
					<a href="#" {if $sorter eq 1} class="user_menu_link_active" {/if} onclick="document.location.href='{$file_name}?sorter=1{if $sorter eq 1}{$order_active_link}{else}{$order_link}{/if}'">{$lang.content.nickname}{if $sorter eq 1}{$order_icon}{/if}</a>&nbsp;|&nbsp;
					<a href="#" {if $sorter eq 0} class="user_menu_link_active" {/if} onclick="document.location.href='{$file_name}?sorter=0{if $sorter eq 0}{$order_active_link}{else}{$order_link}{/if}'">{$lang.default_select.date_reg}{if $sorter eq 0}{$order_icon}{/if}</a>
				{/if}
			</td>
			{/if}
		</tr>
		{/if}
		</table>
		{if $success_confirm_user}
		<table><tr>
			<td style="padding-bottom: 2px;">
			<!-- confirm center -->

			<div align="left" class="error">
				{$success_confirm_user}
			</div>

		  	<!-- /confirm center -->
	  		</td>
		</tr></table>
		{/if}
		{if $section eq 'visited'}
		<table cellpadding="0" cellspacing="0" width="100%" border="0">
			<tr>
				<td style="padding-top: 10px; padding-bottom: 10px; padding-left: 15px;">*&nbsp;{$lang.default_select.viewed_top_text_alert}</td>
			</tr>
		</table>
		{/if}
		{if $empty_result ne 1}
		<table cellpadding="0" cellspacing="0" width="100%" border="0">
		<tr>
			<td>
			{if $section ne 'match'}
				{include file="$gentemplates/users_list.tpl"}
			{else}
				{if $feature_ad}
					{include file="$gentemplates/feature_user.tpl"}
				{/if}
				{include file="$gentemplates/search_results_users.tpl"}
			{/if}
			</td>
		</tr>
		</table>
		<table cellpadding="0" cellspacing="0">
			<tr><td>&nbsp;</td></tr>
		</table>
		{/if}
	<!--END OF SEARCH RESULTS CONTENT -->
	{/if}
	</td>
</tr>
</table>
{include file="$gentemplates/site_footer.tpl"}