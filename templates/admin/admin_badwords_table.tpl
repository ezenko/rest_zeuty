{include file="$admingentemplates/admin_top.tpl"}
	<table cellpadding="0" cellspacing="0" border="0" width="100%">
		<tr>
			<td class="header" align="left">{$lang.menu.content_management} <font class="subheader">| {$lang.menu.references} | {$lang.menu.bad_words}</font></td>
		</tr>
		<tr>
			<td><div class="help_text"><span class="help_title">{$lang.default_select.help}</span>{$lang.content.badwords_help}</div></td>
		</tr>
		<tr>
			<td>			
			<form action="{$file_name}?sel=save" method="POST" id="badword_form" name="badword_form">
			<div class="table_title">{$lang.content.edit_file}:</div>
			<div  style="margin: 0px; margin-bottom: 10px;">
				<textarea rows="15" name="content" id="content" class="whole_width">{$data.content}</textarea>
			</div>
			<input class="button_3" type="button" value="{$lang.buttons.save}" onclick="document.badword_form.submit();">
			</form>			
			</td>
		</tr>
	</table>
{include file="$admingentemplates/admin_bottom.tpl"}