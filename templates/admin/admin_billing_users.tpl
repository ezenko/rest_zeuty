{include file="$admingentemplates/admin_top.tpl"}
<table cellpadding="0" cellspacing="0" border="0" width="100%"><tr>
	<td valign="top">
			<table cellpadding="0" cellspacing="0" width="100%">
				<tr>
					<td class="header" align="left">{$lang.menu.users} <font class="subheader">| {$lang.menu.payments} | {$lang.menu.payments_history}</font></td>
				</tr>
				<tr>
					<td><div class="help_text"><span class="help_title">{$lang.default_select.help}</span>{$lang.content.billing_history_help}</div></td>
				</tr>
			</table>
			<table width="100%" border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td height="30px" align="left">
					{$lang.content.letter_search_help}: {$letter_links}
					</td>
					<td height="30px" align="right">
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
				<!--<tr>
					<th align="center">{$lang.content.number}</th>
					<th align="center">
						<div style="cursor: pointer;" onclick="javascript:location.href='{$file_name}?sorter=1&order={$order}&letter={$letter}&search={$search}&s_type={$s_type}&s_stat={$s_stat}';">{$lang.content.name}</div>
					</th>
					<th align="center">
						<div style="cursor: pointer;" onclick="javascript:location.href='{$file_name}?sorter=2&order={$order}&letter={$letter}&search={$search}&s_type={$s_type}&s_stat={$s_stat}';">{$lang.content.email}</div>
					</th>
					<th align="center">
						<div style="cursor: pointer;" onclick="javascript:location.href='{$file_name}?sorter=3&order={$order}&letter={$letter}&search={$search}&s_type={$s_type}&s_stat={$s_stat}';">{$lang.content.user_type}</div>
					</th>
					<th align="center">
						{$lang.content.payments}
					</th>
				</tr>-->
				<tr>
					<th align="center">{$lang.content.number}</th>
					<th align="center"><div style="cursor: pointer;" onclick="javascript:location.href='{$sorter_link}&sorter=1';">{$lang.content.name}{if $sorter==1}{$order_icon}{/if}</div></th>
					<th align="center"><div style="cursor: pointer;" onclick="javascript:location.href='{$sorter_link}&sorter=2';">{$lang.content.email}{if $sorter==2}{$order_icon}{/if}</div></th>
					<th align="center"><div style="cursor: pointer;" onclick="javascript:location.href='{$sorter_link}&sorter=3';">{$lang.content.user_type}{if $sorter==3}{$order_icon}{/if}</div></th>
					<th align="center">
						{$lang.content.users_group}
					</th>
					<th align="center">
						{$lang.content.payments}
					</th>
				</tr>
				{if $user}
				{section name=u loop=$user}
				<tr>
					<td align="center">{$user[u].number}</td>
					<td align="center">
						{if $user[u].root_user}
							{$user[u].name}
						{else}
							<a href="{$user[u].edit_link}">{$user[u].name}</a>
						{/if}
					</td>
					<td align="center">
						{if $user[u].root_user}
							&nbsp;
						{else}
							{$user[u].email}
						{/if}
					</td>
					<input type="hidden" name="id_user[{$user[u].number}]" value="{$user[u].id}">
					</td>
					<td align="center">
					{if !$user[u].root && !$user[u].guest}
						{if $user[u].user_type eq 1}{$lang.content.user_type_1}
						{elseif $user[u].user_type eq 2}{$lang.content.user_type_2}
						{elseif $user[u].user_type eq 3}{$lang.content.user_type_3}
						{/if}
					{elseif $user[u].root}
						{$lang.content.admin}
					{elseif $user[u].guest}
						{$lang.content.guest}
					{/if}
					</td>
					<td align="center">
						{$user[u].groups}{if $user[u].dates} ({$user[u].dates}){/if}
					</td>
					<td align="center">
						{if $user[u].payments_link}
							<input type="button" class="button_2" value="{$lang.buttons.view}" onclick="{literal}javascript: document.location.href='{/literal}{$user[u].payments_link}{literal}';{/literal}">
						{else}
							{$lang.content.empty_payments}
						{/if}
					</td>
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
			
			{if !$user}
				{if $letter != "*" || $search}
					<div class="message">{$lang.content.empty_result} <a href="{$file_name}?sel={$sel}">{$lang.content.empty_res_search_criteria}</a></div>
				{else}
					<div class="message">{$lang.content.empty_pays}</div>
				{/if}
			{/if}
	</td></tr>
</table>
{include file="$admingentemplates/admin_bottom.tpl"}