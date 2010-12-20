{include file="$gentemplates/site_top.tpl"}

{literal}

<script language="javascript">

	function checkCount(id) {
		var max_photo = {/literal}{$max_photo}{literal};
		var form = document.getElementById(id);
		var reg = /^(plan_)*photo_(\d+)$/;
		var count = 0;
		
		for(var i = 0; i < form.length && count < max_photo; i++) {
			
			if(reg.test(form[i].id)) {
				if(form[i].checked) {
					count++;
				}
			}
		}
		
		if(count >= max_photo) {
			return true;
		}
		
		return false;
	}
	
	function sendRequest(id, name) {
		if(checkCount(id)) {
			form = document.forms[name];
			form.submit();
		} else {
			var message = {/literal}'{$lang.content.not_enough}'{literal};
			alert(message);
		}
	}

</script>

{/literal}

<!-- content-->
<table cellpadding="0" cellspacing="0" border="0" width="100%">
<tr valign="top">
	<td class="left" valign="top">
		{include file="$gentemplates/homepage_hotlist.tpl"}
	</td>
	<td class="delimiter">&nbsp;</td>
	<td class="main">
		<table cellpadding="0" cellspacing="0" border="0" width="100%">
		{if $banner.center}
			<tr>
				<td>
				<!-- banner center -->
			  	
					<div align="left">{$banner.center}</div>
				
				<!-- /banner center -->
				</td>
			</tr>
		{/if}	
			<tr>
				<td colspan="2" class="header"><b>{$lang.headers.services}</b></td>
			</tr>
			<tr>
				<td colspan="2" class="subheader" ><b>{$lang.headers.slide_show}</b></td>
			</tr>
			<tr>
				<td width="15">&nbsp;</td>
				<td style="padding: 10px 0;">
				<table cellpadding="0" cellspacing="0">
					<tr><td>{$lang.content.slideshow_get_ad}</td></tr>
				</table>
			</tr>
		</table>		

		
		<form method="POST" name="apply" id="apply" action="{$file_name}?sel=slideshow_ad&type=rent&id_ad={$profile.id_ad}">
		
		<table cellpadding="0" cellspacing="0" border="0" width="100%">		
		
		<tr><td colspan="3"><hr></td></tr>
		{if $profile.type eq 2 || $profile.type eq 4}
		{if $no_ad ne 1}
		<tr>
			<td width="10">&nbsp;</td>
			<td>
				<table cellpadding="3" cellspacing="0" border="0" class="table_top">
					<tr><td>
							{if $profile.plan_photo_id && $profile.plan_approve_total}
							{$lang.content.planning}:&nbsp;
							<table cellpadding="3" cellspacing="3" border="0">
								{assign var=count value=0}
								{section name=ph loop=$profile.plan_photo_id}								
								{if $count is div by 3}<tr>{/if}
								{if $profile.plan_admin_approve[ph] == 1}
									{assign var=count value=$count+1}									
									<td width="{$thumb_width+10}" valign="top">
										<table cellpadding="0" cellspacing="0" border="0">									
										<tr>
											
											<td height="{$thumb_height+10}" width="{$thumb_width+10}" style=" border: 1px solid #cccccc; vertical-align: middle;" align="center">
												{if $profile.plan_view_link[ph]}<a href="{$profile.plan_file[ph]}" rel="lightbox[profile_plan]" title="{$profile.plan_user_comment[ph]}">{/if}
												<img src='{$profile.plan_thumb_file[ph]}' style="border: none;" align="absmiddle">
												{if $profile.photo_view_link[ph]}</a>{/if}
											</td>
											
										</tr>
										<tr>
											<td width="{$thumb_width+10}" style="padding-top: 2px;">{strip}
												{if $profile.plan_user_comment[ph]}{$profile.plan_user_comment[ph]}<br>{/if}
												{if $profile.plan_status[ph]==1}{*{$lang.content.active_file}<br>*}{else}<font class="error_small">- {$lang.content.inactive_file}</font><br>{/if}
												{if $profile.use_photo_approve}
													{if $profile.plan_admin_approve[ph] == 0}
														<font class="error_small">- {$lang.content.admin_approve_not_complete}</font>
													{elseif $profile.plan_admin_approve[ph] == 1}
														{*$lang.content.admin_approve_acepted*}
													{elseif $profile.plan_admin_approve[ph] == 2}
														<font class="error_small">- {$lang.content.admin_approve_decline}</font>
													{/if}
												{/if}
												{/strip}
											</td>
										</tr>
										<tr>
											<td style="padding-top: 6px;">
												<table cellpadding="0" cellspacing="0" border="0" width="100%">
													<tr>														
														<td valign="middle">
															<input type="checkbox" name="plan_photo_{$profile.plan_photo_id[ph]}" id="plan_photo_{$profile.plan_photo_id[ph]}" style="margin-left: 0;" /> 
														</td>
														<td valign="middle">
															<label style="display: block; margin-top: 3px;" for="plan_photo_{$profile.plan_photo_id[ph]}">{$lang.content.take_part}</label>
														</td>
													</tr>
												</table>
											</td>
										</tr>
										</table>
									</td>
								{/if}	
								{if $count is div by 3 || $smarty.section.ph.last}</tr>{/if}
								{/section}				
							
							</table>
							{else}
							<table cellpadding="0" cellspacing="0">
							<tr>
								<td width="200"><b>{$lang.content.planning}:</b></td>
								<td style="padding-left: 6px;"><font class="error">{if $profile.plan_photo_id}{$lang.content.no_approved}{else}{$lang.content.no_photos2}{/if}</font></td>
							</tr>
							</table>
							{/if}
					</td></tr>
				</table>
			</td>
			<td width="100" align="right" valign="top" style="padding-right: 20px;">&nbsp;{*<a href="{$file_name}?sel=step_7&amp;id_ad={$profile.id}">{$lang.buttons.change}</a>*}</td>
		</tr>
		{else}
		<tr>
			<td width="15">&nbsp;</td>
			<td>
				<table cellpadding="0" cellspacing="0">
					<tr>
						<td height="15" width="200"><b>{$lang.content.planning}:</b>&nbsp;</td>
						<td class="error">{$lang.content.no_ad}</td>
					</tr>
				</table>
 			</td>
			<td width="100" align="right" valign="top" style="padding-right: 20px;">{*<a href="{$file_name}?sel=edit_ad">{$lang.buttons.change}</a>*}</td>
		</tr>
		{/if}
		<tr><td colspan="3"><hr></td></tr>
	{/if}
		{if $no_ad ne 1}
		<tr>
			<td width="10">&nbsp;</td>
			<td>
				<table cellpadding="3" cellspacing="0" border="0" class="table_top">
					<tr><td>
							{if $profile.photo_id && $profile.photo_approve_total}
							<b>{$lang.content.photo}</b>:&nbsp;
							<table cellpadding="3" cellspacing="3" border="0">
								{assign var=count value=0}
								{section name=ph loop=$profile.photo_id}
								{if $count is div by 3}<tr>{/if}
								{if $profile.photo_admin_approve[ph] == 1}
								{assign var=count value=$count+1}
								<td width="{$thumb_width+10}" valign="top">
									<table cellpadding="0" cellspacing="0" border="0">
									<tr>
										<td height="{$thumb_height+10}" width="{$thumb_width+10}" style=" border: 1px solid #cccccc; vertical-align: middle;" align="center">
											{if $profile.photo_view_link[ph]}<a href="{$profile.photo_file[ph]}" rel="lightbox[profile_photo]"title="{$profile.photo_user_comment[ph]}">{/if}
											<img src='{$profile.thumb_file[ph]}' style="border: none;" align="absmiddle">
											{if $profile.photo_view_link[ph]}</a>{/if}
										</td>
									</tr>
									<tr>
										<td width="{$thumb_width+10}" style="padding-top: 2px;">{strip}
											{if $profile.photo_user_comment[ph]}{$profile.photo_user_comment[ph]}<br>{/if}
											{if $profile.photo_status[ph]==1}<!--{$lang.content.active_file}<br>-->{else}<font class="error_small">- {$lang.content.inactive_file}</font><br>{/if}
											{if $profile.use_photo_approve}
												{if $profile.photo_admin_approve[ph] == 0}
													<font class="error_small">- {$lang.content.admin_approve_not_complete}</font>
												{elseif $profile.photo_admin_approve[ph] == 1}
													<!--{$lang.content.admin_approve_acepted}-->
												{elseif $profile.photo_admin_approve[ph] == 2}
													<font class="error_small">- {$lang.content.admin_approve_decline}</font>
												{/if}
											{/if}
											{/strip}
										</td>
									</tr>
									<tr>
										<td style="padding-top: 6px;">
											<table cellpadding="0" cellspacing="0" border="0" width="100%">
												<tr>														
													<td valign="middle">
														<input type="checkbox" name="photo_{$profile.photo_id[ph]}" id="photo_{$profile.photo_id[ph]}" style="margin-left: 0;" /> 
													</td>
													<td valign="middle">
														<label style="display: block; margin-top: 3px;" for="photo_{$profile.photo_id[ph]}">{$lang.content.take_part}</label>
													</td>
												</tr>
											</table>
										</td>
									</tr>
									</table>
								</td>
								{/if}								
								{if $count is div by 3 || $smarty.section.ph.last}</tr>{/if}
								{/section}							
							</table>
							{else}
							<table cellpadding="0" cellspacing="0">
							<tr>
								<td width="200"><b>{$lang.content.photo}:</b></td>
								<td style="padding-left: 6px;"><font class="error">{if $profile.photo_id}{$lang.content.no_approved}{else}{$lang.content.no_photos2}{/if}</font></td>
							</tr>
							</table>
							{/if}
					</td></tr>
				</table>
			</td>
			<td width="100" align="right" valign="top" style="padding-right: 20px;">{*<a {if ($profile.type eq 2 || $profile.type eq 4)} href="{$file_name}?sel=step_4&amp;id_ad={$profile.id}" {else} href="{$file_name}?sel=step_5&amp;id_ad={$profile.id}" {/if}>{$lang.buttons.change}</a>*}&nbsp;</td>
		</tr>
		{else}
		<tr>
			<td width="15">&nbsp;</td>
			<td>
				<table cellpadding="0" cellspacing="0">
					<tr>
						<td height="15" width="200"><b>{$lang.content.photo}:</b>&nbsp;</td>
						<td class="error">{$lang.content.no_ad}</td>
					</tr>
				</table>
 			</td>
			<td width="100" align="right" valign="top" style="padding-right: 20px;">{*<a href="{$file_name}?sel=edit_ad">{$lang.buttons.change}</a>*}&nbsp;</td>
		</tr>
		{/if}
		<tr>
			<td width="10">&nbsp;</td>
			<td>
				<input type="hidden" name="action" value="apply"/>
				<input type="button" class="btn_small" value="{$lang.buttons.back}" onclick="location.href='{$file_name}?sel=slideshow'" />
				<input type="button" class="btn_small" value="{$lang.buttons.apply}" onclick="sendRequest('apply','apply');"/>
			</td>
		</tr>
		</table>
		
		</form>
		
	</td>
</tr>
</table>


{include file="$gentemplates/site_footer.tpl"}