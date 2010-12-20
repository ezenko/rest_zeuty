{include file="$gentemplates/print_top.tpl"}
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="print_table">
<tr>
	<td width="15">&nbsp;</td>
	<td height="{$thumb_height+10}" width="{$thumb_width+10}" valign="middle" align="center" style=" border: 1px solid #cccccc; ">
		<img src="{$profile.photo_thumb_file[0]}" style="border: none" alt="{$profile.photo_user_comment[0]}">
	</td>
	<td valign="top" style="padding-left: 7px;">
		<table cellpadding="3" cellspacing="0" border="0" width="100%">
		<tr>
			<td valign="middle"><strong>{$profile.account.fname}</strong>, {$lang.default_select[$profile.type_name]} {$profile.realty_type_in_line}</strong>
			</td>
		</tr>
		{if $profile.country_name_t || $profile.region_name_t || $profile.city_name_t}
		<tr>
			<td>{$profile.country_name_t}{if $profile.region_name_t},&nbsp;{$profile.region_name_t}{/if}{if $profile.city_name_t},&nbsp;{$profile.city_name_t}{/if}</td>
		</tr>
		{/if}
		<tr>
			<td>
				{if $profile.type eq 1 || $profile.type eq 2}{$lang.content.month_payment_inline}
				{else}{$lang.content.price}{/if}:&nbsp;
				<strong>
				{if $profile.type eq 2 || $profile.type eq 4}{$profile.min_payment_show}
				{else}{$profile.min_payment_show}-{$profile.max_payment_show}{/if}
				</strong>{if $profile.movedate},&nbsp;
				{if $profile.type eq 1 || $profile.type eq 3}{$lang.content.move_in_date}
				{else}{$lang.content.available_date}{/if}:&nbsp;<strong>{$profile.movedate}</strong>{/if}
			</td>
		</tr>
		{if $profile.account.phone && (($registered eq 1 && $group_type eq 1) || ($registered eq 1 && $contact_for_free) || $mhi_registration || $contact_for_unreg)}
		<tr>
			<td>{$lang.default_select.call_him}: {$profile.account.phone}</td>
		</tr>
		{elseif ($group_type ne 1 && $registered eq 1)}
		<tr>
			<td><b>{$lang.content.group_err_no_link}</b></td>
		</tr>
		{elseif ($registered eq 0)}
		<tr id="mhi_registration" style="display: {$mhi_registration}">
			<td><b>{$lang.default_select.contact_for_reg}.</b></td>
		</tr>
		{/if}
		</table>
	</td>
</tr>
</table>

<table cellpadding="0" cellspacing="0" width="100%" style="margin: 0px; margin-top: 15px;" class="print_info_table">
	<tr>
		<td valign="top" style="padding: 0px 15px 0px 15px;">
		<table cellpadding="0" cellspacing="0" border="0" width="100%" class="print_profile">					
		<!-- about_realty -->
			<tr>
				<td colspan="2" class="print_title">{$lang.content.about_realty}</td>
			</tr>
			
			{if $profile.headline}			
				<tr>
					<td width="200"><b>{$lang.content.headline}:&nbsp;</b></td>				
					<td>{$profile.headline}</td>
				</tr>
			{/if}
			
			<tr>
				<td width="200"><b>{$lang.content.location}:&nbsp;</b></td>
				<td>{$profile.country_name_t}{if $profile.region_name_t},&nbsp;&nbsp;{$profile.region_name_t}{/if}{if $profile.city_name_t},&nbsp;&nbsp;{$profile.city_name_t}{/if}</td>
			</tr>
			
			{if $profile.type eq '2' || $profile.type eq '4'}
			<!-- have/sell realty-->
				
				{if $profile.zip_code}
				<tr>
					<td><b>{$lang.content.zipcode}:&nbsp;</b></td>
					<td>{$profile.zip_code}</td>
				</tr>
				{/if}
				{if $profile.street_1 && $profile.street_2}
				<tr>
					<td><b>{$lang.content.cross_streets}:&nbsp;</b></td>
					<td>{$profile.street_1}&nbsp;&&nbsp;{$profile.street_2}</td>
				</tr>
				{/if}
				{if $profile.adress}
				<tr>
					<td><b>{$lang.content.adress}:&nbsp;</b></td>
					<td>{$profile.adress}</td>
				</tr>
				{/if}
				<tr>
					<td><b>{if $profile.type eq 2}{$lang.content.month_payment}{else}{$lang.content.price}{/if}:&nbsp;</b></td>
					<td>{$profile.min_payment_show},&nbsp;{if $profile.auction eq '1'}{$lang.content.auction_possible}{else}{$lang.content.auction_inpossible}{/if}</td>
				</tr>
				{if $profile.type eq 2}
					<!-- period -->
					{section name=b loop=$profile.period}
						<tr>
							<td><b>{$profile.period[b].name}:&nbsp;</b></td>
							<td>{section name=c loop=$profile.period[b].fields}{$profile.period[b].fields[c]}{if !$smarty.section.c.last},&nbsp;{/if}{/section}
							</td>
						</tr>
					{/section}
					<!-- /period -->
				{/if}
				{if $profile.min_deposit > 0}
				<tr>
					<td><b>{$lang.content.deposit}:&nbsp;</b></td>
					<td>{$profile.min_deposit_show}</td>
				</tr>
				{/if}
				{if $profile.movedate}
				<tr>
					<td><b>{$lang.content.available_date}:&nbsp;</b></td>
					<td>{$profile.movedate}</td>
				</tr>
				{/if}
				<!-- realty type -->
				{section name=b loop=$profile.realty_type}
					<tr>
						<td><b>{$profile.realty_type[b].name}:&nbsp;</b></td>
						<td>{section name=c loop=$profile.realty_type[b].fields}{$profile.realty_type[b].fields[c]}{if !$smarty.section.c.last},&nbsp;{/if}{/section}
						</td>
					</tr>
				{/section}
				<!-- /realty type -->
				{if $profile.min_year_build > 0}
				<tr>
					<td><b>{$lang.content.year_build}:&nbsp;</b></td>
					<td>{$profile.min_year_build}</td>
				</tr>
				{/if}
				<!-- description -->
				{section name=b loop=$profile.description}
					<tr>
						<td><b>{$profile.description[b].name}:&nbsp;</b></td>
						<td>{section name=c loop=$profile.description[b].fields}{$profile.description[b].fields[c]}{if !$smarty.section.c.last},&nbsp;{/if}{/section}
						</td>
					</tr>
				{/section}
				<!-- /description -->
				{if $profile.min_live_square > 0}
				<tr>
					<td><b>{$lang.content.live_square}:&nbsp;</b></td>
					<td>{$profile.min_live_square}&nbsp;{$sq_meters}</td>
				</tr>
				{/if}
				{if $profile.min_total_square > 0}
				<tr>
					<td><b>{$lang.content.total_square}:&nbsp;</b></td>
					<td>{$profile.min_total_square}&nbsp;{$sq_meters}</td>
				</tr>
				{/if}
				{if $profile.min_land_square > 0}
				<tr>
					<td><b>{$lang.content.land_square}:&nbsp;</b></td>
					<td>{$profile.min_land_square}&nbsp;{$sq_meters}</td>
				</tr>
				{/if}
				{if $profile.min_floor}
				<tr>
					<td><b>{$lang.content.property_floor_number}:&nbsp;</b></td>
					<td>{$profile.min_floor}&nbsp;{$lang.content.floor}</td>
				</tr>
				{/if}
				{if $profile.floor_num}
				<tr>
					<td><b>{$lang.content.total_floor_num}:&nbsp;</b></td>
					<td>{$profile.floor_num}&nbsp;{$lang.content.of_floors}</td>
				</tr>
				{/if}
				{if $profile.subway_min > 0}
				<tr>
					<td><b>{$lang.content.subway_min}:&nbsp;</b></td>
					<td>{$profile.subway_min}&nbsp;{$lang.content.minutes}</td>
				</tr>
				{/if}
				<!-- info -->
				{section name=b loop=$profile.info}
					<tr>
						<td><b>{$profile.info[b].name}:&nbsp;</b></td>
						<td>{section name=c loop=$profile.info[b].fields}{$profile.info[b].fields[c]}{if !$smarty.section.c.last},&nbsp;{/if}{/section}
						</td>
					</tr>
				{/section}
				<!-- /info -->								
			{else}
				<tr>
					<td><b>{if $profile.type eq 1}{$lang.content.month_payment}{else}{$lang.content.price}{/if}:&nbsp;</b></td>
					<td>{$lang.content.from}&nbsp;{$profile.min_payment_show}&nbsp;{$lang.content.upto}&nbsp;{$profile.max_payment_show},&nbsp;{if $profile.auction eq '1'}{$lang.content.auction_possible}{else}{$lang.content.auction_inpossible}{/if}</td>
				</tr>
				{if $profile.type eq 2}
					<!-- period -->
					{section name=b loop=$profile.period}
						<tr>
							<td><b>{$profile.period[b].name}:&nbsp;</b></td>
							<td>{section name=c loop=$profile.period[b].fields}{$profile.period[b].fields[c]}{if !$smarty.section.c.last},&nbsp;{/if}{/section}
							</td>
						</tr>
					{/section}
					<!-- /period -->
				{/if}
				{if $profile.min_deposit > 0 || $profile.max_deposit > 0}
				<tr>
					<td><b>{$lang.content.deposit}:</b></td>
					<td>{if $profile.min_deposit > 0}{$lang.content.from}&nbsp;{$profile.min_deposit_show}&nbsp;{/if}
						{if $profile.max_deposit > 0}{$lang.content.upto}&nbsp;{$profile.max_deposit_show}{/if}</td>
				</tr>
				{/if}
				{if $profile.movedate}
				<tr>
					<td width="200"><b>{$lang.content.move_date}:&nbsp;</b></td>
					<td>{$profile.movedate}</td>
				</tr>
				{/if}
				<!-- realty type -->
				{section name=b loop=$profile.realty_type}
					<tr>
						<td><b>{$profile.realty_type[b].name}:&nbsp;</b></td>
						<td>{section name=c loop=$profile.realty_type[b].fields}{$profile.realty_type[b].fields[c]}{if !$smarty.section.c.last},&nbsp;{/if}{/section}
						</td>
					</tr>
				{/section}
				<!-- /realty type -->
				{if $profile.min_year_build > 0 || $profile.max_year_build > 0}
				<tr>
					<td><b>{$lang.content.year_build}:</b></td>
					<td>{if $profile.min_year_build > 0}{$lang.content.from_1}&nbsp;{$profile.min_year_build}&nbsp;{/if}
						{if $profile.max_year_build > 0}{$lang.content.upto_1}&nbsp;{$profile.max_year_build}&nbsp;{/if}</td>
				</tr>
				{/if}
				<!-- description -->
				{section name=b loop=$profile.description}
					<tr>
						<td><b>{$profile.description[b].name}:&nbsp;</b></td>
						<td>{section name=c loop=$profile.description[b].fields}{$profile.description[b].fields[c]}{if !$smarty.section.c.last},&nbsp;{/if}{/section}
						</td>
					</tr>
				{/section}
				<!-- /description -->
				{if $profile.min_live_square > 0 || $profile.max_live_square > 0}
				<tr>
					<td><b>{$lang.content.live_square}:&nbsp;</b></td>
					<td>{if $profile.min_live_square > 0}{$lang.content.from}&nbsp;{$profile.min_live_square}&nbsp;{/if}
						{if $profile.max_live_square > 0}{$lang.content.upto}&nbsp;{$profile.max_live_square}&nbsp;{/if}{$sq_meters}</td>
				</tr>
				{/if}
				{if $profile.min_total_square > 0 || $profile.max_total_square > 0}
				<tr>
					<td><b>{$lang.content.total_square}:&nbsp;</b></td>
					<td>{if $profile.min_total_square > 0}{$lang.content.from}&nbsp;{$profile.min_total_square}&nbsp;{/if}
						{if $profile.max_total_square > 0}{$lang.content.upto}&nbsp;{$profile.max_total_square}&nbsp;{/if}{$sq_meters}</td>
				</tr>
				{/if}
				{if $profile.min_land_square > 0 || $profile.max_land_square > 0}
				<tr>
					<td><b>{$lang.content.land_square}:&nbsp;</b></td>
					<td>{if $profile.min_land_square > 0}{$lang.content.from}&nbsp;{$profile.min_land_square}&nbsp;{/if}
						{if $profile.max_land_square > 0}{$lang.content.upto}&nbsp;{$profile.max_land_square}&nbsp;{/if}{$sq_meters}</td>
				</tr>
				{/if}
				{if $profile.min_floor > 0 || $profile.max_floor > 0}
				<tr>
					<td><b>{$lang.content.floor_variants}:&nbsp;</b></td>
					<td>{if $profile.min_floor > 0}{$lang.content.from}&nbsp;{$profile.min_floor}&nbsp;{/if}
						{if $profile.max_floor > 0}{$lang.content.upto}&nbsp;{$profile.max_floor}&nbsp;{/if}{$lang.content.floor}</td>
				</tr>
				{/if}
				{if $profile.floor_num}
				<tr>
					<td><b>{$lang.content.floor_num_max_limitation}:&nbsp;</b></td>
					<td>{$profile.floor_num}&nbsp;{$lang.content.of_floors}</td>
				</tr>
				{/if}
				{if $profile.subway_min > 0}
				<tr>
					<td><b>{$lang.content.subway_min}:&nbsp;</b></td>
					<td>{$profile.subway_min}&nbsp;{$lang.content.minutes}</td>
				</tr>
				{/if}
				<!-- info -->
				{section name=b loop=$profile.info}
					<tr>
						<td><b>{$profile.info[b].name}:&nbsp;</b></td>
						<td>{section name=c loop=$profile.info[b].fields}{$profile.info[b].fields[c]}{if !$smarty.section.c.last},&nbsp;{/if}{/section}
						</td>
					</tr>
				{/section}
				<!-- /info -->
				{if $profile.with_photo eq '1'}
				<tr>
					<td><b>{$lang.content.photo_exist}:&nbsp;</b></td>
					<td>{$lang.content.only_with}</td>
				</tr>
				{/if}
				{if $profile.with_video eq '1'}
				<tr>
					<td><b>{$lang.content.video_exist}:&nbsp;</b></td>
					<td>{$lang.content.only_with}</td>
				</tr>
				{/if}								
			{/if}
	<!--comment-->
		{if $profile.comment}
			<tr>
				<td><b>{$lang.content.add_comments}:&nbsp;</b></td>
				<td>{$profile.comment}</td>
			</tr>
		{/if}
	{if ($profile.type eq 2 || $profile.type eq 4)}
		<!--look for-->
		{if $profile.his_age_1}
			<tr>
				<td colspan="2" class="print_title">{$lang.content.look_for} ({if $profile.type eq 2}{$lang.content.about_leaser}{else if $profile.type eq 4}{$lang.content.about_buyer}{/if})</td>
			</tr>
			<tr>
				<td><b>{$lang.content.age}:&nbsp;</b></td>
				<td>{$profile.his_age_1}{if $profile.his_age_2>0}-{$profile.his_age_2}{/if}</td>
			</tr>
			{if $profile.gender_match}
			{section name=b loop=$profile.gender_match}
			<tr>
				<td><b>{$profile.gender_match[b].name}:&nbsp;</b></td>
				<td>{section name=c loop=$profile.gender_match[b].fields}{$profile.gender_match[b].fields[c]}{if !$smarty.section.c.last},&nbsp;{/if}{/section}
				</td>
			</tr>
			{/section}
			{/if}
			{if $profile.people_match}
			{section name=b loop=$profile.people_match}
			<tr>
				<td><b>{$profile.people_match[b].name}:&nbsp;</b></td>
				<td>{section name=c loop=$profile.people_match[b].fields}{$profile.people_match[b].fields[c]}{if !$smarty.section.c.last},&nbsp;{/if}{/section}
				</td>
			</tr>
			{/section}
			{/if}
			{if $profile.language_match}
			{section name=b loop=$profile.language_match}
			<tr>
				<td><b>{$profile.language_match[b].name}:&nbsp;</b></td>
				<td>{section name=c loop=$profile.language_match[b].fields}{$profile.language_match[b].fields[c]}{if !$smarty.section.c.last},&nbsp;{/if}{/section}
				</td>
			</tr>
			{/section}
			{/if}
		{/if}
	{/if}
	<tr>
		<td colspan="2" class="print_title">{$lang.content.photo}</td>
	</tr>
	
	{if !$profile.photo_id && !$profile.plan_id}
	<tr>
		<td>
		</td>
		<td>
		<font class="error">{$lang.content.no_photos}</font>
		</td>
	</tr>	
	{/if}
	
	
	{if $profile.photo_id}
	<tr>
		<td colspan="2" style="padding-bottom: 0px;" >{$lang.content.general_photo}</td>
	</tr>
	<tr>
		<td style="padding: 0px;">
		</td>
		<td style="padding: 0px; padding-top: 7px;">
			
			<table cellpadding="0" cellspacing="0" border="0">
				{section name=ph loop=$profile.photo_id}
				{if $smarty.section.ph.index is div by 3}<tr>{/if}
				<td width="{$thumb_width+10}" valign="top" style="padding: 2px; padding-left:0px;">
					<table cellpadding="0" cellspacing="0" border="0" align="center">
					<tr>
						<td style="padding: 0px;">
							{if $profile.photo_view_link[ph]}<a href="{$profile.photo_file[ph]}" rel="lightbox[profile_photo]" title="{$profile.photo_user_comment[ph]}">{/if}<img src='{$profile.photo_thumb_file[ph]}' class='upload_thumb' alt="{$profile.photo_user_comment[ph]}">{if $profile.photo_view_link[ph]}</a>{/if}									
						</td>
					</tr>					
					<tr>
						<td style="padding: 0px;">{$profile.photo_user_comment[ph]}</td>
					</tr>	
					</table>												
				</td>
				{/section}
				{if $smarty.section.ph.index_next is div by 3 || $smarty.section.ph.last}</tr>{/if}
			</table>			
		</td>
	</tr>
	{/if}
	{if $profile.plan_id && ($profile.type eq 2 || $profile.type eq 4)}
	<tr>
		<td colspan="2" style="padding-bottom: 0px;">{$lang.content.planirovka}</td>
	</tr>
	<tr>
		<td style="padding: 0px;"></td>
		<td style="padding: 0px; padding-top: 7px;">
			<table cellpadding="0" cellspacing="0" border="0" style="padding:0px;">
				{section name=ph loop=$profile.plan_id}
				{if $smarty.section.ph.index is div by 3}<tr>{/if}
				<td width="{$thumb_width+10}" valign="top" style="padding: 2px; padding-left:0px;">
					<table cellpadding="0" cellspacing="0" border="0" align="center">
					<tr>
						<td style="padding:0px;">
							{if $profile.plan_view_link[ph]}<a href="{$profile.plan_file[ph]}" rel="lightbox[profile_plan]" title="{$profile.plan_user_comment[ph]}">{/if}<img src='{$profile.plan_thumb_file[ph]}' class='upload_thumb' alt="{$profile.plan_user_comment[ph]}">{if $profile.plan_view_link[ph]}</a>{/if}								
						</td>
					</tr>
					<tr>
						<td style="padding: 0px;">{$profile.plan_user_comment[ph]}</td>
					</tr>	
					</table>												
				</td>
				{/section}
				{if $smarty.section.ph.index_next is div by 3 || $smarty.section.ph.last}</tr>{/if}
			</table>
		</td>
	</tr>
	{/if}
	<tr>
		<td colspan="2" class="print_title">{$lang.content.video}</td>
	</tr>
					
	<tr>
		<td style="padding: 0px;"></td>
		<td style="padding: 0px; padding-top: 7px;">
			{if $profile.video_id}
			<table cellpadding="0" cellspacing="0" border="0" style="padding: 0px;">
				{assign var=not_flv value=0}			
				{assign var=count value=0}
				{section name=ph loop=$profile.video_id}
				{if $count is div by 3}<tr>{/if}
				{if $profile.video_view_link[ph] && $profile.video_is_flv[ph]}
				{assign var=count value=$count+1}
				<td width="{$thumb_width+10}" valign="top" style="padding: 2px; padding-left:0px;">
					<table cellpadding="0" cellspacing="0" border="0" align="center">
					<tr>
						<td>						
							<a href="#" onclick="javascript:var top_pos = (window.screen.height - {$profile.video_height[ph]+70})/2; var left_pos = (window.screen.width - {$profile.video_width[ph]})/2; window.open('{$profile.video_view_link[ph]}','video_view','top='+top_pos+', left='+left_pos+', menubar=0, resizable=1, scrollbars=0,status=0,toolbar=0, width={$profile.video_width[ph]}, height={$profile.video_height[ph]+70}');return false;" title="{$profile.video_user_comment[ph]}"><img src='{$profile.video_icon[ph]}' class='upload_thumb' alt="{$profile.video_user_comment[ph]}"></a>
						</td>
					</tr>
					<tr>
						<td  style="padding: 0px;">{$profile.video_user_comment[ph]}</td>
					</tr>	
					</table>												
				</td>
				{else}
				{assign var=not_flv value=$not_flv+1}
				{/if}
				{if $count is div by 3 || $smarty.section.ph.last}</tr>{/if}
				{/section}
				{if $not_flv}
				<tr>
					<td style="padding: 2px; padding-left:0px;">
					{$not_flv}&nbsp;{$lang.content.video_without_preview}
					</td>
				</tr>
				{/if}						
			</table>
			{else}
			<font class="error">{$lang.content.no_video}</font>
			{/if}
		</td>
	</tr>
		<!--about me-->
			<tr>
				<td colspan="2" class="print_title">{if $profile.account.user_type eq 1 || $profile.account.user_type eq 3}{$lang.content.account_about_me}{else}{$lang.content.account_about_us}{/if}</td>
			</tr>
			<tr>
				<td><b>{$lang.content.fname}:</b></td>
				<td>{$profile.account.fname}</td>
			</tr>
			<tr>
				<td><b>{$lang.content.sname}:</b></td>
				<td>{$profile.account.sname}</td>
			</tr>
			{if $profile.account.user_type eq 3}
			<tr>
				<td><b>{$lang.content.photo}:</b></td>
				<td>{if $profile.company_data.photo_path !='' && $profile.company_data.photo_admin_approve==1}<img src="{$profile.company_data.photo_path}" style="border-style: solid; border-width: 1px; border-color: #cccccc; padding-top: 3px; padding-bottom: 3px; padding-left: 3px; padding-right: 3px;">{else}<font class="error">{$lang.content.no_photos}</font>{/if}</td>
			</tr>
			{/if}	
		{if (($registered eq 1 && $group_type eq 1) || ($registered eq 1 && $contact_for_free) || $mhi_registration || $contact_for_unreg) }
			{if $profile.account.phone}
			<tr>
				<td><b>{$lang.content.phone}:</b></td>
				<td>{$profile.account.phone}</td>
			</tr>
			{/if}
		{/if}
			{if $profile.account.user_type eq 2}
				{* realtor *}
					<tr>
						<td><b>{$lang.content.company_name}:</b></td>
						<td>{$profile.account.company_name}</td>
					</tr>
				{if (($registered eq 1 && $group_type eq 1) || ($registered eq 1 && $contact_for_free) || $mhi_registration || $contact_for_unreg)}
					{if $profile.account.company_url != ""}
					<tr>
						<td><b>{$lang.content.company_url}:</b></td>
						<td><a href="{$profile.account.company_url}" target="_blank">{$profile.account.company_url}</a></td>
					</tr>
					{/if}
				{/if}
				{if $profile.account.logo_path !='' && $profile.account.admin_approve==1}
					<tr>
						<td><b>{$lang.content.our_logo}:</b></td>
						<td><img src="{$profile.account.logo_path}" style="border-style: solid; border-width: 1px; border-color: #cccccc; padding-top: 3px; padding-bottom: 3px; padding-left: 3px; padding-right: 3px;"></td>
					</tr>
				{/if}
				{if (($registered eq 1 && $group_type eq 1) || ($registered eq 1 && $contact_for_free) || $mhi_registration || $contact_for_unreg)}					
								{if $profile.account.country_name}
								<tr>
									<td><b>{$lang.content.location}:</b></td>
									<td>
										{$profile.account.country_name}{if $profile.account.region_name}, {$profile.account.region_name}{/if}{if $profile.account.city_name}, {$profile.account.city_name}{/if}{if $profile.account.address}, {$profile.account.address}{/if}
									</td>
								</tr>
								{/if}
								{if $profile.account.postal_code}
								<tr>
									<td><b>{$lang.content.zipcode}:</b></td>
									<td>
										{$profile.account.postal_code}
									</td>
								</tr>
								{/if}				
					{if $profile.account.weekday}
					<tr>
						<td><b>{$lang.content.work_days}:</b></td>
						<td>
						{foreach name=week key=key item=item from=$week}
							{if $profile.account.weekday.$key eq $item.id }{$item.name}{/if}
						{/foreach}
						</td>
					</tr>
					{/if}
					{if $profile.account.work_time_begin > 0 || $profile.account.work_time_end > 0}
					<tr>
						<td><b>{$lang.content.work_time}:</b></td>
						<td>{$lang.content.time_begin}&nbsp;
						{foreach item=item from=$time_arr}
							{if $profile.account.work_time_begin eq $item.value}{$item.value}{/if}
						{/foreach}
						&nbsp;{$lang.content.time_end}&nbsp;
						{foreach item=item from=$time_arr}
							{if $profile.account.work_time_end eq $item.value}{$item.value}{/if}
						{/foreach}
						</td>
					</tr>
					{/if}
					{if $profile.account.lunch_time_begin > 0 || $profile.account.lunch_time_end > 0}
					<tr>
						<td><b>{$lang.content.lunch_time}:</b></td>
						<td>{$lang.content.time_begin}&nbsp;
						{foreach item=item from=$time_arr}
							{if $profile.account.lunch_time_begin eq $item.value}{$item.value}{/if}
						{/foreach}
						&nbsp;{$lang.content.time_end}&nbsp;
						{foreach item=item from=$time_arr}
							{if $profile.account.lunch_time_end eq $item.value}{$item.value}{/if}
						{/foreach}
						</td>
					</tr>
					{/if}
				{/if}

			{elseif $profile.account.user_type eq 1}
				{* private person *}
					{if (($registered eq 1 && $group_type eq 1) || ($registered eq 1 && $contact_for_free) || $mhi_registration || $contact_for_unreg) }
					{if $profile.account.birth_day != '00'}
						<tr>
							<td><b>{$lang.content.birthday}:</b></td>
							<td>{$profile.account.birth_month}.{$profile.account.birth_day}.{$profile.account.birth_year}</td>
						</tr>
					{/if}
					{section name=b loop=$profile.gender}
					<tr>
						<td><b>{$profile.gender[b].name}:&nbsp;</b></td>
						<td>{section name=c loop=$profile.gender[b].fields}{$profile.gender[b].fields[c]}{if !$smarty.section.c.last},&nbsp;{/if}{/section}
						</td>
					</tr>
					{/section}
					{section name=b loop=$profile.people}
					<tr>
						<td><b>{$profile.people[b].name}:&nbsp;</b></td>
						<td>{section name=c loop=$profile.people[b].fields}{$profile.people[b].fields[c]}{if !$smarty.section.c.last},&nbsp;{/if}{/section}
						</td>
					</tr>
					{/section}
					{section name=b loop=$profile.language}
					<tr>
						<td><b>{$profile.language[b].name}:&nbsp;</b></td>
						<td>{section name=c loop=$profile.language[b].fields}{$profile.language[b].fields[c]}{if !$smarty.section.c.last},&nbsp;{/if}{/section}
						</td>
					</tr>
					{/section}
				{/if}
			{elseif $profile.account.user_type eq 3}
				{* agent of company *}	
					{if $profile.company_data.company_name}		
					<tr>
						<td colspan="2" class="print_title">{$lang.content.about_company}</td>
					</tr>
					
																		
					<tr>
						<td><b>{$lang.content.company_name}:</b></td>
						<td>{$profile.company_data.company_name}</td>
					</tr>
					{/if}
					{if (($registered eq 1 && $group_type eq 1) || ($registered eq 1 && $contact_for_free) || $mhi_registration || $contact_for_unreg)}
						{if $profile.company_data.company_url != ""}
						<tr>
							<td><b>{$lang.content.company_url}:</b></td>
							<td><a href="{$profile.company_data.company_url}" target="_blank">{$profile.company_data.company_url}</a></td>
						</tr>
						{/if}
					{/if}
					{if $profile.company_data.logo_path !='' && $profile.company_data.admin_approve==1}
						<tr>
							<td><b>{$lang.content.our_logo}:</b></td>
							<td><img src="{$profile.company_data.logo_path}" style="border-style: solid; border-width: 1px; border-color: #cccccc; padding-top: 3px; padding-bottom: 3px; padding-left: 3px; padding-right: 3px;"></td>
						</tr>
					{/if}	
				{if (($registered eq 1 && $group_type eq 1) || ($registered eq 1 && $contact_for_free) || $mhi_registration || $contact_for_unreg)}		
					{if (($profile.company_data.country_name)||($profile.company_data.region_name)||($profile.company_data.city_name)||($profile.company_data.address))}
					<tr>
						<td><b>{$lang.content.location}:</b></td>
						<td>
							{$profile.company_data.country_name}{if $profile.company_data.region_name}, {$profile.company_data.region_name}{/if}{if $profile.company_data.city_name}, {$profile.company_data.city_name}{/if}{if $profile.company_data.address}, {$profile.company_data.address}{/if}
						</td>
					</tr>
					{/if}

					{if $profile.company_data.postal_code}
					<tr>
						<td><b>{$lang.content.zipcode}:</b></td>
						<td>
							{$profile.company_data.postal_code}
						</td>
					</tr>
					{/if}					
					{if $profile.company_data.weekday}
					<tr>
						<td><b>{$lang.content.work_days}:</b></td>
						<td>
						{foreach name=week key=key item=item from=$week}
							{if $profile.company_data.weekday.$key eq $item.id }{$item.name}{/if}
						{/foreach}
						</td>
					</tr>
					{/if}
					{if $profile.company_data.work_time_begin > 0 || $profile.company_data.work_time_end > 0}
					<tr>
						<td><b>{$lang.content.work_time}:</b></td>
						<td>{$lang.content.time_begin}&nbsp;
						{foreach item=item from=$time_arr}
							{if $profile.company_data.work_time_begin eq $item.value}{$item.value}{/if}
						{/foreach}
						&nbsp;{$lang.content.time_end}&nbsp;
						{foreach item=item from=$time_arr}
							{if $profile.company_data.work_time_end eq $item.value}{$item.value}{/if}
						{/foreach}
						</td>
					</tr>
					{/if}
					{if $profile.company_data.lunch_time_begin > 0 || $profile.company_data.lunch_time_end > 0}
					<tr>
						<td><b>{$lang.content.lunch_time}:</b></td>
						<td>{$lang.content.time_begin}&nbsp;
						{foreach item=item from=$time_arr}
							{if $profile.company_data.lunch_time_begin eq $item.value}{$item.value}{/if}
						{/foreach}
						&nbsp;{$lang.content.time_end}&nbsp;
						{foreach item=item from=$time_arr}
							{if $profile.company_data.lunch_time_end eq $item.value}{$item.value}{/if}
						{/foreach}
						</td>
					</tr>
					{/if}
				{/if}	
			{/if}
			{if $registered eq 1 && $group_type eq 0 && !$contact_for_free && !$contact_for_unreg}
				<tr>
					<td colspan="2"><b>{$lang.content.group_err_no_link}</b></td>
				</tr>
			{/if}
			{if $registered eq 0 && !$mhi_registration && !$contact_for_unreg}
				<tr>
					<td colspan="2"><b>{$lang.content.unreg_group_no_link}</b></td>
				</tr>
			{/if}
		<!-- /about me -->
		</table>
		</td>
	</tr>
	<tr><td>&nbsp;</td></tr>
</table>


{include file="$gentemplates/print_bottom.tpl"}