<tr>
	<td style="padding-top: 10px; padding-left: 0px; padding-right: 0px; line-height: 1.7;">
		<table cellpadding="0" cellspacing="0" border="0">

		{section name=u loop=$search_result}
			{if !($smarty.section.u.index mod 4)}
			<tr>
			{/if}
			<td width="25%" valign="top" align="center" style="padding-bottom: 10px;">
			<table cellpadding="0" cellspacing="0" border="0" width="100%">
			<tr>
				<td align="center">
					<table cellpadding="0" cellspacing="0" border="0">
					<tr>
						<td height="{$thumb_height+10}" width="{$thumb_width+10}" valign="middle" align="center" style=" border: 1px solid #cccccc; ">
							<img src="{$site_root}{$search_result[u].image}" alt="{$search_result[u].alt}" style="border: none; cursor: pointer;" onclick="document.location.href='{$search_result[u].viewprofile_link}';">
						</td>
					</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td align="left" valign="top" style="padding-top: 10px; padding-left: 10px;padding-right: 10px;">{if $search_result[u].id_user ne $user[0]}<a href="{$search_result[u].viewprofile_link}">{else}<b>{/if}{$search_result[u].login}{if $search_result[u].id_user ne $user[0]}</a>{else}<b>{/if},&nbsp;
					<strong>
					{if $search_result[u].id_type eq 1}{$lang.content.i_need}
					{elseif $search_result[u].id_type eq 2}{$lang.content.i_have}
					{elseif $search_result[u].id_type eq 3}{$lang.content.i_buy}
					{elseif $search_result[u].id_type eq 4}{$lang.content.i_sell}
					{/if}
					{$search_result[u].realty_type}
					</strong></td>
			</tr>
			{if $search_result[u].country_name || $search_result[u].region_name || $search_result[u].city_name}
			<tr>
				<td align="left" valign="top" style="padding-left: 11px;">{$search_result[u].country_name}{if $search_result[u].region_name}, {$search_result[u].region_name}{/if}{if $search_result[u].city_name}, {$search_result[u].city_name}{/if}{if $search_result[u].subway_name ne ''}, {$lang.default_select.m} {$search_result[u].subway_name}{/if}</td>
			</tr>
			{/if}
			<tr>
				<td align="left" valign="top" style="padding-left: 11px;">
					{if $search_result[u].id_type eq 1 || $search_result[u].id_type eq 2}{$lang.content.month_payment_in_line}{else}{$lang.content.price}{/if}:&nbsp;<strong>
					{if $search_result[u].id_type eq 1 || $search_result[u].id_type eq 3}
						{$lang.content.from}&nbsp;<font class="str_nowrap">{$search_result[u].min_payment_show}</font>&nbsp;{$lang.content.upto}&nbsp;<font class="str_nowrap">{$search_result[u].max_payment_show}</font>
					{else}
						<font class="str_nowrap">{$search_result[u].min_payment_show}</font>
					{/if}</strong></td>
			</tr>
			{*<tr>
				<td align="left" valign="top" style="padding-left: 11px;">{strip}
					{if $search_result[u].movedate}
						{if $search_result[u].id_type eq 1 || $search_result[u].id_type eq 3}
							{$lang.content.move_in_date}
						{else}
							{$lang.content.available_date}
						{/if}
						:&nbsp;<strong>{$search_result[u].movedate}</strong>
					{/if}
					{/strip}
				</td>
			</tr>*}
			{if $search_result[u].headline}
			<tr>
				<td align="left" valign="top" style="padding-left: 11px; overflow: hidden;"><div title="{$search_result[u].headline}" style="overflow: hidden; width:175px;">{$search_result[u].headline}</div></td>
			</tr>
			{/if}	
			{if $file_name != "index.php"}
			<tr>
				<td id="listing_add_to_comparison_{$search_result[u].id_ad}" align="left" valign="top" style="padding-left: 11px;">
				<a href="#" onclick="javascript: AddToComparisonList('{$search_result[u].id_ad}', 'listing_add_to_comparison_{$search_result[u].id_ad}');">{$lang.default_select.add_to_comparison_list}</a>
				</td>
			</tr>
			{/if}
				
			</table>
			</td>
			{if !(($smarty.section.u.index+1) mod 4)}
			</tr>
			<tr>
				<td colspan="4" height="7 px;">
				</td>
			</tr>
			{/if}
		{/section}

		</table>

		<table cellpadding="2" cellspacing="2" border="0" width="100%">
		<tr>
		{if ($area_parametres.area == "index") && ($area_parametres.for_reg == 0) && ($search_result)}
			{if !$mhi_registration}
				<td align="left" style="padding-left: 11px;">
					<table cellpadding="0" cellspacing="0">
					<tr>
						<td><a href="registration.php"><img src="{$site_root}{$template_root}{$template_images_root}/add_listing.png" border="0"></a>&nbsp;</td>
						<td><a href="registration.php">{$lang.content.member_login_2}</a></td>
					</tr>
					</table>
				</td>
			{/if}				
		{/if}
		{if (($area_parametres.show_type != "off") && ($search_result))}
			<td align="right" style="padding-right: 10px;"><a href="quick_search.php?sel=last_ads">{$lang.default_select.more_link_text}</a></td>
		{/if}
		</tr>
		</table>

	</td>
</tr>