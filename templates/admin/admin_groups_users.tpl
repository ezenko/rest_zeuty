{include file="$admingentemplates/admin_top.tpl" script="qforms"}
<table cellpadding="0" cellspacing="0" border="0" width="100%"><tr>
	<td valign="top">
			<table cellpadding="0" cellspacing="0" width="100%">
				<tr>
					<td class="header" align="left">{$lang.menu.users} <font class="subheader">| {$lang.menu.users} | {$lang.menu.groups_list} | {$groupname} | {$lang.content.group_users}</font></td>
				</tr>
				<tr>
					<td><div class="help_text"><span class="help_title">{$lang.default_select.help}</span>{$lang.content.group_users_list_help}</div></td>
				</tr>
			</table>
			<table width="100%" border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td height="30px" align="left">
					{$lang.content.letter_search_help}: {$letter_links}
					</td>
					<td height="30px" align="right">
					<input type=hidden name="sorter" value="{$sorter}">
					<table>
						<tr>
						<form name="search_form" action="{$form.action}" method="post">
						<td>{$form.hiddens}<input type="text" name="search" value="{$search}"></td>
						<td>
							<select name="s_type" style="">
								<!--<option value="1" {if $s_type == 1}selected{/if}>{$lang.users_types.type_1}</option>-->
								<option value="2" {if $s_type == 2}selected{/if} >{$lang.users_types.type_2}</option>
								<option value="3" {if $s_type == 3}selected{/if}>{$lang.users_types.type_3}</option>
								<option value="4" {if $s_type == 4}selected{/if}>{$lang.users_types.type_4}</option>
							</select>
						</td>
						<td>
							<input type="button" class="button_1" value="{$lang.buttons.search}" onclick="javascript: document.search_form.submit();" name="search_submit">
						</td>
						</form>
						</tr>
					</table>
					</td>
				</tr>
			</table>
			{if $links}
			<table cellpadding="2" cellspacing="1" border="0" class="links_top">
				<tr>
					<td>{$lang.content.pages}</td>
					{foreach item=item from=$links}
					<td><a href="{$item.link}" class="page_link" {if $item.selected} style=" font-weight: bold;  text-decoration: none;" {/if}>{$item.name}</a></td>
					{/foreach}
				</tr>
			</table>
			{/if}
			<table border=0 class="table_main" cellspacing=1 cellpadding=3 width="100%" style="margin: 0px;">
				<tr>
					<th align="center">{$lang.content.number}</th>
					<th align="center"><div style="cursor: pointer;" onclick="javascript:location.href='{$sorter_link}&sorter=1';">{$lang.content.user_name}{if $sorter==1}{$order_icon}{/if}</div></th>
					<th align="center"><div style="cursor: pointer;" onclick="javascript:location.href='{$sorter_link}&sorter=2';">{$lang.content.email}{if $sorter==2}{$order_icon}{/if}</div></th>
					<th align="center"><div style="cursor: pointer;" onclick="javascript:location.href='{$sorter_link}&sorter=3';">{$lang.content.user_type}{if $sorter==3}{$order_icon}{/if}</div></th>
					{if $id_group != 3}
					<th align="center">
						{$lang.content.users_group_period}
					</th>
					{/if}
					<th align="center">
						{$lang.content.payments}
					</th>
				</tr>
				{if $user}
				{section name=u loop=$user}
				<tr>
					<td align="center">{$user[u].number}</td>
					<td align="center">
						{if $user[u].root_user}
							{$user[u].name}
						{else}
							<a href="{$user[u].edit_link}">{$user[u].name}</a>
						{/if}
					</td>
					<td align="center">
						{if $user[u].root_user}
							&nbsp;
						{else}
							{$user[u].email}
						{/if}
					</td>
					<input type="hidden" name="id_user[{$user[u].number}]" value="{$user[u].id}">
					</td>
					<td align="center">
					{if !$user[u].root && !$user[u].guest}
						{if $user[u].user_type eq 1}{$lang.content.user_type_1}
						{elseif $user[u].user_type eq 2}{$lang.content.user_type_2}
						{/if}
					{elseif $user[u].root}
						{$lang.content.admin}
					{elseif $user[u].guest}
						{$lang.content.guest}
					{/if}
					</td>
					{if $id_group != 3}
					<td align="center">
						{$user[u].groups}{if $user[u].dates} ({$user[u].dates}) {else} {$lang.content.expired}{/if}
					</td>
					{/if}
					<td align="center">
						{if $user[u].payments_link}
							<input type="button" class="button_2" value="{$lang.buttons.view}" onclick="{literal}javascript: document.location.href='{/literal}{$user[u].payments_link}{literal}';{/literal}">
						{else}
							{$lang.content.empty_payments}
						{/if}
					</td>
				</tr>
				{/section}
				{/if}
			</table>
			{if $links}
			<table cellpadding="2" cellspacing="1" border="0" class="links_bottom">
				<tr>
					<td>{$lang.content.pages}</td>
					{foreach item=item from=$links}
					<td><a href="{$item.link}" class="page_link" {if $item.selected} style=" font-weight: bold;  text-decoration: none;" {/if}>{$item.name}</a></td>
					{/foreach}
				</tr>
			</table>
			{/if}
			{if !$user}
				{if $letter != "*" || $search}
					<div class="message">{$lang.content.empty_result} <a href="{$file_name}?sel={$sel}&id_group={$id_group}">{$lang.content.empty_res_search_criteria}</a></div>
				{else}
					<div class="message">{$lang.content.empty_pays}</div>
				{/if}
			{/if}
	</td></tr>
</table>
<br><input type="button" class="button_3" value="{$lang.buttons.back}" onclick="document.location.href='{$file_name}';">

{***** user moving from one group to another, now not used*****}
{*
<table cellpadding="0" cellspacing="0" width="100%">
<tr><td valign="top">
	<table cellpadding="0" cellspacing="0" width="100%">
		<tr>
			<td class="header" align="left">{$lang.menu.users} <font class="subheader">| {$lang.menu.users} | {$lang.menu.groups_list} | {$form.groupname} | {$lang.content.group_users}</font></td>
		</tr>
		<tr>
			<td><div class="help_text"><span class="help_title">{$lang.default_select.help}</span>{$lang.content.user_group_help}</div></td>
		</tr>
	</table>
	<table border=0 class="table_main" cellspacing=1 cellpadding=5 width="100%">
	<tr class="table_header">
		<td class="main_header_text" align="center"><b>&nbsp;</b></td>
		<td class="main_header_text" align="center" width="300"><b>{$lang.content.all_users}</b></td>
		<td class="main_header_text" align="center" width="50"><b>&nbsp;</b></td>
		<td class="main_header_text" align="center" width="300"><b>{$lang.content.group_users}</b></td>
		<td class="main_header_text" align="center"><b>&nbsp;</b></td>
	</tr>
	<tr bgcolor="#ffffff">
		<td class="main_header_text" align="center"><b>&nbsp;</b></td>
		<td  class="main_header_text" align="center">
		<table><tr>
			<form name="search_form" action="{$form.action}" method="post">
			{$search_hiddens}
			<td class="main_header_text" ><input type="text" style="width:100; height:20" name="search" value="{$search}" {if $root}disabled{/if}></td>
			<td class="main_content_text" >
				<select name="s_type" style="width:70; height:40">
				{section name=s loop=$types}
				<option value="{$smarty.section.s.index_next}" {if $types[s].sel}selected{/if}>{$types[s].value}</option>
				{/section}
				</select></td>
			<td>{if !$root}<input type="button" class="button_1" onclick="javascript:users_search_click();document.search_form.submit();" value="{$lang.buttons.search}">
			{/if}</td>
			</form>
		</tr></table>
		</td>
		<td class="main_header_text" align="center"><b>&nbsp;</b></td>
		<td class="main_header_text" align="center"><b>&nbsp;</b></td>
		<td class="main_header_text" align="center"><b>&nbsp;</b></td>
	</tr>
	<form name="addUsers" action="{$form.action}" method="POST" enctype="multipart/form-data">
 	{$form.hiddens}
    <tr bgcolor="#ffffff">
		<td class="main_content_text" align="center"><b>&nbsp;</b></td>
        <td align="center" class="main_content_text"><br>
			<select name="AllUsers"  size="20" multiple style="width: 250px;" onDblClick="objForm.AllUsers.transferTo('IncUsers');"  {if $root}disabled{/if}>
			{section name=u loop=$allusers_arr}
			<option value="{$allusers_arr[u].value}" {if $allusers_arr[u].sel}selected{/if}>{$allusers_arr[u].name}</option>
			{/section}
			</select><br><br>
		</td>
        <td align="center" class="main_header_text">
		{if !$root}
		<input type="button" class="button_2" onClick="objForm.AllUsers.transferTo('IncUsers', true, 'all');" value="&gt;&gt;"><br><br>
		<input type="button" class="button_2" onClick="objForm.AllUsers.transferTo('IncUsers');" value="&gt;"><br><br><br>
		<input type="button" class="button_2" onClick="objForm.AllUsers.transferFrom('IncUsers');" value="&lt;"><br><br>
		<input type="button" class="button_2" onClick="objForm.AllUsers.transferFrom('IncUsers', true, 'all');" value="&lt;&lt;"><br><br>
		{/if}
		</td>
        <td align="center" class="main_content_text"><br>
			<select name="IncUsers" size="20" multiple style="width: 250px;" onDblClick="objForm.AllUsers.transferFrom('IncUsers');"  {if $root}disabled{/if}>
			{section name=u loop=$gusers_arr}
			<option value="{$gusers_arr[u].value}" {if $gusers_arr[u].sel}selected{/if}>{$gusers_arr[u].name}</option>
			{/section}
		</select><br><br>
		</td>
		<td class="main_content_text" align="center"><b>&nbsp;</b></td>
    </tr>
	<!-- user hidden list  -->
	{section name=u loop=$goldusers_arr}
	<input type=hidden value="{$goldusers_arr[u].value}" name="prevusers[{$smarty.section.u.index}]">
	{/section}
    </table>
    <table><tr height="40">
    {if !$root}
	<td><input type=submit value="{$lang.buttons.save}"  class="button_3"  {if $root}disabled{/if}></td>
	{/if}
	<td><input type=button value="{$lang.buttons.back}" onclick="javascript: location.href='{$form.back}'" class="button_3"></td>
	</tr></table>
    </form>
{literal}
<SCRIPT LANGUAGE="JavaScript">
<!--//
// initialize the qForm object
objForm = new qForm("addUsers");
// make the User field a container, this will ensure that the "reset()"
// method will restore the values in the select box, even if they've
// been removed from the select box
objForm.AllUsers.makeContainer();
// setting the "dummyContainer" property to false will ensure that no values
// from this container are included with the value
objForm.AllUsers.dummyContainer = true;
// make the "Members" field a container--every item in the "Members" select box
// will be part of the container, even if the item isn't selected.
objForm.IncUsers.makeContainer();
//-->

function users_search_click() {
	var sel = document.addUsers.IncUsers;
	var i=0;
	var res ='';
	var first = 0;
	while (i<sel.options.length) {
		res = res + sel.options(i).value+', ';
		++i;
	}
	document.search_form.IncSUsers.value=res;
}

</script>
{/literal}
</td></tr>
</table>
*}
{***** /user moving from one group to another, now not used*****}
{include file="$admingentemplates/admin_bottom.tpl"}