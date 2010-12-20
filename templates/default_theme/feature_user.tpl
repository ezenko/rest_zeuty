<table cellpadding="0" cellspacing="0" width="100%" border="0">
	<tr>
		<td width="15">&nbsp;</td>
		<td valign="top" width="{$thumb_width+10}">
			<img src="{$site_root}{$feature_ad.image}" alt="" class="thumb" onclick="document.location.href='{$feature_ad.viewprofile_link}';">
		</td>
		<td valign="top" style="padding-left: 7px;">
			<table cellpadding="3" cellspacing="0" width="100%" border="0">
				<tr>
					<td><b>{$lang.headers.region_leader}</b>,&nbsp;&nbsp;<b><i>&quot;{$feature_ad.headline}&quot;</i></b></td>
				</tr>
				<tr>
					<td><b>{$lang.testimonials_block.featured_period}:</b>&nbsp;
						{if $feature_ad.period.days > 0}{$feature_ad.period.days}&nbsp;{$lang.testimonials_block.days}{/if}
						{if ($feature_ad.period.hours > 0) || ($feature_ad.period.days > 0)}{$feature_ad.period.hours}&nbsp;{$lang.testimonials_block.hours}{/if}
						{if ($feature_ad.period.hours > 0) || ($feature_ad.period.days > 0) || ($feature_ad.period.minutes > 0)}{$feature_ad.period.minutes}&nbsp;{$lang.testimonials_block.minutes}{/if}
						{if ($feature_ad.period.hours > 0) || ($feature_ad.period.days > 0) || ($feature_ad.period.minutes > 0) || ($feature_ad.period.seconds > 0)}{$feature_ad.period.seconds}&nbsp;{$lang.testimonials_block.seconds}{/if}
					</td>
				</tr>
				<tr valign="top">
					<td valign="top"><a href="{$feature_ad.viewprofile_link}">{$feature_ad.login}</a>,&nbsp;
					<strong>{if $feature_ad.id_type eq 1}{$lang.content.i_need}
					{elseif $feature_ad.id_type eq 2}{$lang.content.i_have}
					{elseif $feature_ad.id_type eq 3}{$lang.content.i_buy}
					{elseif $feature_ad.id_type eq 4}{$lang.content.i_sell}
					{/if}{$feature_ad.realty_type}</strong></td>
				</tr>
				{if $feature_ad.country_name || $feature_ad.region_name || $feature_ad.city_name}
				<tr>
					<td>{$feature_ad.country_name}{if $feature_ad.region_name},&nbsp;{$feature_ad.region_name}{/if}{if $feature_ad.city_name},&nbsp;{$feature_ad.city_name}{/if}</td>
				</tr>
				{/if}
				<tr>
					<td>{if $feature_ad.id_type eq 1 || $feature_ad.id_type eq 2}{$lang.content.month_payment_in_line}{else}{$lang.content.price}{/if}:&nbsp;<strong>
					{if $feature_ad.id_type eq 1 || $feature_ad.id_type eq 3}
						{$lang.content.from}&nbsp;{$feature_ad.min_payment_show}&nbsp;{$lang.content.upto}&nbsp;{$feature_ad.max_payment_show}
					{else}
						{$feature_ad.min_payment_show}
					{/if}</strong>,&nbsp;{if $feature_ad.auction eq '1'}{$lang.content.auction_possible}{else}{$lang.content.auction_inpossible}{/if}
					{*
					,&nbsp;{if $feature_ad.id_type eq 1 || $feature_ad.id_type eq 3}{$lang.content.move_in_date}{elseif $feature_ad.id_type eq 2 || $feature_ad.id_type eq 4}{$lang.content.available_date}{/if}:&nbsp;<strong>{$feature_ad.movedate}</strong>{if $feature_ad.id_type eq 2}&nbsp;<a href='{$site_root}/viewprofile.php?id={$feature_ad.id_ad}&view=calendar'>{$lang.default_select.view_by_calendar}</a>{/if}
					*}
					</td>
				</tr>
				{*{if $feature_ad.reserve.is_reserved}
					<tr>
						<td>					
							{$lang.content.empty}&nbsp;{$lang.content.time_begin}&nbsp;<strong>{$feature_ad.reserve.reserved_start_period}</strong>&nbsp;{$lang.content.time_end}&nbsp;<strong>{$feature_ad.reserve.reserved_end_period}</strong>
							&nbsp;<a href='{$site_root}/viewprofile.php?id={$feature_ad.id_ad}&view=calendar'>{$lang.default_select.other_period}</a>
						</td>
					</tr>
				{/if}
				{if $feature_ad.phone && $registered eq 1 && $group_type eq 1}
				<tr>
					<td>{$lang.default_select.call_him}: {$feature_ad.phone}</td>
				</tr>
				{elseif ($group_type ne 1 && $registered eq 1)}
				<tr>
					<td>{$lang.default_select.group_err_1}<a href="services.php?sel=group">{$lang.default_select.group_err_2}</a>{$lang.default_select.group_err_3}</td>
				</tr>
				{elseif ($registered eq 0)}
				<tr>
					<td>{$lang.default_select.contact_for_reg}.&nbsp;<a href="registration.php">{$lang.content.reg_now_text}</a></td>
				</tr>
				{/if}
				*}
				{if $feature_ad.headline2}
				<tr>
					<td>{$feature_ad.headline2}</td>
				</tr>
				{/if}
				<tr>
					<td id="listing_add_to_comparison_{$feature_ad.id_ad}">
					<a href="#" onclick="javascript: AddToComparisonList('{$feature_ad.id_ad}', 'listing_add_to_comparison_{$feature_ad.id_ad}');">{$lang.default_select.add_to_comparison_list}</a>
					</td>
				</tr>
			</table>
		</td>
		<td align="right" valign="top">
		<table cellpadding="0" cellspacing="0" border="0">
			<tr>
				<td width="10" align="right"><img alt="{$lang.default_select.featured_alt}" title="{$lang.default_select.featured_alt}" src="{$site_root}{$template_root}{$template_images_root}/icon_leader.png" hspace="1"></td>
				{if $feature_ad.show_topsearch_icon}<td align="right" width="10"><img alt="{$lang.default_select.star_alt} {$feature_ad.topsearch_date_begin}" title="{$lang.default_select.star_alt} {$feature_ad.topsearch_date_begin}" src="{$site_root}{$template_root}{$template_images_root}/icon_up.png" hspace="1"></td>{/if}
				{if $feature_ad.slideshowed}
					<td><img alt="{$lang.default_select.slideshowed_alt}" title="{$lang.default_select.slideshowed_alt}" align="left" src="{$site_root}{$template_root}{$template_images_root}/icon_photoslideshow.png" hspace="1"></td>
				{/if}
				
				{if $feature_ad.issponsor}
				<td><img alt="{$lang.default_select.sponsored_alt}" title="{$lang.default_select.sponsored_alt}" align="left" src="{$site_root}{$template_root}{$template_images_root}/icon_sponsor.png" hspace="1"></td>
				{/if}
				{if $use_sold_leased_status}
					{if $feature_ad.sold_leased_status && ($feature_ad.id_type eq '2' || $feature_ad.id_type eq '4')}
					<td><img alt="{if $feature_ad.id_type eq '4'}{$lang.default_select.sold_alt}{else}{$lang.default_select.leased_alt}{/if}" title="{if $feature_ad.id_type eq '4'}{$lang.default_select.sold_alt}{else}{$lang.default_select.leased_alt}{/if}" align="left" src="{$site_root}{$template_root}{$template_images_root}/{if $feature_ad.id_type eq '4'}icon_sold.png{else}icon_leased.png{/if}" hspace="1"></td>
					{/if}
				{/if}
				<td>&nbsp;</td>
			</tr>
		</table>
		</td>
	</tr>
	<tr>
		<td colspan="5"><hr width="100%"></td>
	</tr>
</table>