{section name=i loop=$users_list}
	<table cellpadding="0" cellspacing="0" width="100%" border="0">
	<tr>
		<td {if !$from_admin_mode} style="padding-left: 15px;"{/if}>
			<table cellpadding="0" cellspacing="0" width="100%" border="0">
				<tr>
					<!--<td valign="top" width="{$thumb_width+20}">{if $users_list[i].view_ad_link}<a href="{$users_list[i].view_ad_link}">{/if}<img src="{$users_list[i].pict_path}" style="border-style: solid; border-width: 1px; border-color: #cccccc; padding-top: 3px; padding-bottom: 3px; padding-left: 3px; padding-right: 3px;" alt="" hspace="0" vspace="0">{if $users_list[i].view_ad_link}</a>{/if}</td>-->
					<td  height="{$thumb_height+10}" width="{$thumb_width+10}" valign="middle" align="center" style=" border: 1px solid #cccccc; ">
						{if $users_list[i].view_ad_link}<a href="{$users_list[i].view_ad_link}">{/if}<img alt="" src="{$users_list[i].pict_path}" style="border: none">{if $users_list[i].view_ad_link}</a>{/if}
					</td>
					<td valign="top" style="padding-left: 7px;">
						<table cellpadding="0" cellspacing="0">
							<tr>
								<td height="23" colspan="2">{if $users_list[i].company_name !=''}{$users_list[i].company_name}{else}{$users_list[i].login}{/if},&nbsp;{$lang.content.status}:&nbsp;{if $users_list[i].online_status eq 1}<font color="#a50000">{$lang.content.online}</font>{else}{$lang.content.offline}{/if}</td>
							</tr>
							<tr>
								<td height="23">{if $users_list[i].view_ad_link}<a href="{$users_list[i].view_ad_link}">{$lang.content.rental_ad}</a>{else}{$lang.content.no_ads_from_user}{/if},&nbsp;{$lang.content.date_last_login}:&nbsp;{$users_list[i].date_last_login}</td>
							</tr>
							<tr>
								<td height="23">{if $lists eq 1}<a href="{$users_list[i].del_link}">{$lang.content.del_from_list}</a>&nbsp;|&nbsp;{/if}<a href="#" onclick='javascript: window.open("mailbox.php?sel=chat_start&user_id={$users_list[i].id}", "blank_", "resizable=yes, scrollbars=yes, location=no, directories=no, status=no, width=650, height=768, toolbar=no, menubar=no, left=0,top=0");' >{$lang.content.contact_user}</a>{if $from_admin_mode != 1}&nbsp;|&nbsp;<noindex><a href="{$users_list[i].contact_link}">{$lang.content.contact_admin}</a></noindex>{/if}</td>
							</tr>
							{if $section_name == "agents"}
							<tr>
								{if $users_list[i].inviter == 'agent'}
								<td height="23">{if $users_list[i].approve == 0}<font class="error">&nbsp;{$lang.content.new_agent}</font>&nbsp;<a href="{$users_list[i].approve_link}">{$lang.content.approve}</a>&nbsp;|&nbsp;<a onclick="if (confirm('{$lang.content.decline_confirm}')) location.href='{$users_list[i].decline_link}'">{$lang.content.decline}</a>{elseif $users_list[i].approve == 1}<a onclick="if (confirm('{$lang.content.del_confirm}')) location.href='{$users_list[i].del_from_agents_link}'">{$lang.content.del_from_agents_list}</a>{/if}</td>
								{elseif $users_list[i].inviter == 'company'}
								<td height="23">{if $users_list[i].approve == 0}<font class="error">&nbsp;{$lang.content.not_approve_by_agent}</font>&nbsp;<a onclick="if (confirm('{$lang.content.confirm_del_company_offer}')) location.href='{$users_list[i].del_company_offer_link}'">{$lang.content.del_company_offer}</a>{elseif $users_list[i].approve == 1}<a onclick="if (confirm('{$lang.content.del_confirm}')) location.href='{$users_list[i].del_from_agents_link}'">{$lang.content.del_from_agents_list}</a>{/if}</td>
								{/if}
							</tr>
							{/if}
						</table>
					</td>
					</tr>
			</table>
		</td>
	</tr>
	</table>

	<table cellpadding="0" cellspacing="0" width="100%" border="0">
	<tr>
		<td style="padding-top:4px; padding-bottom: 4px;"><hr class="hr_2"></td>
	</tr>
	</table>

{/section}
{if $links}

	<table cellpadding="{if !$from_admin_mode}2{else}0{/if}" cellspacing="2" border="0" class="pages_links">

		<tr>
			<td class="text">{$lang.default_select.pages}:
			{foreach item=item from=$links}
			<a href="{$item.link}" {if $item.selected} style="font-weight: bold;"{/if}>{$item.name}</a>
			{/foreach}
			</td>
		</tr>
	</table>
{/if}