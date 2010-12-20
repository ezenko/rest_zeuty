{include file="$gentemplates/mail_top.tpl"}
{$mail_content.hey} {$data.fname} {$data.sname}!

{$mail_content.text_1}
{$mail_content.login}: {$data.login}
{$mail_content.password}: {$data.pass}
{include file="$gentemplates/mail_bottom.tpl"}