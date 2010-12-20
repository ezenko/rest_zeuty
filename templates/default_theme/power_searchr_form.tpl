{include file="$gentemplates/site_top.tpl"}
<table cellpadding="0" cellspacing="0" border="0">
<tr valign="top">
	<td class="left" valign="top">
		{include file="$gentemplates/search_menu.tpl"}
	</td>
	<td class="delimiter">&nbsp;</td>
	<td class="main">
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
			<tr>
				<td valign="top" class="header"><b>{$lang.headers.power_search}</b></td>
				{if $registered eq 1}<td align="right" style="padding-right: 10px;"><a href="quick_search.php">{$lang.content.link_text_1}</a></td>{/if}
			</tr>
			<tr valign="top">
				<td class="subheader" colspan="2"><b>{$lang.headers.search_criteria}</b></td>
			</tr>
		</table>
		<form method="POST" action="" name="power_search_form" id="power_search_form">
		<table cellpadding="0" cellspacing="0" width="100%" border="0">
			<tr>
				<td>
				<table cellpadding="0" cellspacing="0" border="0" width="100%" >
					<tr>
						
						<td style="padding-left:15px;">
							<table cellpadding="0" cellspacing="0" border="0">
							<tr>
								<td colspan="5" height="30"><b>{$lang.content.i_search}:</b></td>
							</tr>
							<tr>
								<td height="30" width="110">{$lang.content.who}</td>
								<td>
									<table cellpadding="0" cellspacing="0" border="0">
										<tr>
										{if !$mhi_ad_buy}
											<td><input type="radio" name="choise" id="choise" value="3" {if $data.choise eq 3} checked {/if} onclick="javascript: ChangeChoise(this.value);" ></td>
											<td>&nbsp;{$lang.content.buy_realty}&nbsp;&nbsp;&nbsp;</td>
										{/if}
										{if !$mhi_ad_sell}
											<td><input type="radio" name="choise" id="choise" value="4" {if $data.choise eq 4} checked {/if} onclick="javascript: ChangeChoise(this.value);"></td>
											<td>&nbsp;{$lang.content.sell_realty}&nbsp;&nbsp;&nbsp;</td>
										{/if}	
										{if !$mhi_ad_rent}
											<td><input type="radio" name="choise" id="choise" value="1" {if $data.choise eq 1} checked {/if} onclick="javascript: ChangeChoise(this.value);"></td>
											<td>&nbsp;{$lang.content.need_room}&nbsp;&nbsp;&nbsp;</td>
										{/if}
										{if !$mhi_ad_lease}
											<td><input type="radio" name="choise" id="choise" value="2" {if $data.choise eq 2} checked {/if} onclick="javascript: ChangeChoise(this.value);"></td>
											<td>&nbsp;{$lang.content.have_room}</td>
										{/if}	
										</tr>
									</table>
								</td>
							</tr>
							</table>
						</td>
					</tr>
					<tr id="realty_type_div">
						
						<td valign="top">
							<table cellpadding="0" cellspacing="0" width="100%" border="0" >
								{section name=f loop=$realty_type}
								{if $realty_type[f].visible_in ne 3}
								<tr>
									<td width="110" valign="top" style="padding-top: 7px;padding-left:15px;" {if $smarty.section.f.index is div by 2}bgcolor="#eeeff3"{/if}>{$realty_type[f].name}:&nbsp;</td>
									<td  align="left" {if $smarty.section.f.index is div by 2}bgcolor="#eeeff3"{/if}>
										<input type=hidden name="spr_realty_type[{$realty_type[f].num}]" value="{$realty_type[f].id}">
										<input type=hidden name="id_spr_realty_type[{$realty_type[f].num}]" value="{$realty_type[f].id_spr}">
										<table cellpadding="0" cellspacing="0" border="0">
										{section name=s loop=$realty_type[f].opt}
										{if $smarty.section.s.index is div by 4}<tr>{/if}
											<td height="30" width="15"><input type="checkbox" name="realty_type[{$realty_type[f].num}][]" value="{$realty_type[f].opt[s].value}"  {if $realty_type[f].opt[s].sel} checked {/if}></td>
											<td width="130">{$realty_type[f].opt[s].name}</td>
										{if $smarty.section.s.index_next is div by 4 || $smarty.section.s.last}</tr>{/if}
										{/section}
										</table>
									</td>
								</tr>
								<tr>
									<td colspan="2" {if $smarty.section.f.index is div by 2}bgcolor="#eeeff3"{/if} style="padding-bottom: 7px; padding-left:15px;">
										<span class="blue_link" onclick="javascript: SelAll('realty_type',{$smarty.section.f.index}, 'power_search_form');">{$lang.content.sel_all_text}</span>&nbsp;&nbsp;&nbsp;
										<span class="blue_link" onclick="UnSelAll('realty_type',{$smarty.section.f.index}, 'power_search_form');"  style="padding-left: 5px;">{$lang.content.unsel_all_text}</span>
									</td>
								</tr>
								{/if}
								{/section}
							</table>
						</td>
					</tr>
					<tr>
						<td colspan="1"><hr></td>
					</tr>
					<tr>
						
						<td style="padding-left:15px;">
							<table cellpadding="0" cellspacing="0" border="0" width="100%">
							<tr><td width="110" height="30">{$lang.content.select_country}&nbsp;:&nbsp;</td>
								<td>
									<div id="country_div">
										<select name="country" id="country" onchange="javascript: {literal} SelectRegion('psr', this.value, document.getElementById('region_div'), document.getElementById('city_div'),'{/literal}{$lang.default_select.psr_load_region}{literal}', '{/literal}{$lang.default_select.psr_city}{literal}'); {/literal}" class="location">
											<option value="">{$lang.default_select.psr_country}</option>
											{foreach item=item from=$country}
												<option value="{$item.id}" {if $data.country}{if $data.country eq $item.id} selected{/if}{else}{if $country_id eq $item.id} selected{/if}{/if}>{$item.name}</option>
											{/foreach}
										</select>
									</div>
							</td></tr>
							<tr><td height="30">{$lang.content.select_region}&nbsp;:&nbsp;</td>
								<td><div id="region_div">
										<select name="region" id="region" onchange="javascript: {literal} SelectCity('psr', this.value, document.getElementById('city_div'), '{/literal}{$lang.default_select.psr_load_city}{literal}');{/literal}" class="location">
											<option value="" >{$lang.default_select.psr_region}</option>
											{foreach item=item from=$region}
												<option value="{$item.id}" {if $data.region eq $item.id} selected {/if}>{$item.name}</option>
											{/foreach}
										</select>
									</div>
							</td></tr>
							<tr><td height="30">{$lang.content.select_city}&nbsp;:&nbsp;</td>
								<td><div id="city_div">
										<select name="city" id="city" class="location">
											<option value="">{$lang.default_select.psr_city}</option>
											{foreach item=item from=$city}
												<option value="{$item.id}" {if $data.city eq $item.id} selected {/if}>{$item.name}</option>
											{/foreach}
										</select>
									</div>
							</td></tr>
							</table>
						</td>
					</tr>
					<tr>
						<td colspan="1"><hr></td>
					</tr>
					{* покупка/продажа *}
					<!--<tr id="payment_3_4">-->
					<tr>
						
						<td  style="padding-left:15px;">
						<table cellpadding="0" cellspacing="0" border="0">
							<tr>
								<td width="110" height="30" id="payment_3_4" {if $data.choise eq 3 || $data.choise eq 4}{else}style="display: none;"{/if}>{$lang.content.price}:&nbsp;</td>
								<td width="110" height="30" id="payment_1_2" {if $data.choise eq 1 || $data.choise eq 2}{else}style="display: none;"{/if}>{$lang.content.month_payment}:&nbsp;</td>
								<td style="padding: 0px; padding-right: 3px;">{$lang.content.from}</td>
								<td align="right"><input type="text" class="str" name="min_payment" id="min_payment" value="{$data.min_payment}" size="7"></td>
								<td style="padding: 0px; padding-left: 5px; padding-right: 5px;">{$lang.content.upto}</td>
								<td align="left"><input type="text" class="str" name="max_payment" id="max_payment" value="{$data.max_payment}" size="7"></td>
								<td>&nbsp;{$cur}</td>
								<td><span name="min_payment_error" id="min_payment_error" style="display: none;" class="error_div">&nbsp;&nbsp;{$lang.errors.incorrect_field}<br></span>
									<span name="max_payment_error" id="max_payment_error" style="display: none;" class="error_div">&nbsp;&nbsp;{$lang.errors.incorrect_field}<br></span>
									<span name="bad_payment_error" id="bad_payment_error" style="display: none;" class="error_div">&nbsp;&nbsp;{$lang.content.price_min_more_max}</span>
								</td>
							</tr>
							</table>
							<!-- bidding possibility -->
							<table cellpadding="0" cellspacing="0" border="0">
								<tr>
									<td width="110" height="30">&nbsp;</td>
									<td align="left">
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
					{* аренда *}
					<!-- period -->
					<tr id="period_div" {if $data.choise eq 1 || $data.choise eq 2}{else} style="display: none;" {/if}>
						
						<td valign="top">
							<table cellpadding="0" cellspacing="0" width="100%" border="0">
								{section name=f loop=$period}
								{if $period[f].visible_in ne 3}
								<tr>
									<td width="110" valign="top" style="padding-top: 7px; padding-left:15px;" {if $smarty.section.f.index is div by 2}bgcolor="#eeeff3"{/if}>{$period[f].name}:&nbsp;</td>
									<td align="left" {if $smarty.section.f.index is div by 2}bgcolor="#eeeff3"{/if}>
										<input type=hidden name="spr_period[{$period[f].num}]" value="{$period[f].id}">
										<input type=hidden name="id_spr_period[{$period[f].num}]" value="{$period[f].id_spr}">
										<table cellpadding="0" cellspacing="0" border="0">
										{section name=s loop=$period[f].opt}
										{if $smarty.section.s.index is div by 4}<tr>{/if}
											<td height="30" width="15"><input type="checkbox" name="period[{$period[f].num}][]" value="{$period[f].opt[s].value}"  {if $period[f].opt[s].sel} checked {/if}></td>
											<td width="130">{$period[f].opt[s].name}</td>
										{if $smarty.section.s.index_next is div by 4 || $smarty.section.s.last}</tr>{/if}
										{/section}
										</table>
									</td>
								</tr>
								<tr>
									<td colspan="2" {if $smarty.section.f.index is div by 2}bgcolor="#eeeff3"{/if} style="padding-bottom: 7px; padding-left:15px;">
										<span class="blue_link" onclick="javascript: SelAll('period',{$smarty.section.f.index}, 'power_search_form');">{$lang.content.sel_all_text}</span>&nbsp;&nbsp;&nbsp;
										<span class="blue_link" onclick="UnSelAll('period',{$smarty.section.f.index}, 'power_search_form');"  style="padding-left: 5px;">{$lang.content.unsel_all_text}</span>
									</td>
								</tr>
								{/if}
								{/section}
							</table>
						</td>
					</tr>
					<!-- /period -->
					{* /аренда *}
					<!-- deposit -->
					<tr id="deposit">
						
						<td style="padding-left:15px;">
							<table cellpadding="0" cellspacing="0" border="0">
							<tr>
								<td width="110" height="30">{$lang.content.deposit}:&nbsp;</td>
								<td style="padding: 0px; padding-right: 5px;">{$lang.content.from}</td>
								<td align="right"><input type="text" class="str" value="{$data.min_deposit}" name="min_deposit" id="min_deposit" style="width: 50px;"'></td>
								<td style="padding: 0px; padding-left: 5px; padding-right: 5px;">{$lang.content.upto}</td>
								<td><input type="text" class="str" value="{$data.max_deposit}" name="max_deposit" id="max_deposit" style="width: 50px;"'></td>
								<td>&nbsp;{$cur}</td>
								<td><div class="error_div" id="min_deposit_error" style="display: none;">{$lang.errors.incorrect_field}</div>
								<div class="error_div" id="max_deposit_error" style="display: none;">{$lang.errors.incorrect_field}</div>
								<div name="bad_deposit_error" id="bad_deposit_error" style="display: none;" class="error_div">{$lang.content.deposit_min_more_max}</div>
								</td>
							</tr>
							</table>
						</td>
					</tr>
					<!-- movedate -->
					<tr id="move_date">
						
						<td style="padding-left:15px;">
							<table cellpadding="0" cellspacing="0" border="0">
							<tr>
								<td width="110" height="30">{$lang.content.move_in_date}<br>({$lang.content.not_earlier}):</td>
								<td><input type="checkbox" name="use_movedate" id="use_movedate" value="1" {if $data.use_movedate eq 1}checked{elseif $data.use_movedate eq 0}{/if} onclick="javascript: MoveDateStyle();"></td>
								<td align="left">
									<select name="move_month" id="move_month" onchange="javascript: MyCheck();" {if $data.use_movedate eq 0}disabled{/if}>
									{foreach item=item from=$month}<option value="{$item.value}" {if $item.sel}selected{/if}>{$item.name}</option>{/foreach}
									</select>
									<select name="move_day" id="move_day" onchange="javascript: MyCheck();" {if $data.use_movedate eq 0}disabled{/if}>
									{foreach item=item from=$day}<option value="{$item.value}" {if $item.sel}selected{/if}>{$item.value}</option>{/foreach}
									</select>
									<select name="move_year" id="move_year" onchange="javascript: MyCheck();" {if $data.use_movedate eq 0}disabled{/if}>
									{foreach item=item from=$year}<option value="{$item.value}" {if $item.sel}selected{/if}>{$item.value}</option>{/foreach}
									</select>
								</td>
							</tr>
							</table>
						</td>
					</tr>
					<!-- year build -->
					<tr id="year_build">
						
						<td style="padding-left:15px;">
							<table cellpadding="0" cellspacing="0" border="0">
							<tr>
								<td width="110" height="30">{$lang.content.year_build}:&nbsp;</td>
								<td style="padding: 0px; padding-right: 5px;">{$lang.content.from_1}</td>
								<td align="right"><input type="text" class="str" value="{$data.min_year_build}" name="min_year_build" id="min_year_build" style="width: 50px;"'></td>
								<td style="padding: 0px; padding-left: 5px; padding-right: 5px;">{$lang.content.upto_1}</td>
								<td><input type="text" class="str" value="{$data.max_year_build}" name="max_year_build" id="max_year_build" style="width: 50px;"'></td>
								<td><div class="error_div" id="min_year_build_error" style="display: none;">{$lang.errors.incorrect_field}</div>
								<div class="error_div" id="max_year_build_error" style="display: none;">{$lang.errors.incorrect_field}</div>
								<div name="bad_year_build_error" id="bad_year_build_error" style="display: none;" class="error_div">{$lang.content.year_build_min_more_max}</div>
								</td>
							</tr>
							</table>
						</td>
					</tr>
					<!-- /year build -->
					<tr>
						<td colspan="1"><hr></td>
					</tr>
					<tr id="description_div">
						
						<td valign="top">
							<table cellpadding="0" cellspacing="0" width="100%" border="0">
								{section name=f loop=$description}
								{if $description[f].visible_in ne 3}
								<tr>
									<td width="110" valign="top" style="padding-top: 7px; padding-left:15px;" {if $smarty.section.f.index is div by 2}bgcolor="#eeeff3"{/if}>{$description[f].name}:&nbsp;</td>
									<td align="left" {if $smarty.section.f.index is div by 2}bgcolor="#eeeff3"{/if}>
										<input type=hidden name="spr_description[{$description[f].num}]" value="{$description[f].id}">
										<input type=hidden name="id_spr_description[{$description[f].num}]" value="{$description[f].id_spr}">
										<table cellpadding="0" cellspacing="0" border="0">
										{section name=s loop=$description[f].opt}
										{if $smarty.section.s.index is div by 4}<tr>{/if}
											<td height="30" width="15"><input type="checkbox" name="description[{$description[f].num}][]" value="{$description[f].opt[s].value}"  {if $description[f].opt[s].sel} checked {/if}></td>
											<td width="130">{$description[f].opt[s].name}</td>
										{if $smarty.section.s.index_next is div by 4 || $smarty.section.s.last}</tr>{/if}
										{/section}
										</table>
									</td>
								</tr>
								<tr>
									<td colspan="2" {if $smarty.section.f.index is div by 2}bgcolor="#eeeff3"{/if} style="padding-bottom: 7px; padding-left:15px;">
										<span class="blue_link" onclick="javascript: SelAll('description',{$smarty.section.f.index}, 'power_search_form');">{$lang.content.sel_all_text}</span>&nbsp;&nbsp;&nbsp;
										<span class="blue_link" onclick="UnSelAll('description',{$smarty.section.f.index}, 'power_search_form');"  style="padding-left: 5px;">{$lang.content.unsel_all_text}</span>
									</td>
								</tr>
								{/if}
								{/section}
							</table>
						</td>
					</tr>
					<tr>
						<td colspan="1"><hr></td>
					</tr>
					<!-- square -->
					<tr id="live_square">
						
						<td style="padding-left:15px;">
							<table cellpadding="0" cellspacing="0" border="0">
							<tr>
								<td width="110" height="30">{$lang.content.live_square}:&nbsp;</td>
								<td style="padding: 0px; padding-right: 5px;">{$lang.content.from}</td>
								<td align="right"><input type="text" class="str" value="{$data.min_live_square}" name="min_live_square" id="min_live_square" style="width: 50px;"'></td>
								<td style="padding: 0px; padding-left: 5px; padding-right: 5px;">{$lang.content.upto}</td>
								<td><input type="text" class="str" value="{$data.max_live_square}" name="max_live_square" id="max_live_square" style="width: 50px;"'></td>
								<td>&nbsp;{$sq_meters}</td>
								<td><div class="error_div" id="min_live_square_error" style="display: none;">{$lang.errors.incorrect_field}</div>
								<div class="error_div" id="max_live_square_error" style="display: none;">{$lang.errors.incorrect_field}</div>
								<div name="bad_live_square_error" id="bad_live_square_error" style="display: none;" class="error_div">{$lang.content.live_square_min_more_max}</div>
								</td>
							</tr>
							</table>
						</td>
					</tr>
					<tr id="total_square">
						
						<td style="padding-left:15px;">
							<table cellpadding="0" cellspacing="0" border="0">
							<tr>
								<td width="110" height="30">{$lang.content.total_square}:&nbsp;</td>
								<td style="padding: 0px; padding-right: 5px;">{$lang.content.from}</td>
								<td align="right"><input type="text" class="str" value="{$data.min_total_square}" name="min_total_square" id="min_total_square" style="width: 50px;"'></td>
								<td style="padding: 0px; padding-left: 5px; padding-right: 5px;">{$lang.content.upto}</td>
								<td><input type="text" class="str" value="{$data.max_total_square}" name="max_total_square" id="max_total_square" style="width: 50px;"'></td>
								<td>&nbsp;{$sq_meters}</td>
								<td><div class="error_div" id="min_total_square_error" style="display: none;">{$lang.errors.incorrect_field}</div>
								<div class="error_div" id="max_total_square_error" style="display: none;">{$lang.errors.incorrect_field}</div>
								<div name="bad_total_square_error" id="bad_total_square_error" style="display: none;" class="error_div">{$lang.content.total_square_min_more_max}</div>
								</td>
							</tr>
							</table>
						</td>
					</tr>
					<tr id="land_square">
						
						<td style="padding-left:15px;">
							<table cellpadding="0" cellspacing="0" border="0">
							<tr>
								<td width="110" height="30">{$lang.content.land_square}:&nbsp;</td>
								<td style="padding: 0px; padding-right: 5px;">{$lang.content.from}</td>
								<td align="right"><input type="text" class="str" value="{$data.min_land_square}" name="min_land_square" id="min_land_square" style="width: 50px;"'></td>
								<td style="padding: 0px; padding-left: 5px; padding-right: 5px;">{$lang.content.upto}</td>
								<td><input type="text" class="str" value="{$data.max_land_square}" name="max_land_square" id="max_land_square" style="width: 50px;"'></td>
								<td>&nbsp;{$sq_meters}</td>
								<td><div class="error_div" id="min_land_square_error" style="display: none;">{$lang.errors.incorrect_field}</div>
								<div class="error_div" id="max_land_square_error" style="display: none;">{$lang.errors.incorrect_field}</div>
								<div name="bad_land_square_error" id="bad_land_square_error" style="display: none;" class="error_div">{$lang.content.land_square_min_more_max}</div>
								</td>
							</tr>
							</table>
						</td>
					</tr>
					<!-- /square -->
					<tr>
						<td colspan="1"><hr></td>
					</tr>
					<!-- floor -->
					<tr id="floor">
						
						<td style="padding-left:15px;">
							<table cellpadding="0" cellspacing="0" border="0">
							<tr>
								<td width="110" height="30">{$lang.content.search_floor_variants}:&nbsp;</td>
								<td style="padding: 0px; padding-right: 5px;">{$lang.content.from_1}</td>
								<td align="right"><input type="text" class="str" value="{$data.min_floor}" name="min_floor" id="min_floor" style="width: 50px;"'></td>
								<td style="padding: 0px; padding-left: 5px; padding-right: 5px;">{$lang.content.upto_1}</td>
								<td><input type="text" class="str" value="{$data.max_floor}" name="max_floor" id="max_floor" style="width: 50px;"'></td>
								<td>&nbsp;{$lang.content.floor}</td>
								<td><div class="error_div" id="min_floor_error" style="display: none;">{$lang.errors.incorrect_field}</div>
								<div class="error_div" id="max_floor_error" style="display: none;">{$lang.errors.incorrect_field}</div>
								<div name="bad_floor_error" id="bad_floor_error" style="display: none;" class="error_div">{$lang.content.floor_min_more_max}</div>
								</td>
							</tr>
							</table>
						</td>
					</tr>
					<tr>
						
						<td style="padding-left:15px;">
							<table cellpadding="0" cellspacing="0" border="0">
							<tr>
								<td width="110" height="30">{$lang.content.floor_num_max_limitation}:&nbsp;</td>
								<td><input type="text" class="str" value="{$data.floor_num}" name="floor_num" id="floor_num" size="7"></td>
								<td>&nbsp;{$lang.content.of_floors}</td>
								<td><div class="error_div" id="floor_num_error" style="display: none;">&nbsp;{$lang.errors.incorrect_field}</div></td>
							</tr>
							</table>
						</td>
					</tr>
					<!-- /floor -->
					<tr>
						<td colspan="1"><hr></td>
					</tr>
					<!-- subway_min -->
					<tr>
						
						<td style="padding-left:15px;">
							<table cellpadding="0" cellspacing="0" border="0">
							<tr>
								<td width="110" height="30">{$lang.content.subway_min}:&nbsp;</td>
								<td><input type="text" class="str" value="{$data.subway_min}" name="subway_min" id="subway_min" size="7"></td>
								<td>&nbsp;{$lang.content.minutes}</td>
								<td><div class="error_div" id="subway_min_error" style="display: none;">&nbsp;{$lang.errors.incorrect_field}</div></td>
							</tr>
							</table>
						</td>
					</tr>
					<!-- /subway_min -->
					<tr>
						<td colspan="1"><hr></td>
					</tr>
					<tr id="info_div">
						
						<td valign="top">
							<table cellpadding="0" cellspacing="0" width="100%" border="0">
								{section name=f loop=$info}
								{if $info[f].visible_in ne 3}
								<tr>
									<td width="110" valign="top" style="padding-top: 7px; padding-left:15px;" {if $smarty.section.f.index is div by 2}bgcolor="#eeeff3"{/if}>{$info[f].name}:&nbsp;</td>
									<td align="left" {if $smarty.section.f.index is div by 2}bgcolor="#eeeff3"{/if}>
										<input type=hidden name="spr_info[{$info[f].num}]" value="{$info[f].id}">
										<input type=hidden name="id_spr_info[{$info[f].num}]" value="{$info[f].id_spr}">
										<table cellpadding="0" cellspacing="0" border="0">
										{section name=s loop=$info[f].opt}
										{if $smarty.section.s.index is div by 4}<tr>{/if}
											<td height="30" width="15"><input type="checkbox" name="info[{$info[f].num}][]" value="{$info[f].opt[s].value}"  {if $info[f].opt[s].sel} checked {/if}></td>
											<td width="130">{$info[f].opt[s].name}</td>
										{if $smarty.section.s.index_next is div by 4 || $smarty.section.s.last}</tr>{/if}
										{/section}
										</table>
									</td>
								</tr>
								<tr>
									<td colspan="2" {if $smarty.section.f.index is div by 2}bgcolor="#eeeff3"{/if} style="padding-left:15px;padding-bottom: 7px;">
										<span class="blue_link" onclick="javascript: SelAll('info',{$smarty.section.f.index}, 'power_search_form');">{$lang.content.sel_all_text}</span>&nbsp;&nbsp;&nbsp;
										<span class="blue_link" onclick="UnSelAll('info',{$smarty.section.f.index}, 'power_search_form');"  style="padding-left: 5px;">{$lang.content.unsel_all_text}</span>
									</td>
								</tr>
								{/if}
								{/section}
							</table>
						</td>
					</tr>
					<tr>
						<td colspan="1"><hr></td>
					</tr>
					<!-- with_photo -->
					<tr>
						
						<td height="30" style="padding-left:15px;">
							<table cellpadding="0" cellspacing="0" border="0">
							<tr>
								<td><input type="checkbox" name="with_photo" value="1" {if $data.with_photo eq 1}checked{/if}></td>
								<td>{$lang.content.with_photo_first}</td>
							</tr>
							</table>
						</td>
					</tr>
					<!-- with_video -->
					<tr>
						
						<td height="30" style="padding-left:15px;">
							<table cellpadding="0" cellspacing="0" border="0">
							<tr>
								<td><input type="checkbox" name="with_video" value="1" {if $data.with_video eq 1}checked{/if}></td>
								<td>{$lang.content.with_video}</td>
							</tr>
							</table>
						</td>
					</tr>
				</table>
				</td>
			</tr>
		</table>
		<table cellpadding="0" cellspacing="0">
			<tr><td colspan="2">&nbsp;</td>
			<tr><td colspan="2" style="padding-left: 17px"><input type="button" class="btn_small" value="{$lang.buttons.search}" onclick="{literal}if (CheckAllForm()==true) {document.power_search_form.action='{/literal}{$file_name}?sel=search{literal}'; document.power_search_form.submit();} {/literal}"></td>
		</table>
		<table cellpadding="0" cellspacing="0" width="100%" border="0" id="saved_search">
			<tr><td colspan="4">&nbsp;</td></tr>
			<tr valign="top">
				<td colspan="2" class="subheader" width="50%"><b>{$lang.headers.search_save}</b></td>
				<td colspan="2" class="subheader" width="50%"><b>{$lang.headers.search_load}</b></td>
			</tr>
			<tr>
				<td width="10">&nbsp;</td>
				<td>
					<table cellpadding="0" cellspacing="0" border="0">
						<tr>
							<td height="30">{$lang.content.save_power_search}&nbsp;:&nbsp;</td>
						</tr>
						<tr>
							<td height="30"><input type="text" class="str" name="search_name" value="{if !$data.search_name}{$lang.content.default_search_name}{else}{$data.search_name}{/if}" size="40" maxlength="100"></td>
						</tr>
						<tr>
							<td height="30"><input type="button" class="btn_small" value="{$lang.buttons.save}" onclick="javascript: {literal} if (CheckAllForm()==true && document.power_search_form.search_name.value.trim() != '') { document.power_search_form.action='{/literal}{$file_name}?sel=save_search{literal}'; document.power_search_form.submit();}{/literal}"></td>
						</tr>
					</table>
				</td>
				<td width="7">&nbsp;</td>
				<td>
					<table cellpadding="0" cellspacing="0" border="0">
						<tr>
							<td height="30" colspan="2">{$lang.content.load_power_search}&nbsp;:&nbsp;</td>
						</tr>
						<tr><td height="30" colspan="2">
							<select name="load" style="width:150px;">
							<option value="0">...</option>
								{html_options options=$load selected=$data.id_save}
							</select>
						</td></tr>
						<tr>
							<td height="30"><input type="button" class="btn_small" value="{$lang.buttons.load}" onclick="javascript: {literal} if (document.power_search_form.load.value !='0') {document.power_search_form.action='{/literal}{$file_name}?sel=load_search&amp;id_save={literal}'+document.power_search_form.load.value; document.power_search_form.submit();}{/literal}">&nbsp;</td>
							<td><input type="button" class="btn_small" value="{$lang.buttons.delete}" onclick="javascript: {literal} if (document.power_search_form.load.value !='0') {document.power_search_form.action='{/literal}{$file_name}?sel=delete_search&amp;id_save={literal}'+document.power_search_form.load.value; document.power_search_form.submit();}{/literal}"></td>
						</tr>
					</table>
				</td>
			</tr>
			<tr><td colspan="4">&nbsp;</td></tr>
		</table>
		</form>
		{if !$mhi_registration}
		<table cellpadding="0" cellspacing="0" border="0" width="100%" id="search_by_name">
		<tr valign="top">
			<td height="30" colspan="2" class="subheader"><b>{$lang.headers.search_direct}</b></td>
		</tr>
		<tr>
			<td height="30" style="padding-left: 15px;">
				<a href="{$file_name}?sel=new_members">{$lang.content.search_new_members}</a>
			</td>
		</tr>
		<tr>
			<td height="30" style="padding-left: 15px;">
			<form method="POST" id="nick_form" name="nick_form" action="">
				<table cellpadding="0" cellspacing="0">
				<tr>
					<td>{$lang.content.search_by_nick}&nbsp;:&nbsp;</td>
					<td><input type="text" name="nick" id="nick" class="str">&nbsp;</td>
					<td><input class="btn_small" type="button" value="{$lang.buttons.search}" onclick="{literal}if (document.getElementById('nick').value.trim() != ''){document.nick_form.action='{/literal}{$file_name}?sel=by_nick{literal}'; document.nick_form.submit();}{/literal}"></td>
				</tr>
				</table>
			</form>
			</td>
		</tr>
		</table>
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
function SelAll(name, value, form_name){
	element = document.forms[form_name].elements;
	new_name = name+'['+value+']'+'[]';
	for (i=0; i < element.length; i++) {
		if (element[i].name == new_name && element[i].type == 'checkbox'){
			element[i].checked = true;
		}
	}
	return;
}
function UnSelAll(name, value, form_name){
	element = document.forms[form_name].elements;
	new_name = name+'['+value+']'+'[]';
	for (i=0; i < element.length; i++) {
		if (element[i].name == new_name && element[i].type == 'checkbox'){
			element[i].checked = false;
		}
	}
	return;
}

function CheckAllForm() {
	err_cnt = 0;
	if (CheckRangeIntegerFields() != true) {
		err_cnt++;
	}
	if (CheckIntegerFields() != true) {
		err_cnt++;
	}

	if (err_cnt == 0) {
		return true;
	} else {
		return false;
	}
}

function ChangeChoise(choise) {
	if (choise == 1 || choise == 2) {
		document.getElementById("payment_1_2").style.display = '';
		document.getElementById("payment_3_4").style.display = 'none';
		document.getElementById("period_div").style.display = '';
	} else if (choise == 3 || choise == 4) {
		document.getElementById("payment_1_2").style.display = 'none';
		document.getElementById("payment_3_4").style.display = '';
		document.getElementById("period_div").style.display = 'none';
	}
}

function CheckRangeIntegerFields( ){

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

function CheckIntegerFields(  ){

	var id_arr = new Array('floor_num', 'subway_min');
	var reg_expr = new Array();
	reg_expr['subway_min'] = '^[0-9]{0,4}$';
	reg_expr['floor_num'] = '^[0-9]{0,3}$';

	id_arr_cnt = id_arr.length;
	var error_cnt = 0;
	for (i = 0; i < id_arr_cnt; i++) {
		name = id_arr[i];
		value = document.getElementById(name).value;

		if (value != "" && value != 0 && value.search(reg_expr[id_arr[i]]) ==-1) {
			document.getElementById(name + '_error').style.display = '';
			error_cnt++;
		} else {
			document.getElementById(name + '_error').style.display = 'none';
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
		document.getElementById("move_year").disabled = false;
	} else {
		document.getElementById("move_day").disabled = true;
		document.getElementById("move_month").disabled = true;
		document.getElementById("move_year").disabled = true;
	}
}

</script>
{/literal}

{include file="$gentemplates/site_footer.tpl"}