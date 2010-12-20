{include file="$gentemplates/site_top.tpl"}
<table cellpadding="0" cellspacing="0" border="0">
<tr valign="top">
	<td class="left" valign="top">
		{include file="$gentemplates/homepage_hotlist.tpl"}
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
		<table cellpadding="0" cellspacing="0" width="100%" border="0">
		<tr>
			<td colspan="2" class="subheader"><b>{$lang.headers.services_paysys}</b></td>
		</tr>
		<tr>
			<td width="12px">&nbsp;</td>
			<td>
				<table cellpadding="5" cellspacing="0" border="0">
				<tr>
					<td>{$lang.content.cost}&nbsp;:&nbsp;</td>
					<td>{$data.chosen_cost}&nbsp;{$data.account_currency}</td>
				</tr>
				{if $data.chosen_period}
				<tr>
					<td>{$lang.content.period}&nbsp;:&nbsp;</td>
					<td>{$data.chosen_period}</td>
				</tr>
				{/if}
				<tr>
					<td>{$lang.content.forpay}&nbsp;:&nbsp;</td>
					<td>{$data.chosen_forpay}&nbsp;{$data.account_currency}&nbsp;{$data.service_type}</td>
				</tr>
				<tr>
					<td colspan=2 align="center">
					<form name="add_pay" id="add_pay" action="{$form.action}" method="POST">
						{$form.hiddens}
					</form>
					</td>
				</tr>
				</table>
				<table cellpadding="2" cellspacing="2">
				<tr>
				{section name=s loop=$paysys}
					<td align=center>
						<input type="button" class="btn_small" value="{$paysys[s].descr}" onclick="javascript:document.add_pay.paysys.value='{$paysys[s].name}'; document.add_pay.submit();">
					</td>
				{/section}
				</tr>
				</table>
			</td>
		</tr>
		</table>
	</td>
</tr>
</table>
{include file="$gentemplates/site_footer.tpl"}