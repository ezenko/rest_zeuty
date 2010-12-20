{include file="$admingentemplates/admin_top.tpl"}
<table cellpadding="0" cellspacing="0" width="100%"><tr>
	<td valign="top">
		<table cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td class="header" align="left">{$lang.menu.users} <font class="subheader">| {$lang.menu.payments} | {$lang.menu.payments_write_offs}</font></td>
			</tr>
			<tr>
				<td><div class="help_text"><span class="help_title">{$lang.default_select.help}</span>{$lang.content.list_spended_help}</div></td>
			</tr>
		</table>
		<table width="100%" border="0" cellpadding="0" cellspacing="0">
			<tr>
				<td height="30px" align="left" >
				{$lang.content.letter_search_help}: {$letter_links}
				</td>
				<td height="30px"  align="right" >
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
				<th align="center"><div style="cursor: pointer;" onclick="javascript:location.href='{$sorter_link}&sorter=2';">{$lang.content.u_email}{if $sorter==2}{$order_icon}{/if}</div></th>
				<th align="center"><div style="cursor: pointer;" onclick="javascript:location.href='{$sorter_link}&sorter=3';">{$lang.content.count_curr}{if $sorter==3}{$order_icon}{/if}</div></th>
				<th align="center"><div style="cursor: pointer;" onclick="javascript:location.href='{$sorter_link}&sorter=4';">{$lang.content.currency}{if $sorter==4}{$order_icon}{/if}</div></th>
				<th align="center"><div style="cursor: pointer;" onclick="javascript:location.href='{$sorter_link}&sorter=5';">{$lang.content.service}{if $sorter==5}{$order_icon}{/if}</div></th>
				<th align="center"><div style="cursor: pointer;" onclick="javascript:location.href='{$sorter_link}&sorter=6';">{$lang.content.date_sended}{if $sorter==6}{$order_icon}{/if}</div></th>
        	</tr>
        	{if $empty ne 1}
			{section name=p loop=$pays}
			<tr>
				<td align="center">{$pays[p].number}</td>
				<td align="center">{$pays[p].user_fname}&nbsp;{$pays[p].user_sname}</td>
				<td align="center">{$pays[p].user_email}</td>
				<td align="center">{$pays[p].count_curr}</td>
				<td align="center">{$pays[p].currency}</td>
				<td align="center">{$pays[p].id_service}</td>
				<td align="center">{$pays[p].date_send_show}</td>
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
		</td>
	</tr>
</table>
{include file="$admingentemplates/admin_bottom.tpl"}