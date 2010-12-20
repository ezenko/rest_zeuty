{include file="$gentemplates/site_top.tpl"}
<table cellpadding="0" cellspacing="0" border="0">
<tr valign="top">
	<td class="left" valign="top">
		{if $account_menu}
			{include file="$gentemplates/account_menu.tpl"}
		{else}
			{include file="$gentemplates/testimonials_post_block.tpl"}
		{/if}
	</td>
	<td class="delimiter">&nbsp;</td>
	<td class="main">
		<!-- banner center -->
	  	{if $banner.center}
			<div align="left">{$banner.center}</div>
		{/if}
		 <!-- /banner center -->
		<table cellpadding="0" cellspacing="0" border="0" width="100%">
			<tr><td class="header" colspan="2"><b>{if $noconfirm}{$lang.headers.alert2}{else}{$lang.headers.alert}{/if}</b></td></tr>
			{if $form.alert_msg}
			<tr><td colspan="2"><hr></td></tr>			
			<tr>
				<td width="1%" style="padding-left:14px;" align="left"><img src="{$site_root}{$template_root}{$template_images_root}/icon_error.png"></td><td class="page_content" align="left" style="padding-left:8px;"><font class="error">{$form.alert_msg}</font></td>
			</tr>
			{/if}
		</table>		
	</td>
</tr>
</table>
{include file="$gentemplates/site_footer.tpl"}