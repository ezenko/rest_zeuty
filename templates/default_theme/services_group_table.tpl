		<table cellpadding="0" cellspacing="0" width="100%" border="0">
		{if $banner.center}
		<tr>
			<td colspan="2">
				<!-- banner center -->
			  	
					<div align="left">{$banner.center}</div>
				
				 <!-- /banner center -->
			</td>
		</tr>
		{/if}
		<tr>
			<td colspan="2" class="subheader"><b>{$lang.content.toptext}</b></td>
		</tr>
		<tr>
			<td width="15">&nbsp;</td>
			<td style="padding-top: 10px; padding-bottom: 10px;">
			{if $error}<div class="error">*&nbsp;{$error}</div>{/if}
			<div>{$lang.content.topdescr}</div>
			</td>
		</tr>
		</table>
		<table cellpadding="0" cellspacing="0" width="100%" border="0">
		<tr>
			<td width="10">&nbsp;</td>
			<td>
			<form id="change_group" name="change_group" action="{$form.action}" method="POST">
			{$form.hiddens}
			<table cellpadding="2" cellspacing="3" border="0" width="100%">
			<tr>
				<td valign=top>
				<div>
				<table cellpadding="0" cellspacing="0" border="0">
					<tr>
						<td width="230" height="27">{$lang.content.present_groups}:&nbsp;</td>
						<td><b>{$data.present_group}</b></td>
					</tr>
					{if $period_rest>0}
					<tr>
						<td height="27">{$lang.content.left_days}:&nbsp;</td>
						<td><b>{$period_rest}&nbsp;{if $day_id eq 1}{$lang.default_select.days_1}{elseif $day_id eq 2}{$lang.default_select.days_2}{elseif $day_id eq 3}{$lang.default_select.days_3}{/if}</b>
						</td>
					</tr>
					{/if}
					<tr>
						<td height="27">{$lang.content.available_groups}:&nbsp;</td>
						<td>
							<select name="group" style="width:150"{if $data.guest eq 1} disabled{/if} onchange="javascript: document.forms.change_group.sel.value='group'; document.forms.change_group.submit();">
								<option value="" >{$lang.default_select.pls_select}</option>
								{section name=d loop=$groups}
								<option value="{$groups[d].id}" {if $selected_group eq $groups[d].id} selected {/if}>{$groups[d].name}</option>
								{/section}
							</select>
						</td>
					</tr>
				</table>
				</div>
				</td>
			</tr>

			<tr>
				<td valign=top>
				{if $descr_new}
				<table cellpadding="0" cellspacing="0" border="0">
					<tr>
						<td height="27"><b>{$lang.content.group_functions} &quot;{$data.selected_name}&quot;{$lang.content.new_group_functions}:</b></td>
					</tr>
				</table>
				<table cellspacing="0" cellpadding="5">
				{section name=d loop=$descr_new}
				<tr>
					<td><b>{$smarty.section.d.index+1}.&nbsp;{$descr_new[d].name}{if $descr_new[d].demo}({$lang.content.group_demo}){/if}:</b></td>
					<td>{$descr_new[d].descr}</td>
				</tr>
				{/section}
				</table>
				{/if}

				{if $descr_old}
				<table cellpadding="0" cellspacing="0" border="0">
					<tr>
						<td height="27"><b>{$lang.content.group_functions} &quot;{$data.present_group}&quot; {$lang.content.old_group_functions}:</b></td>
					</tr>
				</table>
				<table cellspacing="0" cellpadding="5">
				{section name=d loop=$descr_old}
				<tr>
					<td><b>{$smarty.section.d.index+1}.&nbsp;{$descr_old[d].name}{if $descr_old[d].demo}({$lang.content.group_demo}){/if}:</b></td>
					<td>{$descr_old[d].descr}</td>
				</tr>
				{/section}
				</table>
				{/if}

				{if $cost_group}
				<table cellpadding="0" cellspacing="0" border="0">
					<tr>
						<td height="27" style="padding-top: 10px;"><b>{$lang.content.cost}:</b></td>
					</tr>
				</table>
				<table cellspacing="0" cellpadding="5" border="0">
					{if $data.allow_trial}
					<tr>
						<td>&nbsp;{$data.trial_period}&nbsp;{$lang.content.days}:&nbsp;</td>
						<td>0&nbsp;{$data.account_currency}</td>
						<td>{if $trial_was_used}{$lang.content.group_trial_was_used}{else}<a href="#" onclick="javascript: document.location.href='{$file_name}?sel=trial_membership&id_group={$data.selected_group_id}';">{$lang.content.use_group_trial_period}</a>{/if}
						</td>
					</tr>
					{/if}
					{section name=d loop=$cost_group}
					<tr>
						<td>&nbsp;{$cost_group[d].amount}&nbsp;{$cost_group[d].period}:&nbsp;</td>
						<td>{$cost_group[d].cost}&nbsp;{$data.account_currency}
						</td>
						<td><a href="#" onclick="javascript: document.change_group.period_id.value='{$cost_group[d].id}'; document.change_group.submit();return false;"><u>{$lang.content.moneyselect}</u></a>
						</td>
					</tr>
					{/section}
				</table>
				{/if}
				</td>
			</tr>
			</table>
			</form>
			</td>
		</tr>
		</table>