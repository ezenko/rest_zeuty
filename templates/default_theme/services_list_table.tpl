{include file="$gentemplates/site_top.tpl"}
<table cellpadding="0" cellspacing="0" border="0">
<tr valign="top">
	<td class="left" valign="top">
	{if ( $par eq 'featured_form' )}
		<div style="margin-top: 10px;"></div>
		{include file="$gentemplates/featured_left.tpl"}
	{else}
		{include file="$gentemplates/homepage_hotlist.tpl"}
	{/if}
	</td>
	<td class="delimiter">&nbsp;</td>
	<td class="main">
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
		<tr><td class="header"><b>{$lang.headers.services}</b></td></tr>
		</table>
		{if $par eq 'top_search'}
			{include file="$gentemplates/services_ads_table.tpl"}
		{elseif $par eq 'slideshow'}
			{include file="$gentemplates/services_ads_table.tpl"}
		{elseif $par eq 'featured'}
			{include file="$gentemplates/services_ads_table.tpl"}
		{elseif $par eq 'featured_form'}
			{include file="$gentemplates/services_featured_ad_table.tpl"}
		{elseif $par eq 'group'}
			{include file="$gentemplates/services_group_table.tpl"}
		{elseif $par eq 'sell_lease'}
			{include file="$gentemplates/services_sell_lease_table.tpl"}
		{elseif $par eq 'payment_between_user'}				
			{include file="$gentemplates/services_payment_btw_user.tpl"}		
		{elseif $par eq 'all_banners'}				
			{include file="$gentemplates/services_all_banners.tpl"}
		{elseif $par eq 'add_banner'}				
			{include file="$gentemplates/services_add_banner.tpl"}		
		{elseif $par eq 'cost_banner'}				
			{include file="$gentemplates/services_cost_banners.tpl"}
		{elseif $par eq 'statistics'}				
			{include file="$gentemplates/services_statistics_banners.tpl"}
		{elseif $par eq 'sms_notifications'}				
			{include file="$gentemplates/services_sms_notify_settings.tpl"}

		{else}
		<table cellpadding="0" cellspacing="0" width="100%" border="0">
		<tr><td colspan="2" class="subheader"><b>{$lang.headers.services_list}</b></td></tr>
		<tr>
			<td width="10">&nbsp;</td>
			<td>
				<table cellpadding="5" cellspacing="0" border="0" width="100%">
				{if $error}
				<tr><td class="error">*&nbsp;{$error}</td></tr>
				{/if}
				
				<tr>
					<td style="padding-top:15px;"><a href="{$file_name}?sel=group">{$lang.content.charge_link_text}</a></td>
				</tr>
				<tr>
					<td><a href="{$file_name}?sel=top_search">{$lang.content.top_search_link_text}</a></td>
				</tr>
				<tr>
					<td><a href="{$file_name}?sel=slideshow">{$lang.content.slideshow_link_text}</a></td>
				</tr>
				<tr>
					<td><a href="{$file_name}?sel=featured">{$lang.content.featured_link_text}</a></td>
				</tr>
				{if $use_sell_lease_payment}
				<tr>
					<td><a href="{$file_name}?sel=sell_lease">{$lang.content.sell_lease_payment_link_text}</a></td>
				</tr>
				{/if}
				{if $use_pilot_module_banners}
				<tr>
					<td><a href="{$file_name}?sel=all_banners">{$lang.content.advertising_campaign}</a></td>
				</tr>	
				{/if}
				{if $use_pilot_module_sms_notifications && $sms_settings.use}
				<tr>
					<td><a href="{$file_name}?sel=sms_notifications">{$lang.content.sms_notifications}</a></td>
				</tr>	
				{/if}
				

				</table>
			</td>
		</tr>
		<tr>
			<td colspan="2" height="25">&nbsp;</td>
		</tr>
		<tr>
			<td width="10">&nbsp;</td>
			<td>
				<table cellpadding="5" cellspacing="0" border="0" width="100%">
				<tr>
					<td><a href="{$file_name}?sel=payment_form">{$lang.content.link_text_1}</a></td>
				</tr>
				<tr>
					<td><a href="{$file_name}?sel=payment_between_user">{$lang.content.link_text_3}</a></td>
				</tr>
				<tr>
					<td><a href="{$file_name}?sel=payment_history">{$lang.content.link_text_2}</a></td>
				</tr>
				{if $use_listing_completion_bonus}
				<tr>
					<td style="padding-top:20px;">
						{strip}
						{$lang.default_select.bonus_intro}<br>
						{foreach from=$bonus item=b name=bonus}
						{if $smarty.foreach.bonus.last}{$lang.default_select.bonus_more}&nbsp;{/if}{$b.percent}% - {if $smarty.foreach.bonus.first}{$lang.default_select.bonus_you_recieve}&nbsp;{/if}{$cur_symbol}&nbsp;{$b.amount}<br>
						{/foreach}<br>
						{$lang.default_select.bonus_conclusion}
						{/strip}
					</td>
				</tr>
				{/if}
				</table>
			</td>
		</tr>
				
		</table>
		{/if}
	</td>
</tr>
</table>
{include file="$gentemplates/site_footer.tpl"}