{include file="$admingentemplates/admin_top.tpl"}
	<table cellpadding="0" cellspacing="0" border="0" width="100%">
	<tr>
		<td class="header" align="left">{$lang.menu.users} <font class="subheader">| {$lang.menu.users} | {$lang.menu.users_list} | {$user_login} | {$lang.content.rental_ads}</font></td>
	</tr>
	<tr>
		<td><div class="help_text"><span class="help_title">{$lang.default_select.help}</span>{$lang.content.user_ads_help}</div></td>
	</tr>
	{if $links}
	<tr>
		<td>
			<table cellpadding="5" cellspacing="1" border="0" width="100%">
			<tr>
				<td height="30px" colspan="2">{$lang.content.link_ads}:
					{foreach item=item from=$links}
						<a href="{$item.link}" class="page_link" {if $item.selected} style=" font-weight: bold; text-decoration: none;" {/if}>{$item.name}</a>
					{/foreach}
				</td>
			</tr>
			</table>
		</td>
	</tr>
	{/if}
	<tr>
		<td>
		{section name=a loop=$ads}
				<table cellpadding="7" cellspacing="0" width="100%" class="table_listing">
						<tr>
							<td><b>{$user_login}
							{if $ads[a].type eq '1'}{$lang.content.user_need}
							{elseif $ads[a].type eq '2'}{$lang.content.user_have}
							{elseif $ads[a].type eq '3'}{$lang.content.user_buy}
							{elseif $ads[a].type eq '4'}{$lang.content.user_sell}
							{/if}</b>
							</td>
							<td align="right"><input type="button" class="button_3" value="{$lang.buttons.back}" onclick="document.location.href='{if $redirect}{$back_link}{else}{$file_name}{/if}';">
							<input type="button" class="button_2" value="{$lang.buttons.delete_ad}" onclick="{literal}javascript: if(confirm({/literal}'{$lang.content.del_ad_confirm}'{literal} ) ){ document.location.href={/literal}'{$file_name}?sel=del_user_ad&id_ad={$ads[a].id}&id_user={$id_user}'{literal}}{/literal}">
							</td>
						</tr>
					<table cellpadding="7" cellspacing="0" width="100%" class="table_listing">
						{if $ads[a].headline}
						<tr>
							<td height="15" width="170"><b>{$lang.content.headline}:</b></td>
							<td>{$ads[a].headline}</td>
						</tr>								
						{/if}
					<!-- about_realty -->
						<tr>
							<td colspan="2">{$lang.content.about_realty}</td>
						</tr>
						<tr>
							<td width="170"><b>{$lang.rentals.location}:&nbsp;</b></td>
							<td>{$ads[a].country_name}{if $ads[a].region_name},&nbsp;&nbsp;{$ads[a].region_name}{/if}{if $ads[a].city_name},&nbsp;&nbsp;{$ads[a].city_name}{/if}</td>
						</tr>
						<!--{if $ads[a].subway_name}
						<tr>
							<td><b>{$lang.rentals.subway_name}:&nbsp;</b></td>
							<td>{$ads[a].subway_name}</td>
						</tr>
						{/if}-->
						{if $ads[a].type eq '2' || $ads[a].type eq '4'}
						<!-- have/sell realty-->
							{if $ads[a].zip_code}
							<tr>
								<td><b>{$lang.rentals.zipcode}:&nbsp;</b></td>
								<td>{$ads[a].zip_code}</td>
							</tr>
							{/if}
							{if $ads[a].street_1 || $ads[a].street_2}
							<tr>
								<td><b>{$lang.rentals.cross_streets}:&nbsp;</b></td>
								<td>{$ads[a].street_1}&nbsp;&&nbsp;{$ads[a].street_2}</td>
							</tr>
							{/if}
							{if $ads[a].adress}
							<tr>
								<td><b>{$lang.rentals.adress}:&nbsp;</b></td>
								<td>{$ads[a].adress}</td>
							</tr>
							{/if}
							<tr>
								<td><b>{if $ads[a].type eq 2}{$lang.rentals.month_payment}{else}{$lang.rentals.price}{/if}:&nbsp;</b></td>
								<td>{$ads[a].min_payment_show},&nbsp;{if $ads[a].auction eq '1'}{$lang.rentals.auction_possible}{else}{$lang.rentals.auction_inpossible}{/if}</td>
							</tr>
							{if $ads[a].type eq 2}
								<!-- period -->
								{section name=b loop=$ads[a].period}
									<tr>
										<td><b>{$ads[a].period[b].name}:&nbsp;</b></td>
										<td>{section name=c loop=$ads[a].period[b].fields}{$ads[a].period[b].fields[c]}{if !$smarty.section.c.last},&nbsp;{/if}{/section}
										</td>
									</tr>
								{/section}
								<!-- /period -->
							{/if}
							{if $ads[a].min_deposit > 0}
							<tr>
								<td><b>{$lang.rentals.deposit}:&nbsp;</b></td>
								<td>{$ads[a].min_deposit_show}</td>
							</tr>
							{/if}
							{if $ads[a].movedate}
							<tr>
								<td><b>{$lang.rentals.available_date}:&nbsp;</b></td>
								<td>{$ads[a].movedate}{if $ads[a].type eq 2}&nbsp;<a href='{$site_root}/admin/admin_users.php?sel=user_rent&id_user={$ads[a].id_user}&page={$page}#calendar'>{$lang.content.view_by_calendar}</a>{/if}</td>
							</tr>
							{/if}
							{if $ads[a].reserve.is_reserved}
							<tr>
								<td><b>{$lang.content.empty_period}:&nbsp;</b></td>
								<td>					
									<strong>{$lang.content.time_begin}</strong>&nbsp;{$ads[a].reserve.reserved_start_period}&nbsp;<strong>{$lang.content.time_end}</strong>&nbsp;{$ads[a].reserve.reserved_end_period}</strong>								
								</td>
							</tr>
							{/if}
							<!-- realty type -->
							{section name=b loop=$ads[a].realty_type}
								<tr>
									<td><b>{$ads[a].realty_type[b].name}:&nbsp;</b></td>
									<td>{section name=c loop=$ads[a].realty_type[b].fields}{$ads[a].realty_type[b].fields[c]}{if !$smarty.section.c.last},&nbsp;{/if}{/section}
									</td>
								</tr>
							{/section}
							<!-- /realty type -->
							{if $ads[a].min_year_build > 0}
							<tr>
								<td><b>{$lang.rentals.year_build}:&nbsp;</b></td>
								<td>{$ads[a].min_year_build}</td>
							</tr>
							{/if}
							<!-- description -->
							{section name=b loop=$ads[a].description}
								<tr>
									<td><b>{$ads[a].description[b].name}:&nbsp;</b></td>
									<td>{section name=c loop=$ads[a].description[b].fields}{$ads[a].description[b].fields[c]}{if !$smarty.section.c.last},&nbsp;{/if}{/section}
									</td>
								</tr>
							{/section}
							<!-- /description -->
							{if $ads[a].min_live_square > 0}
							<tr>
								<td><b>{$lang.rentals.live_square}:&nbsp;</b></td>
								<td>{$ads[a].min_live_square}&nbsp;{$sq_meters}</td>
							</tr>
							{/if}
							{if $ads[a].min_total_square > 0}
							<tr>
								<td><b>{$lang.rentals.total_square}:&nbsp;</b></td>
								<td>{$ads[a].min_total_square}&nbsp;{$sq_meters}</td>
							</tr>
							{/if}
							{if $ads[a].min_land_square > 0}
							<tr>
								<td><b>{$lang.rentals.land_square}:&nbsp;</b></td>
								<td>{$ads[a].min_land_square}&nbsp;{$sq_meters}</td>
							</tr>
							{/if}
							{if $ads[a].min_floor}
							<tr>
								<td><b>{$lang.rentals.property_floor_number}:&nbsp;</b></td>
								<td>{$ads[a].min_floor}&nbsp;{$lang.rentals.floor}</td>
							</tr>
							{/if}
							{if $ads[a].floor_num}
							<tr>
								<td><b>{$lang.rentals.total_floor_num}:&nbsp;</b></td>
								<td>{$ads[a].floor_num}&nbsp;{$lang.rentals.of_floors}</td>
							</tr>
							{/if}
							{if $ads[a].subway_min > 0}
							<tr>
								<td><b>{$lang.rentals.subway_min}:&nbsp;</b></td>
								<td>{$ads[a].subway_min}&nbsp;{$lang.rentals.minutes}</td>
							</tr>
							{/if}
							<!-- info -->
							{section name=b loop=$ads[a].info}
								<tr>
									<td><b>{$ads[a].info[b].name}:&nbsp;</b></td>
									<td>{section name=c loop=$ads[a].info[b].fields}{$ads[a].info[b].fields[c]}{if !$smarty.section.c.last},&nbsp;{/if}{/section}
									</td>
								</tr>
							{/section}
							<!-- /info -->
						{else}
							<tr>
								<td><b>{if $ads[a].type eq 1}{$lang.rentals.month_payment}{else}{$lang.rentals.price}{/if}:&nbsp;</b></td>
								<td>{$lang.rentals.from}&nbsp;{$ads[a].min_payment_show}&nbsp;{$lang.rentals.upto}&nbsp;{$ads[a].max_payment_show},&nbsp;{if $ads[a].auction eq '1'}{$lang.rentals.auction_possible}{else}{$lang.rentals.auction_inpossible}{/if}</td>
							</tr>
							{if $ads[a].type eq 2}
								<!-- period -->
								{section name=b loop=$ads[a].period}
									<tr>
										<td><b>{$ads[a].period[b].name}:&nbsp;</b></td>
										<td>{section name=c loop=$ads[a].period[b].fields}{$ads[a].period[b].fields[c]}{if !$smarty.section.c.last},&nbsp;{/if}{/section}
										</td>
									</tr>
								{/section}
								<!-- /period -->
							{/if}
							{if $ads[a].min_deposit > 0 || $ads[a].max_deposit > 0}
							<tr>
								<td><b>{$lang.rentals.deposit}:</b></td>
								<td>{if $ads[a].min_deposit > 0}{$lang.rentals.from}&nbsp;{$ads[a].min_deposit_show}&nbsp;{/if}
									{if $ads[a].max_deposit > 0}{$lang.rentals.upto}&nbsp;{$ads[a].max_deposit_show}{/if}</td>
							</tr>
							{/if}
							{if $ads[a].movedate}
							<tr>
								<td width="200"><b>{$lang.rentals.move_date}:&nbsp;</b></td>
								<td>{$ads[a].movedate}</td>
							</tr>
							{/if}
							<!-- realty type -->
							{section name=b loop=$ads[a].realty_type}
								<tr>
									<td><b>{$ads[a].realty_type[b].name}:&nbsp;</b></td>
									<td>{section name=c loop=$ads[a].realty_type[b].fields}{$ads[a].realty_type[b].fields[c]}{if !$smarty.section.c.last},&nbsp;{/if}{/section}
									</td>
								</tr>
							{/section}
							<!-- /realty type -->
							{if $ads[a].min_year_build > 0 || $ads[a].max_year_build > 0}
							<tr>
								<td><b>{$lang.rentals.year_build}:</b></td>
								<td>{if $ads[a].min_year_build > 0}{$lang.rentals.from_1}&nbsp;{$ads[a].min_year_build}&nbsp;{/if}
									{if $ads[a].max_year_build > 0}{$lang.rentals.upto_1}&nbsp;{$ads[a].max_year_build}&nbsp;{/if}</td>
							</tr>
							{/if}
							<!-- description -->
							{section name=b loop=$ads[a].description}
								<tr>
									<td><b>{$ads[a].description[b].name}:&nbsp;</b></td>
									<td>{section name=c loop=$ads[a].description[b].fields}{$ads[a].description[b].fields[c]}{if !$smarty.section.c.last},&nbsp;{/if}{/section}
									</td>
								</tr>
							{/section}
							<!-- /description -->
							{if $ads[a].min_live_square > 0 || $ads[a].max_live_square > 0}
							<tr>
								<td><b>{$lang.rentals.live_square}:&nbsp;</b></td>
								<td>{if $ads[a].min_live_square > 0}{$lang.rentals.from}&nbsp;{$ads[a].min_live_square}&nbsp;{/if}
									{if $ads[a].max_live_square > 0}{$lang.rentals.upto}&nbsp;{$ads[a].max_live_square}&nbsp;{/if}{$sq_meters}</td>
							</tr>
							{/if}
							{if $ads[a].min_total_square > 0 || $ads[a].max_total_square > 0}
							<tr>
								<td><b>{$lang.rentals.total_square}:&nbsp;</b></td>
								<td>{if $ads[a].min_total_square > 0}{$lang.rentals.from}&nbsp;{$ads[a].min_total_square}&nbsp;{/if}
									{if $ads[a].max_total_square > 0}{$lang.rentals.upto}&nbsp;{$ads[a].max_total_square}&nbsp;{/if}{$sq_meters}</td>
							</tr>
							{/if}
							{if $ads[a].min_land_square > 0 || $ads[a].max_land_square > 0}
							<tr>
								<td><b>{$lang.rentals.land_square}:&nbsp;</b></td>
								<td>{if $ads[a].min_land_square > 0}{$lang.rentals.from}&nbsp;{$ads[a].min_land_square}&nbsp;{/if}
									{if $ads[a].max_land_square > 0}{$lang.rentals.upto}&nbsp;{$ads[a].max_land_square}&nbsp;{/if}{$sq_meters}</td>
							</tr>
							{/if}
							{if $ads[a].min_floor > 0 || $ads[a].max_floor > 0}
							<tr>
								<td><b>{$lang.rentals.floor_variants}:&nbsp;</b></td>
								<td>{if $ads[a].min_floor > 0}{$lang.rentals.from}&nbsp;{$ads[a].min_floor}&nbsp;{/if}
									{if $ads[a].max_floor > 0}{$lang.rentals.upto}&nbsp;{$ads[a].max_floor}&nbsp;{/if}{$lang.rentals.floor}</td>
							</tr>
							{/if}
							{if $ads[a].floor_num}
							<tr>
								<td><b>{$lang.rentals.floor_num_max_limitation}:&nbsp;</b></td>
								<td>{$ads[a].floor_num}&nbsp;{$lang.rentals.of_floors}</td>
							</tr>
							{/if}
							{if $ads[a].subway_min > 0}
							<tr>
								<td><b>{$lang.rentals.subway_min}:&nbsp;</b></td>
								<td>{$ads[a].subway_min}&nbsp;{$lang.rentals.minutes}</td>
							</tr>
							{/if}
							<!-- info -->
							{section name=b loop=$ads[a].info}
								<tr>
									<td><b>{$ads[a].info[b].name}:&nbsp;</b></td>
									<td>{section name=c loop=$ads[a].info[b].fields}{$ads[a].info[b].fields[c]}{if !$smarty.section.c.last},&nbsp;{/if}{/section}
									</td>
								</tr>
							{/section}
							<!-- /info -->
							{if $ads[a].with_photo eq '1'}
							<tr>
								<td><b>{$lang.rentals.photo_exist}:&nbsp;</b></td>
								<td>{$lang.rentals.only_with}</td>
							</tr>
							{/if}
							{if $ads[a].with_video eq '1'}
							<tr>
								<td><b>{$lang.rentals.video_exist}:&nbsp;</b></td>
								<td>{$lang.rentals.only_with}</td>
							</tr>
							{/if}
						{/if}

				{if ($ads[a].type eq 2 || $ads[a].type eq 4)}
					<!--look for-->
					<tr>
						<td colspan="2"><hr class="listing"></td>
					</tr>
					<tr>
						<td colspan="2">{$lang.content.my_preferences} ({if $ads[a].type eq 2}{$lang.rentals.about_leaser}{else if $ads[a].type eq 4}{$lang.rentals.about_buyer}{/if})</td>
					</tr>
					{if $ads[a].his_age_1}
						<tr>
							<td><b>{$lang.rentals.age}:&nbsp;</b></td>
							<td>{$ads[a].his_age_1}{if $ads[a].his_age_2>0}-{$ads[a].his_age_2}{/if}</td>
						</tr>
						{if $ads[a].gender_match}
						{section name=b loop=$ads[a].gender_match}
						<tr>
							<td><b>{$ads[a].gender_match[b].name}:&nbsp;</b></td>
							<td>{section name=c loop=$ads[a].gender_match[b].fields}{$ads[a].gender_match[b].fields[c]}{if !$smarty.section.c.last},&nbsp;{/if}{/section}
							</td>
						</tr>
						{/section}
						{/if}
						{if $ads[a].people_match}
						{section name=b loop=$ads[a].people_match}
						<tr>
							<td><b>{$ads[a].people_match[b].name}:&nbsp;</b></td>
							<td>{section name=c loop=$ads[a].people_match[b].fields}{$ads[a].people_match[b].fields[c]}{if !$smarty.section.c.last},&nbsp;{/if}{/section}
							</td>
						</tr>
						{/section}
						{/if}
						{if $ads[a].language_match}
						{section name=b loop=$ads[a].language_match}
						<tr>
							<td><b>{$ads[a].language_match[b].name}:&nbsp;</b></td>
							<td>{section name=c loop=$ads[a].language_match[b].fields}{$ads[a].language_match[b].fields[c]}{if !$smarty.section.c.last},&nbsp;{/if}{/section}
							</td>
						</tr>
						{/section}
						{/if}
					{else}
						<tr>
							<td>{$lang.rentals.not_filled}</td>
						</tr>
					{/if}
				{/if}

					<!--comment-->
					{if $ads[a].comment}
						<tr>
							<td colspan="2"><hr class="listing"></td>
						</tr>
						<tr>
							<td width="170">{$lang.rentals.add_comments}:&nbsp;</td>
							<td>{$ads[a].comment}</td>
						</tr>
					{/if}

					</table>
					{if $ads[a].type eq 2 || $ads[a].type eq 4}
					<table cellpadding="7" cellspacing="0" width="100%" class="table_listing">
						<tr>
							<td><hr class="listing"></td>
						</tr>
						<tr>
							<td>{$lang.content.general_photo}</td>
						</tr>
						<tr>
							<td>
								{if $ads[a].photo_id}
								<table cellpadding="3" cellspacing="3" class="table_listing">
									{section name=ph loop=$ads[a].photo_id}
									{if $smarty.section.ph.index is div by 5}<tr>{/if}									
									<td width="{$thumb_width+10}" valign="top">
										<table cellpadding="0" cellspacing="0" border="0">
										<tr>
											<td>
												{if $ads[a].photo_view_link[ph]}<a href="#" onclick="javascript: window.open('{$ads[a].photo_view_link[ph]}','photo_view','top=10, left=10,menubar=0, resizable=1, scrollbars=0,status=0,toolbar=0, width={$ads[a].photo_width[ph]+10}, height={$ads[a].photo_height[ph]+70}');return false;">{/if}<img src='{$ads[a].thumb_file[ph]}' style="border-style: solid; border-width: 1px; border-color: #cccccc; padding-top: 3px; padding-bottom: 3px; padding-left: 3px; padding-right: 3px;">{if $ads[a].photo_view_link[ph]}</a>{/if}									
											</td>
										</tr>
										<tr>
											<td>{if $ads[a].photo_user_comment[ph]}{$ads[a].photo_user_comment[ph]}{/if}
											</td>
										</tr>	
										{if $ads[a].photo_admin_approve[ph] != 1}	
										<tr>
											<td><font class="error">
											{if $ads[a].photo_admin_approve[ph] == 0}{$lang.content.admin_approve_not_complete}	
											{elseif $ads[a].photo_admin_approve[ph] == 2}{$lang.content.admin_approve_decline}{/if}
											</font>
											</td>
										</tr>																				
										{/if}
										<tr>
											<td>
											<a href="#" onclick="{literal}javascript: if(confirm({/literal}'{$lang.content.del_upload_confirm}'{literal} ) ){ document.location.href={/literal}'{$ads[a].del_upload_photo_link[ph]}'{literal}}{/literal}">{$lang.buttons.delete}</a>
											</td>
										</tr>	
										</table>												
									</td>									
									{/section}
									{if $smarty.section.ph.index_next is div by 5 || $smarty.section.ph.last}</tr>{/if}
								</table>
								{else}
								<font class="error">{$lang.rentals.no_photos}</font>
								{/if}
							</td>
						</tr>
					</table>
					{/if}
					{if $ads[a].type eq 2 || $ads[a].type eq 4}
					<table cellpadding="7" cellspacing="0" width="100%" class="table_listing">
						<tr>
							<td><hr class="listing"></td>
						</tr>
						<tr>
							<td>{$lang.content.planirovka}</td>
						</tr>
						<tr>
							<td>
								{if $ads[a].plan_photo_id}
								<table cellpadding="3" cellspacing="3" class="table_listing">
									{section name=ph loop=$ads[a].plan_photo_id}
									{if $smarty.section.ph.index is div by 5}<tr>{/if}									
									<td width="{$thumb_width+10}" valign="top">
										<table cellpadding="0" cellspacing="0" border="0">
										<tr>
											<td>
												{if $ads[a].plan_view_link[ph]}<a href="#" onclick="javascript: window.open('{$ads[a].plan_view_link[ph]}','photo_view','top=10, left=10,menubar=0, resizable=1, scrollbars=0,status=0,toolbar=0, width={$ads[a].plan_width[ph]+10}, height={$ads[a].plan_height[ph]+70}');return false;">{/if}<img src='{$ads[a].plan_thumb_file[ph]}' style="border-style: solid; border-width: 1px; border-color: #cccccc; padding-top: 3px; padding-bottom: 3px; padding-left: 3px; padding-right: 3px;">{if $ads[a].plan_view_link[ph]}</a>{/if}									
											</td>
										</tr>
										<tr>
											<td>{if $ads[a].plan_user_comment[ph]}{$ads[a].plan_user_comment[ph]}{/if}
											</td>
										</tr>	
										{if $ads[a].plan_admin_approve[ph] != 1}	
										<tr>
											<td><font class="error">
											{if $ads[a].plan_admin_approve[ph] == 0}{$lang.content.admin_approve_not_complete}	
											{elseif $ads[a].plan_admin_approve[ph] == 2}{$lang.content.admin_approve_decline}{/if}
											</font>
											</td>
										</tr>																				
										{/if}
										<tr>
											<td>
											<a href="#" onclick="{literal}javascript: if(confirm({/literal}'{$lang.content.del_upload_confirm}'{literal} ) ){ document.location.href={/literal}'{$ads[a].del_upload_plan_link[ph]}'{literal}}{/literal}">{$lang.buttons.delete}</a>
											</td>
										</tr>	
										</table>												
									</td>
									{/section}
									{if $smarty.section.ph.index_next is div by 5 || $smarty.section.ph.last}</tr>{/if}
								</table>
								{else}
								<font class="error">{$lang.rentals.no_photos}</font>
								{/if}
							</td>
						</tr>
					</table>
					{/if}
					{if $ads[a].type eq 2 || $ads[a].type eq 4}
					<table cellpadding="7" cellspacing="0" width="100%" class="table_listing">
						<tr>
							<td><hr class="listing"></td>
						</tr>
						<tr>
							<td>{$lang.content.video}</td>
						</tr>
						<tr>
							<td>
								{if $ads[a].video_id}
								<table cellpadding="3" cellspacing="3" class="table_listing">
									{section name=ph loop=$ads[a].video_id}									
									{if $smarty.section.ph.index is div by 5}<tr>{/if}									
									<td width="{$thumb_width+10}" valign="top">
										<table cellpadding="0" cellspacing="0" border="0">
										<tr>
											<td>
										{if $ads[a].video_view_link[ph]}
											<a href="#" onclick="javascript: var top_pos = (window.screen.height - {$ads[a].height[ph]+70})/2; var left_pos = (window.screen.width - {$ads[a].width[ph]})/2; window.open('{$ads[a].video_view_link[ph]}','video_view','top='+top_pos+', left='+left_pos+',menubar=0, resizable=1, scrollbars=0,status=0,toolbar=0, width={$ads[a].width[ph]},height={$ads[a].height[ph]+70}');return false;">
										{/if}										
											<img src='{$ads[a].video_icon[ph]}' style="border-style: solid; border-width: 1px; border-color: #cccccc; padding-top: 3px; padding-bottom: 3px; padding-left: 3px; padding-right: 3px;" onclick="javascript: var top_pos = (window.screen.height - {$ads[a].height[ph]+70})/2; var left_pos = (window.screen.width - {$ads[a].width[ph]})/2; window.open('{$ads[a].video_view_link[ph]}','video_view','top='+top_pos+', left='+left_pos+',menubar=0, resizable=1, scrollbars=0,status=0,toolbar=0, width={$ads[a].width[ph]},height={$ads[a].height[ph]+70}');return false;">									
										{if $ads[a].video_view_link[ph]}</a>{/if}								
											</td>
										</tr>
										<tr>
											<td>{if $ads[a].video_user_comment[ph]}{$ads[a].video_user_comment[ph]}<br>{/if}
											</td>
										</tr>
										{if $ads[a].video_admin_approve[ph] != 1}	
										<tr>
											<td><font class="error">
											{if $ads[a].video_admin_approve[ph] == 0}{$lang.content.admin_approve_not_complete}	
											{elseif $ads[a].video_admin_approve[ph] == 2}{$lang.content.admin_approve_decline}{/if}
											</font>
											</td>
										</tr>																				
										{/if}
										<tr>
											<td>
											<a href="#" onclick="{literal}javascript: if(confirm({/literal}'{$lang.content.del_upload_confirm}'{literal} ) ){ document.location.href={/literal}'{$ads[a].del_upload_video_link[ph]}'{literal}}{/literal}">{$lang.buttons.delete}</a>
											</td>
										</tr>
										
										</table>												
									</td>
									{/section}
									{if $smarty.section.ph.index_next is div by 5 || $smarty.section.ph.last}</tr>{/if}
								</table>
								{else}
								<font class="error">{$lang.rentals.no_video}</font>
								{/if}
							</td>
						</tr>
						<tr>
							<td><hr class="listing"></td>
						</tr>
					</table>
					{/if}
					{if $ads[a].type == '2'}
					<table cellpadding="7" cellspacing="0" width="100%" class="table_listing" id="calendar">						
						<tr>
							<td style="padding-bottom: 0px;">
								<TABLE width="100%" cellpadding="0" cellspacing="0" border="0">
									<tr>
										<td align="left">{$lang.content.calendar_name}</td>
											<td colspan="3" align="right" style="padding-left:3px;padding-bottom: 3px;">
												<div style="background-color:gray; display: inline; padding:1px 7px 1px 8px;">&nbsp;</div>&nbsp;{$lang.content.na_days}
												&nbsp;<div style="background-color:#FFD8D8; display: inline; padding:1px 7px 1px 8px;">&nbsp;</div>&nbsp;{$lang.content.reserve_days}
												&nbsp;<div style="background-color:#CCFF99; display: inline; padding:1px 7px 1px 8px;">&nbsp;</div>&nbsp;{$lang.content.empty_days}</td>
									</tr>	
								</TABLE>
								
							</td>											
						</tr>
						<tr>
							<td>
								<TABLE width="100%" cellpadding="2" cellspacing="0" border="0" align="center">
								<TR>
									<TD align="left" valign="middle" style="padding-left: 5px; padding-bottom:5px;" colspan="3">
									{strip}
										<A href="{$file_name}?sel=user_rent&id_user={$id_user}&page={$page}&start_month={$date.display.prev_mon}&start_year={$date.display.prev_year}#calendar" class="calendar_back_next">
											&larr;&nbsp;{$lang.content.prev_year}
										</A>				
									{/strip}
									&nbsp;
									{strip}
										<A href="{$file_name}?sel=user_rent&id_user={$id_user}&page={$page}&view=calendar&start_month={$date.display.prev_mon_1}&start_year={$date.display.prev_year_1}#calendar" class="calendar_back_next">
											&larr;&nbsp;{$lang.content.prev_month}
										</A>				
									{/strip}
									</TD>	
																	
									<TD align="right" valign="middle" style="padding-right: 5px;" colspan="4">
									{strip}
										<A href="{$file_name}?sel=user_rent&id_user={$id_user}&page={$page}&start_month={$date.display.next_mon_1}&start_year={$date.display.next_year_1}#calendar" class="calendar_back_next">
											&rarr;&nbsp;{$lang.content.next_month}
										</A>				
									{/strip}
									&nbsp;
									{strip}
										<A href="{$file_name}?sel=user_rent&id_user={$id_user}&page={$page}&start_month={$date.display.next_mon}&start_year={$date.display.next_year}#calendar" class="calendar_back_next">
											&rarr;&nbsp;{$lang.content.next_year}
										</A>											
									{/strip}																	
									</TD>
									
								</TR>
								{section name=calendar_row start=7 loop=14 step=6}								
								<TR>			
								<td></td>						
									{foreach from=$date.display.month item=item key=key name=month_header}
										{if $smarty.foreach.month_header.iteration < $smarty.section.calendar_row.index
										 && $smarty.foreach.month_header.iteration > $smarty.section.calendar_row.index-7}
										<TD align="center" valign="middle" width="" >								
										<b class="subheader">{$date.months[$item]}&nbsp;{$date.display.year[$key]}</b>
										</TD>										
										{/if}
									{/foreach}				
									<td></td>															
								</TR>
								
								<TR><!-- Calendar header -->
								<td></td>						
									{foreach from=$date.display.calendar.month item=item key=index_month name=month_data}
									{if $smarty.foreach.month_data.iteration < $smarty.section.calendar_row.index
										 && $smarty.foreach.month_data.iteration > $smarty.section.calendar_row.index-7}
										<td width="" valign="top" style="padding-top:5px" >
											<table align="center" style="background-color: #cccccc" cellpadding="2" cellspacing="1">
											<tr>
											{foreach from=$date.day_of_week item=day_of_week key=key}			
												<TD width="12%" align="center" style="background-color:white;">
													{assign var=cur value=$key+1 }																		
													{$date.day_of_week[$cur]}
													{if $cur == 7}
													{$date.day_of_week[0]}
													{/if}						
												</TD>				
											{/foreach}				
											
											</tr>
											{foreach from=$item item=week}
											<tr>		
												{foreach from=$date.day_of_week item=day_of_week key=key}				
													{assign var=cur value=$key+1 }	
													{if $cur == 7}
														{assign var=cur value=0}	
													{/if}
													<TD width="12%" align="center" style="
														{if $week[$cur].current_day == 'true'}border:1px solid red;padding:1px;{/if}
														{if $week[$cur].reserved_day == 'true'}background-color:#FFD8D8
														{elseif $week[$cur].reserved_day == 'not_available'}background-color:gray
														{else}background-color:#CCFF99{/if};
														{if $week[$cur].reserved_day == 'half_tf'} cursor: pointer; background-image: url('{$half_tf_image}');" onclick="GetHourByDate('{$week[$cur].mday}','{$date.display.month[$index_month]}','{$date.display.year[$index_month]}','id_text_on_date{$index_month}');"
														{elseif $week[$cur].reserved_day == 'half_ft'} cursor: pointer; background-image: url('{$half_ft_image}');" onclick="GetHourByDate('{$week[$cur].mday}','{$date.display.month[$index_month]}','{$date.display.year[$index_month]}','id_text_on_date{$index_month}');"
														{elseif $week[$cur].reserved_day == 'half_tft'} cursor: pointer; background-image: url('{$half_tft_image}');" onclick="GetHourByDate('{$week[$cur].mday}','{$date.display.month[$index_month]}','{$date.display.year[$index_month]}','id_text_on_date{$index_month}');"
														{else}"{/if}
														>							
														{if $week[$cur].wday == $cur && $week[$cur].mday>0}{$week[$cur].mday}{/if}																				
													</TD>				
												{/foreach}							
											</tr>				
											{/foreach}				
											</table>
											<table align="center">
											<tr>					
												<td id="choose_date" align="center"><div id='id_text_on_date{$index_month}' style="display:none;"></div></td>
											</tr>
											</table>
										</td>
									{/if}
									{/foreach}	
									<td></td>													
								</TR><!-- /Calendar header -->
								{/section}
								</TABLE>
							</td>
						</tr>			
					</table>
					{/if}
		{/section}
		</td>
	</tr>
	{if $links}
	<tr>
		<td>
			<table cellpadding="5" cellspacing="1" border="0" width="100%">
			<tr>
				<td height="30px" colspan="2">{$lang.content.link_ads}:
					{foreach item=item from=$links}
						<a href="{$item.link}" class="page_link" {if $item.selected} style=" font-weight: bold; text-decoration: none;" {/if}>{$item.name}</a>
					{/foreach}
				</td>
			</tr>
			</table>
		</td>
	</tr>
	{/if}
	</table>
	<br><input type="button" class="button_3" value="{$lang.buttons.back}" onclick="document.location.href='{$file_name}';">
{literal}
<script>


function get_http(){
    var xmlhttp;	
	try{
		// Opera 8.0+, Firefox, Safari
		xmlhttp = new XMLHttpRequest();
	} catch (e){
		// Internet Explorer Browsers
		try{
			xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
		} catch (e) {
			try{
				xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			} catch (e){
				// Something went wrong
				alert("Your browser broke!");
				return false;
			}
		}
	}
    return xmlhttp;
}

function GetHourByDate(day, mon, year, id_text){
	this.http = get_http();                           	
	url='{/literal}{$file_name}?sel=get_hour_by_date&id_ad={$id_ad}&id_user={$ads[0].id_user}&day={literal}'+day+'{/literal}&month={literal}'+mon+'{/literal}&year={literal}'+year;
	if (mon.length < 2){
		date = year+'-0'+mon;		
	}else{
		date = year+'-'+mon;
	}
	if (day.length < 2){
		date = date+'-0'+day;		
	}else{
		date = date+'-'+day;
	}
    if (this.http) {
        var http = this.http;       
        this.http.open("GET", url, true);                            
        this.http.onreadystatechange = function() {	
            if (http.readyState == 4) {            	            	
                show_text(id_text, date, http.responseText);                    
              }else{                   
              }
        }
        
        this.http.send(null);
    }
    if(!this.http){
          alert('Error XMLHTTP creating!')
    }	
}

function show_text(id_text,date,data){
	document.getElementById(id_text).innerHTML = "<b>"+date+"</b>";	
	document.getElementById(id_text).style.display = 'inline';	
	var arr = data.split('|');
	for(var i in arr){        
       document.getElementById(id_text).innerHTML = document.getElementById(id_text).innerHTML+"<br>"+arr[i];	                
    }
}

</script>
{/literal}	
{include file="$admingentemplates/admin_bottom.tpl"}