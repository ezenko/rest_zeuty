{include file="$gentemplates/site_top.tpl"}
<table cellpadding="0" cellspacing="0" border="0" width="100%">
<tr valign="top">
	<td class="left">
		{include file="$gentemplates/search_menu.tpl"}
	</td>
	<td class="delimiter">&nbsp;</td>
	<td class="main">
	<table cellpadding="0" cellspacing="0" width="100%" border="0">
		{if $banner.center}
			<tr>
				<td>
				<!-- banner center -->
			  	
					<div align="left" style="padding-bottom: 4px;">{$banner.center}</div>
				
				 <!-- /banner center -->
				</td>
			</tr>
		{/if}	
			<!-- /quick_search -->
			<tr><td class="header"><b>{$lang.headers.search_database}</b></td></tr>			
			<tr><td><hr></td></tr>			
			<tr>
				<td valign="top" style="padding: 0px; padding-bottom: 4px;">
				{include file="$gentemplates/quick_search_main_form.tpl"}
				</td>
			</tr>
			<!-- /quick_search -->
			<!-- last offers -->
			{if (($area_parametres.show_type != "off") && ($search_result))}
			<tr>
				<td class="subheader"><b>{$lang.content[$area_parametres.show_type]}</b></td>
			</tr>
			{/if}			
					{if $area_parametres.view_type eq "row"}
						{include file="$gentemplates/index_last_ads.tpl"}
					{else}
						<tr>
							<td style="padding-top: 10px;">
						{include file="$gentemplates/search_results_users.tpl"}
							</td>
						</tr>
					{/if}
			</table>
	</td>
</tr>
</table>
{include file="$gentemplates/site_footer.tpl"}