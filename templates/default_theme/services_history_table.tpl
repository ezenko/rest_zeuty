{include file="$gentemplates/site_top.tpl"}
<table cellpadding="0" cellspacing="0" border="0">
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
		<tr><td class="header"><b>{$lang.headers.services}</b></td></tr>
		</table>
		<table cellpadding="0" cellspacing="0" border="0" width="100%">
		<tr><td colspan="2" class="subheader"><b>{$lang.headers.services_history}</b></td></tr>
	{if $par eq 'view'}
		<tr>
			<td width="10">&nbsp;</td>
			<td>
				<table cellpadding="5" cellspacing="0" border="0">
				<tr>
					<td><b>{$lang.content.name}:</b>&nbsp;</td>
					<td>{if $data.status != "none"}{$lang.content.history_text_1}&nbsp;&#8470;{$data.id}{else}{$lang.content.history_text_2}&nbsp;{$data.user_from_name}{/if}</td>
				</tr>
				<tr>
					<td><b>{$lang.content.date_send}:</b>&nbsp;</td>
					<td>{$data.date_send}</td>
				</tr>
				<tr>
					<td><b>{$lang.content.count_curr}:</b>&nbsp;</td>
					<td>{$data.count_curr}&nbsp;{$data.currency}</td>
				</tr>
				<tr>
					<td><b>{$lang.content.add_info}</b></td>
					<td>{$data.user_info}</td>
				</tr>
				<tr>
					<td><b>{$lang.content.status}:</b>&nbsp;</td>
					<td class="error">{if $data.status eq 'send'}{$lang.content.status_send}{elseif $data.status eq 'fail'}{$lang.content.status_fail}{elseif $data.status eq 'approve'}{$lang.content.status_approve}{/if}</td>
				</tr>
				{if $data.print_link}
				<tr>
					<td colspan="2"><a href="{$data.print_link}">{$lang.content.print}</a></td>
				</tr>
				{/if}
				<tr>
					<td colspan="2"><a href="{$file_name}?sel=payment_history">{$lang.content.to_history}</a>&nbsp;|&nbsp;<a href="{$file_name}?sel=payment_form">{$lang.content.link_text_1}</a></td>
				</tr>
				</table>
			</td>
		</tr>
	{else}
		<tr>
			<td width="15">&nbsp;</td>
			<td height="27"><b>{$lang.content.entries}</b>{if !$data} {$lang.content.were_not}{/if}</td>
		</tr>
		{if $data}
			<tr>
				<td width="10">&nbsp;</td>
				<td>
				<div id="five_req_div">
					<table cellpadding="0" cellspacing="0" border="0" class="compare_table" width="100%">
					<tr>
						<td width="50%"><b>{$lang.content.name}</b></td>
						<td width="15%"><b>{$lang.content.date_send}</b></td>
						<td><b>{$lang.content.count_curr}</b></td>
						<td><b>{$lang.content.status}</b></td>
					</tr>
					{section name=i loop=$data max=5}
					<tr>
						<td>{if $data[i].status == "by_admin"}{$lang.content.history_text_3}{elseif $data[i].status != "none"}{$lang.content.history_text_1}&nbsp;&#8470;{$data[i].id}{else}{$lang.content.history_text_2}&nbsp;{$data[i].user_from_name}{/if}</td>
						<td>{$data[i].date_send}</td>
						<td>{$data[i].count_curr}&nbsp;{$data[i].currency}</td>
						<td class="error">{if $data[i].status eq 'send'}{$lang.content.status_send}{elseif $data[i].status eq 'fail'}{$lang.content.status_fail}{elseif $data[i].status eq 'approve'}{$lang.content.status_approve}{elseif $data[i].status eq 'none'}{$lang.content.status_approve}{/if}{if $data[i].link}&nbsp;&nbsp;&nbsp;<a href="{$data[i].link}">{$lang.content.view}</a>{/if}
						</td>						
					</tr>
					{/section}
					</table>
				</div>				
				<div id="all_req_div" style="display: none;">
					<table cellpadding="0" cellspacing="0" border="0" class="compare_table" width="100%">
					<tr>
						<td width="40%"><b>{$lang.content.name}</b></td>
						<td width="15%"><b>{$lang.content.date_send}</b></td>
						<td><b>{$lang.content.count_curr}</b></td>
						<td><b>{$lang.content.status}</b></td>						
					</tr>
					{section name=i loop=$data}
					<tr>
						<td>{if $data[i].status != "none"}{$lang.content.history_text_1}&nbsp;&#8470;{$data[i].id}{else}{$lang.content.history_text_2}&nbsp;{$data[i].user_from_name}{/if}</td>
						<td>{$data[i].date_send}</td>
						<td>{$data[i].count_curr}&nbsp;{$data[i].currency}</td>
						<td class="error">{if $data[i].status eq 'send'}{$lang.content.status_send}{elseif $data[i].status eq 'fail'}{$lang.content.status_fail}{elseif $data[i].status eq 'approve'}{$lang.content.status_approve}{elseif $data[i].status eq 'none'}{$lang.content.status_approve}{/if}{if $data[i].link}&nbsp;&nbsp;&nbsp;<a href="{$data[i].link}">{$lang.content.view}</a>{/if}
						</td>						
					</tr>
					{/section}
					</table>
				</div>	
				</td>
			</tr>
			{if $data[0].all_req_link eq 1}
			<tr>
				<tr>
				<td style="padding-left: 15px; padding-top: 15px;" id="show_link" colspan="3"><a onclick="ShowAllReq();">{$lang.content.all_requests}</a></td>
			</tr>
			{/if}
		{/if}
		<tr><td height="20"></td></tr>
		<tr>
			<td width="15">&nbsp;</td>
			<td height="27"><b>{$lang.content.spended}</b>{if !$spended} {$lang.content.were_not}{/if}</td>
		</tr>
		{if $spended}
			<tr>
				<td width="10">&nbsp;</td>
				<td id="five_spended_div">
					<table cellpadding="0" cellspacing="0" border="0" class="compare_table" width="100%">
					<tr>
						<td width="50%"><b>{$lang.content.name}</b></td>
						<td width="15%"><b>{$lang.content.date_send}</b></td>
						<td><b>{$lang.content.count_curr}</b></td>
					</tr>					
					{section name=i loop=$spended max=5}
					<tr>
						<td>
						{assign var=temp value=$spended[i].id_service}
						{assign var=lang_str value=spended_$temp}						
						{$lang.content[$lang_str]}&nbsp;{$spended[i].user_from_name}

						</td>
						<td>{$spended[i].date_send}</td>
						<td>{$spended[i].count_curr}&nbsp;{$spended[i].currency}</td>
					</tr>
					{/section}
					</table>
				</td>
				<td id="all_spended_div" style="display: none;">
					<table cellpadding="0" cellspacing="0" border="0" class="compare_table" width="100%">
					<tr>
						<td width="50%"><b>{$lang.content.name}</b></td>
						<td width="15%"><b>{$lang.content.date_send}</b></td>
						<td><b>{$lang.content.count_curr}</b></td>
					</tr>
					{section name=i loop=$spended}
					<tr>
						<td>{if $spended[i].id_service eq 1}{$lang.content.spended_1}
						{elseif $spended[i].id_service eq 2}{$lang.content.spended_2}
						{elseif $spended[i].id_service eq 3}{$lang.content.spended_3}
						{elseif $spended[i].id_service eq 4}{$lang.content.spended_4}
						{elseif $spended[i].id_service eq 5}{$lang.content.spended_5}
						{elseif $spended[i].id_service eq 6}{$lang.content.spended_6}&nbsp;{$spended[i].user_from_name}
						{/if}
						</td>
						<td>{$spended[i].date_send}</td>
						<td>{$spended[i].count_curr}&nbsp;{$spended[i].currency}</td>
					</tr>
					{/section}
					</table>
				</td>
			</tr>
			{if $spended[0].all_spend_link eq 1}
			<tr>
				<td style="padding-left: 15px; padding-top: 15px;" id="show_spended_link" colspan="3"><a onclick="ShowAllSpended();">{$lang.content.all_spended}</a></td>
			</tr>
			{/if}
		{/if}
	{/if}
		</table>
	</td>
</tr>
</table>
{literal}
<script type="text/javascript">
function ShowAllReq() {
	if (document.getElementById('all_req_div').style.display == 'none') {
		document.getElementById('five_req_div').style.display = 'none';
		document.getElementById('show_link').style.display = 'none';
		document.getElementById('all_req_div').style.display = 'inline';
	}
	return;
}
function ShowAllSpended() {
	if (document.getElementById('all_spended_div').style.display == 'none') {
		document.getElementById('five_spended_div').style.display = 'none';
		document.getElementById('show_spended_link').style.display = 'none';
		document.getElementById('all_spended_div').style.display = 'inline';
	}	return;
}
</script>
{/literal}
{include file="$gentemplates/site_footer.tpl"}