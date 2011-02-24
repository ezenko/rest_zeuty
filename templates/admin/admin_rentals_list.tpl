{include file="$admingentemplates/admin_top.tpl"}
<table cellpadding="0" cellspacing="0" border="0" width="100%">
<tr valign="top">
	<td class="main">
		<!-- RENTAL CONTENT -->
		<table cellpadding="0" cellspacing="0" border="0" width="100%">
			<tr>
				<td class="header">{$lang.menu.realestate} <font class="subheader">| {$lang.menu.realestate} | {$lang.menu.realestate_listings}</font></td>
			<tr>
				<td><div class="help_text"><span class="help_title">{$lang.default_select.help}</span>{$lang.content.realestate_listings_help}</div></td>
			</tr>
		</table>
		<table cellpadding="5" cellspacing="0" width="100%" border="0">
			<tr>
				<td>{$lang.content.list_text_1}{assign var=user_type_str value="user_type_"|cat:$user_type}&nbsp;<b>{$lang.content[$user_type_str]}.</b>&nbsp;{$lang.content.list_text_2}&nbsp;<a href="./admin_settings.php?section=admin">{$lang.content.list_text_3}</a></td>
			</tr>
			{if $user_type eq 2}
			<tr>
				<td><b>{$lang.content.list_link_text_2}</b></td>
			</tr>
			{/if}
			<tr>
				<td>{$lang.content.list_text_5}&nbsp;{$num_records}&nbsp;{$lang.content.list_text_6}</td>
			</tr>
			<tr>
				<td>
					<table cellpadding="0" cellspacing="0" border="0">
						<tr>
							<td><a href="{$add_rent_link}"><img src="{$site_root}{$template_root}/images/add_listing.png" border="0"></a>&nbsp;</td>
							<td><a href="{$add_rent_link}">{$lang.content.add_rent_link_text}</a></td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
			</tr>
		</table>
		<table cellpadding="5" cellspacing="0" width="100%" border="0">
	 		{if $empty eq 1}
			<tr>
				<td class="error">{$lang.content.empty_result}</td>
			</tr>
			{else}
			<tr>
				<td>
					{foreach from=$ads item=item name=ua}
					{assign var="rows_after_image" value=0}					
					<table cellpadding="0" cellspacing="0" width="100%" border="0" style="margin-bottom: 10px;">
						<tr>
							<td width="15" valign="top">{$item.number}.</td>
							<td height="{$thumb_height+10}" width="{$thumb_width+10}" valign="middle" align="center" style=" border: 1px solid #cccccc; ">
								<a href="{$item.edit_link}"><img alt="" src='{$item.thumb_file[0]}' style="border: none"></a>
							</td>
							<td valign="top" style="padding-left: 7px;" rowspan="2">
								<table cellpadding="0" cellpadding="0" border="0" width="100%">
								<tr>
									<td>
										<table cellpadding="0" cellspacing="0" border="0">
											<tr>
												<td height="23" valign="top">
													{if $item.type eq 1}{$lang.content.category_wild}
													{elseif $item.type eq 3}{$lang.content.category_realty}
													{elseif $item.type eq 2}{$lang.content.category_tours}
													{elseif $item.type eq 4}{$lang.content.category_active}
													{/if}
													{$item.realty_type}
													{if $item.movedate}&nbsp;&nbsp;&nbsp;{$lang.content.move_date}&nbsp;-&nbsp;{$item.movedate}{if $item.type eq 2}&nbsp;<a href='{$site_root}/admin/admin_rentals.php?sel=calendar&id_ad={$item.id}'>{$lang.content.view_by_calendar}</a>{/if}{/if}
												</td>
											</tr>
											<tr>
												<td height="23" valign="top">
													{if $item.country_name}{$item.country_name}{/if}
													{if $item.region_name},&nbsp;{$item.region_name}{/if}
													{if $item.city_name},&nbsp;{$item.city_name}{/if}
												</td>
											</tr>
										</table>
									</td>
									{if !$mhi_services}
									<td valign="top" align="right" width="1%">
										<table cellpadding="0" cellspacing="0">
										<tr>
											{if $item.show_topsearch_icon}
											<td><img alt="{$lang.content.star_alt} {$item.topsearch_date_begin}" title="{$lang.content.star_alt} {$item.topsearch_date_begin}" align="left" src="{$site_root}{$index_template_root}{$template_images_root}/icon_up.png" hspace="1"></td>
											{/if}
											{if $item.slideshowed}
											<td><img alt="{$lang.content.slideshowed_alt}" title="{$lang.content.slideshowed_alt}" align="left" src="{$site_root}{$index_template_root}{$template_images_root}/icon_photoslideshow.png" hspace="1"></td>
											{/if}
											{if $item.featured}
											<td><img alt="{$lang.content.featured_alt}" title="{$lang.content.featured_alt}" src="{$site_root}{$index_template_root}{$template_images_root}/icon_leader.png" hspace="1"></td>
											{/if}
											{if $item.issponsor}
											<td><img alt="{$lang.content.sponsored_alt}" title="{$lang.default_select.sponsored_alt}" align="left" src="{$site_root}{$index_template_root}{$template_images_root}/icon_sponsor.png" hspace="1"></td>
											{/if}
											{if $use_sold_leased_status}
												{if $item.sold_leased_status && ($item.type eq '2' || $item.type eq '4')}
												<td><img alt="{if $item.type eq '4'}{$lang.content.sold_alt}{else}{$lang.content.leased_alt}{/if}" title="{if $item.type eq '4'}{$lang.content.sold_alt}{else}{$lang.content.leased_alt}{/if}" align="left" src="{$site_root}{$index_template_root}{$template_images_root}/{if $item.type eq '4'}icon_sold.png{else}icon_leased.png{/if}" hspace="1"></td>
												{/if}
											{/if}	
											<td>&nbsp;</td>
										</tr>
										</table>
									</td>
									{/if}	
								</tr>																			
								<tr>
									<td height="23" valign="top" colspan="2">{if $item.status eq 1}{$lang.content.place_text_1}{$item.place}{$lang.content.place_text_2}{else}{$lang.content.place_text_3}{/if}</td>
								</tr>
								<tr>
									<td height="23" valign="top" colspan="2">{$lang.content.link_text_4}:&nbsp;{$lang.content.link_text_5}&nbsp;{$item.visit_day},&nbsp;{$lang.content.monthly}:&nbsp;{$item.visit_month}</td>
								</tr>																							
								{if $item.reserve.is_reserved}
								{assign var=rows_after_image value=$rows_after_image+1} 
									<tr>
										<td height="23" valign="top" colspan="2">					
											{$lang.content.empty}&nbsp;{$lang.content.time_begin}&nbsp;<strong>{$item.reserve.reserved_start_period}</strong>&nbsp;{$lang.content.time_end}&nbsp;<strong>{$item.reserve.reserved_end_period}</strong>
											&nbsp;<a href='{$site_root}/admin/admin_rentals.php?sel=calendar&id_ad={$item.id_ad}'>{$lang.default_select.other_period}</a>
										</td>
									</tr>
								{/if}
								{if $item.headline}
								{assign var=rows_after_image value=$rows_after_image+1} 
								<tr>
									<td height="23" valign="top" colspan="2">{$item.headline}</td>
								</tr>
								{/if}																
								<tr>
									<td colspan="2" valign="top">{strip}
										<a href="{$item.edit_link}">{$lang.content.edit}</a>&nbsp;&nbsp;|&nbsp;&nbsp;
										<a href="{$server}{$site_root}/admin/admin_rentals.php?sel=listing_position&id_ad={$item.id}">{$lang.content.search_position}</a>&nbsp;&nbsp;|&nbsp;&nbsp;										
										<a href="#" onclick="if (confirm('{$lang.content.true_delete_ad}')) document.location.href='{$file_name}?sel=del&amp;id_ad={$item.id}';">{$lang.buttons.delete}</a>										
										{/strip}
									</td>
								</tr>
								</table>
							</td>
						</tr>
						{if $rows_after_image > 0}
						<tr>
							<td width="15" height="{$rows_after_image*23}">&nbsp;</td>							
						</tr>
						{/if}						
					</table>
					<hr class="listing" width="100%">
					{/foreach}					
					{if $links}
					<table cellpadding="2" cellspacing="2" border="0">
						<tr>
							<td class="text">{$lang.content.pages}:
							{foreach item=item from=$links}
							<a href="{$item.link}" {if $item.selected} style="font-weight: bold;"{/if}>{$item.name}</a>
							{/foreach}
							</td>
						</tr>
					</table>
					{/if}
				</td>
			</tr>
			{/if}
		</table>
		<table cellpadding="0" cellspacing="0" border="0">
			<tr>
				<td><a href="{$add_rent_link}"><img src="{$site_root}{$template_root}/images/add_listing.png" border="0"></a>&nbsp;</td>
				<td><a href="{$add_rent_link}">{$lang.content.add_rent_link_text}</a></td>
			</tr>
		</table>

		<!--END OF RENTAL CONTENT -->
	</td>
</tr>
</table>
{include file="$admingentemplates/admin_bottom.tpl"}