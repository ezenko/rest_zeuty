{include file="$admingentemplates/admin_top_popup.tpl"}
<table cellpadding="0" cellspacing="0" border="0" width="100%" style="padding-top:5px;">
<tr><td width="10"></td><td class="header" align="left"><b>{if $balance_field_id == 'all'}{$lang.content.pay_manual_all}{else}{$lang.content.pay_manual}{/if}</b></td></tr>
</table>

<table cellpadding="3" cellspacing="1" border="0" width="360px" class="table_main">
{if $balance_field_id != 'all'}
<tr>
<td width="100"><b>{$lang.content.name}</b></td>
<td>{$details.fname}&nbsp;{$details.sname}</td>
</tr>
<tr>
<td><b>{$lang.content.email}</b></td>
<td>{$details.email}</td>
</tr>
<tr>
<td><b>{$lang.content.balance}</b></td>
<td>{$details.account}&nbsp;{$cur}</td>
</tr>
{else}
<tr>
	<td colspan="2" align="justify" style="text-align:justify;">
	&nbsp;&nbsp;{$lang.content.add_to_all_help}
	</td>
</tr>
<tr>
	<td style="padding-left:0px;" colspan="2" align="left">
	<table cellpadding="0" cellspacing="0">
	<tr>
		<td width="10px;" align="left">
			<input type="checkbox" id="use_alert" value="0" onclick="if (this.checked=='true') this.value=0; else this.value=1;" style="padding:2px;margin:3px;">
		</td>
		<td>
			{$lang.content.use_alert}
		</td>
	</tr>
	</table>
		
	</td>
</tr>
{/if}
<tr>
<td><b>{$lang.content.add_for}</b></td>
<td>
	<table cellpadding="0" cellspacing="0"><tr>
		<td width="50px;">
			<input type="text" class="str" id='pay_amount' value="{if $balance_field_id == 'all'}1.00{else}5.00{/if}" style="width:50px;">
		</td>
		<td align="left" style="padding-left:2px;">
			{$cur}
		</td>
		<td style="padding-left:15px;">
			<input type="button" class="button_2" value="{$lang.content.add_on}" onclick="if (CheckInputFields('pay_amount')) CloseParentWindow('{$server}{$site_root}/admin/admin_users.php?sel=add_money{if $balance_field_id == 'all'}&user_id=0&to_all=1&use_alert='+document.getElementById('use_alert').value+'{else}&user_id={$user_id}{/if}&amount='+document.getElementById('pay_amount').value);">
		</td>
	</tr></table>
</td>
</tr>

</table>

{include file="$admingentemplates/admin_bottom_popup.tpl"}
{literal}
<script>
function CheckInputFields(field_id){	
	
	if (document.getElementById(field_id).value == "" || document.getElementById(field_id).value.search(/^[0-9\.,]{0,5}$/) == -1){
		alert('{/literal}{$lang.content.incorrect_input_field}{literal}');
		return false;
	}	
	return true;
}

function CloseParentWindow(url){	
	window.returnValue = url;
	window.close();
	window.opener.focus();
	{/literal}{if $balance_field_id=='all'}{literal}
	window.opener.LocationTo(url);
	{/literal}{else}{literal}
	window.opener.ChangeAccount({/literal}'{$user_id}'{literal}, {/literal}'{$balance_field_id}'{literal}, url);
	{/literal}{/if}{literal}
		
}
</script>
{/literal}