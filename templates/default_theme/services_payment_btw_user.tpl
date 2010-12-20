<table cellpadding="0" cellspacing="0" width="100%" border="0">
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
	<td colspan="2" class="subheader"><b>{$lang.headers.payment_between_user}</b></td>
</tr>
<tr>
	<td width="15"></td>
	<td>
		<table cellpadding="0" cellspacing="0" width="100%" border="0">
		{if $error}
		<tr>
			<td style="padding: 10px 0px 0px 5px;">
			<div class="error">*&nbsp;{$error}{if $amount < $minimal_transfer_value}{$minimal_transfer_value}&nbsp;{$cur}{/if}</div>
			</td>			
		</tr>
		{/if}
		<tr>
			<td style="padding-top: 10px; padding-bottom: 5px;">{if $settings.commission_percent}{$lang.content.payment_form_text_3}{$settings.commission_percent}%{else}{$lang.content.payment_no_commission}{/if}</td>
		</tr>
		<tr>
			<td style="padding-top: 5px;"><b>{$lang.content.user_search}</b></td>
		</tr>
		<tr>
			<td style="padding-top: 5px;">{$lang.content.search_help}</td>
		</tr>
		
		<tr>
			<td style="padding-bottom: 10px;">
				<table width="100%" cellpadding="1" cellspacing="0"><tr>
					
					<td height="30px"  align="left" bgcolor="#FFFFFF" class="main_content_text">
					
					
					<table cellpadding="0" cellspacing="0"><tr>
						<form name="search_form" action="{$file_name}" method="post">
						<input type=hidden name="sel" value="payment_between_user">
						<input type=hidden name="is_show" value="1">
						<input type=hidden name="sorter" value="{$sorter}">
						<input type=hidden name="order" value="{$order}">

						<td class="main_content_text" ><input type="text" name="search" class="str" value="{$search}"></td>			
		
						<td class="main_content_text" style="padding-left:5px;" >
							<input type="button" class="btn_small" value="{$lang.buttons.search}" onclick="javascript: document.search_form.sel.value='payment_between_user'; document.search_form.submit();" name="search_submit">
						</td>
						</form>
					</tr></table>
					</td>
				</tr></table>
			</td>
		</tr>
		{if $users}
		<tr>
		<td>			
		<form name="user_form" id="user_form" action="" method="POST"> 
			<table class="table_main"  cellspacing=1 cellpadding=3 width="100%" border="0" style="margin-bottom: 10px;" >				
				<tr class="table_header">
					<td class="main_header_text" align="center">{$lang.content.number}</td>
					<!--<td class="main_header_text" align="center">
						<div style="cursor: pointer;" onclick="javascript:location.href='{$file_name}?sorter=1&order={$order}&letter={$letter}&search={$search}&s_type={$s_type}&s_stat={$s_stat}';">{$lang.content.nick}</div>
					</td>-->
					<td class="main_header_text" align="center">
						<div style="cursor: pointer;" onclick="javascript:location.href='{$file_name}?sel=payment_between_user&is_show=1&sorter=1&order={if $sorter==1}{$data.sorter_tolink}{else}2{/if}&search={$search}&s_type={$s_type}&page={$page}';">{$lang.content.user_name}{if $sorter==1}{$data.order_icon}{/if}</div>
					</td>
					<td class="main_header_text" align="center">
						<div style="cursor: pointer;" onclick="javascript:location.href='{$file_name}?sel=payment_between_user&is_show=1&sorter=2&order={if $sorter==2}{$data.sorter_tolink}{else}2{/if}&search={$search}&s_type={$s_type}&page={$page}';">{$lang.content.company_name}{if $sorter==2}{$data.order_icon}{/if}</div>
					</td>					
					
					<td class="main_header_text" align="center">
						<div style="cursor: pointer;" onclick="javascript:location.href='{$file_name}?sel=payment_between_user&is_show=1&sorter=5&order={if $sorter==5}{$data.sorter_tolink}{else}2{/if}&search={$search}&s_type={$s_type}&page={$page}';">{$lang.content.user_ads}{if $sorter==5}{$data.order_icon}{/if}</div>
					</td>
					<td class="main_header_text" align="center">{$lang.content.user_payment}</td>
				</tr>
				{assign var=page_pr value=$page-1}
				
				
				{section name=u loop=$users}
				
				<tr>
					<td class="main_content_text" align="center" bgcolor="#FFFFFF"><!--{$user[u].number}-->{$rows_num_page*$page_pr+$smarty.section.u.index+1}</td>
					<!--<td class="main_content_text" align="center" bgcolor="#FFFFFF">
						{if $user[u].root_user}
							{$user[u].nick}
						{else}
							<a href="{$user[u].edit_link}">{$user[u].nick}</a>
						{/if}
					</td>-->
					<td class="main_content_text" align="center" bgcolor="#FFFFFF">						
						{$users[u].name}
					</td>
					<td class="main_content_text" align="center" bgcolor="#FFFFFF">						
						{$users[u].company_name}
						<input type="hidden" name="id_user[{$users[u].number}]" value="{$users[u].id}">
					</td>
										
					
										
					<td class="main_content_text" align="center" bgcolor="#FFFFFF">
						{if $users[u].rent_count}
							<input type="button" class="button_2" value="{$lang.buttons.view}&nbsp;:&nbsp;{$users[u].rent_count}" onclick="{literal}javascript: document.location.href='{/literal}{$users[u].rent_link}&user_myself={$users[u].index}&user_index={literal}'+document.payment_form.user_index.value;{/literal}">
						{else}
							{$lang.content.empty_ads}
						{/if}
					</td>
									
					<td class="main_content_text" align="center" bgcolor="#FFFFFF">
						
							<input type="button" class="button_2" value="{$lang.buttons.to_payment}" onclick="{literal}javascript: VisiblePayment({/literal}&quot;{$smarty.section.u.index}&quot;{literal}, {/literal}&quot;{$users[u].id}&quot;{literal}, &quot;{/literal}{$lang.content.string_1} {$users[u].name} {$lang.content.string_2}{literal}&quot;);{/literal}">
						
					</td>
				</tr>
				{/section}
						
			</table>
			{if $links}
				<table cellpadding="2" cellspacing="2" border="0" style="margin-bottom:5px;">
					<tr>
						<td class="text2">{$lang.default_select.pages}:
						{foreach item=item from=$links}
						<a href="{$item.link}" {if $item.selected} style="font-weight: bold;"{/if}>{$item.name}</a>
						{/foreach}
						</td>
					</tr>
				</table>
			{/if}
			</form>			
		</td>
		</tr>
		<tr>
		
			<td style="{if $user_index < 0} display: none;{/if}" id="field_to_user" valign="middle">
			
				<form name="payment_form" action="{$file_name}" method="post">
				<input type="hidden" name="sel" value="transfer_to_user">
				<input type="hidden" name="sorter" value="{$sorter}">
				<input type="hidden" name="order" value="{$order}">
				<input type="hidden" name="page" value="{$page}">
				<input type="hidden" name="s_type" value="{$s_type}">
				<input type="hidden" name="search" value="{$search}">				
							
				<input type="hidden" name="to_user_id" id="to_user_id" value="{$users[$user_index].id}"> 
				<input type="hidden" name="user_index" id="user_index" value="{$user_index}">
				<div style="display: inline;" id="to_user" name="test">{$lang.content.string_1} {$users[$user_index].name} {$lang.content.string_2}</div>				
				<input type="text" name="amount" style="vertical-align:middle;" class="amount" value="{$amount}">
				{$cur}			
				<input type="button" class="btn_small" style="vertical-align:middle;" value="{$lang.buttons.transfer}" onclick="{literal}javascript: if(confirm({/literal}'{$lang.content.transfer_confirm}'{literal} ) ) document.payment_form.submit();{/literal}">				
				</form>
						
			<td>
		</tr>
		{/if}
		<tr>
			<td>
			
			{if !$users && $is_show}
			<table>
			<tr>
				<td><font class="error">{if $search == ""}{$lang.content.need_search_criteria}{else}{$lang.content.empty_search}{/if}</font></td>				
			</tr>
			</table>
			{/if}
			<td>
		</tr>	
	</table>
	</td>
</tr>
</table>

{literal}
<script language="javascript">

function VisiblePayment(user_index, user_id, str) {	
	document.getElementById('field_to_user').style.display = 'inline';	
	document.getElementById('to_user_id').value = user_id;
	document.getElementById('user_index').value = user_index;
	document.getElementById("to_user").innerHTML =str;
}
</script>
{/literal}