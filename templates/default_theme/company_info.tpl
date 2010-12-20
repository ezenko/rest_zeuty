{if !$from_admin_mode}
	<table cellpadding="0" cellspacing="0" width="100%" border="0">
	<tr>
		<td><hr class="hr_2"></td>
	</tr>
	</table>
{/if}	
{section name=i loop=$company_info}	
	<table cellpadding="0" cellspacing="0" width="100%" border="0">	
	<tr>		
		<td style="{if !$from_admin_mode}padding-left: 15px; {/if}padding-top: 0px;">
			<table cellpadding="0" cellspacing="0" width="100%" border="0">
				<tr>
					<!--<td valign="top" width="{$thumb_width+20}">{if $company_info[i][i].view_ad_link}<a href="{$company_info[i].view_ad_link}">{/if}<img src="{$company_info[i].pict_path}" style="border-style: solid; border-width: 1px; border-color: #cccccc; padding-top: 3px; padding-bottom: 3px; padding-left: 3px; padding-right: 3px;" alt="" hspace="0" vspace="0">{if $company_info[i].view_ad_link}</a>{/if}</td>-->
					<td  height="{$thumb_height+10}" width="{$thumb_width+10}" valign="middle" align="center" style=" border: 1px solid #cccccc; ">
						{if $company_info[i].view_ad_link}<a href="{$company_info[i].view_ad_link}">{/if}{if $company_info[i].logo_path !='' && $company_info[i].admin_approve==1}<img alt="" src="{$company_info[i].logo_path}" style="border: none">{/if}{if $company_info[i].view_ad_link}</a>{/if}
					</td>
					<td valign="middle" style="padding-left: 7px;">
						<table cellpadding="0" cellspacing="0">
							<tr>
								<td height="23" colspan="2">{if $company_info[i].company_name !=''}{$company_info[i].company_name}{else}{$company_info[i].login}{/if}</td>
							</tr>
							<tr>
								<td height="23">{if $company_info[i].view_ad_link}<a href="{$company_info[i].view_ad_link}">{$lang.content.rental_ad}</a>{else}{$lang.content.no_ads_from_user}{/if}{if $company_info[i].company_url},&nbsp;{$lang.content.company_url}:&nbsp;<a href='{$company_info[i].company_url}'>{$company_info[i].company_url}</a>{/if}</td>
							</tr>
							<tr>
							
								<td height="23">{if $lists eq 1}<a href="{$company_info[i].del_link}">{$lang.content.del_from_list}</a>&nbsp;|&nbsp;{/if}<a href="#" onclick='javascript: window.open("mailbox.php?sel=chat_start&user_id={$company_info[i].id}", "blank_", "resizable=yes, scrollbars=yes, location=no, directories=no, status=no, width=650, height=768, toolbar=no, menubar=no, left=0,top=0");' >{$lang.content.contact_user}</a>{if $from_admin_mode ne 1}&nbsp;|&nbsp;<noindex><a href="{$company_info[i].contact_link}">{$lang.content.contact_admin}</a></noindex>{/if}</td>
							</tr>
							{if $section_name == "agents"}
							<tr>						
								<td height="23">
								{if $company_info[i].approve == 0}
								{if $company_info[i].inviter == "company"}
									<font class="error">&nbsp;{$lang.content.new_company}</font>&nbsp;<a href="{$company_info[i].approve_link}">{$lang.content.approve}</a>&nbsp;|&nbsp;<a onclick="if (confirm('{$lang.content.decline_offer}')) location.href='{$company_info[i].decline_link}'">{$lang.content.decline}</a>
								{elseif $company_info[i].inviter == "agent"}
									<font class="error">&nbsp;{$lang.content.offer_by_agent}</font>&nbsp;<a href="{$company_info[i].del_agent_offer_link}">{$lang.content.del_company_offer}</a>
								{/if}
								{else}
								{$lang.content.your_realtor}
								&nbsp;<a onclick="if (confirm('{$lang.content.conf_delete_realtor}')) location.href='{$company_info[i].delete_realtor_link}'">{$lang.content.delete_realtor}</a>
								{/if}</td>						
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
		<td><hr class="hr_2"></td>
	</tr>
	</table>
{/section}	
