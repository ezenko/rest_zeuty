{include file="$gentemplates/site_top.tpl"}
    <div id="middle-container">
          <h2>Сочи - о курорте, вступительная статья</h2>

	<table cellpadding="0" cellspacing="0" width="100%" border="0">
	<tr>
		<td align="left">
			<table cellpadding="0" cellspacing="0" width="100%" border="0" height="160" style="background-image: url('{$site_root}{$template_root}{$template_images_root}/realestatemain.jpg'); background-repeat: no-repeat;">
			<tr>
				<td valign="top" class="text" style="padding-top: 25px; padding-left: 33px;">
				{assign var="site_mode_header" value="header_text_site_mode_"|cat:$site_mode|cat:$is_default_theme}
				{$lang.content[$site_mode_header]}</td>
			</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td style="padding: 0px; padding-top: 3px;">
		{include file="$gentemplates/quick_search_main_form.tpl"}
		</td>
	</tr>
	<tr>
		<td height="4px" colspan="3">
		</td>
	</tr>
	<!-- last offers -->
	{if (($area_parametres.show_type != "off") && ($search_result))}
	<tr>
		<td class="subheader"><b>{$lang.content[$area_parametres.show_type]}</b></td>
	</tr>
	{/if}

	{if $area_parametres.view_type eq "row"}
		{include file="$gentemplates/index_last_ads.tpl"}
	{else}
		<tr>
			<td style="padding-top: 10px;">
		{include file="$gentemplates/search_results_users.tpl"}
			</td>
		</tr>
	{/if}
	<!-- /last offers -->

	<tr>
		<td>&nbsp;</td>
	</tr>
	<!--<tr>
		<td><hr style="height:1px; background:none; border:none; border-top:dotted 1px #656565;"></td>
	</tr>-->
	</table>
{literal}
<script type="text/javascript">
var monthLength = new Array(31,28,31,30,31,30,31,31,30,31,30,31);
function checkDate(name) {
		var x = document.quick_search_form.elements;
		var day = parseInt(x[name+"_day"].options[x[name+"_day"].selectedIndex].value);
		var month = parseInt(x[name+"_month"].options[x[name+"_month"].selectedIndex].value);

		d = new Date();
		if (month>12){
			year = d.getYear()+1;
			month = month - 12;
		} else {
			year = d.getYear();
		}
		if (!day || !month || !year)
			return false;
		if (year/4 == parseInt(year/4)) {
			monthLength[1] = 29;
		} else {
			monthLength[1] = 28;
		}
		if (day > monthLength[month-1] || (month == (d.getMonth()+1) && d.getDate()>=day )){
			document.quick_search_form.search_button.disabled = true;
			return 0;
		} else {
			document.quick_search_form.search_button.disabled = false;
			return 1;
		}
	}

	function MyCheck() {
		if (checkDate('move') == 0){
			document.getElementById('move_div').style.display = '';
		} else {
			document.getElementById('move_div').style.display = 'none';
		}
		return true;
	}
</script>
{/literal}
</div>
{include file="$gentemplates/site_footer.tpl"}