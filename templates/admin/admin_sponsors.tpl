{include file="$admingentemplates/admin_top.tpl"}
	<table cellpadding="0" cellspacing="0" border="0" width="100%">
		<tr>
			<td class="header" align="left">{$lang.menu.realestate} <font class="subheader">| {$lang.menu.realestate} | {$lang.menu.sponsors_announcements}</font></td>
		</tr>
		<tr>
			<td><div class="help_text"><span class="help_title">{$lang.default_select.help}</span>{if $type == "list"}{$lang.content.help_list_ads}{else}{$lang.content.help_add_ads}{/if}</div></td>
		</tr>
		<tr>
			<td style="padding-bottom: 10px;">
			{strip}
			&nbsp;&nbsp;&nbsp;
			{if $type == "list"}<b>{else}<a href="{$file_name}?type=list">{/if}
			{$lang.content.list} 
			{if $type == "list"}</b>{else}</a>{/if}
			&nbsp;&nbsp;&nbsp;
			{if $type == "add"}<b>{else}<a href="{$file_name}?type=add">{/if}
			{$lang.content.add} 
			{if $type == "add"}</b>{else}</a>{/if}
			&nbsp;&nbsp;&nbsp;			
			{/strip}
			</td>
		</tr>
	{if $type == "add"} <!-- start $type == add -->
		<tr>
		<td>
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
			</td>
		</tr>
		{if $user}
		<tr>
		<td>			
		<form name="user_form" id="user_form" action="" method="POST">
			<table border=0 class="table_main" cellspacing=1 cellpadding=3 width="100%" style="margin-bottom: 0px;">
				
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
						<div style="cursor: pointer;" onclick="javascript:location.href='{$file_name}?sorter=8&order={$order}&letter={$letter}&search={$search}&s_type={$s_type}&s_stat={$s_stat}';">{$lang.content.user_type}{if $sorter==8}{$order_icon}{/if}</div>
					</td>
					<td class="main_header_text" align="center">
						<div style="cursor: pointer;" onclick="javascript:location.href='{$file_name}?sorter=9&order={$order}&letter={$letter}&search={$search}&s_type={$s_type}&s_stat={$s_stat}';">{$lang.content.user_ads}{if $sorter==9}{$order_icon}{/if}</div>
					</td>
					
				</tr>
				{assign var=page_pr value=$page-1}
				{if $user}
				{section name=u loop=$user}
				<tr>
					<td class="main_content_text" align="center"><!--{$user[u].number}-->{$rows_num_page*$page_pr+$smarty.section.u.index+1}</td>
					<!--<td class="main_content_text" align="center">
						{if $user[u].root_user}
							{$user[u].nick}
						{else}
							<a href="{$user[u].edit_link}">{$user[u].nick}</a>
						{/if}
					</td>-->
					<td class="main_content_text" align="center">{$user[u].name}</td>
					<td class="main_content_text" align="center">
						{if $user[u].root_user}
							&nbsp;
						{else}
							{$user[u].email}
						{/if}
					</td>
					<input type="hidden" name="id_user[{$user[u].number}]" value="{$user[u].id}">					
					<td class="main_content_text" align="center">
					{if !$user[u].root_user}
						{if $user[u].user_type eq 1}{$lang.content.user_type_1}
						{elseif $user[u].user_type eq 2}{$lang.content.user_type_2}
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
							<input type="button" class="button_2" value="{$lang.buttons.chooseTo}&nbsp;:&nbsp;{$user[u].rent_count}" onclick="{literal}javascript: document.location.href='{/literal}{$user[u].rent_link}{literal}';{/literal}">
						{else}
							{$lang.content.empty_ads}
						{/if}
					</td>
				</tr>
				{/section}
				{/if}
			</table>
			{if $links}
				<table cellpadding="2" cellspacing="2" border="0">
					<tr>
						<td class="text">{$lang.content.pages}:
						{foreach item=item from=$links}
						<a href="{$item.link}" {if $item.selected} style="font-weight: bold;"{/if}>{$item.name}</a>
						{/foreach}
						</td>
					</tr>
				</table>
				{/if}
			</form>
		</td>
		</tr>
		{/if}
			{if !$user}
			<table>
			<tr>
				{if $letter != "*" || $search}
					<td><div class="message">{$lang.content.empty_result} {$lang.content.empty_res_search_criteria}</div></td>
				{else}
					<td><div class="message">{$lang.content.empty}</div></td>
				{/if}
			</tr>
			</table>
			{/if}
	{/if}<!-- ent $type == add -->	

	{if ($type == "list") } <!-- start $type == list -->
		{if $ads}			
			<tr>
				<td>
			<form name="ads_form" id="ads_form" action="" method="POST">
				<table border=0 class="table_main" cellspacing=1 cellpadding=3 width="100%" style="margin-bottom: 0px;">
					<tr class="table_header">
						<td class="main_header_text" align="center" colspan="2">{$lang.content.position_number}</td>
						<td class="main_header_text" align="center">
							{$lang.content.preview}
						</td>
						<td class="main_header_text" align="center" width="10%">
							{$lang.content.show_sp}
						</td>
						<td class="main_header_text" align="center">
							{$lang.content.delete}
						</td>
					</tr>
					{if $ads}
					{section name=u loop=$ads}
					<tr>
						<td class="main_content_text" {if $num_records==1} colspan="2" {/if} align="center"><!--{$user[u].number}-->{$smarty.section.u.index+$page*$ads_numpage-$ads_numpage+1}</td>		
						{if $num_records>1}
							<td align="center" nowrap>
							{if $smarty.section.u.index+$page*$ads_numpage-$ads_numpage+1 != 1}
							&nbsp;<a href="{$file_name}?sel=change_order&ads_move_up={$ads[u].id}&page={$page}" class="sort_link" title="{$lang.content.move_up}">&uarr;</a>
							{/if}
							{if $smarty.section.u.index+$page*$ads_numpage-$ads_numpage+1 != $num_records}
							&nbsp;<a href="{$file_name}?sel=change_order&ads_move_down={$ads[u].id}&page={$page}" class="sort_link" title="{$lang.content.move_down}">&darr;</a>
							{/if}
							</td>
						{/if}
						<td class="main_content_text" align="left" width="100%">
							<table cellpadding="0" cellspacing="0" border="0" align="left" width="100%">
							<tr>
								<td valign="middle" align="center" height="{$thumb_height+10}" width="{$thumb_width+10}" style=" border: 1px solid #cccccc; ">			
									{if $ads[u].photo_id}
									{section name=ph loop=$ads[u].photo_id max=1}
										<img src="{$ads[u].thumb_file[ph]}" style="padding: 3px; cursor: pointer;" alt="" onclick="document.location.href='{$ads[u].viewprofile_link}';" >
									{/section}
									{else}
									<img src="{$ads[u].thumb_file[0]}" style="padding: 3px; cursor: pointer;" alt="" onclick="document.location.href='{$ads[u].viewprofile_link}';" >
									{/if}	
								</td>
								<td valign="top" align="left">
									<table>
									<tr>
										<td align="left" style="padding-left: 11px;">
											<a href="{$ads[u].viewprofile_link}">{$ads[u].user_login}</a>,&nbsp;
												
														<strong>
											{if $ads[u].type eq '1'}{$lang.content.i_need}
													{elseif $ads[u].type eq '2'}{$lang.content.i_have}
													{elseif $ads[u].type eq '3'}{$lang.content.i_buy}
													{elseif $ads[u].type eq '4'}{$lang.content.i_sell}
													{/if}
											</strong>
											<!-- realty type -->
											<b>{$ads[u].realty_type}</b>
										</td>
									</tr>
									{if $ads[u].country_name || $ads[u].region_name || $ads[u].city_name}
									<tr>
										<td align="left" valign="top" style="padding-left: 11px;">{$ads[u].country_name}{if $ads[u].region_name}, {$ads[u].region_name}{/if}{if $ads[u].city_name}, {$ads[u].city_name}{/if}</td>
									</tr>
									{/if}
									<tr>
										<td align="left" valign="top" style="padding-left: 11px;">
											{if $ads[u].type eq 1 || $ads[u].type eq 2}{$lang.content.month_payment_in_line}{else}{$lang.content.price}{/if}&nbsp;<strong>
											{if $ads[u].type eq 1 || $ads[u].type eq 3}
												{$lang.content.from2}&nbsp;{$ads[u].min_payment_show}&nbsp;{$lang.content.upto}&nbsp;{$ads[u].max_payment_show}
											{else}
												{$ads[u].min_payment_show}
											{/if}</strong>
										</td>
									</tr>
									<tr>
										<td align="left" valign="top" style="padding-left: 11px;">{strip}
											{if $ads[u].movedate}
												{if $ads[u].id_type eq 1 || $ads[u].id_type eq 3}
													{$lang.content.move_in_date}
												{else}
													{$lang.content.available_date}
												{/if}
												:&nbsp;<strong>{$ads[u].movedate}</strong>
											{/if}
											{/strip}
										</td>
									</tr>
									<tr>
										<td align="left" valign="top" style="padding-left: 11px;">{strip}
											{if !$ads[u].is_active}	
											<font class="error">{$lang.content.not_active}</font>											
											{/if}	
											{/strip}
										</td>
									</tr>
								</table>
								</td>
							</tr>
							</table>
						</td>
						<td class="main_content_text" align="center"><input type="checkbox" name="show_sp[{$ads[u].id}]" value="1" {if $ads[u].status == 1}checked{/if}></td>
						
						<td align="center"><input type="button" class="button_2" value="{$lang.buttons.delete}" onclick="{literal}javascript: if(confirm({/literal}'{$lang.content.del_ad_sp_confirm}'{literal} ) ){ document.location.href={/literal}'{$file_name}?sel=del_sponsors_ad&id={$ads[u].id}'{literal}}{/literal}">
						<input type="hidden" name="id_ad[{$ads[u].number}]" value="{$ads[u].id}">
						</td>
					</tr>
					{/section}
					{/if}
				</table>
				{if $links}
				<table cellpadding="2" cellspacing="2" border="0">
					<tr>
						<td class="text">{$lang.content.pages}:
						{foreach item=item from=$links}
						<a href="{$item.link}" {if $item.selected} style="font-weight: bold;"{/if}>{$item.name}</a>
						{/foreach}
						</td>
					</tr>
				</table>
				{/if}
				<table align="right">
				<tr>						
					<td>
					<input type="button" id="selAll" class="button_3" value="{$lang.buttons.select_all}" onclick="select_all();">
					</td>
					<td>
					<input type="button" class="button_3" value="{$lang.buttons.save}" onclick="document.ads_form.action='{$file_name}?sel=show_sp_edit'; document.ads_form.submit();">
					</td>
				</tr>
				</table>	
				</form>
				</td>
			</tr>	
		{/if}	
		{if !$ads}
			<tr>
			<td><div class="message">
				<font class="error">{$lang.content.empty_ads}</font>
				</div>
				</td>
			</tr>	
			
		{/if}
	{/if}<!-- ent $type == list -->	

	</table>
{include file="$admingentemplates/admin_bottom.tpl"}	
{literal}
<script language="javascript">
var code = 1;
	function select_all() {
		
		var elements = document.ads_form.elements.length;
		if (code == 1) {
			for (i = 0; i < elements; i++) { 
				if (document.ads_form.elements[i].name.substr(0,7) == "show_sp") {				
					document.ads_form.elements[i].checked = true;					
				}
			}
			code = 0;
			document.getElementById("selAll").value="{/literal}{$lang.buttons.deselect_all}{literal}";
			
		} else {
			for (i = 0; i < elements; i++) { 
				if(document.ads_form.elements[i].name.substr(0,7) == "show_sp") {
					document.ads_form.elements[i].checked = false;
				}
		}
			code = 1;
			document.getElementById("selAll").value="{/literal}{$lang.buttons.select_all}{literal}";
		}		
	}
	
	function ChangeStatus(type, file_id) {
		var elem_approve_id = "approve_" + file_id;
		var elem_decline_id = "decline_" + file_id;
		if (type == 'approve') {
			if (document.getElementById(elem_approve_id).checked) {
				document.getElementById(elem_decline_id).disabled = true;
			} else {
				document.getElementById(elem_decline_id).disabled = false;
			}
		}
		if (type == 'decline') {
			if (document.getElementById(elem_decline_id).checked) {
				document.getElementById(elem_approve_id).disabled = true;
			} else {
				document.getElementById(elem_approve_id).disabled = false;
			}
		}
	}
</script>
{/literal}
