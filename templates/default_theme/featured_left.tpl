{if $featured_rent}
<table cellpadding="3" cellspacing="0" border="0" align="center">
<tr>
	<td align="center"><b>{$lang.testimonials_block.featured_rent_head}</b></td>
</tr>
<tr>
	<td align="center">{$lang.testimonials_block.in_region}&nbsp;{$featured_rent.region_name}</td>
</tr>
<tr>
	<td align="center">{if $featured_rent.link}<a href="{$featured_rent.link}{if $view_from_admin==1}&view_from_admin=1{/if}" target="_blank">{/if}<b>{$featured_rent.fname}</b>{if $featured_rent.link}</a>{/if}</td>
</tr>
<tr>
	<td align="center">{if $featured_rent.link}<a href="{$featured_rent.link}{if $view_from_admin==1}&view_from_admin=1{/if}" target="_blank">{/if}<img src="{$featured_rent.upload_path}" style="border-style: solid; border-width: 1px; border-color: #cccccc; padding-top: 3px; padding-bottom: 3px; padding-left: 3px; padding-right: 3px;">{if $featured_rent.link}</a>{/if}</td>
</tr>
<tr>
	<td align="center"><i>{$featured_rent.headline}</i></td>
</tr>
<tr>
	<td align="center">{$lang.testimonials_block.stavka}:&nbsp;<b>{$featured_rent.curr_count}&nbsp;{$cur}</b></td>
</tr>
<tr>
	<td align="center">{$lang.testimonials_block.featured_period}:&nbsp;
	{if $featured_rent.period.days > 0}{$featured_rent.period.days}&nbsp;{$lang.testimonials_block.days}{/if}
	{if ($featured_rent.period.hours > 0) || ($featured_rent.period.days > 0)}{$featured_rent.period.hours}&nbsp;{$lang.testimonials_block.hours}{/if}
	{if ($featured_rent.period.hours > 0) || ($featured_rent.period.days > 0) || ($featured_rent.period.minutes > 0)}{$featured_rent.period.minutes}&nbsp;{$lang.testimonials_block.minutes}{/if}
	{if ($featured_rent.period.hours > 0) || ($featured_rent.period.days > 0) || ($featured_rent.period.minutes > 0) || ($featured_rent.period.seconds > 0)}{$featured_rent.period.seconds}&nbsp;{$lang.testimonials_block.seconds}{/if}
	</td>
</tr>
</table>
{/if}