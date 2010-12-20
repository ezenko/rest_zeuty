{include file="$admingentemplates/admin_top.tpl"}
	<table cellpadding="0" cellspacing="0" border="0" width="100%">
	<tr>
		<td class="header" align="left">{$lang.menu.users} <font class="subheader">| {$lang.menu.users} | {$lang.menu.groups_list} | {if $data.name}{$data.name}{else}{$lang.content.add_new_group}{/if} | {$lang.content.permission}</font></td>
	</tr>
	<tr>
		<td><div class="help_text"><span class="help_title">{$lang.default_select.help}</span>{$lang.content.group_permission_help}</div></td>
	</tr>
	<tr>
		<td>
			<form name="permission_form" id="permission_form" action="{$file_name}" method="POST">
			<input type="hidden" name="id_group" value="{$data.id_group}">
			<input type="hidden" name="sel" value="perm_change">
			<input type="hidden" name="type" value="{$data.type}">
			<TABLE cellpadding="0" cellspacing="0" border="0" width="100%">
			<TR><TD style="padding-bottom: 10px;"><b>{$lang.content.group_name}:</b>&nbsp;<input type="text" name="group_name" id="group_name" value="{$data.name}" size="40"></TD></TR>
			{if $data.type == "f"}
			<TR><TD style="padding-bottom: 10px;">
				<table cellpadding="0" cellspacing="0" border="0">
					<tr>
						<td><input type="checkbox" id="allow_trial" name="allow_trial" value="1"{if $data.allow_trial}checked{/if}></td>
						<td>{$lang.content.allow_trial_membership}&nbsp;<input type="text" name="trial_period" id="trial_period" value="{$data.trial_period}" size="4">&nbsp;{$lang.content.days}</td>
					</tr>
				</table>
			</TD></TR>
			{/if}
			<TR><TD>
				<table cellpadding="5" class="table_main" cellspacing="1" border="0" width="100%">
				<tr class="table_header">
					<td width="40%" class="main_header_text" align="center">{$lang.content.module}</td>
					<td width="55%" class="main_header_text" align="center">{$lang.content.description}</td>
					<td width="5%" class="main_header_text" align="center">{$lang.content.allow}</td>
				</tr>
				{section name=p loop=$permission}
				<tr>
					<td class="main_content_text" bgcolor="#FFFFFF">{$permission[p].name}</td>
					<td class="main_content_text" bgcolor="#FFFFFF">{$permission[p].descr}</td>
					<td bgcolor="#FFFFFF" align="center"><input type="checkbox" value="1" id="allowed_{$smarty.section.p.index}" name="allowed[{$permission[p].id}]" {if $permission[p].allowed eq 1} checked {/if} ></td>
				</tr>
				{/section}
				{assign var=total_allowed value=$smarty.section.p.total}
				</table>
			</TD></TR>
			<TR><TD>
				<table cellpadding="0" cellspacing="0" border="0" width="100%">
					<tr>
						<td><input type="button" class="button_3" value="{$lang.buttons.back}" onclick="document.location.href='{$file_name}'"></td>
						<td align="right">
						<input type="button" class="button_3" value="{$lang.content.set_default_perms}" onclick="document.permission_form.sel.value='set_default_perms'; document.permission_form.submit();">
						<input type="button" class="button_3" value="{$lang.buttons.save}" onclick="javascript: if (CheckForm()) document.permission_form.submit();"></td>
					</tr>
				</table>
				</div>
			</TD></TR>
			</TABLE>
			</form>
		</td>
	</tr>
	</table>
{literal}
<script language="JavaScript">
function CheckForm() {
	var is_error = false;
	if (document.getElementById("group_name").value == "") {
		alert("{/literal}{$lang.errors.empty_group_name}{literal}");
		is_error = true;
	}
	if (document.getElementById("allow_trial") && document.getElementById("allow_trial").checked) {
		var trial_period = document.getElementById("trial_period").value;
		if (trial_period == "" || parseInt(trial_period) <= 0) {
			alert("{/literal}{$lang.content.wrong_group_trial_period}{literal}");
			is_error = true;
		}
	}

	is_error = true;
	i=0;
	while (i<{/literal}{$total_allowed}{literal}){
		id = 'allowed_'+i;		
		if (document.getElementById(id).checked){
			is_error = false;
			break;
		}
		i++;
	}

	if (is_error){
		alert("{/literal}{$lang.errors.empty_permission}{literal}");
	}
	if (is_error) {
		return false;
	} else {
		return true;
	}
}
</script>
{/literal}
{include file="$admingentemplates/admin_bottom.tpl"}