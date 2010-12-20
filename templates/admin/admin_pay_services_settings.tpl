<table width="100%" cellpadding="0" cellspacing="0" class="form_table" border="0">
	<form name="pay_services" action="{$file_name}" method="POST">
	<input type="hidden" name="sel" value="save">
	<input type="hidden" name="section" value="{$section}">
	<tr>
		<td  style="padding-top: 10px;">
			<table cellpadding="0" cellspacing="0">
				<tr>
					<td><img src="{$site_root}{$template_root}/images/arrow_down.gif"></td>
					<td class="fast_nav_title"><a class="fast_nav_link" href="#" onclick="javascript: ShowHideDiv('fast_menu');">{$lang.default_select.fast_navigation}</a></td>
				</tr>
				<tr>
					<td colspan="2" class="fast_menu_nav" style="padding-bottom: 0px;">
					<ul class="fast_navigation" id="fast_menu" style="display: none;">
						<li><a href="#payment_between_user">{$lang.content.payment_between_user}</a></li>
						<li><a href="#top_search">{$lang.content.top_search}</a></li>
						<li><a href="#slideshow">{$lang.content.slideshow}</a></li>
						<li><a href="#featured_in_region">{$lang.content.featured_in_region}</a></li>
						<li><a href="#listing_completion_bonus">{$lang.content.listing_completion_bonus}</a></li>
						<li><a href="#sell_lease_payment">{$lang.content.sell_lease_payment}</a></li>
					</ul>&nbsp;
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td><div class="section_title" id="payment_between_user" style="padding-top: 0px;">{$lang.content.payment_between_user}:</div><div class="help_text_wide"><span class="help_title">{$lang.default_select.help}</span>{$lang.content.payment_between_user_help}</div></td>
	</tr>
	<tr><td>{$lang.content.commission_percent}: <input type="text" name="commission_percent" value="{$data.commission_percent}"> %{if $err.commission_percent}<div class="field_error">{$lang.content[$err.commission_percent]}</div>{/if}</td>
	<tr>
		<td><div class="section_title" id="top_search" >{$lang.content.top_search}:</div><div class="help_text_wide"><span class="help_title">{$lang.default_select.help}</span>{$lang.content.top_search_help}</div></td>
	</tr>
	<tr><td>{$lang.content.sevice_cost}: <input type="text" name="top_search_cost" value="{$data.top_search_cost}"> {$costunit}{if $err.top_search_cost}<div class="field_error">{$lang.content[$err.top_search_cost]}</div>{/if}</td>
	</tr>
	<tr>
		<td><div class="section_title" id="slideshow">{$lang.content.slideshow}:</div><div class="help_text_wide"><span class="help_title">{$lang.default_select.help}</span>{$lang.content.slideshow_help}</div></td>
	</tr>
	<tr>
		<td>{$lang.content.sevice_cost}: <input type="text" name="slideshow_cost" value="{$data.slideshow_cost}"> {$costunit}{if $err.slideshow_cost}<div class="field_error">{$lang.content[$err.slideshow_cost]}</div>{/if}</td>
	</tr>
	<tr>
		<td>{$lang.content.sevice_period}: <input type="text" name="slideshow_period" value="{$data.slideshow_period}" size="3"> {$lang.content.days}{if $err.slideshow_period}<div class="field_error">{$lang.content[$err.slideshow_period]}</div>{/if}</td>
	</tr>
	<tr>
		<td><div class="section_title" id="featured_in_region">{$lang.content.featured_in_region}:</div><div class="help_text_wide"><span class="help_title">{$lang.default_select.help}</span>{$lang.content.featured_in_region_help} {$costunit}.</div></td>
	</tr>
	<tr>
		<td>{$lang.content.featured_in_region_period}: <input type="text" name="featured_in_region_period" value="{$data.featured_in_region_period}" size="3"> {$lang.content.minutes}{if $err.featured_in_region_period}<div class="field_error">{$lang.content[$err.featured_in_region_period]}</div>{/if}</td>
	</tr>
	<tr>
		<td>{$lang.content.featured_in_region_cost}: <input type="text" name="featured_in_region_cost" value="{$data.featured_in_region_cost}"> {$costunit}{if $err.featured_in_region_cost}<div class="field_error">{$lang.content[$err.featured_in_region_cost]}</div>{/if}</td>
	</tr>
	<tr>
		<td><div class="section_title" id="listing_completion_bonus">{$lang.content.listing_completion_bonus}:</div><div class="help_text_wide"><span class="help_title">{$lang.default_select.help}</span>{$lang.content.lcbonus_help}</td>
	</tr>
	<tr>
		<td>
			<table cellpadding="0" cellspacing="0" border="0">
			<tr>
				<td style="padding: 0px;">{$lang.content.listing_completion_bonus_activate}:</td><td style="padding: 0px;"><input type="checkbox" name="use_listing_completion_bonus" id="use_listing_completion_bonus" {if $data.use_listing_completion_bonus}checked{/if} value="1" onclick="CheckBonusCount('{$bonus_cnt}');"></td>
			</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>{$lang.content.listing_completion_bonus_number}: <input type="text" name="listing_completion_bonus_number" id="listing_completion_bonus_number" value="{$data.listing_completion_bonus_number}" size="3">{if $err.listing_completion_bonus_number}<div class="field_error">{$lang.content[$err.listing_completion_bonus_number]}</div>{/if}</td>
	</tr>
	<tr>
		<td>{$lang.content.bonus_settings_list}:</td>
	</tr>
	<tr>
		<td>
			<table cellpadding="0" cellspacing="0" class="form_table">
			{foreach from=$bonus item=b name=bonus}
				<tr>
					<td>{$b.percent}{$lang.content.percent} - {$b.amount} {$costunit}</td>
					<td style="padding-left: 10px;"><input type="button" value="{$lang.buttons.delete}" onclick="javascript: if (IsDeleteBonus('{$smarty.foreach.bonus.total}')) document.location.href='{$file_name}?sel=delete_bonus&id={$b.id}&section={$section}';"></td>
				</tr>
			{/foreach}
			</table>
		</td>
	</tr>
	<tr>
		<td style="padding-bottom: 10px;">
			<input type="text" name="lc_bonus_percent" id="lc_bonus_percent" value="{$data.lc_bonus_percent}" size="3"> {$lang.content.percent} - <input type="text" name="lc_bonus_amount" id="lc_bonus_amount" value="{$data.lc_bonus_amount}"> {$costunit} <input type="button" value="{$lang.buttons.add}" onclick="javascript: {literal}if (CheckBonus()) {document.forms.pay_services.sel.value='add_bonus'; document.forms.pay_services.submit();}{/literal}">
		</td>
	</tr>
	{if $err.lc_bonus_percent}
	<tr>
		<td><font class="error">{$lang.content[$err.lc_bonus_percent]}</font></td>
	</tr>
	{/if}
	{if $err.lc_bonus_percent_dublicate}
	<tr>
		<td><font class="error">{$lang.content[$err.lc_bonus_percent_dublicate]}</font></td>
	</tr>
	{/if}
	{if $err.lc_bonus_amount}
	<tr>
		<td><font class="error">{$lang.content[$err.lc_bonus_amount]}</font></td>
	</tr>
	{/if}
	<tr>
		<td><div class="section_title" id="sell_lease_payment">{$lang.content.sell_lease_payment}:</div><div class="help_text_wide"><span class="help_title">{$lang.default_select.help}</span>{$lang.content.sell_lease_payment_help}</td>
	</tr>
	<tr>
		<td>
			<table cellpadding="0" cellspacing="0" border="0">
			<tr>
				<td style="padding: 0px;">{$lang.content.sell_lease_payment_activate}:</td><td style="padding: 0px;"><input type="checkbox" name="use_sell_lease_payment" id="use_sell_lease_payment" {if $data.use_sell_lease_payment}checked{/if} value="1" onclick="CheckSellLeaseCount('{$sell_lease_cnt}');"></td>
			</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>{$lang.content.sell_lease_settings_list}:</td>
	</tr>
	<tr>
		<td>
			<table cellpadding="0" cellspacing="0" class="form_table">
			{foreach from=$sell_lease item=b name=sell_lease}
				<tr>
					<td>{$b.ads_number} {$lang.content.ads_number} - {$b.amount} {$costunit}</td>
					<td style="padding-left: 10px;"><input type="button" value="{$lang.buttons.delete}" onclick="javascript: if (IsDeleteSellLease('{$smarty.foreach.sell_lease.total}')) document.location.href='{$file_name}?sel=delete_sell_lease&id={$b.id}&section={$section}';"></td>
				</tr>
			{/foreach}
			</table>
		</td>
	</tr>
	<tr>
		<td style="padding-bottom: 10px;">
			<input type="text" name="sell_lease_ads_number" id="sell_lease_ads_number" value="{$data.sell_lease_ads_number}" size="3"> {$lang.content.number} - <input type="text" name="sell_lease_amount" id="sell_lease_amount" value="{$data.sell_lease_amount}"> {$costunit} <input type="button" value="{$lang.buttons.add}" onclick="javascript: {literal}if (CheckSellLease()) {document.forms.pay_services.sel.value='add_sell_lease'; document.forms.pay_services.submit();}{/literal}">
		</td>
	</tr>
	{if $err.sell_lease_ads_number}
	<tr>
		<td><font class="error">{$lang.content[$err.sell_lease_ads_number]}</font></td>
	</tr>
	{/if}
	{if $err.sell_lease_ads_number_dublicate}
	<tr>
		<td><font class="error">{$lang.content[$err.sell_lease_ads_number_dublicate]}</font></td>
	</tr>
	{/if}
	{if $err.sell_lease_amount}
	<tr>
		<td><font class="error">{$lang.content[$err.sell_lease_amount]}</font></td>
	</tr>
	{/if}
	<tr>
		<td style="padding-top: 10px;">
			<input type="button" value="{$lang.buttons.save}" onclick="javascript: document.forms.pay_services.submit();">
		</td>
	</tr>
	</form>
</table>

{literal}
<script language="javascript">
function CheckBonus() {
	var error_cnt=0;

	var percent = document.getElementById("lc_bonus_percent").value;
	var percent_int = parseInt(percent);
	if (percent == "" || isNaN(percent_int) || percent_int <= 0 || percent_int>100) {
		document.getElementById("lc_bonus_percent").focus();
		alert("{/literal}{$lang.content.invalid_lc_bonus_percent}{literal}");
		error_cnt++;
	}

	var amount = document.getElementById("lc_bonus_amount").value;
	var amount_float = parseFloat(amount);
	if (amount == "" || isNaN(amount_float) || amount_float <= 0) {
		document.getElementById("lc_bonus_amount").focus();
		alert("{/literal}{$lang.content.invalid_lc_bonus_amount}{literal}");
		error_cnt++;
	}

	if (error_cnt > 0) {
		return false;
	} else {
		return true;
	}
}

function IsDeleteBonus(bonus_cnt) {
	if (bonus_cnt==1) {
		if (confirm('{/literal}{$lang.content.delete_last_bonus}{literal}'))
			return true;
		else
			return false;
	}
	return true;
}

function CheckBonusCount(bonus_cnt) {
	if (bonus_cnt == 0) {
		alert("{/literal}{$lang.content.use_lc_bonus_no_bonus}{literal}");
		document.getElementById("use_listing_completion_bonus").checked = false;
		document.getElementById("lc_bonus_percent").focus();
	}
}

function CheckSellLease() {
	var error_cnt=0;

	var number = document.getElementById("sell_lease_ads_number").value;
	var number_int = parseInt(number);
	if (number == "" || isNaN(number_int) || number_int <= 0) {
		document.getElementById("sell_lease_ads_number").focus();
		alert("{/literal}{$lang.content.invalid_sell_lease_ads_number}{literal}");
		error_cnt++;
	}

	var amount = document.getElementById("sell_lease_amount").value;
	var amount_float = parseFloat(amount);
	if (amount == "" || isNaN(amount_float) || amount_float <= 0) {
		document.getElementById("sell_lease_amount").focus();
		alert("{/literal}{$lang.content.invalid_sell_lease_amount}{literal}");
		error_cnt++;
	}

	if (error_cnt > 0) {
		return false;
	} else {
		return true;
	}
}

function IsDeleteSellLease(sell_lease_cnt) {
	if (sell_lease_cnt==1) {
		if (confirm('{/literal}{$lang.content.delete_last_sell_lease}{literal}'))
			return true;
		else
			return false;
	}
	return true;
}

function CheckSellLeaseCount(sell_lease_cnt) {
	if (sell_lease_cnt == 0) {
		alert("{/literal}{$lang.content.use_sell_lease_no_sell_lease}{literal}");
		document.getElementById("use_sell_lease_payment").checked = false;
		document.getElementById("sell_lease_ads_number").focus();
	}
}
</script>
{/literal}