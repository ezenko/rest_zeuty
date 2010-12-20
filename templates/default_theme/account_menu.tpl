	<table cellpadding="0" cellspacing="0" width="100%" border="0">
	<tr><td class="subheader"><b>{$lang.headers.account_menu}</b></td>
	<tr>
		<td style="padding-top: 7px;"><table cellpadding="0" cellspacing="0"><tr>
			<td width="10">&nbsp;</td>
			<td><table cellpadding="0" cellspacing="0" border="0" class="rental_menu">			
			{section name=m loop=$account_menu}
				{if $user_type != 1 || ($user_type == 1 && $account_menu[m].name != "agents")}
					{if ($account_menu[m].name != "agents" || ($account_menu[m].name == "agents" && $user_type == 2 && $use_agent_user_type)) && ($account_menu[m].name != "realtor" || ( $account_menu[m].name == "realtor" && $user_type == 3 && $use_agent_user_type))}								
				<tr><td><a href="{$account_menu[m].href}" {if {$account_menu[m].onclick} onclick="{$account_menu[m].onclick}" {/if} {if $submenu == $account_menu[m].name} class="user_menu_link_active" {/if} >{$account_menu[m].value}</a>{if $new_agents !=0 && $account_menu[m].name == "agents"}&nbsp;({$new_agents}){/if}{if $new_company !=0 && $account_menu[m].name == "realtor"}&nbsp;({$new_company}){/if}</td></tr>
					{/if}
				{/if}
			{/section}
			{if $user.8 == 0} {* deactivated user *}
				<tr><td><a href="{$site_root}/account.php?sel=activate" {if $submenu == "activate"} class="user_menu_link_active" {/if} >{$lang.content.activate}</a></td></tr>
			{else}
				<tr><td><a href="{$site_root}/account.php?sel=deactivate" {if $submenu == "deactivate"} class="user_menu_link_active" {/if} >{$lang.content.deactivate}</a></td></tr>
			{/if}
			{if $use_pilot_module_sms_notifications}
				<tr><td><a href="{$site_root}/services.php?sel=sms_notifications" {if $submenu == "sms_notifications"} class="user_menu_link_active" {/if} >{$lang.sms_notifications.sms_notifications}</a></td></tr>
			{/if}
			</table></td>
		</tr>
		<tr><td>&nbsp;</td></tr>
		</table></td>
	</tr>
	<tr>
		<td valign="top" align="center">
			{include file="$gentemplates/testimonials_post_block.tpl"}
		</td>
	</tr>
	</table>