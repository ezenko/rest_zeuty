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
			<tr><td colspan="2" class="subheader"><b>{$lang.headers.services_payment_form}</b></td></tr>
			<tr>
				<td width="15">&nbsp;</td>
				<td>
					<table cellpadding="0" cellspacing="0">
						{if $error}
						<tr>
							<td class="error_div" style="padding-top: 10px;">*&nbsp;{$error}</td>
						</tr>
						{/if}
						<tr>
							<td style="padding-top: 10px; padding-bottom: 10px;">{$lang.content.payment_form_text}</td>
						</tr>
						<tr>
							<td><b>{$lang.content.your_billing}</b>:&nbsp;{$data.user_bill}&nbsp;{$cur}</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td width="15">&nbsp;</td>
				<td>
					<form method="POST" id="pay_form" name="pay_form" action="services.php?sel=add_to_account">
					<table cellpadding="0" cellspacing="0" border="0">
					{if $paysys_cnt == 0}
					<tr>
						<td height="30" width="150">{$lang.content.site_paysys}:</td>
						<td>
							{$lang.content.no_active_paysys}
						</td>
					</tr>
					{elseif $paysys_cnt == 1}
					<tr>
						<td height="30" width="150">{$lang.content.site_paysys}:</td>
						<td>
							<input type="hidden" name="paysys" value="{$paysys.name}">{$paysys.descr}
						</td>
					</tr>
					{else}
					<tr>
						<td height="30" width="200">{$lang.content.choose_paysys}:</td>
						<td>
							<select name="paysys" onclick="javascript: if (this.value != '') document.pay_form.do_payment.disabled=false;  if (this.value == '') document.pay_form.do_payment.disabled=true; CheckShowSmsCoin(this.value);" onchange="ManualPayment(this.value);">
									<option value="">{$lang.content.pls_choose_paysys}
									{section name=s loop=$paysys}

									<option value="{$paysys[s].name}">{$paysys[s].descr}

									{/section}
							</select>
						</td>
					</tr>
					{/if}
					</table>
					<div id="pay_general" {if $paysys_cnt == 1 && $paysys.name=="smscoin"} style="display: none;"{/if}>
					<table cellpadding="0" cellspacing="0" border="0">
					<tr>
						<td height="30" width="200">{$lang.content.pay_sum}&nbsp;{$cur}:&nbsp;
						</td>
						<td>
							<div><input type="text" class="str" name="pay_sum" value="5" style="padding-left:3px;"></div>							
						</td>
					</tr>
					<tr>
						<td>&nbsp;
						</td>
						<td valign="top" style="paddin-top:0px;"><div class="hint">{$lang.content.pay_hint}&nbsp;{$cur}</div>
						</td>
					</tr>
					</table>
					</div>
					<div id="pay_manual" {if $paysys.name!="manual"} style="display: none;"{/if}>
					<table cellpadding="0" cellspacing="0" border="0">
					<tr>
						<td width="200px" valign="top">{$lang.content.payment_information}:
						</td>
						<td width="400px;" style="text-align:justify;">
							{$payment_information}							
						</td>
					</tr>
					<tr>
						<td class="help" colspan="2" width="600px" style="padding-bottom: 5px; padding-top:15px;"><b>{$lang.content.payment_data}</b>
						</td>
					</tr>
					<tr>
						<td>{$lang.content.amount}&nbsp;{$cur}:&nbsp;</td>
						<td width="400px;">
							<input type="text" name="payment_amount" class="str" value="0" style="padding-left:3px;">
						</td>
					</tr>	
					<tr>
						<td>{$lang.content.addition_information}:</td>
						<td width="400px;" style="text-align:justify;padding-top:3px;">
							<textarea name="payment_data" style="width:400px;padding-left:3px;" rows="3"></textarea>							
						</td>
					</tr>					
					</table>
					</div>
					<div id="pay_smscoin" {if !($paysys_cnt == 1 && $paysys.name=="smscoin")} style="display: none;"{/if}>
					{if $smscoin_operators}
						<table cellpadding="0" cellspacing="0" border="0">
						<tr>
							<td height="30" width="200">{$lang.content.smscoin_operator}:
							</td>
							<td>
								{foreach from=$smscoin_operators item=op key=key name='smscoin_operators'}
								{if $smarty.foreach.smscoin_operators.total > 1}
									<input type="radio" name="smscoin_operator" value="{$key}" {if $smarty.foreach.smscoin_operators.first}checked{/if} onclick="ChangeTarif(this.value);">{$op.name}<br>
								{else}
									<input type="hidden" name="smscoin_operator" value="1">
									{$op.name}
								{/if}
								{/foreach}
							</td>
						</tr>
						<tr>
							<td height="30" width="150">{$lang.content.pay_sum}&nbsp;{$cur}:&nbsp;</td>
							<td>
								{foreach from=$smscoin_operators name="sms_tarif" item=op key=key}
								<div id="smscoin_tarif_{$key}" style="display: {if $smarty.foreach.sms_tarif.first}inline{else}none{/if};" {if !$smarty.foreach.sms_tarif.first}disabled{/if}>
									<select name="smscoin_pay_sum[{$key}]">
										{foreach from=$op.tarif item=tarif}
											<option value="{$tarif}">{$tarif}
										{/foreach}
									</select>
								</div>
								{/foreach}
							</td>
						</tr>
						</table>
					{/if}
					</div>
					<table cellpadding="0" cellspacing="0" border="0">
					<tr>
						<td width="200px">&nbsp;</td>
						<td height="30"><input name="do_payment" id="do_payment" type="button" class="btn_small" onclick="javascript: document.pay_form.submit();" value="{$lang.content.do_payment}" {if $paysys_cnt != 1}disabled{/if}></td>
					</tr>
					</table>
					</form>
				</td>
			</tr>
		</table>
	</td>
</tr>
</table>
{literal}
<script language="javascript">
function ManualPayment(value){
	CheckShowSmsCoin(value);	
}
function CheckShowSmsCoin(pay_sys) {
	if (pay_sys == "smscoin") {
		document.getElementById("pay_smscoin").style.display = "inline";
		document.getElementById("pay_general").style.display = "none";
		document.getElementById("pay_manual").style.display = "none";
		document.getElementById("do_payment").value = "{/literal}{$lang.content.do_payment}{literal}";
	} else if (pay_sys == "manual") {
		document.getElementById("pay_smscoin").style.display = "none";
		document.getElementById("pay_general").style.display = "none";
		document.getElementById("pay_manual").style.display = "inline";
		document.getElementById("do_payment").value = "{/literal}{$lang.content.send_to_approve}{literal}";
	} else {
		document.getElementById("pay_smscoin").style.display = "none";
		document.getElementById("pay_general").style.display = "inline";
		document.getElementById("pay_manual").style.display = "none";
		document.getElementById("do_payment").value = "{/literal}{$lang.content.do_payment}{literal}";
	}
}

function ChangeTarif(op_id) {
	var smscoin_operators = new Array();
	var i = 0;
	{/literal}
	{foreach from=$smscoin_operators item=version key=key}
		smscoin_operators[i] = {$key};
		i++;
	{/foreach}
	{literal}

	var smscoin_operators_cnt = smscoin_operators.length;
	var div_name_text = 'smscoin_tarif_';
	for (i=0; i<smscoin_operators_cnt; i++){
		if (smscoin_operators[i] == op_id){
			document.getElementById(div_name_text+smscoin_operators[i]).style.display = "inline";
			document.getElementById(div_name_text+smscoin_operators[i]).disabled = false;
		} else {
			document.getElementById(div_name_text+smscoin_operators[i]).style.display = "none";
			document.getElementById(div_name_text+smscoin_operators[i]).disabled = true;
		}
	}
}
</script>
{/literal}
{include file="$gentemplates/site_footer.tpl"}