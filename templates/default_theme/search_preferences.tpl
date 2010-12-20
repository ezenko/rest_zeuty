{include file="$gentemplates/site_top.tpl"}
<table cellpadding="0" cellspacing="0" border="0">
<tr valign="top">
	<td class="left" valign="top">
		{include file="$gentemplates/homepage_hotlist.tpl"}
	</td>
	<td class="delimiter">&nbsp;</td>
	<td class="main">
		<table cellpadding="0" cellspacing="0" width="100%" border="0">
		{if $banner.center}
		<tr>
			<td>
			<!-- banner center -->
		  	
			<div align="left">
				{$banner.center}
			</div>
		  	
		  	<!-- /banner center -->
	  		</td>
		</tr>
		{/if}
		<tr>
			<td class="header"><b>{$lang.headers.my_search_preferences}</b></td>
		</tr>
		<tr>
			<td>
			<!--PREFERENCES SEARCH CONTENT -->
				<table cellpadding="0" cellspacing="0" width="100%" border="0">
					<tr>
						<td class="subheader"><b>{$lang.headers.my_preferences}</b></td>
					</tr>
					<tr>
						<td style="padding: 10px 0px 10px 16px;">{$lang.content.preferences_help}</td>
					</tr>
					<tr>
						<td>
						<form name="quick_search_form" id="quick_search_form" action="" method="POST">
						<input type="hidden" name="qsform_more_opt" id="qsform_more_opt" value="1">
						{if $primary_location.id_country != 0}
						<input type="hidden" name="country" value="{$primary_location.id_country}">
						<input type="hidden" name="region" value="{$primary_location.id_region}">
						<input type="hidden" name="city" value="{$primary_location.id_city}">
						{/if}
						<table cellpadding="0" cellspacing="0" width="100%" border="0">
							<tr>
								<td style="padding-left: 16px;">
									<table cellpadding="0" cellspacing="0" border="0">
										<tr>
											<td width="155" height="33"><b>{$lang.content.i_want}:&nbsp;</b></td>
											{if !$mhi_ad_sell}
											<td><input type="radio" name="choise" value="4" {if !$search_pref || $search_pref.choise==4}checked{/if}></td>
											<td>{$lang.content.qs_i_buy}&nbsp;&nbsp;&nbsp;</td>
											{/if}
											{if !$mhi_ad_buy}
											<td><input type="radio" name="choise" value="3" {if $search_pref.choise==3}checked{/if}></td>
											<td>{$lang.content.qs_i_sell}&nbsp;&nbsp;&nbsp;</td>
											{/if}
											{if !$mhi_ad_lease}
											<td><input type="radio" name="choise" value="2" {if $search_pref.choise==2}checked{/if}></td>
											<td>{$lang.content.qs_i_need}&nbsp;&nbsp;&nbsp;</td>
											{/if}
											{if !$mhi_ad_rent}
											<td><input type="radio" name="choise" value="1" {if $search_pref.choise==1}checked{/if}></td>
											<td>{$lang.content.qs_i_have}</td>
											{/if}
										</tr>
									</table>
								</td>
							</tr>
							{if $primary_location.id_country != 0}
								<tr>
									<td>
									<table cellpadding="0" cellspacing="0" border="0" class="qsearch" width="100%">
										<tr>
											<td width="155" height="33" style="padding-left: 16px;"><b>{$lang.content.where_loc}:</b></td>
											<td>{$primary_location.country_name}{if $primary_location.region_name}, {$primary_location.region_name}{/if}{if $primary_location.city_name}, {$primary_location.city_name}{/if}&nbsp;&nbsp;&nbsp;({$lang.content.primary_search_location})
											</td>
										</tr>
									</table>
									</td>
								</tr>
							{else}
								<tr>
									<td style="padding-left: 16px;">
										<table cellpadding="0" cellspacing="0" border="0">
										<tr>
											<td width="155" height="33"><b>{$lang.content.where_loc}:</b></td>
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
							{/if}
							<tr>
								<td valign="top" style="padding-left: 16px; padding-right: 65px;">
									<table cellpadding="0" cellspacing="0" border="0">
										<tr>
										<!-- type -->
										{section name=f loop=$realty_type}
											{if $realty_type[f].visible_in ne 3}<!--visibility checking-->
												<td width="155" height="33"><b>{$realty_type[f].name}:</b><input type=hidden name="spr_realty_type[{$realty_type[f].num}]" value="{$realty_type[f].id}"></td>
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
								<td valign="top" style="padding-left: 16px; padding-right: 50px;">
									<table cellpadding="0" cellspacing="0" border="0" >
									<tr>
											<!-- description -->
											{section name=f loop=$description}
											{if $description[f].id == '1'} <!-- only bedrooms -->
												{if $description[f].visible_in ne 3}<!--visibility checking-->
													<td width="155" height="33"><b>{$description[f].name} {$lang.content.min}:</b><input type=hidden name="spr_description[{$description[f].num}]" value="{$description[f].id}"></td>
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
										<td><b>{$lang.content.costs_min}</b>&nbsp;</td>
										<td><input type="text" class="str" name="min_payment" id="min_payment" style="width: 50px;" value="{$search_pref.min_payment}"></td>
										<td style="padding-left: 6px;"><b>{$lang.content.upto}</b>&nbsp;</td>
										<td><input type="text" class="str" name="max_payment" id="max_payment" style="width: 50px;" value="{$search_pref.max_payment}"></td>
										<td>&nbsp;{$cur}</td>
									</tr>
									</table>
								</td>
							</tr>
							<tr>
								<td valign="top" style="padding-left: 16px; padding-right: 50px;">
									<table cellpadding="0" cellspacing="0" border="0">
									<tr>
											<!-- description -->
											{section name=f loop=$description}
											{if $description[f].id == '2' || $description[f].id == '3'} <!-- bathrooms and garage-->
												{if $description[f].visible_in ne 3}<!--visibility checking-->
													<td width="{if $description[f].id == '2'}155{else}75{/if}" height="33"><b>{$description[f].name} {$lang.content.min}:</b><input type=hidden name="spr_description[{$description[f].num}]" value="{$description[f].id}"></td>
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
									</tr>
									</table>
								</td>
							</tr>
							<tr>
								<td valign="top" style="padding-left: 16px; padding-right: 50px;">
									<table cellpadding="0" cellspacing="0" border="0" width="100%">
									<tr>
										<td width="155" height="33"><b>{$lang.content.move_date}:</b></td>
										<td width="160">
										<table cellpadding="0" cellspacing="0" border="0">
											<tr>
												<td>
													<input type="checkbox" name="use_movedate" id="use_movedate" value="1" style="margin-left: 0px;" onclick="javascript: MoveDateStyle();" {if $search_pref.use_movedate}checked{/if}>&nbsp;
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
										<td style="width: 22px;"><input type="checkbox" name="photo" id="photo" value="1"  {if $search_pref.photo}checked{/if} style="margin-left: 0px;"></td>
										<td>{$lang.default_select.with_photo_first}</td>
										<td style="width: 22px;"><input type="checkbox" name="video" id="video" value="1" {if $search_pref.video}checked{/if} style="margin-left: 0px;"></td>
										<td>{$lang.default_select.with_video}</td>
										<!--<td align="right"><input type="button" class="btn" value="{$lang.content.button_search}" name="search_button" id="search_button" onclick="javascript: {literal} if (CheckRangeIntegerFields()){ document.quick_search_form.action='./quick_search.php?sel=from_form&from_file={/literal}{$from_file}{literal}'; document.quick_search_form.submit();} {/literal}"></td>-->
										<td align="right"><input type="button" class="btn" value="{$lang.content.button_search}" name="search_button" id="search_button" onclick="javascript: {literal} if (CheckRangeIntegerFields()){ document.quick_search_form.action='./quick_search.php?sel=search_preferences&from_file={/literal}{$from_file}{literal}'; document.quick_search_form.submit();} {/literal}"></td>
									</tr>
									</table>
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
					<tr>
						<td style="padding-left: 16px; padding-top: 10px;"><a href="{$file_name}?sel=clean_search_preferences">{$lang.content.clean_search_preferences}</a></td>
					</tr>
				</table>
				<!--END OF PREFERENCES SEARCH CONTENT -->
				<table cellpadding="0" cellspacing="0" style="margin-top: 15px;" width="100%">
				<tr>
					<td class="subheader"><b>{$lang.headers.my_search_location}</b></td>
				</tr>
				<tr>
					<td style="padding: 10px 0px 0px 16px;">{$lang.content.primary_comment}</td>
				</tr>				
				<tr>
					<td>
						<table cellpadding="0" cellspacing="0" border="0" style="margin-top: 15px;" width="100%">
						<tr>
							<td style="padding-left: 16px; padding-bottom: 7px;"><b>{$lang.content.preferred_location}:</b></td>
						</tr>
						{foreach from=$search_preferred item=preferred}
						<tr>
							<td {if $preferred.is_primary}class="qsearch"{else}style="padding-left: 16px;"{/if}>
								<table cellpadding="0" cellspacing="0" border="0" class="list_table" {if $preferred.is_primary}style="margin: 7px 0px 7px 16px;"{/if}>
									<tr>
										<td class="list_item">
										<a href="./quick_search.php?search_location_id={$preferred.id}">{$preferred.country_name}{if $preferred.region_name}, {$preferred.region_name}{/if}{if $preferred.city_name}, {$preferred.city_name}{/if}</a>
										</td>
										<td>{if $preferred.is_primary}{$lang.content.is_primary}{else}<a href="{$file_name}?sel=make_primary&id={$preferred.id}">{$lang.content.make_primary}</a>{/if}</td>
										<td><a href="{$file_name}?sel=delete_location&id={$preferred.id}">{$lang.buttons.delete}</a></td>
									</tr>
								</table>
							</td>
						</tr>							
						{foreachelse}
						<tr>
							<td style="padding-left: 16px;">{$lang.content.empty_preferred_list}</td>
						</tr>
						{/foreach}
						</table>
					</td>
				</tr>
				<tr>
					<td>
						<table cellpadding="0" cellspacing="0" border="0" class="list_table" style="margin: 15px 0px 0px 16px;">
						<tr>
							<td colspan="3"><b>{$lang.content.location_history}:</b></td>
						</tr>
						{foreach from=$search_history item=history}
						<tr>
							<td class="list_item">
							<a href="./quick_search.php?search_location_id={$history.id}">{$history.country_name}{if $history.region_name}, {$history.region_name}{/if}{if $history.city_name}, {$history.city_name}{/if}</a>
							</td>
							<td><a href="{$file_name}?sel=make_preferred&id={$history.id}">{$lang.content.make_preferred}</a></td>
							<td><a href="{$file_name}?sel=delete_location&id={$history.id}">{$lang.buttons.delete}</td>
						</tr>
						{foreachelse}
						<tr>
							<td>{$lang.content.empty_location_history}</td>
						</tr>
						{/foreach}
						</table>
					</td>
				</tr>
				{if $search_history}
				<tr>
					<td style="padding-left: 16px; padding-top: 10px;"><a href="{$file_name}?sel=clean_search_history">{$lang.content.clean_search_history}</a></td>
				</tr>
				{/if}
				<tr>
					<td>
						<form name="quick_search_location" action="" method="POST">
						<table cellpadding="0" cellspacing="0" border="0" style="margin: 25px 0px 0px 16px;">
						<tr>
							<td colspan="4" style="padding-bottom: 7px;"><b>{$lang.content.add_to_search_location}:</b></td>
						</tr>
						<tr>
							<td id="country_div_add">
								<select name="country" onchange="javascript: {literal} SelectRegion('sl', this.value, document.getElementById('region_div_add'), document.getElementById('city_div_add'),'{/literal}{$lang.default_select.ip_load_region}{literal}', '{/literal}{$lang.default_select.ip_city}{literal}'); {/literal}" class="location">
									<option value="">{$lang.default_select.ip_country}</option>
									{foreach item=item from=$country}
									<option value="{$item.id}" {if $country_id eq $item.id} selected {/if}>{$item.name}</option>
									{/foreach}
								</select>
							</td>
							<td id="region_div_add">
								<select name="region" onchange="javascript: {literal} SelectCity('sl', this.value, document.getElementById('city_div_add'), '{/literal}{$lang.default_select.ip_load_city}{literal}');{/literal}" class="location">
								<option value="">{$lang.default_select.ip_region}</option>
								{foreach item=item from=$region}
								<option value="{$item.id}"  {if $data.region eq $item.id} selected {/if}>{$item.name}</option>
								{/foreach}
								</select>
							</td>
							<td id="city_div_add">
								<select name="city" class="location">
								<option value="">{$lang.default_select.ip_city}</option>
								{foreach item=item from=$city}
								<option value="{$item.id}" {if $data.city eq $item.id} selected {/if}>{$item.name}</option>
								{/foreach}
								</select>
							</td>
							<td align="right"><input type="button" class="btn" value="{$lang.content.button_search}" name="search_button" onclick="javascript: {literal}document.quick_search_location.action='./quick_search.php?subsel=new_search_location'; document.quick_search_location.submit();{/literal}"></td>
						</tr>
						</table>
						</form>
					</td>
				</tr>
				</table>
			</td>
		</tr>
		</table>
	</td>
</tr>

</table>
{include file="$gentemplates/site_footer.tpl"}

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

function MoveDateStyle(checked){
	if ( checked || document.getElementById("use_movedate").checked == true) {
		document.getElementById("move_day").disabled = false;
		document.getElementById("move_month").disabled = false;
	} else {
		document.getElementById("move_day").disabled = true;
		document.getElementById("move_month").disabled = true;
	}
}
</script>
{/literal}