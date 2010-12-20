{include file="$gentemplates/mail_top.tpl"}
{$mail_content.hey} {$data.to_name}!

{$mail_content.text} {$data.login}.
{include file="$gentemplates/mail_bottom.tpl"}