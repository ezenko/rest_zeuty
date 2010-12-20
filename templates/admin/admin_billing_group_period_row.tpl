<table width=100% class="main_content_text">
{if $period}
{section name=s loop=$period}
<tr>
<td align=right width="50%">{$period[s].count}&nbsp;{$period[s].period}:</td>
<td align=left width="30%">{$period[s].cost}&nbsp;{$form.currency}</td>
<td align=left width="20%">
<input type="button" class="button_2" value="{$lang.buttons.delete}" onclick="javascript:location.href='{$period[s].del_link}'">
</td>
</tr>
{/section}
{else}
<tr><td align=center>{$lang.content.free}</td></tr>
{/if}
</table>