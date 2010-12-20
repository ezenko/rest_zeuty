<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<title>{$lang.title}</title>
	<meta http-equiv="Content-Language" content="{$default_lang}">
	<meta http-equiv="Content-Type" content="text/html; charset={$charset}">
	<meta http-equiv="expires" content="0">
	<meta http-equiv="pragma" content="no-cache">
	<link href="{$site_root}{$template_root}{$template_css_root}/style.css" rel="stylesheet" type="text/css" media="all">

</head>
<body>
<table cellpadding="0" cellspacing="0" border="0" align="center" width="100%">
<tr>
	<td>
		<table cellpadding="0" cellspacing="0" border="0" width="100%">
		<tr>
			<td class="header"><b>{$lang.headers.contact_us}</b></td>
		</tr>
		<tr>
			<td style="padding-left: 15px; padding-right: 15px;"><hr></td>
		</tr>
		<tr>
			<td style="padding-left: 15px;">
			<table cellpadding="0" cellspacing="0" border="0" width="100%">
				<tr>
					<td height="{$thumb_height+10}" width="{$thumb_width+10}" valign="middle" align="center" style=" border: 1px solid #cccccc;">
						<img src="{$site_root}{$profile.image}" style="border: none" alt="" >
					</td>
					<td valign="top" style="padding-left: 7px;" rowspan="2">
						<table cellpadding="3" cellspacing="0" border="0" width="100%">
						<tr>
							<td valign="middle"><strong>{$profile.fname}</strong>,&nbsp;
							{if $profile.type eq '1'}{$lang.content.need_realty}
								{elseif $profile.type eq '2'}{$lang.content.have_realty}
								{elseif $profile.type eq '3'}{$lang.content.buy_realty}
								{elseif $profile.type eq '4'}{$lang.content.sell_realty}
							{/if}
							{$profile.realty_type}
							</strong>
							</td>
						</tr>
						{if $profile.country_name || $profile.region_name || $profile.city_name}
						<tr>
							<td>{$profile.country_name}{if $profile.region_name},&nbsp;{$profile.region_name}{/if}{if $profile.city_name},&nbsp;{$profile.city_name}{/if}</td>
						</tr>
						{/if}
						<tr>
							<td>
								{if $profile.type eq 2 || $profile.type eq 4}{$lang.content.month_payment_inline}
								{else}{$lang.content.price}{/if}:&nbsp;
								<strong>{$cur}
								{if $profile.type eq 2 || $profile.type eq 4}{$profile.min_payment}
								{else}{$profile.min_payment}-{$profile.max_payment}{/if}
								</strong>{if $profile.movedate},&nbsp;
								{if $profile.type eq 1 || $profile.type eq 3}{$lang.content.move_in_date}
								{else}{$lang.content.available_date}{/if}:&nbsp;<strong>{$profile.movedate}</strong>{if $profile.type eq 2}&nbsp;<a href='{$site_root}/viewprofile.php?id={$profile.id}&view=calendar'>{$lang.default_select.view_by_calendar}</a>{/if}{/if}
							</td>
						</tr>
						{if $profile.reserve.is_reserved}
							<tr>
								<td>					
									{$lang.content.empty}&nbsp;{$lang.content.time_begin}&nbsp;<strong>{$profile.reserve.reserved_start_period}</strong>&nbsp;{$lang.content.time_end}&nbsp;<strong>{$profile.reserve.reserved_end_period}</strong>
									{if $profile.type eq 2}
									&nbsp;<a href='{$site_root}/viewprofile.php?id={$profile.id}&view=calendar'>{$lang.content.other_period}</a>{/if}
								</td>
							</tr>
						{/if}
						{if $profile.account.phone && (($registered eq 1 && $group_type eq 1) || ($registered eq 1 && $contact_for_free) || $mhi_registration || $contact_for_unreg)}
						<tr>
							<td>{$lang.default_select.call_him}: {$profile.account.phone}</td>
						</tr>
						{elseif ($group_type ne 1 && $registered eq 1)}
						<tr>
							<td>{$lang.default_select.group_err_1}<a href="services.php?sel=group">{$lang.default_select.group_err_2}</a>{$lang.default_select.group_err_3}</td>
						</tr>
						{elseif ($registered eq 0)}
						<tr id="mhi_registration" style="display: {$mhi_registration}">
							<td>{$lang.default_select.contact_for_reg}.&nbsp;<a href="registration.php">{$lang.content.reg_now_text}</a></td>
						</tr>
						{if $profile.headline}
						<tr>
							<td>{$profile.headline}</td>
						</tr>
						{/if}
						{/if}
						</table>
					</td>
					<td width="48" valign="top">
						<table cellpadding="0" cellspacing="0">
							<tr>
							{if !$mhi_services}
								{if $profile.show_topsearch_icon}<td><img alt="{$lang.default_select.star_alt} {$profile.topsearch_date_begin}" title="{$lang.default_select.star_alt} {$profile.topsearch_date_begin}" src="{$site_root}{$template_root}{$template_images_root}/icon_up.png" hspace="1" vspace="0"></td>{/if}
								{if $profile.slideshowed eq 1}<td><img alt="{$lang.default_select.slideshowed_alt}" title="{$lang.default_select.slideshowed_alt}" align="left" src="{$site_root}{$template_root}{$template_images_root}/icon_photoslideshow.png" hspace="1"></td>{/if}
								{if $profile.featured eq 1}<td><img alt="{$lang.default_select.featured_alt}" title="{$lang.default_select.featured_alt}" align="left" src="{$site_root}{$template_root}{$template_images_root}/icon_leader.png" hspace="1"></td>{/if}
							{/if}
								{if $profile.issponsor}
								<td><img alt="{$lang.default_select.sponsored_alt}" title="{$lang.default_select.sponsored_alt}" align="left" src="{$site_root}{$template_root}{$template_images_root}/icon_sponsor.png" hspace="1"></td>
								{/if}
								{if $use_sold_leased_status}
									{if $profile.sold_leased_status && ($profile.type eq '2' || $profile.type eq '4')}
									<td><img alt="{if $profile.type eq '4'}{$lang.default_select.sold_alt}{else}{$lang.default_select.leased_alt}{/if}" title="{if $profile.type eq '4'}{$lang.default_select.sold_alt}{else}{$lang.default_select.leased_alt}{/if}" align="left" src="{$site_root}{$template_root}{$template_images_root}/{if $profile.type eq '4'}icon_sold.png{else}icon_leased.png{/if}" hspace="1"></td>
									{/if}
								{/if}
								<td>&nbsp;</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td width="15">&nbsp;</td>
					<td colspan=""></td>
				</tr>
			</table>
			</td>
		</tr>
		<tr>
			<td style="padding-left: 15px; padding-top: 10px; padding-bottom: 10px; padding-right: 15px;"><hr></td>
		</tr>
		<tr>
			<td style="padding-left: 15px;">
			<form action="{$form.action}" method="POST" name="mailbox_write">
			{$form.hidden}
				<table cellpadding="0" cellspacing="3" border="0" width="100%">
				{foreach item=item from=$error}
				<div style="padding-bottom: 5px; padding-left: 3px;" class="error">*&nbsp;{$item}</div>
				{/foreach}
				<div id="empty_fields" style="padding-bottom: 5px; padding-left: 3px; display: none;" class="error">{$lang.errors.empty_fields}</div>
				<tr>
					<td width="120">{$lang.content.name}: <font class="error">*</font></td>
					<td><input type="text" class="str" size="50" name="name" id="name" value="{$data.name}"></td>
				</tr>
				<tr>
					<td>{$lang.content.email}: <font class="error">*</font></td>
					<td><input type="text" class="str" size="50" name="email" id="email" value="{$data.email}"><br><span class="error" id="user_email_error" style="display: none;">&nbsp;{$lang.errors.invalid_email}</span></td>
				</tr>
				<tr>
					<td>{$lang.content.body}: <font class="error">*</font></td>
					<td><textarea name="body" id="body" cols=50 rows=15>{$data.body}</textarea></td>
				</tr>
				<tr>
					<td>{$lang.content.keystring}: <font class="error">*</font></td>
					<td><img alt="" src="./include/kcaptcha/index.php" style="border: 1px solid #cccccc; padding: 3px;"><br>
						<input type="text" class="str" size="35" name="keystring" id="keystring" style="margin: 0px; margin-top: 4px;">
						</td>								
				</tr>
				<tr><td></td><td><input type="button" class="btn_small" onclick="javascript: {literal} if (FieldsCheck()==true) {document.mailbox_write.submit();} {/literal} " value="{$lang.buttons.send}"></td></tr>
				</table>
			</form>
		</td>
		</tr>
	</td>
</tr>
</table>

{literal}
<script type="text/javascript">
	String.prototype.trim = function()
	{
	    return this.replace(/(^\s*)|(\s*$)/g, "");
	}

	function FieldsCheck() {
		var valid = true;
		if (document.getElementById('email').value !="" && document.getElementById('email').value.search('^.+@.+\\..+$') !=-1) {
			document.getElementById('user_email_error').style.display = 'none';
		} else {
			document.getElementById('user_email_error').style.display = 'inline';
			valid = false;
		}
		if ((document.getElementById('name').value == "") || document.getElementById('body').value == ""){
			document.getElementById('empty_fields').style.display = 'inline';
			valid = false;
		}
		return valid;
	}
</script>
{/literal}

</body>
</html>