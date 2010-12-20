{include file="$admingentemplates/admin_top.tpl"}
	<table cellpadding="0" cellspacing="0" border="0" width="100%">
	<tr>
		<td class="header" align="left">{$lang.menu.content_management} <font class="subheader">| {$lang.menu.sections_management} | {$lang.menu.show_ads_area}</font></td>
	</tr>
	<tr>
		<td><div class="help_text"><span class="help_title">{$lang.default_select.help}</span>{$lang.content.show_ads_area_help}<br>{$lang.content.invalid_ads_number} {$max_ads_number}.</div></td>
	</tr>
	<tr>
		<td>
		<form name="show_ads_area" action="{$file_name}" method="POST">
		<input type="hidden" name="sel" value="save">
		{foreach from=$show_areas item=area}
		<table cellpadding="0" cellspacing="0" class="form_table" border="0">
			<tr>
				<td colspan="2"><div class="section_title">{$lang.content[$area.area]} ({if $area.for_registered}{$lang.content.for_registered}{else}{$lang.content.for_unregistered}{/if}):</div></td>
			</tr>
			<tr>
				<td>{strip}
				{$lang.content.show_as}:&nbsp;
				<select name="view_type[{$area.id}]">
				{foreach from=$view_type item=vt}
					<option value="{$vt}" {if $vt eq $area.view_type}selected{/if}>{$lang.content[$vt]}
				{/foreach}
				</select>
				</td>
				<td style="padding-left: 5px;">
				{$lang.content.ads_number}:&nbsp;
				<input type="text" name="ads_number[{$area.id}]" id="ads_number[{$area.id}]" value="{$area.ads_number}" size="2" maxlength="2"> {if $err.ads_number[$area.id]}{assign var="err_name" value=$err.ads_number[$area.id]}<font class="error">{$lang.content[$err_name]}{if $err_name=='positive_int_not_more_than'}&nbsp;{$max_ads_number}{/if}</font>{/if}
				{/strip}
				</td>
			</tr>
			{foreach from=$show_type item=st}
			<tr>
				<td colspan="2">
					<table cellpadding="0" cellspacing="0" border="0">
						<tr>
							<td style="padding: 0px;"><input type="radio" name="show_type[{$area.id}]" value="{$st}" {if $area.show_type == $st}checked{/if}></td>
							<td style="padding: 0px;">{$lang.content[$st]}</td>
						</tr>
					</table>
				</td>
			</tr>			
			{/foreach}
			<tr>
				<td colspan="2">
				<input type="button" value="{$lang.buttons.preview}" onclick="javascript: window.open('{$server}{$site_root}/{$area.area}.php?view_from_admin=1{if !$area.for_registered}&for_unreg_user=1{/if}&lang_from_admin={$current_lang_id}');">
				</td>
			</tr>	
		</table>
		{/foreach}
		</td>
	</tr>
	<tr>
		<td style="padding-top: 10px;"><input type="button" value="{$lang.buttons.save}" onclick="javascript: if (CheckAreaSettings()) document.forms.show_ads_area.submit()";></td>
	</tr>
	</table>

{literal}
<script language="javascript">
function CheckAreaSettings() {
	var max_ads_number = {/literal}{$max_ads_number}{literal};
	var error_cnt=0;
	var ids_array = new Array();
	{/literal}{foreach from=$show_areas item=area name=js_sarea key=key}ids_array['{$key}'] = '{$area.area}';{/foreach}{literal}
	ids_cnt = ids_array.length;

	for (i = 0; i<ids_cnt; i++) {
		var id_name = "ads_number[" + (i+1) + "]";
		var number = document.getElementById(id_name).value;
		var number_int = parseInt(number);
		if (number == "" || isNaN(number_int) || number_int < 1 || number_int > max_ads_number) {
			document.getElementById(id_name).value = "";
			document.getElementById(id_name).focus();
			alert("{/literal}{$lang.content.invalid_ads_number}{literal}" + " " + max_ads_number + "!");
			error_cnt++;
		}
	}

	if (error_cnt > 0) {
		return false;
	} else {
		return true;
	}
}
</script>
{/literal}
{include file="$admingentemplates/admin_bottom.tpl"}