		</td></tr>
		</table>
	</td>
</tr>
<tr valign="top">
	<td width="100%" height="30" class="empty_line">&nbsp;</td>
</tr>
<tr valign="top">
	<td width="100%" height="5" class="bottom_footer_line">&nbsp;</td>
</tr>
<tr valign="top">
	<td align="center">
		<table cellpadding="10" cellspacing="0" width="{if $page_name == "index"}720{else}948{/if}px" border="0">
		<tr>
			<td width="100%" align="center">
			{section name=m loop=$bottom_menu}
			{if !$smarty.section.m.last}<noindex>{/if}<a class="bottom_menu" href="{$bottom_menu[m].href}">{$bottom_menu[m].value}</a>{if !$smarty.section.m.last}</noindex>{/if}{if !$smarty.section.m.last}&nbsp;&nbsp;&#8212;&nbsp;&nbsp;{/if}
			{/section}
		</td></tr>
		</table>
	</td>
</tr>
<tr>
	<td align="center">
	  <!-- banner bottom -->
	  	{if $banner.bottom}
		<div style="margin-top: 10px; margin-bottom: 10px;" align="center">
			{$banner.bottom}
		</div>
	  	{/if}
	  	<!-- /banner bottom -->
	</td>
</tr>
</table>
</div>
</body>
</html>