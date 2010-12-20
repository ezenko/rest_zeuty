<!--QUICK SEARCH CONTENT -->
<table cellpadding="0" cellspacing="0" width="100%" border="0">
	<tr>
		<td style="height:160px;" background="{$site_root}{$template_root}{$template_images_root}/top_back.gif" valign="top" class="qsearch" id="qsform">
		<form name="quick_search_form" id="quick_search_form" action="" method="POST">
		<input type="hidden" name="qsform_more_opt" id="qsform_more_opt" value="0">
		<table cellpadding="0" cellspacing="0" width="100%" border="0" height="160">
				{if $registered eq 1 && $file_name == "quick_search.php"}
					<tr><td valign="top" style="padding-top: 5px; padding-right: 15px;" align="right" colspan="2"><a href="{$site_root}/power_searchr.php" class="ps_link">{$lang.content.go_to_power_search}</a></td></tr>
				{/if}
				<tr>								
				{if $registered eq 1 && $file_name == "quick_search.php"}
					<td valign="top" style="padding-left: 21px;" colspan="2">
				{else}
					<td valign="top" style="padding-left: 21px; padding-top: 17px;" colspan="2">
				{/if}
					<table cellpadding="0" cellspacing="0" border="0" width="100%">
						<tr>
							<td>
								<table cellpadding="0" cellspacing="0" border="0">
									<tr>
										<td class="text" width="155" height="33"><b>{$lang.content.i_want}:&nbsp;</b></td>
										{if !$mhi_ad_sell}
										<td><input type="radio" name="choise" value="4"  {if !$search_pref || $search_pref.choise==4}checked{/if}></td>
										<td class="text">{$lang.content.qs_i_buy}&nbsp;&nbsp;&nbsp;</td>
										{/if}
										{if !$mhi_ad_buy}
										<td><input type="radio" name="choise" value="3" {if $search_pref.choise==3}checked{/if}></td>
										<td class="text">{$lang.content.qs_i_sell}&nbsp;&nbsp;&nbsp;</td>
										{/if}
										{if !$mhi_ad_lease}
										<td><input type="radio" name="choise" value="2" {if $search_pref.choise==2}checked{/if}></td>
										<td class="text">{$lang.content.qs_i_need}&nbsp;&nbsp;&nbsp;</td>
										{/if}
										{if !$mhi_ad_rent}
										<td><input type="radio" name="choise" value="1" {if $search_pref.choise==1}checked{/if}></td>
										<td class="text">{$lang.content.qs_i_have}</td>
										{/if}																
									</tr>
								</table>	
							</td>
							<td align="right" style="padding-right: 15px;"><input type="button" id="qs_state" value="{$lang.default_select.more_qs_opt}" onclick="ShowHideQSOpt();" class="btn_small"></td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td valign="top" style="padding-left: 21px; padding-right: 65px;" colspan="2">
					<table cellpadding="0" cellspacing="0" border="0">
						<tr>
						<!-- type -->
						{section name=f loop=$realty_type}
							{if $realty_type[f].visible_in ne 3}<!--visibility checking-->
								<td class="text" width="155" height="33"><b>{$realty_type[f].name}:</b><input type=hidden name="spr_realty_type[{$realty_type[f].num}]" value="{$realty_type[f].id}"></td>
								{if $realty_type[f].des_type eq 2}
								<td align="left">
									<select id="realty_type{$realty_type[f].num}" name="realty_type[{$realty_type[f].num}][]"  style="width:150px" {if $realty_type[f].type eq 2}multiple{/if}>
									<option value="" {if !$item.sel} selected {/if} >{$lang.content.choose}</option>
									{foreach item=item from=$realty_type[f].opt}<option value="{$item.value}" {if $item.sel}selected{/if}>{$item.name}</option>{/foreach}
									</select>
								</td>
								{else}
								<td align="left" {if $smarty.section.f.index is not div by 2}bgcolor="#eeeff3"{/if}>
									<table cellpadding="2" cellspacing="0" border="0">
									{section name=s loop=$realty_type[f].opt}
									{if $smarty.section.s.index is div by 4}<tr>{/if}
									<td width="15" height="30"><input type="checkbox" name="realty_type[{$realty_type[f].num}][]" value="{$realty_type[f].opt[s].value}"  {if $realty_type[f].opt[s].sel} checked {/if}></td>
									<td width="130">{$realty_type[f].opt[s].name}</td>
									{if $smarty.section.s.index_next is div by 4 || $smarty.section.s.last}</tr>{/if}
									{/section}
									</table>
								</td>
								{/if}
							{/if}
						{/section}
						<!-- /type -->
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td valign="top" style="padding-left: 21px; padding-right: 50px;" colspan="2">
					<table cellpadding="0" cellspacing="0" border="0">
					<tr>
						<td class="text" width="155" height="33"><b>{$lang.content.where_loc}:</b></td>
						<td id="country_div">
							<select name="country" onchange="javascript: {literal} SelectRegion('ip', this.value, document.getElementById('region_div'), document.getElementById('city_div'),'{/literal}{$lang.default_select.ip_load_region}{literal}', '{/literal}{$lang.default_select.ip_city}{literal}'); {/literal}" class="location">
								<option value="">{$lang.default_select.ip_country}</option>
								{foreach item=item from=$country}
								<option value="{$item.id}" {if $country_id eq $item.id} selected {/if}>{$item.name}</option>
								{/foreach}
							</select>
						</td>
						<td id="region_div">
							<select name="region" onchange="javascript: {literal} SelectCity('ip', this.value, document.getElementById('city_div'), '{/literal}{$lang.default_select.ip_load_city}{literal}');{/literal}" class="location">
							<option value="">{$lang.default_select.ip_region}</option>
							{foreach item=item from=$region}
							<option value="{$item.id}"  {if $data.region eq $item.id} selected {/if}>{$item.name}</option>
							{/foreach}
							</select>
						</td>
						<td id="city_div">
							<select name="city" class="location">
							<option value="">{$lang.default_select.ip_city}</option>
							{foreach item=item from=$city}
							<option value="{$item.id}" {if $data.city eq $item.id} selected {/if}>{$item.name}</option>
							{/foreach}
							</select>
						</td>
					</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td valign="top" style="padding-left: 21px; padding-right: 50px;">
					<table cellpadding="0" cellspacing="0" border="0" >
					<tr>
							<!-- description -->
							{section name=f loop=$description}
							{if $description[f].id == '1'} <!-- only bedrooms -->
								{if $description[f].visible_in ne 3}<!--visibility checking-->
									<td class="text" width="155" height="33"><b>{$description[f].name} {$lang.content.min}:</b><input type=hidden name="spr_description[{$description[f].num}]" value="{$description[f].id}"></td>
									{if $description[f].des_type eq 2}
									<td align="left">
										<select id="description{$description[f].num}" name="description[{$description[f].num}][]"  style="width:150px; margin-right: 10px;" {if $description[f].type eq 2}multiple{/if}>
										<option value="" {if !$item.sel} selected {/if} >{$lang.content.choose}</option>
										{foreach item=item from=$description[f].opt}<option value="{$item.value}" {if $item.sel}selected{/if}>{$item.name}</option>{/foreach}
										</select>
									</td>
									{else}
									<td align="left" {if $smarty.section.f.index is not div by 2}bgcolor="#eeeff3"{/if}>
										<table cellpadding="2" cellspacing="0" border="0">
										{section name=s loop=$description[f].opt}
										{if $smarty.section.s.index is div by 4}<tr>{/if}
										<td width="15" height="30"><input type="checkbox" name="description[{$description[f].num}][]" value="{$description[f].opt[s].value}"  {if $description[f].opt[s].sel} checked {/if}></td>
										<td width="130">{$description[f].opt[s].name}</td>
										{if $smarty.section.s.index_next is div by 4 || $smarty.section.s.last}</tr>{/if}
										{/section}
										</table>
									</td>
									{/if}
								{/if}
							{/if}
							{/section}
							<!-- /description -->
						</td>
						<td class="text"><b>{$lang.content.costs_min}</b>&nbsp;</td>
						<td><input type="text" class="str" name="min_payment" id="min_payment" style="width: 50px;" value="{$search_pref.min_payment}"></td>
						<td style="padding-left: 6px;" class="text"><b>{$lang.content.upto}</b>&nbsp;</td>
						<td><input type="text" class="str" name="max_payment" id="max_payment" style="width: 50px;" value="{$search_pref.max_payment}"></td>
						<td class="text">&nbsp;{$cur}</td>																	
					</tr>
					</table>					
				</td>
				<td align="right" rowspan="3" valign="bottom" style="padding-right: 15px; padding-bottom: 7px;"><input type="button" class="btn" value="{$lang.content.button_search}" name="search_button" id="search_button" onclick="javascript: {literal} if (CheckRangeIntegerFields()){ document.quick_search_form.action='./quick_search.php?sel=from_form&from_file={/literal}{$from_file}{literal}'; document.quick_search_form.submit();} {/literal}"></td>
			</tr>
			{assign var="show_qsopt" value=""}				
								
			<tr >
				<td valign="top" style="padding-left: 21px; padding-right: 50px;">
				<div id="qsopt_1" style="display: none;">
					<table cellpadding="0" cellspacing="0" border="0">
					<tr>
							<!-- description -->
							{section name=f loop=$description}
							{if $description[f].id == '2' || $description[f].id == '3'} <!-- bathrooms and garage-->
								{if $description[f].visible_in ne 3}<!--visibility checking-->
									<td class="text" width="{if $description[f].id == '2'}155{else}75{/if}" height="33"><b>{$description[f].name} {$lang.content.min}:</b><input type=hidden name="spr_description[{$description[f].num}]" value="{$description[f].id}"></td>
									{if $description[f].des_type eq 2}
									<td align="left">
										<select id="description{$description[f].num}" name="description[{$description[f].num}][]"  style="width:150px; margin-right: 10px;" {if $description[f].type eq 2}multiple{/if}>
										<option value="" {if !$item.sel} selected {/if} >{$lang.content.choose}</option>
										{foreach item=item from=$description[f].opt}<option value="{$item.value}" {if $item.sel}selected {assign var="show_qsopt" value="1"}{/if}>{$item.name}</option>{/foreach}
										</select>
									</td>
									{else}
									<td align="left" {if $smarty.section.f.index is not div by 2}bgcolor="#eeeff3"{/if}>
										<table cellpadding="2" cellspacing="0" border="0">
										{section name=s loop=$description[f].opt}
										{if $smarty.section.s.index is div by 4}<tr>{/if}
										<td width="15" height="30"><input type="checkbox" name="description[{$description[f].num}][]" value="{$description[f].opt[s].value}"  {if $description[f].opt[s].sel} checked {assign var="show_qsopt" value="1"}{/if}></td>
										<td width="130">{$description[f].opt[s].name}</td>
										{if $smarty.section.s.index_next is div by 4 || $smarty.section.s.last}</tr>{/if}
										{/section}
										</table>
									</td>
									{/if}
								{/if}
							{/if}
							{/section}
							<!-- /description -->
						</td>						
					</tr>
					</table>
				</div>
				</td>
			</tr>
			<tr>
				<td valign="top" style="padding-left: 21px; padding-right: 50px;">
					<div id="qsopt_2" style="display: none;">
					<table cellpadding="0" cellspacing="0" border="0" width="100%">
					<tr>
						<td class="text" width="155" height="33"><b>{$lang.content.move_date}:</b></td>
						<td width="160">
						<table cellpadding="0" cellspacing="0" border="0">
							<tr>
								<td>
									<input type="checkbox" name="use_movedate" id="use_movedate" value="1" style="margin-left: 0px;" onclick="javascript: MoveDateStyle();" {if $search_pref.use_movedate}checked {assign var="show_qsopt" value="1"}{/if}>&nbsp;
								</td>
								<td>
									<select name="move_month" id="move_month" onchange="javascript: MyCheck();" {if !$search_pref.use_movedate}disabled{/if}>
									{foreach item=item from=$month}<option value="{$item.value}" {if $item.sel}selected{/if}>{$item.name}</option>{/foreach}
									</select>&nbsp;
									<select name="move_day" id="move_day" onchange="javascript: MyCheck();" {if !$search_pref.use_movedate}disabled{/if}>
									{foreach item=item from=$day}<option value="{$item.value}" {if $item.sel}selected{/if}>{$item.value}</option>{/foreach}
									</select>
								</td>
							</tr>
						</table>
						</td>
						<td style="width: 22px;"><input type="checkbox" name="photo" id="photo" value="1" {if $search_pref.photo}checked {assign var="show_qsopt" value="1"}{/if} style="margin-left: 0px;"></td>
						<td class="text">{$lang.default_select.with_photo_first}</td>
						<td style="width: 22px; padding-left: 15px;"><input type="checkbox" name="video" id="video" value="1" {if $search_pref.video}checked {assign var="show_qsopt" value="1"}{/if} style="margin-left: 0px;"></td>
						<td class="text">{$lang.default_select.with_video}</td>						
					</tr>
					</table>
					</div>
				</td>
			</tr>		
			
		</table>
		</form>
		</td>
	</tr>
	<tr>
		<td>
		<div class="qs_error_div" id="min_payment_error" style="display: none;">{$lang.errors.min_payment_error}</div>
		<div class="qs_error_div" id="max_payment_error" style="display: none;">{$lang.errors.max_payment_error}</div>
		<div class="qs_error_div" id="bad_payment_error" style="display: none;">{$lang.errors.price_min_more_max}</div>
		<div class="qs_error_div" id="move_div" style="display: none;">{$lang.errors.incorrect_date}</div>
		</td>
	</tr>
</table>


<!--END OF QUICK SEARCH CONTENT -->

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
	if (day > monthLength[month-1] ){
//		if (day > monthLength[month-1] || (month == (d.getMonth()+1) && d.getDate()>=day )){
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

function CheckRangeIntegerFields( ){
	var id_arr = new Array('payment');
	var reg_expr = new Array();
	//reg_expr['payment'] = '^[1-9]+[0-9]*$';
	reg_expr['payment'] = '^[0-9]*$';

	id_arr_cnt = id_arr.length;
	var error_cnt = 0;

	for (i = 0; i < id_arr_cnt; i++) {
		min_name = "min_" + id_arr[i];
		max_name = "max_" + id_arr[i];
		min_value = document.getElementById(min_name).value;
		max_value = document.getElementById(max_name).value;


		if ((min_value != ""  && min_value != 0) || (max_value != "" && max_value != 0)) {
			if (min_value != ""  && min_value != 0 && min_value.search(reg_expr[id_arr[i]]) ==-1) {
				document.getElementById(min_name + '_error').style.display = '';
				error_cnt++;
			} else {
				document.getElementById(min_name + '_error').style.display = 'none';
			}

			if (max_value != "" && max_value != 0 && max_value.search(reg_expr[id_arr[i]]) ==-1) {
				document.getElementById(max_name + '_error').style.display = '';
				error_cnt++;
			} else {
				document.getElementById(max_name + '_error').style.display = 'none';
			}

			if (max_value != "" && max_value != 0 && parseInt(min_value) >= parseInt(max_value)) {
				document.getElementById('bad_' + id_arr[i]+ '_error').style.display = '';
				error_cnt++;
			} else {
				document.getElementById('bad_' + id_arr[i]+ '_error').style.display = 'none';
			}
		}
	}

	if (error_cnt == 0) {
		return true;
	} else {
		return false;
	}
}

function MoveDateStyle(){
	if (document.getElementById("use_movedate").checked == true) {
		document.getElementById("move_day").disabled = false;
		document.getElementById("move_month").disabled = false;
	} else {
		document.getElementById("move_day").disabled = true;
		document.getElementById("move_month").disabled = true;
	}
}

function ShowHideQSOpt() {
	var to_display = 0;
	var elems_arr = new Array("qsopt_1", "qsopt_2");
	var elems_arr_len = elems_arr.length;
	for (i=0; i<elems_arr_len; i++) {
		var elem = document.getElementById(elems_arr[i]);
		if (elem.style.display == 'inline') {
			elem.style.display = 'none';			
		} else {
			elem.style.display = 'inline';			
			to_display = 1;
		}
	}		
	if (to_display == 1) {
		document.getElementById("qsform").style.height='220px';
		document.getElementById("qsform_more_opt").value='1';
		document.getElementById("qs_state").value='{/literal}{$lang.default_select.less_qs_opt}{literal}';	
	} else {
		document.getElementById("qsform").style.height='160px';
		document.getElementById("qsform_more_opt").value='0';
		document.getElementById("qs_state").value='{/literal}{$lang.default_select.more_qs_opt}{literal}';
	}	
}

{/literal}
{if $data.qsform_more_opt == 1}
	{if $show_qsopt != ""}ShowHideQSOpt();{/if}
{/if}	
{literal}

</script>
{/literal}