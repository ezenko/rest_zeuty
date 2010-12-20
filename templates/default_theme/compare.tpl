{include file="$gentemplates/print_top.tpl"}
{strip}
{foreach from=$listings item=profile name=profile}
{/foreach}
{assign var=profile_cnt value=$smarty.foreach.profile.total}
{assign var=colspan_val value=$smarty.foreach.profile.total+1}
<table cellpadding="0" cellpadding="0" border="0">
<tr><td height="4" class="main_header_line">&nbsp;</td></tr>
<tr>	
	<td style="padding: 15px 0px 22px 0px;">
	<table class="compare_table" cellpadding="0" cellspacing="0" border="0" width="{$colspan_val*200}">		
		<tr>
			<td class="no_border" colspan="{$colspan_val}"><a href="{$server}{$site_root}/quick_search.php">{$lang.content.back_to_ads_search}</a></td>
		</tr>
		<tr>
			<th class="compare_title"><div class="compare_table_title"><font class="header"><b>{$lang.content.comparison}</b></font></div></th>
			{foreach from=$listings item=profile}
			<th>
			<div class="compare_table_img">
				<a href="{$server}{$site_root}/viewprofile.php?id={$profile.id}"><img src="{$profile.photo_thumb_file[0]}" class='upload_thumb' alt="{$profile.photo_user_comment[0]}"></a>
			{if $profile_cnt >= 2}</div>{/if}
			</th>
			{/foreach}
		</tr>
		<tr>
			<th>&nbsp;</th>
			{foreach from=$listings item=profile}
			<th><span class="blue_link"><a href="{$server}{$site_root}/viewprofile.php?id={$profile.id}" class="blue_link">{$profile.account.fname}</a></span><a href="#" onclick="javascript: document.location.href='{$server}{$site_root}/comparison_list.php?action=delete_ad&id_ad={$profile.id}&par=from_compare';" title="{$lang.default_select.delete_from_comparison}"><img src="{$server}{$site_root}{$template_root}{$template_images_root}/delete.gif" alt="{$lang.default_select.delete_from_comparison}" class="comp_list_icon"></a><br>{$lang.default_select[$profile.type_name]}<br>{$profile.realty_type_in_line}
			{if !$profile.status}<br><font class="error">{$lang.content.ad_is_not_active}</font>{/if}
			</th>
			{/foreach}
		</tr>		
	<!-- about_realty -->
		<tr>
			<td colspan="{$colspan_val}" class="compare_title">{$lang.content.about_realty}</td>
		</tr>
		<tr>
		<tr>
			<td><b>{$lang.content.headline}:</b></td>
			{foreach from=$listings item=profile}
			<td>{if $profile.headline}{$profile.headline}{else}{$lang.default_select.not_set}{/if}</td>
			{/foreach}
		</tr>
		<tr>
			<td><b>{$lang.content.location}:</b></td>
			{foreach from=$listings item=profile}
			<td>{if $profile.country_name}{$profile.country_name}{if $profile.region_name}, {$profile.region_name}{/if}{if $profile.city_name}, {$profile.city_name}{/if}{else}{$lang.default_select.not_set}{/if}</td>
			{/foreach}
		</tr>
		<tr>
			<td><b>{$lang.content.zipcode}:</b></td>
			{foreach from=$listings item=profile}
			<td>{if $profile.zip_code}{$profile.zip_code}{else}{$lang.default_select.not_set}{/if}</td>
			{/foreach}
		</tr>
		<tr>
			<td><b>{$lang.content.cross_streets}:</b></td>
			{foreach from=$listings item=profile}
			<td>{if $profile.street_1 && $profile.street_2}{$profile.street_1}&nbsp;&&nbsp;{$profile.street_2}{else}{$lang.default_select.not_set}{/if}</td>
			{/foreach}
		</tr>
		<tr>
			<td><b>{$lang.content.adress}:</b></td>
			{foreach from=$listings item=profile}
			<td>{if $profile.adress}{$profile.adress}{else}{$lang.default_select.not_set}{/if}</td>
			{/foreach}
		</tr>
		<tr>
			<td><b>{$lang.content.price}/{$lang.content.month_payment}:</b></td>
			{foreach from=$listings item=profile}
			<td>{$profile.min_payment_show}{if $profile.max_payment}&nbsp;{$lang.content.upto}&nbsp;{$profile.max_payment_show}{/if}, {if $profile.auction eq '1'}{$lang.content.auction_possible}{else}{$lang.content.auction_inpossible}{/if}</td>
			{/foreach}
		</tr>
		<!-- period -->
		{foreach from=$ref_names.period item=ref key=key}
			<tr>
				<td><b>{$ref}:</b></td>
				{foreach from=$listings item=profile}
					{assign var=set_val value=0}
					{section name=b loop=$profile.period}

						{if $profile.period[b].id_spr == $key}
						<td>
							{section name=c loop=$profile.period[b].fields}{$profile.period[b].fields[c]}{if !$smarty.section.c.last}, {/if}{/section}
						</td>
						{assign var=set_val value=1}
						{/if}
					{/section}
					{if $set_val == 0}
					<td>{$lang.default_select.not_set}</td>
					{/if}
				{/foreach}
			</tr>
		{/foreach}
		<!-- /period -->
		<tr>
			<td><b>{$lang.content.deposit}:</b></td>
			{foreach from=$listings item=profile}
			<td>{if $profile.min_deposit}{$profile.min_deposit}&nbsp;{$cur_symbol}{if $profile.max_deposit}&nbsp;{$lang.content.upto}&nbsp;{$profile.max_deposit}&nbsp;{$cur_symbol}{/if}{else}{$lang.default_select.not_set}{/if}</td>
			{/foreach}
		</tr>
		<tr>
			<td><b>{$lang.content.available_date}:</b></td>
			{foreach from=$listings item=profile}
			<td>{if $profile.movedate}{$profile.movedate}{else}{$lang.default_select.not_set}{/if}</td>
			{/foreach}
		</tr>
		<tr>
			<td><b>{$lang.content.year_build}:</b></td>
			{foreach from=$listings item=profile}
			<td>
			{if $profile.min_year_build > 0 || $profile.max_year_build > 0}
				{if $profile.min_year_build > 0}{if $profile.max_year_build > 0}{$lang.content.from_1}&nbsp;{/if}{$profile.min_year_build}&nbsp;{/if}
				{if $profile.max_year_build > 0}{$lang.content.upto_1}&nbsp;{$profile.max_year_build}&nbsp;{/if}
			{else}
				{$lang.default_select.not_set}
			{/if}
			</td>
			{/foreach}
		</tr>
		<!-- description -->
		{foreach from=$ref_names.description item=ref key=key}
			<tr>
				<td><b>{$ref}:</b></td>
				{foreach from=$listings item=profile}
					{assign var=set_val value=0}
					{section name=b loop=$profile.description}

						{if $profile.description[b].id_spr == $key}
						<td>
							{section name=c loop=$profile.description[b].fields}{$profile.description[b].fields[c]}{if !$smarty.section.c.last}, {/if}{/section}
						</td>
						{assign var=set_val value=1}
						{/if}
					{/section}
					{if $set_val == 0}
					<td>{$lang.default_select.not_set}</td>
					{/if}
				{/foreach}
			</tr>
		{/foreach}
		<!-- /description -->
		<tr>
			<td><b>{$lang.content.live_square}:</b></td>
			{foreach from=$listings item=profile}
			<td>
				{if $profile.min_live_square > 0 && $profile.max_live_square > 0}{$lang.content.from}&nbsp;{/if}
				{if $profile.min_live_square > 0}{$profile.min_live_square}{/if}
				{if $profile.max_live_square > 0}&nbsp;{$lang.content.upto}&nbsp;{$profile.max_live_square}{/if}
				{if $profile.min_live_square > 0 || $profile.max_live_square > 0}&nbsp;{$sq_meters}{else}{$lang.default_select.not_set}{/if}
			</td>
			{/foreach}
		</tr>
		<tr>
			<td><b>{$lang.content.total_square}:</b></td>
			{foreach from=$listings item=profile}
			<td>
				{if $profile.min_total_square > 0 && $profile.max_total_square > 0}{$lang.content.from}&nbsp;{/if}
				{if $profile.min_total_square > 0}{$profile.min_total_square}{/if}
				{if $profile.max_total_square > 0}&nbsp;{$lang.content.upto}&nbsp;{$profile.max_total_square}{/if}
				{if $profile.min_total_square > 0 || $profile.max_total_square > 0}&nbsp;{$sq_meters}{else}{$lang.default_select.not_set}{/if}
			</td>
			{/foreach}
		</tr>
		<tr>
			<td><b>{$lang.content.land_square}:</b></td>
			{foreach from=$listings item=profile}
			<td>
				{if $profile.min_land_square > 0 && $profile.max_land_square > 0}{$lang.content.from}&nbsp;{/if}
				{if $profile.min_land_square > 0}{$profile.min_land_square}{/if}
				{if $profile.max_land_square > 0}&nbsp;{$lang.content.upto}&nbsp;{$profile.max_land_square}{/if}
				{if $profile.min_land_square > 0 || $profile.max_land_square > 0}&nbsp;{$sq_meters}{else}{$lang.default_select.not_set}{/if}
			</td>
			{/foreach}
		</tr>
		<tr>
			<td><b>{$lang.content.property_floor_number}:</b></td>
			{foreach from=$listings item=profile}
			<td>
				{if $profile.min_floor > 0 && $profile.max_floor > 0}{$lang.content.from}&nbsp;{/if}
				{if $profile.min_floor > 0}{$profile.min_floor}{/if}
				{if $profile.max_floor > 0}&nbsp;{$lang.content.upto}&nbsp;{$profile.max_floor}{/if}
				{if $profile.min_floor > 0 || $profile.max_floor > 0}&nbsp;{$lang.content.floor}{else}{$lang.default_select.not_set}{/if}
			</td>
			{/foreach}
		</tr>
		<tr>
			<td><b>{$lang.content.total_floor_num}:</b></td>
			{foreach from=$listings item=profile}
			<td>{if $profile.floor_num}{$profile.floor_num}&nbsp;{$lang.content.of_floors}{else}{$lang.default_select.not_set}{/if}</td>
			{/foreach}
		</tr>
		<tr>
			<td><b>{$lang.content.subway_min}:</b></td>
			{foreach from=$listings item=profile}
			<td>{if $profile.subway_min > 0}{$profile.subway_min}&nbsp;{$lang.content.minutes}{else}{$lang.default_select.not_set}{/if}</td>
			{/foreach}
		</tr>
		<!-- info -->
		{foreach from=$ref_names.info item=ref key=key}
			<tr>
				<td><b>{$ref}:</b></td>
				{foreach from=$listings item=profile}
					{assign var=set_val value=0}
					{section name=b loop=$profile.info}

						{if $profile.info[b].id_spr == $key}
						<td>
							{section name=c loop=$profile.info[b].fields}{$profile.info[b].fields[c]}{if !$smarty.section.c.last}, {/if}{/section}
						</td>
						{assign var=set_val value=1}
						{/if}
					{/section}
					{if $set_val == 0}
					<td>{$lang.default_select.not_set}</td>
					{/if}
				{/foreach}
			</tr>
		{/foreach}
		<!-- /info -->
		{*will not display this block*}
		{*
			<tr>
				<td><b>{$lang.content.photo_exist}:</b></td>
				{foreach from=$listings item=profile}
				<td>{if $profile.with_photo eq '1'}{$lang.content.only_with}{else}{$lang.default_select.not_set}{/if}</td>
				{/foreach}
			</tr>
			<tr>
				<td><b>{$lang.content.video_exist}:</b></td>
				{foreach from=$listings item=profile}
				<td>{if $profile.with_video eq '1'}{$lang.content.only_with}{else}{$lang.default_select.not_set}{/if}</td>
				{/foreach}
			</tr>
		*}
		<!--comment-->
		<tr>
			<td class="no_border_bottom"><b>{$lang.content.add_comments}:</b></td>
			{foreach from=$listings item=profile}
			<td class="no_border_bottom">{if $profile.comment}{$profile.comment}{else}{$lang.default_select.not_set}{/if}</td>
			{/foreach}
		</tr>

		<!--look for (about buyer/leaser)-->
		<tr>
			<td colspan="{$colspan_val}" class="compare_title">{$lang.content.look_for} ({$lang.content.about_buyer}/{$lang.content.about_leaser})</td>
		</tr>
		<tr>
			<td><b>{$lang.content.age}:</b></td>
			{foreach from=$listings item=profile}
			<td>
				{if $profile.type eq 2 || $profile.type eq 4}
					{if $profile.his_age_1}
						{$profile.his_age_1}{if $profile.his_age_2>0}-{$profile.his_age_2}{/if}
					{else}
						{$lang.default_select.not_set}
					{/if}
				{else}
					{$lang.default_select.no_value}
				{/if}
			</td>
			{/foreach}
		</tr>
		{foreach from=$ref_names.gender_match item=ref key=key}
			<tr>
				<td><b>{$ref}:</b></td>
				{foreach from=$listings item=profile}
					<td>
					{if $profile.type eq 2 || $profile.type eq 4}
							{assign var=set_val value=0}
							{section name=b loop=$profile.gender_match}
								{if $profile.gender_match[b].id_spr == $key}
									{section name=c loop=$profile.gender_match[b].fields}{$profile.gender_match[b].fields[c]}{if !$smarty.section.c.last}, {/if}{/section}
								{assign var=set_val value=1}
								{/if}
							{/section}
							{if $set_val == 0}
								{$lang.default_select.not_set}
							{/if}
					{else}
						{$lang.default_select.no_value}
					{/if}
					</td>
				{/foreach}
			</tr>
		{/foreach}
		{foreach from=$ref_names.people_match item=ref key=key}
			<tr>
				<td><b>{$ref}:</b></td>
				{foreach from=$listings item=profile}
					<td>
					{if $profile.type eq 2 || $profile.type eq 4}
							{assign var=set_val value=0}
							{section name=b loop=$profile.people_match}
								{if $profile.people_match[b].id_spr == $key}
									{section name=c loop=$profile.people_match[b].fields}{$profile.people_match[b].fields[c]}{if !$smarty.section.c.last}, {/if}{/section}
								{assign var=set_val value=1}
								{/if}
							{/section}
							{if $set_val == 0}
								{$lang.default_select.not_set}
							{/if}
					{else}
						{$lang.default_select.no_value}
					{/if}
					</td>
				{/foreach}
			</tr>
		{/foreach}
		{foreach from=$ref_names.language_match item=ref key=key name=language_match}
			<tr>
				<td {if $smarty.foreach.language_match.last} class="no_border_bottom"{/if}><b>{$ref}:</b></td>
				{foreach from=$listings item=profile}
					<td {if $smarty.foreach.language_match.last} class="no_border_bottom"{/if}>
					{if $profile.type eq 2 || $profile.type eq 4}
							{assign var=set_val value=0}
							{section name=b loop=$profile.language_match}
								{if $profile.language_match[b].id_spr == $key}
									{section name=c loop=$profile.language_match[b].fields}{$profile.language_match[b].fields[c]}{if !$smarty.section.c.last}, {/if}{/section}
								{assign var=set_val value=1}
								{/if}
							{/section}
							{if $set_val == 0}
								{$lang.default_select.not_set}
							{/if}
					{else}
						{$lang.default_select.no_value}
					{/if}
					</td>
				{/foreach}
			</tr>
		{/foreach}
		<!--about me-->
		<tr>
			<td colspan="{$colspan_val}" class="compare_title">{$lang.content.account_about_me}/{$lang.content.account_about_us}</td>
		</tr>
		<tr>
			<td><b>{$lang.content.fname}:</b></td>
			{foreach from=$listings item=profile}
			<td>{if $profile.account.fname}{$profile.account.fname}{else}{$lang.default_select.not_set}{/if}</td>
			{/foreach}
		</tr>
		<tr>
			<td><b>{$lang.content.sname}:</b></td>
			{foreach from=$listings item=profile}
			<td>{if $profile.account.sname}{$profile.account.sname}{else}{$lang.default_select.not_set}{/if}</td>
			{/foreach}
		</tr>

		{if (($registered eq 1 && $group_type eq 1) || ($registered eq 1 && $contact_for_free) || $mhi_registration || $contact_for_unreg) }
			<tr>
				<td><b>{$lang.content.phone}:</b></td>
				{foreach from=$listings item=profile}
				<td>{if $profile.account.phone}{$profile.account.phone}{else}{$lang.default_select.not_set}{/if}</td>
				{/foreach}
			</tr>
		{/if}
		<tr>
			<td><b>{$lang.content.user_type}:</b></td>
			{foreach from=$listings item=profile}
			<td>{if $profile.account.user_type eq 1}{$lang.content.private_person}{elseif $profile.account.user_type eq 2}{$lang.content.agency}{elseif $profile.account.user_type eq 3}{$lang.content.agent}{/if}</td>
			{/foreach}
		</tr>
	{* realtor *}
		<tr>
			<td><b>{$lang.content.company_name}:</b></td>
			{foreach from=$listings item=profile}
			<td>{if $profile.account.user_type eq 2}{$profile.account.company_name}{elseif $profile.account.user_type eq 3 && $profile.company_data.company_name}{$profile.company_data.company_name}{else}{$lang.default_select.no_value}{/if}</td>
			{/foreach}
		</tr>
		{if (($registered eq 1 && $group_type eq 1) || ($registered eq 1 && $contact_for_free) || $mhi_registration || $contact_for_unreg)}
			<tr>
				<td><b>{$lang.content.company_url}:</b></td>
				{foreach from=$listings item=profile}
				<td>{if $profile.account.user_type eq 2}
						{if $profile.account.company_url != ""}
							<a href="{$profile.account.company_url}" target="_blank">{$profile.account.company_url}</a>
						{else}{$lang.default_select.not_set}
						{/if}
					{elseif $profile.account.user_type eq 3}
						{if $profile.company_data.company_url != ""}
							<a href="{$profile.company_data.company_url}" target="_blank">{$profile.company_data.company_url}</a>
						{else}{$lang.default_select.not_set}
						{/if}
					{else}
						{$lang.default_select.no_value}
					{/if}
				</td>
				{/foreach}
			</tr>
		{/if}
		<tr>
			<td><b>{$lang.content.our_logo}:</b></td>
			{foreach from=$listings item=profile}
			<td>
			{if $profile.account.user_type eq 2}
				{if $profile.account.logo_path !='' && $profile.account.admin_approve==1}
				<img src="{$profile.account.logo_path}" class='upload_thumb'>
				{else}{$lang.default_select.not_set}{/if}
			{elseif $profile.account.user_type eq 3}
				{if $profile.company_data.logo_path !='' && $profile.company_data.admin_approve==1}
				<img src="{$profile.company_data.logo_path}" class='upload_thumb'>
				{else}{$lang.default_select.not_set}{/if}	
			{else}
				{$lang.default_select.no_value}
			{/if}
			</td>
			{/foreach}
		</tr>
		{if (($registered eq 1 && $group_type eq 1) || ($registered eq 1 && $contact_for_free) || $mhi_registration || $contact_for_unreg)}
			<tr>
				<td><b>{$lang.content.work_days}:</b></td>
				{foreach from=$listings item=profile}
				<td>
				{if $profile.account.user_type eq 2}
					{if $profile.account.weekday}
					{foreach name=week key=key item=item from=$week}
						{if $profile.account.weekday.$key eq $item.id} {$item.name}{/if}
					{/foreach}
					{else}{$lang.default_select.not_set}{/if}
				{elseif $profile.account.user_type eq 3}
					{if $profile.company_data.weekday}
					{foreach name=week key=key item=item from=$week}
						{if $profile.company_data.weekday.$key eq $item.id} {$item.name}{/if}
					{/foreach}
					{else}{$lang.default_select.not_set}{/if}	
				{else}
					{$lang.default_select.no_value}
				{/if}
				</td>
				{/foreach}
			</tr>
			<tr>
				<td><b>{$lang.content.work_time}:</b></td>
				{foreach from=$listings item=profile}
				<td>
				{if $profile.account.user_type eq 2}
					{if $profile.account.work_time_begin > 0 || $profile.account.work_time_end > 0}
						{$lang.content.time_begin}&nbsp;
						{foreach item=item from=$time_arr}
							{if $profile.account.work_time_begin eq $item.value}{$item.value}{/if}
						{/foreach}
						&nbsp;{$lang.content.time_end}&nbsp;
						{foreach item=item from=$time_arr}
							{if $profile.account.work_time_end eq $item.value}{$item.value}{/if}
						{/foreach}
					{else}{$lang.default_select.not_set}{/if}
				{elseif $profile.account.user_type eq 3}
					{if $profile.company_data.work_time_begin > 0 || $profile.company_data.work_time_end > 0}
						{$lang.content.time_begin}&nbsp;
						{foreach item=item from=$time_arr}
							{if $profile.company_data.work_time_begin eq $item.value}{$item.value}{/if}
						{/foreach}
						&nbsp;{$lang.content.time_end}&nbsp;
						{foreach item=item from=$time_arr}
							{if $profile.company_data.work_time_end eq $item.value}{$item.value}{/if}
						{/foreach}
					{else}{$lang.default_select.not_set}{/if}	
				{else}
					{$lang.default_select.no_value}
				{/if}
				</td>
				{/foreach}
			</tr>
			<tr>
				<td><b>{$lang.content.lunch_time}:</b></td>
				{foreach from=$listings item=profile}
				<td>
				{if $profile.account.user_type eq 2}
					{if $profile.account.lunch_time_begin > 0 || $profile.account.lunch_time_end > 0}
						{$lang.content.time_begin}&nbsp;
						{foreach item=item from=$time_arr}
							{if $profile.account.lunch_time_begin eq $item.value}{$item.value}{/if}
						{/foreach}
						&nbsp;{$lang.content.time_end}&nbsp;
						{foreach item=item from=$time_arr}
							{if $profile.account.lunch_time_end eq $item.value}{$item.value}{/if}
						{/foreach}
					{else}{$lang.default_select.not_set}{/if}
				{elseif $profile.account.user_type eq 3}
					{if $profile.company_data.lunch_time_begin > 0 || $profile.company_data.lunch_time_end > 0}
						{$lang.content.time_begin}&nbsp;
						{foreach item=item from=$time_arr}
							{if $profile.company_data.lunch_time_begin eq $item.value}{$item.value}{/if}
						{/foreach}
						&nbsp;{$lang.content.time_end}&nbsp;
						{foreach item=item from=$time_arr}
							{if $profile.company_data.lunch_time_end eq $item.value}{$item.value}{/if}
						{/foreach}
					{else}{$lang.default_select.not_set}{/if}	
				{else}
					{$lang.default_select.no_value}
				{/if}
				</td>
				{/foreach}
			</tr>
		{/if}
	{* private person *}
		<tr>
			<td><b>{$lang.content.birthday}:</b></td>
			{foreach from=$listings item=profile}
			<td>
			{if $profile.account.user_type eq 1}
				{if $profile.account.birth_day != '00'}
					{$profile.account.birth_month}.{$profile.account.birth_day}.{$profile.account.birth_year}
				{else}{$lang.default_select.not_set}{/if}
			{else}
				{$lang.default_select.no_value}
			{/if}
			</td>
			{/foreach}
		</tr>
		{foreach from=$ref_names.gender item=ref key=key}
			<tr>
				<td><b>{$ref}:</b></td>
				{foreach from=$listings item=profile}
				{if $profile.account.user_type eq 1}
						{assign var=set_val value=0}
						{section name=b loop=$profile.gender}

							{if $profile.gender[b].id_spr == $key}
							<td>
								{section name=c loop=$profile.gender[b].fields}{$profile.gender[b].fields[c]}{if !$smarty.section.c.last}, {/if}{/section}
							</td>
							{assign var=set_val value=1}
							{/if}
						{/section}
						{if $set_val == 0}
						<td>{$lang.default_select.not_set}</td>
						{/if}
				{else}
					<td>{$lang.default_select.no_value}</td>
				{/if}
				{/foreach}
			</tr>
		{/foreach}
		{foreach from=$ref_names.people item=ref key=key}
			<tr>
				<td><b>{$ref}:</b></td>
				{foreach from=$listings item=profile}
				{if $profile.account.user_type eq 1}
						{assign var=set_val value=0}
						{section name=b loop=$profile.people}

							{if $profile.people[b].id_spr == $key}
							<td>
								{section name=c loop=$profile.people[b].fields}{$profile.people[b].fields[c]}{if !$smarty.section.c.last}, {/if}{/section}
							</td>
							{assign var=set_val value=1}
							{/if}
						{/section}
						{if $set_val == 0}
						<td>{$lang.default_select.not_set}</td>
						{/if}
				{else}
					<td>{$lang.default_select.no_value}</td>
				{/if}
				{/foreach}
			</tr>
		{/foreach}
		{foreach from=$ref_names.language item=ref key=key}
			<tr>
				<td><b>{$ref}:</b></td>
				{foreach from=$listings item=profile}
				{if $profile.account.user_type eq 1}
						{assign var=set_val value=0}
						{section name=b loop=$profile.language}

							{if $profile.language[b].id_spr == $key}
							<td>
								{section name=c loop=$profile.language[b].fields}{$profile.language[b].fields[c]}{if !$smarty.section.c.last}, {/if}{/section}
							</td>
							{assign var=set_val value=1}
							{/if}
						{/section}
						{if $set_val == 0}
						<td>{$lang.default_select.not_set}</td>
						{/if}
				{else}
					<td>{$lang.default_select.no_value}</td>
				{/if}
				{/foreach}
			</tr>
		{/foreach}
		<!-- /about me -->
	</table>
	<table cellpadding="3" cellspacing="0" border="0" style="margin: 10px 0px 0px 12px;">
		{if $registered eq 1 && $group_type eq 0 && !$contact_for_free && !$contact_for_unreg}
			<tr>
				<td><b>{$lang.content.group_err_no_link}</b></td>
			</tr>
		{/if}
		{if $registered eq 0 && !$mhi_registration && !$contact_for_unreg}
			<tr>
				<td><b>{$lang.content.unreg_group_no_link}</b></td>
			</tr>
		{/if}
		<tr>
			<td><a href="{$server}{$site_root}/quick_search.php">{$lang.content.back_to_ads_search}</a></td>
		</tr>
	</table>
	</td>		
</tr>
<tr><td height="5" class="bottom_footer_line">&nbsp;</td></tr>
</table>	
{/strip}
{assign var=is_compare value=1}
{include file="$gentemplates/print_bottom.tpl"}