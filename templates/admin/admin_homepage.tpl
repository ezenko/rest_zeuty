{include file="$admingentemplates/admin_top.tpl"}
<table cellpadding="0" cellspacing="0" width="100%" border="0">
<tr>
	<td class="header" align="left">{$lang.content.page_header} <font class="subheader">| {$lang.content.page_subheader}</font></td>
</tr>
</table>
<table cellpadding="0" cellspacing="0" width="100%" border="0">
<tr>
	<td valign="top" width="100%">
	<!-- statistics -->
		<table cellpadding="0" cellspacing="0">
		<tr><td class="homepage_title">{$lang.content.site_statistics}</td></tr>
		<tr><td class="text">&nbsp;</td></tr>
		<tr><td class="text">{$lang.content.page_content}</td></tr>
		<tr>
			<td>
				<table class="table_main" cellpadding="5" cellspacing="1">
				<tr bgcolor="#FFFFFF">
					<td class="main_content_text" width="240"><b>{$lang.content.all_users}</b></td>
					<td class="main_content_text" align="center" width="50">{$data.all_users}</td>
				</tr>
				<tr bgcolor="#FFFFFF">
					<td class="main_content_text" style="padding-left: 10px;"><b>{$lang.content.active_users}</b></td>
					<td class="main_content_text" align="center">{$data.active_users}</td>
				</tr>
				<tr bgcolor="#FFFFFF">
					<td class="main_content_text" style="padding-left: 20px;">&nbsp;-&nbsp;{$lang.content.active_users_private}</td>
					<td class="main_content_text" align="center">{$data.active_users_private}</td>
				</tr>
				<tr bgcolor="#FFFFFF">
					<td class="main_content_text" style="padding-left: 20px;">&nbsp;-&nbsp;{$lang.content.active_users_agencies}</td>
					<td class="main_content_text" align="center">{$data.active_users_agencies}</td>
				</tr>
				<tr bgcolor="#FFFFFF">
					<td class="main_content_text" style="padding-left: 20px;">&nbsp;-&nbsp;{$lang.content.active_users_agents}</td>
					<td class="main_content_text" align="center">{$data.active_users_agents}</td>
				</tr>				
				</table>
			</td>
		</tr>
		<tr>
			<td style="padding-top: 15px;">
				<table class="table_main" cellpadding="5" cellspacing="1">
				<tr bgcolor="#FFFFFF">
					<td class="main_content_text" width="240"><b>{$lang.content.all_ads}</b></td>
					<td class="main_content_text" align="center" width="50">{$data.all_ads}</td>
				</tr>
				<tr bgcolor="#FFFFFF">
					<td class="main_content_text" style="padding-left: 10px;"><b>{$lang.content.active_ads}</b></td>
					<td class="main_content_text" align="center">{$data.active_ads}</td>
				</tr>
				<tr bgcolor="#FFFFFF">
					<td class="main_content_text" style="padding-left: 20px;">&nbsp;-&nbsp;{$lang.content.sell_ads}</td>
					<td class="main_content_text" align="center">{$data.sell_ads}</td>
				</tr>
				<tr bgcolor="#FFFFFF">
					<td class="main_content_text" style="padding-left: 20px;">&nbsp;-&nbsp;{$lang.content.buy_ads}</td>
					<td class="main_content_text" align="center">{$data.buy_ads}</td>
				</tr>
				<tr bgcolor="#FFFFFF">
					<td class="main_content_text" style="padding-left: 20px;">&nbsp;-&nbsp;{$lang.content.have_ads}</td>
					<td class="main_content_text" align="center">{$data.have_ads}</td>
				</tr>
				<tr bgcolor="#FFFFFF">
					<td class="main_content_text" style="padding-left: 20px;">&nbsp;-&nbsp;{$lang.content.need_ads}</td>
					<td class="main_content_text" align="center">{$data.need_ads}</td>
				</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td style="padding-top: 15px;">
				<table class="table_main" cellpadding="5" cellspacing="1">
				<tr bgcolor="#FFFFFF">
					<td class="main_content_text" width="240"><b>{$lang.content.mailbox_all}</b></td>
					<td class="main_content_text" align="center" width="50">{$data.mailbox_all}</td>
				</tr>
				<tr bgcolor="#FFFFFF">
					<td class="main_content_text" style="padding-left: 10px;">&nbsp;-&nbsp;{$lang.content.mailbox_from_user_cnt}</td>
					<td class="main_content_text" align="center">{$data.mailbox_from_user_cnt}</td>
				</tr>
				</table>
			</td>
		</tr>
		</table>
	</td>

</tr>
</table>

{include file="$admingentemplates/admin_bottom.tpl"}