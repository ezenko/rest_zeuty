{include file="$gentemplates/site_top.tpl"}
    <div id="middle-container">
<table cellpadding="0" cellspacing="0" border="0">
<tr>
    <!--
	<td class="left" valign="top">
		<table>
			<tr>
				<td valign="top" align="center">
				{include file="$gentemplates/testimonials_post_block.tpl"}
				</td>
			</tr>
			<tr>
				<td class="left" valign="top" style="padding-top: 10px; padding-left: 20px;">
					{if $par ne 'for_agency'}
					<div id="mhi_contact_agency" style="display: {$mhi_contact_agency};"><a href="{$file_name}?sel=for_agency" id="mhi_contact_agency">{$lang.content.for_agency}</a></div>
					{else}
					<div><a href="{$file_name}">{$lang.content.contact}</a></div>
					{/if}
				</td>
			</tr>
		</table>
	</td>
	<td class="delimiter">&nbsp;</td>
	-->
	<td class="main" valign="top">
		<!-- banner center -->
	  	{if $banner.center}
			<div align="left">{$banner.center}</div>
		{/if}
		 <!-- /banner center -->
		<table cellpadding="0" cellspacing="0" border="0" width="100%">
		{if $par ne 'for_agency'}
		<tr><td colspan="2" class="header"><h2>{$lang.headers.contact}</h2></td></tr>
		<tr>
			<td width="15">&nbsp;</td>
			<td>{$lang.content.page_header}</td>
		</tr>
		</table>
		<br>
		<form action="{$form.action}" method="POST" name="mailbox_write">
		{$form.hidden}
			<table cellpadding="0" cellspacing="0" border="0" width="100%">
			<tr>
				<td width="12">&nbsp;</td>
				<td valign="top">
					<table cellpadding="0" cellspacing="3" border="0" width="100%">
					{foreach item=item from=$error}
					<div style="padding-bottom: 5px; padding-left: 3px;" class="error">*&nbsp;{$item}</div>
					{/foreach}
					{if $registered eq 1 && ($par eq 'from_searche' || $par eq 'from_searchv')}
						<tr>
							<td width="120" height="27">{$lang.content.name}: <font class="error">*</font><input type="hidden" value="complaint" id="from" name="from"></td>
							<td colspan="2"><input type="text" class="str" size="50" name="name" value="{$data.name}"></td>
						</tr>
						<tr>
							<td  height="27">{$lang.content.email}:</td>
							<td colspan="2"><input type="text" class="str" size="50" name="email" value="{$data.email}"></td>
						</tr>
						<tr>
						<td colspan="3"><input type="hidden" name="subject" value="{$lang.content.default_subj}"><input type="hidden" value="{$data.href}" id="href" name="href"></td>
						</tr>
	<!--					<tr>
							<td height="27">{$lang.content.subject}:&nbsp;</td>
							<td colspan="2">{$lang.content.default_subj}
								<input type="hidden" name="subject" value="{$lang.content.default_subj}">
							</td>
						</tr>-->
	<!--					<tr>
							<td height="27">{$lang.content.ad_href}:&nbsp;</td>
							<td colspan="2">{$data.href}<input type="hidden" value="{$data.href}" id="href" name="href"></td>
						</tr>-->
						<tr>
							<td colspan="3" style="padding-top: 10px; padding-bottom: 10px;">{$lang.content.text_2}</td>
						</tr>
						<tr>
							<td height="27">{$lang.content.complaint_reason}:</td>
							<td colspan="2">
							<select name="complaint_reason" id="complaint_reason">
								<option value="0" {if $data.complaint_reason == 0}selected{/if}>{$lang.content.complaint_0}</option>
								<option value="1" {if $data.complaint_reason == 1}selected{/if}>{$lang.content.complaint_1}</option>
								<option value="2" {if $data.complaint_reason == 2}selected{/if}>{$lang.content.complaint_2}</option>
								<option value="3" {if $data.complaint_reason == 3}selected{/if}>{$lang.content.complaint_3}</option>
								<option value="4" {if $data.complaint_reason == 4}selected{/if}>{$lang.content.complaint_4}</option>
								<option value="5" {if $data.complaint_reason == 5}selected{/if}>{$lang.content.complaint_5}</option>
								<option value="6" {if $data.complaint_reason == 6}selected{/if}>{$lang.content.complaint_6}</option>
							</select>
							</td>
						</tr>						
						<tr>
							<td height="27">{$lang.content.your_comment}:&nbsp;</td>
							<td ><textarea name="your_comment" cols="50" rows="10">{$data.your_comment}</textarea></td>
							<td width="350" valign="top" style="padding-top: 20px;"><font class="hint">{$lang.content.comment_hint}</font></td>
						</tr>
						<tr>
							<td>{$lang.content.keystring}: <font class="error">*</font></td>
							<td><img alt="" src="./include/kcaptcha/index.php" style="border: 1px solid #cccccc; padding: 3px;"><br>
								<input type="text" class="str" size="35" name="keystring" id="keystring" style="margin: 0px; margin-top: 4px;">
								</td>								
						</tr>						
						<tr>
							<td height="27"></td><td colspan="2" height="27"><input type="button" class="btn_small" onclick="document.mailbox_write.submit();" value="{$lang.buttons.send_message}"></td>
						</tr>
					{else}
						{if !$hide_contact_form}
							<tr>
								<td width="120">{$lang.content.name}: <font class="error">*</font></td>
								<td><input type="text" class="str" size="50" name="name" value="{$data.name}"></td>
							</tr>
							<tr>
								<td>{$lang.content.email}:</td>
								<td><input type="text" class="str" size="50" name="email" value="{$data.email}"></td>
							</tr>
							<tr>
								<td>{$lang.content.subject}: <font class="error">*</font></td>
								<td><input type="text" class="str" size="50" name="subject" value="{$data.subject}"></td>
							</tr>
							<tr>
								<td>{$lang.content.body}: <font class="error">*</font></td>
								<td><textarea name="body" cols=50 rows=15>{$data.body}</textarea></td>
							</tr>
							<tr>
								<td>{$lang.content.keystring}: <font class="error">*</font></td>
								<td><img alt="" src="./include/kcaptcha/index.php" style="border: 1px solid #cccccc; padding: 3px;"><br>
									<input type="text" class="str" size="35" name="keystring" id="keystring" style="margin: 0px; margin-top: 4px;">
									</td>								
							</tr>
							<tr><td></td><td><input type="button" class="btn_small" onclick="javascript: document.mailbox_write.submit();" value="{$lang.buttons.send}"></td>
							</tr>
						{/if}
					{/if}
					</table>
				</td>
			</tr>
			</table>
		</form>
		{else}
		<tr><td colspan="2" class="header"><b>{$lang.content.for_agency}</b></td></tr>
		<tr><td colspan="2"><hr></td></tr>
		<tr>
			<td width="15">&nbsp;</td>
			<td>{if $mhi_registration}{$lang.content.text_top_mhi_registration}{else}{$lang.content.text_top}{/if}</td>
		</tr>
		</table>
		<br>
		<form action="{$file_name}" method="POST" name="agency_form" id="agency_form">
		<input type="hidden" id="sel" name="sel" value="agency_send">
			<table cellpadding="0" cellspacing="0" border="0" >
			<tr>
				<td width="12">&nbsp;</td>
				<td>
					<!-- for realtors -->
					{foreach item=item from=$error}
						<div style="padding-bottom: 5px; padding-left: 3px;" class="error">*&nbsp;{$item}</div>
					{/foreach}		
					<table cellpadding="0" cellspacing="3" border="0" width="100%">					
					<tr>
						<td height="27" width="175">{$lang.content.company_name}:&nbsp;<font class="error">*</font></td>
						<td><input type="text" class="str" size="50" name="company_name" id="company_name" value="{$data.company_name}"><span class="error" id="company_name_error" style="display: none;">&nbsp;{$lang.errors.incorrect_field}</span></td>
					</tr>
					<tr>
						<td height="27">{$lang.content.company_contact_person}:&nbsp;<font class="error">*</font></td>
						<td><input type="text" class="str" size="50" name="company_contact_person" id="company_contact_person" value="{$data.name}"><span class="error" id="company_contact_person_error" style="display: none;">&nbsp;{$lang.errors.incorrect_field}</span></td>
					</tr>
					<tr>
						<td height="27">{$lang.content.company_phone}:&nbsp;<font class="error">*</font></td>
						<td><input type="text" class="str" size="50" name="company_phone" id="company_phone" value="{$data.phone}"><span class="error" id="company_phone_error" style="display: none;">&nbsp;{$lang.errors.incorrect_field}</span></td>
					</tr>
					<tr>
						<td height="27">{$lang.content.company_email}:&nbsp;<font class="error">*</font></td>
						<td><input type="text" class="str" size="50" name="company_email" id="company_email" value="{$data.email}"><span class="error" id="email_error" style="display: none;">&nbsp;{$lang.errors.incorrect_field}</span>
						</td>
					</tr>
					<tr>
						<td height="27">{$lang.content.company_url}:&nbsp;<font class="error">*</font></td>
						<td><input type="text" class="str" size="50" name="company_url" id="company_url" value="{$data.company_url}"><span class="error" id="company_url_error" style="display: none;">&nbsp;{$lang.errors.incorrect_field}</span></td>
					</tr>
					<tr>
						<td height="27">{$lang.content.company_rent_count}:&nbsp;<font class="error">*</font></td>
						<td><input type="text" class="str" size="50" name="company_rent_count" id="company_rent_count" value="{$data.company_rent_count}"><span class="error" id="company_rent_count_error" style="display: none;">&nbsp;{$lang.errors.incorrect_field}</span></td>
					</tr>
					<tr>
						<td height="27">{$lang.content.company_how_know}:&nbsp;</td>
						<td><textarea cols="50" rows="5" name="company_how_know" id="company_how_know">{$data.company_how_know}</textarea></td>
					</tr>
					<tr>
						<td height="27">{$lang.content.company_quests_comments}:&nbsp;<font class="error">*</font></td>
						<td><textarea cols="50" rows="5" name="company_quests_comments" id="company_quests_comments">{$data.company_quests_comments}</textarea><span class="error" id="company_quests_comments_error" style="display: none;">&nbsp;{$lang.errors.incorrect_field}</span></td>
					</tr>
					<tr>
						<td>{$lang.content.keystring}: <font class="error">*</font></td>
						<td><img alt="" src="./include/kcaptcha/index.php" style="border: 1px solid #cccccc; padding: 3px;"><br>
							<input type="text" class="str" size="35" name="keystring" id="keystring" style="margin: 0px; margin-top: 4px;">
							</td>								
					</tr>
					<tr><td></td><td><input type="button" class="btn_small" onclick="javascript: {literal} if (FieldsCheck()==true) {document.agency_form.submit();} {/literal}" value="{$lang.buttons.send}"></td></tr>
					<tr><td colspan="2">&nbsp;</td></tr>
					<tr>
						<td colspan="2"><a href="{$show_url}">{$lang.content.show_link_text}</a></td>
					</tr>
					</table>
				</td>
			</tr>
			</table>
		</form>
		{/if}
	</td>
</tr>
</table>
{literal}
<script type="text/javascript">
	String.prototype.trim = function()
	{
	    return this.replace(/(^\s*)|(\s*$)/g, "");
	}

	function FieldCheck(obj) {
		if(obj.name=='company_email' && ( obj.value != "" && obj.value.search('^.+@.+\\..+$') !=-1) ){
			document.getElementById('email_error').style.display = 'none';
		} else {
			document.getElementById('email_error').style.display = 'inline';
		}
		return;
	}
	function FieldsCheck() {
		k = 1;
		if (document.getElementById('company_email').value !="" && document.getElementById('company_email').value.search('^.+@.+\\..+$') !=-1) {
			document.getElementById('email_error').style.display = 'none';
		} else {
			document.getElementById('email_error').style.display = 'inline';
			k = 0;
		}
		if (document.getElementById('company_name').value !="") {
			document.getElementById('company_name_error').style.display = 'none';
		} else {
			document.getElementById('company_name_error').style.display = 'inline';
			k = 0;
		}
		if (document.getElementById('company_contact_person').value !="") {
			document.getElementById('company_contact_person_error').style.display = 'none';
		} else {
			document.getElementById('company_contact_person_error').style.display = 'inline';
			k = 0;
		}
		if (document.getElementById('company_phone').value !="") {
			document.getElementById('company_phone_error').style.display = 'none';
		} else {
			document.getElementById('company_phone_error').style.display = 'inline';
			k = 0;
		}
		if (document.getElementById('company_url').value !="") {
			document.getElementById('company_url_error').style.display = 'none';
		} else {
			document.getElementById('company_url_error').style.display = 'inline';
			k = 0;
		}
		if (document.getElementById('company_rent_count').value !="") {
			document.getElementById('company_rent_count_error').style.display = 'none';
		} else {
			document.getElementById('company_rent_count_error').style.display = 'inline';
			k = 0;
		}
		if (document.getElementById('company_quests_comments').value !="") {
			document.getElementById('company_quests_comments_error').style.display = 'none';
		} else {
			document.getElementById('company_quests_comments_error').style.display = 'inline';
			k = 0;
		}
		if (k==1) {
			return true;
		} else {
			return false;
		}
	}
</script>
{/literal}
    </div>
{include file="$gentemplates/site_footer.tpl"}