{include file="$gentemplates/site_top_popup.tpl"}
<table cellpadding="0" cellspacing="0" border="0" width="100%">
<tr><td colspan="2" class="header"><b>{$lang.headers.member_login}</b></td></tr>
</table>
<form action="{$file_name}?from={$from}" method="POST" name="login_form">
{$form.hidden}
<input type="hidden" name="id_mail" id="id_mail" value="{$id_mail}">
<table cellpadding="0" cellspacing="0" border="0" width="100%">
<tr>
	<td width="12">&nbsp;</td>
	<td>
		{foreach item=item from=$error}
			<div class="error">{$item}</div>
		{/foreach}
		{if $from eq 'subscribe'}
		<div class="error">{$lang.content.login_for_edit}</div>
		{/if}
		<table cellpadding="0" cellspacing="3" border="0" width="100%">
		<tr>
			<td width="100">{$lang.content.nickname}:</td>
			<td><input type="text" name="login_lg" size="30" class="str" value="{$login}"></td>
		</tr>
		<tr>
			<td width="100">{$lang.content.password}:</td>
			<td><input type="password" name="pass_lg" size="30" class="str"></td>
		</tr>
		<tr>
			<td width="100">&nbsp;</td>
			<td><input type="checkbox" name="remember_me" value="1"> {$lang.content.remember_me}</td>
		</tr>
		<tr>
			<td width="100" style="padding-top: 15px;">&nbsp;</td>
			<td><input type="submit" class="btn_small" value="{$lang.buttons.submit}"></td>
		</tr>
		<tr>
			<td width="100">&nbsp;</td>
			<td height="20"><a href="{$lost_passw_link}">{$lang.content.lost_password}</a></td>
		</tr>
		<tr>
			<td colspan="2" style="padding-top: 10px;">
				<table cellpadding="0" cellspacing="0" width="100%">
				<tr>
					<td valign="top"><img src="{$site_root}{$template_root}{$template_images_root}/icon_error.png"></td>
					<td class="error">{$lang.content.reg_text}</td>
					<td align="right" valign="bottom"><a href="./registration.php?lang_code={$lang_code}" onclick="parent.location.href='./registration.php?lang_code={$lang_code}'; parent.GB_hide();">{$lang.content.reg_link_text}</a></td>
				</tr>
				</table>
		</tr>
		</table>
	</td>
</tr>
</table>
</form>
{include file="$gentemplates/site_footer_popup.tpl"}