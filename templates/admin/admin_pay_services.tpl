{include file="$admingentemplates/admin_top.tpl"}
	<table cellpadding="0" cellspacing="0" border="0" width="100%">
	<tr>
		<td class="header" align="left">{$lang.menu.users} <font class="subheader">| {$lang.menu.payments} | {$lang.menu.pay_services}</font></td>
	</tr>
	<tr>
		<td><div class="help_text"><span class="help_title">{$lang.default_select.help}</span>{$lang.content.membership_pay_services_help}</div></td>
	</tr>
	<tr>
		<td>
		{strip}
		{if $section=="sec_group_payment"}<b>{else}<a href="{$file_name}?sel=groups&section=sec_group_payment">{/if}
		{$lang.content.sec_group_payment}
		{if $section=="sec_group_payment"}</b>{else}</a>{/if}
		&nbsp;&nbsp;&nbsp;
		{if $section=="sec_pay_services"}<b>{else}<a href="{$file_name}?sel=list_services&section=sec_pay_services">{/if}
		{$lang.content.sec_pay_services}
		{if $section=="sec_pay_services"}</b>{else}</a>{/if}
		{/strip}
		</td>
	</tr>
	<tr>
		<td>
			{if $section=="sec_group_payment"}
				{include file="$admingentemplates/admin_billing_group_cost_table.tpl"}
			{elseif $section=="sec_pay_services"}
				{include file="$admingentemplates/admin_pay_services_settings.tpl"}
			{/if}
		</td>
	</tr>
	</table>
{include file="$admingentemplates/admin_bottom.tpl"}
