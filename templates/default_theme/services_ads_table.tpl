		<table cellpadding="0" cellspacing="0" width="100%" border="0">
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
			<td colspan="2" class="subheader" ><b>{if $par == "featured"}{$lang.headers.featured}{elseif $par == "top_search"}{$lang.headers.top_search}{elseif $par == "slideshow"}{$lang.headers.slideshow}{/if}</b></td>
		</tr>
		<tr>
			<td width="15">&nbsp;</td>
			<td style="padding-bottom: 10px;padding-top:10px;">
			<table cellpadding="0" cellspacing="0">
			{if $error}
			<tr><td style="padding-bottom: 10px;"><div class="error">*&nbsp;{$error}</div></td></tr>
			{/if}
			<tr><td>
			{strip}
			{if $par == "featured"}{$lang.content.featured_top_text}
			{elseif $par == "top_search"}{$lang.content.topsearch_top_text}
			{elseif $par == "slideshow"}{$lang.content.slideshow_top_text_p1} {$service_period} {$lang.content.slideshow_top_text_p2}
			{/if}
			{if $par == "top_search" || $par == "slideshow"}
				<br><br><b>{$lang.content.cost} - {$cur_symbol} {$service_cost}</b>
			{/if}
			{/strip}
			</td></tr>
			</table>
			</td>
		</tr>
		<tr>
			<td colspan="2" class="subheader"><b>{$lang.headers.services_user_rent_ads}</b></td>
		</tr>
		</table>
<table cellpadding="0" cellspacing="0" width="100%" border="0">
		<tr>
			<td>
				{if $empty eq 1}
				<table cellpadding="0" cellspacing="0" border="0" width="100%">
					<tr>
						<td width="15">&nbsp;</td>
						<td class="error">
						{if $ad_num}
							{$lang.content.no_ads_by_num}&nbsp;{$ad_num}.&nbsp;<a href="{$file_name}?sel={$par}">{$lang.content.goto_ads_list}</a>
						{else}
							{$lang.content.no_rent_ads}
						{/if}
						</td>
					</tr>
				</table>
				{else}
				<form id="search_form" name="search_form" action="{$file_name}?sel={$par}&amp;par={if $par == 'featured'}search_ad_featured{elseif $par == 'top_search'}search_ad_top{elseif $par == 'slideshow'}search_ad{/if}" method="POST">
				<table cellpadding="0" cellspacing="0" border="0"  style="margin: 0px; margin-top: 10px; margin-bottom: 10px;">
				<tr>
					<td style="padding-left: 15px;">{$lang.content.search_by_num_in_rent}:&nbsp;</td>
					<td>
						<input class="str" type="text" value="" id="ad_num" name="ad_num">
					</td>
					<td>
						&nbsp;&nbsp;<input class="btn_small" type="button" value="{$lang.buttons.search}" onclick="javascript: document.search_form.submit();">
					</td>
				</tr>
				</table>
				</form>
				{foreach from=$ads item=item name=ua}
				{assign var="rows_after_image" value=0}
				<table cellpadding="0" cellspacing="0" width="100%" border="0" style="margin-bottom: 10px;">
					<tr>
						<td width="15" style="padding-left: 15px;" valign="top">{$item.number}.</td>
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
												{if $item.type eq 1}{$lang.content.need_realty}
												{elseif $item.type eq 2}{$lang.content.have_realty}
												{elseif $item.type eq 3}{$lang.content.buy_realty}
												{elseif $item.type eq 4}{$lang.content.sell_realty}
												{/if}
												{$item.realty_type}
												{if $item.movedate}&nbsp;&nbsp;&nbsp;{$lang.content.move_date}&nbsp;-&nbsp;{$item.movedate}{if $item.type eq 2}&nbsp;<a href='{$site_root}/viewprofile.php?id={$item.id}&view=calendar'>{$lang.default_select.view_by_calendar}</a>{/if}{/if}
											</td>
										</tr>										
										<tr>
											<td height="23" valign="top">{if $item.country_name}{$item.country_name}{/if}{if $item.region_name},&nbsp;{$item.region_name}{/if}{if $item.city_name},&nbsp;{$item.city_name}{/if}&nbsp;({if $item.user_type eq 2 && $item.company_name!=''}{$item.company_name}{else}{$item.fname}{/if})</td>
										</tr>
									</table>
								</td>
								<td valign="top" align="left" width="1%">
									<table cellpadding="0" cellspacing="0">
									<tr>
										{if $item.show_topsearch_icon}
										<td><img alt="{$lang.default_select.star_alt} {$item.topsearch_date_begin}" title="{$lang.default_select.star_alt} {$item.topsearch_date_begin}" align="left" src="{$site_root}{$template_root}{$template_images_root}/icon_up.png" hspace="1"></td>
										{/if}
										{if $item.slideshowed}
										<td><img alt="{$lang.default_select.slideshowed_alt}" title="{$lang.default_select.slideshowed_alt}" align="left" src="{$site_root}{$template_root}{$template_images_root}/icon_photoslideshow.png" hspace="1"></td>
										{/if}
										{if $item.featured}
										<td><img alt="{$lang.default_select.featured_alt}" title="{$lang.default_select.featured_alt}" src="{$site_root}{$template_root}{$template_images_root}/icon_leader.png" hspace="1"></td>
										{/if}
										{if $item.issponsor}
										<td><img alt="{$lang.default_select.sponsored_alt}" title="{$lang.default_select.sponsored_alt}" align="left" src="{$site_root}{$template_root}{$template_images_root}/icon_sponsor.png" hspace="1"></td>
										{/if}
										{if $use_sold_leased_status}
											{if $item.sold_leased_status && ($item.type eq '2' || $item.type eq '4')}
											<td><img alt="{if $item.type eq '4'}{$lang.default_select.sold_alt}{else}{$lang.default_select.leased_alt}{/if}" title="{if $item.type eq '4'}{$lang.default_select.sold_alt}{else}{$lang.default_select.leased_alt}{/if}" align="left" src="{$site_root}{$template_root}{$template_images_root}/{if $item.type eq '4'}icon_sold.png{else}icon_leased.png{/if}" hspace="1"></td>
											{/if}
										{/if}
										<td>&nbsp;</td>
									</tr>
									</table>
								</td>
							</tr>										
							<tr>
								<td height="23" valign="top" colspan="2">{if $item.status eq 1}{$lang.content.place_text_1}{$item.place}{$lang.content.place_text_2}{else}{$lang.content.place_text_3}{/if}</td>
							</tr>
							<!--<tr>
								<td height="23" valign="top" colspan="2">{if $item.visit_not_guest ne 0}<a href="{$item.visited_ad_link}">{/if}{$lang.default_select.link_text_4}{if $item.visit_not_guest ne 0}</a>{/if}:&nbsp;{$lang.default_select.link_text_5}&nbsp;{$item.visit_day},&nbsp;{$lang.content.monthly}:&nbsp;{$item.visit_month}</td>
							</tr>-->
							<tr>
								<td height="23" valign="top" colspan="2"><a href="{$item.visited_ad_link}">{$lang.default_select.link_text_4}</a>:&nbsp;{$lang.default_select.link_text_5}&nbsp;{$item.visit_day},&nbsp;{$lang.content.monthly}:&nbsp;{$item.visit_month}</td>
							</tr>
							{if $item.reserve.is_reserved}
							{assign var=rows_after_image value=$rows_after_image+1} 
							<tr>
								<td height="23" valign="top" colspan="2">					
									{$lang.content.empty}&nbsp;{$lang.content.time_begin}&nbsp;<strong>{$item.reserve.reserved_start_period}</strong>&nbsp;{$lang.content.time_end}&nbsp;<strong>{$item.reserve.reserved_end_period}</strong>
									&nbsp;<a href='{$site_root}/viewprofile.php?id={$item.id}&view=calendar'>{$lang.default_select.other_period}</a>
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
								<td colspan="2" valign="top">
								<a href="{$file_name}?sel={if $par == 'featured'}feature{elseif $par == 'top_search'}top_search{elseif $par == 'slideshow'}slideshow{/if}_ad&type=rent&id_ad={$item.id}">{if $par == 'featured'}{$lang.content.rent_feature_link_text}{elseif $par == 'top_search'}{$lang.content.this_link_text}{elseif $par == 'slideshow'}{$lang.content.rent_slideshow_link_text}{/if}</a>
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
				<hr>
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
				{/if}
			</td>
		</tr>
		</table>