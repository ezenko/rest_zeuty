{include file="$admingentemplates/admin_top_popup.tpl"}
<table cellpadding="0" cellspacing="0" border="0" width="100%" style="padding-top:5px;">
<tr><td width="10"></td><td class="header" align="left"><b>{$lang.content.payment_information}</b></td></tr>
</table>

<table cellpadding="3" cellspacing="1" border="0" width="360px" class="table_main">
<tr>
<td width="100"><b>{$lang.content.u_name}</b></td>
<td>{$details.fname}&nbsp;{$details.sname}</td>
</tr>
<tr>
<td><b>{$lang.content.u_email}</b></td>
<td>{$details.email}</td>
</tr>
<tr>
<td><b>{$lang.content.count_curr}</b></td>
<td>{$details.count_curr}</td>
</tr>
<tr>
<td><b>{$lang.content.currency}</b></td>
<td>{$details.currency}</td>
</tr>
<tr>
<td><b>{$lang.content.date_sended}</b></td>
<td>{$details.date_send_show}</td>
</tr>
<tr>
<td><b>{$lang.content.pay_status}</b></td>
<td>{$details.status}</td>
</tr>
<tr>
<td valign="top" style="vertical-align:top;"><b>{$lang.content.user_info}</b></td>
<td>{$details.user_info}</td>
</tr>	
{if $details.status eq 'send'}
<tr>
<td width="100"><b>{$lang.content.pay_approve}</b></td>
<td><input type="button" class="button_2" value="{$lang.buttons.approve}" onclick="CloseParentWindow('{$server}{$site_root}/admin/admin_payment.php?sel=approve_req&amp;id_order={$details.id}_{$details.id_user}');">&nbsp;<input type="button" class="button_2" value="{$lang.buttons.decline}" onclick="CloseParentWindow('{$server}{$site_root}/admin/admin_payment.php?sel=decline_req&amp;id_order={$details.id}_{$details.id_user}');"></td>
</tr>
{/if}

</table>

{include file="$admingentemplates/admin_bottom_popup.tpl"}
{literal}
<script>
function CloseParentWindow(url){	
	window.returnValue = url;
	window.close();
	window.opener.focus();
	window.opener.ChangeStatus({/literal}'{$status_id}'{literal}, {/literal}'{$approve_field_id}'{literal}, url);	
}
</script>
{/literal}