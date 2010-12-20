{include file="$admingentemplates/admin_top.tpl"}
	<table cellpadding="0" cellspacing="0" border="0" width="100%">
	<tr>
		<td class="header" align="left">{$lang.menu.realestate} <font class="subheader">| {$lang.menu.realestate} | {$lang.menu.sponsors_announcements} | {$user_login} | {$lang.content.rental_ads}</font></td>
	</tr>
	<tr>
		<td><div class="help_text"><span class="help_title">{$lang.default_select.help}</span>{$lang.content.help_add_ads}</div></td>
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
	<tr>
	<td>
	<form name="ads_form" id="ads_form" action="" method="POST">
			<table border=0 class="table_main" cellspacing=1 cellpadding=3 width="100%" style="margin-bottom: 0px;">
				<tr class="table_header">
					<td class="main_header_text" align="center" width="3%">{$lang.content.number}</td>
					<td class="main_header_text" align="center" >
						{$lang.content.preview}
					</td>
					<td class="main_header_text" align="center" width="10%">
						{$lang.content.issponsor}
					</td>
				</tr>
				{assign var=page_pr value=$page-1}
				{if $ads}
				{section name=u loop=$ads}
				<tr>
					<td class="main_content_text" align="center" bgcolor="#FFFFFF"><!--{$user[u].number}-->{$ads_numpage*$page_pr+$smarty.section.u.index+1}</td>
					<td align="center" bgcolor="#FFFFFF">
						<table cellpadding="0" cellspacing="0" border="0" align="left" width="70%">
						<tr>
							<td valign="middle" align="center" height="{$thumb_height+10}" width="{$thumb_width+10}" style=" border: 1px solid #cccccc; ">
								{if $ads[u].photo_id}
								{section name=ph loop=$ads[u].photo_id max=1}
									<img src="{$ads[u].thumb_file[ph]}" style="padding: 3px; cursor: pointer;" alt="" onclick="document.location.href='{$ads[u].viewprofile_link}';">
								{/section}
								{else}								
								
								<img src="{$ads[u].thumb_file[0]}" style="padding: 3px; cursor: pointer;" alt="" onclick="document.location.href='{$ads[u].viewprofile_link}';">
								{/if}	
							</td>
							<td valign="top" align="left" >
								<table>
								<tr>
									<td align="left" style="padding-left: 11px;">
										<a href="{$ads[u].viewprofile_link}">{$user_login}</a>,&nbsp;
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
										{if $ads[u].type eq 1 || $ads[u].type eq 2}{$lang.content.month_payment_in_line}{else}{$lang.content.price}{/if}:&nbsp;<strong>
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
				<input type="hidden" name="id_ad[{$smarty.section.u.index}]" value="{$ads[u].id}">
					<input type="hidden" name="id_user" value="{$id_user}">		
					</td>
					<td class="main_content_text" align="center" bgcolor="#FFFFFF"><input type="checkbox" name="issponsor[{$ads[u].id}]" value="1" {if ($ads[u].issponsor > -1)}checked{/if}></td>					
				</tr>
				{/section}
				{/if}
				<tr>
					<td colspan="2">&nbsp;</td>
					<td align="center"><input type="button" class="button_3" value="{$lang.buttons.save}" onclick="document.ads_form.action='{$file_name}?sel=list_sp_edit'; document.ads_form.submit();"></td>	
				</tr>
			</table>
			</form>
			{if !$ads}
				<div class="message">{$lang.content.empty_pays}</div>
			{/if}
		</td>
	</tr>
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
	<br><input type="button" class="button_3" value="{$lang.buttons.back}" onclick="document.location.href='{$file_name}?page={$pageR}';">
{include file="$admingentemplates/admin_bottom.tpl"}