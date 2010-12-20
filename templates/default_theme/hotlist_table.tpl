{include file="$gentemplates/site_top.tpl"}
<table cellpadding="0" cellspacing="0" border="0" width="100%">
<tr valign="top">
	<td class="left">
		{include file="$gentemplates/homepage_hotlist.tpl"}
	</td>
	<td class="delimiter">&nbsp;</td>
	<td class="main">
	<!-- HOTLIST CONTENT -->
	<table cellpadding="0" cellspacing="0" border="0" width="100%">
	{if $banner.center}
		<tr>
			<td>
			 <!-- banner center -->
		  	
				<div align="left">{$banner.center}</div>
			
			 <!-- /banner center -->
			</td>
		</tr>
	{/if}	
		<tr>
			<td class="header"><b>{$lang.headers.hotlist}<b></td>
		</tr>
		<tr>
			<td><hr></td>
		</tr>
		{if $empty eq 1}
		<tr>
			<td>
				<table cellpadding="0" cellspacing="0">
					<tr>
						<td width="15">&nbsp;</td>
						<td class="error">*&nbsp;{$lang.content.empty_result}</td>
					</tr>
				</table>
			</td>
		</tr>
		{else}
		<tr>
			<td height="27" align="right">{$lang.default_select.order_by}:&nbsp;
			<a href="{$file_name}?sorter=0{if $sorter eq 0}{$order_active_link}{else}{$order_link}{/if}" {if $sorter eq 0} class="user_menu_link_active" {/if}>{$lang.content.date_reg}{if $sorter eq 0}{$order_icon}{/if}</a>&nbsp;|&nbsp;
			<a href="{$file_name}?sorter=1{if $sorter eq 1}{$order_active_link}{else}{$order_link}{/if}" {if $sorter eq 1} class="user_menu_link_active" {/if}>{$lang.content.nickname}{if $sorter eq 1}{$order_icon}{/if}</a>&nbsp;|&nbsp;
			<a href="{$file_name}?sorter=2{if $sorter eq 2}{$order_active_link}{else}{$order_link}{/if}" {if $sorter eq 2} class="user_menu_link_active" {/if}>{$lang.content.date_last}{if $sorter eq 2}{$order_icon}{/if}</a>
			</td>
		</tr>
		<tr>
			<td>
				<table cellpadding="0" cellspacing="0" width="100%">
					<tr><td>
					{include file="$gentemplates/users_list.tpl"}
					</td></tr>
				</table>
			</td>
		</tr>
		{/if}
	</table>
	<!--END OF HOTLIST CONTENT -->
	</td>
</tr>
</table>
{include file="$gentemplates/site_footer.tpl"}