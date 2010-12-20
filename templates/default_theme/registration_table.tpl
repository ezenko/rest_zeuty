{include file="$gentemplates/site_top.tpl"}
<table cellpadding="0" cellspacing="0" border="0">
<tr>
	<td class="left" valign="top">
		{include file="$gentemplates/testimonials_post_block.tpl"}
		<!--<table cellpadding="0" cellspacing="0" border="0">
			{include file="$gentemplates/comparison_menu.tpl"}
		</table>-->
	</td>
	<td class="delimiter">&nbsp;</td>
	<td class="main">
		<form method="POST" action="registration.php?sel=register" name="reg_form" id="reg_form" enctype="multipart/form-data">
		<input type="hidden" id="from" name="from" value="{$data.from}">
			<table cellpadding="0" cellspacing="0" border="0" width="100%">
			{if $banner.center}
			<tr>
				<td colspan="2">
				<!-- banner center -->
			  	
					<div align="left">{$banner.center}</div>
				
				 <!-- /banner center -->
				</td>
			</tr>
			{/if}
			<tr><td colspan="2" class="header"><b>{$lang.headers.registration}</b></td></tr>
			<tr>
				<td width="15">&nbsp;</td>
				<td>{$lang.content.text_header}</td>
			</tr>
			</table>
			<hr>
			<table cellpadding="0" cellspacing="0" border="0" width="100%">
			<tr><td colspan="2" class="subheader"><b>{$lang.headers.account_profile}</b></td></tr>
			<tr><td id="top_nbsp" colspan="2" style="display: none;">&nbsp;</td></tr>
			<tr>
				<td width="12">&nbsp;</td>
				<td style="padding-left: 3px;">
					<div id="top_error_fname" style="display: none;" class="error">{$lang.errors.invalid_first_name}</div>
					<div id="top_error_sname" style="display: none;" class="error">{$lang.errors.invalid_last_name}</div>
					<div id="email_exists_err" style="display: none;" class="error">{$lang.errors.email_exist}</div>
					<div id="top_error_email" style="display: none;" class="error">{$lang.errors.invalid_email}</div>
					<div id="top_error_veremail" style="display: none;" class="error">{$lang.errors.invalid_ver_email}</div>
					<div id="top_error_pass" style="display: none;" class="error">{$lang.errors.invalid_pass}</div>
					<div id="top_error_verpass" style="display: none;" class="error">{$lang.errors.invalid_ver_pass}</div>
					<div id="top_error_usertype" style="display: none;" class="error">{$lang.errors.invalid_user_type}</div>
					<div id="top_error_empty_fields" style="display: none;" class="error">{$lang.errors.empty_fields}</div>
					<div id="top_error_na_agency" style="display: none;" class="error">{$lang.errors.na_agency}</div>
				</td>
			</tr>
			<tr>
				<td width="12">&nbsp;</td>
				<td>
					{foreach item=item from=$error}
						<div style="padding-top: 10px; padding-left: 3px;" class="error">{$item}</div>
					{/foreach}
					<table cellpadding="3" cellspacing="0" border="0" width="100%">
					<tr>
						<td width="25%">{$lang.content.first_name}:&nbsp;<font class="error">*</font></td>
						<td width="30%"><input type="text" class="str" size="35" name="first_name" id="first_name" value="{$data.first_name}" onblur="javascript: CheckEmptyValue(this);">
						<span class="error" id="fname_err" style="display: none;">*</span>
						</td>
						<td align="left" width="45%">&nbsp;</td>
					</tr>
					<tr>
						<td>{$lang.content.last_name}:&nbsp;<font class="error">*</font></td>
						<td><input type="text" class="str" size="35" name="last_name" id="last_name" value="{$data.last_name}" onblur="javascript: CheckEmptyValue(this);">
						<span class="error" id="sname_err" style="display: none;">*</span>
						</td>
						<td align="left">&nbsp;</td>
					</tr>
					<tr>
						<td>{$lang.content.email}:&nbsp;<font class="error">*</font></td>
						<td><input type="text" class="str" size="35" name="email" id="email" value="{$data.email}" onblur="javascript: CheckEmptyValue(this);">
						<span class="error" id="email_err" style="display: none;">*</span>
						</td>
						<td align="left" class="hint" rowspan="2">{$lang.content.hint_email}</td>
					</tr>
					<tr>
						<td>{$lang.content.verify_email}:&nbsp;<font class="error">*</font></td>
						<td><input type="text" class="str" size="35" name="ver_email" id="ver_email" value="{$data.ver_email}" onblur="javascript: CheckEmptyValue(this);">
						<span class="error" id="veremail_err" style="display: none;">*</span>
						</td>
					</tr>
					<tr>
						<td>{$lang.content.phone}:</td>
						<td><input type="text" class="str" size="35" name="phone" id="phone" value="{$data.phone}"></td>
						<td align="left">&nbsp;</td>
					</tr>
					<tr>
						<td>{$lang.content.birth_date}:</td>
						<td>
							<select name="birth_day">
							{foreach item=item from=$day}<option value="{$item.value}" {if $item.sel}selected{/if}>{$item.value}</option>{/foreach}
							</select>
							<select name="birth_month">
							{foreach item=item from=$month}<option value="{$item.value}" {if $item.sel}selected{/if}>{$item.value}</option>{/foreach}
							</select>
							<select name="birth_year">
							{foreach item=item from=$year}<option value="{$item.value}" {if $item.sel}selected{/if}>{$item.value}</option>{/foreach}
							</select>
						</td>
						<td align="left" class="hint">{$lang.content.hint_birth}</td>
					</tr>
					<tr>
						<td>{$lang.content.create_pass}:&nbsp;<font class="error">*</font></td>
						<td><input type="password" class="str" size="35" name="pass" id="pass" onblur="javascript: CheckEmptyValue(this);">
						<span class="error" id="pass_err" style="display: none;">*</span>
						</td>
						<td align="left" class="hint" rowspan="2">{$lang.content.hint_pass}</td>
					</tr>
					<tr>
						<td>{$lang.content.verify_pass}:&nbsp;<font class="error">*</font></td>
						<td><input type="password" class="str" size="35" name="ver_pass" id="ver_pass" onblur="javascript: CheckEmptyValue(this);">
						<span class="error" id="verpass_err" style="display: none;">*</span>
						</td>
					</tr>
					<tr>
						<td>{$lang.content.registration_pin}:&nbsp;<font class="error">*</font></td>
						<td><img alt="" src="./include/kcaptcha/index.php" style="border-style: solid; border-width: 1px; border-color: #cccccc; padding-top: 3px; padding-bottom: 3px; padding-left: 3px; padding-right: 3px;"><br>
							<input type="text" class="str" size="35" name="keystring" id="keystring" style="margin: 0px; margin-top: 6px;"></td>
						<td align="left">&nbsp;<input type="hidden" id="user_type_copy" name="user_type_copy" value="{$data.user_type_copy}"></td>
					</tr>
					
					<tr>
						<td valign="top" style="padding-top:6px;"><b>{$lang.content.i_reg_as}</b>:&nbsp;<font class="error">*</font></td>						
						<td colspan="2">
							<table cellpadding="0" cellspacing="0" border="0" width="100%" class="reg_user_type">			
							<tr>
								<td width="1%"><input type="radio" name="user_type" value="1" {if $data.user_type_copy eq 1} checked {/if} onclick="DivVision(this.value); DialogShow(this.value); CompanyShow(this.value); "></td>
								<td>{$lang.content.private_person}&nbsp;&nbsp;<span id="user_type_err" class="error" style="display: none;">&nbsp;*</span></td>									
							</tr>
											
							<tr>
								<td><input type="radio" name="user_type" value="2" {if $data.user_type_copy eq 2} checked {/if} onclick="DivVision(this.value); DialogShow(this.value); CompanyShow(this.value); "></td>
								<td>{$lang.content.agency}</td>									
							</tr>									
							<tr id="company_block" style="display: none;">													
								<td colspan="2" style="padding-top: 5px; padding-bottom: 5px;">
								<table cellpadding="0" cellspacing="0" class="reg_user_type_info" width="100%">
									<tr>										
										<td>
										<table cellpadding="0" cellspacing="0" border="0" class="user_type_info">
											<tr>
												<td width="100">{$lang.content.company}:</b></td>
												<td>
													<input type="text" class="str" id="company_name" name="company_name" onblur="javascript: CheckEmptyValue(this);"><span class="error" id="company_name_err" style="display: none;">&nbsp;*</span>
												</td>
											</tr>
											<tr>
												<td>{$lang.content.country}:</td>
												<td id="country_div">
													<select name="country" onchange="javascript: {literal} SelectRegion('ip', this.value, document.getElementById('region_div'), document.getElementById('city_div'),'{/literal}{$lang.default_select.ip_load_region}{literal}', '{/literal}{$lang.default_select.ip_city}{literal}'); {/literal}" class="location">
														<option value="">{$lang.default_select.ip_country}</option>
														{foreach item=item from=$country}
														<option value="{$item.id}" {if $country_id eq $item.id} selected {/if}>{$item.name}</option>
														{/foreach}
													</select>
												</td>
											</tr>
											<tr>
												<td>{$lang.content.region}:</td>
												<td id="region_div">
													<select name="region"  onchange="javascript: {literal} SelectCity('ip', this.value, document.getElementById('city_div'), '{/literal}{$lang.default_select.ip_load_city}{literal}');{/literal}" class="location">
													<option value="">{$lang.default_select.ip_region}</option>
													{foreach item=item from=$region}
													<option value="{$item.id}"  {if $data.region eq $item.id} selected {/if}>{$item.name}</option>
													{/foreach}
													</select>
												</td>
											</tr>
											<tr>
												<td>{$lang.content.city}:</td>
												<td id="city_div">
													<select name="city"  class="location">
													<option value="">{$lang.default_select.ip_city}</option>
													{foreach item=item from=$city}
													<option value="{$item.id}" {if $data.city eq $item.id} selected {/if}>{$item.name}</option>
													{/foreach}
													</select>
												</td>
											</tr>
											<tr>
												<td>{$lang.content.address}:</td>
												<td colspan="2">
													<input type="text" class="str" name="address"></input>
												</td>
											</tr>
											<tr>
												<td>{$lang.content.post_code}:</td>
												<td colspan="2">
													<input type="text" class="str" name="postal_code"></input>
												</td>
											</tr>
										</table>									
										</td>
									</tr>
								</table>								
								</td>
							</tr>	
							{if $use_agent_user_type}							
							<tr>
								<td><input type="radio" name="user_type" value="3" {if $data.user_type_copy eq 3} checked {/if} onclick="DivVision(this.value); DialogShow(this.value); CompanyShow(this.value); "></td>
								<td align="left">{$lang.content.agent_of_agency}</td>
							</tr>	
							{/if}	
							<tr id="choose_agency" style="display: none;">				
								<td colspan="2" style="padding-top: 5px; padding-bottom: 5px;">
									<table cellpadding="0" cellspacing="0" border="0" style="margin-bottom: 10px;">
									<tr>
										 <td class="reg_user_type_info">
											 <table cellpadding="0" cellspacing="0" border="0" class="user_type_info">
												<tr>
													<td width="1%" align="left" id="id_input_agency"><input type="text" size="35" id="agency_name" name="agency_name" class="str" onblur="javascript: CheckEmptyValue(this);"></td>
													<td align="left" id="id_choose_agency" style="padding-left:4px;"><a onclick="javascript: return OpenParentWindow('{$file_name}?sel=choose_company');">{$lang.content.choose_company}</a></td>	
												</tr>
												<tr>
													<td colspan="2" id="hint_3">
													{$lang.content.hint_3}
													<input type="hidden" name="id_company" id="id_company" value="{$data.id_agency}">
													</td>
												</tr>
											</table>
										 </td>
									</tr>
									</table>
								</td>
							</tr>
							
							</table>
						</td>
					</tr>	
								
					<tr>
						<td colspan="3" style="padding-top: 0px;">
							<table cellpadding="0" cellspacing="0" border="0">
							<tr>
								<td>
									<div style="height: 35px; {if !$data.dialog_value_1} display: none; {/if}" id="dialog_div_1">
									<table cellpadding="0" cellspacing="0" border="0">
										<tr>
											<td>{$lang.content.dialog_text_1}:&nbsp;&nbsp;</td>
											<td>
												<select name="dialog_1" id="dialog_1" onchange="Dialog2Show(this.value);">
													<option value="">{$lang.content.not_selected}</option>
													<option value="4" {if $data.dialog_value_1 eq 3} selected {/if}>{$lang.content.sell}</option>
													<option value="3" {if $data.dialog_value_1 eq 4} selected {/if}>{$lang.content.buy}</option>
													<option value="2" {if $data.dialog_value_1 eq 1} selected {/if}>{$lang.content.dialog_text_2}</option>
													<option value="1" {if $data.dialog_value_1 eq 2} selected {/if}>{$lang.content.dialog_text_3}</option>
												</select>&nbsp;&nbsp;
											</td>
											<td id="dialog_td_2_1" {if $data.user_type_copy eq 2} style='display: none;' {/if}>
												<select name="dialog_2_1" id="dialog_2_1">
													<option value="">{$lang.content.not_selected}</option>
													{foreach from=$property_types item=type}
														<option value="{$type.value}">{$type.name}</option>
													{/foreach}
												</select>&nbsp;&nbsp;
											</td>
											<td id="dialog_td_2_2" {if $data.user_type_copy ne 2} style='display: none;' {/if}>
											  <select name="dialog_2_2" id="dialog_2_2">
													<option value="">{$lang.content.not_selected}</option>
													{foreach from=$property_types item=type}
														<option value="{$type.value}">{$type.name}</option>
													{/foreach}
												</select>&nbsp;&nbsp;
											</td>											
										</tr>
									</table>
									</div>
								</td>
							</tr>
							</table>
						</td>
					</tr>
					
						
					</table>
				</td>
			</tr>
			<tr>
				<td width="12">&nbsp;</td>
				<td>
					<table cellpadding="0" cellspacing="0" border="0"  style="padding-top:0px;margin-left: 3px;margin-top:0px;">
					<tr>
						<td valign="top"  style="padding-right:2px;"><input type="checkbox" value="1" name="show_info" id="show_info" {if $data.show_info eq 1} checked {/if}></td>
						<td>{$lang.content.show_info}</td>
					</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td width="12">&nbsp;</td>
				<td>
					<table cellpadding="0" cellspacing="3" border="0">
					<tr>
						<td valign="top"><input type="checkbox" value="1" name="agree" id="agree"></td>
						<td>{$lang.content.agreement_1}<a href="./info.php?id=10" target="_blank">{$lang.content.agreement_2}</a>{$lang.content.agreement_3}</td>
					</tr>
					</table>
				</td>
			</tr>
			<tr><td colspan="2">&nbsp;</td></tr>
			<tr><td colspan="2" class="subheader"><b>{$lang.headers.account_subscribtion}</b></td></tr>
			<tr>
				<td width="12">&nbsp;</td>
				<td>
					<table cellpadding="0" cellspacing="3" border="0">
					{foreach item=item from=$alerts}
					<tr>
						<td><input type="checkbox" value="{$item.id}" name="alert['{$item.id}']"></td>
						<td>{$item.name}</td>
					</tr>
					{/foreach}
					</table>
				</td>
			</tr>
			<tr><td colspan="2">&nbsp;</td></tr>
			<tr><td colspan="2" style="padding-left: 17px"><input class="btn_small" type="button" name="submit1" id="submit1" value="{$lang.buttons.register}" onclick="{literal}javascript: if (CheckOnAgreeTerms() && CheckValidCompany()) { ClearErrors(); if (CheckRegisterForm()==true) {document.reg_form.submit();}} {/literal}"></td></tr>
			</table>
		</form>
	</td>
</tr>
</table>
{include file="$gentemplates/site_footer.tpl"}
{literal}
<script type="text/javascript">

String.prototype.trim = function() {
    return this.replace(/(^\s*)|(\s*$)/g, "");
}

var doc = null;
function ajax() {
	// Make a new XMLHttp object
	if (typeof window.ActiveXObject != 'undefined' ) doc = new ActiveXObject("Microsoft.XMLHTTP");
	else doc = new XMLHttpRequest();
}

function CheckValidCompany(){
	if (document.getElementById('user_type_copy').value == 3){
		if (document.getElementById('agency_name').value.trim() != '' ){
			if (document.getElementById('id_company').value == 0){
				alert("{/literal}{$lang.content.enter_valid_company}{literal}");
				return 0;
			}else{
				return 1;
			}
		}else{
			return 1;
		}
	}else{
		return 1;
	}
}	
	
function CheckOnAgreeTerms(){
	if (document.getElementById('agree').checked == true ){		
		return 1;
	}else{
		alert('{/literal}{$lang.content.need_agree}{literal}');
		return 0;
	}
}

function ClearErrors(){
		document.getElementById('top_error_fname').style.display = 'none';
		document.getElementById('top_error_sname').style.display = 'none';
		document.getElementById('top_error_email').style.display = 'none';
		document.getElementById('top_error_veremail').style.display = 'none';
		document.getElementById('top_error_pass').style.display = 'none';
		document.getElementById('top_error_verpass').style.display = 'none';
		document.getElementById('top_error_usertype').style.display = 'none';
		document.getElementById('top_error_empty_fields').style.display = 'none';
		document.getElementById('top_error_na_agency').style.display = 'none';
		document.getElementById('top_nbsp').style.display = 'none';
		return;
}
function CheckEmptyValue(obj){	
	
	if(obj){
		bp = document.reg_form;		
		if(obj.name=='pass' && (obj.value.length>=4 && obj.value.length<=10)){
			document.getElementById('pass_err').style.display = 'none';
		}
		if(obj.name=='ver_pass' && ( obj.value !="" && obj.value == bp.pass.value) ){
			document.getElementById('verpass_err').style.display = 'none';			
		}
		if(obj.name=='first_name' && obj.value != ""){
			document.getElementById('fname_err').style.display = 'none';
		}
		if(obj.name=='last_name' && obj.value != ""){
			document.getElementById('sname_err').style.display = 'none';			
		}
		if(obj.name=='company_name' && obj.value != ""){
			document.getElementById('company_name_err').style.display = 'none';			
		}
		if(obj.name=='email' && ( obj.value != "" && obj.value.search('^.+@.+\\..+$') !=-1) ){
			document.getElementById('email_err').style.display = 'none';			
		}
		if(obj.name=='ver_email' && ( obj.value == bp.email.value )){
			document.getElementById('veremail_err').style.display = 'none';
		}
		if(obj.name=='agency_name'){			
			CheckAgencyName(document.getElementById('agency_name').value, 0);
		}
	}
	return;
}

function CheckRegisterForm() {
	k = 'true';
	
	pass = document.getElementById('pass');
	ver_pass = document.getElementById('ver_pass');
	first_name = document.getElementById('first_name');
	last_name = document.getElementById('last_name');
	email = document.getElementById('email');
	ver_email = document.getElementById('ver_email');
	company_name = document.getElementById('company_name');

	if( first_name.value.trim() == ""){
		//ClearErrors();
		document.getElementById('top_nbsp').style.display = 'block';
		document.getElementById('top_error_fname').style.display = 'block';
		document.getElementById('fname_err').style.display = '';
		k = 'false';
	}
	if(	last_name.value.trim() == ""){
		//ClearErrors();
		document.getElementById('top_nbsp').style.display = 'block';
		document.getElementById('top_error_sname').style.display = 'block';
		document.getElementById('sname_err').style.display = '';
		k = 'false';
	}
	if( email.value.trim() == "" || email.value.search('^.+@.+\\..+$') ==-1) {
		//ClearErrors();
		document.getElementById('top_nbsp').style.display = 'block';
		document.getElementById('top_error_email').style.display = 'block';
		document.getElementById('email_err').style.display = '';
		k = 'false';
	}	
	if (UserEmailCheck(email.value) == 'exists') {		
		document.getElementById('email_err').style.display = '';
		k = 'false';
	}	
	if ( ver_email.value != email.value ) {
		//ClearErrors();
		document.getElementById('top_nbsp').style.display = 'block';		
		document.getElementById('top_error_veremail').style.display = 'block';
		document.getElementById('veremail_err').style.display = '';
		k = 'false';
	}	
	if ( pass.value.length<4 || pass.value.length>10 ){
		//ClearErrors();
		document.getElementById('top_nbsp').style.display = 'block';
		document.getElementById('top_error_pass').style.display = 'block';
		document.getElementById('pass_err').style.display = '';
		k = 'false';
	}
	if ( ver_pass.value != pass.value) {
		//ClearErrors();
		document.getElementById('top_nbsp').style.display = 'block';
		document.getElementById('top_error_verpass').style.display = 'block';
		document.getElementById('verpass_err').style.display = '';
		k = 'false';
	}
	
	if ( document.getElementById('user_type_copy').value =='') {
		//ClearErrors();
		document.getElementById('top_nbsp').style.display = 'block';
		document.getElementById('top_error_usertype').style.display = 'block';		
		document.getElementById('user_type_err').style.display = '';
		k = 'false';
	}
	
	if ( company_name.value.trim() == "" && document.getElementById('user_type_copy').value == 2) {
		//ClearErrors();
		document.getElementById('top_nbsp').style.display = 'block';
		document.getElementById('top_error_empty_fields').style.display = 'block';		
		document.getElementById('company_name_err').style.display = '';
		k = 'false';
	}
	
	

	
	if (k=='false') {
		return false;
	} else {
		return true;
	}
}

function UserEmailCheck(email) {
    	ajax();
    	err = 0;
	    if (doc){
	       doc.open("GET", "../location.php?sec=hp&sel=email&email=" + email, false);
	       doc.send(null);
	    	// Write the response to the div
	    	//destination.innerHTML = doc.responseText;
	    	if (doc.responseText=='exists'){
	    		document.getElementById('email_exists_err').style.display = '';
   				err = 1;
	    	} else {
	    		document.getElementById('email_exists_err').style.display = 'none';	    		
	    	}
	    } else{
	       alert ('Browser unable to create XMLHttp Object');
	    }
	    if (err == 1) {
	    	return 'exists';
	    } else {
	    	return;
	    }
}

function DivVision(id_div){
	if ( id_div == '1' ) {
		document.getElementById('user_type_copy').value = id_div;
		document.getElementById('user_type_err').style.display = 'none';
		document.getElementById('choose_agency').style.display = 'none';
	} else if ( id_div == '2' ) {
		document.getElementById('user_type_copy').value = id_div;
		document.getElementById('user_type_err').style.display = 'none';
		document.getElementById('choose_agency').style.display = 'none';
	} else if ( id_div == '3' ) {
		document.getElementById('user_type_copy').value = id_div;
		document.getElementById('user_type_err').style.display = 'none';
		document.getElementById('choose_agency').style.display = '';
	}
	return;
}

function DialogShow(type){
	document.getElementById('dialog_div_1').style.display = 'inline';
	
	if (document.getElementById('dialog_2_1').value == '') {
		document.getElementById('dialog_td_2_1').style.display = 'none';
	}	
	if (document.getElementById('dialog_2_2').value == '') {
		document.getElementById('dialog_td_2_2').style.display = 'none';
	}	
	return;
}

function CompanyShow(type){
	if (type == 2) {
		document.getElementById('company_block').style.display = '';
	} else {
		document.getElementById('company_block').style.display = 'none';
	}	
	return;
}


function Dialog2Show(type){
	if (document.getElementById('user_type_copy').value == 1){
		if (type !=''){
			document.getElementById('dialog_td_2_1').style.display = 'inline';
		} else {
			document.getElementById('dialog_td_2_1').style.display = 'none';
		}
		document.getElementById('dialog_2_1').value = '';
	} else {
		if (type !=''){
			document.getElementById('dialog_td_2_2').style.display = 'inline';
		} else {
			document.getElementById('dialog_td_2_2').style.display = 'none';
		}
		document.getElementById('dialog_2_2').value = '';
	}
	return;
}

var doc = null;
function ajax() {
	// Make a new XMLHttp object
	if (typeof window.ActiveXObject != 'undefined' ) doc = new ActiveXObject("Microsoft.XMLHTTP");
	else doc = new XMLHttpRequest();
}

function CheckAgencyName(name, user){	
 	ajax();
 	ClearErrors();	
    if (doc){
       doc.open("GET", "location.php?sec=hp&sel=agency_name&agency_name=" + name + "&user_id=" + user, false);
       doc.send(null);
    	// Write the response to the div
    	//destination.innerHTML = doc.responseText;
    	if (doc.responseText == '' || doc.responseText.substr(0,3) != '|-|'){
    		document.getElementById('top_error_na_agency').style.display = 'none';
    		document.getElementById('id_company').value = doc.responseText;			
			return ;
    	} else {    	    		
    		document.getElementById('top_nbsp').style.display = 'inline';
			document.getElementById('top_error_na_agency').style.display = 'inline';
			document.getElementById('id_company').value = 0;				
    	}
    }
    else{
       alert ('Browser unable to create XMLHttp Object');
    }	
	return;
}

function OpenParentWindow(url){
	
	var nameWin = '{/literal}{$lang.content.user_choose}{literal}';
	var left_pos = (window.screen.width - 800)/2;
	var top_pos = (window.screen.height - 600)/2;			
	
	ptWin = window.open(url,"", "width=800, height=600, resizable = yes, scrollbars = yes, menubar = no, left="+left_pos+", top="+top_pos+", modal=yes");	
	return false;
	
}

function EnterAgency(agency_name, user){
	document.getElementById('agency_name').value = agency_name;		
	CheckAgencyName(agency_name, user);
}
	</script>
	{/literal}
