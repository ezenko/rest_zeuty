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
	<td colspan="2" class="subheader"><b>{$lang.headers.sell_lease_payment}</b></td>
</tr>
<tr>
	<td width="15"></td>
	<td>
		<table cellpadding="0" cellspacing="0" width="100%" border="0">
		{if $error}
		<tr>
			<td style="padding: 10px 0px 0px 5px;">
			<div class="error">*&nbsp;{$error}</div>
			</td>
		</tr>
		{/if}
		<tr>
			<td height="27" style="padding-top: 10px;"><b>{$lang.content.sell_lease_payment_cost}:</b></td>
		</tr>
		<tr>
			<td>
				<table cellpadding="5" cellspacing="0" border="0">
				{foreach from=$sell_lease item=b name=sell_lease}
					<tr>
						<td>{$b.ads_number} {$lang.content.ads_number} - {$cur_symbol} {$b.amount}</td>
						<td><a href="#" onclick="javascript: document.location.href='{$file_name}?sel=sell_lease_payment&id={$b.id}';" >{$lang.content.moneyselect}</a>
						</td>
					</tr>
				{/foreach}
				</table>
			</td>
		</tr>
		<tr>
			<td height="27" style="padding-top: 10px;">
			{strip}
			{if $user_sell_lease}
				{$lang.content.sell_lease_user_payment} <b>{$user_sell_lease.ads_number}</b> {$lang.content.ads_number} {$lang.content.about_sell_lease}, {$lang.content.sell_lease_used_amount} {$cur_symbol} <b>{$user_sell_lease.amount}</b>.
				<br>{$lang.content.sell_lease_posted_ads} <b>{$user_sell_lease.used_ads_number}</b> {$lang.content.ads_number} {$lang.content.about_sell_lease}.<br><br>
			{/if}
			{if !$user_sell_lease || ($user_sell_lease.ads_number==$user_sell_lease.used_ads_number)}
				{$lang.content.sell_lease_need_pay}.
			{else}
				{$lang.content.sell_lease_rest_ads} <b>{$user_sell_lease.ads_number-$user_sell_lease.used_ads_number}</b> {$lang.content.ads_number} {$lang.content.about_sell_lease}.<br><br>
				<a href="rentals.php?sel=add_rent">{$lang.default_select.add_rent_text}</a>
			{/if}
			{/strip}
			</td>
		</tr>
	</table>
	</td>
</tr>
</table>
