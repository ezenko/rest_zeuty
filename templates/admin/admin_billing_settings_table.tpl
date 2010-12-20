{include file="$admingentemplates/admin_top.tpl"}
<table cellpadding="0" cellspacing="0" width="100%"><tr>
	<td valign="top">
	<table cellpadding="0" cellspacing="0" border="0" width="100%">
	<tr>
		<td class="header" align="left">{$lang.menu.users} <font class="subheader">| {$lang.menu.payments} | {$lang.menu.payments_settings}</font></td>
	</tr>
	<tr>
		<td><div class="help_text"><span class="help_title">{$lang.default_select.help}</span>{if $settype!="general"}{$lang.content.paysys_settings_help}{else}{$lang.content.billing_settings_help}{/if}</div></td>
	</tr>
	<tr><td>
		<table border=0 cellpadding="3" cellspacing="1">			
			<form name="money_form" action="{$form.action}" method="post">
			{$form.hiddens}
			<input type="hidden" name="sel" value="saveset">
			<input type="hidden" name="id" value="">
			<input type="hidden" name="count" value={$count}>	
			<input type="hidden"  id="err_count" name="err_count">			
			<tr height="30">
	            <td class="main_header_text" align="left">{$lang.content.settings}:</td>
	            <td>
				<select name=settype onchange="javascript:document.forms.money_form.sel.value='settings'; document.forms.money_form.submit();">
					<option value='general' {if $settype == "general"}selected{/if}>{$lang.content.option_general}</option>
					{section name=mas loop=$paysystems}
					<option value='{$paysystems[mas.index_next].value}' {if $settype == $paysystems[mas.index_next].value}selected{/if}>{$paysystems[mas.index_next].name}</option>
					{/section}
				</select>
				</td>
	        </tr>
		</table>
		<br>
		<table border=0 cellpadding="3" cellspacing="1">

		{if $settype=='general'}
				<tr bgcolor="#ffffff">
                        <td>{$lang.content.currency}:</td>
                        <td align="left">
						<select name="currency" style="width:195">
						{section name=s loop=$currency}
						<option value="{$currency[s].abbr}"{if $currency[s].abbr == $data.site_unit_costunit}selected{/if}>{$currency[s].abbr}</option>
						{/section}
						</select>
						</td>
                        <td><input type="button" class="button_1" value="{$lang.buttons.save}" onclick="javascript: document.money_form.submit();"></td>
                 </tr>
          </table>
          <table cellpadding="3" cellspacing="1">                        
                <tr>
                	<td colspan="2">                
			           <table width="50%" cellpadding="3" cellspacing="1" border="0" class="table_main" style="margin: 0px; margin-top:10px; margin-bottom:10px;">
							<INPUT type="hidden" name="language_id" value="{$current_lang_id}">
							<tr>
								<th align="center" width="20%">{$lang.content.currency_abbr}</th>
								<th align="center" >{$lang.content.currency_name}</th>
								<th align="center" width="5%">{$lang.content.currency_symbol}</th>
								<th align="center" width="20%">{$lang.content.add}</th>
							</tr>
							<tr>
								<td align="center">
								<input type="text" name="currency_abbr">
								</td>
								<td align="center">
								<input type="text" name="currency_name">
								</td>
								<td align="center">
								<input type="text" name="currency_symbol">
								</td>
								<td align="center">
								<input type="button" value="{$lang.buttons.add}" onclick="javascript:document.forms.money_form.sel.value='add_currency'; document.forms.money_form.submit();">
								</td>
							</tr>
					  </table>
					  <table width="50%" cellpadding="3" cellspacing="1" border="0" class="table_main" style="margin: 0px; margin-top:10px; margin-bottom:10px;">
							<INPUT type="hidden" name="language_id" value="{$current_lang_id}">
							<tr>
								<th align="center" width="20%">{$lang.content.currency_abbr}</th>
								<th align="center" >{$lang.content.currency_name}</th>
								<th align="center" width="5%">{$lang.content.currency_symbol}</th>
								<th align="center" width="5%">{$lang.content.currency_symbol_view}</th>
								<th align="center" width="20%">{$lang.content.delete}</th>
							</tr>
							{section name=e loop=$currency}
							<tr>
								<input type="hidden" name="id_m[{$currency[e].num}]" value="{$currency[e].id}">
								<input type="hidden"  id="errors_m[{$currency[e].num}]" name="errors_m[{$currency[e].num}]" value="">
								<td align="center">
								<input type="text" id="abbr_m[{$currency[e].num}]" name="abbr_m[{$currency[e].num}]" value="{$currency[e].abbr}" onblur="check_empty({$currency[e].num})">
								</td>
								<td align="center">
								<input type="text" id="name_m[{$currency[e].num}]" name="name_m[{$currency[e].num}]" value="{$currency[e].name}" onblur="check_empty({$currency[e].num})">
								</td>
								<td align="center">
								<input type="text" id="symbol_m[{$currency[e].num}]" name="symbol_m[{$currency[e].num}]" value="{$currency[e].symbol}" onblur="check_empty({$currency[e].num})">
								</td>
								<td align="center">{$currency[e].symbol_view}</td>
								<td align="center">
								<input type="button" value="{$lang.buttons.delete}" {literal} onclick="javascript: if (confirm ('{/literal}{$lang.content.confirm_delete}{literal}')) {document.forms.money_form.sel.value='remove_currency'; document.forms.money_form.id.value={/literal}{$currency[e].id}{literal}; document.forms.money_form.submit()}" {/literal}>
								</td>
													
							</tr>
							{/section}
					  </table>		   
            		 </td>
                </tr>        
                <tr>
		            <td>
						<input type="button" class="button_1" value="{$lang.buttons.save}" onclick="javascript:  sum_errors({$count});document.forms.money_form.sel.value='save_changes'; document.money_form.submit();">
					</td>
		        </tr>        
		{else}
			{$data.table_options}
			<tr>
	            <td>
					<input type="button" class="button_1" value="{$lang.buttons.save}" onclick="javascript: document.money_form.submit();">
				</td>
	        </tr>        
		{/if}
		</table>   
        </form>	
	</td></tr>
	</table>
	</td>
	</tr>
</table>
{literal}

<script>
function check_empty(num){
	if ((document.getElementById('abbr_m['+num+']').value == '')||(document.getElementById('name_m['+num+']').value == '')||(document.getElementById('symbol_m['+num+']').value == ''))
		document.getElementById('errors_m['+num+']').value = 1; else document.getElementById('errors_m['+num+']').value = 0;
	return;
}

function sum_errors(num){
	summ = 0;
	for (i=0; i<num; i++)
		if (document.getElementById('errors_m['+i+']').value !=0) summ++;	
	document.getElementById('err_count').value=summ;
	return;
}


</script>
{/literal}
{include file="$admingentemplates/admin_bottom.tpl"}