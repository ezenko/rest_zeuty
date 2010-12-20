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
			<td colspan="2" class="subheader"><b>{$lang.headers.featured}</b></td>
		</tr>
		<tr>
			<td width="15">&nbsp;</td>
			<td style="padding-top: 10px; padding-bottom: 10px;">
			{if $error}<div class="error">*&nbsp;{$error}</div>{/if}
				<div>{$lang.content.featured_top_text}</div>
				<div>{$lang.content.featured_top_text_each} {$featured_in_region_period} {$lang.content.featured_top_text_period} {$cur_symbol} {$featured_in_region_cost}.</div>
			</td>
		</tr>
		<tr>
			<td colspan="2" class="subheader"><b>{$lang.headers.featured_ad}</b></td>
		</tr>
		<tr>
			<td width="12"></td>
			<td>
				<form method="POST" action="{$file_name}" id="feature_form" name="feature_form">
				<input type="hidden" id="sel" name="sel" value="get_featured">
				<input type="hidden" id="id_ad" name="id_ad" value="{$id_ad}">
				<input type="hidden" id="type" name="type" value="{$type}">
				<input type="hidden" id="id_region" name="id_region" value="{$id_region}">
				<table cellpadding="3" cellspacing="0" border="0">
					<tr>
						<td height="27">{$lang.content.featured_text1}</td>
					</tr>
					<tr>
						<td><textarea cols="55" rows="3" id="feature_headline" name="feature_headline"></textarea></td>
					</tr>
					<tr>
						<td>
							<table cellpadding="0" cellspacing="0" border="0">
							<tr>
								<td>{$cur_symbol}&nbsp;<input type="text" id="curr_value" name="curr_value" size="5"></td>
								<td>&nbsp;<input type="button" value="{$lang.content.feature_btn_text}" class="btn_small" onclick="javascript: if (CheckForm('{$on_account}')) document.feature_form.submit(); "></td>
							</tr>
							</table>
						</td>
					</tr>
				</table>
				</form>
			</td>
		</tr>
		</table>
{literal}
<script language="javascript">
function CheckForm(on_user_account, error) {
	var err_cnt = 0;

	var feature_headline = document.getElementById("feature_headline").value;
	if (feature_headline == "") {
		alert('{/literal}{$lang.errors.no_headline}{literal}' + "!");
		err_cnt++;
	}
	var curr_value = document.getElementById("curr_value").value;
	ptn = /^[0-9]+[.0-9]*$/;
	if (ptn.test(curr_value) == false) {
		alert("{/literal}{$lang.errors.not_positive_float_number}{literal}");
		document.getElementById("curr_value").value="";
		document.getElementById("curr_value").focus();
		return false;
	}
	if (curr_value > on_user_account) {
		alert('{/literal}{$lang.errors.bill_is_small}{literal}' + "!");
		err_cnt++;
	}

	if (err_cnt > 0) {
		return false;
	} else {
		return true;
	}
}
</script>
{/literal}