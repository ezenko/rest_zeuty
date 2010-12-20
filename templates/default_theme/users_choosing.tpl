{include file="$gentemplates/site_top_popup.tpl"}
<table cellpadding="0" cellspacing="0" border="0" width="100%">
<tr><td width="20"></td><td class="header"><b>{$lang.content.user_choose}</b></td></tr>
</table>

<table cellpadding="0" cellspacing="0" width="100%" border="0" align="center">
<tr>
	<td width="20"></td>
	<td>
		<table cellpadding="0" cellspacing="0" width="100%" border="0">
		<tr>
			<td  class="subheader"><b>{$lang.content.user_search}</b></td>
		</tr>
		{if $error}
		<tr>
			<td style="padding: 10px 0px 10px 5px;">
			<div class="error">*&nbsp;{$error}</div>
			</td>			
		</tr>
		{/if}		
		
		<tr>
			<td style="padding-top: 5px;padding-left:15px;">{if $par == "choose_company"}{$lang.content.search_help_company}{else}{$lang.content.search_help_agent}{/if}</td>
		</tr>
		
		<tr>
			<td style="{if $users}padding-bottom: 10px;{/if}padding-left:15px;">
				<table width="100%" cellpadding="1" cellspacing="0"><tr>
					
					<td height="30px"  align="left" bgcolor="#FFFFFF" class="main_content_text">
					<input type=hidden name="sorter" value="{$sorter}">
					<input type=hidden name="order" value="{$order}">
					
					<table cellpadding="0" cellspacing="0"><tr>
						<form name="search_form" action="{$file_name}" method="post">
						<input type=hidden name="sel" value="{$par}">
						<input type=hidden name="is_show" value="1">
						<input type=hidden name="id_user_exc" value="{$id_user_exc}">
						{if $from_admin_mode}
						<input type=hidden name="from_admin_mode_2" value="1">
						{/if}

						<td class="main_content_text" ><input type="text" name="search" class="str" value="{$search}">&nbsp;</td>			
		
						<td class="main_content_text" style="padding-left:5px;" >
							<input type="button" class="btn_small" value="{$lang.buttons.search}" onclick="javascript: document.search_form.sel.value='{$par}'; document.search_form.submit();" name="search_submit">
						</td>
						</form>
					</tr>					
					</table>
					</td>
				</tr></table>
			</td>
		</tr>
		
		{if $users}
		{if $par == "choose_company"}
		<tr>
		<td style="padding-left:15px;padding-top:0px;">			
		<form name="user_form" id="user_form" action="" method="POST"> 
			<table class="table_main" cellspacing=1 cellpadding=3 width="100%" border="0" style="margin-bottom: 10px;" >				
				<tr class="table_header">
					<td class="main_header_text" align="center">{$lang.content.number}</td>
					<!--<td class="main_header_text" align="center">
						<div style="cursor: pointer;" onclick="javascript:location.href='{$file_name}?sorter=1&order={$order}&letter={$letter}&search={$search}&s_type={$s_type}&s_stat={$s_stat}';">{$lang.content.nick}</div>
					</td>-->
					<td class="main_header_text" align="center">
						<div style="cursor: pointer;" onclick="javascript:location.href='{$file_name}?sel=choose_company&is_show=1&sorter=1&order={if $sorter==1}{$data.sorter_tolink}{else}2{/if}&search={$search}&s_type={$s_type}&page={$page}';">{$lang.content.user_name}{if $sorter==1}{$data.order_icon}{/if}</div>
					</td>
					<td class="main_header_text" align="center">
						<div style="cursor: pointer;" onclick="javascript:location.href='{$file_name}?sel=choose_company&is_show=1&sorter=2&order={if $sorter==2}{$data.sorter_tolink}{else}2{/if}&search={$search}&s_type={$s_type}&page={$page}';">{$lang.content.company_name}{if $sorter==2}{$data.order_icon}{/if}</div>
					</td>					
					
					<td class="main_header_text" align="center">
						<div style="cursor: pointer;" onclick="javascript:location.href='{$file_name}?sel=choose_company&is_show=1&sorter=5&order={if $sorter==5}{$data.sorter_tolink}{else}2{/if}&search={$search}&s_type={$s_type}&page={$page}';">{$lang.content.user_ads}{if $sorter==5}{$data.order_icon}{/if}</div>
					</td>
					<td class="main_header_text" align="center">{$lang.content.user_choose}</td>
				</tr>
				{assign var=page_pr value=$page-1}				
				
				{section name=u loop=$users}				
				<tr>
					<td class="main_content_text" align="center" bgcolor="#FFFFFF"><!--{$user[u].number}-->{$rows_num_page*$page_pr+$smarty.section.u.index+1}</td>
					<!--<td class="main_content_text" align="center" bgcolor="#FFFFFF">
						{if $user[u].root_user}
							{$user[u].nick}
						{else}
							<a href="{$user[u].edit_link}">{$user[u].nick}</a>
						{/if}
					</td>-->
					<td class="main_content_text" align="center" bgcolor="#FFFFFF">						
						{$users[u].name}
					</td>
					<td class="main_content_text" align="center" bgcolor="#FFFFFF">						
						{$users[u].company_name}
					</td>
										
					
										
					<td class="main_content_text" align="center" bgcolor="#FFFFFF">
						{if $users[u].rent_count}
							<input type="button" class="button_2" value="{$lang.buttons.view}&nbsp;:&nbsp;{$users[u].rent_count}" onclick="{literal}javascript: document.location.href='{/literal}{$users[u].rent_link}&user_myself={$users[u].index}'">
						{else}
							{$lang.content.empty_ads}
						{/if}
					</td>
									
					<td class="main_content_text" align="center" bgcolor="#FFFFFF">
						<input type="hidden" name="id_user[{$users[u].number}]" value="{$users[u].id}">
							<input type="button" class="button_2" value="{$lang.buttons.choose}" onclick="{if $users[u].notify != 0}{literal}javascript: alert('{/literal}{$lang.content.already_choose}') {else}{literal}javascript: CloseParentWindow({/literal}'{$users[u].company_name}', '{$par}', '{$id_user_exc}'){/if};">
						
					</td>
				</tr>
				{/section}
						
			</table>
			{if $links}
				<table cellpadding="2" cellspacing="2" border="0" style="margin-bottom:5px;">
					<tr>
						<td class="text2">{$lang.content.pages}:
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
		
		{if $par == "choose_agent"}
		<tr>
		<td style="padding-left:15px;padding-top:0px;">			
		<form name="user_form" id="user_form" action="" method="POST"> 
			<table class="table_main" cellspacing=1 cellpadding=3 width="100%" border="0" style="margin-bottom: 10px;" >				
				<tr class="table_header">
					<td class="main_header_text" align="center">{$lang.content.number}</td>
					<!--<td class="main_header_text" align="center">
						<div style="cursor: pointer;" onclick="javascript:location.href='{$file_name}?sorter=1&order={$order}&letter={$letter}&search={$search}&s_type={$s_type}&s_stat={$s_stat}';">{$lang.content.nick}</div>
					</td>-->
					<td class="main_header_text" align="center">
						<div style="cursor: pointer;" onclick="javascript:location.href='{$file_name}?sel={$par}{if $from_admin_mode}&from_admin_mode=1{/if}&is_show=1&sorter=1&order={if $sorter==1}{$data.sorter_tolink}{else}2{/if}&search={$search}&s_type={$s_type}&page={$page}';">{$lang.content.user_name}{if $sorter==1}{$data.order_icon}{/if}</div>
					</td>
										
					<td class="main_header_text" align="center">
						<div style="cursor: pointer;" onclick="javascript:location.href='{$file_name}?sel={$par}{if $from_admin_mode}&from_admin_mode=1{/if}&is_show=1&sorter=5&order={if $sorter==5}{$data.sorter_tolink}{else}2{/if}&search={$search}&s_type={$s_type}&page={$page}';">{$lang.content.user_ads}{if $sorter==5}{$data.order_icon}{/if}</div>
					</td>
					<td class="main_header_text" align="center">{$lang.content.user_choose}</td>
				</tr>
				{assign var=page_pr value=$page-1}				
				
				{section name=u loop=$users}				
				<tr>
					<td class="main_content_text" align="center" bgcolor="#FFFFFF"><!--{$user[u].number}-->{$rows_num_page*$page_pr+$smarty.section.u.index+1}</td>
					<!--<td class="main_content_text" align="center" bgcolor="#FFFFFF">
						{if $user[u].root_user}
							{$user[u].nick}
						{else}
							<a href="{$user[u].edit_link}">{$user[u].nick}</a>
						{/if}
					</td>-->
					<td class="main_content_text" align="center" bgcolor="#FFFFFF">						
						{$users[u].name}
					</td>					
										
					<td class="main_content_text" align="center" bgcolor="#FFFFFF">
						{if $users[u].rent_count}
							<input type="button" class="button_2" value="{$lang.buttons.view}&nbsp;:&nbsp;{$users[u].rent_count}" onclick="{literal}javascript: document.location.href='{/literal}{$users[u].rent_link}&user_myself={$users[u].index}'">
						{else}
							{$lang.content.empty_ads}
						{/if}
					</td>
									
					<td class="main_content_text" align="center" bgcolor="#FFFFFF">
						<input type="hidden" name="id_user[{$users[u].number}]" value="{$users[u].id}">
							<input type="button" class="button_2" value="{$lang.buttons.choose}" onclick="{literal}javascript: CloseParentWindow({/literal}'{$users[u].id}', '{$par}');">
						
					</td>
				</tr>
				{/section}
						
			</table>
			{if $links}
				<table cellpadding="2" cellspacing="2" border="0" style="margin-bottom:5px;">
					<tr>
						<td class="text2">{$lang.content.pages}:
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
		{/if}
		<tr>
			<td style="padding-left:15px;">
			
			{if !$users && $is_show}
			<table>			
			<tr>
				<td><font class="error">{if $search == ""}{$lang.content.need_search_criteria}{else}{$lang.content.empty_search}{/if}</font></td>				
			</tr>
			</table>
			{/if}
			<td>
		</tr>	
	</table>
	</td>
</tr>
</table>
{include file="$gentemplates/site_footer_popup.tpl"}

{literal}
<script>
function CloseParentWindow(value, par, user){	
	window.close();
	window.opener.focus();
	if (par == 'choose_company'){
		window.opener.document.getElementById('agency_name').value = value;	
		window.opener.document.getElementById('agency_name').focus();	
	}
	if (par == 'choose_agent'){
		window.opener.ChooseAgent(value);
	}
	
}

</script>
{/literal}
{if $script}
{$script}
{/if}