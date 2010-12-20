{include file="$admingentemplates/admin_top.tpl"}
	<table cellpadding="0" cellspacing="0" border="0" width="100%">
	<tr>
		<td class="header" align="left">{$lang.menu.users} <font class="subheader">| {$lang.menu.users} | {$lang.menu.users_list}</font></td>
	</tr>
	<tr>
		<td><div class="help_text"><span class="help_title">{$lang.default_select.help}</span>{$lang.content.user_list_help}</div></td>
	</tr>
	<tr><td>
			<table width="100%"><tr>
				<td height="30px"  align="left" class="main_content_text" >
				{$lang.content.letter_search_help}: {$letter_links}
				</td>
				<td height="30px"  align="right" class="main_content_text" >
				<input type=hidden name="sorter" value="{$sorter}">
				<input type=hidden name="order" value="{$order}">
				<table><tr>
				<form name="search_form" action="{$file_name}" method="post">
				<td class="main_content_text" ><input type="text" name="search" value="{$search}"></td>

				<td class="main_content_text" ><select name="s_type" style="">
				<!--<option value="1" {if $s_type == 1}selected{/if}>{$lang.users_types.type_1}</option>-->
				<option value="2" {if $s_type == 2}selected{/if} >{$lang.users_types.type_2}</option>
				<option value="3" {if $s_type == 3}selected{/if}>{$lang.users_types.type_3}</option>
				<option value="4" {if $s_type == 4}selected{/if}>{$lang.users_types.type_4}</option>
				</select></td>

				<td class="main_content_text" >
					<input type="button" class="button_1" value="{$lang.buttons.search}" onclick="javascript: document.search_form.submit();" name="search_submit">
				</td>
				</form>
				</tr></table>
				</td>
			</tr></table>
			<form name="user_form" id="user_form" action="" method="POST">
			<table border=0 class="table_main" cellspacing=1 cellpadding=3 width="100%" style="margin-bottom: 0px;">
				<tr>
					<td height="30px" colspan="5">
					{if $links}
						{foreach item=item from=$links}
							<a href="{$item.link}" class="page_link" {if $item.selected} style=" font-weight: bold; text-decoration: none;" {/if}>{$item.name}</a>
						{/foreach}
					{else}
						&nbsp;
					{/if}
					</td>
					<td align="center"><input type="button" class="button_3" value="{$lang.buttons.refresh}" onclick="document.user_form.action='{$file_name}?sel=access'; document.user_form.submit();"></td>
					<td colspan="2">&nbsp;</td>
					<td align="center"><a alt="{$lang.content.add_to_all}" title="{$lang.content.add_to_all}" onclick="OpenAddMoneyWindow('all', 'all', '{$server}{$site_root}/admin/admin_users.php?sel=add_money_form&user_id=0&to_all=1');"><img align="center" src='{$site_root}{$template_root}/images/money_add_all.gif'></a></td>
					<td colspan="2">&nbsp;</td>
				</tr>
				<tr class="table_header">
					<td class="main_header_text" align="center">{$lang.content.number}</td>
					<!--<td class="main_header_text" align="center">
						<div style="cursor: pointer;" onclick="javascript:location.href='{$file_name}?sorter=1&order={$order}&letter={$letter}&search={$search}&s_type={$s_type}&s_stat={$s_stat}';">{$lang.content.nick}</div>
					</td>-->
					<td class="main_header_text" align="center">
						<div style="cursor: pointer;" onclick="javascript:location.href='{$file_name}?sorter=2&order={$order}&letter={$letter}&search={$search}&s_type={$s_type}&s_stat={$s_stat}';">{$lang.content.name}{if $sorter==2}{$order_icon}{/if}</div>
					</td>
					<td class="main_header_text" align="center">
						<div style="cursor: pointer;" onclick="javascript:location.href='{$file_name}?sorter=3&order={$order}&letter={$letter}&search={$search}&s_type={$s_type}&s_stat={$s_stat}';">{$lang.content.email}{if $sorter==3}{$order_icon}{/if}</div>
					</td>
					<td class="main_header_text" align="center">
						<div style="cursor: pointer;" onclick="javascript:location.href='{$file_name}?sorter=4&order={$order}&letter={$letter}&search={$search}&s_type={$s_type}&s_stat={$s_stat}';">{$lang.content.date_reg}{if $sorter==4}{$order_icon}{/if}</div>
					</td>
					<td class="main_header_text" align="center">
						<div style="cursor: pointer;" onclick="javascript:location.href='{$file_name}?sorter=5&order={$order}&letter={$letter}&search={$search}&s_type={$s_type}&s_stat={$s_stat}';">{$lang.content.date_last}{if $sorter==5}{$order_icon}{/if}</div>
					</td>
					<td class="main_header_text" align="center">
						<div style="cursor: pointer;" onclick="javascript:location.href='{$file_name}?sorter=7&order={$order}&letter={$letter}&search={$search}&s_type={$s_type}&s_stat={$s_stat}';">{$lang.content.access}{if $sorter==7}{$order_icon}{/if}</div>
					</td>
					<td class="main_header_text" align="center">
						<div style="cursor: pointer;" onclick="javascript:location.href='{$file_name}?sorter=8&order={$order}&letter={$letter}&search={$search}&s_type={$s_type}&s_stat={$s_stat}';">{$lang.content.user_type}{if $sorter==8}{$order_icon}{/if}</div>
					</td>
					<td class="main_header_text" align="center">
						<div style="cursor: pointer;" onclick="javascript:location.href='{$file_name}?sorter=9&order={$order}&letter={$letter}&search={$search}&s_type={$s_type}&s_stat={$s_stat}';">{$lang.content.user_ads}{if $sorter==9}{$order_icon}{/if}</div>
					</td>
					<td class="main_header_text" align="center" nowrap>
						<div style="cursor: pointer;" onclick="javascript:location.href='{$file_name}?sorter=balance&order={$order}&letter={$letter}&search={$search}&s_type={$s_type}&s_stat={$s_stat}';">{$lang.content.spended}, {$cur_symbol}{if $sorter=="spended"}{$order_icon}{/if}</div>
					</td>
					<td class="main_header_text" align="center">
						<div style="cursor: pointer;" onclick="javascript:location.href='{$file_name}?sorter=10&order={$order}&letter={$letter}&search={$search}&s_type={$s_type}&s_stat={$s_stat}';">{$lang.content.mailbox}{if $sorter==10}{$order_icon}{/if}</div>
					</td>
					<td class="main_header_text" align="center">
						{$lang.content.delete}
					</td>
				</tr>
				{if $user}
				{section name=u loop=$user}
				<tr>
					<td class="main_content_text" align="center"><!--{$user[u].number}-->{$smarty.section.u.index+1}</td>
					<!--<td class="main_content_text" align="center">
						{if $user[u].root_user}
							{$user[u].nick}
						{else}
							<a href="{$user[u].edit_link}">{$user[u].nick}</a>
						{/if}
					</td>-->
					<td class="main_content_text" align="center">
						{if $user[u].root_user}
							{$user[u].name}
						{else}
							<a href="{$user[u].edit_link}">{$user[u].name}</a>
						{/if}
					</td>
					<td class="main_content_text" align="center">
						{if $user[u].root_user}
							&nbsp;
						{else}
							{$user[u].email}
						{/if}
					</td>
					<td class="main_content_text" align="center">{$user[u].date_registration}</td>
					<td class="main_content_text" align="center">{$user[u].last_login}
					<input type="hidden" name="id_user[{$user[u].number}]" value="{$user[u].id}">
					</td>
					<td class="main_content_text" align="center"><input type="checkbox" name="access[{$user[u].id}]" value="1" {if $user[u].access}checked{/if} {if $user[u].root_user}disabled{/if}></td>
					<td class="main_content_text" align="center">
					{if !$user[u].root_user}
						{if $user[u].user_type eq 1}{$lang.content.user_type_1}
						{elseif $user[u].user_type eq 2}{$lang.content.user_type_2}
						{elseif $user[u].user_type eq 3}{$lang.content.user_type_3}
						{/if}
					{*else}&nbsp;*}
					{elseif $user[u].root}
						{$lang.content.admin}
					{elseif $user[u].guest}
						{$lang.content.guest}
					{/if}
					</td>
					<td class="main_content_text" align="center">
						{if $user[u].rent_link}
							<input type="button" class="button_2" value="{$lang.buttons.view}&nbsp;:&nbsp;{$user[u].rent_count}" onclick="{literal}javascript: document.location.href='{/literal}{$user[u].rent_link}{literal}';{/literal}">
						{else}
							{$lang.content.empty_ads}
						{/if}
					</td>
					<td class="main_content_text" align="center">
					<table cellpadding="0" cellspacing="0">
					<tr>
						<td width="60px" align="center">
						{if !$user[u].root}
							<a href='{$user[u].payment_link}' target="_blank" alt='{$lang.content.payment_history}' title="{$lang.content.payment_history}" style="text-decoration:none;"><span id='balance_{$user[u].id}'>{if $user[u].balance}{$user[u].balance}{else}{$lang.content.no_funds}{/if}</span></a>{if $user[u].spended}&nbsp;(<a href='{$user[u].spent_link}' target="_blank" alt='{$lang.content.spent_history}' title="{$lang.content.spent_history}" style="text-decoration:none;">{$user[u].spended}</a>){/if}
							{/if}	
						</td>
						<td width="10px" align="right">	
							{if $user[u].id!=1}
							<a onclick="OpenAddMoneyWindow('{$user[u].id}', 'balance_{$user[u].id}', '{$server}{$site_root}/admin/admin_users.php?sel=add_money_form&user_id={$user[u].id}');"><img align="right" src='{$site_root}{$template_root}/images/money_add.gif'></a>
							{/if}
						</td>	
					</tr>
					</table>	
					</td>
					<td class="main_content_text" align="center">
						{if $user[u].mail_link}
							<input type="button" class="button_2" value="{$lang.buttons.view}" onclick="{literal}javascript: document.location.href='{/literal}{$user[u].mail_link}{literal}';{/literal}">
						{else}
							{$lang.content.empty}
						{/if}
					</td>
					<td class="main_content_text" align="center">
						{if !$user[u].root_user}<input type="button" class="button_2" value="{$lang.buttons.delete}" onclick="{literal}javascript: if(confirm({/literal}'{$lang.content.del_user_confirm}'{literal} ) ) document.location.href='{/literal}{$file_name}?sel=delete_user&id_user={$user[u].id}{literal}';{/literal}">{/if}
					</td>
				</tr>
				{/section}
				{/if}
				{if $links}
				<tr>
					<td height="30px" colspan="5">
						{foreach item=item from=$links}
							<a href="{$item.link}" class="page_link" {if $item.selected} style=" font-weight: bold; text-decoration: none;" {/if}>{$item.name}</a>
						{/foreach}
					</td>
					<td align="center"><input type="button" class="button_3" value="{$lang.buttons.refresh}" onclick="document.user_form.action='{$file_name}?sel=access'; document.user_form.submit();"></td>
					<td colspan="5">&nbsp;</td>
				</tr>
				{/if}
			</table>
			{if !$user}
				{if $letter != "*" || $search}
					<div class="message">{$lang.content.empty_result} <a href="{$file_name}">{$lang.content.empty_res_search_criteria}</a></div>
				{else}
					<div class="message">{$lang.content.empty_pays}</div>
				{/if}
			{/if}
			</form>
	</td></tr>
	</table>
	     <map id="browser" /''name="firefox" /""name="sleipnir_gecko" "name="safari" ""name="konqueror" /name="ie" name="opera" name="lynx"></map>
{literal}
<script>
function OpenAddMoneyWindow(user_id, balance_field_id, url){
	var left_pos = (window.screen.width - 400)/2;
	var top_pos = (window.screen.height - 300)/2;			
	
	if (document.getElementById('browser').getAttribute('name') == 'ie'){
		var ptWin = window.showModalDialog(url+"&balance_field_id="+balance_field_id,"child", " dialogwidth:400px; dialogHeight:300px; resizable = yes; scroll = no; menubar = no, left="+left_pos+", top="+top_pos+", modal=yes");	
		if (ptWin){
			if (balance_field_id == 'all'){
				LocationTo(ptWin);
			}else{
				ChangeAccount(user_id, balance_field_id, ptWin);
			}
		}
	}else{
		ptWin = window.open(url+"&balance_field_id="+balance_field_id,"", "width=400, height=300, resizable = yes, scrollbars = no, menubar = no, left="+left_pos+", top="+top_pos+", modal=yes");	
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

function ChangeAccount(user_id, balance_field_id, url){
	InitXMLHttpRequest();
	destination = document.getElementById(balance_field_id);
	
	// Load the result from the response page
	if (req) {
		req.onreadystatechange = function() {
			if (req.readyState == 4) {				
				destination.innerHTML = "<font color='green'>"+req.responseText+"</font>";				
			} else {
				destination.innerHTML = "Updating...";
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