{include file="$admingentemplates/admin_top.tpl"}

	<table cellpadding="0" cellspacing="0" border="0" width="100%">
	<tr><td class="header">{strip}
	{if $data.section=='langeditfile'}
		{$lang.menu.content_management} <font class="subheader">| {$lang.menu.sections_management} | {$lang.menu.menu_and_content}</font>
	{elseif $data.section=='admin'}		
		{$lang.menu.realestate} |<font class="subheader"> {$lang.menu.realestate} |&nbsp;
		{assign var='section_name' value="section_"|cat:$data.section}
		{$lang.content[$section_name]}</font>
	{else}
		{$lang.content.page_header}&nbsp;|&nbsp;<font class="subheader">{$lang.content.page_subheader}{if $data.section}&nbsp;|&nbsp;{/if}
		{assign var='section_name' value="section_"|cat:$data.section}
		{$lang.content[$section_name]}</font>
	{/if}{/strip}
	</td></tr>
	{assign var='section_help' value="settings_"|cat:$data.section|cat:"_help"}
	<tr>
		<td><div class="help_text"><span class="help_title">{$lang.default_select.help}</span>{$lang.content[$section_help]}</div></td>
	</tr>
	<tr><td>
		<TABLE cellpadding="0" cellspacing="0" border="0" width="100%">
		{if $data.section=='admin'}
		<!--admin section begin-->
			<TR><TD>
			<div class="table_title">{$lang.content.password_change}:</div>
			<form method="POST" name="admin_pass" id="admin_pass" action="{$file_name}">
			<input type="hidden" name="sel" value="">
			<input type="hidden" name="section" value="pass_change">
			<table cellpadding="3" cellspacing="0" border="0" width="100%">
				<tr>
					<td width="20%" align="left">{$lang.content.new_password}:&nbsp;</td>
					<td width="80%"><input type="password" name="password" id="password" size="40" value=""></td>
				</tr>
				<tr>
					<td>{$lang.content.repassword}:&nbsp;</td>
					<td><input type="password" name="repassword" id="repassword" size="40" value=""></td>
				</tr>
				<tr>
					<td>{$lang.content.oldpassword}:&nbsp;</td>
					<td><input type="password" name="oldpassword" id="oldpassword" size="40" value=""></td>
				</tr>
				<tr>
				<td colspan="2">
					<input type="button" class="button_3" value="{$lang.buttons.save}" onclick="javascript: document.admin_pass.sel.value='save'; document.admin_pass.submit();">
				</td></tr>
			</table>
			</form>
			<br>
			<div class="table_title">{$lang.content.admin_info}:</div>
			<form method="POST" name="admin_form" id="admin_form" action="{$file_name}" enctype="multipart/form-data">
			<input type="hidden" name="sel" value="">
			<input type="hidden" name="section" value="{$data.section}">
			{if $redirect}
			<input type="hidden" name="redirect" value="{$redirect}">
			{/if}
			<table cellpadding="3" cellspacing="0" border="0" width="100%">
			<tr>
				<td width="20%">{$lang.content.site_email}:&nbsp;</td>
				<td width="80%"><input type="text" name="site_email" size="40" value="{$data.site_email}"></td>
			</tr>
			<tr>
				<td>{$lang.content.login}:&nbsp;</td>
				<td><input type="text" name="login" size="40" value="{$data.login}"></td>
			</tr>
			<tr>
				<td>{$lang.content.fname}&nbsp;:&nbsp;<span class="error">*</span></td>
				<td><input type="text" name="fname" id="fname" size="40" value="{$data.fname}" style="float:left;" onblur="javascript: CheckCorrect(this);"><div align="left" class="error" name="fname_div" id="fname_div" style="display: none;">&nbsp;{$lang.content.incorrect_field}</div></td>
			</tr>
			<tr>
				<td>{$lang.content.sname}&nbsp;:&nbsp;<span class="error">*</span></td>
				<td><input type="text" name="sname" id="sname" size="40" value="{$data.sname}" style="float:left;" onblur="javascript: CheckCorrect(this);"><div align="left" class="error" name="sname_div" id="sname_div" style="display: none;">&nbsp;{$lang.content.incorrect_field}</div></td>
			</tr>
			<tr>
				<td>{$lang.content.email}&nbsp;:&nbsp;<span class="error">*</span></td>
				<td><input type="text" name="email" id="email" size="40" value="{$data.email}" style="float:left;" onblur="javascript: CheckCorrect(this);"><div align="left" class="error" name="email_div" id="email_div" style="display: none;">&nbsp;{$lang.content.incorrect_field}</div><div id="email_error" name="email_error" style="display: none;">{$lang.content.email_exists}</div></td>
			</tr>
			<tr>
				<td>{$lang.content.phone}&nbsp;:&nbsp;</td>
				<td><input type="text" name="phone" size="40" value="{$data.phone}"></td>
			</tr>
			{if $admin_lang_menu_visible_cnt > 1}
			<tr>
				<td>{$lang.content.interface_language}:</td>
				<td>{strip}
					{section name=lang_menu loop=$admin_lang_menu}
						{if $admin_lang_menu[lang_menu].vis eq 1}
							<input type="radio" name="lang_id" value="{$admin_lang_menu[lang_menu].id_lang}" {if $admin_lang_menu[lang_menu].id_lang eq $data.lang_id}checked{/if}>&nbsp;{$admin_lang_menu[lang_menu].value}&nbsp;&nbsp;
						{/if}
					{/section}
					{/strip}
				</td>
			</tr>
			{else}
			<input type="hidden" name="lang_id" value="{$data.lang_id}">
			{/if}
			<tr>
				<td>{$lang.content.you_registered_as}:</td>
				<td>
					<table cellpadding="0" cellspacing="0" border="0">
					<tr>						
						<td><input type="radio" id="user_type_1" name="user_type" style="margin:5px;" value="1" {if $data.user_type eq 1} checked {/if}
  {if $data.user_type eq 3 && $data.agency_approve eq 1}onclick="alert('{$lang.content.need_delete_realtor}{$data.agency_name}'); document.getElementById('user_type_3').checked = 'true';"
  {elseif $data.user_type eq 2 && $data.have_agents eq 1}onclick="alert('{$lang.content.need_delete_agents}'); document.getElementById('user_type_2').checked = 'true';" 
  {else} onclick="DivVision(this.value);"
  {/if}><input type="hidden" id="user_type_copy" name="user_type_copy" value="{$data.user_type_copy}"></td>							
						<td>{$lang.content.private_person}&nbsp;&nbsp;</td>
						
						<td><input type="radio" id="user_type_2" name="user_type" style="margin:5px;" value="2" {if $data.user_type eq 2} checked {/if}
  {if $data.user_type eq 3 && $data.agency_approve eq 1}onclick="alert('{$lang.content.need_delete_realtor}{$data.agency_name}'); document.getElementById('user_type_3').checked = 'true';"
  {else} onclick="DivVision(this.value);"
  {/if}></td>			
						<td>{$lang.content.agency}</td>		
						{if $use_agent_user_type}	
						<td><input type="radio" id="user_type_3" name="user_type" style="margin:5px;" value="3" {if $data.user_type eq 3} checked {/if}
   {if $data.user_type eq 2 && $data.have_agents eq 1}onclick="alert('{$lang.content.need_delete_agents}'); document.getElementById('user_type_2').checked = 'true';"
  {else} onclick="DivVision(this.value);"
  {/if}></td>						
						
						<td>{$lang.content.agent_of_agency}&nbsp;&nbsp;</td>	
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
			<div id="agent_div" style="display:{if $data.user_type == 3}inline{else}none{/if};">
			<table cellpadding="0" cellspacing="0" border="0" width="100%" style="padding-left:30px;">											
				<tr>
					<td  width="20%"></td>
					<td>
					<div>
						<table cellpadding="0" cellspacing="0" border="0">
							<tr>
							
							<td align="left" height="30" id="id_input_agency" style="display:;"><input type="text" size="35" id="agency_name" name="agency_name" class="" onblur="CheckAgencyName(this.value, '1');" value="{$data.agency_name}"></td>			
							<td align="right"><div  id="na_agency_error" class="error" style="display: none;padding-left:10px;"></div></td>					
							</tr>
							<tr>
								<td colspan="2" align="left" id="id_choose_agency">
							
								
							<a id="choose_company_href" style="display:{if $data.agency_approve == -1}inline{else}none{/if};" onclick="javascript: return OpenParentWindow('{$server}{$site_root}/registration.php?sel=choose_company&id_user_exc=1');">{$lang.content.choose_company}</a>
							
							<a id="decline_company_href" onclick="document.getElementById('id_company').value = 0; document.admin_form.action = '{$file_name}';document.admin_form.sel.value = 'save'; document.admin_form.submit();" style="display:{if $data.agency_approve == 0}inline{else}none{/if};">{$lang.content.decline_company}&nbsp;{$data.agency_name}</a>
							
							<a id="delete_company_href" onclick="document.getElementById('id_company').value = 0; document.admin_form.action = '{$file_name}';document.admin_form.sel.value = 'save'; document.admin_form.submit();" style="display:{if $data.agency_approve == 1}inline{else}none{/if};">{$lang.content.delete_company}&nbsp;{$data.agency_name}</a>
							
							{if $data.agency_approve == 0 || $data.agency_approve == 1}&nbsp;{/if}	
							
							<a id="change_company_href" style="display:{if $data.agency_approve == 0 || $data.agency_approve == 1}inline{else}none{/if};" onclick="javascript: return OpenParentWindow('{$server}{$site_root}/registration.php?sel=choose_company&id_user_exc=1');">{$lang.content.change_company}</a>&nbsp;	
								</td>																						
							</tr>
						</table>
					</div>
					</td>
				</tr>						
				{if $data.agency_approve == 0}			
				<tr>	
					<td  width="20%"></td>						
					<td id="not_approve_hint" class="error" style="padding-top:6px;">*{$lang.content.not_approve}</td>
				</tr>
				{/if}
				<tr>
					<td  width="20%"></td>			
				
					<td align="justify" id="hint_3" style=" padding-top:6px;padding-bottom: 5px;"><small>{$lang.content.hint_3}</small>
				<input type="hidden" name="id_company" id="id_company" value="{$data.id_agency}">
					</td>
				
				</tr>							
			</table>
			</div>			
			
			<div id="birth_div_1" {if $data.user_type == 2 || ($data.user_type == 3 && $data.agency_approve == 1)} style="display: none;" {/if}>
				<table cellpadding="3" cellspacing="0" border="0" width="100%">
					<tr>
						<td width="20%">{$lang.content.birthday}&nbsp;:&nbsp;</td>
						<td width="80%">
							<div style="float:left;">
								<select name="birth_month" id="birth_month" onchange="javascript: MyCheck();">
									{foreach item=item from=$month}<option value="{$item.value}" {if $item.sel}selected{/if}>{$item.name}</option>{/foreach}
								</select>
								<select name="birth_day" id="birth_day" onchange="javascript: MyCheck();">
									{foreach item=item from=$day}<option value="{$item.value}" {if $item.sel}selected{/if}>{$item.value}</option>{/foreach}
								</select>
								<select name="birth_year" id="birth_year" onchange="javascript: MyCheck();">
									{foreach item=item from=$year}<option value="{$item.value}" {if $item.sel}selected{/if}>{$item.value}</option>{/foreach}
								</select>
							</div>
							<div align="left" class="error" name="birthdate_div" id="birthdate_div" style="display: none;">&nbsp;{$lang.content.incorrect_date}</div>
						</td>
					</tr>
					<!-- references -->

					<!-- gender -->
					{section name=g loop=$gender}
					{if $gender[g].visible_in ne 3}<!--visibility checking-->
					<tr>
						<td class="spr_colored">
							{$gender[g].name}:&nbsp;<input type=hidden name="spr_gender[{$gender[g].num}]" value="{$gender[g].id}"><br><br>
							<!--<span class="blue_link" onclick="javascript: SelAll('gender',{$smarty.section.g.index}, 'admin_form');">{$lang.content.sel_all_text}</span></td>
							<td style="padding-left: 10px;">--><span class="blue_link" onclick="UnSelAll('gender',{$smarty.section.g.index}, 'admin_form');">{$lang.content.unsel_radio_text}</span>
						</td>
						<td class="spr_colored">
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
					{/if}
					{/section}
					<!-- /gender -->

					<!-- people -->
					{section name=p loop=$people}
					{if $people[p].visible_in ne 3}<!--visibility checking-->
					<tr>
						<td valign="top" {if $smarty.section.p.index is not div by 2 && $people[p].des_type eq 1}class="spr_colored"{/if}>
							{$people[p].name}:&nbsp;<input type=hidden name="spr_people[{$people[p].num}]" value="{$people[p].id}"><br><br>
							{if $people[p].des_type eq 1}
								{if $people[p].type eq 2}
									<span class="blue_link" onclick="javascript: SelAll('people',{$smarty.section.p.index}, 'admin_form');">{$lang.content.sel_all_text}</span>
									&nbsp;&nbsp;&nbsp;
								{/if}
								<span class="blue_link" onclick="UnSelAll('people',{$smarty.section.p.index}, 'admin_form');">{if $people[p].type eq 2}{$lang.content.unsel_all_text}{else}{$lang.content.unsel_radio_text}{/if}</span>
							{/if}
						</td>
						{if $people[p].des_type eq 2}
						<td {if $smarty.section.p.index is not div by 2 && $people[p].des_type eq 1}class="spr_colored"{/if}>
							<select id="people{$people[p].num}" name="people[{$people[p].num}][]"  style="width:150px" {if $people[p].type eq 2}multiple{/if}>
								<option value="" {if !$item.sel && $people[p].type eq 1} selected {/if} >{$lang.content.no_answer}</option>
								{foreach item=item from=$people[p].opt}<option value="{$item.value}" {if $item.sel}selected{/if}>{$item.name}</option>{/foreach}
							</select>
						</td>
						{else}
						<td {if $smarty.section.p.index is not div by 2 && $people[p].des_type eq 1}class="spr_colored"{/if}>
							<table cellpadding="2" cellspacing="0" border="0">
								{section name=s loop=$people[p].opt}
								{if $smarty.section.s.index is div by 4}<tr>{/if}
									<td width="15" height="30"><input {if $people[p].type eq 1}type="radio"{elseif $people[p].type eq 2}type="checkbox"{/if} name="people[{$people[p].num}][]" value="{$people[p].opt[s].value}"  {if $people[p].opt[s].sel} checked {/if}></td>
									<td width="100">{$people[p].opt[s].name}</td>
								{if $smarty.section.s.index_next is div by 4 || $smarty.section.s.last}</tr>{/if}
								{/section}
							</table>
						</td>
						{/if}
					</tr>
					{/if}
					{/section}
					<!-- /people -->

					<!-- language -->
					{section name=f loop=$language}
					{if $language[f].visible_in ne 3}<!--visibility checking-->
					<tr>
						<td valign="top" {if $smarty.section.p.total is not div by 2}class="spr_colored"{/if}>
							{$language[f].name}:&nbsp;<input type=hidden name="spr_language[{$language[f].num}]" value="{$language[f].id}"><br><br>
							<span class="blue_link" onclick="javascript: SelAll('language',{$smarty.section.f.index}, 'admin_form');">{$lang.content.sel_all_text}</span>&nbsp;&nbsp;&nbsp;
							<span class="blue_link" onclick="UnSelAll('language',{$smarty.section.f.index}, 'admin_form');">{$lang.content.unsel_all_text}</span>
						</td>
						<td {if $smarty.section.p.total is not div by 2}class="spr_colored"{/if}>
							<table cellpadding="2" cellspacing="0" border="0">
							{section name=s loop=$language[f].opt}
							{if $smarty.section.s.index is div by 4}<tr>{/if}
								<td width="15" height="30"><input type="checkbox" name="language[{$language[f].num}][]" value="{$language[f].opt[s].value}"  {if $language[f].opt[s].sel} checked {/if}></td>
								<td width="100">{$language[f].opt[s].name}</td>
							{if $smarty.section.s.index_next is div by 4 || $smarty.section.s.last}</tr>{/if}
							{/section}
							</table>
						</td>
					</tr>
					{/if}
					{/section}
					<!--/language -->
					<!-- /references -->
				</table>
			</div>
			<div id="agent_company_div" style="display:{if $data.user_type == 3 && $data.agency_approve == 1}inline{else}none{/if};">
				<table cellpadding="5" cellspacing="0" border="0" width="100%">
					
					<tr>
						<td height="35" width="20%" >{$lang.content.your_photo}&nbsp;:&nbsp;</td>
						
						<td >{if $data.photo_path !=''}<img src="{$data.photo_path}" style="border-style: solid; border-width: 1px; border-color: #cccccc; padding: 3px;">{else}
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
																							
					</tr>
					
					{if $data.use_photo_approve && $data.admin_approve != 1}
					<tr>
						<td width="20%" height="27"></td>
						<td align="left">	&nbsp;<font class="error_small">
							{if $data.admin_approve == 0}{$lang.content.admin_approve_not_complete}{elseif $data.admin_approve == 2}{$lang.content.admin_approve_decline}
							{/if}</font>
						</td>
					</tr>
					{/if}
					{if $data.photo_path != ''}
					<tr >	
						<td width="20%" style="padding-top:5px;">
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
					<tr >	
						<td width="20%" style="padding-top:5px;" height="27">
						{$lang.content.company_name}:
						</td>				
						<td style="padding-top:5px;">											
						{$data.agency_name}	
						</td>																				
					</tr>
					{if $data.agency_url != ''}	
					<tr >	
						<td width="20%"  height="27">
						{$lang.content.agency_url}:
						</td>				
						<td>											
						<a href="{$data.agency_url}">{$data.agency_url}</a>	
						</td>																				
					</tr>		
					{/if}
					{if $data.agency_logo_path !='' && $data.logo_approve == 1}
					<tr >	
						<td width="20%"  height="27">
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
				
			<div id="agency_div" {if $data.user_type ne 2} style="display: none;" {/if} >
				<table cellpadding="3" cellspacing="0" border="0" width="100%">
					<tr>
						<td width="20%">{$lang.content.company_name}&nbsp;:&nbsp;<span id="company_name_error" class="error">*</span></td>
						<td width="80%"><input type="text" name="company_name" size="40" id="company_name" value="{$data.company_name}"></td>
					</tr>
					<tr>
						<td>{$lang.content.company_url}&nbsp;:&nbsp;</td>
						<td><input type="text" name="company_url" size="40" id="company_url" value="{$data.company_url}"></td>
					</tr>
					<tr>
						<td>{$lang.content.our_logo}&nbsp;:&nbsp;</td>
						<td>
							<table cellpadding="0" cellspacing="0" border="0">
								
								<tr>
									<td style="padding-top: 5px;">{if $data.logo_path !=''}<img src="{$data.logo_path}" style="border-style: solid; border-width: 1px; border-color: #cccccc; padding-top: 3px; padding-bottom: 3px; padding-left: 3px; padding-right: 3px;">{/if}</td>
									{if $data.logo_path !=''}
										{if $data.use_photo_approve && $data.admin_approve != 1}
											<td>&nbsp;<font class="error_small">
												{if $data.admin_approve == 0}
													{$lang.content.admin_approve_not_complete}
												{elseif $data.admin_approve == 2}
													{$lang.content.admin_approve_decline}
												{/if}</font>
											</td>
										{/if}
									{/if}
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
								<tr>
									<td colspan="2">												
												<tr>
												<td width="20%">{$lang.content.country}:</td>
														<td id="country_div">
														<select name="country" id=country onchange="javascript: {literal} SelectRegion('ip', this.value, document.getElementById('region_div'), document.getElementById('city_div'),'{/literal}{$lang.content.ip_load_region}{literal}', '{/literal}{$lang.content.ip_city}{literal}'); {/literal}" class="location">
															<option value="">{$lang.content.ip_country}</option>
																{foreach item=item from=$country}
															<option value="{$item.id}" {if $data.id_country eq $item.id} selected {/if}>{$item.name}</option>
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
														<option value="{$item.id}"  {if $data.id_region eq $item.id} selected {/if}>{$item.name}</option>
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
															<option value="{$item.id}" {if $data.id_city eq $item.id} selected {/if}>{$item.name}</option>
																{/foreach}
														</select>
												</td>													
											</tr>
											<tr>
												<td >{$lang.content.address}:</td>
												<td >
													<input type="text" name="address" value='{$data.address}' size="40">
												</td>						
											</tr>
											<tr>
												<td>{$lang.content.zipcode}:</td>
												<td >
													<input type="text" name="postal_code" value='{$data.postal_code}' size="40">
												</td>						
											</tr>										
									</td>
								</tr>																
								{if $data.user_type eq 2 && $data.in_base && $data.id_country && $use_maps_in_account}								
								<tr>
									<td colspan="2">
										<div id="map_container" {if $map.name == "mapquest"} style="width: 550px; height: 550px;" {elseif $map.name == "microsoft"}style="position: relative; width: 600px; height: 400px;"{/if}></div>
									</td>
								</tr>								
								{/if}
						</td>
					</tr>
					<!--<tr>
							<td height="27" width="20%">{$lang.content.company_rent_count}:&nbsp;</td>
							<td><input type="text" class="str" name="company_rent_count" id="company_rent_count" value="{$data.company_rent_count}" size="10"></td>
						</tr>
						<tr>
							<td height="27" width="20%">{$lang.content.company_how_know}:&nbsp;</td>
							<td><textarea cols="90" rows="3" name="company_how_know" id="company_how_know">{$data.company_how_know}</textarea></td>
						</tr>
						<tr>
							<td height="27" width="20%">{$lang.content.company_quests_comments}:&nbsp;</td>
							<td><textarea cols="90" rows="3" name="company_quests_comments" id="company_quests_comments">{$data.company_quests_comments}</textarea></td>
						</tr>-->
					<tr>
						<td valign="top">{$lang.content.work_days}:&nbsp;</td>
						<td>
							{foreach key=key item=item from=$week name=week}
								<input type="checkbox" name="weekday[{$key}]" {if $data.weekday.$key eq $item.id } checked {/if} value="{$item.id}">&nbsp;{$item.name}&nbsp;&nbsp;
							{/foreach}
						</td>
					</tr>
					<tr>
						<td valign="top">{$lang.content.work_time}:&nbsp;</td>
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
						<td valign="top">{$lang.content.lunch_time}:&nbsp;</td>
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
			<table cellpadding="3" cellspacing="0" border="0" width="100%">
			<tr>
				<td width="20%">{$lang.content.account_subscribtion}&nbsp;:&nbsp;</td>
				<td width="80%">
					<table cellpadding="0" cellspacing="3" border="0" width="100%">
					{foreach item=item from=$alerts}
					<tr>
						<td colspan="3"><input type="checkbox" value="{$item.id}" name="alert[{$item.id}]" {if $item.sel}checked{/if}> {$item.name}</td>
					</tr>
					{/foreach}
					</table>
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<input type="button" class="button_3" value="{$lang.buttons.save}" onclick="javascript: {literal} if (CheckValues()==true) { document.admin_form.sel.value='save'; document.admin_form.submit();}{/literal} ">
				</td>
			</tr>
			</form>
			</table>
			</TD></TR>
		<!--admin section end-->
		{elseif $data.section=='misc'}
			<!--misc section begin-->
			<TR><TD>
			<form method="POST" name="admin_form" id="admin_form" action="{$file_name}" enctype="multipart/form-data">
			<input type="hidden" name="sel" value="">
			<input type="hidden" name="section" value="{$data.section}">
			<table cellpadding="0" cellspacing="0">
				<tr>
					<td><img src="{$site_root}{$template_root}/images/arrow_down.gif"></td>
					<td class="fast_nav_title"><a class="fast_nav_link" href="#" onclick="javascript: ShowHideDiv('fast_menu');">{$lang.default_select.fast_navigation}</a></td>
				</tr>
				<tr>
					<td colspan="2" class="fast_menu_nav">
					<ul class="fast_navigation" id="fast_menu" style="display: none;">
						<li><a href="#private_person_ads_limit_head">{$lang.content.private_person_ads_limit}</a></li>
						<li><a href="#settings_path">{$lang.content.settings_path}</a></li>
						<li><a href="#settings_num_at_page">{$lang.content.settings_num_at_page}</a></li>
						<li><a href="#settings_age">{$lang.content.settings_age}</a></li>
						<li><a href="#thumb_image">{$lang.content.thumb_image}</a></li>
						<li><a href="#photos">{$lang.content.photos}</a></li>
						<li><a href="#video">{$lang.content.video}</a></li>
						<li><a href="#slideshow">{$lang.content.slideshow}</a></li>
						<li><a href="#ffmpeg_module">{$lang.content.ffmpeg_module}</a></li>
						<li><a href="#price_format">{$lang.content.price_format}</a></li>
						<li><a href="#sq_format">{$lang.content.sq_format}</a></li>
						<li><a href="#headline_preview">{$lang.content.headline_preview}</a></li>
						<li><a href="#ads_activity_period_head">{$lang.content.ads_activity_period}</a></li>
						<li><a href="#sold_leased_status">{$lang.content.sold_leased_status}</a></li>
						<li><a href="#user_types_mode">{$lang.content.user_types_mode}</a></li>						
						<li><a href="#settings_other">{$lang.content.settings_other}</a></li>
						<li><a href="#settings_icons">{$lang.content.settings_icons}</a></li>
					</ul>&nbsp;
					</td>
				</tr>
			</table>

			<div class="table_title" id="private_person_ads_limit_head">{$lang.content.private_person_ads_limit}:</div>
			<div class="help_text_table"><span class="help_title">{$lang.default_select.help}</span>{$lang.content.settings_private_person_ads_limit_help}</div>
			<table cellpadding="3" class="table_main" cellspacing="1" border="0" width="700">
			<tr>
				<td width="45%">{$lang.content.use_private_person_ads_limit}:</td>
				<td>
					<table cellpadding="0" cellspacing="0" border="0" class="table_turn_on">
						<tr>
							<td><input type="checkbox" class="checkbox" id="use_private_person_ads_limit" name="use_private_person_ads_limit" value="1" {if $data.use_private_person_ads_limit eq 1} checked {/if}></td>
							<td>{$lang.content.turn_on}</div></td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td>{$lang.content.number_private_person_ads_limit}:</td>
				<td><input type="text" id="private_person_ads_limit" name="private_person_ads_limit" value="{$data.private_person_ads_limit}">
				&nbsp;<span class="error" id="span_private_person_ads_limit" style="display: none;">*</span></td>
			</tr>
			</table>
			<table style="margin-bottom:10px;">
			<tr bgcolor="#FFFFFF">
			<td>
				<input type="button" class="button_3" value="{$lang.buttons.save}" onclick="javascript: var settings_array_int = new Array('private_person_ads_limit'); var settings_array_checkbox = new Array('use_private_person_ads_limit'); SaveAdminSettings('private_person_ads_limit_span', settings_array_int, settings_array_checkbox, '');">
			</td>
			<td style="padding-left:10px;">
				<span id='private_person_ads_limit_span'></span>
			</td>
			</tr>
			</table>

			<div class="table_title" id="settings_path">{$lang.content.settings_path}:</div>
			<table cellpadding="3" class="table_main" cellspacing="1" border="0" width="700">
			<tr>
				<td width="45%">{$lang.content.index_theme_path}:</td>
				<td>
				<select id="index_theme_path" name="index_theme_path" class="str" style="padding-left:0px; width:250px;">
					{foreach from=$theme_array item=item}
					<option  value="/templates/{$item}" {if $data.index_theme_path == "/templates/$item"}selected='true'{/if}>/templates/{$item}</option>
					{/foreach}
					</select>
				&nbsp;<span class="error" id="span_index_theme_path" style="display: none;">*</span>
					
				</td>
			</tr>
			<tr>
				<td>{$lang.content.admin_theme_path}:</td>
				<td><input type="text" id="admin_theme_path" name="admin_theme_path" id="admin_theme_path" style="width: 250px;" value="{$data.admin_theme_path}">
				&nbsp;<span class="error" id="span_admin_theme_path" style="display: none;">*</span></td>
			</tr>
			<tr>
				<td>{$lang.content.index_theme_css_path}:</td>
				<td><input type="text" id="index_theme_css_path" name="index_theme_css_path" id="index_theme_css_path" value="{$data.index_theme_css_path}">
				&nbsp;<span class="error" id="span_index_theme_css_path" style="display: none;">*</span></td>
			</tr>
			<tr>
				<td>{$lang.content.index_theme_images_path}:</td>
				<td><input type="text" id="index_theme_images_path" name="index_theme_images_path" id="index_theme_images_path" value="{$data.index_theme_images_path}">
				&nbsp;<span class="error" id="span_index_theme_images_path" style="display: none;">*</span></td>
			</tr>
			<tr>
				<td>{$lang.content.menu_path}:</td>
				<td><input type="text" id="menu_path" name="menu_path" id="menu_path" value="{$data.menu_path}">
				&nbsp;<span class="error" id="span_menu_path" style="display: none;">*</span></td>
			</tr>
			<tr>
				<td>{$lang.content.photo_folder}:</td>
				<td><input type="text" id="photo_folder" name="photo_folder" id="photo_folder" value="{$data.photo_folder}">
				&nbsp;<span class="error" id="span_photo_folder" style="display: none;">*</span></td>
			</tr>
			<tr>
				<td>{$lang.content.slideshow_folder}:</td>
				<td><input type="text" id="slideshow_folder" name="slideshow_folder" id="slideshow_folder" value="{$data.slideshow_folder}">
				&nbsp;<span class="error" id="span_slideshow_folder" style="display: none;">*</span></td>
			</tr>
			</table>
			<table style="margin-bottom:10px;">
			<tr bgcolor="#FFFFFF">
			<td>
				<input type="button" class="button_3" value="{$lang.buttons.save}" onclick="javascript: var settings_array_str = new Array('index_theme_path', 'admin_theme_path', 'index_theme_css_path', 'index_theme_images_path', 'menu_path', 'photo_folder', 'slideshow_folder'); SaveAdminSettings('settings_path_span', '', '', settings_array_str);">
			</td>
			<td style="padding-left:10px;">
				<span id='settings_path_span'></span>
			</td>
			</tr>
			</table>

			<div class="table_title" id="settings_num_at_page">{$lang.content.settings_num_at_page}:</div>
			<table cellpadding="3" class="table_main" cellspacing="1" border="0" width="700">
			<tr>
				<td width="45%">{$lang.content.users_num_page}:</td>
				<td><input type="text" name="users_num_page" id="users_num_page" value="{$data.users_num_page}">
				&nbsp;<span class="error" id="span_users_num_page" style="display: none;">*</span></td>
			</tr>
			<tr>
				<td>{$lang.content.news_per_page}:</td>
				<td><input type="text" name="news_per_page" id="news_per_page" value="{$data.news_per_page}">
				&nbsp;<span class="error" id="span_news_per_page" style="display: none;">*</span></td>
			</tr>
			<tr>
				<td>{$lang.content.reference_numpage}:</td>
				<td><input type="text" name="reference_numpage" id="reference_numpage" value="{$data.reference_numpage}">
				&nbsp;<span class="error" id="span_reference_numpage" style="display: none;">*</span></td>
			</tr>
			<tr>
				<td>{$lang.content.ads_num_page}:</td>
				<td><input type="text" name="ads_num_page" id="ads_num_page" value="{$data.ads_num_page}">
				&nbsp;<span class="error" id="span_ads_num_page" style="display: none;">*</span></td>
			</tr>
			<tr>
				<td>{$lang.content.admin_user_ads_numpage}:</td>
				<td><input type="text" name="admin_user_ads_numpage" id="admin_user_ads_numpage" value="{$data.admin_user_ads_numpage}">
				&nbsp;<span class="error" id="span_admin_user_ads_numpage" style="display: none;">*</span></td>
			</tr>
			<tr>
				<td>{$lang.content.admin_rows_per_page}:</td>
				<td><input type="text" name="admin_rows_per_page" id="admin_rows_per_page" value="{$data.admin_rows_per_page}">
				&nbsp;<span class="error" id="span_admin_rows_per_page" style="display: none;">*</span></td>
			</tr>
			<tr>
				<td>{$lang.content.max_ads_admin}:</td>
				<td><input type="text" name="max_ads_admin" id="max_ads_admin" value="{$data.max_ads_admin}">&nbsp;<span class="error" id="span_max_ads_admin" style="display: none;">*</span></td>
				
			</tr>
			</table>
			<table style="margin-bottom:10px;">
			<tr bgcolor="#FFFFFF">
			<td>
				<input type="button" class="button_3" value="{$lang.buttons.save}" onclick="javascript: var settings_array_int = new Array('users_num_page', 'news_per_page', 'reference_numpage', 'ads_num_page', 'admin_user_ads_numpage', 'admin_rows_per_page', 'max_ads_admin'); SaveAdminSettings('settings_num_at_page_span', settings_array_int, '', '');">
			</td>
			<td style="padding-left:10px;">
				<span id='settings_num_at_page_span'></span>
			</td>
			</tr>
			</table>

			<div class="table_title" id="settings_age">{$lang.content.settings_age}:</div>
			<table cellpadding="3" class="table_main" cellspacing="1" border="0" width="700">
			<tr>
				<td width="45%">{$lang.content.min_age_limit}:</td>
				<td><input type="text" name="min_age_limit" id="min_age_limit" value="{$data.min_age_limit}">
				&nbsp;<span class="error" id="span_min_age_limit" style="display: none;">*</span></td>
			</tr>
			<tr>
				<td>{$lang.content.max_age_limit}:</td>
				<td><input type="text" name="max_age_limit" id="max_age_limit" value="{$data.max_age_limit}">
				&nbsp;<span class="error" id="span_max_age_limit" style="display: none;">*</span></td>
			</tr>
			<tr>
				<td>{$lang.content.date_format}:</td>
				<td><input type="text" name="date_format" id="date_format" value="{$data.date_format}">
				&nbsp;<span class="error" id="span_date_format" style="display: none;">*</span></td>
			</tr>
			</table>
			<table style="margin-bottom:10px;">
			<tr bgcolor="#FFFFFF">
			<td>
				<input type="button" class="button_3" value="{$lang.buttons.save}" onclick="javascript: var settings_array_int = new Array('min_age_limit', 'max_age_limit'); var settings_array_str = new Array('date_format'); SaveAdminSettings('settings_age_span', settings_array_int, '', settings_array_str);">
			</td>
			<td style="padding-left:10px;">
				<span id='settings_age_span'></span>
			</td>
			</tr>
			</table>

			<div class="table_title" id="thumb_image">{$lang.content.thumb_image}:</div>
			<div class="help_text_table"><span class="help_title">{$lang.default_select.help}</span>{$lang.content.settings_thumb_image_help}</div>
			<table cellpadding="3" class="table_main" cellspacing="1" border="0" width="700">
			<tr>
				<td width="45%">{$lang.content.max_width}:</td>
				<td><input type="text" name="thumb_max_width" id="thumb_max_width" size="40" value="{$data.thumb_max_width}" style="width: 100px;">
				&nbsp;<span class="error" id="span_thumb_max_width" style="display: none;">*</span></td>
			</tr>
			<tr>
				<td>{$lang.content.max_height}:</td>
				<td><input type="text" name="thumb_max_height" id="thumb_max_height" size="40" value="{$data.thumb_max_height}" style="width: 100px;">
				&nbsp;<span class="error" id="span_thumb_max_height" style="display: none;">*</span></td>
			</tr>
			</table>
			<table style="margin-bottom:10px;">
			<tr bgcolor="#FFFFFF">
			<td>
				<input type="button" class="button_3" value="{$lang.buttons.save}" onclick="javascript: var settings_array_int = new Array('thumb_max_width', 'thumb_max_height'); SaveAdminSettings('thumb_image_span', settings_array_int, '', '');">
			</td>
			<td style="padding-left:10px;">
				<span id='thumb_image_span'></span>
			</td>
			</tr>
			</table>

			<div class="table_title" id="photos">{$lang.content.photos}:</div>
			<div class="help_text_table"><span class="help_title">{$lang.default_select.help}</span>{$lang.content.settings_photos_help}</div>
			<table cellpadding="3" class="table_main" cellspacing="1" border="0" width="700">
			<tr>
				<td width="45%">{$lang.content.use_photo_approve}:</td>
				<td>
					<table cellpadding="0" cellspacing="0" border="0" class="table_turn_on">
						<tr>
							<td><input type="checkbox" class="checkbox" name="use_photo_approve" id="use_photo_approve" value="1" {if $data.use_photo_approve eq 1} checked {/if}></td>
							<td>{$lang.content.turn_on}</div></td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td>{$lang.content.photo_max_user_count}:</td>
				<td><input type="text" name="photo_max_user_count" id="photo_max_user_count" size="40" value="{$data.photo_max_user_count}" style="width: 100px;">
				&nbsp;<span class="error" id="span_photo_max_user_count" style="display: none;">*</span></td>
			</tr>
			<tr>
				<td>{$lang.content.plan_photo_max_user_count}:</td>
				<td><input type="text" name="plan_photo_max_user_count" id="plan_photo_max_user_count" size="40" value="{$data.plan_photo_max_user_count}" style="width: 100px;">
				&nbsp;<span class="error" id="span_plan_photo_max_user_count" style="display: none;">*</span></td>
			</tr>
			<tr>
				<td>{$lang.content.max_size}:</td>
				<td><input type="text" name="photo_max_size" id="photo_max_size" size="40" value="{$data.photo_max_size}" style="width: 100px;">
				&nbsp;<span class="error" id="span_photo_max_size" style="display: none;">*</span></td>
			</tr>
			<tr>
				<td>{$lang.content.max_width}:</td>
				<td><input type="text" name="photo_max_width" id="photo_max_width" size="40" value="{$data.photo_max_width}" style="width: 100px;">
				&nbsp;<span class="error" id="span_photo_max_width" style="display: none;">*</span></td>
			</tr>
			<tr>
				<td>{$lang.content.max_height}:</td>
				<td><input type="text" name="photo_max_height" id="photo_max_height" size="40" value="{$data.photo_max_height}" style="width: 100px;">
				&nbsp;<span class="error" id="span_photo_max_height" style="display: none;">*</span></td>
			</tr>
			</table>
			<table style="margin-bottom:10px;">
			<tr bgcolor="#FFFFFF">
			<td>
				<input type="button" class="button_3" value="{$lang.buttons.save}" onclick="javascript: var settings_array_int = new Array('photo_max_user_count', 'plan_photo_max_user_count', 'photo_max_size', 'photo_max_width', 'photo_max_height'); var settings_array_checkbox = new Array('use_photo_approve'); SaveAdminSettings('photos_span', settings_array_int, settings_array_checkbox, '');">
			</td>
			<td style="padding-left:10px;">
				<span id='photos_span'></span>
			</td>
			</tr>
			</table>

			<div class="table_title" id="video">{$lang.content.video}:</div>
			<div class="help_text_table"><span class="help_title">{$lang.default_select.help}</span>{$lang.content.settings_video_help}</div>
			<table cellpadding="3" class="table_main" cellspacing="1" border="0" width="700">
			<tr>
				<td width="45%">{$lang.content.use_video_approve}:</td>
				<td>
					<table cellpadding="0" cellspacing="0" border="0" class="table_turn_on">
						<tr>
							<td><input type="checkbox" class="checkbox" id="use_video_approve" name="use_video_approve" value="1" {if $data.use_video_approve eq 1} checked {/if}></td>
							<td>{$lang.content.turn_on}</div></td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td>{$lang.content.video_max_user_count}:</td>
				<td><input type="text" id="video_max_count" name="video_max_count" size="40" value="{$data.video_max_count}" style="width: 100px;">
				&nbsp;<span class="error" id="span_video_max_count" style="display: none;">*</span></td>
			</tr>
			<tr>
				<td>{$lang.content.max_size}:</td>
				<td><input type="text" id="video_max_size" name="video_max_size" size="40" value="{$data.video_max_size}" style="width: 100px;">
				&nbsp;<span class="error" id="span_video_max_size" style="display: none;">*</span></td>
			</tr>
			</table>
			<table style="margin-bottom:10px;">
			<tr bgcolor="#FFFFFF">
			<td>
				<input type="button" class="button_3" value="{$lang.buttons.save}" onclick="javascript: var settings_array_int = new Array('video_max_count', 'video_max_size'); var settings_array_checkbox = new Array('use_video_approve'); SaveAdminSettings('video_span', settings_array_int, settings_array_checkbox, '');">
			</td>
			<td style="padding-left:10px;">
				<span id='video_span'></span>
			</td>
			</tr>
			</table>

			<div class="table_title" id="slideshow">{$lang.content.slideshow}:</div>
			<div class="help_text_table"><span class="help_title">{$lang.default_select.help}</span>{$lang.content.settings_slideshow_help}</div>
			<table cellpadding="3" class="table_main" cellspacing="1" border="0" width="700">
			<tr>
				<td width="45%">{$lang.content.max_size}:</td>
				<td><input type="text" name="slideshow_max_size" id="slideshow_max_size" size="40" value="{$data.slideshow_max_size}" style="width: 100px;">
				&nbsp;<span class="error" id="span_slideshow_max_size" style="display: none;">*</span></td>
			</tr>
			</table>
			<table style="margin-bottom:10px;">
			<tr bgcolor="#FFFFFF">
			<td>
				<input type="button" class="button_3" value="{$lang.buttons.save}" onclick="javascript: var settings_array_int = new Array('slideshow_max_size'); SaveAdminSettings('slideshow_span', settings_array_int, '', '');">
			</td>
			<td style="padding-left:10px;">
				<span id='slideshow_span'></span>
			</td>
			</tr>
			</table>

			<div class="table_title" id="ffmpeg_module">{$lang.content.ffmpeg_module}:</div>
			<div class="help_text_table"><span class="help_title">{$lang.default_select.help}</span>{$lang.content.settings_ffmpeg_module_help}</div>
			<table cellpadding="3" class="table_main" cellspacing="1" border="0" width="700">
			<tr>
				<td width="45%">{$lang.content.use_ffmpeg}:</td>
				<td><input type="checkbox" name="use_ffmpeg" id="use_ffmpeg" value="1"  {if $data.use_ffmpeg eq 1}checked{/if}></td>
			</tr>
			<tr>
                <td>{$lang.content.path_to_ffmpeg}:&nbsp;</td>
                <td><input type="text" size="40" name="path_to_ffmpeg" id="path_to_ffmpeg" value="{$data.path_to_ffmpeg}">&nbsp;&nbsp;{$lang.content.example}:&nbsp;{$lang.content.example_path_to_ffmpeg}
                &nbsp;<span class="error" id="span_path_to_ffmpeg" style="display: none;">*</span></td>
            </tr>
            <tr>
                <td>{$lang.content.flv_output_dimension}:&nbsp;</td>
                <td><input type="text" size="40" name="flv_output_dimension" id="flv_output_dimension" value="{$data.flv_output_dimension}">&nbsp;&nbsp;{$lang.content.example}:&nbsp;{$lang.content.example_flv_output_dimension}
                &nbsp;<span class="error" id="span_flv_output_dimension" style="display: none;">*</span></td>
            </tr>
            <tr>
                <td>{$lang.content.flv_output_audio_sampling_rate}:&nbsp;</td>
                <td><input type="text" size="40" name="flv_output_audio_sampling_rate" id="flv_output_audio_sampling_rate" value="{$data.flv_output_audio_sampling_rate}">&nbsp;&nbsp;{$lang.content.example}:&nbsp;{$lang.content.example_flv_output_audio_sampling_rate}
                &nbsp;<span class="error" id="span_flv_output_audio_sampling_rate" style="display: none;">*</span></td>
            </tr>
            <tr>
                <td>{$lang.content.flv_output_audio_bit_rate}:&nbsp;</td>
                <td><input type="text" size="40" name="flv_output_audio_bit_rate" id="flv_output_audio_bit_rate" value="{$data.flv_output_audio_bit_rate}">&nbsp;&nbsp;{$lang.content.example}:&nbsp;{$lang.content.example_flv_output_audio_bit_rate}
                &nbsp;<span class="error" id="span_flv_output_audio_bit_rate" style="display: none;">*</span></td>
            </tr>
            <tr>
                <td>{$lang.content.flv_output_foto_dimension}:&nbsp;</td>
                <td><input type="text" size="40" name="flv_output_foto_dimension" id="flv_output_foto_dimension" value="{$data.flv_output_foto_dimension}">&nbsp;&nbsp;{$lang.content.example}:&nbsp;{$lang.content.example_flv_output_foto_dimension}
                &nbsp;<span class="error" id="span_flv_output_foto_dimension" style="display: none;">*</span></td>
            </tr>
			</table>
			<table style="margin-bottom:10px;">
			<tr bgcolor="#FFFFFF">
			<td>
				<input type="button" class="button_3" value="{$lang.buttons.save}" onclick="javascript: var settings_array_checkbox = new Array('use_ffmpeg'); var settings_array_int = new Array('flv_output_audio_sampling_rate','flv_output_audio_bit_rate'); var settings_array_str = new Array('path_to_ffmpeg', 'flv_output_dimension', 'flv_output_foto_dimension'); SaveAdminSettings('ffmpeg_module_span', settings_array_int, settings_array_checkbox, settings_array_str);">
			</td>
			<td style="padding-left:10px;">
				<span id='ffmpeg_module_span'></span>
			</td>
			</tr>
			</table>

			<div class="table_title" id="price_format">{$lang.content.price_format}:</div>
			<div class="help_text_table"><span class="help_title">{$lang.default_select.help}</span>{$lang.content.settings_price_format_help}</div>

			<table cellpadding="3" class="table_main" cellspacing="1" border="0" width="700">
			<tr>
				<td width="45%">{$lang.content.cur_format}:				
				</td>
				<td>
				<select name="cur_format" id="cur_format" onchange="ChangeExample('example',document.getElementById('thousands_separator').value, document.getElementById('cur_position').value, this.value);" style="width:120px;">
					<option value="abbr" {if $data.cur_format == "abbr"}selected{/if}>{$lang.content.cur_abbr}</option>
					<option value="symbol" {if $data.cur_format == "symbol"}selected{/if}>{$lang.content.cur_symbol}</option>
					
				</select>
				&nbsp;<span class="error" id="span_cur_format" style="display: none;">*</span>
				</td>
			</tr>
			<tr>
				<td width="45%">{$lang.content.cur_position}:				
				</td>
				<td>
				<select name="cur_position" id="cur_position" onchange="ChangeExample('example',document.getElementById('thousands_separator').value, this.value, document.getElementById('cur_format').value);" style="width:120px;">
					<option value="begin" {if $data.cur_position == "begin"}selected{/if}>{$lang.content.begin_position}</option>
					<option value="end" {if $data.cur_position == "end"}selected{/if}>{$lang.content.end_position}</option>
					
				</select>
				&nbsp;<span class="error" id="span_cur_position" style="display: none;">*</span>
				</td>
			</tr>
			<tr>
				<td width="45%">{$lang.content.thousands_separator}:</td>
				<td>
				<table border="0" cellpadding="0" cellspacing="0">
				<tr>
				<td>
				<select name="thousands_separator" id="thousands_separator" onchange="ChangeExample('example',this.value, document.getElementById('cur_position').value, document.getElementById('cur_format').value);" style="width:120px;">
					<option value="nbsp" {if $data.thousands_separator == "nbsp"}selected{/if}>{$lang.content.space_sep}</option>
					<option value="," {if $data.thousands_separator == ","}selected{/if}>{$lang.content.comma_sep}</option>
					<option value="empty" {if $data.thousands_separator == "empty"}selected{/if}>{$lang.content.empty_sep}</option>
				</select>
				&nbsp;<span class="error" id="span_thousands_separator" style="display: none;">*</span>
				</td>
				
				<td style="padding-left:25px;"><span id='example'>{$lang.content.example}:&nbsp;&nbsp;{$example}</span>
				</td>
				</tr>
				</table>

				</td>
			</tr>
			</table>	
			<table style="margin-bottom:10px;">
			<tr bgcolor="#FFFFFF">
			<td>
				<input type="button" class="button_3" value="{$lang.buttons.save}" onclick="javascript:  var settings_array_str = new Array('cur_format', 'cur_position', 'thousands_separator'); SaveAdminSettings('price_format_span', '', '', settings_array_str);">
			</td>
			<td style="padding-left:10px;">
				<span id='price_format_span'></span>
			</td>
			</tr>
			</table>	
			
			<div class="table_title" id="sq_format">{$lang.content.sq_format}:</div>
			<div class="help_text_table"><span class="help_title">{$lang.default_select.help}</span>{$lang.content.settings_sq_format_help}</div>

			<table cellpadding="3" class="table_main" cellspacing="1" border="0" width="700">
			<tr>
				<td width="45%">{$lang.content.sq_format}:</td>
				<td>
					<table cellpadding="0" cellspacing="0">
					<tr>
					<td>
						<input type="text" name="sq_meters" id="sq_meters" value="{$data.sq_meters}">	
					</td>
					<td style="padding-left:25px;">	
						{$lang.content.example}:&nbsp;<span id='example_sq'>{$data.sq_meters}</span>	
						&nbsp;<span class="error" id="span_sq_meters" style="display: none;">*</span></td>
					</td>
					</tr>
					</table>
				</td>		
			</tr>
			<table style="margin-bottom:10px;">
			<tr bgcolor="#FFFFFF">
			<td>
				<input type="button" class="button_3" value="{$lang.buttons.save}" onclick="javascript:  var settings_array_str = new Array('sq_meters');document.getElementById('example_sq').innerHTML=document.getElementById('sq_meters').value; SaveAdminSettings('sq_format_span', '', '', settings_array_str);">
			</td>
			<td style="padding-left:10px;">
				<span id='sq_format_span'></span>
			</td>
			</tr>
			</table>	

			<div class="table_title" id="headline_preview">{$lang.content.headline_preview}:</div>
			<div class="help_text_table"><span class="help_title">{$lang.default_select.help}</span>{$lang.content.headline_preview_help}</div>

			<table cellpadding="3" class="table_main" cellspacing="1" border="0" width="700">
			<tr>
				<td width="45%">{$lang.content.headline_preview_size}:</td>
				<td><input type="text" name="headline_preview_size" id="headline_preview_size" size="40" value="{$data.headline_preview_size}" style="width: 100px;">
				&nbsp;<span class="error" id="span_headline_preview_size" style="display: none;">*</span></td>
			</tr>
			</table>
			<table style="margin-bottom:10px;">
			<tr bgcolor="#FFFFFF">
			<td>
				<input type="button" class="button_3" value="{$lang.buttons.save}" onclick="javascript:  var settings_array_int = new Array('headline_preview_size'); SaveAdminSettings('headline_preview_span', settings_array_int, '', '');">
			</td>
			<td style="padding-left:10px;">
				<span id='headline_preview_span'></span>
			</td>
			</tr>
			</table>	

			<div class="table_title" id="ads_activity_period_head">{$lang.content.ads_activity_period}:</div>
			<div class="help_text_table"><span class="help_title">{$lang.default_select.help}</span>{$lang.content.ads_activity_period_help}</div>

			<table cellpadding="3" class="table_main" cellspacing="1" border="0" width="700">
			<tr>
				<td width="45%">{$lang.content.use_ads_activity_period}:</td>
				<td>
					<table cellpadding="0" cellspacing="0" border="0" class="table_turn_on">
						<tr>
							<td><input type="checkbox" class="checkbox" name="use_ads_activity_period" id="use_ads_activity_period" value="1" {if $data.use_ads_activity_period}checked{/if}></td>
							<td>{$lang.content.turn_on}</div></td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td width="45%">{$lang.content.ads_activity_period_indays}:</td>
				<td><input type="text" name="ads_activity_period" id="ads_activity_period" size="40" value="{$data.ads_activity_period}" style="width: 100px;">
				&nbsp;<span class="error" id="span_ads_activity_period" style="display: none;">*</span></td>
			</tr>
			</table>
			<table style="margin-bottom:10px;">
			<tr bgcolor="#FFFFFF">
			<td>
				<input type="button" class="button_3" value="{$lang.buttons.save}" onclick="javascript:  var settings_array_int = new Array('ads_activity_period'); var settings_array_checkbox = new Array('use_ads_activity_period'); SaveAdminSettings('ads_activity_period_head_span', settings_array_int, settings_array_checkbox, '');">
			</td>
			<td style="padding-left:10px;">
				<span id='ads_activity_period_head_span'></span>
			</td>
			</tr>
			</table>	


			<div class="table_title" id="sold_leased_status">{$lang.content.sold_leased_status}:</div>
			<div class="help_text_table"><span class="help_title">{$lang.default_select.help}</span>{$lang.content.sold_leased_status_help}</div>
			<table cellpadding="3" class="table_main" cellspacing="1" border="0" width="700">
			<tr>
				<td width="45%">{$lang.content.use_sold_leased_status}:</td>
				<td>
					<table cellpadding="0" cellspacing="0" border="0" class="table_turn_on">
						<tr>
							<td><input type="checkbox" class="checkbox" name="use_sold_leased_status" id="use_sold_leased_status" value="1" {if $data.use_sold_leased_status eq 1} checked {/if}></td>
							<td>{$lang.content.turn_on}</td>
						</tr>
					</table>
				</td>
			</tr>
			</table>
			<table style="margin-bottom:10px;">
			<tr bgcolor="#FFFFFF">
			<td>
				<input type="button" class="button_3" value="{$lang.buttons.save}" onclick="javascript:  var settings_array_checkbox = new Array('use_sold_leased_status'); SaveAdminSettings('sold_leased_status_span', '', settings_array_checkbox, '');">
			</td>
			<td style="padding-left:10px;">
				<span id='sold_leased_status_span'></span>
			</td>
			</tr>
			</table>	
			
			<div class="table_title" id="user_types_mode">{$lang.content.user_types_mode}:</div>
			<div class="help_text_table"><span class="help_title">{$lang.default_select.help}</span>{$lang.content.user_types_mode_help}</div>
			<table cellpadding="3" class="table_main" cellspacing="1" border="0" width="700">
			<tr>
				<td width="45%">{$lang.content.use_agent_user_type}:</td>
				<td>
					<table cellpadding="0" cellspacing="0" border="0" class="table_turn_on">
						<tr>
							<td><input type="checkbox" class="checkbox" name="use_agent_user_type"  id="use_agent_user_type" value="1" {if $data.use_agent_user_type eq 1} checked {/if}></td>
							<td>{$lang.content.turn_on}</td>
						</tr>
					</table>
				</td>
			</tr>
			</table>
			<table style="margin-bottom:10px;">
			<tr bgcolor="#FFFFFF">
			<td>
				<input type="button" class="button_3" value="{$lang.buttons.save}" onclick="javascript:  var settings_array_checkbox = new Array('use_agent_user_type'); SaveAdminSettings('user_types_mode_span', '', settings_array_checkbox, '');">
			</td>
			<td style="padding-left:10px;">
				<span id='user_types_mode_span'></span>
			</td>
			</tr>
			</table>	


			<div class="table_title" id="settings_other">{$lang.content.settings_other}:</div>
			<table cellpadding="3" class="table_main" cellspacing="1" border="0" width="700">
			<tr>
				<td width="45%">{$lang.content.use_registration_confirmation}:</td>
				<td>
					<table cellpadding="0" cellspacing="0" border="0" class="table_turn_on">
						<tr>
							<td><input type="checkbox" class="checkbox" name="use_registration_confirmation" id="use_registration_confirmation" value="1" {if $data.use_registration_confirmation}checked{/if}></td>
							<td>{$lang.content.turn_on}</div></td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td>{$lang.content.use_image_resize}:</td>
				<td>
					<table cellpadding="0" cellspacing="0" border="0" class="table_turn_on">
						<tr>
							<td><input type="checkbox" class="checkbox" name="use_image_resize" id="use_image_resize" value="1" {if $data.use_image_resize eq 1} checked {/if}></td>
							<td>{$lang.content.turn_on}</div></td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td>{$lang.content.show_contact_for_unreg_users}:</td>
				<td>
					<table cellpadding="0" cellspacing="0" border="0" class="table_turn_on">
						<tr>
							<td><input type="checkbox" class="checkbox" name="contact_for_unreg" id="contact_for_unreg" value="1" {if $data.contact_for_unreg eq 1} checked {/if}></td>
							<td>{$lang.content.turn_on}</div></td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td>{$lang.content.show_contact_for_free_users}:</td>
				<td>
					<table cellpadding="0" cellspacing="0" border="0" class="table_turn_on">
						<tr>
							<td><input type="checkbox" class="checkbox" name="contact_for_free" id="contact_for_free" value="1" {if $data.contact_for_free eq 1} checked {/if}></td>
							<td>{$lang.content.turn_on}</div></td>
						</tr>
					</table>
				</td>
			</tr>
			</table>
			<table style="margin-bottom:10px;">
			<tr bgcolor="#FFFFFF">
			<td>
				<input type="button" class="button_3" value="{$lang.buttons.save}" onclick="javascript:  var settings_array_checkbox = new Array('use_registration_confirmation', 'use_image_resize', 'contact_for_unreg', 'contact_for_free'); SaveAdminSettings('settings_other_span', '', settings_array_checkbox, '');">
			</td>
			<td style="padding-left:10px;">
				<span id='settings_other_span'></span>
			</td>
			</tr>
			</table>	

			<div class="table_title" id="settings_icons">{$lang.content.settings_icons}:</div>
			<div class="help_text_table"><span class="help_title">{$lang.default_select.help}</span>{$lang.content.settings_icons_help}</div>
			<table cellpadding="3" class="table_main" cellspacing="1" border="0" width="700">
			<tr>
				<td>{$lang.content.default_photo_agency}:
					<div style="padding: 0px; padding-top: 5px; padding-bottom: 5px;">
						<img src="{$site_root}{$data.photo_folder}/{$data.default_photo_agency}" border="1" >
					</div>
					
					<div class="fileinputs">
						<input type="file" name="default_photo_agency" id="default_photo_agency" class="file" onchange="document.getElementById('file_text_default_photo_agency').innerHTML = this.value.replace(/.*\\(.*)/, '$1');document.getElementById('file_img_default_photo_agency').innerHTML = ChangeIcon(this.value.replace(/.*\.(.*)/, '$1'));"/>
						<div class="fakefile">
							<table cellpadding="0" cellspacing="0">
							<tr>												
								<td>	
									<input type="button"  class="btn_upload" value="{$lang.buttons.choose}">
								</td>			
								<td style="padding-left:10px;">
									<span id='file_img_default_photo_agency'></span>
								</td>								
								<td style="padding-left:4px;">
									<span id='file_text_default_photo_agency'></span>	
								</td>
							</tr>
							</table>
						</div>
					</div>
				
				&nbsp;<span class="error" id="span_default_photo_agency" style="display: none;">*</span></td>
				<td>{$lang.content.default_photo_male}:
					<div style="padding: 0px; padding-top: 5px; padding-bottom: 5px;">
						<img src="{$site_root}{$data.photo_folder}/{$data.default_photo_male}" border="1" >
					</div>
					<div class="fileinputs">
						<input type="file" name="default_photo_male" id="default_photo_male" class="file" onchange="document.getElementById('file_text_default_photo_male').innerHTML = this.value.replace(/.*\\(.*)/, '$1');document.getElementById('file_img_default_photo_male').innerHTML = ChangeIcon(this.value.replace(/.*\.(.*)/, '$1'));"/>
						<div class="fakefile">
							<table cellpadding="0" cellspacing="0">
							<tr>												
								<td>	
									<input type="button"  class="btn_upload" value="{$lang.buttons.choose}">
								</td>			
								<td style="padding-left:10px;">
									<span id='file_img_default_photo_male'></span>
								</td>								
								<td style="padding-left:4px;">
									<span id='file_text_default_photo_male'></span>	
								</td>
							</tr>
							</table>
						</div>
					</div>
				
				&nbsp;<span class="error" id="span_default_photo_male" style="display: none;">*</span></td>
				<td>{$lang.content.default_photo_female}:
					<div style="padding: 0px; padding-top: 5px; padding-bottom: 5px;">
						<img src="{$site_root}{$data.photo_folder}/{$data.default_photo_female}" border="1" >
					</div>
					<div class="fileinputs">
						<input type="file" name="default_photo_female" id="default_photo_female" class="file" onchange="document.getElementById('file_text_default_photo_female').innerHTML = this.value.replace(/.*\\(.*)/, '$1');document.getElementById('file_img_default_photo_female').innerHTML = ChangeIcon(this.value.replace(/.*\.(.*)/, '$1'));"/>
						<div class="fakefile">
							<table cellpadding="0" cellspacing="0">
							<tr>												
								<td>	
									<input type="button"  class="btn_upload" value="{$lang.buttons.choose}">
								</td>			
								<td style="padding-left:10px;">
									<span id='file_img_default_photo_female'></span>
								</td>								
								<td style="padding-left:4px;">
									<span id='file_text_default_photo_female'></span>	
								</td>
							</tr>
							</table>
						</div>
					</div>
				
				&nbsp;<span class="error" id="span_default_photo_female" style="display: none;">*</span></td>
			</tr>
			<tr>
				<td>{$lang.content.default_photo_fwithc}:
					<div style="padding: 0px; padding-top: 5px; padding-bottom: 5px;">
						<img src="{$site_root}{$data.photo_folder}/{$data.default_photo_fwithc}" border="1" >
					</div>
					
					<div class="fileinputs">
						<input type="file" name="default_photo_fwithc" id="default_photo_fwithc" class="file" onchange="document.getElementById('file_text_default_photo_fwithc').innerHTML = this.value.replace(/.*\\(.*)/, '$1');document.getElementById('file_img_default_photo_fwithc').innerHTML = ChangeIcon(this.value.replace(/.*\.(.*)/, '$1'));"/>
						<div class="fakefile">
							<table cellpadding="0" cellspacing="0">
							<tr>												
								<td>	
									<input type="button"  class="btn_upload" value="{$lang.buttons.choose}">
								</td>			
								<td style="padding-left:10px;">
									<span id='file_img_default_photo_fwithc'></span>
								</td>								
								<td style="padding-left:4px;">
									<span id='file_text_default_photo_fwithc'></span>	
								</td>
							</tr>
							</table>
						</div>
					</div>
				
				&nbsp;<span class="error" id="span_default_photo_fwithc" style="display: none;">*</span></td>
				<td>{$lang.content.default_photo_fwithoutc}:
					<div style="padding: 0px; padding-top: 5px; padding-bottom: 5px;">
						<img src="{$site_root}{$data.photo_folder}/{$data.default_photo_fwithoutc}" border="1" >
					</div>
					<div class="fileinputs">
						<input type="file" name="default_photo_fwithoutc" id="default_photo_fwithoutc" class="file" onchange="document.getElementById('file_text_default_photo_fwithoutc').innerHTML = this.value.replace(/.*\\(.*)/, '$1');document.getElementById('file_img_default_photo_fwithoutc').innerHTML = ChangeIcon(this.value.replace(/.*\.(.*)/, '$1'));"/>
						<div class="fakefile">
							<table cellpadding="0" cellspacing="0">
							<tr>												
								<td>	
									<input type="button"  class="btn_upload" value="{$lang.buttons.choose}">
								</td>			
								<td style="padding-left:10px;">
									<span id='file_img_default_photo_fwithoutc'></span>
								</td>								
								<td style="padding-left:4px;">
									<span id='file_text_default_photo_fwithoutc'></span>	
								</td>
							</tr>
							</table>
						</div>
					</div>
				
				&nbsp;<span class="error" id="span_default_photo_fwithoutc" style="display: none;">*</span></td>
				<td>{$lang.content.default_photo_man}:
					<div style="padding: 0px; padding-top: 5px; padding-bottom: 5px;">
						<img src="{$site_root}{$data.photo_folder}/{$data.default_photo_man}" border="1" >
					</div>
					<div class="fileinputs">
						<input type="file" name="default_photo_man" id="default_photo_man" class="file" onchange="document.getElementById('file_text_default_photo_man').innerHTML = this.value.replace(/.*\\(.*)/, '$1');document.getElementById('file_img_default_photo_man').innerHTML = ChangeIcon(this.value.replace(/.*\.(.*)/, '$1'));"/>
						<div class="fakefile">
							<table cellpadding="0" cellspacing="0">
							<tr>												
								<td>	
									<input type="button"  class="btn_upload" value="{$lang.buttons.choose}">
								</td>			
								<td style="padding-left:10px;">
									<span id='file_img_default_photo_man'></span>
								</td>								
								<td style="padding-left:4px;">
									<span id='file_text_default_photo_man'></span>	
								</td>
							</tr>
							</table>
						</div>
					</div>
				
				&nbsp;<span class="error" id="span_default_photo_man" style="display: none;">*</span></td>
			</tr>
			</table>
			<table>
			<tr bgcolor="#FFFFFF">
			<td colspan="2">
				<input type="button" class="button_3" value="{$lang.buttons.save}" onclick="javascript: document.admin_form.sel.value='save'; document.admin_form.submit();">
			</td></tr>
			</table>
			</form>
			</TD></TR>
		<!--misc section end-->
			{elseif $data.section=='icons'}
		<!--icons section begin-->
			<TR><TD>
			<form method="POST" name="icons_form" action="{$file_name}" enctype="multipart/form-data">
			<input type="hidden" name="sel" value="">
			<input type="hidden" name="section" value="{$data.section}">
			<table cellpadding="3" class="table_main" cellspacing="1" border="0" width="700">
			<tr bgcolor="#FFFFFF"><td class="header" colspan="4">{$lang.content.section_icons}</td></tr>
			{if $no_icons eq 1}
			<tr bgcolor="#FFFFFF">
				<td colspan="4" align="center" class="error">{$lang.content.no_icons}</td>
			</tr>
			{else}
			<tr bgcolor="#FFFFFF">
				<td colspan="4"><table cellpadding="4" cellspacing="4" width="100%" border="0">
				{section name=i loop=$icons}
				{if $smarty.section.i.index is div by 4}<tr>{/if}
				<td align="center" valign="top">
<!--				<div><img src="{$site_root}{$data.icons_folder}/{$icons[i].file_path}" border="1" height="{$data.thumb_max_height}" width="{$data.thumb_max_width}"></div> -->
					<div><img src="{$site_root}{$data.icons_folder}/{$icons[i].file_path}" border="1"></div>
					<div class="main_content_text"><input type="checkbox" name="icons_status[{$icons[i].id}]" value="1" {if $icons[i].status eq 1}checked{/if}>&nbsp;{$lang.content.show_this_icon}</div>
					<div><a href="{$icons[i].del_link}" class="page_link">{$lang.content.delete}</a></div>
				</td>
				{if $smarty.section.i.index_next is div by 4 || $smarty.section.i.last}</tr>{/if}
				{/section}
				</table>
				</td>
			</tr>
			{/if}
			<tr bgcolor="#FFFFFF">
				<td class="main_content_text" align="right">{$lang.content.upload_icon}&nbsp;:&nbsp;</td>
				<td>
					<div class="fileinputs">
						<input type="file" name="icon_image" id="icon_image" class="file" onchange="document.getElementById('file_text_icon_image').innerHTML = this.value.replace(/.*\\(.*)/, '$1');document.getElementById('file_img_icon_image').innerHTML = ChangeIcon(this.value.replace(/.*\.(.*)/, '$1'));"/>
						<div class="fakefile">
							<table cellpadding="0" cellspacing="0">
							<tr>												
								<td>	
									<input type="button"  class="btn_upload" value="{$lang.buttons.choose}">
								</td>			
								<td style="padding-left:10px;">
									<span id='file_img_icon_image'></span>
								</td>								
								<td style="padding-left:4px;">
									<span id='file_text_icon_image'></span>	
								</td>
							</tr>
							</table>
						</div>
					</div>
				</td>
				<td colspan="2">&nbsp;<input type="button" class="button_3" value="{$lang.buttons.upload}" onclick="javascript: document.icons_form.sel.value='upload_icon'; document.icons_form.submit();"></td>
			</tr>
			<tr bgcolor="#FFFFFF">
				<td class="main_content_text" align="right">{$lang.content.icons_folder}&nbsp;:&nbsp;</td>
				<td colspan="3">&nbsp;<input type="text" name="icons_folder" size="40" value="{$data.icons_folder}" style="width: 150px;"></td>
			</tr>
			<tr bgcolor="#FFFFFF">
			<td colspan="4" style="padding-left: 20px;">
				<input type="button" class="button_3" value="{$lang.buttons.save}" onclick="javascript: document.icons_form.sel.value='save'; document.icons_form.submit();">
			</td></tr>
			</table>
			</form>
			</TD></TR>
		<!--uploads section end-->
		<!--langs section begin-->
			{elseif $data.section=='langedit'}
			<TR><TD>
			<form name="lang_form" action="{$form.action}" method="post">
			<input type="hidden" name="par" value="lang_save">
			<input type="hidden" name="sel" value="save">
			<input type="hidden" name="section" value="langedit">
			<table cellpadding="3" cellspacing="1" border="0">
            <tr>
            	<td>{$lang.content.default_site_language}:</td>
            </tr>
            <tr>
            	<td>
            	<table cellpadding="3" cellspacing="0" border="0">
            		{section name=s loop=$language}
            		<tr>
            			<td><input type='checkbox' class='checkbox' name="visible[{$smarty.section.s.index}]" value="{$language[s].value}" {if $language[s].visible}checked{/if}></td>
            			<td>{$language[s].name}</td>
            			<td>{if $smarty.section.s.total > 1}<input type="button" value="{$lang.buttons.delete}" onclick="javascript: if (confirm('{$lang.content.confirm_delete} {$language[s].name}?')) document.location.href='{$file_name}?section=langedit&sel=save&par=lang_delete&lang_id={$language[s].value}';">{/if}</td>
            		</tr>
            		{/section}
            	</table>
            	</td>
            </tr>
            <tr>
				<td>{$lang.content.default_language}:</td>
			</tr>
			<tr>
				<td align="left">
				<select  name="def_l" style="width:195px">
					{section name=lan loop=$language}
					{if $language[lan].visible eq 1}
					<option value="{$language[lan].name}"{if $language[lan].sel}selected{/if}><span id="span1">{$language[lan].name}</span></option>
					{/if}
					{/section}
				</select>
				</td>
			</tr>
            <tr height="10">
            	<td><input type="button" class="button_3" value="{$lang.buttons.save}" onclick="javascript:document.lang_form.submit();"></td>
            </tr>
            </table>
            </form>
            <form name="lang_add_form" action="{$form.action}" method="post">
            <input type="hidden" name="par" value="lang_add">
            <input type="hidden" name="sel" value="save">
            <input type="hidden" name="section" value="langedit">
            <br>
            <div class="section_title">{$lang.content.new_site_language}:</div>
			<div class="help_text_wide"><span class="help_title">{$lang.default_select.help}</span>{$lang.content.add_new_language_help}</div>
            <table cellpadding="3" cellspacing="1" border="0">
            	<tr>
            		<td width="35%">{$lang.content.new_name}:</td>

            		<td class="main_content_text">
            			<input type="text" name="name" value="" size="10">
            		</td>
            	</tr>
            	<tr>
            		<td>{$lang.content.base_language}:</td>
					<td class="main_content_text" align="left">
					<select  name="base_lang" style="width:195px">
						{section name=s loop=$language}
						<option value="{$language[s].value}">{$language[s].name}
						{/section}
					</select>
					</td>
				</tr>
            	<tr>
            		<td colspan="2">
            			<input type="button" class="button_1" value="{$lang.buttons.add}" onclick="javascript: document.lang_add_form.submit();">
            		</td>
            	</tr>
            </table>
            </form>
			</TD></TR>
			<!--langs section end-->
			<!--langs section begin-->
			{elseif $data.section=='langeditfile'}
			<TR><TD>
			<form name="lang_red" action="{$form.action}" method="GET">
				{section name=s loop=$language}
				{if $language[s].sel}{assign var="edit_value" value=$language[s].name}{/if}
				{/section}
				<input type="hidden" name="edit" value="{$edit_value}">
				<input type="hidden" name="section" value="langsave">
			</form>
			<form name="lang_form" method="post">
			<table cellpadding="3" cellspacing="1" border="0" style="margin-bottom: 40px;">
			<tr valign=center>
				<td align="left">
				<select id="def_l" name="def_l" style="width:195px" onchange="javascript: document.lang_red.edit.value=this.value; {literal} if (document.lang_form.def_l.value != '' && document.lang_form.part.value != '0') { document.lang_form.open_button.disabled = false; } else { document.lang_form.open_button.disabled = true; } {/literal}">
					<option value="" selected>{$lang.content.choose_language}
					{section name=m loop=$admin_lang_menu}
						<option value="{$admin_lang_menu[m].name}">{$admin_lang_menu[m].value}{if $admin_lang_menu[m].vis eq 0} ({$lang.default_select.unactive_lang}){/if}</option>
					{/section}
				</select>
				</td>
               <td align="left">
                	<select name="part" onchange="{literal} if (document.lang_form.def_l.value != '' && document.lang_form.part.value != '0') { document.lang_form.open_button.disabled = false; } else { document.lang_form.open_button.disabled = true; } {/literal}">
                		<option value="0">{$lang.content.choose_part}
                		{foreach from=$parts item=part key=key}
                			<option value="{$key}">{$lang.content[$part]}
                		{/foreach}
                	</select>
				 </td>
				 <td class="main_content_text" align="left">
					<input id="open_button" type="button" class="button_2" value="{$lang.buttons.open}" disabled onclick="javascript: window.open('admin_editfile.php'+'?part='+document.lang_form.part.value+'&edit='+document.lang_red.edit.value,'langfile', 'height=600, resizable=yes, scrollbars=yes, width=800, menubar=no, status=no, left=20, top=20, screenX=20, screenY=20'); ">
				</td>
            </tr>
            </table>
            </form> 
			{literal}                       
			<script language="JavaScript">
			function SearchAction() {
				var search_str = document.search_string.search_string.value;	
				search_str = search_str.replace(/^\s+|\s+$/g, '') ;
				
				if (document.search_string.find_lang.value != '' && search_str != '') {
					document.search_string.start_search.disabled = false; 
				} else { 
					document.search_string.start_search.disabled = true;
				}
			}	 
			</script>
			{/literal}                       
			<div class="help_text"><span class="help_title">{$lang.default_select.help}</span>{$lang.content.search_string_help}</div>
            <form action="{$server}{$site_root}/admin/{$file_name}?sel=search_string" name="search_string" method="POST">
            <table cellpadding="3" cellspacing="1" border="0" style="margin-top: 10px;">
			<tr valign=center>
				<td align="left">{$lang.content.find_in} 
				<select name="find_lang" id="find_lang" style="width:195px" onclick="SearchAction();">
					<option value="" selected>{$lang.content.choose_language}
					{section name=m loop=$admin_lang_menu}
						<option value="{$admin_lang_menu[m].name}">{$admin_lang_menu[m].value}{if $admin_lang_menu[m].vis eq 0} ({$lang.default_select.unactive_lang}){/if}</option>
					{/section}
				</select>
				</td>
				<td>{$lang.content.search_for} <input type="text" name="search_string" id="search_string" size="30" onkeyup="SearchAction();"></td>	
            	<td>
            		<input type="button" id="start_search" value="{$lang.buttons.search}" disabled onclick="javascript: window.open('{$server}{$site_root}/admin/{$file_name}?sel=search_string'+'&find_lang='+document.search_string.find_lang.value+'&search_string='+document.search_string.search_string.value, 'search_string', 'height=600, resizable=yes, scrollbars=yes, width=800, menubar=no, status=no, left=20, top=20, screenX=20, screenY=20'); " > 
  				</td>
            </tr>
            </table>
            </form>
            </TD></TR>
            <!--langs file section end-->
			{elseif $data.section=='countries'}
			<!--country section begin-->
			<TR><TD>
			<form method="POST" name="admin_form" id="admin_form" action="{$file_name}">
			<input type="hidden" name="sel" value="">
			<input type="hidden" name="section" value="{$data.section}">
			<table cellpadding="3" cellspacing="1" border="0">
			{if $countries}
			<tr bgcolor="#FFFFFF">
				<td class="main_content_text">{$lang.content.one_country_text}:&nbsp;</td>
				<td><input type="checkbox" id="one_country" name="one_country" value="1" {if $data.one_country eq 1} checked {/if} onclick="javascript: {literal} if (this.checked == true) {document.getElementById('country').disabled=false;} else {document.getElementById('country').disabled=true;} {/literal}"></td>
			</tr>
			<tr bgcolor="#FFFFFF">
				<td class="main_content_text">{$lang.content.countries}:&nbsp;</td>
				<td>
				<select id="country" name="country" {if $data.one_country eq 0} disabled {/if}>
				{section name=c loop=$countries}
				<option value="{$countries[c].id}" {if $countries[c].sel eq 1} selected {/if}>{$countries[c].name}</option>
				{/section}
				</select>
				<span style="padding-left: 10px;"><a href="{$server}{$site_root}/install/countries/index.php">{$lang.content.change_countries}</a></span>
				</td>
			</tr>
			<tr bgcolor="#FFFFFF">
			<td colspan="2">
				<input type="button" class="button_3" value="{$lang.buttons.save}" onclick="javascript: document.admin_form.sel.value='save'; document.admin_form.submit();">
			</td>
			</tr>
			{else}
			<tr>
				<td>{$lang.content.countries_not_installed}<span style="padding-left: 10px;"><a href="{$server}{$site_root}/install/countries/index.php">{$lang.content.install_countries}</a></span></td>
			</tr>
			{/if}
			</table>
			</form>
			</TD></TR>
		<!--country section end-->
			{elseif $data.section=='site_mode'}
			{literal}
			<script language="javascript">
			function ShowHideDivIds(elem_id) {
				var modes_array = new Array({/literal}{foreach from=$modes item=mode name="js_smode"}"{$mode.id}"{if !$smarty.foreach.js_smode.last},{/if}{/foreach}{literal});
			
				var modes_size = modes_array.length;
			
				for (i=0; i<modes_size; i++) {
					var name = "ids_" + modes_array[i];
					var elem = document.getElementById(name);
					if (modes_array[i] == elem_id) {
						elem.style.display = 'block';				
					} else {
						elem.style.display = 'none';
					}
				}
			}
			</script>
			{/literal}
			<TR><TD>	
			<form method="POST" name="admin_form" id="admin_form" action="{$file_name}">
			<input type="hidden" name="sel" value="">
			<input type="hidden" name="section" value="{$data.section}">
			<table cellpadding="3" cellspacing="1" border="0">
			<tr>
				<td colspan="2">{$lang.content.choose_site_mode}:</td>
			</tr>
			{foreach from=$modes item=mode}
			<tr>
				<td><input name="site_mode" type="radio" class="radio" value="{$mode.id}" {if $mode.id == $site_mode}checked{/if} onclick="ShowHideDivIds({$mode.id});"></td>
				<td>{$lang.content[$mode.descr]}</td>
			</tr>			
			{/foreach}						
			<tr>
				<td colspan="2" style="padding-top: 10px;">{$lang.content.hide_features}:</td>
			</tr>
			</table>			
			{foreach from=$modes item=mode}
			<div id="ids_{$mode.id}" style="display: {if $mode.id == $site_mode}block{else}none{/if};">	
				<table cellpadding="2" cellspacing="2" border="0">
				{foreach from=$mode.elem_ids item=elem_id key=key}
					<tr>
						<td>
							<input type="checkbox" name="smode_ids[{$mode.id}][]" value="{$key}" {if $elem_id}checked{/if} {if $mode.id==1 || $mode.id==2}disabled{/if}>
						</td>
						<td>{$mode_ids[$key]}</td>
					</tr>
				{/foreach}
				</table>
			</div>
			{/foreach}	
			<input type="button" class="button_3" value="{$lang.buttons.save}" onclick="javascript: document.admin_form.sel.value='save'; document.admin_form.submit();">						
			</form>
			</TD></TR>
			{elseif $data.section=='maps'}			
			<TR><TD>
			<form method="POST" name="admin_form" id="admin_form" action="{$file_name}">
			<input type="hidden" name="sel" value="">
			<input type="hidden" name="section" value="{$data.section}">
			<table cellpadding="3" cellspacing="1" border="0" style="margin: 0px; margin-bottom: 10px;">
				<tr>
					<td><input type="checkbox" class="checkbox" name="use_maps_in_viewprofile" value="1" {if $data.use_maps_in_viewprofile eq 1} checked {/if}></td>
					<td>{$lang.content.use_maps_in_viewprofile}</td>
				</tr>
				<tr>
					<td><input type="checkbox" class="checkbox" name="use_maps_in_search_results" value="1" {if $data.use_maps_in_search_results eq 1} checked {/if}></td>
					<td>{$lang.content.use_maps_in_search_results}</td>
				</tr>
				<tr>
					<td><input type="checkbox" class="checkbox" name="use_maps_in_account" value="1" {if $data.use_maps_in_account eq 1} checked {/if}></td>
					<td>{$lang.content.use_maps_in_account}</td>
				</tr>
			</table>
			<div class="table_title">{$lang.content.maps_services}:</div>
			<table cellpadding="3" cellspacing="1" border="0" class="table_main">
			<tr>
				<td class="main_content_text" align="center">{$lang.content.maps_used}</td>
				<td class="main_content_text" align="center">{$lang.content.maps_name}</td>
				<td class="main_content_text" align="center">{$lang.content.maps_app_id}</td>
			</tr>
			{foreach from=$maps item=map}
			<tr>
				<td align="center"><input type="radio" name="map_used" value="{$map.id}" {if $map.used=="1"}checked{/if}></td>
				<td>{$lang.content[$map.name]}</td>
				<td>{if $map.name == "microsoft"}{$lang.content.app_id_not_used}{else}<input type="text" name="app_id[{$map.id}]" value="{$map.app_id}" size="105">{/if}</td>
			</tr>
			{/foreach}
			</table>
			<input type="button" class="button_3" value="{$lang.buttons.save}" onclick="javascript: document.admin_form.sel.value='save'; document.admin_form.submit();">
			</form>
			</TD></TR>
		<!--server errors section start-->
			{elseif $data.section=='server_errors'}
			<TR><TD>
			<form method="POST" name="admin_form" id="admin_form" action="{$file_name}">
			<input type="hidden" name="sel" value="">
			<input type="hidden" name="pos" value="">
			<input type="hidden" name="section" value="{$data.section}">
			<table cellpadding="3" cellspacing="1" border="0" width="100%">
			<tr>
				<td>
				<div style="padding-bottom: 10px;">
					{$lang.default_select.interface_lang}:
					{section name=m loop=$admin_lang_menu}
						<span class="space">
							{if $admin_lang_menu[m].id_lang == $current_lang_id}
								<b>{$admin_lang_menu[m].value}</b>
							{else}
								<a href="#" onclick="javascript: document.location.href='{$file_virt_name}&language_id={$admin_lang_menu[m].id_lang}';">{$admin_lang_menu[m].value}</a>
							{/if}
						</span>
						{if $admin_lang_menu[m].vis eq 0} ({$lang.default_select.unactive_lang}){/if}
					{/section}
				</div>
				</td>
			</tr>
			</table>
			{if $errors}
				<table width="100%" cellpadding="3" cellspacing="1" border="0" class="table_main" style="margin: 0px; margin-top:10px; margin-bottom:10px;">
				<INPUT type="hidden" name="language_id" value="{$current_lang_id}">
				<tr>
					<th align="center" width="10%">{$lang.content.position_number}</th>
					<th align="center" width="30%">{$lang.content.description}</th>
					<th align="center">{$lang.content.error_message}</th>
					<th align="center" width="20%">{$lang.content.error_to_default}</th>
					<th align="center" width="5%">{$lang.content.page_preview}</th>
				</tr>
				{section name=e loop=$errors}
				<tr>
					<td align="center">
					{$errors[e].code}
					</td>
					<td align="center">
					{$errors[e].description}
					</td>
					<td align="center">
					<textarea name="message[{$errors[e].id}]" rows="1" style="width: 80%;">{$errors[e].message}</textarea>
					</td>
					<td align="center">
					<input type="button" value="{$lang.buttons.to_default}" onclick="javascript: document.admin_form.sel.value='default'; document.admin_form.pos.value='{$errors[e].id}'; document.admin_form.submit();">
					</td>
					<td align="center">
					<input type="button" value="{$lang.buttons.preview}" onclick="javascript: window.open('{$server}{$site_root}/error.php?code={$errors[e].code}&view_from_admin=1&lang_from_admin={$current_lang_id}');">
					</td>
				</tr>
				{/section}
				</table>
			{/if}
			<input type="button" value="{$lang.buttons.save}" onclick="javascript: document.admin_form.sel.value='save'; document.admin_form.submit();">
			</form>
			</TD></TR>
		<!--server errors section end-->
		<!--metatags section start-->
			{elseif $data.section=='metatags'}
			<TR>
				<TD>
				<table cellpadding="0" cellspacing="0">
					<tr>
						<td><img src="{$site_root}{$template_root}/images/arrow_down.gif"></td>
						<td class="fast_nav_title"><a class="fast_nav_link" href="#" onclick="javascript: ShowHideDiv('fast_menu');">{$lang.default_select.fast_navigation}</a></td>
					</tr>
				<tr>
					<td colspan="2" class="fast_menu_nav">
					<ul class="fast_navigation" id="fast_menu" style="display: none;">
					{foreach from=$pages item=page key=key}
					 	<li><a href="#metatag_{$key}">{$page.file}</a></li>
					{/foreach}
					</ul>&nbsp;
					</td>
				</tr>
				</table>
				<form method="POST" name="admin_form" id="admin_form" action="{$file_name}">
				<input type="hidden" name="sel" value="save">
				<input type="hidden" name="section" value="{$data.section}">
				<input type="hidden" name="language_id" value="{$current_lang_id}">
				<table cellpadding="3" cellspacing="1" border="0" width="100%">
				<tr>
					<td>
					<div style="padding-bottom: 10px;">
						{$lang.default_select.interface_lang}:
						{section name=m loop=$admin_lang_menu}
							<span class="space">
								{if $admin_lang_menu[m].id_lang == $current_lang_id}
									<b>{$admin_lang_menu[m].value}</b>
								{else}
									<a href="#" onclick="javascript: document.location.href='{$file_virt_name}&language_id={$admin_lang_menu[m].id_lang}';">{$admin_lang_menu[m].value}</a>
								{/if}
							</span>
							{if $admin_lang_menu[m].vis eq 0} ({$lang.default_select.unactive_lang}){/if}
						{/section}
					</div>
					</td>
				</tr>
				</table>
				<table width="100%" cellpadding="3" cellspacing="1" border="0">
				{foreach from=$pages item=page key=key}
					<tr>
						<td colspan="2" id="metatag_{$key}" style="padding-top: 10px; padding-bottom: 10px;">{$lang.content.metatag_page}: <b>{$page.file}</b> {if $page.file_descr}({$page.file_descr}){/if}</td>
					</tr>
					<tr>
						<td width="5%" valign="top">{$lang.content.metatag_title}:</td>
						<td><textarea name="title[{$key}]" rows="2" class="whole_width">{$page.metatags.title}</textarea></td>
					</tr>
					<tr>
						<td valign="top">{$lang.content.metatag_description}:</td>
						<td><textarea name="description[{$key}]" rows="2" class="whole_width">{$page.metatags.description}</textarea></td>
					</tr>
					<tr>
						<td valign="top">{$lang.content.metatag_keywords}:</td>
						<td><textarea name="keywords[{$key}]" rows="2" class="whole_width">{$page.metatags.keywords}</textarea></td>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td style="padding-bottom: 15px;"><input type="button" value="{$lang.buttons.preview}" onclick="javascript: window.open('{$server}{$site_root}/{if $page.file_link}{$page.file_link}{else}{$key}{/if}.php?view_from_admin=1{if $key=='index' || $key=='registration'}&for_unreg_user=1{/if}&lang_from_admin={$current_lang_id}');">
					</td>
					</tr>
				{/foreach}
				</table>
				<input type="button" value="{$lang.buttons.save}" onclick="javascript: document.admin_form.submit();">
				</form>
				</TD>
			</TR>
		<!--metatags section end-->

		<!--watermark section start-->
			{elseif $data.section=='watermark'}
			<TR><TD>
			<form method="POST" name="admin_form" id="admin_form" action="{$file_name}" enctype="multipart/form-data">
			<input type="hidden" name="sel" value="">
			<input type="hidden" name="pos" value="">
			<input type="hidden" name="section" value="{$data.section}">
			<table width="100%" cellpadding="3" cellspacing="1" border="0"  style="margin: 0px;">
				<tr>
					<td  colspan="2">
					<b>{$lang.content.current_watermark}:</b>
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<img src="{$site_root}{$settings.photo_folder}/{$settings.watermark_image}" style="border: 1px solid #cccccc;">
					</td>
				</tr>
				<tr>
					<td colspan="2">
					<b>{$lang.content.use_watermark}:</b>&nbsp;&nbsp;{if $settings.use_watermark eq 1}{$lang.content.watermark_on}{else}{$lang.content.watermark_off}{/if}
					</td>
				</tr>
				<tr>
					<td align="left" colspan="2">
					<input type="hidden" style="margin-left: 0px;" name="use_watermark" value="{if $settings.use_watermark eq 1}1{else}0{/if}">
					<input type="button" class="button_3" value="{if $settings.use_watermark eq 1}{$lang.buttons.turn_off}{else}{$lang.buttons.turn_on}{/if}" onclick="javascript: document.admin_form.sel.value='save';document.admin_form.pos.value='save'; document.admin_form.submit();">
					</td>
				</tr>				
				<tr>
					<td colspan="2" style="padding-top:20px;">
					<b>{$lang.content.create_watermark}:</b>
					</td>
				</tr>
				
				<tr>
					<td width="1%">
						<input name="type_watermark" type="radio" value="image" {if $settings.watermark_type eq 'image'}checked{/if} onclick="WatermarkTypeChange('2');">
					</td>
					<td>
						{$lang.content.image_watermark}:
					</td>
				</tr>
				<tr>
					<td>
					</td>
					<td align="left">
						<input type="file" name="image_watermark" id="image_watermark"  value="{$site_root}{$settings.photo_folder}/{$settings.watermark_image}" style="width: 200px;" {if $settings.watermark_type eq 'text'} disabled {/if}>
					</td>
				</tr>
				<tr>
					<td>
					</td>
					<td align="left">
						<table cellpadding="0" cellspacing="0" class="form_table">
							<tr>
								<td colspan="4">
								{$lang.content.resize_watermark}:
								</td>
							</tr>
							<tr>
								<td>
								{$lang.content.width_watermark}:
								</td>
								<td align="left" style="padding-left:2px;">
								<input type="text" name="width_watermark" id="width_watermark" value="{$settings.watermark_width}" style="width: 30px;" {if $settings.watermark_type eq 'text'} disabled {/if}>
								</td>							
								<td>
								&nbsp;{$lang.content.pixels}
								</td>
								<td style="padding-left:20px;">
								{$lang.content.height_watermark}:
								</td>
								<td align="left" style="padding-left:2px;">
								<input type="text" name="height_watermark" id="height_watermark"  value="{$settings.watermark_height}" style="width: 30px;" {if $settings.watermark_type eq 'text'} disabled {/if}>
								</td>
								<td>
								&nbsp;{$lang.content.pixels}
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td width="1%">
					<input name="type_watermark" type="radio" value="text" {if $settings.watermark_type eq 'text'}checked{/if} onclick="WatermarkTypeChange('1');">
						</td>
					<td>
						{$lang.content.text_watermark}:
					</td>
				</tr>
				<tr>
					<td>
					</td>
					<td align="left">
						<table cellspacing="0" cellpadding="0">
							<tr>
								<td>
								<select name="font-size" onchange="" id="font-size" {if $settings.watermark_type eq 'image'} disabled {/if}>
									<option value="">font-size</option>
									{foreach key=key item=item from=$fonts_size}
									<option value={$item} {if $item eq $cur_font_size}selected{/if}>{$item}pt</option>
									{/foreach}
								</select>&nbsp;</td>
								<td><select name="font-face" onchange="" id="font-face" {if $settings.watermark_type eq 'image'} disabled {/if}>
									<option value="">font-face</option>
									{foreach key=key item=item from=$own_fonts}
									<option value={$key} {if $key eq $cur_font}selected{/if}>{if $fonts[$key] != ""}{$fonts[$key]}{else}{$item}{/if}</option>
									{/foreach}
								</select>&nbsp;</td>
								<td><input type="text" name="text_watermark" id="text_watermark"  value="{$settings.watermark_text}" style="width: 150px;" {if $settings.watermark_type eq 'image'} disabled {/if}></td>
							</tr>
						</table>
					</td>
				</tr>	
				<tr>				
					<td align="left" colspan="2">
					<input type="button" class="button_3" value="{$lang.buttons.preview}" onclick="javascript: document.admin_form.sel.value='save'; document.admin_form.pos.value='preview'; document.admin_form.submit();">
					<input type="button" class="button_3" value="{$lang.buttons.create}" onclick="javascript: document.admin_form.sel.value='save'; document.admin_form.pos.value='create'; document.admin_form.submit();">
					</td>
				</tr>
				<tr>
					<td colspan="2" style="padding-top: 20px;">
						<b>{$lang.content.logo_to_default}:</b>
					</td>
				</tr>
				<tr>
					<td colspan="2">
					<input type="button" class="button_3" value="{$lang.buttons.to_default}" onclick="javascript: document.admin_form.sel.value='default'; document.admin_form.submit();">
					</td>
				</tr>
			</table>
			</form>
			</TD></TR>
		<!--watermark section end-->
		<!--logotype section start-->
			{elseif $data.section=='logotype'}
			<TR><TD>
			<form method="POST" name="admin_form" id="admin_form" action="{$file_name}" enctype="multipart/form-data">
			<input type="hidden" name="sel" value="">
			<input type="hidden" name="pos" value="">
			<input type="hidden" name="section" value="{$data.section}">
			<input type="hidden" name="language_id" value="{$current_lang_id}">
			<table cellpadding="3" cellspacing="1" border="0" width="100%">
			<tr>
				<td>
				<div style="padding-bottom: 10px;">
					{$lang.default_select.interface_lang}:
					{section name=m loop=$admin_lang_menu}
						<span class="space">
							{if $admin_lang_menu[m].id_lang == $current_lang_id}
								<b>{$admin_lang_menu[m].value}</b>
							{else}
								<a href="#" onclick="javascript: document.location.href='{$file_virt_name}&language_id={$admin_lang_menu[m].id_lang}';">{$admin_lang_menu[m].value}</a>
							{/if}
						</span>
						{if $admin_lang_menu[m].vis eq 0} ({$lang.default_select.unactive_lang}){/if}
					{/section}
					<div align="right">
						<span class="space">{$lang.content.preview}:</span>
						<span class="space"><a href="{$server}{$site_root}/index.php?view_from_admin=1&for_unreg_user=1&lang_from_admin={$current_lang_id}" target="_blank">{$lang.content.preview_index}</a></span>
						<span class="space"><a href="{$server}{$site_root}/homepage.php?view_from_admin=1&lang_from_admin={$current_lang_id}" target="_blank">{$lang.content.preview_homepage}</a></span>
					</div>
				</div>
				</td>
			</tr>
			</table>
			{foreach from=$logo_settings item=item key=key}
				<table width="100%" cellpadding="0" cellspacing="0" class="form_table">
					<tr align="left" valign="center">
						<td><b>{$lang.content[$key]}</b>:</td>
					</tr>
					<tr align="left">
						<td>
						{$lang.content.logo_image}:
						</td>
					</tr>
					<tr align="left" valign="center">
						<td >
						<input type="hidden" name="type[{$item.id}]" value="{$item.type}">
						<img src="{$site_root}{$settings.photo_folder}/{$item.img}" alt="{$item.alt}" border="1">
						<input type="hidden" name="width[{$item.id}]" value="{$item.width}">
						<input type="hidden" name="height[{$item.id}]" value="{$item.height}">

						</td>
					</tr>
					<tr align="left">
						<td>
						{$lang.content.alt_text}:
						</td>
					</tr>
					<tr align="left">
						<td>
							<textarea name="alt[{$item.id}]" cols="60" rows="1">{$item.alt}</textarea>
						</td>
					</tr>
					<tr align="left">
						<td>
						{$lang.content.download_new}:
						</td>
					</tr>
					<tr align="left">
						<td>
						{assign var=id_text value="file_text_"|cat:$item.type}
						{assign var=id_img value="file_img_"|cat:$item.type}
						
						<div class="fileinputs">
							<input type="file" name="{$item.type}" id="{$item.type}" value="{$site_root}{$settings.photo_folder}/{$item.img}" class="file" onchange="document.getElementById('{$id_text}').innerHTML = this.value.replace(/.*\\(.*)/, '$1');document.getElementById('{$id_img}').innerHTML = ChangeIcon(this.value.replace(/.*\.(.*)/, '$1'));"/>
							<div class="fakefile">
								<table cellpadding="0" cellspacing="0">
								<tr>												
									<td>	
										<input type="button"  class="btn_upload" value="{$lang.buttons.choose}">
									</td>			
									<td style="padding-left:10px;">
										<span id='{$id_img}'></span>
									</td>								
									<td style="padding-left:4px;">
										<span id='{$id_text}'></span>	
									</td>
								</tr>
								</table>
							</div>
						</div>
						{if $error_arr[$key]}<div class="field_error">{$error_arr[$key]}</div>{/if}
						</td>
					</tr>
					<tr align="left">
						<td>
						{$lang.content.logo_to_default}:
						</td>
					</tr>
					<tr>
						<td  style="padding-bottom: 15px;"><input type="button" class="button_3" value="{$lang.buttons.to_default}" onclick="javascript: document.admin_form.sel.value='default'; document.admin_form.pos.value='{$item.id}'; document.admin_form.submit();"></td>
					</tr>
				</table>
			{/foreach}

			<input type="button" value="{$lang.buttons.save}" onclick="javascript: document.admin_form.sel.value='save'; document.admin_form.submit();">
			</form>
			</TD></TR>
		<!--logotype section end-->
			{/if}
		<!--admin section end-->
		</TABLE>
	</td></tr>
	</table>

{literal}
<script>

var doc = null;
function ajax() {
	// Make a new XMLHttp object
	if (typeof window.ActiveXObject != 'undefined' ) doc = new ActiveXObject("Microsoft.XMLHTTP");
	else doc = new XMLHttpRequest();
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
	if (obj.name == 'email') {
		if (obj.value !="" && obj.value.search('^.+@.+\\..+$') != -1) {
			document.getElementById('email_div').style.display = 'none';
		} else {
			document.getElementById('email_div').style.display = '';
		}
	}
	return true;
}


function DivVision(id_div){
	if ( id_div == '1' ) {
		document.getElementById('user_type_copy').value = id_div;
		document.getElementById('agency_div').style.display = 'none';
		//document.getElementById('user_type_err').style.display = 'none';
		document.getElementById('birth_div_1').style.display = 'inline';
		document.getElementById('agent_div').style.display = 'none';
	} else if ( id_div == '2' ) {
		document.getElementById('user_type_copy').value = id_div;
		document.getElementById('agency_div').style.display = 'inline';
		document.getElementById('birth_div_1').style.display = 'none';
		document.getElementById('agent_div').style.display = 'none';
		//document.getElementById('user_type_err').style.display = 'none';
	}else if ( id_div == '3' ) {
		document.getElementById('user_type_copy').value = id_div;
		document.getElementById('agency_div').style.display = 'none';
		document.getElementById('birth_div_1').style.display = 'inline';
		document.getElementById('agent_div').style.display = 'inline';
	}
	return;
}

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

function WatermarkTypeChange(section){
		if (section == '1') {
			document.getElementById('font-size').disabled = false;
			document.getElementById('font-face').disabled = false;
			document.getElementById('text_watermark').disabled = false;
			document.getElementById('image_watermark').disabled = true;
			document.getElementById('height_watermark').disabled = true;
			document.getElementById('width_watermark').disabled = true;
		} else if (section == '2') {
			document.getElementById('font-size').disabled = true;
			document.getElementById('font-face').disabled = true;
			document.getElementById('text_watermark').disabled = true;
			document.getElementById('image_watermark').disabled = false;
			document.getElementById('height_watermark').disabled = false;
			document.getElementById('width_watermark').disabled = false;
		}
		return;
	}

function ChangeExample(IdEx, par, position, format){
		if (par == 'nbsp') {
			digit = "2 000 000";			
		} else if (par == ',') {
			digit = "2,000,000";			
		} else if (par == 'empty') {
			digit = "2000000";			
		}
		if (format == 'abbr'){
			cur = "{/literal}{$cur}{literal}";
			space = "&nbsp;";
		}else{
			cur = "{/literal}{$cur_symbol}{literal}";
			space = "";
		}
		if (position == 'end') {
			document.getElementById(IdEx).innerHTML = "{/literal}{$lang.content.example}:&nbsp;&nbsp;{literal}" + digit + "&nbsp;"+ cur;			
		} else {
			document.getElementById(IdEx).innerHTML = "{/literal}{$lang.content.example}:&nbsp;&nbsp;{literal}" + cur + space + digit;						
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
				}
	    		
	    	} else {			
				if (name != ''){
					document.getElementById('na_agency_error').style.display = 'inline';
					document.getElementById('na_agency_error').innerHTML = "&nbsp;{/literal}{$lang.content.na_agency_1}&nbsp;&quot;" + doc.responseText.substr(3, doc.responseText.length - 3) + "&quot;&nbsp;{$lang.content.na_agency_2}{literal}";		
				}else{
					{/literal}{if $data.agency_name != ''}{literal}
					document.getElementById('na_agency_error').style.display = 'inline';
					document.getElementById('na_agency_error').innerHTML = "&nbsp;{/literal}{$lang.content.na_agency_3}{literal}";
					{/literal}{/if}{literal}
				}
				document.getElementById('agency_name').value = '{/literal}{$data.agency_name}{literal}'
				if (document.getElementById('agency_name').value != ''){
					{/literal}{if $data.agency_approve == 1}{literal}
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

function CheckInteger(field_id){	
	
	if (document.getElementById(field_id).value == "" || document.getElementById(field_id).value.search(/^[0-9]{0,7}$/) == -1 || document.getElementById(field_id).value == 0){	
		return false;
	}	
	return true;
}

function SaveAdminSettings(span_id, settings_array_int, settings_array_checkbox, settings_array_str){
	ajax();
	url = "admin_settings.php?sel=save_settings";
	for (id_set in settings_array_int){		
		if (!CheckInteger(settings_array_int[id_set])){
			document.getElementById(span_id).innerHTML = "<font color='red'>{/literal}{$lang.content.not_integer}{literal}</font>";				
			document.getElementById("span_" + settings_array_int[id_set]).style.display = 'inline';
			return;
		}else{
			document.getElementById("span_" + settings_array_int[id_set]).style.display = 'none';
		}
		url = url + "&" + settings_array_int[id_set] + "=" + document.getElementById(settings_array_int[id_set]).value;
	}
	for (id_set in settings_array_checkbox){	
		if ( document.getElementById(settings_array_checkbox[id_set]).checked){
			value = 1;
		}else{
			value = 0;
		}
		url = url + "&" + settings_array_checkbox[id_set] + "=" + value;
	}
	for (id_set in settings_array_str){				
		if (!(document.getElementById(settings_array_str[id_set]).value.length)){
			document.getElementById(span_id).innerHTML = "<font color='red'>{/literal}{$lang.content.empty_str}{literal}</font>";				
			document.getElementById("span_" + settings_array_str[id_set]).style.display = 'inline';
			return;
		}else{
			document.getElementById("span_" + settings_array_str[id_set]).style.display = 'none';
		}
		url = url + "&" + settings_array_str[id_set] + "=" + document.getElementById(settings_array_str[id_set]).value;
	}
	
	if (doc){		
		doc.onreadystatechange = function() {
			if (doc.readyState == 4) {			
				if (doc.responseText == 'saved'){
					document.getElementById(span_id).innerHTML = "<font color='red'>* {/literal}{$lang.errors.success_save}{literal}</font>";				
				}
			} else {
				document.getElementById(span_id).innerHTML = "Saving...";
			}
		}
		doc.open("GET", url+"&ajax=1", true);
		doc.send(null);
		
	}else{
		document.location = url + "&ajax=0";
	}
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
function ShowErrorField(id_error_field){
	document.getElementById(id_error_field).style.display = 'inline';		
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

{/literal}

{if $err_id}
{literal}

<script>
	{/literal}
		{foreach item=item from=$err_id}
	{literal}
			ShowErrorField('{/literal}span_{$item}{literal}');
	{/literal}
		{/foreach}
	{literal}
</script>
{/literal}
{/if}

</script>

{if $data.section=='admin' && $data.user_type eq 2 && $data.in_base && $data.id_country && $use_maps_in_account}
	{include file="$gentemplates/viewmap.tpl"}
{/if}
{include file="$admingentemplates/admin_bottom.tpl"}