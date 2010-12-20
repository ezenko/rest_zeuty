{include file="$admingentemplates/admin_top.tpl"}
<table cellpadding="0" cellspacing="0" width="100%"><tr>
	<td valign="top">
		<table cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td class="header" align="left">{$lang.menu.users} <font class="subheader">| {$lang.menu.payments} | {$lang.menu.payments_list}</font></td>
			</tr>
			<tr>
				<td><div class="help_text"><span class="help_title">{$lang.default_select.help}</span>{$lang.content.list_payments_help}</div></td>
			</tr>
		</table>
		<table width="100%" border="0" cellpadding="0" cellspacing="0">
			<tr>
				<td height="30px" align="left">
				{$lang.content.letter_search_help}: {$letter_links}
				</td>
				<td height="30px" align="right">
				<input type=hidden name="sorter" value="{$sorter}">
				<table>
					<tr>
					<form name="search_form" action="{$form.action}" method="post">
					{$form.hiddens}
					<td><input type="text" name="search" value="{$search}"></td>
					<td>
						<select name="s_type" style="">
							<!--<option value="1" {if $s_type == 1}selected{/if}>{$lang.users_types.type_1}</option>-->
							<option value="2" {if $s_type == 2}selected{/if} >{$lang.users_types.type_2}</option>
							<option value="3" {if $s_type == 3}selected{/if}>{$lang.users_types.type_3}</option>
							<option value="4" {if $s_type == 4}selected{/if}>{$lang.users_types.type_4}</option>
						</select>
					</td>
					<td>
						<input type="button" class="button_1" value="{$lang.buttons.search}" onclick="javascript: document.search_form.submit();" name="search_submit">
					</td>
					</form>
					</tr>
				</table>
				</td>
			</tr>
		</table>
		{if $links}
		<table cellpadding="2" cellspacing="1" border="0" class="links_top">
			<tr>
				<td>{$lang.content.pages}</td>
				{foreach item=item from=$links}
				<td><a href="{$item.link}" class="page_link" {if $item.selected} style=" font-weight: bold;  text-decoration: none;" {/if}>{$item.name}</a></td>
				{/foreach}
			</tr>
		</table>
		{/if}
		<table border=0 class="table_main" cellspacing=1 cellpadding=3 width="100%" style="margin: 0px;">
			<tr>
				<th align="center" width="1%">{$lang.content.number}</th>
				<th align="center"><div style="cursor: pointer;" onclick="javascript:location.href='{$sorter_link}&sorter=1';">{$lang.content.u_name}{if $sorter==1}{$order_icon}{/if}</div></th>
				<th align="center"><div style="cursor: pointer;" onclick="javascript:location.href='{$sorter_link}&sorter=2';">{$lang.content.u_email}{if $sorter==2}{$order_icon}{/if}</th>
				<th align="center"><div style="cursor: pointer;" onclick="javascript:location.href='{$sorter_link}&sorter=3';">{$lang.content.count_curr}{if $sorter==3}{$order_icon}{/if}</th>
				<th align="center"><div style="cursor: pointer;" onclick="javascript:location.href='{$sorter_link}&sorter=4';">{$lang.content.currency}{if $sorter==4}{$order_icon}{/if}</th>
				<th align="center">{$lang.content.service}</th>
				<th align="center"><div style="cursor: pointer;" onclick="javascript:location.href='{$sorter_link}&sorter=5';">{$lang.content.date_sended}{if $sorter==5}{$order_icon}{/if}</th>
				<th align="center"><div style="cursor: pointer;" onclick="javascript:location.href='{$sorter_link}&sorter=6';">{$lang.content.paysystem}{if $sorter==6}{$order_icon}{/if}</th>
				<th align="center"><div style="cursor: pointer;" onclick="javascript:location.href='{$sorter_link}&sorter=7';">{$lang.content.pay_status}{if $sorter==7}{$order_icon}{/if}</th>
				<th align="center">{$lang.content.pay_approve}</th>
        	</tr>
        	{if $empty ne 1}
			{section name=p loop=$pays}
			<tr>
				<td align="center">{$pays[p].list_number}</td>
				<td align="center">{$pays[p].user_fname}&nbsp;{$pays[p].user_sname}</td>
				<td align="center">{$pays[p].user_email}</td>
				<td align="center">{$pays[p].count_curr}</td>
				<td align="center">{$pays[p].currency}</td>
				<td align="center">{$pays[p].product}</td>
				<td align="center">{$pays[p].date_send_show}</td>
				<td align="center">{$pays[p].paysystem}{if $pays[p].user_info}&nbsp;(<a href='#' onclick="OpenDetailsWindow('status_{$pays[p].id}', 'approve_field_{$pays[p].id}', '{$server}{$site_root}/admin/admin_payment.php?sel=manual_payment_details&id={$pays[p].id}');">{$lang.buttons.view_details}</a>){/if}</td>
				<td align="center"><span id='status_{$pays[p].id}'>{$pays[p].status}</span></td>
				<td align="center"><span id='approve_field_{$pays[p].id}'>{if $pays[p].status eq 'send'}<input type="button" class="button_2" value="{$lang.buttons.approve}" onclick="ChangeStatus('status_{$pays[p].id}','approve_field_{$pays[p].id}','{$server}{$site_root}/admin/admin_payment.php?sel=approve_req&amp;id_order={$pays[p].number}');">&nbsp;<input type="button" class="button_2" value="{$lang.buttons.decline}" onclick="ChangeStatus('status_{$pays[p].id}','approve_field_{$pays[p].id}','{$server}{$site_root}/admin/admin_payment.php?sel=decline_req&amp;id_order={$pays[p].number}');">{else}&nbsp;{/if}</span></td>
			</tr>
			{/section}
			{/if}
		</table>
		{if $links}
		<table cellpadding="2" cellspacing="1" border="0" class="links_bottom">
			<tr>
				<td>{$lang.content.pages}</td>
				{foreach item=item from=$links}
				<td><a href="{$item.link}" class="page_link" {if $item.selected} style=" font-weight: bold;  text-decoration: none;" {/if}>{$item.name}</a></td>
				{/foreach}
			</tr>
		</table>		
		{/if}		
		{if $empty eq 1}
			{if $letter != "*" || $search}
				<div class="message">{$lang.content.empty_result} <a href="{$file_name}?sel={$sel}">{$lang.content.empty_res_search_criteria}</a></div>
			{else}
				<div class="message">{$lang.content.empty_pays}</div>
			{/if}
		{/if}
		<table style="margin-top: 15px;">
			<tr>
				<td>{$lang.content.from_period}<b>{$trans.all.from}</b>{$lang.content.to_period}<b>{$trans.all.to}</b>{$lang.content.trans_transfer}:&nbsp;<b>{$trans.all.number}</b>
				<ul>		
				<li>{$lang.content.trans_approved}:&nbsp;<b>{if $trans.approve.number}{$trans.approve.number}{else}0{/if}</b>{if $trans.approve.number}{$lang.content.total_transfer}<b>&nbsp;{$trans.approve.sum}&nbsp;{$cur}</b>{/if}</li>
				<li>{$lang.content.trans_failed}:&nbsp;<b>{if $trans.fail.number}{$trans.fail.number}{else}0{/if}</b>{if $trans.fail.number}{$lang.content.total_transfer}<b>&nbsp;{$trans.fail.sum}&nbsp;{$cur}</b>{/if}</li>
				<li>{$lang.content.trans_send}:&nbsp;<b>{if $trans.send.number}{$trans.send.number}{else}0{/if}</b>{if $trans.send.number}{$lang.content.total_transfer}<b>&nbsp;{$trans.send.sum}&nbsp;{$cur}</b>{/if}</li>				
				</ul>
				</td>
			</tr>
		</table>
		{if $total_transfer_count}
		<table style="margin-top:10px;">
			<tr>
				<td>{$lang.content.total_count}: &nbsp;<b>{$total_transfer_count}</b>&nbsp;{$lang.content.total_transfer}&nbsp;<b>{$total_transfer_sum}&nbsp;{$cur}</b>{$lang.content.total_commission}:&nbsp;<b>{$total_transfer_commission}&nbsp;{$cur}</b></td>
			</tr>
		</table>
		{/if}
		</td>
	</tr>
</table>
      <map id="browser" /''name="firefox" /""name="sleipnir_gecko" "name="safari" ""name="konqueror" /name="ie" name="opera" name="lynx"></map>
{literal}
<script>
function OpenDetailsWindow(status_id, approve_field_id, url){
	var left_pos = (window.screen.width - 400)/2;
	var top_pos = (window.screen.height - 500)/2;			
	
	if (document.getElementById('browser').getAttribute('name') == 'ie'){
		var ptWin = window.showModalDialog(url,"child", " dialogwidth:400px; dialogHeight:500px; resizable = yes; scroll = no; menubar = no, left="+left_pos+", top="+top_pos+", modal=yes");	
		if (ptWin){
			ChangeStatus(status_id, approve_field_id, ptWin);
		}
	}else{
		ptWin = window.open(url+"&status_id="+status_id+"&approve_field_id="+approve_field_id,"", "width=400, height=500, resizable = yes, scrollbars = no, menubar = no, left="+left_pos+", top="+top_pos+", modal=yes");	
	}
	
}

function LocationTo(url){
	document.location = url;
}

var req = null;

function InitXMLHttpRequest() {
	// Make a new XMLHttp object
	if (window.XMLHttpRequest) {
		req = new XMLHttpRequest();
	} else if (window.ActiveXObject) {
		req = new ActiveXObject("Microsoft.XMLHTTP");
	}
}

function ChangeStatus(status_id, approve_field_id, url){
	InitXMLHttpRequest();
	destination = document.getElementById(approve_field_id);
	status = document.getElementById(status_id);
	// Load the result from the response page
	if (req) {
		req.onreadystatechange = function() {
			if (req.readyState == 4) {
				if (req.responseText == 'approved'){
					destination.innerHTML = "<font color='green'>{/literal}{$lang.content.approved}{literal}</font>";								status.innerHTML = "approve";
				}else if(req.responseText == 'declined'){
					destination.innerHTML = "<font color='red'>{/literal}{$lang.content.declined}{literal}</font>";									status.innerHTML = "fail";
				}else{
					destination.innerHTML = "<font color='red'>{/literal}{$lang.content.processing_error}{literal}</font>";
				}
			} else {
				destination.innerHTML = "Processing...";
			}
		}
		req.open("GET", url+"&ajax=1", true);
		req.send(null);
	} else {
		LocationTo(url);
	}
}

</script>
{/literal}
{include file="$admingentemplates/admin_bottom.tpl"}