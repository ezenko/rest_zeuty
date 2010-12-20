{if $from_admin_mode}
	{include file="$admingentemplates/admin_top.tpl"}
	<table cellpadding="0" cellspacing="0" border="0" width="100%">
		<tr>
			<td class="header" align="left">{$lang.menu.realestate} <font class="subheader">| {$lang.menu.realestate} | {$lang.menu.mailbox}</font></td>
		</tr>
		<tr>
			<td><div class="help_text"><span class="help_title">{$lang.default_select.help}</span>{$lang.admin_content.mailbox_help_1}<a href="./admin/admin_rentals.php">{$lang.admin_content.mailbox_help_2}</a>{$lang.admin_content.mailbox_help_3}</div></td>
		</tr>
		<tr>
			<td>
{else}
	{include file="$gentemplates/site_top.tpl"}	
	<table cellpadding="0" cellspacing="0" border="0">
	<tr valign="top">
		<td class="left" valign="top">
			{include file="$gentemplates/homepage_hotlist.tpl"}
		</td>
		<td class="delimiter">&nbsp;</td>
		<td class="main" valign="top">
			<table cellpadding="0" cellspacing="0" border="0" width="100%">
			{if $banner.center}
				<tr>
					<td>
					<!-- banner center -->
				  	
						<div align="left">{$banner.center}</div>
				
					 <!-- /banner center -->
					</td>
				</tr>
			{/if}
				<tr>
					<td valign="top" class="header"><b>{$lang.headers.mailbox}</b></td>
				</tr>
				<tr><td><hr></td></tr>
				<tr>
					<td style="padding-top: 10px;">
{/if}	
<!-- mailbox users' list -->			
						{if $list_user}
						{section loop=$list_user name=m}
						{if $list_user[m].num_messages >0}
						{assign var=user_count value='1'}
						<table cellpadding="0" cellspacing="0" width="100%" border="0" {if !$from_admin_mode}style="margin-left: 15px;"{/if}>
						<tr>
							<td  height="{$thumb_height+10}" width="{$thumb_width+10}" valign="middle" align="center" style=" border: 1px solid #cccccc; ">
							{if $from_admin_mode}
								<a href="{$list_user[m].view_user_link}">
							{else}							
								{if $list_user[m].view_ad_link}<a href="{$list_user[m].view_ad_link}">{/if}
							{/if}	
								<img src="{$list_user[m].pict_path}" style="border: none" alt="">
							{if $from_admin_mode || $list_user[m].view_ad_link}
								</a>
							{/if}	
							</td>
							<td valign="top" style="padding-left: 7px;">
								<table cellpadding="0" cellspacing="0">
									<tr>
										<td height="23" colspan="2">{if $list_user[m].company_name !=''}{$list_user[m].company_name}{else}{$list_user[m].name}{/if},&nbsp;{$lang.content.status_text}:&nbsp;{if $list_user[m].online_status eq 1}<font color="#a50000">{$lang.content.online}</font>{else}{$lang.content.offline}{/if}</td>
									</tr>
									<tr>
										<td height="23">{if $list_user[m].view_ad_link}<a href="{$list_user[m].view_ad_link}">{$lang.content.ads_from_user}</a>{else}{$lang.content.no_ads_from_user}{/if},&nbsp;{$lang.content.date_last_login}:&nbsp;{$list_user[m].date_seen}</td>
									</tr>
									<tr>
										<td>
											<table cellpadding="0" cellspacing="0">
											<tr>

												<td height="23"><a href="#" onclick='javascript: window.open("mailbox.php?sel=chat_start&user_id={$list_user[m].user_id}", "blank_", "resizable=yes, scrollbars=yes, location=no, directories=no, status=no, width=650, height=768, toolbar=no, menubar=no, left=0,top=0");' >{$list_user[m].num_messages}&nbsp;{if $list_user[m].mess_id eq 1}{$lang.content.num_messages_1}{elseif $list_user[m].mess_id eq 2}{$lang.content.num_messages_2}{elseif $list_user[m].mess_id eq 3}{$lang.content.num_messages_3}{/if}</a>{if $list_user[m].new_messages>0}, {$lang.content.new_messages} - <b>{$list_user[m].new_messages}</b>{/if}</td>

												{if $list_user[m].new_messages>0}
												<td>&nbsp;<img src="{$site_root}{$index_theme_path}{$template_images_root}/mail_alert.gif" border="0" vspace="0" hspace="0" alt="{$lang.default_select.unread_messages}"></td>
												{else}
												<td>&nbsp;</td>
												{/if}
											</tr>
											</table>
										</td>
									</tr>
									{if $list_user[m].black_id>0}
									<tr>
										<td height="23">{$lang.content.your_are_in_black}</td>
									</tr>
									{/if}
									{if $list_user[m].user_in_blacklist>0}
									<tr>
										<td height="23">{$lang.content.user_is_in_your_black}</td>
									</tr>
									{/if}
									<tr>
										<td style="padding-top:10px;"><input type="button" class="btn_small" value="{$lang.content.clear_history}" onclick="if (confirm('{$lang.content.are_you_sure}')) document.location.href='{$server}{$site_root}/mailbox.php?sel=clear_history&user_id={$list_user[m].user_id}';"></td>
									</tr>
								</table>
							</td>
						</tr>
						</table>
						<hr {if $from_admin_mode}class="listing"{/if}>	
						{/if}					
						{/section}
						{if !$user_count}
						<font class="error">*&nbsp;{$lang.content.no_contacts}</font>
						{/if}
						{if $links}
						<table cellpadding="2" cellspacing="2" border="0" class="pages_links">
							<tr>
								<td class="text">{$lang.default_select.pages}:
								{foreach item=item from=$links}
								<a href="{$item.link}" {if $item.selected} style="font-weight: bold;"{/if}>{$item.name}</a>
								{/foreach}
								</td>
							</tr>
						</table>
						{/if}
						
						{else}
							<font class="error">*&nbsp;{$lang.content.no_contacts}</font>
						{/if}
<!-- /mailbox users' list -->
{if $from_admin_mode}	
			</td>
		</tr>
	</table>		
	{include file="$admingentemplates/admin_bottom.tpl"}
{else}
						
					</td>
				</tr>
			</table>
		</td>
	</tr>
	</table>
	{include file="$gentemplates/site_footer.tpl"}
{/if}	