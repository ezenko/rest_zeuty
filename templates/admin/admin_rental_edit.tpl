{include file="$admingentemplates/admin_top.tpl"}
<script>
//right vars values from lightbox.js
var fileLoadingImage = "{$server}{$site_root}{$template_root}/images/lightbox/loading.gif";
var fileBottomNavCloseImage = "{$server}{$site_root}{$template_root}/images/lightbox/closelabel.gif";

</script>
<script language="JavaScript" type="text/javascript" src="{$site_root}{$template_root}/js/lightbox/prototype.js"></script>
<script language="JavaScript" type="text/javascript" src="{$site_root}{$template_root}/js/lightbox/scriptaculous.js?load=effects"></script>
<script language="JavaScript" type="text/javascript" src="{$site_root}{$template_root}/js/lightbox/lightbox.js"></script>
<script language="JavaScript" type="text/javascript" src="{$site_root}{$template_root}/js/location.js"></script>
<script language="JavaScript" type="text/javascript" src="{$site_root}{$template_root}/js/JsHttpRequest.js"></script>
{literal}
<script>
function jsLoad(value, result_id, id_ad, upload_type, comment, user_upload_photo, choise, id_file, id_str_file, id_img_file) {
	
    var req = new JsHttpRequest();    
    req.onreadystatechange = function() {
        if (req.readyState == 4) {   	
        	if (req.responseText.substr(0,11) == '||success||'){
        		document.getElementById(result_id).innerHTML = req.responseText.substr(11,req.responseText.length);
        		UpdateUserUpload("view", id_ad, user_upload_photo, choise, upload_type, 0);
            	document.getElementById(id_file).value = '';
            	document.getElementById(id_str_file).innerHTML = '';
            	document.getElementById(id_img_file).innerHTML = '';
        	}else{
        		document.getElementById(result_id).innerHTML = req.responseText;
        	}
        	            
        }
    }
    
    req.open(null, 'admin_rentals.php?sel=js_upload&id_ad='+id_ad+'&upload_type='+ upload_type +'&upload_comment='+comment, true);
    req.send( { q: value } );
}
</script>
{/literal}
<table cellpadding="0" cellspacing="0" border="0" width="100%">
<tr valign="top">
	<td class="main" valign="top">
	<!-- RENTAL CONTENT -->
<TABLE cellpadding="0" cellspacing="0" border="0" width="100%">
	<tr>
		<td class="header">{strip}{$lang.menu.realestate} <font class="subheader">| {$lang.menu.realestate} | {$lang.menu.realestate_listings} |&nbsp;
			 {if $form.sel eq 'step_1'}
			 	{if $id_ad}{$lang.content.rental_ad_edit}{else}{$lang.content.rental_ad_new}{/if}
			 {else}
			 	{$lang.content.rental_ad_edit} 
			 	{if $choise}&nbsp;|&nbsp;{/if}
			 	{if $choise eq '1'}{$lang.content.category_wild_big}
				{elseif $choise eq '2'}{$lang.content.category_realty_big}
				{elseif $choise eq '3'}{$lang.content.category_tours_big}
				{elseif $choise eq '4'}{$lang.content.category_active_big}
				{/if}
			 {/if}			
			{/strip}</font>
		</td>
	</tr>
	<tr>
		<td><div class="help_text"><span class="help_title">{$lang.default_select.help}</span>{$lang.content.realestate_listings_help}</div></td>
	</tr>
	{if $form.sel eq 'step_1'}
	<!--//STEP 1-->
<TR><TD valign="top">
	<form method="POST" name="step_1" action="">
	<input name="id_ad" type="hidden" value="{$id_ad}">
	<table cellpadding="0" cellspacing="0" border="0" width="100%">		
		<tr valign="top">
			<td class="subsection_title">{$lang.content.page_header_step_1}</td>			
		</tr>
	</table>
	<table cellpadding="5" cellspacing="0" border="0" width="100%">
		<tr>
			<td>
				<table cellpadding="3" cellspacing="0" border="0" width="100%">
				<tr>
					<td height="27">
					{strip}
					{if !$mhi_ad_buy}
						{if !$choise || $choise==3 || $choise==1 || !$id_ad}
						<input type="radio" name="choise" value="3" {if $choise==3}checked{/if}>&nbsp;{$lang.content.category_tours}&nbsp;&nbsp;	
						{/if}
					{/if}
					{if !$mhi_ad_sell}
						{if !$choise || $choise==4 || $choise==2  || !$id_ad}
							<input type="radio" name="choise" value="4" checked {if $choise==4}checked{/if}>&nbsp;{$lang.content.category_active}&nbsp;&nbsp;
						{/if}
					{/if}	
					{if !$mhi_ad_rent}
						{if !$choise || $choise==3 || $choise==1  || !$id_ad}
						<input type="radio" name="choise" value="1" {if $choise==1}checked{/if}>&nbsp;{$lang.content.category_wild}&nbsp;&nbsp;
						{/if}
					{/if}
					{if !$mhi_ad_lease}
						{if !$choise || $choise==4 || $choise==2  || !$id_ad}
							<input type="radio" name="choise" value="2" {if $choise==2}checked{/if}>&nbsp;{$lang.content.category_realty}
						{/if}
					{/if}
					{/strip}
					</td>
				</tr>
				<tr>
					<td>
					<table cellpadding="0" cellspacing="0" border="0" height="25">
					<tr><td height="27" width="100">{$lang.content.select_country}&nbsp;:<font class="error">&nbsp;*</font></td>
						<td><div id="country_div" name="country_div">
								<select name="country" onchange="javascript: {literal} SelectRegion('rnte', this.value, document.getElementById('region_div'), document.getElementById('city_div'),'{/literal}{$lang.default_select.rnte_load_region}{literal}', '{/literal}{$lang.default_select.rnte_city}{literal}'); {/literal}" class="location">
									<option value="">{$lang.content.rnte_country}</option>
									{foreach item=item from=$country}
									<option value="{$item.id}" id="{$item.id}" {if $data.country eq $item.id} selected {/if}>{$item.name}</option>
									{/foreach}
								</select>
							</div>
						</td></tr>
					</table>
					</td>
				</tr>
				<tr>
					<td>
						<table cellpadding="0" cellspacing="0" border="0"  height="25">
						<tr><td height="27" width="100">{$lang.content.select_region}&nbsp;:<font class="error">&nbsp;*</font></td>
						<td><div id="region_div" name="region_div">
								<select name="region" onchange="javascript: {literal} SelectCity('rnte', this.value, document.getElementById('city_div'), '{/literal}{$lang.default_select.rnte_load_city}{literal}');{/literal}" class="location">
									<option value="">{$lang.content.rnte_region}</option>
									{foreach item=item from=$region}
									<option value="{$item.id}" id="{$item.id}" {if $data.region eq $item.id} selected {/if}>{$item.name}</option>
								{/foreach}
								</select>
							</div></td>
						</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td>
						<table cellpadding="0" cellspacing="0" border="0" height="25">
						<tr><td height="27" width="100">{$lang.content.select_city}&nbsp;:<div id="error_city" class="error" style="display:{if $choise == 3 || $choise == 1}none {else} inline{/if};">&nbsp;*</div></td>
						<td><div id="city_div" name="city_div">
								<select name="city" class="location">
									<option value="">{$lang.content.rnte_city}</option>
									{foreach item=item from=$city}
									<option value="{$item.id}" id="{$item.id}" {if $data.city eq $item.id} selected {/if}>{$item.name}</option>
									{/foreach}
								</select>
							</div></td>
						</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td id="subway_div" name="subway_div">
					{if $subway}
					<table cellpadding="0" cellspacing="0" border="0" height="25">
						<tr>
							<td height="27" width="100">{$lang.content.select_subway}&nbsp;:</td>
							<td>
								<select name="subway" id="subway" style="width: 150px;">
									<option value="">{$lang.content.rnte_subway}</option>
									{foreach item=item from=$subway}
									<option value="{$item.id}" id="{$item.id}" {if $subway_id eq $item.id} selected {/if}>{$item.name}</option>
									{/foreach}
								</select>
							</td>
						</tr>
					</table>
					{/if}
					</td>
				</tr>
				<tr>
					<td height="27"><input type="button" value="{$lang.buttons.save}" class="button_3" onclick="javascript: document.step_1.action='{$form.next_link}'; document.step_1.submit();"></td>
				</tr>
			</table>
			</td>
		</tr>
	</table>
	</form>
</TD></TR>
{elseif $form.sel eq 'step_3'}
	<!--//STEP 3-->
<TR><TD>
	<form method="POST" name="step_3" action="" id="step_3" enctype="multipart/form-data">
	<input name="choise" id="choise" type="hidden" value="{$choise}">
	<input name="id_ad" type="hidden" value="{$id_ad}">
	<table cellpadding="0" cellspacing="0" border="0" width="100%">
		<tr valign="top">
			<td class="subsection_title">
			{if $choise eq '1'}{$lang.content.page_header_step_3}
			{elseif $choise eq '2'}{$lang.content.page_header_step_3_have}
			{elseif $choise eq '3'}{$lang.content.page_header_step_3_buy}
			{elseif $choise eq '4'}{$lang.content.page_header_step_3_sell}
			{/if}</td>
		</tr>
	</table>
	<table cellpadding="0" cellspacing="0" border="0" width="100%">
		<tr>
			<td>
			<table cellpadding="5" cellspacing="0" border="0" width="100%">
			{if ($choise eq '1' || $choise eq '3')}
			<!-- need to rent / buy realty -->
			<!-- price -->
			<tr>
				<td width="110" valign="top" style="padding-top: 10px;">{if $choise eq '1'}{$lang.content.month_payment}{else}{$lang.content.price}{/if}:&nbsp;<font class="error">*</font></td>
				<td align="left">
					<table cellpadding="0" cellspacing="0" border="0">
					<tr>
						<td style="padding: 0px; padding-right: 3px;">{$lang.content.from}</td>
						<td align="right"><input type="text" class="str" name="min_payment" id="min_payment" value="{$data.min_payment}" size="7"></td>
						<td style="padding: 0px; padding-left: 5px; padding-right: 5px;">{$lang.content.upto}</td>
						<td align="left"><input type="text" class="str" name="max_payment" id="max_payment" value="{$data.max_payment}" size="7"></td>
						<td>&nbsp;{$cur}</td>
						<td><span name="min_payment_error" id="min_payment_error" style="display: none;" class="error">&nbsp;&nbsp;{$lang.errors.incorrect_field}<br></span>
							<span name="max_payment_error" id="max_payment_error" style="display: none;" class="error">&nbsp;&nbsp;{$lang.errors.incorrect_field}<br></span>
							<span name="bad_payment_error" id="bad_payment_error" style="display: none;" class="error">&nbsp;&nbsp;{$lang.content.price_min_more_max}</span>
						</td>
					</tr>
					</table>
					<!-- bidding possibility -->
					<table cellpadding="0" cellspacing="0" border="0">
						<tr>
							<td height="27" align="left">
								<table cellpadding="0" cellspacing="0" border="0">
									<tr>
										<td height="27"><input type="radio" name="auction" value="1" {if $data.auction eq 1 || !$data.auction}checked{/if} style="margin-left: 0px;"></td>
										<td>{$lang.content.auction_possible}</td>
										<td style="padding-left: 10px;"><input type="radio" name="auction" value="2" {if $data.auction eq 2}checked{/if} style="margin-left: 0px;"></td>
										<td>{$lang.content.auction_inpossible}</td>
									</tr>
								</table>
							</td>
						</tr>
					</table>
					<!-- /bidding possibility -->
				</td>
			</tr>
			<!-- /price -->
			{if $choise eq '1'}
				<!-- period -->
				{section name=f loop=$period}
					{if $period[f].visible_in ne 3}<!--visibility checking-->
						<tr>
							<td width="110" valign="top" style="padding-top: 10px;" {if $smarty.section.f.index is not div by 2 && $period[f].des_type ne 2}bgcolor="#eeeff3"{/if}>{$period[f].name}:&nbsp;<input type=hidden name="spr_period[{$period[f].num}]" value="{$period[f].id}"></td>
							{if $period[f].des_type eq 2}
							<td align="left">
								<select id="period{$period[f].num}" name="period[{$period[f].num}][]"  style="width:150px" {if $period[f].type eq 2}multiple{/if}>
								<option value="" {if !$item.sel} selected {/if} >{$lang.content.no_answer}</option>
								{foreach item=item from=$period[f].opt}<option value="{$item.value}" {if $item.sel}selected{/if}>{$item.name}</option>{/foreach}
								</select>
							</td>
							{else}
							<td align="left" {if $smarty.section.f.index is not div by 2}bgcolor="#eeeff3"{/if}>
								<table cellpadding="2" cellspacing="0" border="0">
								{section name=s loop=$period[f].opt}
								{if $smarty.section.s.index is div by 4}<tr>{/if}
								<td width="15" height="30"><input type="checkbox" name="period[{$period[f].num}][]" value="{$period[f].opt[s].value}"  {if $period[f].opt[s].sel} checked {/if}></td>
								<td width="130">{$period[f].opt[s].name}</td>
								{if $smarty.section.s.index_next is div by 4 || $smarty.section.s.last}</tr>{/if}
								{/section}
								</table>
							</td>
							{/if}
						</tr>
						{if $period[f].des_type ne 2}
						<tr {if $smarty.section.f.index is not div by 2}bgcolor="#eeeff3"{/if}>
							<td colspan="2" style="padding-left: 5px;">
								<table cellpadding="0" cellspacing="0">
								<tr>
									<td><span class="blue_link" onclick="javascript: SelAll('period',{$smarty.section.f.index}, 'step_3');">{$lang.content.sel_all_text}</span></td>
									<td style="padding-left: 5px;"><span class="blue_link" onclick="UnSelAll('period',{$smarty.section.f.index}, 'step_3');">{$lang.content.unsel_all_text}</span></td>
								</tr>
								</table>
							</td>
						</tr>
						{/if}
					{/if}
				{/section}
				<!-- /period -->
			{/if}
			<!-- deposit -->
			<tr>
				<td width="110" valign="top" style="padding-top: 10px;">{$lang.content.deposit}:</td>
				<td align="left">
					<table cellpadding="0" cellspacing="0" border="0">
					<tr>
						<td style="padding: 0px; padding-right: 5px;">{$lang.content.from}</td>
						<td align="right"><input type="text" class="str" value="{$data.min_deposit}" name="min_deposit" id="min_deposit" style="width: 50px;"'></td>
						<td style="padding: 0px; padding-left: 5px; padding-right: 5px;">{$lang.content.upto}</td>
						<td><input type="text" class="str" value="{$data.max_deposit}" name="max_deposit" id="max_deposit" style="width: 50px;"'></td>
						<td>&nbsp;{$cur}</td>
						<td>
							<div class="error" id="min_deposit_error" style="display: none;">&nbsp;&nbsp;{$lang.errors.incorrect_field}</div>
							<div class="error" id="max_deposit_error" style="display: none;">&nbsp;&nbsp;{$lang.errors.incorrect_field}</div>
							<div class="error" id="bad_deposit_error" style="display: none;">&nbsp;&nbsp;{$lang.content.deposit_min_more_max}</div>
						</td>
					</tr>
					</table>
				</td>
			</tr>
			<!-- date -->
			<tr>
				<td width="110" valign="top" style="padding-top: 10px;">{$lang.content.move_date}:&nbsp;<font class="error">*</font></td>
				<td align="left">
					<select name="move_month">
					{foreach item=item from=$month}<option value="{$item.value}" {if $item.sel}selected{/if}>{$item.name}</option>{/foreach}
					</select>
					<select name="move_day">
					{foreach item=item from=$day}<option value="{$item.value}" {if $item.sel}selected{/if}>{$item.value}</option>{/foreach}
					</select>
					<select name="move_year">
					{foreach item=item from=$year}<option value="{$item.value}" {if $item.sel}selected{/if}>{$item.value}</option>{/foreach}
					</select>
				</td>
			</tr>
			<!-- type -->
			{section name=f loop=$realty_type}
				{if $realty_type[f].visible_in ne 3}<!--visibility checking-->
					<tr>
						<td width="110" valign="top" style="padding-top: 10px;">{$realty_type[f].name}:&nbsp;<font class="error">*</font><input type=hidden name="spr_realty_type[{$realty_type[f].num}]" value="{$realty_type[f].id}"></td>
						<td align="left">
						{if $realty_type[f].des_type eq 2}
							<select id="realty_type{$realty_type[f].num}" name="realty_type[{$realty_type[f].num}][]"  style="width:150px" {if $realty_type[f].type eq 2}multiple{/if}>
							<option value="" {if !$item.sel} selected {/if} >{$lang.content.please_choose}</option>
							{foreach item=item from=$realty_type[f].opt}<option value="{$item.value}" {if $item.sel}selected{/if}>{$item.name}</option>{/foreach}
							</select>
						{else}
							<table cellpadding="2" cellspacing="0" border="0">
							{section name=s loop=$realty_type[f].opt}
							{if $smarty.section.s.index is div by 4}<tr>{/if}
							<td width="15" height="30"><input type="checkbox" name="realty_type[{$realty_type[f].num}][]" value="{$realty_type[f].opt[s].value}"  {if $realty_type[f].opt[s].sel} checked {/if}></td>
							<td width="130">{$realty_type[f].opt[s].name}</td>
							{if $smarty.section.s.index_next is div by 4 || $smarty.section.s.last}</tr>{/if}
							{/section}

						{/if}
						<div class="error" id="realty_type_error" style="display: none;">&nbsp;&nbsp;{$lang.content.incorrect_field}</div>
						</td>
					</tr>
				{/if}
			{/section}
			<!-- /type -->
			<!-- year build -->
			<tr>
				<td width="110" valign="top" style="padding-top: 10px;">{$lang.content.year_build}:</td>
				<td align="left">
					<table cellpadding="0" cellspacing="0" border="0">
					<tr>
						<td style="padding: 0px; padding-right: 5px;">{$lang.content.from_1}</td>
						<td align="right"><input type="text" class="str" value="{$data.min_year_build}" name="min_year_build" id="min_year_build" style="width: 50px;"'></td>
						<td style="padding: 0px; padding-left: 5px; padding-right: 5px;">{$lang.content.upto_1}</td>
						<td><input type="text" class="str" value="{$data.max_year_build}" name="max_year_build" id="max_year_build" style="width: 50px;"'></td>
						<td>
							<div class="error" id="min_year_build_error" style="display: none;">&nbsp;&nbsp;{$lang.content.incorrect_field}</div>
							<div class="error" id="max_year_build_error" style="display: none;">&nbsp;&nbsp;{$lang.content.incorrect_field}</div>
							<div class="error" id="bad_year_build_error" style="display: none;">&nbsp;&nbsp;{$lang.content.year_build_min_more_max}</div>
						</td>
					</tr>
					</table>
				</td>
			</tr>
			<!-- /year build -->
			<!-- description -->
			{section name=f loop=$description}
				{if $description[f].visible_in ne 3}<!--visibility checking-->
					<tr>
						<td width="110" valign="top" style="padding-top: 10px;" {if $smarty.section.f.index is not div by 2 && $description[f].des_type ne 2}bgcolor="#eeeff3"{/if}>{$description[f].name}:&nbsp;<input type=hidden name="spr_description[{$description[f].num}]" value="{$description[f].id}"></td>
						{if $description[f].des_type eq 2}
						<td align="left">
							<select id="description{$description[f].num}" name="description[{$description[f].num}][]"  style="width:150px" {if $description[f].type eq 2}multiple{/if}>
							<option value="" {if !$item.sel && $description[f].type eq 1} selected {/if} >{$lang.content.no_answer}</option>
							{foreach item=item from=$description[f].opt}<option value="{$item.value}" {if $item.sel}selected{/if}>{$item.name}</option>{/foreach}
							</select>
						</td>
						{else}
						<td align="left" {if $smarty.section.f.index is not div by 2}bgcolor="#eeeff3"{/if}>
							<table cellpadding="2" cellspacing="0" border="0">
							{section name=s loop=$description[f].opt}
							{if $smarty.section.s.index is div by 4}<tr>{/if}
							<td width="15" height="30"><input {if $description[f].type eq 1}type="radio"{elseif $description[f].type eq 2}type="checkbox"{/if}  name="description[{$description[f].num}][]" value="{$description[f].opt[s].value}"  {if $description[f].opt[s].sel} checked {/if}></td>
							<td width="130">{$description[f].opt[s].name}</td>
							{if $smarty.section.s.index_next is div by 4 || $smarty.section.s.last}</tr>{/if}
							{/section}
							</table>
						</td>
						{/if}
					</tr>
					{if $description[f].des_type eq 1}
					<tr {if $smarty.section.f.index is not div by 2}bgcolor="#eeeff3"{/if}>
						<td colspan="2" style="padding-left: 5px;">
							<table cellpadding="0" cellspacing="0">
							<tr>
								<td>
								{if $description[f].type eq 2}
									<span class="blue_link" onclick="javascript: SelAll('description',{$smarty.section.f.index}, 'step_3');">{$lang.content.sel_all_text}</span></td>
									<td style="padding-left: 5px;">
								{/if}
									<span class="blue_link" onclick="UnSelAll('description',{$smarty.section.f.index}, 'step_3');">{$lang.content.unsel_all_text}</span></td>
							</tr>
							</table>
						</td>
					</tr>
					{/if}
				{/if}
			{/section}
			<!-- /description -->
			<!-- square -->
			<tr>
				<td width="110" valign="top" style="padding-top: 10px;">{$lang.content.live_square}:</td>
				<td align="left">
					<table cellpadding="0" cellspacing="0" border="0">
					<tr>
						<td style="padding: 0px; padding-right: 5px;">{$lang.content.from}</td>
						<td align="right"><input type="text" class="str" value="{$data.min_live_square}" name="min_live_square" id="min_live_square" style="width: 50px;"'></td>
						<td style="padding: 0px; padding-left: 5px; padding-right: 5px;">{$lang.content.upto}</td>
						<td><input type="text" class="str" value="{$data.max_live_square}" name="max_live_square" id="max_live_square" style="width: 50px;"'></td>
						<td>&nbsp;{$sq_meters}</td>
						<td align="left">
							<div class="error" id="min_live_square_error" style="display: none;">&nbsp;&nbsp;{$lang.content.incorrect_field}</div>
							<div class="error" id="max_live_square_error" style="display: none;">&nbsp;&nbsp;{$lang.content.incorrect_field}</div>
							<div class="error" id="bad_live_square_error" style="display: none;">&nbsp;&nbsp;{$lang.content.live_square_min_more_max}</div>
						</td>
					</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td width="110" valign="top" style="padding-top: 10px;">{$lang.content.total_square}:</td>
				<td align="left">
					<table cellpadding="0" cellspacing="0" border="0">
					<tr>
						<td style="padding: 0px; padding-right: 5px;">{$lang.content.from}</td>
						<td align="right"><input type="text" class="str" value="{$data.min_total_square}" name="min_total_square" id="min_total_square" style="width: 50px;"'></td>
						<td style="padding: 0px; padding-left: 5px; padding-right: 5px;">{$lang.content.upto}</td>
						<td><input type="text" class="str" value="{$data.max_total_square}" name="max_total_square" id="max_total_square" style="width: 50px;"'></td>
						<td>&nbsp;{$sq_meters}</td>
						<td>
							<div class="error" id="min_total_square_error" style="display: none;">&nbsp;&nbsp;{$lang.content.incorrect_field}</div>
							<div class="error" id="max_total_square_error" style="display: none;">&nbsp;&nbsp;{$lang.content.incorrect_field}</div>
							<div class="error" id="bad_total_square_error" style="display: none;">&nbsp;&nbsp;{$lang.content.total_square_min_more_max}</div>
						</td>
					</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td width="110" valign="top" style="padding-top: 10px;">{$lang.content.land_square}:</td>
				<td align="left">
					<table cellpadding="0" cellspacing="0" border="0">
					<tr>
						<td style="padding: 0px; padding-right: 5px;">{$lang.content.from}</td>
						<td align="right"><input type="text" class="str" value="{$data.min_land_square}" name="min_land_square" id="min_land_square" style="width: 50px;"'></td>
						<td style="padding: 0px; padding-left: 5px; padding-right: 5px;">{$lang.content.upto}</td>
						<td><input type="text" class="str" value="{$data.max_land_square}" name="max_land_square" id="max_land_square" style="width: 50px;"'></td>
						<td>&nbsp;{$sq_meters}</td>
						<td>
							<div class="error" id="min_land_square_error" style="display: none;">&nbsp;&nbsp;{$lang.content.incorrect_field}</div>
							<div class="error" id="max_land_square_error" style="display: none;">&nbsp;&nbsp;{$lang.content.incorrect_field}</div>
							<div class="error" id="bad_land_square_error" style="display: none;">&nbsp;&nbsp;{$lang.content.land_square_min_more_max}</div>
						</td>
					</tr>
					</table>
				</td>
			</tr>
			<!-- /square -->
			<!-- floor -->
			<tr>
				<td width="110" valign="top" style="padding-top: 10px;">{$lang.content.floor_variants}:</td>
				<td align="left">
					<table cellpadding="0" cellspacing="0" border="0">
					<tr>
						<td style="padding: 0px; padding-right: 5px;">{$lang.content.from_1}</td>
						<td align="right"><input type="text" class="str" value="{$data.min_floor}" name="min_floor" id="min_floor" style="width: 50px;"'></td>
						<td style="padding: 0px; padding-left: 5px; padding-right: 5px;">{$lang.content.upto_1}</td>
						<td><input type="text" class="str" value="{$data.max_floor}" name="max_floor" id="max_floor" style="width: 50px;"'></td>
						<td>&nbsp;{$lang.content.floor}</td>
						<td>
							<div class="error" id="min_floor_error" style="display: none;">&nbsp;&nbsp;{$lang.content.incorrect_field}</div>
							<div class="error" id="max_floor_error" style="display: none;">&nbsp;&nbsp;{$lang.content.incorrect_field}</div>
							<div class="error" id="bad_floor_error" style="display: none;">&nbsp;&nbsp;{$lang.content.floor_min_more_max}</div>
						</td>
					</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td width="110" valign="top" style="padding-top: 10px;">{$lang.content.floor_num_max_limitation}:&nbsp;</td>
				<td><input type="text" class="str" value="{$data.floor_num}" name="floor_num" id="floor_num" style="width: 50px;"'>&nbsp;{$lang.content.of_floors}
					<span class="error" name="floor_num_error" id="floor_num_error" style="display: none;">&nbsp;&nbsp;{$lang.content.incorrect_field}</span>
				</td>
			</tr>
			<!-- /floor -->
			<!-- subway_min -->
			<tr>
				<td width="110" valign="top" style="padding-top: 10px;">{$lang.content.subway_min}:&nbsp;</td>
				<td class="text_small" align="left"><input type="text" class="str" name="subway_min" id="subway_min" size="10" value="{$data.subway_min}"'>&nbsp;{$lang.content.minutes}
					<span class="error" id="subway_min_error" style="display: none;">&nbsp;&nbsp;{$lang.content.incorrect_field}</span>
				</td>
			</tr>
			<!-- /subway_min -->
			<!-- info -->
			{section name=f loop=$info}
				{if $info[f].visible_in ne 3}<!--visibility checking-->
					<tr>
						<td width="110" valign="top" style="padding-top: 10px;" {if $smarty.section.f.index is not div by 2 && $info[f].des_type ne 2}bgcolor="#eeeff3"{/if}>{$info[f].name}:&nbsp;<input type=hidden name="spr_info[{$info[f].num}]" value="{$info[f].id}"></td>
						{if $info[f].des_type eq 2}
						<td align="left">
							<select id="info{$info[f].num}" name="info[{$info[f].num}][]"  style="width:150px" {if $info[f].type eq 2}multiple{/if}>
							<option value="" {if !$item.sel && $info[f].type eq 1} selected {/if} >{$lang.content.no_answer}</option>
							{foreach item=item from=$info[f].opt}<option value="{$item.value}" {if $item.sel}selected{/if}>{$item.name}</option>{/foreach}
							</select>
						</td>
						{else}
						<td align="left" {if $smarty.section.f.index is not div by 2}bgcolor="#eeeff3"{/if}>
							<table cellpadding="2" cellspacing="0" border="0">
							{section name=s loop=$info[f].opt}
							{if $smarty.section.s.index is div by 4}<tr>{/if}
							<td width="15" height="30"><input {if $info[f].type eq 1}type="radio"{elseif $info[f].type eq 2}type="checkbox"{/if}  name="info[{$info[f].num}][]" value="{$info[f].opt[s].value}"  {if $info[f].opt[s].sel} checked {/if}></td>
							<td width="130">{$info[f].opt[s].name}</td>
							{if $smarty.section.s.index_next is div by 4 || $smarty.section.s.last}</tr>{/if}
							{/section}
							</table>
						</td>
						{/if}
					</tr>
					{if $info[f].des_type eq 1}
					<tr {if $smarty.section.f.index is not div by 2}bgcolor="#eeeff3"{/if}>
						<td colspan="2" style="padding-left: 5px;">
							<table cellpadding="0" cellspacing="0">
							<tr>
								<td>
								{if $info[f].type eq 2}
									<span class="blue_link" onclick="javascript: SelAll('info',{$smarty.section.f.index}, 'step_3');">{$lang.content.sel_all_text}</span></td>
									<td style="padding-left: 5px;">
								{/if}
									<span class="blue_link" onclick="UnSelAll('info',{$smarty.section.f.index}, 'step_3');">{$lang.content.unsel_all_text}</span></td>
							</tr>
							</table>
						</td>
					</tr>
					{/if}
				{/if}
			{/section}
			<!-- /info -->
			<!-- with photo -->
			<tr>
				<td width="110" align="absmiddle">{$lang.content.photo_exist}:&nbsp;</td>
				<td class="text_small" align="left">
					<table cellpadding="0" cellspacing="0" border="0">
						<tr>
							<td align="absmiddle"><input type="checkbox" name="with_photo" id="with_photo" {if $data.with_photo == 1}checked{/if}></td>
							<td align="absmiddle">{$lang.content.only_with}</td>
						</tr>
					</table>
				</td>
			</tr>
			<!-- /with photo -->
			<!-- with video -->
			<tr>
				<td width="110" align="absmiddle">{$lang.content.video_exist}:&nbsp;</td>
				<td class="text_small" align="left">
					<table cellpadding="0" cellspacing="0" border="0">
						<tr>
							<td align="absmiddle"><input type="checkbox" name="with_video" id="with_video" {if $data.with_video == 1}checked{/if}></td>
							<td align="absmiddle">{$lang.content.only_with}</td>
						</tr>
					</table>
				</td>
			</tr>
			<!-- /with video -->
			<tr>
				<td colspan="2" align="left">
					<input type="button" value="{$lang.buttons.save}" id="next_btn" class="button_3" onclick="{literal}javascript: if (CheckStep('step_3')) {document.step_3.action='{/literal}{$form.next_link}{literal}'; document.step_3.submit();}{/literal}">
				</td>
			</tr>
			{else}<!-- have / sell realty -->
			<!-- price -->
			<tr>
				<td width="110" valign="top" style="padding-top: 10px;">{if $choise eq '2'}{$lang.content.month_payment}{else}{$lang.content.price}{/if}:&nbsp;<font class="error">*</font></td>
				<td align="left">
					<table cellpadding="0" cellspacing="0" border="0">
					<tr>
						<td align="right"><input type="text" class="str" name="min_payment" id="min_payment" value="{$data.min_payment}" size="7">&nbsp;{$cur}</td>
						<td>
							<span name="min_payment_error" id="min_payment_error" style="display: none;" class="error">&nbsp;&nbsp;{$lang.content.incorrect_field}</span>
						</td>
					</tr>
					</table>
					<!-- bidding possibility -->
					<table cellpadding="0" cellspacing="0" border="0">
						<tr>
							<td height="27" align="left">
								<table cellpadding="0" cellspacing="0" border="0">
									<tr>
										<td height="27"><input type="radio" name="auction" value="1" {if $data.auction eq 1 || !$data.auction}checked{/if} style="margin-left: 0px;"></td>
										<td>{$lang.content.auction_possible}</td>
										<td style="padding-left: 10px;"><input type="radio" name="auction" value="2" {if $data.auction eq 2}checked{/if} style="margin-left: 0px;"></td>
										<td>{$lang.content.auction_inpossible}</td>
									</tr>
								</table>
							</td>
						</tr>
					</table>
					<!-- /bidding possibility -->
				</td>
			</tr>
			<!-- /price -->
			{if $choise eq '2'}
				<!-- period -->
				{section name=f loop=$period}
					{if $period[f].visible_in ne 3}<!--visibility checking-->
						<tr>
							<td width="110" valign="top" style="padding-top: 10px;" {if $smarty.section.f.index is not div by 2 && $period[f].des_type ne 2}bgcolor="#eeeff3"{/if}>{$period[f].name}:&nbsp;<input type=hidden name="spr_period[{$period[f].num}]" value="{$period[f].id}"></td>
							{if $period[f].des_type eq 2}
							<td align="left">
								<select id="period{$period[f].num}" name="period[{$period[f].num}][]"  style="width:150px" {if $period[f].type eq 2}multiple{/if}>
								<option value="" {if !$item.sel} selected {/if} >{$lang.content.no_answer}</option>
								{foreach item=item from=$period[f].opt}<option value="{$item.value}" {if $item.sel}selected{/if}>{$item.name}</option>{/foreach}
								</select>
							</td>
							{else}
							<td align="left" {if $smarty.section.f.index is not div by 2}bgcolor="#eeeff3"{/if}>
								<table cellpadding="2" cellspacing="0" border="0">
								{section name=s loop=$period[f].opt}
								{if $smarty.section.s.index is div by 4}<tr>{/if}
								<td width="15" height="30"><input type="checkbox" name="period[{$period[f].num}][]" value="{$period[f].opt[s].value}"  {if $period[f].opt[s].sel} checked {/if}></td>
								<td width="130">{$period[f].opt[s].name}</td>
								{if $smarty.section.s.index_next is div by 4 || $smarty.section.s.last}</tr>{/if}
								{/section}
								</table>
							</td>
							{/if}
						</tr>
						{if $period[f].des_type ne 2}
						<tr {if $smarty.section.f.index is not div by 2}bgcolor="#eeeff3"{/if}>
							<td colspan="2" style="padding-left: 5px;">
								<table cellpadding="0" cellspacing="0">
								<tr>
									<td><span class="blue_link" onclick="javascript: SelAll('period',{$smarty.section.f.index}, 'step_3');">{$lang.content.sel_all_text}</span></td>
									<td style="padding-left: 5px;"><span class="blue_link" onclick="UnSelAll('period',{$smarty.section.f.index}, 'step_3');">{$lang.content.unsel_all_text}</span></td>
								</tr>
								</table>
							</td>
						</tr>
						{/if}
					{/if}
				{/section}
				<!-- /period -->
			{/if}
			<!-- deposit -->
			<tr>
				<td width="110" valign="top" style="padding-top: 10px;">{$lang.content.deposit}:&nbsp;</td>
				<td><input type="text" class="str" value="{$data.min_deposit}" name="min_deposit" id="min_deposit" style="width: 50px;">&nbsp;{$cur}
					<span class="error" id="min_deposit_error" style="display: none;">&nbsp;&nbsp;{$lang.errors.incorrect_field}</span>
				</td>
			</tr>
			<!-- date -->
			<tr>
				<td width="110" valign="top" style="padding-top: 10px;">{$lang.content.available_move_date}:&nbsp;<font class="error">*</font></td>
				<td align="left">
					<select name="move_month">
					{foreach item=item from=$month}<option value="{$item.value}" {if $item.sel}selected{/if}>{$item.name}</option>{/foreach}
					</select>
					<select name="move_day">
					{foreach item=item from=$day}<option value="{$item.value}" {if $item.sel}selected{/if}>{$item.value}</option>{/foreach}
					</select>
					<select name="move_year">
					{foreach item=item from=$year}<option value="{$item.value}" {if $item.sel}selected{/if}>{$item.value}</option>{/foreach}
					</select>
				</td>
			</tr>
			<!-- type -->
			{section name=f loop=$realty_type}
				{if $realty_type[f].visible_in ne 3}<!--visibility checking-->
					<tr>
						<td width="110" valign="top" style="padding-top: 10px;">{$realty_type[f].name}:&nbsp;<font class="error">*</font><input type=hidden name="spr_realty_type[{$realty_type[f].num}]" value="{$realty_type[f].id}"></td>
						<td align="left">
						{if $realty_type[f].des_type eq 2}
							<select id="realty_type{$realty_type[f].num}" name="realty_type[{$realty_type[f].num}][]"  style="width:150px" {if $realty_type[f].type eq 2}multiple{/if}>
							<option value="" {if !$item.sel} selected {/if} >{$lang.content.please_choose}</option>
							{foreach item=item from=$realty_type[f].opt}<option value="{$item.value}" {if $item.sel}selected{/if}>{$item.name}</option>{/foreach}
							</select>
						{else}
							<table cellpadding="2" cellspacing="0" border="0">
							{section name=s loop=$realty_type[f].opt}
							{if $smarty.section.s.index is div by 4}<tr>{/if}
							<td width="15" height="30"><input type="checkbox" name="realty_type[{$realty_type[f].num}][]" value="{$realty_type[f].opt[s].value}"  {if $realty_type[f].opt[s].sel} checked {/if}></td>
							<td width="130">{$realty_type[f].opt[s].name}</td>
							{if $smarty.section.s.index_next is div by 4 || $smarty.section.s.last}</tr>{/if}
							{/section}

						{/if}
						<div class="error" id="realty_type_error" style="display: none;">&nbsp;&nbsp;{$lang.content.incorrect_field}</div>
						</td>
					</tr>
				{/if}
			{/section}
			<!-- /type -->
			<!-- year build -->
			<tr>
				<td width="110" valign="top" style="padding-top: 10px;">{$lang.content.year_build}:</td>
				<td><input type="text" class="str" value="{$data.min_year_build}" name="min_year_build" id="min_year_build" style="width: 50px;">
					<span class="error" id="min_year_build_error" style="display: none;">&nbsp;&nbsp;{$lang.content.incorrect_field}<br></span>
				</td>
			</tr>
			<!-- /year build -->
			<!-- description -->
			{section name=f loop=$description}
				{if $description[f].visible_in ne 3}<!--visibility checking-->
					<tr>
						<td width="110" valign="top" style="padding-top: 10px;" {if $smarty.section.f.index is not div by 2 && $description[f].des_type ne 2}bgcolor="#eeeff3"{/if}>{$description[f].name}:&nbsp;<input type=hidden name="spr_description[{$description[f].num}]" value="{$description[f].id}"></td>
						{if $description[f].des_type eq 2}
						<td align="left">
							<select id="description{$description[f].num}" name="description[{$description[f].num}][]"  style="width:150px" {if $description[f].type eq 2}multiple{/if}>
							<option value="" {if !$item.sel && $description[f].type eq 1} selected {/if} >{$lang.content.no_answer}</option>
							{foreach item=item from=$description[f].opt}<option value="{$item.value}" {if $item.sel}selected{/if}>{$item.name}</option>{/foreach}
							</select>
						</td>
						{else}
						<td align="left" {if $smarty.section.f.index is not div by 2}bgcolor="#eeeff3"{/if}>
							<table cellpadding="2" cellspacing="0" border="0">
							{section name=s loop=$description[f].opt}
							{if $smarty.section.s.index is div by 4}<tr>{/if}
							<td width="15" height="30"><input {if $description[f].type eq 1}type="radio"{elseif $description[f].type eq 2}type="checkbox"{/if}  name="description[{$description[f].num}][]" value="{$description[f].opt[s].value}"  {if $description[f].opt[s].sel} checked {/if}></td>
							<td width="130">{$description[f].opt[s].name}</td>
							{if $smarty.section.s.index_next is div by 4 || $smarty.section.s.last}</tr>{/if}
							{/section}
							</table>
						</td>
						{/if}
					</tr>
					{if $description[f].des_type eq 1}
					<tr {if $smarty.section.f.index is not div by 2}bgcolor="#eeeff3"{/if}>
						<td colspan="2" style="padding-left: 5px;">
							<table cellpadding="0" cellspacing="0">
							<tr>
								<td>
								{if $description[f].type eq 2}
									<span class="blue_link" onclick="javascript: SelAll('description',{$smarty.section.f.index}, 'step_3');">{$lang.content.sel_all_text}</span></td>
									<td style="padding-left: 5px;">
								{/if}
									<span class="blue_link" onclick="UnSelAll('description',{$smarty.section.f.index}, 'step_3');">{$lang.content.unsel_all_text}</span></td>
							</tr>
							</table>
						</td>
					</tr>
					{/if}
				{/if}
			{/section}
			<!-- /description -->
			<!-- square -->
			<tr>
				<td width="110" valign="top" style="padding-top: 10px;">{$lang.content.live_square}:&nbsp;</td>
				<td class="text_small" align="left"><input type="text" class="str" name="min_live_square" id="min_live_square" size="10" value="{$data.min_live_square}">&nbsp;{$sq_meters}
					<span class="error" id="min_live_square_error" style="display: none;">&nbsp;&nbsp;{$lang.content.incorrect_field}</span>
				</td>
			</tr>
			<tr>
				<td width="110" valign="top" style="padding-top: 10px;">{$lang.content.total_square}:&nbsp;</td>
				<td class="text_small" align="left"><input type="text" class="str" name="min_total_square" id="min_total_square" size="10" value="{$data.min_total_square}">&nbsp;{$sq_meters}
					<span class="error" id="min_total_square_error" style="display: none;">&nbsp;&nbsp;{$lang.content.incorrect_field}</span>
				</td>
			</tr>
			<tr>
				<td width="110" valign="top" style="padding-top: 10px;">{$lang.content.land_square}:&nbsp;</td>
				<td class="text_small" align="left"><input type="text" class="str" name="min_land_square" id="min_land_square" size="10" value="{$data.min_land_square}">&nbsp;{$sq_meters}
					<span class="error" id="min_land_square_error" style="display: none;">&nbsp;&nbsp;{$lang.content.incorrect_field}</span>
				</td>
			</tr>
			<!-- /square -->
			<!-- floor -->
			<tr>
				<td width="110" valign="top" style="padding-top: 10px;">{$lang.content.property_floor_number}:&nbsp;</td>
				<td class="text_small" align="left"><input type="text" class="str" value="{$data.min_floor}" name="min_floor" id="min_floor" style="width: 50px;">&nbsp;{$lang.content.floor}
					<span class="error" id="min_floor_error" style="display: none;">&nbsp;&nbsp;{$lang.content.incorrect_field}<br></span>
				</td>
			</tr>
			<tr>
				<td width="110" valign="top" style="padding-top: 10px;">{$lang.content.total_floor_num}:&nbsp;</td>
				<td><input type="text" class="str" value="{$data.floor_num}" name="floor_num" id="floor_num" style="width: 50px;">&nbsp;{$lang.content.of_floors}
					<span class="error" id="floor_num_error" style="display: none;">&nbsp;&nbsp;{$lang.total_floor_num.incorrect_field}</span>
				</td>
			</tr>
			<!-- /floor -->
			<!-- subway_min -->
			<tr>
				<td width="110" valign="top" style="padding-top: 10px;">{$lang.content.subway_min}:&nbsp;</td>
				<td class="text_small" align="left"><input type="text" class="str" name="subway_min" id="subway_min" size="10" value="{$data.subway_min}">&nbsp;{$lang.content.minutes}
					<span class="error" id="subway_min_error" style="display: none;">&nbsp;&nbsp;{$lang.total_floor_num.incorrect_field}</span>
				</td>
			</tr>
			<!-- /subway_min -->
			<!-- info -->
			{section name=f loop=$info}
				{if $info[f].visible_in ne 3}<!--visibility checking-->
					<tr>
						<td width="110" valign="top" style="padding-top: 10px;" {if $smarty.section.f.index is not div by 2 && $info[f].des_type ne 2}bgcolor="#eeeff3"{/if}>{$info[f].name}:&nbsp;<input type=hidden name="spr_info[{$info[f].num}]" value="{$info[f].id}"></td>
						{if $info[f].des_type eq 2}
						<td align="left">
							<select id="info{$info[f].num}" name="info[{$info[f].num}][]"  style="width:150px" {if $info[f].type eq 2}multiple{/if}>
							<option value="" {if !$item.sel && $info[f].type eq 1} selected {/if} >{$lang.content.no_answer}</option>
							{foreach item=item from=$info[f].opt}<option value="{$item.value}" {if $item.sel}selected{/if}>{$item.name}</option>{/foreach}
							</select>
						</td>
						{else}
						<td align="left" {if $smarty.section.f.index is not div by 2}bgcolor="#eeeff3"{/if}>
							<table cellpadding="2" cellspacing="0" border="0">
							{section name=s loop=$info[f].opt}
							{if $smarty.section.s.index is div by 4}<tr>{/if}
							<td width="15" height="30"><input {if $info[f].type eq 1}type="radio"{elseif $info[f].type eq 2}type="checkbox"{/if}  name="info[{$info[f].num}][]" value="{$info[f].opt[s].value}"  {if $info[f].opt[s].sel} checked {/if}></td>
							<td width="130">{$info[f].opt[s].name}</td>
							{if $smarty.section.s.index_next is div by 4 || $smarty.section.s.last}</tr>{/if}
							{/section}
							</table>
						</td>
						{/if}
					</tr>
					{if $info[f].des_type eq 1}
					<tr {if $smarty.section.f.index is not div by 2}bgcolor="#eeeff3"{/if}>
						<td colspan="2" style="padding-left: 5px;">
							<table cellpadding="0" cellspacing="0">
							<tr>
								<td>
								{if $info[f].type eq 2}
									<span class="blue_link" onclick="javascript: SelAll('info',{$smarty.section.f.index}, 'step_3');">{$lang.content.sel_all_text}</span></td>
									<td style="padding-left: 5px;">
								{/if}
									<span class="blue_link" onclick="UnSelAll('info',{$smarty.section.f.index}, 'step_3');">{$lang.content.unsel_all_text}</span></td>
							</tr>
							</table>
						</td>
					</tr>
					{/if}
				{/if}
			{/section}
			<!-- /info -->
			<tr>
				<td colspan="2" align="left">
					<input type="button" value="{$lang.buttons.save}" class="button_3" onclick="{literal}javascript: if (CheckStep('step_3')) { document.step_3.action='{/literal}{$form.next_link}{literal}'; document.step_3.submit(); }{/literal}"></td>
				</td>
			</tr>
			{/if}
			</table>
			</td>
		</tr>
	</table>
	</form>
</TD></TR>
	{elseif $form.sel eq 'step_4'}
<TR><TD>
	<!--//STEP 4-->
	<form method="POST" name="step_4" id="step_4" action="" enctype="multipart/form-data">
	<input name="choise" type="hidden" value="{$choise}">
	<input name="id_ad" type="hidden" value="{$id_ad}">
	<table cellpadding="0" cellspacing="0" border="0" width="100%">
		<tr valign="top">
			<td class="subsection_title"><b>{if ($choise eq '1' || $choise eq '3')}{$lang.content.page_header_step_4}{else}{$lang.content.page_header_step_4_have}{/if}</b></td>
		</tr>
	</table>
	<table cellpadding="0" cellspacing="0" border="0" width="100%">
		<tr>
			<td>
			<table cellpadding="5" cellspacing="0" border="0" width="100%">
				{if ($choise eq '2' || $choise eq '4')}
					<tr>
						<td colspan="2">{$lang.content.photo_word}</td>
					</tr>
					<tr>
						<td colspan="2">{$lang.content.photo_max_count}:&nbsp;{$data.limit}</td>
					</tr>
					<tr>
						<td colspan="2">
						<div id="user_upload_photo">
							{include file="$gentemplates/user_upload.tpl"}
						</div>	
						</td>
					</tr>
					<form method="POST" enctype="multipart/form-data" onsubmit="return false;">
					<tr>
						<td width="20%">
						<table cellpadding="0" cellspacing="0" border="0" class="upload_file">
							<tr>
								<td>{$lang.content.upload_file}:</td>
							</tr>
							<tr>
								<td>
									<div class="fileinputs">
										<input type="file" name="photo" id="photo" class="file" onchange="document.getElementById('file_text_photo').innerHTML = this.value.replace(/.*\\(.*)/, '$1');document.getElementById('file_img_photo').innerHTML = ChangeIcon(this.value.replace(/.*\.(.*)/, '$1'));"/>
										<div class="fakefile">
											<table cellpadding="0" cellspacing="0">
											<tr>												
												<td>	
													<input type="button"  class="btn_upload" value="{$lang.buttons.choose}">
												</td>			
												<td style="padding-left:10px;">
													<span id='file_img_photo'></span>
												</td>								
												<td style="padding-left:4px;">
													<span id='file_text_photo'></span>	
												</td>
											</tr>
											</table>
										</div>
									</div>
								</td>
							</tr>
							<tr>
								<td>{$lang.content.comments}:</td>
							</tr>
							<tr>
								<td><textarea name="upload_comment" id="upload_comment" cols="40" rows="5"></textarea></td>
							</tr>							
						</table>
						</td>
						<td valign="top" style="padding-left: 10px;">
							{$lang.content.photo_max_size}:&nbsp;{$data.size}&nbsp;{$lang.content.kb}.<br><br>
							{$lang.content.photo_extensions}:&nbsp;
							{foreach from=$data.photo_extensions item=ext name=photo_extensions}
							{$ext|upper}{if !$smarty.foreach.photo_extensions.last},{else}.{/if}
							{/foreach}<br><br>
							{if $data.use_resize eq 1}
								<div>{$lang.content.max_height}:&nbsp;{$data.width}</div>
								<div>{$lang.content.max_width}:&nbsp;{$data.height}</div>
							{/if}
							{$lang.content.upload_photo_limitations}
						</td>
					</tr>
					<tr>
						<td colspan="2">
							<table cellpadding="0" cellspacing="0">
								<tr>
									<td><input type="button" class="btn_small" value="{$lang.buttons.upload}" onclick=" document.getElementById('result_photo').innerHTML='<img src=\'{$site_root}{$template_root}/images/indicator.gif\' style=\'height:16px;\'>'; jsLoad(this.form.photo, 'result_photo','{$id_ad}', 'f', document.getElementById('upload_comment').value, 'user_upload_photo', '{$choise}', 'photo', 'file_text_photo', 'file_img_photo');"></td>
									<td style="padding-left:10px;">
										<div id="result_photo" style="color:red;"></div>
									</td>
								</tr>
							</table>						
						</td>
					</tr>
					</form>
					<tr><td colspan="2"><hr class="listing"></td></tr>
										
				{/if}
				</table>
			</td>
		</tr>
	</table>
	</form>
</TD></TR>
{elseif $form.sel eq 'step_5'}
	<!--//STEP 5-->
<TR><TD>
<form method="POST" name="step_5" id="step_5" action="" enctype="multipart/form-data">
<input name="choise" type="hidden" value="{$choise}">
<input name="id_ad" type="hidden" value="{$id_ad}">
	<table cellpadding="0" cellspacing="0" border="0" width="100%">
		<tr valign="top">
			<td class="subsection_title"><b>{if ($choise eq '1' || $choise eq '3')}{$lang.content.page_header_step_5}{else}{$lang.content.page_header_step_5_have} ({if $choise eq 2}{$lang.content.about_leaser}{else if $choise eq 4}{$lang.content.about_buyer}{/if}){/if}</b></td>
		</tr>
	</table>
	<table cellpadding="0" cellspacing="0" border="0" width="100%">
		<tr>
			<td>
			<table cellpadding="5" cellspacing="0" border="0" width="100%">
			{if ($choise eq '1' || $choise eq '3')}
			<!-- need/buy  -->
				<tr>
					<td colspan="2">{$lang.content.photo_word}</td>
				</tr>
				<tr>
						<td colspan="2">{$lang.content.photo_max_count}:&nbsp;{$data.limit}</td>
					</tr>
					<tr>
						<td colspan="2">
						{if $upload}
							<table cellpadding="0" cellspacing="0" border="0" width="100%">
							{section name=f loop=$upload}
							<tr>
							<td>{$smarty.section.f.iteration}.</td>							
							<td>
								{if !$smarty.section.f.first}<a href="{$file_name}?id_ad={$id_ad}&file_id={$upload[f].id}&sel=file_up&file_type=photo&from_sel={$form.sel}" class="sorter">&uarr;</a>{/if}
								{if !$smarty.section.f.last}<a href="{$file_name}?id_ad={$id_ad}&file_id={$upload[f].id}&sel=file_down&file_type=photo&from_sel={$form.sel}" class="sorter">&darr;</a>{/if}
							</td>							
							<td height="{$thumb_height+10}" width="{$thumb_width+10}" valign="middle" align="center" style=" border: 1px solid #cccccc; ">
								{if $upload[f].view_link}
									<a href="{$upload[f].file}" rel="lightbox[photo]" title="{$upload[f].user_comment}">
								{/if}
								<img src='{$upload[f].thumb_file}' style="border: none;">{if $upload[f].view_link}</a>{/if}
							</td>
							<td valign="top" style="padding-left: 7px;">
								<table cellpadding="0" cellspacing="0" border="0" width="100%">
								<tr>
									<td><textarea name="edit_upload_comment[{$upload[f].id}]" cols="40" rows="5">{$upload[f].user_comment}</textarea></td>
									<td valign="top" align="right">
										{strip}
										{if $data.use_photo_approve}
											{if $upload[f].admin_approve == 0}
												<font class="error">{$lang.content.admin_approve_not_complete}</font>
											{elseif $upload[f].admin_approve == 1}
												<!--{$lang.content.admin_approve_acepted}-->
											{elseif $upload[f].admin_approve == 2}
												<font class="error">{$lang.content.admin_approve_decline}</font>
											{/if}
										{/if}
										{/strip}
									</td>
								</tr>
								<tr>
									<td style="padding-top: 5px;" colspan="2"><a href="#" onclick="javascript: document.step_5.action='{$upload[f].edit_comment_link}'; document.step_5.submit();">{$lang.buttons.save}</a>&nbsp;&nbsp;|&nbsp;&nbsp;{if $upload[f].status == 1}<a href="#" onclick="javascript: document.step_5.action='{$upload[f].deactivate_link}'; document.step_5.submit();">{$lang.content.deactivate_file}</a>{else}<a href="#" onclick="javascript: document.step_5.action='{$upload[f].activate_link}'; document.step_5.submit();">{$lang.content.activate_file}</a>{/if}&nbsp;&nbsp;|&nbsp;&nbsp;<a href="#" onclick="javascript: document.step_5.action='{$upload[f].del_link}'; document.step_5.submit();" class="action_link">{$lang.content.delete}</a></td>
								</tr>
								</table>
							</td>
							</tr>
							{if !($smarty.section.f.last && $upload_count == $data.limit)}
							<tr><td colspan="5"><hr class="listing"></td></tr>
							{/if}
							{/section}
							</table>
						{else}
						<font class="error">{$lang.content.no_photos}</font>
						{/if}
						</td>
					</tr>
					{if $upload_count < $data.limit}
					<tr>
						<td width="20%">
						<table cellpadding="0" cellspacing="0" border="0" class="upload_file">
							<tr>
								<td>{$lang.content.upload_file}:</td>
							</tr>
							<tr>
								<td>
									<div class="fileinputs">
										<input type="file" name="photo" id="photo" class="file" onchange="document.getElementById('file_text_photo').innerHTML = this.value.replace(/.*\\(.*)/, '$1');document.getElementById('file_img_photo').innerHTML = ChangeIcon(this.value.replace(/.*\.(.*)/, '$1'));"/>
										<div class="fakefile">
											<table cellpadding="0" cellspacing="0">
											<tr>												
												<td>	
													<input type="button"  class="btn_upload" value="{$lang.buttons.choose}">
												</td>			
												<td style="padding-left:10px;">
													<span id='file_img_photo'></span>
												</td>								
												<td style="padding-left:4px;">
													<span id='file_text_photo'></span>	
												</td>
											</tr>
											</table>
										</div>
									</div>
								</td>
							</tr>
							<tr>
								<td>{$lang.content.comments}:</td>
							</tr>
							<tr>
								<td><textarea name="upload_comment" id="upload_comment" cols="40" rows="5"></textarea></td>
							</tr>
							<tr>
								<td><input type="button" class="btn_small" value="{$lang.buttons.upload}" onclick="javascript: document.step_5.action='{$file_name}?sel=upload_photo&back=step_5'; document.step_5.submit();"></td>
							</tr>
						</table>
						</td>
						<td valign="top" style="padding-left: 10px;">
							{$lang.content.photo_max_size}:&nbsp;{$data.size}&nbsp;{$lang.content.kb}.<br><br>
							{$lang.content.photo_extensions}:&nbsp;
							{foreach from=$data.photo_extensions item=ext name=photo_extensions}
							{$ext|upper}{if !$smarty.foreach.photo_extensions.last},{else}.{/if}
							{/foreach}<br><br>
							{if $data.use_resize eq 1}
								<div>{$lang.content.max_height}:&nbsp;{$data.width}</div>
								<div>{$lang.content.max_width}:&nbsp;{$data.height}</div>
							{/if}
							{$lang.content.upload_photo_limitations}
						</td>
					</tr>
					<tr><td colspan="2"><hr class="listing"></td></tr>
					{/if}									
			{else}
			<!-- have/sell  -->
				<tr>
					<td width="110" valign="top" style="padding-top: 10px;">{$lang.content.age}:&nbsp;</td>
					<td>
						<select name="age_1" id="age_1" style="width:40px" onchange="CheckAge(this);">
						{foreach item=item from=$age}
							<option value="{$item}" {if $age_sel.age_1 eq $item} selected {/if}>{$item}</option>
						{/foreach}
						</select>
						&nbsp;{$lang.content.to}&nbsp;
						<select name="age_2" id="age_2" style="width:40px" onchange="CheckAge(this);">
						{foreach item=item from=$age}
							<option value="{$item}" {if $age_sel.age_2 eq $item} selected {/if}>{$item}</option>
						{/foreach}
						</select>
						<span name="age_error_div" class="error" id="age_error_div" style="display: none;">&nbsp;&nbsp;{$lang.content.incorrect_field}</span>
					</td>
				</tr>
				{section name=f loop=$gender_match}
				{if $gender_match[f].visible_in ne 3}<!--visibility checking-->
					<tr>
						<td width="110" valign="top" style="padding-top: 10px;" bgcolor="#eeeff3">{$gender_match[f].name}:&nbsp;<input type=hidden name="spr_gender_match[{$gender_match[f].num}]" value="{$gender_match[f].id}"></td>
						<td align="left" bgcolor="#eeeff3">
							<table cellpadding="2" cellspacing="0" border="0">
							{section name=s loop=$gender_match[f].opt}
							{if $smarty.section.s.index is div by 4}<tr>{/if}
							<td width="15" height="30"><input type="checkbox" name="gender_match[{$gender_match[f].num}][]" value="{$gender_match[f].opt[s].value}"  {if $gender_match[f].opt[s].sel} checked {/if}></td>
							<td>{$gender_match[f].opt[s].name}</td>
							{if $smarty.section.s.index_next is div by 4 || $smarty.section.s.last}</tr>{/if}
							{/section}
							</table>
						</td>
					</tr>
					<tr>
						<td colspan="2" style="padding-left: 5px;" bgcolor="#eeeff3">
							<table cellpadding="0" cellspacing="0">
								<tr>
									<td><span class="blue_link" onclick="javascript: SelAll('gender_match',{$smarty.section.f.index}, 'step_5');">{$lang.content.sel_all_text}</span></td>
									<td style="padding-left: 10px;"><span class="blue_link" onclick="UnSelAll('gender_match',{$smarty.section.f.index}, 'step_5');">{$lang.content.unsel_all_text}</span></td>
								</tr>
							</table>
						</td>
					</tr>
				{/if}
				{/section}
				{section name=p loop=$people_match}
				{if $people_match[p].visible_in ne 3}<!--visibility checking-->
					<tr>
						<td width="110" valign="top" style="padding-top: 10px;" {if $smarty.section.p.index is not div by 2}bgcolor="#eeeff3"{/if}>{$people_match[p].name}:&nbsp;<input type=hidden name="spr_people_match[{$people_match[p].num}]" value="{$people_match[p].id}"></td>
						<td align="left" {if $smarty.section.p.index is not div by 2}bgcolor="#eeeff3"{/if}>
							<table cellpadding="2" cellspacing="0" border="0">
							{section name=s loop=$people_match[p].opt}
							{if $smarty.section.s.index is div by 4}<tr>{/if}
							<td width="15" height="30"><input type="checkbox" name="people_match[{$people_match[p].num}][]" value="{$people_match[p].opt[s].value}"  {if $people_match[p].opt[s].sel} checked {/if}></td>
							<td width="120">{$people_match[p].opt[s].name}</td>
							{if $smarty.section.s.index_next is div by 4 || $smarty.section.s.last}</tr>{/if}
							{/section}
							</table>
						</td>
					</tr>
					<tr>
						<td colspan="2" style="padding-left: 5px;" {if $smarty.section.p.index is not div by 2}bgcolor="#eeeff3"{/if}>
							<table cellpadding="0" cellspacing="0">
								<tr>
									<td><span class="blue_link" onclick="javascript: SelAll('people_match',{$smarty.section.p.index}, 'step_5');">{$lang.content.sel_all_text}</span></td>
									<td style="padding-left: 10px;"><span class="blue_link" onclick="UnSelAll('people_match',{$smarty.section.p.index}, 'step_5');">{$lang.content.unsel_all_text}</span></td>
								</tr>
							</table>
						</td>
					</tr>
				{/if}
				{/section}
				{section name=f loop=$language_match}
				{if $language_match[f].visible_in ne 3}<!--visibility checking-->
					<tr>
						<td width="110" valign="top" style="padding-top: 10px;" {if $smarty.section.p.total is not div by 2}bgcolor="#eeeff3"{/if}>{$language_match[f].name}:&nbsp;<input type=hidden name="spr_language_match[{$language_match[f].num}]" value="{$language_match[f].id}"></td>
						<td align="left" {if $smarty.section.p.total is not div by 2}bgcolor="#eeeff3"{/if}>
							<table cellpadding="2" cellspacing="0" border="0">
							{section name=s loop=$language_match[f].opt}
							{if $smarty.section.s.index is div by 4}<tr>{/if}
							<td width="15" height="30"><input type="checkbox" name="language_match[{$language_match[f].num}][]" value="{$language_match[f].opt[s].value}"  {if $language_match[f].opt[s].sel} checked {/if}></td>
							<td width="120">{$language_match[f].opt[s].name}</td>
							{if $smarty.section.s.index_next is div by 4 || $smarty.section.s.last}</tr>{/if}
							{/section}
							</table>
						</td>
					</tr>
					<tr>
						<td colspan="2" style="padding-left: 5px;" {if $smarty.section.p.index is not div by 2}bgcolor="#eeeff3"{/if}>
							<table cellpadding="0" cellspacing="0">
								<tr>
									<td><span class="blue_link" onclick="javascript: SelAll('language_match',{$smarty.section.f.index}, 'step_5');">{$lang.content.sel_all_text}</span></td>
									<td style="padding-left: 5px;"><span class="blue_link" onclick="UnSelAll('language_match',{$smarty.section.f.index}, 'step_5');">{$lang.content.unsel_all_text}</span></td>
								</tr>
							</table>
						</td>
					</tr>
				{/if}
				{/section}
				<tr>
					<td colspan="2" align="left">
						<input type="button" id="next_btn" value="{$lang.buttons.save}" class="button_3" onclick="javascript: document.step_5.action='{$form.next_link}'; document.step_5.submit();">
					</td>
				</tr>
			{/if}
			</table>
			</td>
		</tr>
	</table>
	</form>
</TD></TR>
	{elseif $form.sel eq 'step_6'}
<TR><TD>
	<!--//STEP 6-->
	<form method="POST" name="step_6" id="step_6" action="">
	<input name="choise" type="hidden" value="{$choise}">
	<input name="id_ad" type="hidden" value="{$id_ad}">
	<table cellpadding="0" cellspacing="0" border="0" width="100%">
		<tr valign="top">
			<td class="subsection_title"><b>{if ($choise eq '1' || $choise eq '1')}{$lang.content.page_header_step_6}{else}{$lang.content.page_header_step_6_have}{/if}</b></td>
		</tr>
	</table>
	<table cellpadding="0" cellspacing="0" border="0" width="100%">
		<tr>
			<td>
			<table cellpadding="5" cellspacing="0" border="0" width="100%">
				<tr>
					<td style="padding-top: 10px;">{$lang.content.comments}:</td>
				</tr>	
				<tr>
					<td><textarea name="comments" style="width: 200px; height: 75px;">{$comments}</textarea></td>
				</tr>
				<tr>
					<td>
						<input type="button" value="{$lang.buttons.save}" class="button_3" onclick="javascript: document.step_6.action='{$form.next_link}'; document.step_6.submit();">
					</td>
				</tr>
			</table>
			</td>
		</tr>
	</table>
	</form>
</TD></TR>
{elseif $form.sel eq 'step_7'}
<TR><TD>
	<!--//STEP 7-->
	<form method="POST" name="step_7" id="step_7" enctype="multipart/form-data">
	<input name="choise" type="hidden" value="{$choise}">
	<input name="id_ad" type="hidden" value="{$id_ad}">
	<table cellpadding="0" cellspacing="0" border="0" width="100%">
		<tr>
			<td class="subsection_title">{$lang.content.planirovka_header}</td>
		</tr>
	</table>
	<table cellpadding="0" cellspacing="0" border="0" width="100%">
		<tr>
			<td valign="top">
				<table cellpadding="5" cellspacing="0" border="0" width="100%">
				<tr>
					<td colspan="2">{$lang.content.photo_word}</td>
				</tr>
				<tr>
					<td colspan="2">{$lang.content.photo_max_count}:&nbsp;{$data.limit}</td>
				</tr>
				<tr>
					<td colspan="2">
					<div id="user_upload_plan">
						{include file="$gentemplates/user_upload.tpl"}	
					</div>
					</td>
				</tr>
					<form method="POST" enctype="multipart/form-data" onsubmit="return false;">
					<tr>
						<td width="20%">
						<table cellpadding="0" cellspacing="0" border="0" class="upload_file">
							<tr>
								<td>{$lang.content.upload_file}:</td>
							</tr>
							<tr>
								<td>
									<div class="fileinputs">
										<input type="file" name="plan" id="plan" class="file" onchange="document.getElementById('file_text_plan').innerHTML = this.value.replace(/.*\\(.*)/, '$1');document.getElementById('file_img_plan').innerHTML = ChangeIcon(this.value.replace(/.*\.(.*)/, '$1'));"/>
										<div class="fakefile">
											<table cellpadding="0" cellspacing="0">
											<tr>												
												<td>	
													<input type="button"  class="btn_upload" value="{$lang.buttons.choose}">
												</td>			
												<td style="padding-left:10px;">
													<span id='file_img_plan'></span>
												</td>								
												<td style="padding-left:4px;">
													<span id='file_text_plan'></span>	
												</td>
											</tr>
											</table>
										</div>
									</div>
								</td>
							</tr>
							<tr>
								<td>{$lang.content.comments}:</td>
							</tr>
							<tr>
								<td><textarea name="upload_comment" id="upload_comment" cols="40" rows="5"></textarea></td>
							</tr>
							
						</table>
						</td>
						<td valign="top" style="padding-left: 10px;">
							{$lang.content.photo_max_size}:&nbsp;{$data.size}&nbsp;{$lang.content.kb}.<br><br>
							{$lang.content.photo_extensions}:&nbsp;
							{foreach from=$data.photo_extensions item=ext name=photo_extensions}
							{$ext|upper}{if !$smarty.foreach.photo_extensions.last},{else}.{/if}
							{/foreach}<br><br>
							{if $data.use_resize eq 1}
								<div>{$lang.content.max_height}:&nbsp;{$data.width}</div>
								<div>{$lang.content.max_width}:&nbsp;{$data.height}</div>
							{/if}
							{$lang.content.upload_photo_limitations}
							{if $data.use_photo_approve}
							{$lang.content.use_photo_approve}
							{/if}
						</td>
					</tr>
					<tr>
						<td colspan="2">
							<table cellpadding="0" cellspacing="0">
								<tr>
									<td><input type="button" class="btn_small" value="{$lang.buttons.upload}" onclick=" document.getElementById('result_plan').innerHTML='<img src=\'{$site_root}{$template_root}/images/indicator.gif\' style=\'height:16px;\'>'; jsLoad(this.form.plan, 'result_plan','{$id_ad}', 'plan', document.getElementById('upload_comment').value, 'user_upload_plan', '{$choise}', 'plan', 'file_text_plan', 'file_img_plan');"></td>
									<td style="padding-left:10px;">
										<div id="result_plan" style="color:red;"></div>
									</td>
								</tr>
							</table>						
						</td>
					</tr>
					</form>
					<tr><td colspan="2"><hr class="listing"></td></tr>
				
			</table>
			</td>
		</tr>
	</table>
	</form>
</TD></TR>
{elseif $form.sel eq 'step_8'}
<TR><TD>
	<!--//STEP 6-->
	<form method="POST" name="step_8" id="step_8" action="">
	<input name="choise" type="hidden" value="{$choise}">
	<input name="id_ad" type="hidden" value="{$id_ad}">
	<table cellpadding="0" cellspacing="0" border="0" width="100%">
		<tr valign="top">
			<td class="subsection_title"><b>{if ($choise eq '1' || $choise eq '1')}{$lang.content.page_header_step_8}{else}{$lang.content.page_header_step_8_have}{/if}</b></td>
		</tr>
	</table>
	<table cellpadding="0" cellspacing="0" border="0" width="100%">
		<tr>
			<td>
			<table cellpadding="5" cellspacing="0" border="0" width="100%">
				<tr>
					<td style="padding-top: 10px;">{$lang.content.headline}:</td>
				</tr>	
				<tr>
					<td><textarea name="headline" style="width: 200px; height: 75px;">{$headline}</textarea></td>
				</tr>
				<tr>
					<td>
						<input type="button" value="{$lang.buttons.save}" class="button_3" onclick="javascript: document.step_8.action='{$form.next_link}'; document.step_8.submit();">
					</td>
				</tr>
			</table>
			</td>
		</tr>
	</table>
	</form>
</TD></TR>
{elseif $form.sel eq 'step_type'}
<TR><TD>
	<!--//STEP 6-->
	<form method="POST" name="step_type" id="step_type" action="">
	<input name="choise" type="hidden" value="{$choise}">
	<input name="id_ad" type="hidden" value="{$id_ad}">
	<table cellpadding="0" cellspacing="0" border="0" width="100%">
		<tr valign="top">
			<td class="subsection_title"><b>{$lang.content.page_header_step_type}</b></td>
		</tr>
	</table>
	<table cellpadding="0" cellspacing="0" border="0" width="100%">
		<tr>
			<td>
			<table cellpadding="5" cellspacing="0" border="0" width="100%">
				<tr>
					<td style="padding-top: 10px;">
              <input type="radio" name="offer_type" onclick="hideParents();" value="parent" {if !$parent_id} checked="checked" {/if} />{$lang.content.type_parent}<br />
              <input type="radio" name="offer_type" value="child" onclick="showParents();" {if $parent_id} checked="checked" {/if} {if !$parents_available} disabled="disabled" {/if}  />{$lang.content.type_child}<br />
              {if $parents_available}
              <div id="parent_id"  id="parent_id" {if !$parent_id} style="display: none;"{/if}>
                <span>{$lang.content.parent_choose}: </span>
                <select name="parent_id">
                  {foreach from=$parents_available item=item}
                    <option value="{$item.id}" {if $item.id eq $parent_id} selected="selected" {/if}>{$item.headline}</option>
                  {/foreach}
                </select>
              </div>
              {/if}
              <input type="hidden" name="is_parent" id="is_parent" value="1" />
          </td>
				</tr>	
				<tr>
					<td>
						<input type="button" value="{$lang.buttons.save}" class="button_3" onclick="javascript: document.step_type.action='{$form.next_link}'; document.step_type.submit();">
					</td>
				</tr>
			</table>
			</td>
		</tr>
	</table>
	</form>
</TD></TR>
	
	{elseif $form.sel eq 'upload_video'}
<TR><TD>
	<!--//UPLOAD VIDEO-->
	<form method="POST" name="upload_video" id="upload_video" action="" enctype="multipart/form-data">
	<input name="choise" type="hidden" value="{$choise}">
	<input name="id_ad" type="hidden" value="{$id_ad}">
	<table cellpadding="0" cellspacing="0" border="0" width="100%">
		<tr valign="top">
			<td class="subsection_title"><b>{$lang.content.page_header_step_upload_video}</b></td>
		</tr>
	</table>
	<table cellpadding="0" cellspacing="0" border="0" width="100%">
		<tr>
			<td>
			<table cellpadding="5" cellspacing="0" border="0" width="100%">
					<tr>
						<td colspan="2">{$lang.content.video_word}</td>
					</tr>
					<tr>
						<td colspan="2">{$lang.content.photo_max_count}:&nbsp;{$data.limit}</td>
					</tr>
					<tr>
						<td colspan="2">
						<div id="user_upload_video">						
							{include file="$gentemplates/user_upload.tpl"}	
						</div>	
						</td>
					</tr>
					<form method="POST" enctype="multipart/form-data" onsubmit="return false;">
					<tr>
						<td width="20%">
						<table cellpadding="0" cellspacing="0" border="0" class="upload_file">
							<tr>
								<td>{$lang.content.upload_file}:</td>
							</tr>
							<tr>
								<td>
									<div class="fileinputs">
										<input type="file" name="video" id="video" class="file" onchange="document.getElementById('file_text_video').innerHTML = this.value.replace(/.*\\(.*)/, '$1');document.getElementById('file_img_video').innerHTML = ChangeIcon(this.value.replace(/.*\.(.*)/, '$1'));"/>
										<div class="fakefile">
											<table cellpadding="0" cellspacing="0">
											<tr>												
												<td>	
													<input type="button"  class="btn_upload" value="{$lang.buttons.choose}">
												</td>			
												<td style="padding-left:10px;">
													<span id='file_img_video'></span>
												</td>								
												<td style="padding-left:4px;">
													<span id='file_text_video'></span>	
												</td>
											</tr>
											</table>
										</div>
									</div>
								</td>
							</tr>
							<tr>
								<td>{$lang.content.comments}:</td>
							</tr>
							<tr>
								<td><textarea name="upload_comment" id="upload_comment" cols="40" rows="5"></textarea></td>
							</tr>
							
						</table>
						</td>
						<td valign="top" style="padding-left: 10px;">
							{$lang.content.photo_max_size}:&nbsp;{$data.size}&nbsp;{$lang.content.kb}.<br><br>
							{$lang.content.video_extensions}:&nbsp;
							{foreach from=$data.video_extensions item=ext name=video_extensions}
							{$ext|upper}{if !$smarty.foreach.video_extensions.last},{else}.{/if}
							{/foreach}<br><br>
							{$lang.content.upload_video_limitations}
						</td>
					</tr>
					<tr>
						<td colspan="2">
							<table cellpadding="0" cellspacing="0">
								<tr>
									<td><input type="button" class="btn_small" value="{$lang.buttons.upload}" onclick=" document.getElementById('result_video').innerHTML='<img src=\'{$site_root}{$template_root}/images/indicator.gif\' style=\'height:16px;\'>'; jsLoad(this.form.video, 'result_video','{$id_ad}', 'v', document.getElementById('upload_comment').value, 'user_upload_video', '{$choise}', 'video', 'file_text_video', 'file_img_video');"></td>
									<td style="padding-left:10px;">
										<div id="result_video" style="color:red;"></div>
									</td>
								</tr>
							</table>						
						</td>
					</tr>
					</form>
					<tr><td colspan="2"><hr class="listing"></td></tr>
					
			</table>
			</td>
		</tr>
	</table>
	</form>
</TD></TR>
{elseif $form.sel eq 'listing_position'}
<TR><TD valign="top">
	<table cellpadding="0" cellspacing="0" border="0" width="100%">
		<tr valign="top">
			<td class="subsection_title"><b>{$lang.content.search_position}</b></td>
		</tr>
	</table>	
	{strip}
	<table cellpadding="5" cellspacing="0" border="0">	
		<tr>
			<td>
			{$lang.content.ad_in_search}:&nbsp;&nbsp;
			{if $use_ads_activity_period && ($profile.sold_leased_status != 1)}
				{if $profile.status eq 1}{$lang.content.active_2}&nbsp;{$profile.date_unactive}{else}{$lang.content.inactive}{/if}&nbsp;&nbsp;|&nbsp;&nbsp;
				{if $profile.status eq 1}
					<a href="{$file_name}?sel=deactivate_ad&amp;id_ad={$profile.id}">{$lang.content.deactivate}</a>
				{else}
					{if $private_person_ads_limit && ($user_ads_cnt > $private_person_ads_limit)}
						<a href="#" onclick="alert('{$lang.content.ads_limit_activate_alert}');">{$lang.content.activate_2}&nbsp;{$ads_activity_period}&nbsp;{$lang.content.activate_2_days}</a>
					{else}
						{if $profile.act_status neq 0}
							<a href="{$file_name}?sel=activate_ad&amp;id_ad={$profile.id}">{$lang.content.activate_2}&nbsp;{$ads_activity_period}&nbsp;{$lang.content.activate_2_days}</a>
						{else}
							<a href="#" onclick="alert('{$lang.content.activate_alert}');">{$lang.content.activate_2}&nbsp;{$ads_activity_period}&nbsp;{$lang.content.activate_2_days}</a>
						{/if}
					{/if}
				{/if}
									
			{else}	
				{if $profile.status eq 1}{$lang.content.active}{else}{$lang.content.inactive}{/if}&nbsp;&nbsp;|&nbsp;&nbsp;
				{if $profile.status eq 1}
					<a href="{$file_name}?sel=deactivate_ad&amp;id_ad={$profile.id}">{$lang.content.deactivate}</a>
				{else}
					{if $private_person_ads_limit && ($user_ads_cnt > $private_person_ads_limit)}
						<a href="#" onclick="alert('{$lang.content.ads_limit_activate_alert}');">{$lang.content.activate}</a>
					{else}
						{if $profile.act_status neq 0}
							<a href="{$file_name}?sel=activate_ad&amp;id_ad={$profile.id}">{$lang.content.activate}</a>
						{else}
							<a href="#" onclick="alert('{$lang.content.activate_alert}');">{$lang.content.activate}</a>
						{/if}
					{/if}
				{/if}				
			{/if}			
			{if $profile.status eq 1 && (($profile.type eq '2' || $profile.type eq '4') && $use_sold_leased_status)}			
			&nbsp;&nbsp;|&nbsp;&nbsp;
				{if $profile.sold_leased_status eq 1}
					{$lang.content.your_ad_status_yes}&nbsp;					
					{if $profile.type eq '4'}
						&quot;{$lang.content.your_ad_status_sold}&quot;
					{else}
						&quot;{$lang.content.your_ad_status_leased}&quot;
					{/if}
					&nbsp;&nbsp;|&nbsp;&nbsp;			
				{/if}	
				{if $profile.sold_leased_status eq 1}
					<a href="{$file_name}?sel=de_sold_leased&amp;id_ad={$profile.id}">
					{$lang.content.your_ad_status_do_no}&nbsp;					
				{else}
					<a href="{$file_name}?sel=sold_leased&amp;id_ad={$profile.id}">
					{$lang.content.your_ad_status_do_yes}&nbsp;					
				{/if}
				{if $profile.type eq '4'}
					&quot;{$lang.content.your_ad_status_sold}&quot;
				{else}
					&quot;{$lang.content.your_ad_status_leased}&quot;
				{/if}				
				</a>					
			{/if}				
			</td>
		</tr>
		<tr>
			<td>{$lang.content.mark_ad}:&nbsp;&nbsp;
				<a href="{$server}{$site_root}/admin/admin_rentals.php?sel=top_search_ad&type=rent&id_ad={$profile.id}">{$lang.content.link_text_1}</a>&nbsp;&nbsp;|&nbsp;&nbsp;
				<a href="{$server}{$site_root}/admin/admin_rentals.php?sel=slideshow_ad&type=rent&id_ad={$profile.id}">{$lang.content.link_text_2}</a>&nbsp;&nbsp;|&nbsp;&nbsp;
				<a href="#" onclick="ShowFeature('feature_{$profile.id}')">{$lang.content.link_text_3}</a>			
			</td>
		</tr>			
		<tr id="feature_{$profile.id}" {if $sub_sel != "make_featured"}style="display:none;"{/if}>
			<td>
				<table cellpadding="0" cellspacing="0" border="0" style="margin-top: 10px;">
					<tr>
						{if $featured_rent}
						<td valign="top" style="border-right: 1px solid #000000; padding-right: 10px;">
							{assign var="view_from_admin" value="1"}
							{include file="$gentemplates/featured_left.tpl"}
						</td>
						{/if}
						<td valign="top" {if $featured_rent}style="padding-left: 20px;"{/if}>
							<form method="POST" action="{$file_name}" id="feature_form_{$profile.id}" name="feature_form_{$profile.id}">
							<input type="hidden" id="sel" name="sel" value="feature_ad">
							<input type="hidden" id="id_ad" name="id_ad" value="{$profile.id}">
							<input type="hidden" id="type" name="type" value="rent">
							<input type="hidden" id="id_region" name="id_region" value="{$profile.id_region}">
							<table cellpadding="3" cellspacing="0" border="0">
							<tr>
								<td>{$lang.content.featured_text1}</td>
							</tr>
							<tr>
								<td align="left">
									<textarea cols="55" rows="3" id="feature_headline" name="feature_headline"></textarea><br><br>
									{$cur_symbol}&nbsp;<input type="text" id="curr_value" name="curr_value" size="5">
									&nbsp;<input type="button" value="{$lang.content.feature_btn_text}" class="button_3" onclick="javascript: document.feature_form_{$profile.id}.submit(); ">
								</td>
							</tr>
							</table>
							</form>				
						</td>
					</tr>
				</table>				
			</td>
		</tr>
		<!--<tr>
			<td>
				<a href="{$file_name}?sel=list_ads">{$lang.content.back_to_ads_list}</a>
			</td>
		</tr>-->
	</table>
	{/strip}
	</TD>
</TR>	
{/if}
{if $id_ad}
<tr>
	<td style="padding-top: 10px;">
		<input type="button" class="btn_small" value="{$lang.content.back_to_edit}" onclick="document.location.href='{$file_name}?sel=my_ad&id_ad={$id_ad}';">
	</td>
</tr>
{/if}
</TABLE>
{literal}
<script>
function CheckStep(step) {
	err_cnt = 0;

	if (step == "step_3") {
		/**
		 *	realty_type - обязательное поле
		 **/
		if (document.getElementById("realty_type0").value == ""){
			document.getElementById('realty_type_error').style.display = 'inline';
			err_cnt++;
		} else {
			document.getElementById('realty_type_error').style.display = 'none';
		}

		choise = document.getElementById("choise").value;
		if (choise == 1 || choise == 3) {
			if (CheckRangeIntegerFields(step) != true) {
				err_cnt++;
			}
			if (CheckIntegerFields(step, choise) != true) {
				err_cnt++;
			}
		} else if (choise == 2 || choise == 4) {
			if (CheckIntegerFields(step, choise) != true) {
				err_cnt++;
			}
		}
	}
	if (err_cnt == 0) {
		return true;
	} else {
		return false;
	}
}

function CheckRangeIntegerFields( step ){
	if (step == "step_3") {
		var id_arr = new Array('payment', 'year_build', 'deposit', 'live_square', 'total_square', 'land_square', 'floor');
		var reg_expr = new Array();
		reg_expr['payment'] = '^[1-9]+[0-9]*$';
		reg_expr['year_build'] = '^[0-9]{4}$';
		reg_expr['deposit'] = '^[0-9]*$';
		reg_expr['live_square'] = '^[0-9]{0,6}$';
		reg_expr['total_square'] = '^[0-9]{0,6}$';
		reg_expr['land_square'] = '^[0-9]{0,6}$';
		reg_expr['floor'] = '^[0-9]{0,3}$';

		id_arr_cnt = id_arr.length;
		var error_cnt = 0;

		for (i = 0; i < id_arr_cnt; i++) {
			min_name = "min_" + id_arr[i];
			max_name = "max_" + id_arr[i];
			min_value = document.getElementById(min_name).value;
			max_value = document.getElementById(max_name).value;


			if (id_arr[i] == 'payment') {
				/* mandatory field */
				if (min_value.search(reg_expr[id_arr[i]]) ==-1) {
					document.getElementById(min_name + '_error').style.display = '';
					error_cnt++;
				} else {
					document.getElementById(min_name + '_error').style.display = 'none';
				}

				if (max_value.search(reg_expr[id_arr[i]]) ==-1) {
					document.getElementById(max_name + '_error').style.display = '';
					error_cnt++;
				} else {
					document.getElementById(max_name + '_error').style.display = 'none';
				}

				if ((isNaN(parseInt(max_value))) || parseInt(min_value) >= parseInt(max_value)) {
					document.getElementById('bad_' + id_arr[i]+ '_error').style.display = '';
					error_cnt++;
				} else {
					document.getElementById('bad_' + id_arr[i]+ '_error').style.display = 'none';
				}

			} else if ((min_value != ""  && min_value != 0) || (max_value != "" && max_value != 0)) {
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
}

function CheckIntegerFields( step, choise ){
	if (step == "step_3") {
		if (choise == 1 || choise == 3) {
			var id_arr = new Array('floor_num', 'subway_min');
			var reg_expr = new Array();
			reg_expr['subway_min'] = '^[0-9]{0,4}$';
			reg_expr['floor_num'] = '^[0-9]{0,3}$';

		} else if (choise == 2 || choise == 4) {
			var id_arr = new Array('min_payment', 'min_year_build', 'min_deposit', 'min_live_square', 'min_total_square', 'min_land_square', 'min_floor', 'floor_num', 'subway_min');
			var reg_expr = new Array();
			reg_expr['min_payment'] = '^[1-9]+[0-9]*$';
			reg_expr['min_year_build'] = '^[0-9]{4}$';
			reg_expr['min_deposit'] = '^[0-9]*$';
			reg_expr['min_live_square'] = '^[0-9]{0,6}$';
			reg_expr['min_total_square'] = '^[0-9]{0,6}$';
			reg_expr['min_land_square'] = '^[0-9]{0,6}$';
			reg_expr['min_floor'] = '^[0-9]{0,3}$';
			reg_expr['subway_min'] = '^[0-9]{0,4}$';
			reg_expr['floor_num'] = '^[0-9]{0,3}$';
		}

		id_arr_cnt = id_arr.length;
		var error_cnt = 0;
		for (i = 0; i < id_arr_cnt; i++) {
			name = id_arr[i];
			value = document.getElementById(name).value;

			if (name == "min_payment") {
				/* mandatory field */
				if (value.search(reg_expr[id_arr[i]]) ==-1) {
					document.getElementById(name + '_error').style.display = '';
					error_cnt++;
				} else {
					document.getElementById(name + '_error').style.display = 'none';
				}
			} else if (name == "min_year_build") {
				/* year build can not be bigger, then current year */
				if (value != "" && value != 0 && (value.search(reg_expr[id_arr[i]]) ==-1 || value > "{/literal}{$current_year}{literal}")) {
					document.getElementById(name + '_error').style.display = '';
					error_cnt++;
				} else {
					document.getElementById(name + '_error').style.display = 'none';
				}
			} else {
				if (value != "" && value != 0 && value.search(reg_expr[id_arr[i]]) ==-1) {
					document.getElementById(name + '_error').style.display = '';
					error_cnt++;
				} else {
					document.getElementById(name + '_error').style.display = 'none';
				}
			}
		}
		if (error_cnt == 0) {
			return true;
		} else {
			document.location = '#';
			return false;
		}
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

function CheckAge(obj) {
	if ( (obj.name=='age_1' || obj.name=='age_2') && ( document.getElementById('age_1').value > document.getElementById('age_2').value ) ){
		if (document.getElementById) {
			document.getElementById('age_error_div').style.display = '';
		} else if (document.all) {
			document.all['age_error_div'].style.display = '';
		}
		document.getElementById('next_btn').disabled = true;
		return false;
	} else {
		if (document.getElementById) {
			document.getElementById('age_error_div').style.display = 'none';
		} else if (document.all) {
			document.all['age_error_div'].style.display = 'none';
		}
		document.getElementById('next_btn').disabled = false;
	}
	return;
}

function Planirovka() {
	if (document.getElementById('planirovka').style.display == 'none'){
		document.getElementById('planirovka').style.display = 'inline';
	} else if (document.getElementById('planirovka').style.display == 'inline') {
		document.getElementById('planirovka').style.display = 'none';
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
function DivVision(id_div){
	if ( id_div == '1' ) {
		document.getElementById('user_type_copy').value = id_div;
		document.getElementById('agency_div').style.display = 'none';
		document.getElementById('user_type_err').style.display = 'none';
		document.getElementById('birth_div_1').style.display = 'inline';
	} else if ( id_div == '2' ) {
		document.getElementById('user_type_copy').value = id_div;
		document.getElementById('agency_div').style.display = 'inline';
		document.getElementById('user_type_err').style.display = 'none';
		document.getElementById('birth_div_1').style.display = 'none';
	}
	return;
}

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
function CheckAccountValues() {
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

function ShowFeature(id){
	element = document.getElementById(id).style.display;
	if(element == "none"){
		document.getElementById(id).style.display="block";
	} else {
		document.getElementById(id).style.display="none";
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

function UpdateUserUpload(act, id_ad, user_upload_id, choise, upload_type, upload_id) {
    	ajax();
    	
	    if (doc){
	    	switch (act){
	    		case "view":
	    			if (upload_type == 'f'){
			    		step = "step_4";
			    	}else if(upload_type == 'plan'){
			    		step = "step_7";
			    	}else if(upload_type == 'v'){
			    		step = "upload_video";
			    	}
	    			url = "admin_rentals.php?sel=" + step + "&ajax=1&id_ad=" + id_ad + "&choise=" + choise;
	    		break;
	    		case "file_up":
	    		case "file_down":
	    		case "upload_del":
	    		case "upload_deactivate":
	    		case "upload_activate":
	    			url = "admin_rentals.php?sel=" + act + "&ajax=1&id_ad=" + id_ad + "&file_id=" + upload_id + "&file_type=" + upload_type + "&choise=" + choise;
	    		break;
	    		case "edit_comment":
	    			upload_comment_id = 'edit_upload_comment_' + upload_id;
	    			var pattern = /\r\n|\r|\n/g;
					var new_pattern = document.getElementById(upload_comment_id).value.replace(pattern,"<br>");	    			
	    			url = "admin_rentals.php?sel=" + act + "&ajax=1&id_ad=" + id_ad + "&file_id=" + upload_id + "&file_type=" + upload_type + "&choise=" + choise + "&edit_upload_comment=" + new_pattern;
	    		break;
	    	}	    	
	    	
	       doc.open("GET", url, false);	       
	       doc.send(null);
	    	// Write the response to the div
	    	//destination.innerHTML = doc.responseText;
	    	if (doc.readyState == 4){	  
	    		document.getElementById(user_upload_id).innerHTML = doc.responseText;   
	    		if (act == 'edit_comment'){
	    			document.getElementById('save_status_'+upload_id).style.display = 'inline';
	    			document.getElementById('save_link_'+upload_id).style.display = 'none';
	    		}
	    	}
	    }
	    else{
	       alert ('Browser unable to create XMLHttp Object');
	    }
}
</script>
{/literal}

{if $form.sel eq 'step_1'}
	{literal}
	<script language="javascript">
	function Show(pos){
		document.getElementById('zip_code_div_1').style.display = '';
		document.getElementById('street_div_1').style.display = '';
		document.getElementById('adress_div_1').style.display = '';
		if (pos == 1 || pos == 3){
			document.getElementById('error_city').style.display = 'none';
		}else{
			document.getElementById('error_city').style.display = 'inline';
		}
		return true;
	}
	function Hide(pos){
		document.getElementById('zip_code_div_1').style.display = 'none';
		document.getElementById('street_div_1').style.display = 'none';
		document.getElementById('adress_div_1').style.display = 'none';
		if (pos == 1 || pos == 3){
			document.getElementById('error_city').style.display = 'none';
		}else{
			document.getElementById('error_city').style.display = 'inline';
		}
		return true;
	}
	var choise='{/literal}{$choise}{literal}';
	if (choise) {
		if (choise == 1 || choise == 3) {
			Hide(choise);
		} else if (choise == 2 || choise == 4) {
			Show(choise);
		}
	} else {
		Show(0);
	}
	</script>
	{/literal}
{/if}
	<!--END OF RENTAL CONTENT -->
	</td>
</tr>
</table>
{include file="$admingentemplates/admin_bottom.tpl"}