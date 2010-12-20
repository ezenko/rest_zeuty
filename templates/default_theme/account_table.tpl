{include file="$gentemplates/site_top.tpl"}
	
<table cellpadding="0" cellspacing="0" border="0">
<tr valign="top">
	<td class="left" valign="top">
		{include file="$gentemplates/account_menu.tpl"}
	</td>
	<td class="delimiter">&nbsp;</td>
	<td class="main">
	 	<!-- banner center -->
	  	{if $banner.center}
			<div align="left">{$banner.center}</div>
		{/if}
		 <!-- /banner center -->
		<table cellpadding="0" cellspacing="0" border="0" width="100%">
			<tr><td class="header"><b>{$lang.headers.account}</b></td></tr>
		</table>
		{if $section eq 1}
		<form method="POST" action="" name="account_form" id="account_form" enctype="multipart/form-data">
		<table cellpadding="0" cellspacing="0" width="100%" border="0">
			<tr>
				<td colspan="2" class="subheader"><b>{$lang.headers.account_profile}</b>
				</td>
			</tr>
		{if $error}
			<tr>
				<td colspan="2">&nbsp;</td>
			</tr>
			<tr>
				<td colspan="2" class="error_div">*&nbsp;{$error}</td>
			</tr>
		{/if}
			<tr>
				<td width="12">&nbsp;</td>
				<td>
					<table cellpadding="0" cellspacing="0" border="0">
						<tr>
							<td width="5">&nbsp;</td>
							<td>
								<table cellpadding="0" cellspacing="0" border="0">
								{if $user.8 == 0}	{* deactivated *}
									<tr><td height="27" class="error" style="padding-top: 10px;">{$lang.content.deactivated_profile}</td></tr>
								{/if}
									<tr>
										<td height="27"align="left">{$lang.content.subheader}</td>
									</tr>
								</table>
								<table cellpadding="0" cellspacing="0" border="0">
									<tr>
										<td height="27" width="120">{$lang.content.fname}&nbsp;:&nbsp;<span class="error">*</span></td>
										<td><input type="text" class="str" height="27" size="35" name="fname" id="fname" value="{$data.fname}" onblur="javascript: CheckCorrect(this);"></td>
										<td class="error_div" name="fname_div" id="fname_div" style="display: none;">{$lang.content.incorrect_field}</td>
									</tr>
									<tr>
										<td height="27" width="120">{$lang.content.sname}&nbsp;:&nbsp;<span class="error">*</span></td>
										<td><input type="text" class="str" size="35" name="sname" id="sname" value="{$data.sname}" onblur="javascript: CheckCorrect(this);"></td>
										<td class="error_div" name="sname_div" id="sname_div" style="display: none;">{$lang.content.incorrect_field}</td>
									</tr>
									<tr>
										<td height="27" width="120">{$lang.content.email}&nbsp;:&nbsp;<span class="error">*</span><input type="hidden" value="{$data.email}" name="login" id="login"></td>
										<td><input type="text" class="str" size="35" name="email" id="email" value="{$data.email}" onblur="{literal} if ( CheckCorrect(this) &&  this.value!='{/literal}{$data.email}{literal}' ) { UserEmailCheck(this.value); {/literal} }; "></td>
										<td class="error_div"><div id="email_div" name="email_div" style="display: none;">{$lang.content.incorrect_field}</div><div id="email_error" name="email_error" style="display: none;">{$lang.content.email_exists}</div></td>
									</tr>
									<tr>
										<td height="27" width="120">{$lang.content.phone}&nbsp;:&nbsp;</td>
										<td colspan="2"><input type="text" class="str" size="35" name="phone" id="phone" value="{$data.phone}"></td>
									</tr>
									{if $lang_menu}
									<tr>
										<td height="27">{$lang.content.interface_language}&nbsp;:&nbsp;</td>
										<td>
											<table cellpadding="0" cellspacing="0" border="0">
												<tr>
												{section name=m loop=$lang_menu}
													{if $lang_menu[m].vis eq 1}
													<td><input type="radio" name="lang_id" value="{$lang_menu[m].id_lang}" {if $lang_menu[m].id_lang==$data.lang_id}checked{/if}></td>
													<td>{$lang_menu[m].value}&nbsp;&nbsp;</td>
													{/if}
												{/section}
													<td class="hint" style="padding-left: 10px;">{$lang.content.interface_language_help}</td>
												</tr>
											</table>
										</td>
										
									</tr>
									{else}
										<input type="hidden" name="lang_id" value="{$data.lang_id}">
									{/if}
								</table>
							</td>
						</tr>
					</table>
					<table cellpadding="0" cellspacing="0" border="0" width="100%">
						<tr>
							<td><input type="hidden" id="user_type_copy" name="user_type_copy" value="{$data.user_type_copy}">
								
								<table cellpadding="0" cellspacing="0" border="0">								
								<tr>
									<td height="27" width="120" style="padding-left: 5px;">{$lang.content.you_registered_as}:</td>
									<td><input type="radio" id="user_type_1" name="user_type" value="1" {if $data.user_type eq 1} checked {/if}
			  {if $data.user_type eq 3 && $data.agency_approve eq 1}onclick="alert('{$lang.content.need_delete_realtor}{$data.agency_name}'); document.getElementById('user_type_3').checked = 'true';"
			  {elseif $data.user_type eq 2 && $data.have_agents eq 1}onclick="alert('{$lang.content.need_delete_agents}'); document.getElementById('user_type_2').checked = 'true';" 
			  {else} onclick="DivVision(this.value);"
			  {/if}></td>
									<td>{$lang.content.private_person}&nbsp;&nbsp;</td>
									<td><input type="radio" id="user_type_2" name="user_type" value="2" {if $data.user_type eq 2} checked {/if}
			  {if $data.user_type eq 3 && $data.agency_approve eq 1}onclick="alert('{$lang.content.need_delete_realtor}{$data.agency_name}'); document.getElementById('user_type_3').checked = 'true';"
			  {else} onclick="DivVision(this.value);"
			  {/if}></td>			
									<td>{$lang.content.agency}</td>	
									{if $use_agent_user_type}		
									<td><input type="radio" id="user_type_3" name="user_type" value="3" {if $data.user_type eq 3} checked {/if}
			   {if $data.user_type eq 2 && $data.have_agents eq 1}onclick="alert('{$lang.content.need_delete_agents}'); document.getElementById('user_type_2').checked = 'true';"
			  {else} onclick="DivVision(this.value);"
			  {/if}></td>			
									<td>{$lang.content.agent_of_agency}&nbsp;&nbsp;</td>
									{/if}
								</tr>																					
								</table>
								
								<table cellpadding="0" cellspacing="0" border="0" width="100%">					
									<tr>
										<td width="120"></td>
										<td ><div id="user_type_err" class="error" style="display: none;">&nbsp;*</div></td>
									</tr>
									<tr>
										<td colspan="2" style="padding-left:25px;">
										<div id="realtor_div_0" style="display:{if $data.user_type ==3 }inline{else}none{/if};">
										<table cellpadding="0" cellspacing="0" border="0">
											<tr>
												<td width="120"></td>
												<td align="left" id="id_input_agency" style="display:none; "><input type="text" size="35" id="agency_name" name="agency_name" class="str" onblur="CheckAgencyName(this.value, '{$data.user_id}');" value="{$data.agency_name}"></td>							
												<td align="left"><div  id="na_agency_error" class="error" style="display: none;padding-left:10px;"></div></td>
											</tr>
										</table>	
										<table cellpadding="0" cellspacing="0" border="0" style="padding-top:5px;">	
											<tr>
												<td width="120"></td>
												<td align="left" id="id_choose_agency" style="padding-left:0px; display:none;">
												<a id="choose_company_href" style="display:{if $data.agency_approve == -1}inline{else}none{/if};" onclick="javascript: return OpenParentWindow('{$server}{$site_root}/registration.php?sel=choose_company');">{$lang.content.choose_company}</a>
												<a id="decline_company_href" onclick="document.getElementById('id_company').value = 0; document.account_form.action = '{$file_name}?sel=save_profile{if $redirect}&redirect={$redirect}{/if}'; document.account_form.submit();" style="display:{if $data.agency_approve == 0}inline{else}none{/if};">{$lang.content.decline_company}&nbsp;{$data.agency_name}</a>
												<a id="delete_company_href" onclick="document.getElementById('id_company').value = 0; document.account_form.action = '{$file_name}?sel=save_profile{if $redirect}&redirect={$redirect}{/if}'; document.account_form.submit();" style="display:{if $data.agency_approve == 1}inline{else}none{/if};">{$lang.content.delete_company}&nbsp;{$data.agency_name}</a>
												<div id="nbsp" style="display:{if $data.agency_approve == 0 || $data.agency_approve == 1}inline{else}none{/if};">&nbsp;</div>
												<a id="change_company_href" style="display:{if $data.agency_approve == 0 || $data.agency_approve == 1}inline{else}none{/if};" onclick="javascript: return OpenParentWindow('{$server}{$site_root}/registration.php?sel=choose_company');">{$lang.content.change_company}</a>&nbsp;
												</td>						
											</tr>
										</table>	
										<table cellpadding="0" cellspacing="0" border="0" style="padding-top:5px;">	
											{if $data.agency_approve == 0}			
											<tr>
												<td width="120"></td>
												<td><div id="not_approve_hint" class="error" style="padding-top:4px; padding-left:0px;display:none;">*{$lang.content.not_approve}</div></td>
											</tr>
											{/if}
										</table>	
										<table cellpadding="0" cellspacing="0" border="0" style="padding-top:5px;">		
											<tr>			
												<td width="120"></td>
												<td  align="justify" class="hint" id="hint_3" style="display: none; padding-left:0px;">{$lang.content.hint_3}
												<input type="hidden" name="id_company" id="id_company" value="{$data.id_agency}">
												</td>							
											</tr>		
										</table>
										</div>
										</td>
									</tr>	
								</table>
								<hr>				
								<table cellpadding="0" cellspacing="0" border="0" width="100%">
								<tr>
									<td>							
										<div id="realtor_div_1" {if $data.agency_approve != 1 || $data.user_type !=3} style="display: none;" {/if}>			
										<table cellpadding="0" cellspacing="0" border="0" style="padding-left:5px;" width="100%">
											<tr>
												<td width="120" valign="top">{$lang.content.your_photo}&nbsp;:&nbsp;</td>
												<td>{if $data.photo_path !=''}<img src="{$data.photo_path}" style="border-style: solid; border-width: 1px; border-color: #cccccc; padding: 3px;">{else}
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
											{/if}</td>
											{if $data.use_photo_approve && $data.admin_approve != 1}						
												<td align="right" valign="top">
													<font class="error">
													{if $data.admin_approve == 0}
														{$lang.content.admin_approve_not_complete}
													{elseif $data.admin_approve == 2}
														{$lang.content.admin_approve_decline}
													{/if}
													</font>
												</td>
											{/if}									
											</tr>
										</table>
										<table>	
											{if $data.photo_path != ''}
											<tr >	
												<td width="120" style="padding-top:5px;">
												</td>				
												<td style="padding-top:5px;">											
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
												</td>																				
											</tr>
											{/if}
										</table>
										<table cellpadding="0" cellspacing="0" border="0" width="100%">
											<tr>
												<td height="10"><hr></td>
											</tr>
										</table>
										<table cellpadding="0" cellspacing="0" border="0" style="padding-left:5px;">
										<tr >	
											<td width="120" style="padding-top:5px;" height="27">
											{$lang.content.company_name}:
											</td>				
											<td style="padding-top:5px;">											
											{$data.agency_name}	
											</td>																				
										</tr>
										{if $data.agency_url != ''}	
										<tr >	
											<td width="120"  height="27">
											{$lang.content.agency_url}:
											</td>				
											<td>											
											<a href="{$data.agency_url}">{$data.agency_url}</a>	
											</td>																				
										</tr>		
										{/if}
										{if $data.agency_logo_path !='' && $data.logo_approve == 1}
										<tr >	
											<td width="120"  height="27">
											{$lang.content.agency_logo}:
											</td>				
											<td >
											
											<img src="{$data.agency_logo_path}" style="border-style: solid; border-width: 1px; border-color: #cccccc; padding: 3px;">
											</td>																				
										</tr>									
										{/if}
										{if $data.agency_phone}
										<tr>
											<td height="27">{$lang.content.phone}:</td>
											<td>{$data.agency_phone}</td>
										</tr>
										{/if}
										
										{if (($data.country_name)||($data.region_name)||($data.city_name)||($data.ag_address))}
										<tr>
											<td height="27">{$lang.content.location}:</td>
											<td>
												{$data.country_name}{if $data.region_name}, {$data.region_name}{/if}{if $data.city_name}, {$data.city_name}{/if}{if $data.ag_address}, {$data.ag_address}{/if}
											</td>
										</tr>
										{/if}
										{if $profile.country_name && $use_maps_in_account && $profile.in_base && $data.user_type eq 3 && $data.agency_approve eq 1}
										<tr>
											<td colspan="2" style="padding: 0px; padding-top: 10px; padding-bottom: 10px;">
												<table border="0" width="100%"><tr><td>
												<div id="map_container" {if $map.name == "mapquest"} style="width: 330px; height: 330px;" {elseif $map.name == "microsoft"}style="position: relative; width: 480px; height: 320px;"{/if}></div>
												</td></tr></table>
											</td>
										</tr>					
										{/if}
										{if $data.weekday}
										<tr>
											<td height="27">{$lang.content.work_days}:</td>
											<td>
											{foreach name=week key=key item=item from=$week}
												{if $data.weekday.$key eq $item.id }{$item.name}{/if}
											{/foreach}
											</td>
										</tr>
										{/if}
										{if $data.work_time_begin > 0 || $data.work_time_end > 0}
										<tr>
											<td height="27">{$lang.content.work_time}:</td>
											<td>{$lang.content.time_begin}&nbsp;
											{foreach item=item from=$time_arr}
												{if $data.work_time_begin eq $item.value}{$item.value}{/if}
											{/foreach}
											&nbsp;{$lang.content.time_end}&nbsp;
											{foreach item=item from=$time_arr}
												{if $data.work_time_end eq $item.value}{$item.value}{/if}
											{/foreach}
											</td>
										</tr>
										{/if}
										{if $data.lunch_time_begin > 0 || $data.lunch_time_end > 0}
										<tr>
											<td height="27">{$lang.content.lunch_time}:</td>
											<td>{$lang.content.time_begin}&nbsp;
											{foreach item=item from=$time_arr}
												{if $data.lunch_time_begin eq $item.value}{$item.value}{/if}
											{/foreach}
											&nbsp;{$lang.content.time_end}&nbsp;
											{foreach item=item from=$time_arr}
												{if $data.lunch_time_end eq $item.value}{$item.value}{/if}
											{/foreach}
											</td>
										</tr>
										{/if}				
									</table>				
									</div>
									<div id="birth_div_1" {if ($data.user_type ne 1) || $data.agency_approve == 1} style="display: none;" {/if}>
									<table cellpadding="0" cellspacing="0" border="0">
										<tr>
											<td height="35" width="120" style="padding-left: 5px;">{$lang.content.birthday}&nbsp;:&nbsp;</td>
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
									<table cellpadding="0" cellspacing="0" border="0">
										<!-- references -->
										<!-- gender -->
										<tr>
											<td>
											<table cellpadding="5" cellspacing="0" border="0" width="100%">
											{section name=g loop=$gender}
												{if $gender[g].visible_in ne 3}<!--visibility checking-->
													<tr>
														<td width="110" valign="top" style="padding-top: 10px;" bgcolor="#eeeff3">{$gender[g].name}:&nbsp;<input type=hidden name="spr_gender[{$gender[g].num}]" value="{$gender[g].id}"></td>
														<td align="left" bgcolor="#eeeff3">
															<table cellpadding="2" cellspacing="0" border="0">
															{section name=s loop=$gender[g].opt}
															{if $smarty.section.s.index is div by 4}<tr>{/if}
															<td width="15" height="30"><input type="radio" name="gender[{$gender[g].num}][]" value="{$gender[g].opt[s].value}"  {if $gender[g].opt[s].sel} checked {/if}></td>
															<td>{$gender[g].opt[s].name}</td>
															{if $smarty.section.s.index_next is div by 4 || $smarty.section.s.last}</tr>{/if}
															{/section}
															</table>
														</td>
													</tr>
													<tr>
														<td colspan="2" style="padding-left: 5px;" bgcolor="#eeeff3">
															<table cellpadding="0" cellspacing="0">
															<tr>
																<td><!--<span class="blue_link" onclick="javascript: SelAll('gender',{$smarty.section.g.index}, 'account_form');">{$lang.content.sel_all_text}</span></td>
																<td style="padding-left: 10px;">--><span class="blue_link" onclick="UnSelAll('gender',{$smarty.section.g.index}, 'account_form');">{$lang.content.unsel_radio_text}</span></td>
															</tr>
															</table>
														</td>
													</tr>
												{/if}
											{/section}
											</table>
											</td>
										</tr>
										<!-- /gender -->
										<!-- people -->
										<tr>
											<td>
											<table cellpadding="5" cellspacing="0" border="0" width="100%">
											{section name=p loop=$people}
												{if $people[p].visible_in ne 3}<!--visibility checking-->
													<tr>
														<td width="110" valign="top" style="padding-top: 10px;" {if $smarty.section.p.index is not div by 2 && $people[p].des_type eq 1}bgcolor="#eeeff3"{/if}>{$people[p].name}:&nbsp;<input type=hidden name="spr_people[{$people[p].num}]" value="{$people[p].id}"></td>
														{if $people[p].des_type eq 2}
														<td align="left">
															<select id="people{$people[p].num}" name="people[{$people[p].num}][]"  style="width:150px" {if $people[p].type eq 2}multiple{/if}>
															<option value="" {if !$item.sel && $people[p].type eq 1} selected {/if} >{$lang.content.no_answer}</option>
															{foreach item=item from=$people[p].opt}<option value="{$item.value}" {if $item.sel}selected{/if}>{$item.name}</option>{/foreach}
															</select>
														</td>
														{else}
														<td align="left" {if $smarty.section.p.index is not div by 2}bgcolor="#eeeff3"{/if}>
															<table cellpadding="2" cellspacing="0" border="0">
															{section name=s loop=$people[p].opt}
															{if $smarty.section.s.index is div by 4}<tr>{/if}
															<td width="15" height="30"><input {if $people[p].type eq 1}type="radio"{elseif $people[p].type eq 2}type="checkbox"{/if} name="people[{$people[p].num}][]" value="{$people[p].opt[s].value}"  {if $people[p].opt[s].sel} checked {/if}></td>
															<td width="120">{$people[p].opt[s].name}</td>
															{if $smarty.section.s.index_next is div by 4 || $smarty.section.s.last}</tr>{/if}
															{/section}
															</table>
														</td>
														{/if}
													</tr>
													{if $people[p].des_type eq 1}
													<tr>
														<td colspan="2" style="padding-left: 5px;" {if $smarty.section.p.index is not div by 2}bgcolor="#eeeff3"{/if}>
															<table cellpadding="0" cellspacing="0">
															<tr>
																<td>
																{if $people[p].type eq 2}
																	<span class="blue_link" onclick="javascript: SelAll('people',{$smarty.section.p.index}, 'account_form');">{$lang.content.sel_all_text}</span></td>
																	<td style="padding-left: 10px;">
																{/if}
																<span class="blue_link" onclick="UnSelAll('people',{$smarty.section.p.index}, 'account_form');">{if $people[p].type eq 2}{$lang.content.unsel_all_text}{else}{$lang.content.unsel_radio_text}{/if}</span></td>
															</tr>
															</table>
														</td>
													</tr>
													{/if}
												{/if}
											{/section}
											</table>
											</td>
										</tr>
										<!-- /people -->
										<!-- language -->
										<tr>
											<td>
											<table cellpadding="5" cellspacing="0" border="0" width="100%">
											{section name=f loop=$language}
												{if $language[f].visible_in ne 3}<!--visibility checking-->
													<tr>
														<td width="110" valign="top" style="padding-top: 10px;" {if $smarty.section.p.total is not div by 2}bgcolor="#eeeff3"{/if}>{$language[f].name}:&nbsp;<input type=hidden name="spr_language[{$language[f].num}]" value="{$language[f].id}"></td>
														<td align="left" {if $smarty.section.p.total is not div by 2}bgcolor="#eeeff3"{/if}>
															<table cellpadding="2" cellspacing="0" border="0">
															{section name=s loop=$language[f].opt}
															{if $smarty.section.s.index is div by 4}<tr>{/if}
															<td width="15" height="30"><input type="checkbox" name="language[{$language[f].num}][]" value="{$language[f].opt[s].value}"  {if $language[f].opt[s].sel} checked {/if}></td>
															<td width="120">{$language[f].opt[s].name}</td>
															{if $smarty.section.s.index_next is div by 4 || $smarty.section.s.last}</tr>{/if}
															{/section}
															</table>
														</td>
													</tr>
													<tr>
														<td colspan="2" style="padding-left: 5px;" {if $smarty.section.p.index is not div by 2}bgcolor="#eeeff3"{/if}>
															<table cellpadding="0" cellspacing="0">
															<tr>
																<td><span class="blue_link" onclick="javascript: SelAll('language',{$smarty.section.f.index}, 'account_form');">{$lang.content.sel_all_text}</span></td>
																<td style="padding-left: 10px;"><span class="blue_link" onclick="UnSelAll('language',{$smarty.section.f.index}, 'account_form');">{$lang.content.unsel_all_text}</span></td>
															</tr>
															</table>
														</td>
													</tr>
												{/if}
											{/section}
											</table>
											</td>
										</tr>
										<!-- /language -->
										<!-- /references -->
									</table>
									</div>
									<div id="agency_div" {if $data.user_type ne 2} style="display: none;" {/if} >
									<table cellpadding="5" cellspacing="0" border="0" width="100%">
										<tr>
											<td>
												<table cellpadding="0" cellspacing="0" border="0" width="100%">
												<tr>
													<td height="27" width="120">{$lang.content.company_name}:&nbsp;<span id="company_name_error" class="error">*</span></td>
													<td><input type="text" class="str" name="company_name" id="company_name" value="{$data.company_name}" size="25"></td>
												</tr>
												<tr>
													<td height="27" width="120">{$lang.content.company_url}:&nbsp;</td>
													<td><input type="text" class="str" name="company_url" id="company_url" value="{$data.company_url}" size="25" maxlength="50"></td>
												</tr>
												<tr>
													<td height="27" width="120" valign="top">{$lang.content.our_logo}:&nbsp;</td>
													<td>
														<table cellpadding="0" cellspacing="0" border="0" width="100%">
															<tr>
																<td style="padding-top: 5px; padding-bottom: 5px;">{if $data.logo_path !=''}<img src="{$data.logo_path}" style="border-style: solid; border-width: 1px; border-color: #cccccc; padding-top: 3px; padding-bottom: 3px; padding-left: 3px; padding-right: 3px;">{/if}</td>
																{if $data.logo_path !=''}
																	{if $data.use_photo_approve && $data.admin_approve != 1}<td valign="top" align="right">&nbsp;<font class="error">{if $data.admin_approve == 0}{$lang.content.admin_approve_not_complete}{elseif $data.admin_approve == 2}{$lang.content.admin_approve_decline}{/if}</font></td>{/if}
																{/if}															
															</tr>
															<tr>
																<td>
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
												</table>
												<table cellpadding="3" cellspacing="0" border="0">
													<tr>
														<td width="110">{$lang.content.country}:</td>
																<td id="country_div">
																<select name="country" id=country onchange="javascript: {literal} SelectRegion('ip', this.value, document.getElementById('region_div'), document.getElementById('city_div'),'{/literal}{$lang.default_select.ip_load_region}{literal}', '{/literal}{$lang.default_select.ip_city}{literal}'); {/literal}" class="location">
																	<option value="">{$lang.default_select.ip_country}</option>
																		{foreach item=item from=$country}
																	<option value="{$item.id}" {if $data.id_country eq $item.id} selected {/if}>{$item.name}</option>
																		{/foreach}
																</select>
														</td>														
													</tr>
		
													<tr>
														<td>{$lang.content.region}:</td>
																<td id="region_div">
																<select name="region"  id="region" onchange="javascript: {literal} SelectCity('ip', this.value, document.getElementById('city_div'), '{/literal}{$lang.default_select.ip_load_city}{literal}');{/literal}" class="location">
																	<option value="">{$lang.default_select.ip_region}</option>
																		{foreach item=item from=$region}
																	<option value="{$item.id}"  {if $data.id_region eq $item.id} selected {/if}>{$item.name}</option>
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
																	<option value="{$item.id}" {if $data.id_city eq $item.id} selected {/if}>{$item.name}</option>
																		{/foreach}
																</select>
														</td>													
													</tr>
													<tr>
														<td>{$lang.content.address}:</td>
														<td colspan="2">
															<input type="text" class="str" name="address" value='{$data.address}' size="25">
														</td>						
													</tr>
													<tr>
														<td>{$lang.content.post_code}:</td>
														<td colspan="2">
															<input type="text" class="str" name="postal_code" value='{$data.postal_code}' size="25">
														</td>						
													</tr>
															
													{if  $data.in_base && $data.id_country && $use_maps_in_account && $data.user_type eq 2}			
														<tr>
															<td colspan="2" style="padding-top: 10px; padding-bottom: 10px;">
																<div id="map_container" {if $map.name == "mapquest"} style="width: 550px; height: 550px;" {elseif $map.name == "microsoft"}style="position: relative; width: 600px; height: 400px;"{/if}></div>
															</td>													
														</tr>	
													{/if}
												</table>
										<!--<tr>
											<td height="27" width="120">{$lang.content.company_rent_count}:&nbsp;</td>
											<td><input type="text" class="str" name="company_rent_count" id="company_rent_count" value="{$data.company_rent_count}" size="10"></td>
										</tr>
										<tr>
											<td height="27" width="120">{$lang.content.company_how_know}:&nbsp;</td>
											<td><textarea cols="90" rows="3" name="company_how_know" id="company_how_know">{$data.company_how_know}</textarea></td>
										</tr>
										<tr>
											<td height="27" width="120">{$lang.content.company_quests_comments}:&nbsp;</td>
											<td><textarea cols="90" rows="3" name="company_quests_comments" id="company_quests_comments">{$data.company_quests_comments}</textarea></td>
										</tr>-->
											</td>
										</tr>
									</table>					
									<table cellpadding="0" cellspacing="0" border="0" style="display:none; margin-left: 8px;" id="week">			
										<tr>
											<td height="27" width="112">{$lang.content.work_days}:&nbsp;</td>
											<td>
												<table cellpadding="0" cellspacing="0" border="0" width="100%">
													<tr>
													{foreach key=key item=item from=$week}
														<td><input type="checkbox" name="weekday[{$key}]" {if $data.weekday.$key eq $item.id } checked {/if} value="{$item.id}"></td>
														<td>{$item.name}</td>
													{/foreach}
													</tr>
												</table>
											</td>
										</tr>
									</table>	
									<table cellpadding="0" cellspacing="0" border="0" style="display:none; margin-left: 8px;" style="display:none" id="work">
										<tr>
											<td width="112">{$lang.content.work_time}:&nbsp;</td>
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
													<td><div id="incorrect_time" style="display:none" class="error">{$lang.content.incorrect_time_interval}</div></td>
												</tr>
												</table>
											</td>
										</tr>
									</table>
									<table cellpadding="0" cellspacing="0" border="0" style="display:none; margin-left: 8px;" style="display:none" id="lunch">
										<tr>
											<td width="112">{$lang.content.lunch_time}:&nbsp;</td>
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
													<td><div id="incorrect_lunch" style="display:none;" class="error">{$lang.content.incorrect_time_interval}</div></td>
												</tr>
												</table>
											</td>
										</tr>
									</table>	
								</div>	
									
								<table cellpadding="0" cellspacing="0" width="100%" border="0" style="margin-top: 10px;">
									<tr><td colspan="2" class="subheader"><b>{$lang.headers.account_subscribtion}</b></td></tr>
									<tr>
										<td style="padding-left: 12px;">
										<table cellpadding="0" cellspacing="3" border="0">
										{foreach item=item from=$alerts}
											<tr>
												<td><input type="checkbox" value="{$item.id}" name="alert[{$item.id}]" {if $item.sel}checked{/if}></td>
												<td>{$item.name}</td>
											</tr>
										{/foreach}
										</table>
										</td>
									</tr>
									{if $use_pilot_module_sms_notifications}
									<tr><td colspan="2" class="subheader"><b>{$lang.sms_notifications.sms_subscribtion}</b></td></tr>
									<tr>
										<td style="padding-left: 12px;">
										{if !$sms_balance}	
											<table cellpadding="1" cellspacing="3" border="0" style="padding-top:5px;padding-bottom:5px;">		
											<tr><td>							
											{$lang.sms_notifications.empty_sms_balance}&nbsp;<a href='{$link_to_sms}' target="_blank">{$lang.sms_notifications.want_sms_link}</a>
											</td></tr>
											</table>
										{else}										
											<table cellpadding="0" cellspacing="3" border="0">
											{foreach from=$sms_cases item=item key=key}
											{assign var=lang_str value=about_$item}
											<tr>
												<td>					
													<input type="checkbox" id="sms_price_{$key}" name="sms_price[{$key}]" {if $sms_user_event[$key]}checked{/if}>
												</td>
												<td>
												{$lang.sms_notifications[$lang_str]}
												</td>
											</tr>	
											{/foreach}
											
											</table>
											<table width="100%">
											<tr>
												<td align="right">
													<a href='{$link_to_sms}' target="_blank">{$lang.sms_notifications.sms_more_info}</a>
												</td>
											</tr>
											</table>	
											
										{/if}	
										</td>
									</tr>
									{/if}

									<tr><td style="padding-left: 17px"><input name="account_submit" id="account_submit" type="button" class="btn_small" value="{$lang.buttons.save}" onclick="javascript: {literal} if (CheckValues()==true) {document.account_form.action = '{/literal}{$file_name}?sel=save_profile{if $redirect}&redirect={$redirect}{/if}{literal}'; document.account_form.submit();{/literal}}; "></td></tr>
								</table>
							</td>
						</tr>
						</table>									
				</td>
			</tr>
			</table>
		</td>
	</tr>
</table>									
		
		</form>
{elseif $section eq 2}
		<form method="POST" action="" name="deactivate_form" id="deactivate_form">
		<input type="hidden" name="sel" value="from_deactivation">
		<input type="hidden" name="user_type" value="{$user_type}">
		<table cellpadding="0" cellspacing="0" width="100%" border="0">
		<tr><td colspan="2" class="subheader"><b>{$lang.headers.account_deactivate}</b></td></tr>
		<tr>
			<td width="12px">&nbsp;</td>
			<td>
			<table cellpadding="0" cellspacing="3" border="0" width="100%">
			{section name=f loop=$deactivate}
				<tr>
					<td align="left" width="160px;">{$deactivate[f].name}:</td>
					<td align="left"><input type=hidden name="spr_deactivate[{$deactivate[f].num}]" value="{$deactivate[f].id}">
						<select id="deactivate{$deactivate[f].num}" name="deactivate[{$deactivate[f].num}][]"  style="width:150px">
						{foreach item=item from=$deactivate[f].opt}<option value="{$item.value}" {if $item.sel} selected {/if}>{$item.name}</option>{/foreach}
						</select>
					</td>
				</tr>
			{/section}
			<tr>
				<td width="160px;">{$lang.content.addition_comment}:</td>
				<td><textarea name="comment" id="comment" rows="10" cols="50"></textarea></td>
			</tr>
			<tr><td colspan="2">
				<input type="button" value="{$lang.content.button_deactivate}" class="btn_small" onclick="javascript: if ({$need_delete}) alert('{if $user_type eq 2}{$lang.content.delete_agents}{elseif $user_type eq 3}{$lang.content.delete_realtor}{/if}'); else  {literal}{{/literal} document.deactivate_form.action='{$file_name}'; document.deactivate_form.submit();{literal}}{/literal}">
			</td></tr>
			</table>
			</td>
		</tr>
		</table>
		</form>
{elseif $section eq 3}
		<form method="POST" action="" name="password_form" id="password_form">
		<table cellpadding="0" cellspacing="0" width="100%" border="0">
		<tr><td colspan="2" class="subheader"><b>{$lang.headers.account_password}</b></td></tr>
		<tr>
			<td width="12px">&nbsp;</td>
			<td>
			<table cellpadding="3" cellspacing="3" border="0" width="100%">
			<tr>
				<td width="160px">{$lang.content.old_password}&nbsp;:&nbsp;</td>
				<td><input type="password" class="str" size="35" name="old_password" id="old_password" value="" onblur="CheckOldPass();"></td>
				<td class="error_div"><div id="old_password_div" name="old_password_div" style="display: none;">{$lang.content.incorrect_field}</div><div id="wrong_password_div" name="wrong_password_div" style="display: none;">{$lang.content.wrong_password}</div></td>
			</tr>
			<tr>
				<td>{$lang.content.new_password}&nbsp;:&nbsp;</td>
				<td><input type="password" class="str" size="35" name="new_password" id="new_password" value="" onblur="CheckCorrect(this);"></td>
				<td class="error_div"><div id="new_password_div" name="new_password_div" style="display: none;">{$lang.content.incorrect_field}</div></td>
			</tr>
			<tr>
				<td>{$lang.content.new_password_verify}&nbsp;:&nbsp;</td>
				<td><input type="password" class="str" size="35" name="new_password_verify" id="new_password_verify" value="" onblur="CheckCorrect(this);"></td>
				<td class="error_div"><div id="new_password_verify_div" name="new_password_verify_div" style="display: none;">{$lang.content.incorrect_field}</div></td>
			</tr>
			<tr>
				<td colspan="3">&nbsp;</td>
			</tr>
			<tr><td colspan="3">
				<input type="button" value="{$lang.buttons.save}" class="btn_small" onclick="javascript: {literal} if (CheckPassValues()==true) { document.password_form.action='{/literal}{$file_name}?sel=save_password{literal}'; document.password_form.submit();}{/literal};">
			</td></tr>
			</table>
			</td>
		</tr>
		</table>
		</form>
{/if}
		
</td>
	</tr>
</table>
</td>
</tr>



{if ($data.user_type eq 2  && $data.id_country && $data.in_base && $use_maps_in_account) || ($data.user_type eq 3 && $data.agency_approve eq 1 && $profile.in_base && $use_maps_in_account)}
{include file="$gentemplates/viewmap.tpl"}
{/if}

{literal}

<script>

var doc = null;
function ajax() {
	// Make a new XMLHttp object
	if (typeof window.ActiveXObject != 'undefined' ) doc = new ActiveXObject("Microsoft.XMLHTTP");
	else doc = new XMLHttpRequest();
}

function UserEmailCheck(email) {
    	ajax();
	    if (doc){
	       doc.open("GET", "location.php?sec=hp&sel=email&email=" + email, false);
	       doc.send(null);
	    	// Write the response to the div
	    	//destination.innerHTML = doc.responseText;
	    	if (doc.responseText=='exists'){
	    		document.getElementById('email_error').style.display = '';
   				return 'exists';
	    	} else {
	    		document.getElementById('email_error').style.display = 'none';
	    	}
	    }
	    else{
	       alert ('Browser unable to create XMLHttp Object');
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
			document.getElementById('birthdate_div').style.display = '';
		} else {
			document.getElementById('birthdate_div').style.display = 'none';
		}
	return true;
}

function CheckValues() {
	if (document.getElementById('fname').value == ""){
		document.getElementById('fname_div').style.display = '';
		return false;
	} else if (document.getElementById('sname').value == ""){
		document.getElementById('sname_div').style.display = '';
		return false;
	} else if (document.getElementById('email').value == "" || document.getElementById('email').value.search('^.+@.+\\..+$') == -1){
		document.getElementById('email_div').style.display = '';
		return false;
	} else if (document.getElementById('email').value != "" && document.getElementById('email').value != "{/literal}{$data.email}{literal}" && UserEmailCheck(document.getElementById('email').value) == 'exists'){
		return false;
	} else {
		if (document.getElementById('user_type_copy').value == 1){
			if (checkDate('birth') == 0){
				document.getElementById('birthdate_div').style.display = '';
				return false;
			} else {
				document.getElementById('birthdate_div').style.display = 'none';
			}
		}
	}
	return true;
}

function CheckCorrect(obj) {
	if (obj.name == 'fname') {
		if (obj.value !="") {
			document.getElementById('fname_div').style.display = 'none';
		} else {
			document.getElementById('fname_div').style.display = '';
		}
	}
	if (obj.name == 'sname') {
		if (obj.value !="") {
			document.getElementById('sname_div').style.display = 'none';
		} else {
			document.getElementById('sname_div').style.display = '';
		}
	}
	if (obj.name == 'new_password') {
		if (obj.value !="" && obj.value.length>=4 && obj.value.length<=10) {
			document.getElementById('new_password_div').style.display = 'none';
		} else {
			document.getElementById('new_password_div').style.display = '';
		}
	}
	if (obj.name == 'new_password_verify') {
		if (obj.value !="" && obj.value.length>=4 && obj.value.length<=10 && obj.value == document.getElementById('new_password').value) {
			document.getElementById('new_password_verify_div').style.display = 'none';
		} else {
			document.getElementById('new_password_verify_div').style.display = '';
		}
	}
	if (obj.name == 'email') {
		if (obj.value !="" && obj.value.search('^.+@.+\\..+$') != -1) {
			document.getElementById('email_div').style.display = 'none';
		} else {
			document.getElementById('email_div').style.display = '';
		}
	}
	return true;
}

function CheckOldPass() {
	if (document.getElementById('old_password').value == ""){
		document.getElementById('old_password_div').style.display = '';
		document.getElementById('wrong_password_div').style.display = 'none';
	} else if (hex_md5(document.getElementById('old_password').value) != '{/literal}{$data.password}{literal}') {
		document.getElementById('old_password_div').style.display = 'none';
		document.getElementById('wrong_password_div').style.display = '';
	} else {
		document.getElementById('old_password_div').style.display = 'none';
		document.getElementById('wrong_password_div').style.display = 'none';
	}
}

function CheckPassValues(){
	if (document.getElementById('new_password').value == "" || document.getElementById('new_password').value.length<4 || document.getElementById('new_password').value.length>10){
		document.getElementById('new_password_div').style.display = '';
		return false;
	}
	if (hex_md5(document.getElementById('old_password').value) != '{/literal}{$data.password}{literal}'){
		document.getElementById('wrong_password_div').style.display = '';
		return false;
	}
	if (document.getElementById('new_password_verify').value != document.getElementById('new_password').value){
		document.getElementById('new_password_verify_div').style.display = '';
		return false;
	}

	document.getElementById('new_password_div').style.display = 'none';
	document.getElementById('new_password_verify_div').style.display = 'none';
	document.getElementById('wrong_password_div').style.display = 'none';
	return true;
}

function DivVision(id_div){
	if ( id_div == '1' ) {
		document.getElementById('user_type_copy').value = id_div;
		document.getElementById('agency_div').style.display = 'none';
		document.getElementById('user_type_err').style.display = 'none';
		document.getElementById('birth_div_1').style.display = 'inline';
		document.getElementById('realtor_div_0').style.display = 'none';
		document.getElementById('realtor_div_1').style.display = 'none';
		document.getElementById('week').style.display = 'none';
		document.getElementById('work').style.display = 'none';
		document.getElementById('lunch').style.display = 'none';
		document.getElementById('hint_3').style.display = 'none';
		document.getElementById('id_input_agency').style.display = 'none';
		document.getElementById('id_choose_agency').style.display = 'none';
		document.getElementById('na_agency_error').style.display = 'none';
		{/literal}{if $data.agency_approve == 0}{literal}
		document.getElementById('not_approve_hint').style.display = 'none';
		{/literal}{/if}{literal}
	} else if ( id_div == '2' ) {
		document.getElementById('user_type_copy').value = id_div;
		document.getElementById('agency_div').style.display = 'inline';
		document.getElementById('user_type_err').style.display = 'none';
		document.getElementById('birth_div_1').style.display = 'none';
		document.getElementById('realtor_div_0').style.display = 'none';
		document.getElementById('realtor_div_1').style.display = 'none';
		document.getElementById('week').style.display = 'block';
		document.getElementById('work').style.display = 'block';
		document.getElementById('lunch').style.display = 'block';
		document.getElementById('hint_3').style.display = 'none';
		document.getElementById('id_input_agency').style.display = 'none';
		document.getElementById('id_choose_agency').style.display = 'none';
		document.getElementById('na_agency_error').style.display = 'none';
		{/literal}{if $data.agency_approve == 0}{literal}
		document.getElementById('not_approve_hint').style.display = 'none';
		{/literal}{/if}{literal}
	} else if ( id_div == '3' ) {		
		document.getElementById('user_type_copy').value = id_div;
		document.getElementById('agency_div').style.display = 'none';
		document.getElementById('user_type_err').style.display = 'none';
		
		document.getElementById('week').style.display = 'none';
		document.getElementById('work').style.display = 'none';
		document.getElementById('lunch').style.display = 'none';		
		document.getElementById('hint_3').style.display = 'inline';
		document.getElementById('id_input_agency').style.display = 'inline';
		document.getElementById('id_choose_agency').style.display = 'inline';
		document.getElementById('realtor_div_0').style.display = 'inline';		
		{/literal}{if $data.agency_approve == 1}{literal}
		document.getElementById('birth_div_1').style.display = 'none';
		
		document.getElementById('realtor_div_1').style.display = 'inline';
		{/literal}{elseif $data.agency_approve == 0}{literal}
		document.getElementById('not_approve_hint').style.display = 'inline';
		document.getElementById('birth_div_1').style.display = 'inline';
		{/literal}{else}{literal}
		document.getElementById('birth_div_1').style.display = 'inline';
		
		{/literal}{/if}{literal}
		}
	
	return;
}

DivVision({/literal}{$data.user_type}{literal});

function SelAll(name, value, form_name){
	element = document.forms[form_name].elements;
	new_name = name+'['+value+']'+'[]';
	for (i=0; i < element.length; i++) {
		if (element[i].name == new_name && (element[i].type == 'checkbox' || element[i].type == 'radio')){
			element[i].checked = true;
		}
	}
	return;
}
function UnSelAll(name, value, form_name){
	element = document.forms[form_name].elements;
	new_name = name+'['+value+']'+'[]';
	for (i=0; i < element.length; i++) {
		if (element[i].name == new_name && (element[i].type == 'checkbox' || element[i].type == 'radio')){
			element[i].checked = false;
		}
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
 		if (doc){
	       doc.open("GET", "location.php?sec=hp&sel=agency_name&agency_name=" + name + "&user_id=" + user, false);
	       doc.send(null);
	    	// Write the response to the div
	    	//destination.innerHTML = doc.responseText;
	    	if (doc.responseText.substr(0,3) != '|-|'){
	    		document.getElementById('na_agency_error').style.display = 'none';	    		
	    		if (document.getElementById('id_company').value != doc.responseText){
	    			document.getElementById('decline_company_href').style.display = 'none';
	    			document.getElementById('delete_company_href').style.display = 'none';
	    			document.getElementById('nbsp').style.display = 'none';
	    		}
	    		if (doc.responseText != document.getElementById('id_company').value){
	    			{/literal}{if $data.agency_approve == 0}{literal}
	    			document.getElementById('not_approve_hint').style.display = 'none';
	    			{/literal}{/if}{literal}
	    		}
	    		document.getElementById('id_company').value = doc.responseText;	   	    		
					return ;
	    	} else if(doc.responseText.substr(3,3) == 'yet'){
	    		if (doc.responseText.substr(6, doc.responseText.length - 6) != '{/literal}{$data.agency_name}{literal}'){
	    			document.getElementById('na_agency_error').style.display = 'inline';
					document.getElementById('na_agency_error').innerHTML = "&nbsp;{/literal}{$lang.content.with_company_1}&nbsp;&quot;" + doc.responseText.substr(6, doc.responseText.length - 6) + "&quot;&nbsp;{$lang.content.with_company_2}{literal}";	
	    		}
	    		
				document.getElementById('agency_name').value = '{/literal}{$data.agency_name}{literal}';
				if (document.getElementById('agency_name').value != ''){
					{/literal}{if $user.agency_approve == 1}{literal}
						document.getElementById('delete_company_href').style.display = 'inline';					
					{/literal}{else}{literal}
						document.getElementById('decline_company_href').style.display = 'inline';					
					{/literal}{/if}{literal}						
					document.getElementById('nbsp').style.display = 'inline';					
				}
	    		
	    	} else {				
				if (name != ''){
					document.getElementById('na_agency_error').style.display = 'inline';
					document.getElementById('na_agency_error').innerHTML = "&nbsp;{/literal}{$lang.content.na_agency_1}&nbsp;&quot;" + doc.responseText.substr(3, doc.responseText.length - 3) + "&quot;&nbsp;{$lang.content.na_agency_2}{literal}";
				}else{
					{/literal}{if $data.agency_name != ''}{literal}
					document.getElementById('na_agency_error').style.display = 'inline';
					document.getElementById('na_agency_error').innerHTML = "&nbsp;{/literal}{$lang.content.na_agency_3}{literal}";
					{/literal}{else}{literal}
					document.getElementById('na_agency_error').style.display = 'none';
					{/literal}{/if}{literal}
				}
				document.getElementById('agency_name').value = '{/literal}{$data.agency_name}{literal}'
				if (document.getElementById('agency_name').value != ''){
					{/literal}{if $data.agency_approve == 1}{literal}
						document.getElementById('delete_company_href').style.display = 'inline';					
					{/literal}{else}{literal}
						document.getElementById('decline_company_href').style.display = 'inline';					
					{/literal}{/if}{literal}						
					document.getElementById('nbsp').style.display = 'inline';			
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


{if $map.name == "microsoft"}</body>{/if}
{include file="$gentemplates/site_footer.tpl"}
