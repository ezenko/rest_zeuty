{include file="$admingentemplates/admin_top.tpl"}
	<table cellpadding="0" cellspacing="0" border="0" width="100%">
		<tr>
			<td class="header" align="left">{$lang.menu.users} <font class="subheader">| {$lang.menu.users} | {if $par=='add'}{$lang.menu.users_add}{else}{$lang.menu.users_list} | {$user.login}{/if}</font></td>
		</tr>
		<tr>
			<td><div class="help_text"><span class="help_title">{$lang.default_select.help}</span>{if $par=='add'}{$lang.content.add_user_help}{else}{$lang.content.user_profile_help}{/if}</div></td>
		</tr>
		<tr>
			<td width="100%" class="main_content_text">
				<form method="POST" action="" name="user_form" id="user_form" enctype="multipart/form-data">
				<input type="hidden" name="par" id="par" value="{$par}">
				<input type="hidden" name="user_id" id="user_id" value="{$user.id}">
				<div class="table_title">{$lang.content.user_info}:</div>
				<table cellpadding="5" cellspacing="0" cellspacing="0" border="0" width="80%">
				<tr>
					<td width="120">{$lang.content.name}<font class="error">*</font>:</td>
					<td><input type="text" name="fname" id="fname" value="{$user.fname}" size="30"  {if $user.root_user eq 1}disabled{/if} onblur="CheckFname();"><div name="fname_err" id="fname_err" class="error" style="display : none;">{$lang.content.incorrect_field}</div></td>
				</tr>
				<tr>
					<td>{$lang.content.sname}<font class="error">*</font>:</td>
					<td><input type="text" name="sname" id="sname" value="{$user.sname}" size="30" {if $user.root_user eq 1}disabled{/if} onblur="CheckSname();"><div name="sname_err" id="sname_err" class="error" style="display : none;">{$lang.content.incorrect_field}</div></td>
				</tr>
				<tr>
					<td>{$lang.content.email}<font class="error">*</font>:</td>
					<td><input type="text" name="email" id="email" value="{$user.email}" size="30" {if $user.root_user eq 1}disabled{/if} onblur="CheckEmail();"><div name="email_err" id="email_err" class="error" style="display : none;">{$lang.content.incorrect_field}</div><div name="email_exists_err" id="email_exists_err" class="error" style="display : none;">{$lang.content.email_exists}</div>
					</td>
				</tr>
				<tr>
					<td>{$lang.content.phone}&nbsp;:</td>
					<td><input type="text" name="phone" id="phone" value="{$user.phone}" size="30" {if $user.root_user eq 1}disabled{/if}></td>
				</tr>
				{if $par eq 'edit'}
				<tr>
					<td>{$lang.content.change_pass}&nbsp;&nbsp;&nbsp;</td>
					<td><input type="checkbox" name="change_pass" id="change_pass" value="1" onclick="PassFields();"></td>
				</tr>
				{/if}
				<tr>
					<td>{$lang.content.password}{if $par eq 'edit'}&nbsp;{elseif $par eq 'add'}<font class="error">*</font>{/if}:&nbsp;</td>
					<td><input type="password" name="password" id="password" maxlength="10" onblur="CheckPassword();" {if $par eq 'edit'} disabled {/if}><div name="password_err" id="password_err" class="error" style="display : none;">{$lang.content.incorrect_field}</div></td>
				</tr>
				<tr>
					<td>{$lang.content.ver_password}{if $par eq 'edit'}&nbsp;{elseif $par eq 'add'}<font class="error">*</font>{/if}:&nbsp;</td>
					<td><input type="password" name="ver_password" id="ver_password" maxlength="10" onblur="CheckVerPassword();" {if $par eq 'edit'} disabled {/if}><div name="ver_password_err" id="ver_password_err" class="error" style="display : none;">{$lang.content.incorrect_field}</div></td>
				</tr>				
				{if $admin_lang_menu_visible_cnt > 1}
				<tr>
					<td>{$lang.content.interface_language}:</td>
					<td>{strip}
						{section name=lang_menu loop=$admin_lang_menu}
							{if $admin_lang_menu[lang_menu].vis eq 1}
								<input type="radio" name="lang_id" value="{$admin_lang_menu[lang_menu].id_lang}" {if $admin_lang_menu[lang_menu].id_lang eq $user.lang_id}checked{/if}>&nbsp;{$admin_lang_menu[lang_menu].value}&nbsp;&nbsp;
							{/if}
						{/section}
						{/strip}
					</td>
				</tr>
				{else}
				<input type="hidden" name="lang_id" value="{$user.lang_id}">
				{/if}
				<tr>
					<td>
						{$lang.content.user_field_1}&nbsp;:
					</td>
					<td >
					<table border="0" cellpadding="0" cellspacing="0">
						<tr>
															
						<td style="padding-bottom:3px;padding-right:3px;"><input type="radio" id="user_type_1" name="user_type" value="1" {if $user.user_type eq 1} checked {/if}
	  {if $user.user_type eq 3 && $user.agency_approve eq 1}onclick="alert('{$lang.content.need_delete_realtor}'); document.getElementById('user_type_3').checked = 'true';"
	  {elseif $user.user_type eq 2 && $user.have_agents eq 1}onclick="alert('{$lang.content.need_delete_agents}'); document.getElementById('user_type_2').checked = 'true';" 
	  {else} onclick="DivVision(this.value);"
	  {/if}><input type="hidden" id="user_type_copy" name="user_type_copy" value="{$user.user_type}"></td>
							<td>{$lang.content.user_field_2}&nbsp;&nbsp;</td>
							
							<td style="padding-bottom:3px;padding-right:3px;"><input type="radio" id="user_type_2" name="user_type" value="2" {if $user.user_type eq 2} checked {/if}
	  {if $user.user_type eq 3 && $user.agency_approve eq 1}onclick="alert('{$lang.content.need_delete_realtor}'); document.getElementById('user_type_3').checked = 'true';"
	  {else} onclick="DivVision(this.value);"
	  {/if}></td>			
							<td>{$lang.content.user_field_3}</td>
							{if $use_agent_user_type}			
							<td style="padding-bottom:3px;padding-right:3px;"><input type="radio" id="user_type_3" name="user_type" value="3" {if $user.user_type eq 3} checked {/if}
	   {if $user.user_type eq 2 && $user.have_agents eq 1}onclick="alert('{$lang.content.need_delete_agents}'); document.getElementById('user_type_2').checked = 'true';"
	  {else} onclick="DivVision(this.value);"
	  {/if}></td>						
							
							<td>{$lang.content.user_field_4}&nbsp;&nbsp;</td>		
							{else}
							<td style="padding-left:10px;">
							<span class="error">{$lang.content.use_only_private_user_type}</span>
							</td>
							{/if}
						</tr>
					</table>
					</td>
				</tr>
				</table>
				
				<div id="agent_div" style="display:{if $user.user_type == 3}inline{else}none{/if};">
				<table cellpadding="0" cellspacing="0" border="0" width="100%" >											
					<tr>
						<td  width="130"></td>
						<td style="padding-left:25px;">
						<div>
							<table cellpadding="0" cellspacing="0" border="0" align="left">
								<tr>
								
								<td align="left" height="30" id="id_input_agency" ><input type="text" size="35" id="agency_name" name="agency_name" class="" onblur="CheckAgencyName(this.value, '{$user.id}');" value="{$user.agency_name}"></td>			
								<td align="left"><div  id="na_agency_error" class="error" style="display: none;padding-left:10px;"></div></td>					
								</tr>
								<tr>
									<td colspan="2" align="left" id="id_choose_agency">
								
									
								<a id="choose_company_href" style="display:{if $user.agency_approve == -1}inline{else}none{/if};" onclick="javascript: return OpenParentWindow('{$server}{$site_root}/registration.php?sel=choose_company&id_user_exc={$user.id}');">{$lang.content.choose_company}</a>
								
								<a id="decline_company_href" onclick="document.getElementById('id_company').value = 0; document.user_form.action = '{$file_name}?sel=save_changes'; document.user_form.submit();" style="display:{if $user.agency_approve == 0}inline{else}none{/if};">{$lang.content.decline_company}&nbsp;{$user.agency_name}</a>								
								
								<a id="delete_company_href" onclick="document.getElementById('id_company').value = 0; document.user_form.action = '{$file_name}?sel=save_changes'; document.user_form.submit();" style="display:{if $user.agency_approve == 1}inline{else}none{/if};">{$lang.content.delete_company}&nbsp;{$user.agency_name}</a>
								
								{if $user.agency_approve == 0 || $user.agency_approve == 1}&nbsp;{/if}	
								
								<a id="change_company_href" style="display:{if $user.agency_approve == 0 || $user.agency_approve == 1}inline{else}none{/if};" onclick="javascript: return OpenParentWindow('{$server}{$site_root}/registration.php?sel=choose_company&id_user_exc={$user.id}');">{$lang.content.change_company}</a>&nbsp;	
									</td>																						
								</tr>
							</table>
						</div>
						</td>
					</tr>						
					{if $user.agency_approve == 0}			
					<tr>	
						<td  width="130"></td>						
						<td id="not_approve_hint" class="error" style="padding-top:5px;padding-left:25px;">*{$lang.content.not_approve}</td>
					</tr>
					{/if}
					<tr>
						<td  width="130"></td>			
					
						<td align="justify" id="hint_3" style="padding-top:5px;padding-left:25px;"><small>{$lang.content.hint_3}</small>
					<input type="hidden" name="id_company" id="id_company" value="{$user.id_agency}">
						</td>
					
					</tr>							
				</table>
				</div>			
				

				<div id="broker_div" style="display:{if $user.user_type == 1 || $user.user_type == 3 && $user.agency_approve == 0}inline{else}none{/if};">
				<table cellpadding="5" cellspacing="0" border="0">
					<tr>
						<td  width="120" height="35">{$lang.content.birthday}&nbsp;:</td>
						<td>
							<select name="birth_month" id="birth_month" onchange="javascript: MyCheck();">
								{foreach item=item from=$month}<option value="{$item.value}" {if $item.sel}selected{/if}>{$item.name}</option>{/foreach}
							</select>
							<select name="birth_day" id="birth_day" onchange="javascript: MyCheck();">
								{foreach item=item from=$day}<option value="{$item.value}" {if $item.sel}selected{/if}>{$item.value}</option>{/foreach}
							</select>
							<select name="birth_year" id="birth_year" onchange="javascript: MyCheck();">
								{foreach item=item from=$year}<option value="{$item.value}" {if $item.sel}selected{/if}>{$item.value}</option>{/foreach}
							</select>
						</td>
						<td class="error_div" name="birthdate_div" id="birthdate_div" style="display: none;">{$lang.content.incorrect_date}</td>
					</tr>
				</table>
				</div>
				
				<div id="agent_company_div" style="display:{if $user.user_type == 3 && $user.agency_approve == 1}inline{else}none{/if};">
				<table cellpadding="5" cellspacing="0" border="0">
					
					<tr>
						<td height="35" width="120" >{$lang.content.your_photo}&nbsp;:&nbsp;</td>
						
						<td >{if $user.photo_path !=''}<img src="{$user.photo_path}" style="border-style: solid; border-width: 1px; border-color: #cccccc; padding: 3px;">{else}
						<div class="fileinputs">
							<input type="file" name="agent_photo" id="agent_photo" class="file" onchange="document.getElementById('file_text_agent_photo').innerHTML = this.value.replace(/.*\\(.*)/, '$1');document.getElementById('file_img_agent_photo').innerHTML = ChangeIcon(this.value.replace(/.*\.(.*)/, '$1'));"/>
							<div class="fakefile">
								<table cellpadding="0" cellspacing="0">
								<tr>												
									<td>	
										<input type="button"  class="btn_upload" value="{$lang.buttons.choose}">
									</td>			
									<td style="padding-left:10px;">
										<span id='file_img_agent_photo'></span>
									</td>								
									<td style="padding-left:4px;">
										<span id='file_text_agent_photo'></span>	
									</td>
								</tr>
								</table>
							</div>
						</div>	
							{/if}
						</td>																							
					</tr>
					
					{if $user.use_photo_approve && $user.admin_approve != 1}
					<tr>
						<td width="120" height="27"></td>
						<td align="left">	&nbsp;<font class="error_small">
							{if $user.admin_approve == 0}{$lang.content.admin_approve_not_complete}{elseif $user.admin_approve == 2}{$lang.content.admin_approve_decline}
							{/if}</font>
						</td>
					</tr>
					{/if}
					{if $user.photo_path != ''}
					<tr >	
						<td width="120" style="padding-top:5px;">
						</td>				
						<td style="padding-top:5px;">											
							<div class="fileinputs">
							<input type="file" name="agent_photo" id="agent_photo" class="file" onchange="document.getElementById('file_text_agent_photo_2').innerHTML = this.value.replace(/.*\\(.*)/, '$1');document.getElementById('file_img_agent_photo_2').innerHTML = ChangeIcon(this.value.replace(/.*\.(.*)/, '$1'));"/>
							<div class="fakefile">
								<table cellpadding="0" cellspacing="0">
								<tr>												
									<td>	
										<input type="button"  class="btn_upload" value="{$lang.buttons.choose}">
									</td>			
									<td style="padding-left:10px;">
										<span id='file_img_agent_photo_2'></span>
									</td>								
									<td style="padding-left:4px;">
										<span id='file_text_agent_photo_2'></span>	
									</td>
								</tr>
								</table>
							</div>
						</div>	
						</td>																				
					</tr>
					{/if}
					<tr >	
						<td width="120" style="padding-top:5px;" height="27">
						{$lang.content.company_name}:
						</td>				
						<td style="padding-top:5px;">											
						{$user.agency_name}	
						</td>																				
					</tr>
					{if $user.agency_url != ''}	
					<tr >	
						<td width="120"  height="27">
						{$lang.content.agency_url}:
						</td>				
						<td>											
						<a href="{$user.agency_url}">{$user.agency_url}</a>	
						</td>																				
					</tr>		
					{/if}
					{if $user.agency_logo_path !='' && $user.logo_approve == 1}
					<tr >	
						<td width="120"  height="27">
						{$lang.content.agency_logo}:
						</td>				
						<td >
						
						<img src="{$user.agency_logo_path}" style="border-style: solid; border-width: 1px; border-color: #cccccc; padding: 3px;">
						</td>																				
					</tr>									
					{/if}
					{if $user.agency_phone}
					<tr>
						<td height="27">{$lang.content.phone}:</td>
						<td>{$user.agency_phone}</td>
					</tr>
					{/if}
					
					{if (($user.country_name)||($user.region_name)||($user.city_name)||($user.ag_address))}
					<tr>
						<td height="27">{$lang.content.location}:</td>
						<td>
							{$user.country_name}{if $user.region_name}, {$user.region_name}{/if}{if $user.city_name}, {$user.city_name}{/if}{if $user.ag_address}, {$user.ag_address}{/if}
						</td>
					</tr>
					{/if}					
					{if $user.weekday}
					<tr>
						<td height="27">{$lang.content.work_days}:</td>
						<td>
						{foreach name=week key=key item=item from=$week}
							{if $user.weekday.$key eq $item.id }{$item.name}{/if}
						{/foreach}
						</td>
					</tr>
					{/if}
					{if $user.work_time_begin > 0 || $user.work_time_end > 0}
					<tr>
						<td height="27">{$lang.content.work_time}:</td>
						<td>{$lang.content.time_begin}&nbsp;
						{foreach item=item from=$time_arr}
							{if $user.work_time_begin eq $item.value}{$item.value}{/if}
						{/foreach}
						&nbsp;{$lang.content.time_end}&nbsp;
						{foreach item=item from=$time_arr}
							{if $user.work_time_end eq $item.value}{$item.value}{/if}
						{/foreach}
						</td>
					</tr>
					{/if}
					{if $user.lunch_time_begin > 0 || $user.lunch_time_end > 0}
					<tr>
						<td height="27">{$lang.content.lunch_time}:</td>
						<td>{$lang.content.time_begin}&nbsp;
						{foreach item=item from=$time_arr}
							{if $user.lunch_time_begin eq $item.value}{$item.value}{/if}
						{/foreach}
						&nbsp;{$lang.content.time_end}&nbsp;
						{foreach item=item from=$time_arr}
							{if $user.lunch_time_end eq $item.value}{$item.value}{/if}
						{/foreach}
						</td>
					</tr>
					{/if}				
				</table>				
				</div>

				<div id="agency_div" style="display:{if $user.user_type == 2}inline{else}none{/if};">

					<table cellpadding="5" cellspacing="0" border="0">
					<tr>
						<td width="120">{$lang.content.company_name}:&nbsp;<span id="company_name_error" class="error">*</span></td>
						<td><input type="text" class="str" name="company_name" id="company_name" value="{$user.company_name}" size="25"><div name="company_name_err" id="company_name_err" class="error" style="display : none;">{$lang.content.incorrect_field}</div></td>
					</tr>
					{if $user.company_url}
					<tr>
						<td width="120">{$lang.content.company_url}:</td>
						<td><input type="text" class="str" name="company_url" id="company_url" value="{$user.company_url}" size="25"></td>
					</tr>
					{/if}
					<tr>
						<td width="120">{$lang.content.logo_path}:</td>
						<td>
							<table cellpadding="0" cellspacing="0">
								
								<tr>
									<td style="padding-top: 5px;">{if $user.logo_path !=''}<img src="{$user.logo_path}" style="border-style: solid; border-width: 1px; border-color: #cccccc; padding-top: 3px; padding-bottom: 3px; padding-left: 3px; padding-right: 3px;">{/if}</td>
									{if $user.use_photo_approve && $user.user_type == 2 && $user.admin_approve != 1}<td>&nbsp;<font class="error_small">{if $user.admin_approve == 0}{$lang.content.admin_approve_not_complete}{elseif $user.admin_approve == 2}{$lang.content.admin_approve_decline}{else}asd{/if}</font></td>{/if}
								</tr>
								<tr>
									<td colspan="2">
									<div class="fileinputs">
										<input type="file" name="company_logo" id="company_logo" class="file" onchange="document.getElementById('file_text_company_logo').innerHTML = this.value.replace(/.*\\(.*)/, '$1');document.getElementById('file_img_company_logo').innerHTML = ChangeIcon(this.value.replace(/.*\.(.*)/, '$1'));"/>
										<div class="fakefile">
											<table cellpadding="0" cellspacing="0">
											<tr>												
												<td>	
													<input type="button"  class="btn_upload" value="{$lang.buttons.choose}">
												</td>			
												<td style="padding-left:10px;">
													<span id='file_img_company_logo'></span>
												</td>								
												<td style="padding-left:4px;">
													<span id='file_text_company_logo'></span>	
												</td>
											</tr>
											</table>
										</div>
									</div>		
									</td>
								</tr>
							</table>
						</td>
					</tr>
					
					
					<tr>
											
						<td colspan="2">											
							<table cellpadding="3" cellspacing="0" border="0">
								<tr>
									<td width="120">{$lang.content.country}:</td>
									<td id="country_div">
										<select name="country" id=country onchange="javascript: {literal} SelectRegion('ip', this.value, document.getElementById('region_div'), document.getElementById('city_div'),'{/literal}{$lang.content.ip_load_region}{literal}', '{/literal}{$lang.content.ip_city}{literal}'); {/literal}" class="location">
										<option value="">{$lang.content.ip_country}</option>
											{foreach item=item from=$country}
										<option value="{$item.id}" {if $user.id_country eq $item.id} selected {/if}>{$item.name}</option>
											{/foreach}
										</select>
									</td>														
								</tr>

								<tr>
									<td>{$lang.content.region}:</td>
									<td id="region_div">
										<select name="region"  id="region" onchange="javascript: {literal} SelectCity('ip', this.value, document.getElementById('city_div'), '{/literal}{$lang.content.ip_load_city}{literal}');{/literal}" class="location">
										<option value="">{$lang.content.ip_region}</option>
											{foreach item=item from=$region}
										<option value="{$item.id}"  {if $user.id_region eq $item.id} selected {/if}>{$item.name}</option>
											{/foreach}
										</select>
									</td>														
								</tr>
								<tr>
								<td>{$lang.content.city}:</td>
									<td id="city_div">
										<select name="city"  class="location">
										<option value="">{$lang.content.ip_city}</option>
											{foreach item=item from=$city}
										<option value="{$item.id}" {if $user.id_city eq $item.id} selected {/if}>{$item.name}</option>
											{/foreach}
										</select>
									</td>													
								</tr>
								<tr>
									<td>{$lang.content.address}:</td>
									<td colspan="2">
										<input type="text" name="address" value='{$user.address}'size="35">
									</td>						
								</tr>
								<tr>
									<td>{$lang.content.zipcode}:</td>
									<td colspan="2">
										<input type="text" name="postal_code" value='{$user.postal_code}'size="35">
									</td>						
								</tr>
								</table>
											
							</td>
					</tr>
					
					
					
					<tr>
						<td width="120" valign="top">{$lang.content.weekday_str}:</td>
						<td>
							<table cellpadding="0" cellspacing="0" border="0" width="100%">
								<tr>
								{foreach key=key item=item from=$week}
									<td><input type="checkbox" name="weekday[{$key}]" {if $user.weekday.$key eq $item.id } checked {/if} value="{$item.id}"></td>
									<td>{$item.name}</td>
								{/foreach}
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td width="120">{$lang.content.work_time}:</td>
						<td>
							<table cellpadding="3" cellspacing="0" border="0">
							<tr>
								<td height="27" align="absmiddle">{$lang.content.time_begin}</td>
								<td>
									<select name="work_time_begin" id="work_time_begin" onchange="CheckTimeInterval('work_time_begin','work_time_end', 'incorrect_time')">
										{foreach item=item from=$time_arr}
										<option {if $data.work_time_begin eq $item.value} selected {/if} value="{$item.value}">{$item.value}</option>
										{/foreach}
									</select>
								</td>
								<td align="absmiddle">{$lang.content.time_end}</td>
									<td>
										<select name="work_time_end" id="work_time_end" onchange="CheckTimeInterval('work_time_begin','work_time_end', 'incorrect_time')">
										{foreach item=item from=$time_arr}
										<option {if $data.work_time_end eq $item.value} selected {/if} value="{$item.value}">{$item.value}</option>
										{/foreach}
									</select>
								</td>
								<td><div id="incorrect_time" style="display:none" class="error">{$lang.content.incorrect_time_interval}</td>
							</tr>
							</table>
						</td>		
					</tr>
					<tr>
						<td width="120">{$lang.content.lunch_time}:</td>
						<td>
							<table cellpadding="3" cellspacing="0" border="0">
							<tr>
								<td height="27" align="absmiddle">{$lang.content.time_begin}</td>
								<td>
									<select name="lunch_time_begin" id="lunch_time_begin" onchange="CheckTimeInterval('lunch_time_begin','lunch_time_end', 'incorrect_lunch')">
										{foreach item=item from=$time_arr}
										<option {if $data.lunch_time_begin eq $item.value} selected {/if} value="{$item.value}">{$item.value}</option>
										{/foreach}
									</select>
								</td>
								
								<td align="absmiddle">{$lang.content.time_end}</td>
								<td>
									<select name="lunch_time_end" id="lunch_time_end" onchange="CheckTimeInterval('lunch_time_begin','lunch_time_end', 'incorrect_lunch')">
										{foreach item=item from=$time_arr}
										<option {if $data.lunch_time_end eq $item.value} selected {/if} value="{$item.value}">{$item.value}</option>
										{/foreach}
									</select>
								</td>
								<td><div id="incorrect_lunch" style="display:none;" class="error">{$lang.content.incorrect_time_interval}</td>
							</tr>
							</table>
						</td>
					</tr>
					</table>
				</div>

				{if $par=='add'}
					<input type="button" class="button_2" value="{$lang.buttons.add}" onclick="{literal}if ( CheckUserForm() == true ) { document.user_form.action='{/literal}{$file_name}{literal}?sel=add_user'; document.user_form.submit();}{/literal}">
				{else}
					<input type="button" class="button_2" value="{$lang.buttons.save}" onclick="{literal}if ( CheckUserForm() == true ) { document.user_form.action='{/literal}{$file_name}{literal}?sel=save_changes'; document.user_form.submit();}{/literal}">
				{/if}

				{if $par eq 'edit' && $user_root ne 1}
				<br><br>
				<div class="table_title">{$lang.content.user_statistics}:</div>
				<table cellpadding="5" cellspacing="0" cellspacing="0" border="0">
				{if $user.login_count > 0}
				<tr>
					<td>{$lang.content.date_last}&nbsp;:</td>
					<td>{$user.date_last_seen}</td>
				</tr>
				{/if}
				<tr>
					<td>{$lang.content.date_reg}&nbsp;:</td>
					<td>{$user.date_registration}</td>
				</tr>
				<tr>
					<td>{$lang.content.login_count}&nbsp;:</td>
					<td>{$user.login_count}</td>
				</tr>
				{if $user.rent_link}
				<tr>
					<td>{$lang.content.user_ads}:</td>
					<td><input type="button" class="button_2" value="{$lang.buttons.view}&nbsp;:&nbsp;{$user.rent_count}" onclick="document.location.href='{$user.rent_link}'">
				</td>
				</tr>
				{/if}
				{if $user.mail_link}
				<tr>
					<td>{$lang.content.mailbox}:</td>
					<td><input type="button" class="button_2" value="{$lang.buttons.view}" onclick="document.location.href='{$user.mail_link}'"></td>
				</tr>
				{/if}
				</table>
				{/if}

				{if $par eq 'edit'}
				<br><input type="button" class="button_3" value="{$lang.buttons.back}" onclick="document.location.href='{$back_link}';">
				{/if}
			</td>
			</form>
		</tr>
	</table>
{literal}
<script>
	String.prototype.trim = function()
	{
	    return this.replace(/(^\s*)|(\s*$)/g, "");
	}


var doc = null;
function ajax() {
	// Make a new XMLHttp object
	if (typeof window.ActiveXObject != 'undefined' ) doc = new ActiveXObject("Microsoft.XMLHTTP");
	else doc = new XMLHttpRequest();
}

/*function UserLoginCheck(login) {
    	ajax();
    	ugu = 0;
	    if (doc){
	       doc.open("GET", "../location.php?sec=hp&sel=login&login=" + login, false);
	       doc.send(null);
	    	// Write the response to the div
	    	//destination.innerHTML = doc.responseText;
	    	if (doc.responseText=='exists'){
	    		document.getElementById('login_exists_err').style.display = '';
   				ugu = 1;
	    	} else {
	    		document.getElementById('login_exists_err').style.display = 'none';
	    		ugu = 0;
	    	}
	    }
	    else{
	       alert ('Browser unable to create XMLHttp Object');
	    }
	    if (ugu == 1) {
	    	return 'exists';
	    } else {
	    	return;
	    }

}*/

function UserEmailCheck(email) {
    	ajax();
    	ugu = 0;
	    if (doc){
	       doc.open("GET", "../location.php?sec=hp&sel=email&email=" + email, false);
	       doc.send(null);
	    	// Write the response to the div
	    	//destination.innerHTML = doc.responseText;
	    	if (doc.responseText=='exists'){
	    		document.getElementById('email_exists_err').style.display = '';
   				ugu = 1;
	    	} else {
	    		document.getElementById('email_exists_err').style.display = 'none';
	    		ugu = 0;
	    	}
	    }
	    else{
	       alert ('Browser unable to create XMLHttp Object');
	    }
	    if (ugu == 1) {
	    	return 'exists';
	    } else {
	    	return;
	    }
}

function PassFields() {
	if (document.getElementById("change_pass").checked){
		document.getElementById("password").disabled = false;
		document.getElementById("password_err").style.display = 'none';
		document.getElementById("ver_password_err").style.display = 'none';
		document.getElementById("ver_password").disabled = false;
	} else {
		document.getElementById("password").disabled = true;
		document.getElementById("password_err").style.display = 'none';
		document.getElementById("ver_password_err").style.display = 'none';
		document.getElementById("ver_password").disabled = true;
		document.getElementById("ver_password").value = '';
		document.getElementById("password").value = '';
	}
	return;
}

function CheckPassword() {
	 if ( document.getElementById("password").value.trim().length>4 && document.getElementById("password").value.trim().length<10 ) {
	 	document.getElementById("password_err").style.display = 'none';
	 }
	 return;
}
function CheckVerPassword() {
	if (document.getElementById("password").value.trim() == document.getElementById("ver_password").value.trim()) {
		document.getElementById("ver_password_err").style.display = 'none';
	}
	return;
}
function CheckFname() {
	if ( document.getElementById("fname").value.trim()  != '') {
		document.getElementById("fname_err").style.display = 'none';
	}
	return;
}
function CheckSname() {
	if ( document.getElementById("sname").value.trim()  != '') {
		document.getElementById("sname_err").style.display = 'none';
	}
	return;
}
/*function CheckLogin() {
	if ( document.getElementById("login").value.trim().length>3 && document.getElementById("login").value.trim().length<12 ) {
		document.getElementById("login_err").style.display = 'none';
	}
	return;
}*/
function CheckEmail() {
	if ( !(document.getElementById("email").value.search('^.+@.+\\..+$') ==-1) ) {
		document.getElementById("email_err").style.display = 'none';
	}
	return;
}
function CheckUserForm() {
	aga = 0;
	if ( document.getElementById("fname").value.trim()  == '') {
		document.getElementById("fname_err").style.display = 'inline';
		aga = 1;
	}
	if  ( document.getElementById("sname").value.trim()  == '') {
		document.getElementById("sname_err").style.display = 'inline';
		aga = 1;
	}
	/*if  ( document.getElementById("login").value.trim().length<3 || document.getElementById("login").value.trim().length>12 ) {
		document.getElementById("login_err").style.display = 'inline';
		aga = 1;
	}*/
	if ( (document.getElementById("par").value == 'edit' && document.getElementById("change_pass").checked) || document.getElementById("par").value == 'add') {
		if ( document.getElementById("password").value.trim().length<4 || document.getElementById("password").value.trim().length>10 ) {
			document.getElementById("password_err").style.display = 'inline';
			document.getElementById("password_err").innerHTML = '{/literal}{$lang.content.incorrect_field} ({$lang.content.password_requirement}{literal})';
			aga = 1;
		}
		if (document.getElementById("password").value.trim() != document.getElementById("ver_password").value.trim()) {
			document.getElementById("ver_password_err").style.display = 'inline';
			aga = 1;
		}
	}
	if  ( document.getElementById("email").value.search('^.+@.+\\..+$') ==-1){
		document.getElementById("email_err").style.display = 'inline';
		aga = 1;
	}
	if ( (document.getElementById("email").value != '{/literal}{$user.email}{literal}') && (UserEmailCheck(document.getElementById("email").value) == 'exists')) {
		aga = 1;
	}
	if ( document.getElementById("user_type_copy").value == 2) {
		if ( document.getElementById("company_name").value.trim()  == '') {
			document.getElementById("company_name_err").style.display = 'inline';
			aga = 1;
		}
	}
	/*if ( (document.getElementById("login").value != '{/literal}{$user.login}{literal}') && (UserLoginCheck(document.getElementById("login").value) == 'exists')) {
		aga = 1;
	}*/
	if (aga == 1) {
		return false;
	} else {
		return true;
	}
}

var monthLength = new Array(31,28,31,30,31,30,31,31,30,31,30,31);

function checkDate(name) {
	var day = parseInt(document.getElementById(name+"_day").options[document.getElementById(name+"_day").selectedIndex].value);
	var month = parseInt(document.getElementById(name+"_month").options[document.getElementById(name+"_month").selectedIndex].value);
	var year = parseInt(document.getElementById(name+"_year").options[document.getElementById(name+"_year").selectedIndex].value);

	if (!day || !month || !year)
		return false;

	if (year/4 == parseInt(year/4)) {
		monthLength[1] = 29;
	} else {
		monthLength[1] = 28;
	}

	if (day > monthLength[month-1]){
		return 0;
	} else {
		return 1;
	}
}

function MyCheck() {
		if (checkDate('birth') == 0){
			document.getElementById('birthdate_div').style.display = 'inline';
		} else {
			document.getElementById('birthdate_div').style.display = 'none';
		}
	return true;
}

function DivVision(id_div){
	document.getElementById('user_type_copy').value = id_div;
	if ( id_div == '1' ) {
		document.getElementById('broker_div').style.display = 'inline';
		document.getElementById('agent_div').style.display = 'none';
		document.getElementById('agency_div').style.display = 'none';
	} else if ( id_div == '2' ) {
		document.getElementById('broker_div').style.display = 'none';
		document.getElementById('agent_div').style.display = 'none';
		document.getElementById('agency_div').style.display = 'inline';
	} else if ( id_div == '3') {
		document.getElementById('agent_div').style.display = 'inline';
		document.getElementById('broker_div').style.display = 'inline';
		document.getElementById('agency_div').style.display = 'none';
	}
		return;
}

function CheckAgencyName(name, user){	
 	ajax(); 	
 		if (doc){
	       doc.open("GET", "admin_location.php?sec=hp&sel=agency_name&agency_name=" + name + "&user_id=" + user, false);
	       doc.send(null);
	    	// Write the response to the div
	    	//destination.innerHTML = doc.responseText;	    	
	    	if (doc.responseText.substr(0,3) != '|-|'){	    		
	    		document.getElementById('na_agency_error').style.display = 'none';	    		
	    		if (document.getElementById('id_company').value != doc.responseText){
	    			document.getElementById('decline_company_href').style.display = 'none';
	    			document.getElementById('delete_company_href').style.display = 'none';
	    		}
	    		if (doc.responseText != document.getElementById('id_company').value){
	    			{/literal}{if $user.agency_approve == 0}{literal}
	    			document.getElementById('not_approve_hint').style.display = 'none';
	    			{/literal}{/if}{literal}
	    		}
	    		document.getElementById('id_company').value = doc.responseText;	   	    			    		
					return ;
	    	} else if(doc.responseText.substr(3,3) == 'yet'){
	    		if (doc.responseText.substr(6, doc.responseText.length - 6) != '{/literal}{$user.agency_name}{literal}'){
	    			document.getElementById('na_agency_error').style.display = 'inline';
					document.getElementById('na_agency_error').innerHTML = "&nbsp;{/literal}{$lang.content.with_company_1}&nbsp;&quot;" + doc.responseText.substr(6, doc.responseText.length - 6) + "&quot;&nbsp;{$lang.content.with_company_2}{literal}";
	    		}
				document.getElementById('agency_name').value = '{/literal}{$user.agency_name}{literal}';
				if (document.getElementById('agency_name').value != ''){
					{/literal}{if $user.agency_approve == 1}{literal}
						document.getElementById('delete_company_href').style.display = 'inline';					
					{/literal}{else}{literal}
						document.getElementById('decline_company_href').style.display = 'inline';					
					{/literal}{/if}{literal}						
				}
	    		
	    	} else {    	    		
				if (name != ''){
					document.getElementById('na_agency_error').style.display = 'inline';
					document.getElementById('na_agency_error').innerHTML = "&nbsp;{/literal}{$lang.content.na_agency_1}&nbsp;&quot;" + doc.responseText.substr(3, doc.responseText.length - 3) + "&quot;&nbsp;{$lang.content.na_agency_2}{literal}";		
				}else{
					{/literal}{if $user.agency_name != ''}{literal}
					document.getElementById('na_agency_error').style.display = 'inline';
					document.getElementById('na_agency_error').innerHTML = "&nbsp;{/literal}{$lang.content.na_agency_3}{literal}";
					{/literal}{/if}{literal}
				}
				document.getElementById('agency_name').value = '{/literal}{$user.agency_name}{literal}';
				if (document.getElementById('agency_name').value != ''){
					{/literal}{if $user.agency_approve == 1}{literal}
						document.getElementById('delete_company_href').style.display = 'inline';					
					{/literal}{else}{literal}
						document.getElementById('decline_company_href').style.display = 'inline';					
					{/literal}{/if}{literal}						
				}	
	    	}
	    }
	    else{
	       alert ('Browser unable to create XMLHttp Object');
	    }	 	
    
	return;
}

function OpenParentWindow(url){
		
	var left_pos = (window.screen.width - 800)/2;
	var top_pos = (window.screen.height - 600)/2;			
	
	ptWin = window.open(url,"", "width=800, height=600, resizable = yes, scrollbars = yes, menubar = no, left="+left_pos+", top="+top_pos+", modal=yes");	
	return false;
	
}

function EnterAgency(agency_name, user){

	document.getElementById('agency_name').value = agency_name;	
	CheckAgencyName(agency_name, user);					
}

function CheckTimeInterval(id_start, id_end, id_error){
	if (document.getElementById(id_start).value > document.getElementById(id_end).value){
		document.getElementById(id_error).style.display = 'inline';
	}else{
		document.getElementById(id_error).style.display = 'none';	
	}
}

function ChangeIcon(type){	
    switch (type.toLowerCase())
    {
        case 'bmp':         
        case 'jpg':  
        case 'png':
        case 'gif':
        case 'tiff':
        type = type.toLowerCase();
        break;
        case 'jpeg':
        	type = 'jpg';
        	break;
        case 'tif':
        	type = 'tiff';
        	break;
        case 'mp3':
        case 'wav':
        case 'ogg':
        	type = 'mp3';
         break;
        case 'avi':
        case 'wmv':
        case 'flv':
        	type = 'avi';
        	 break;
        
        default: type = 'other'; break;
    };   
    return "<img src='{/literal}{$site_root}{$template_root}/images/file_types/{literal}" + type +".png'>";           
}
</script>
{/literal}
{include file="$admingentemplates/admin_bottom.tpl"}