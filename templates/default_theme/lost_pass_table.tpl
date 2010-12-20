{include file="$gentemplates/site_top_popup.tpl"}
<table cellpadding="0" cellspacing="0" border="0" width="100%">
<tr><td colspan="2" class="header"><b>{$lang.headers.lost_pass}</b></td></tr>
{if $sended ne 1}
<tr>
	<td width="15">&nbsp;</td>
	<td>{$lang.content.comment}</td>
</tr>
</table>
<br>
<form action="{$form.action}" method="POST" name="lost_pass" id="lost_pass">
{$form.hidden}
<input type=hidden name=send value=1>
<table cellpadding="0" cellspacing="0" border="0" width="100%">
<tr>
	<td width="12">&nbsp;</td>
	<td>
		{foreach item=item from=$error}
			<div class="error">{$item}</div>
		{/foreach}
		<table cellpadding="0" cellspacing="3" border="0" width="100%">
		<tr>
			<td width="100">{$lang.content.email}:</td>
			<td><input type="text" name="email" class="str" size="50"></td>
		</tr>
		<tr><td colspan="2">
			<input type="button" class="btn_small" value="{$lang.buttons.send}" onclick="javascript: document.lost_pass.submit();">
		</td></tr>
		<tr>
			<td colspan="2" style="padding-top: 10px;">
			<a href="./login.php" onclick="javascript: document.location.href='{$form.login_link}';">{$lang.content.link_text_1}</a>
			</td>
		</tr>
		</table>
	</td>
</tr>
</table>
</form>
{else}
</table>
<table cellpadding="0" cellspacing="0" border="0" width="100%">
<tr>
	<td width="15">&nbsp;</td>
	<td>
		{foreach item=item from=$error}
			<div class="error">{$item}</div>
		{/foreach}
		<table cellpadding="0" cellspacing="0" border="0" width="100%">
		<tr>
			<td style="padding-top: 10px;">
			<a href="#" onclick="javascript: document.location.href='{$form.login_link}';">{$lang.content.link_text_1}</a>
			</td>
		</tr>
		<tr>
			<td style="padding-top: 10px;">
			<a href="#" onclick="parent.GB_hide();">{$lang.content.link_text_2}</a>
			</td>
		</tr>
		</table>
	</td>
</tr>
</table>
{/if}
{include file="$gentemplates/site_footer_popup.tpl"}