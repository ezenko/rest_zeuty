	{if $from_admin_mode}
	{include file="$admingentemplates/admin_top.tpl"}	
	<table cellpadding="0" cellspacing="0" border="0" width="100%">
	<tr>
		<td class="header" align="left">{$lang.menu.realestate} <font class="subheader">| {$lang.menu.realestate} | {if !$is_realtor}{$lang.menu.my_realtor}{else}{$lang.menu.my_agents}{/if}</font></td>
	</tr>
	<tr>
		<td><div class="help_text"><span class="help_title">{$lang.default_select.help}</span>{if !$is_realtor}{$lang.admin_content.help_agents}{else}{$lang.admin_content.help_realtor}{/if}</div></td>
	</tr>
	<tr>
		<td>
			<table cellpadding="0" cellspacing="0" width="100%" border="0">				
				<tr>					
					{if $empty_result ne 1 && $is_realtor}
					<td align="right">{$lang.admin_content.order_by}:&nbsp;							
							<a href="#" {if $sorter eq 0} class="user_menu_link_active" {/if} onclick="document.location.href='{$file_name}?from_admin_mode=1&sorter=0{if $sorter eq 0}{$order_active_link}{else}{$order_link}{/if}'">{$lang.content.date_reg}{if $sorter eq 0}{$order_icon}{/if}</a>&nbsp;|&nbsp;
							<a href="#" {if $sorter eq 1} class="user_menu_link_active" {/if} onclick="document.location.href='{$file_name}?from_admin_mode=1&sorter=1{if $sorter eq 1}{$order_active_link}{else}{$order_link}{/if}'">{$lang.content.nickname}{if $sorter eq 1}{$order_icon}{/if}</a>&nbsp;|&nbsp;
							<a href="#" {if $sorter eq 2} class="user_menu_link_active" {/if} onclick="document.location.href='{$file_name}?from_admin_mode=1&sorter=2{if $sorter eq 2}{$order_active_link}{else}{$order_link}{/if}'">{$lang.content.date_last}{if $sorter eq 2}{$order_icon}{/if}</a>							
					</td>
					{/if}
				</tr>
				
			</table>
			
			<table cellpadding="0" cellspacing="0" width="100%" border="0">
				<tr>
					<td {if $is_realtor && $empty_result} style="padding-left:1px;"{/if}>
						{if $is_realtor}
							{if $empty_result ne 1}							
							{include file="$gentemplates/users_list.tpl"}					
							{else}
							<font class="error">{$lang.content.empty_result}</font>
							{/if}													
						{elseif !$company_info}
							<font class="error" style="{if !$from_admin_mode}padding-left:10px;{/if}">{$lang.content.no_offer}</font>
						{/if}	
					</td>
				</tr>
				{if $company_info}	
				<tr>
					<td style="padding:0px;">
					{include file="$gentemplates/company_info.tpl"}							
					</td>					
				</tr>	
				{/if}				
			</table>
			{if $is_realtor}
			<table style="{if !$from_admin_mode} padding:10px{else}padding-top:10px{/if};">
				<tr>
					<td>
					<a onclick="javascript: return OpenParentWindow('{$file_name}?sel=choose_agent&from_admin_mode_2=1');">{$lang.content.add_agents}</a>
					</td>
				</tr>
			</table>	
			{else}
			<table cellpadding="0" cellspacing="0" border="0">
				<tr>
					<td {if $from_admin_mode}style="padding-top:5px;"{/if}>
					<a href="{$server}{$site_root}/admin/admin_settings.php?section=admin">{$lang.content.change_registration_data}</a>
					</td>
				</tr>
			</table>	
			{/if}
	
			
{else}
	{include file="$gentemplates/site_top.tpl"}
	<table cellpadding="0" cellspacing="0" border="0">
	<tr valign="top">
		<td class="left" valign="top">
			{include file="$gentemplates/homepage_hotlist.tpl"}
		</td>
		<td class="delimiter">&nbsp;</td>
		<td class="main">
			<table cellpadding="0" cellspacing="0" width="100%" border="0">
			{if $banner.center}
			<tr>
				<td>
				<!-- banner center -->
			  	
				<div align="left">
					{$banner.center}
				</div>
			  	
			  	<!-- /banner center -->
		  		</td>
			</tr>	
			{/if}	
			<tr>
				<td class="header"><b>{if !$is_realtor}{$lang.headers.my_realtor}{else}{$lang.headers.my_agents}{/if}</b></td>
			</tr>
		
			<tr>
				<td>
			
				<table cellpadding="0" cellspacing="0" width="100%" border="0">
					<tr>
						<td class="subheader"><b>{if !$is_realtor}{$lang.headers.company_list}{else}{$lang.headers.agents_list}{/if}</b></td>
					</tr>
					<tr>
						<td style="padding: 10px 0px 5px 15px;">{if !$is_realtor}{$lang.content.preferences_help}{/if}</td>
					</tr>
					<tr>
					
						{if $empty_result ne 1 && $is_realtor}
						<td align="right">{$lang.default_select.order_by}:&nbsp;							
								<a href="#" {if $sorter eq 0} class="user_menu_link_active" {/if} onclick="document.location.href='{$file_name}?sorter=0{if $sorter eq 0}{$order_active_link}{else}{$order_link}{/if}'">{$lang.content.date_reg}{if $sorter eq 0}{$order_icon}{/if}</a>&nbsp;|&nbsp;
								<a href="#" {if $sorter eq 1} class="user_menu_link_active" {/if} onclick="document.location.href='{$file_name}?sorter=1{if $sorter eq 1}{$order_active_link}{else}{$order_link}{/if}'">{$lang.content.nickname}{if $sorter eq 1}{$order_icon}{/if}</a>&nbsp;|&nbsp;
								<a href="#" {if $sorter eq 2} class="user_menu_link_active" {/if} onclick="document.location.href='{$file_name}?sorter=2{if $sorter eq 2}{$order_active_link}{else}{$order_link}{/if}'">{$lang.content.date_last}{if $sorter eq 2}{$order_icon}{/if}</a>							
						</td>
						{/if}
					</tr>
					
				</table>
				
				<table cellpadding="0" cellspacing="0" width="100%" border="0" >
				<tr>
					<td {if !$company_info && !$is_realtor}style="padding:15px;"{/if}{if $is_realtor && $empty_result}style="padding-left:15px; padding-bottom:10px;"{/if}>
						{if $is_realtor}
							{if $empty_result ne 1}							
							{include file="$gentemplates/users_list.tpl"}					
							{else}
							<font class="error">{$lang.content.empty_result}</font>
							{/if}													
						{elseif !$company_info}
							<font class="error" >{$lang.content.no_offer}</font>
						{/if}	
					</td>
				</tr>
				{if $company_info}	
				<tr>
					<td style="padding:0px;">
					{include file="$gentemplates/company_info.tpl"}							
					</td>					
				</tr>	
				{/if}				
				</table>
				{if $is_realtor}
				<table cellpadding="0" cellspacing="0">
				<tr>
					<td  style="padding-left:15px">
					<a onclick="javascript: return OpenParentWindow('{$file_name}?sel=choose_agent');">{$lang.content.add_agents}</a>
					</td>
				</tr>
				</table>	
				{else}
				<table cellpadding="0" cellspacing="0"	>
				<tr>
					<td style="padding-left:15px; padding-top:5px;">
					<a href="{$server}{$site_root}/account.php">{$lang.content.change_registration_data}</a>
					</td>
				</tr>
				</table>	
				{/if}
				
			</td>
		</tr>
		</table>
	</td>
</tr>

</table>
{include file="$gentemplates/site_footer.tpl"}
{/if}	
{if $from_admin_mode}	
			</td>
		</tr>
	</table>		
	{include file="$admingentemplates/admin_bottom.tpl"}
{/if}	
{literal}
<script type="text/javascript">

function OpenParentWindow(url){
	
	
	var left_pos = (window.screen.width - 800)/2;
	var top_pos = (window.screen.height - 600)/2;			
	
	ptWin = window.open(url,"", "width=800, height=600, resizable = yes, scrollbars = yes, menubar = no, left="+left_pos+", top="+top_pos+", modal=yes");	
	return false;
	
}

function ChooseAgent(value){	
	location.href = '{/literal}{$file_name}?sel=add{if $from_admin_mode}&from_admin_mode=1{/if}&id_agent={literal}'+value;
	return ;	
}
</script>
{/literal}