{include file="$admingentemplates/admin_top.tpl"}
<table cellpadding="0" cellspacing="0" width="100%"><tr>
	<td valign="top">
		<table cellpadding="0" cellspacing="0" width="100%">
			<tr>
				<td class="header" align="left">{$lang.menu.users} <font class="subheader">| {$lang.menu.payments} | {$lang.menu.payments_history} | {$lang.menu.payments_history} {$lang.content.of_user} {$user.name}</font></td>
			</tr>
			<tr>
				<td><div class="help_text"><span class="help_title">{$lang.default_select.help}</span>{$lang.content.user_history_help}</div></td>
			</tr>
		</table>
		<table cellpadding="0" cellspacing="0" border="0" width="100%">
		<tr>
			<td height="27"><b>{$lang.content.on_account}</b>: {$user.account} {$cur}</td>
		</tr>
		<tr>
			<td height="27"><b>{$lang.content.refresh_date}</b>: {$user.date_refresh}</td>
		</tr>
		<tr>
			<td height="27"><b>{$lang.content.entries}</b>{if !$data} {$lang.content.were_not}{/if}</td>
		</tr>
		{if $data}
			<tr>
				<td>
					<table class="table_main" cellpadding="3" cellspacing="1" border="0" width="100%">
					<tr>
						<th width="1%"><b>{$lang.content.number}</b></th>
						<th><b>{$lang.content.payment_name}</b></th>
						<th><b>{$lang.content.date_send}</b></th>
						<th><b>{$lang.content.count_curr}</b></th>
						<th><b>{$lang.content.paysystem}</b></th>
						<th><b>{$lang.content.status}</b></th>
						<th><b>{$lang.content.pay_approve}</b></th>
					</tr>
					{section name=i loop=$data}
					<tr>
						<td align="center">{$smarty.section.i.index+1}</td>
						<td align="center">{if $data[i].status == "by_admin"}{$lang.content.history_text_3}{elseif $data[i].status == "none"}{$lang.content.history_text_2}{else}{$lang.content.history_text_1}{/if}&nbsp;&#8470;{$data[i].id}</td>
						<td align="center">{$data[i].date_send}</td>
						<td align="center">{$data[i].count_curr}&nbsp;{$data[i].currency}</td>
						<td align="center">{$data[i].paysystem}</td>
						<td align="center">{if $data[i].status eq 'send'}<font class="error">{$lang.content.status_send}</font>{elseif $data[i].status eq 'fail'}<font class="error">{$lang.content.status_fail}</font>{elseif $data[i].status eq 'approve'}{$lang.content.status_approve}{elseif $data[i].status eq 'none'}{$lang.content.status_approve}{else}{/if}</td>
						<td align="center">{if $data[i].status eq 'send'}<input type="button" class="button_2" value="{$lang.buttons.approve}" onclick="document.location.href='{$server}{$site_root}/admin/admin_payment.php?sel=approve_req&amp;id_order={$data[i].number}&amp;redirect=1&amp;id_user={$data[i].id_user}';">&nbsp;<input type="button" class="button_2" value="{$lang.buttons.decline}" onclick="document.location.href='{$server}{$site_root}/admin/admin_payment.php?sel=decline_req&amp;id_order={$data[i].number}&amp;redirect=1&amp;id_user={$data[i].id_user}';">{else}&nbsp;{/if}</td>
					</tr>
					{/section}
					</table>
				</td>
			</tr>
		{/if}

		<tr>
			<td height="27"><b>{$lang.content.spended}</b>{if !$spended} {$lang.content.were_not}{/if}</td>
		</tr>
		{if $spended}
			<tr>
				<td>
					<table class="table_main" cellpadding="3" cellspacing="1" border="0" width="100%">
					<tr>
						<th width="1%"><b>{$lang.content.number}</b></th>
						<th><b>{$lang.content.payment_name}</b></th>
						<th width="10%"><b>{$lang.content.date_send}</b></th>
						<th width="10%"><b>{$lang.content.count_curr}</b></th>
					</tr>
					{section name=i loop=$spended}
					<tr>
						<td align="center">{$smarty.section.i.index+1}</td>
						<td align="center">
						{assign var=lang_spended value="spended_"|cat:$spended[i].id_service}
						{$lang.content[$lang_spended]}
						</td>
						<td align="center">{$spended[i].date_send}</td>
						<td align="center">{$spended[i].count_curr}&nbsp;{$spended[i].currency}</td>
					</tr>
					{/section}
					</table>
				</td>
			</tr>
		{/if}

		</table>
	</td>
	</tr>
</table>
<br><input type="button" class="button_3" value="{$lang.buttons.back}" onclick="document.location.href='{$back_link}';">
{include file="$admingentemplates/admin_bottom.tpl"}