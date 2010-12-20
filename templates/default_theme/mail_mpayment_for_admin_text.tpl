{include file="$gentemplates/mail_top.tpl"}
{$mail_content.hey} {$data.admin_name}!

{$mail_content.text_0} {$data.site} {$mail_content.text_1}:
{$mail_content.name}:{$data.user_name}
{$mail_content.login}:{$data.user_login}
{$mail_content.email}:{$data.user_email}
{$mail_content.date}:{$data.date}
{$mail_content.amount}:{$data.amount}
{$mail_content.form_data}:{$data.form_data}

{$mail_content.text_2}

{include file="$gentemplates/mail_bottom.tpl"}