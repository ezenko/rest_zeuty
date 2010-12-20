{include file="$gentemplates/site_top.tpl"}
<table cellpadding="0" cellspacing="0" border="0" width="100%">
<tr valign="top">
	<td class="left">
		{include file="$gentemplates/homepage_errorlist.tpl"}
	</td>
	<td class="delimiter">&nbsp;</td>
	<td class="main">
		<table width="100%" border="0" cellpadding="0" cellspacing="0">
		{if $banner.center}
			<tr>
				<td colspan="2">
				<!-- banner center -->
			  	
					<div align="left">{$banner.center}</div>
				
				 <!-- /banner center -->
				</td>
			</tr>
		{/if}	
			<tr valign="top">
				<td class="header" colspan="2"><b>{$lang.headers.error}</b></td>
			</tr>
			<tr>
			<td colspan="2"><hr></td></tr>
			<tr>
				<td width="1%" style="padding-left:14px;" align="left"><img src="{$site_root}{$template_root}{$template_images_root}/icon_error.png"></td><td class="page_content" align="left" style="padding-left:8px;"><font class="error">{$error}</font></td>
			</tr>
		</table>
	</td>
</tr>
</table>
{include file="$gentemplates/site_footer.tpl"}