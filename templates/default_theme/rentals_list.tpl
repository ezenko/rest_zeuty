{include file="$gentemplates/site_top.tpl"}
<table cellpadding="0" cellspacing="0" border="0" width="100%">
<tr valign="top">
	<td class="left">
		{include file="$gentemplates/homepage_hotlist.tpl"}
	</td>
	<td class="delimiter">&nbsp;</td>
	<td class="main">
		<!-- RENTAL CONTENT -->
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
				<td class="header"><b>{$lang.headers.rentals}</b></td>
			</tr>
			<tr>
				<td><hr></td>
			</tr>
			{if $error}
			<tr><td class="error_div" style="padding-top: 15px; padding-left:15px;">
					*&nbsp;{$error}
			</td></tr>
			{/if}
		</table>
		<table cellpadding="5" cellspacing="0" width="100%" border="0" style="margin: 0px; margin-left: 10px;">
			<tr>
				<td>{strip}{assign var=user_type_str value="user_type_"|cat:$user_type}
					{$lang.content.list_text_1}&nbsp;<b>{$lang.content[$user_type_str]}.</b>
					{if	$private_person_ads_limit && ($user_ads_cnt > $private_person_ads_limit)}
						<br><br>{$lang.content.your_ads_cnt} <b>{$user_ads_cnt}</b> {$lang.content.listings}.<br>{$lang.content.private_ads_limit_intro} - <b>{$private_person_ads_limit}</b> {$lang.content.private_ads_limit_message}<br><a href="{$server}{$site_root}/account.php">{$lang.content.change_status}</a>
					{else}
						&nbsp;{$lang.content.list_text_2}&nbsp;<a href="./account.php?sel=edit_profile">{$lang.content.list_text_3}</a>
					{/if}
					{/strip}
				</td>
			</tr>
			<tr>
				<td>{$lang.content.list_text_4_1}<a href='./rentals.php?sel=add_rent'>{$lang.content.list_text_4_2}</a></td>
			</tr>
			{if $user_type eq 2}
			<tr>
				<td><a href="{$file_name}?sel=db_operation">{$lang.content.import_listings}</a></td>
			</tr>
			{/if}
			{if	!($private_person_ads_limit && ($user_ads_cnt > $private_person_ads_limit))}
				<tr>
					<td>{$lang.content.list_text_5}&nbsp;{$user_ads_cnt}&nbsp;{$lang.content.list_text_6}</td>
				</tr>
				<tr>
					<td>
					<table cellpadding="0" cellspacing="0" border="0">
						<tr>
							<td><a href="{$add_rent_link}"><img src="{$site_root}{$template_root}{$template_images_root}/add_listing.png" border="0"></a>&nbsp;</td>
							<td><a href="{$add_rent_link}">{$lang.content.add_rent_link_text}</a></td>
						</tr>
					</table>
					</td>
				</tr>
			{/if}
		</table>
		<table cellpadding="0" cellspacing="0" width="100%" border="0">
	 		{if $empty eq 1}
			<tr>
				<td width="15">&nbsp;</td>
				<td class="error">{$lang.content.empty_result}</td>
			</tr>
			{else}
			<tr>
				<td style="padding-bottom: 5px;"><hr></td>
			</tr>
			<tr>
				<td height="33" valign="top" align="right" style="padding-top: 5px;">{$lang.default_select.order_by}:&nbsp;
				<a href="#" {if $sorter eq 3} class="user_menu_link_active" {/if} onclick="document.location.href='{$file_name}?sorter=3{if $sorter eq 3}{$order_active_link}{else}{$order_link}{/if}'">{$lang.default_select.sorter_by_date_move}{if $sorter eq 3}{$order_icon}{/if}</a>&nbsp;|&nbsp;
				<a href="#" {if $sorter eq 4} class="user_menu_link_active" {/if} onclick="document.location.href='{$file_name}?sorter=4{if $sorter eq 4}{$order_active_link}{else}{$order_link}{/if}'">{$lang.default_select.cost}{if $sorter eq 4}{$order_icon}{/if}</a>&nbsp;|&nbsp;				
				<a href="#" {if $sorter eq 6} class="user_menu_link_active" {/if} onclick="document.location.href='{$file_name}?sorter=6{if $sorter eq 6}{$order_active_link}{else}{$order_link}{/if}'">{$lang.default_select.sorter_by_max_view}{if $sorter eq 6}{$order_icon}{/if}</a>&nbsp;|&nbsp;
				<a href="#" {if $sorter eq 0} class="user_menu_link_active" {/if} onclick="document.location.href='{$file_name}?sorter=0{if $sorter eq 0}{$order_active_link}{else}{$order_link}{/if}'">{$lang.default_select.date_reg}{if $sorter eq 0}{$order_icon}{/if}</a>
				</td>
			</tr>
			<tr>
				<td>
					{foreach from=$search_result item=item name=ua}
					{assign var="rows_after_image" value=0}
					<table cellpadding="0" cellspacing="0" width="100%" border="0" style="margin-bottom: 10px;">
						<tr>
							<td width="15" style="padding-left: 15px;" valign="top">{$item.number}.</td>
							<td height="{$thumb_height+10}" width="{$thumb_width+10}" valign="middle" align="center" style=" border: 1px solid #cccccc; ">
								<a href="{$server}{$site_root}/rentals.php?sel=my_ad&id_ad={$item.id_ad}"><img alt="{$item.alt}" src='{$site_root}{$item.image}' style="border: none"></a>
							</td>
							<td valign="top" style="padding-left: 7px;" rowspan="2">
								<table cellpadding="0" cellpadding="0" border="0" width="100%">
								<tr>
									<td>
										<table cellpadding="0" cellspacing="0" border="0">
											<tr>
												<td height="23" valign="top">
													{if $item.id_type eq 1}{$lang.content.need_room}
													{elseif $item.id_type eq 2}{$lang.content.have_room}
													{elseif $item.id_type eq 3}{$lang.content.buy_realty}
													{elseif $item.id_type eq 4}{$lang.content.sell_realty}
													{/if}
													{$item.realty_type}
													{if $item.movedate}&nbsp;&nbsp;&nbsp;{$lang.content.move_date}&nbsp;-&nbsp;{$item.movedate}{if $item.id_type eq 2}&nbsp;<a href='{$site_root}/viewprofile.php?id={$item.id_ad}&view=calendar'>{$lang.default_select.view_by_calendar}</a>{/if}{/if}
												</td>
											</tr>
											<tr>
												<td height="23" valign="top">{if $item.country_name}{$item.country_name}{/if}{if $item.region_name},&nbsp;{$item.region_name}{/if}{if $item.city_name},&nbsp;{$item.city_name}{/if}</td>
											</tr>																				
										</table>
									</td>
									<td valign="top" align="left" width="1%">
										<table cellpadding="0" cellspacing="0">
										<tr>
											{if !$mhi_services}
												{if $item.show_topsearch_icon}
												<td><img alt="{$lang.default_select.star_alt} {$item.topsearch_date_begin}" title="{$lang.default_select.star_alt} {$item.topsearch_date_begin}" align="left" src="{$site_root}{$template_root}{$template_images_root}/icon_up.png" hspace="1"></td>
												{/if}
												{if $item.slideshowed}
												<td><img alt="{$lang.default_select.slideshowed_alt}" title="{$lang.default_select.slideshowed_alt}" align="left" src="{$site_root}{$template_root}{$template_images_root}/icon_photoslideshow.png" hspace="1"></td>
												{/if}
												{if $item.featured}
												<td><img alt="{$lang.default_select.featured_alt}" title="{$lang.default_select.featured_alt}" src="{$site_root}{$template_root}{$template_images_root}/icon_leader.png" hspace="1"></td>
												{/if}
											{/if}
											{if $item.issponsor}
											<td><img alt="{$lang.default_select.sponsored_alt}" title="{$lang.default_select.sponsored_alt}" align="left" src="{$site_root}{$template_root}{$template_images_root}/icon_sponsor.png" hspace="1"></td>
											{/if}
											{if $use_sold_leased_status}
												{if $item.sold_leased_status && ($item.id_type eq '2' || $item.id_type eq '4')}
												<td><img alt="{if $item.id_type eq '4'}{$lang.default_select.sold_alt}{else}{$lang.default_select.leased_alt}{/if}" title="{if $item.id_type eq '4'}{$lang.default_select.sold_alt}{else}{$lang.default_select.leased_alt}{/if}" align="left" src="{$site_root}{$template_root}{$template_images_root}/{if $item.id_type eq '4'}icon_sold.png{else}icon_leased.png{/if}" hspace="1"></td>
												{/if}
											{/if}
											<td>&nbsp;</td>
										</tr>
										</table>
									</td>
								</tr>
								<tr>
									<td height="23" valign="top" colspan="2">
									{if $item.id_type eq 1 || $item.id_type eq 2}{$lang.content.month_payment_in_line}{else}{$lang.content.price}{/if}:&nbsp;<strong>
									{if $item.id_type eq 1 || $item.id_type eq 3}
										{$lang.content.from}&nbsp;{$item.min_payment_show}&nbsp;{$lang.content.upto}&nbsp;{$item.max_payment_show}
									{else}
										{$item.min_payment_show}
									{/if}</strong>,&nbsp;{if $item.auction eq '1'}{$lang.content.auction_possible}{else}{$lang.content.auction_inpossible}{/if}					
									</td>
								</tr>								
								<tr>
									<td height="23" valign="top" colspan="2">									
										{if $item.status eq 1}{$lang.content.place_text_1}{$ad_position[$item.id_ad].place}{$lang.content.place_text_2}{else}{$lang.content.place_text_3}{/if}.																	
									<a href="{$server}{$site_root}/homepage.php?sel=visited_ad&id_ad={$item.id_ad}">{$lang.default_select.link_text_4}</a>:&nbsp;{$lang.default_select.link_text_5}&nbsp;{$ad_position[$item.id_ad].visit_day},&nbsp;{$lang.content.monthly}:&nbsp;{$ad_position[$item.id_ad].visit_month}
									</td>
								</tr>																		
								{if $item.reserve.is_reserved}
								{assign var=rows_after_image value=$rows_after_image+1} 
									<tr>
										<td height="23" valign="top" colspan="2">
											{$lang.content.empty}&nbsp;{$lang.content.time_begin}&nbsp;<strong>{$item.reserve.reserved_start_period}</strong>&nbsp;{$lang.content.time_end}&nbsp;<strong>{$item.reserve.reserved_end_period}</strong>
											&nbsp;<a href='{$server}{$site_root}/viewprofile.php?id={$item.id_ad}&view=calendar'>{$lang.default_select.other_period}</a>
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
										<a href="{$server}{$site_root}/rentals.php?sel=my_ad&id_ad={$item.id_ad}">{$lang.content.edit_descr}</a>&nbsp;&nbsp;|&nbsp;&nbsp;
										<a href="{$server}{$site_root}/rentals.php?sel=listing_position&id_ad={$item.id_ad}">{$lang.content.search_position}</a>&nbsp;&nbsp;|&nbsp;&nbsp;
										<a href="#" onclick="if (confirm('{$lang.content.true_delete_ad}')) document.location.href='{$server}{$site_root}/rentals.php?sel=del&id_ad={$item.id_ad}';">{$lang.buttons.delete}</a>
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
					{if !$smarty.foreach.ua.last}
						<hr width="100%">
					{/if}
					{/foreach}
					{if $links}
					<table cellpadding="2" cellspacing="2" border="0" class="pages_links">
						<tr>
							<td class="text">{$lang.default_select.pages}:
							{foreach item=item from=$links}
							<a href="{$item.link}" {if $item.selected} style="font-weight: bold;"{/if}>{$item.name}</a>
							{/foreach}
							</td>
						</tr>
					</table>
					{/if}
					<hr width="100%">
				</td>
				</tr>
			{/if}
		</table>
		<table cellpadding="0" cellspacing="0" border="0">
			<tr>
				<td style="padding-left: 15px;"><a href="{$add_rent_link}"><img src="{$site_root}{$template_root}{$template_images_root}/add_listing.png" border="0"></a>&nbsp;</td>
				<td><a href="{$add_rent_link}">{$lang.content.add_rent_link_text}</a></td>
			</tr>
		</table>
		<!--END OF RENTAL CONTENT -->
	</td>
</tr>
</table>
{include file="$gentemplates/site_footer.tpl"}