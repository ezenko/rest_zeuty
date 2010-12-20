<table cellpadding="0" cellspacing="0" border="0" width="100%">
<tr>
	<td width="11">&nbsp;</td>
	<td valign="top" style="padding-top: 7px;">
		<table cellpadding="4" cellspacing="0" border="0">
		<tr>
			<td>&nbsp;</td>
			<td class="hint">{$lang.content.monthly}</td>
			<td class="hint">{$lang.content.weekly}</td>
		</tr>
		<tr>
			<td><a href="{$left_links.rent_viewed_my}">{$lang.content.menu_rental_my_1}</a></td>
			<td align="center"><b>{$link_count.visit_my_month}</b></td>
			<td align="center"><b>{$link_count.visit_my_week}</b></td>
		</tr>
		{if !$mhi_addfriend_link}
		<tr>
			<td><a href="{$left_links.rent_hotlisted_me}" >{$lang.content.menu_rental_my_2}</a></td>
			<td align="center"><b>{$link_count.hotlisted_my_month}</b></td>
			<td align="center"><b>{$link_count.hotlisted_my_week}</b></td>
		</tr>
		{/if}
		{if !$mhi_interest_link}
		<tr>
			<td><a href="{$left_links.rent_interested_me}" >{$lang.content.menu_rental_my_3}</a></td>
			<td align="center"><b>{$link_count.interested_my_month}</b></td>
			<td align="center"><b>{$link_count.interested_my_week}</b></td>
		</tr>
		{/if}
		{if !$mhi_blacklist_link}
		<tr>
			<td><a href="{$left_links.rent_blacklisted_me}" >{$lang.default_select.blacklisted_me}</a></td>
			<td align="center"><b>{$link_count.blacklisted_my_month}</b></td>
			<td align="center"><b>{$link_count.blacklisted_my_week}</b></td>
		</tr>
		{/if}
		</table>
	</td>
	<td valign="top" style="padding-top: 7px;">
		<table cellpadding="4" cellspacing="0" border="0">
		<tr>
			<td>&nbsp;</td>
			<td class="hint">{$lang.content.monthly}</td>
			<td class="hint">{$lang.content.weekly}</td>
		</tr>
		<tr>
			<td><a href="{$left_links.rent_viewed_their}">{$lang.content.menu_rental_his_1}</a></td>
			<td align="center"><b>{$link_count.visit_their_month}</b></td>
			<td align="center"><b>{$link_count.visit_their_week}</b></td>
		</tr>
		{if !$mhi_addfriend_link}
		<tr>
			<td><a href="{$left_links.rent_hotlisted_them}" >{$lang.content.menu_rental_his_2}</a></td>
			<td align="center"><b>{$link_count.hotlisted_their_month}</b></td>
			<td align="center"><b>{$link_count.hotlisted_their_week}</b></td>
		</tr>
		{/if}
		{if !$mhi_interest_link}
		<tr>
			<td><a href="{$left_links.rent_interested_them}" >{$lang.content.menu_rental_his_3}</a></td>
			<td align="center"><b>{$link_count.interested_their_month}</b></td>
			<td align="center"><b>{$link_count.interested_their_week}</b></td>
		</tr>
		{/if}
		{if !$mhi_blacklist_link}
		<tr>
			<td><a href="{$left_links.rent_blacklisted_them}" >{$lang.default_select.blacklisted_them}</a></td>
			<td align="center"><b>{$link_count.blacklisted_their_month}</b></td>
			<td align="center"><b>{$link_count.blacklisted_their_week}</b></td>
		</tr>
		{/if}
		</table>
	</td>
</tr>
<tr>
	<td colspan="3">&nbsp;</td>
</tr>
</table>