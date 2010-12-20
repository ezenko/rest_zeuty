{include file="$gentemplates/site_top.tpl"}
<table cellpadding="0" cellspacing="0" border="0">
<tr valign="top">
	<td class="left" valign="top">
		{include file="$gentemplates/homepage_hotlist.tpl"}
	</td>
	<td class="delimiter">&nbsp;</td>
	<td class="main">
		<table cellpadding="0" cellspacing="0" width="100%" border="0">
		{if $banner.center}
		<tr>
			<td style="padding-bottom: 2px;">
			<!-- banner center -->
		  	
			<div align="left">
				{$banner.center}
			</div>
		  	
		  	<!-- /banner center -->
	  		</td>
		</tr>
		{/if}
		{if $success_confirm_user}
		<tr>
			<td style="padding-bottom: 2px;">
			<!-- confirm center -->
		  	
			<div align="left" class="error">
				{$success_confirm_user}
			</div>
		  	
		  	<!-- /confirm center -->
	  		</td>
		</tr>
		{/if}
		<tr>
			<td>
			<table cellpadding="0" cellspacing="0" width="100%" border="0">
			<tr>
				<td>
				{include file="$gentemplates/quick_search_main_form.tpl"}
				</td>
			</tr>
			<tr><td>
				<table cellpadding="0" cellspacing="0" width="100%" border="0">
					<tr><td colspan="4">&nbsp;</td></tr>
					<tr>
						<td width="15">&nbsp;</td>
						<td width="74"><img alt="{$logo_settings.homelogo.alt}" src="{$site_root}{$template_root}{$template_images_root}/{$logo_settings.homelogo.img}"></td>
						<td width="12">&nbsp;</td>
						<td valign="top">
							<table cellpadding="3" cellspacing="0" width="100%" border="0">
								<tr>
									<td><img alt="{$logo_settings.slogan.alt}" src="{$site_root}{$template_root}{$template_images_root}/{$logo_settings.slogan.img}"></td>
								</tr>
								<tr>
									<td><b>{$lang.content.home_text_1}</b></td>
								</tr>
								<tr>
									<td>{$lang.content.home_text_2}</td>
								</tr>
								<tr>
									<td>{$lang.content.home_text_3}</td>
								</tr>
							</table>
						</td>
					</tr>
					
				</table>
			</td></tr>
			
			{if (($area_parametres.show_type != "off") && ($search_result))}
				<tr><td colspan="4">&nbsp;</td></tr>
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
			<tr>
				<td class="subheader"><b>{$lang.content.my_contacts}</b></td>
			</tr>
			<tr>
				<td>{include file="$gentemplates/homepage_links.tpl"}</td>
			</tr>						
			</table>
			</td>
		</tr>
		</table>
	</td>
</tr>
</table>
{include file="$gentemplates/site_footer.tpl"}