<form action="{$form.action}" name="cost" method="post">
{$form.hiddens}
	<div class="section_title">{$lang.content.set_group_period_cost}:</div>
	<table border=0 class="table_main" cellspacing=1 cellpadding=5 width="50%">
			<tr class="table_header">
				<td class="main_header_text" align="center">{$lang.content.users_group}</td>
				<td class="main_header_text" align="center">{$lang.content.period}</td>
				<td class="main_header_text" align="center">
				<div style="cursor: pointer;" onclick="javascript:location.href='{$file_name}?sorter=cost&order={$order}';">{$lang.content.pay}{if $sorter=="cost"}{$order_icon}{/if}</div>
				</td>
				<td class="main_header_text" align="center" >{$lang.content.delete}</td>
		  </tr>
			{if $groups}
			{section name=u loop=$groups}
			<!--<tr bgcolor="#ffffff">
				<td class="main_content_text" align="center">{$groups[u].name}</td>
				<td class="main_content_text" align="center">{include file="$admingentemplates/admin_billing_group_period_row.tpl" period=$groups[u].period}</td>
			</tr>-->
				{if $groups[u].period}
				{section name=s loop=$groups[u].period}
				<tr bgcolor="#ffffff">
					<td class="main_content_text" align="center" width="40%">{$groups[u].name}</td>
					<td class="main_content_text" align="center" width="20%">{$groups[u].period[s].count}&nbsp;{$groups[u].period[s].period}</td>
					<td class="main_content_text" align="center" width="20%">{$groups[u].period[s].cost}&nbsp;{$form.currency}</td>
					<td class="main_content_text" align="center" width="20%">
					<input type="button" class="button_2" value="{$lang.buttons.delete}" onclick="javascript:location.href='{$groups[u].period[s].del_link}'">
					</td>
				</tr>
				{/section}
				{else}
				<tr>
					<td class="main_content_text" align="center">{$groups[u].name}</td>
					<td align=center colspan="3">{$lang.content.free}</td>
				</tr>
				{/if}
			{/section}
			{else}
			<tr bgcolor="#ffffff" height="40">
				<td class="main_error_text" align="left" colspan="2">{$lang.content.empty_group}</td>
			</tr>
			{/if}
	</table>

	<div class="section_title">{$lang.content.new_group_period_cost}:</div>
	<table cellpadding=0 cellspacing=0 border="0" class="main_content_text">
		<tr>
			<td>{$lang.content.group}:&nbsp;<select name=group style="width:150px">
			{section name=u loop=$groups}
			<option value="{$groups[u].id}">{$groups[u].name}</option>
			{/section}
			</select>&nbsp;&nbsp;&nbsp;</td>
			<td>{$lang.content.period}:&nbsp;<input type="text" name=count style="width:50px">&nbsp;</td>
			<td>{strip}&nbsp;
				<select name=period style="width:70px">
					<option value="day">{$lang.content.periods_day}</option>
					<option value="week">{$lang.content.periods_week}</option>
					<option value="month">{$lang.content.periods_month}</option>
					<option value="year">{$lang.content.periods_year}</option>
				</select>
				&nbsp;&nbsp;&nbsp;{/strip}
			</td>
			<td>{$lang.content.pay}:&nbsp;<input type="text" name=cost style="width:50px">&nbsp;{$form.currency}&nbsp;</td>
			<td><input type="button" class="button_1" value="{$lang.content.add_period}" onclick="javascript:document.cost.submit();">
			</td>
		</tr>
	</table>
</form>

{literal}
<script language="javascript">
function UpdateAddable(id){
	el = document.all['cost['+id+']'];
	if(el.value.length >0 && isNaN(el.value) == true){
		alert({/literal}'{$err.cost_numeric}'{literal});
		el.value='0';
	}
}
</script>
{/literal}