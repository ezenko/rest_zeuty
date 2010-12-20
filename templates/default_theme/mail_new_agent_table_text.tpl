{include file="$gentemplates/mail_top.tpl"}
{$mail_content.hey} {$data.realtor_name}!

{$data.user_name} {$mail_content.text_1} {$data.company_name}.
{$mail_content.approve_link} {$data.approve_link}
{include file="$gentemplates/mail_bottom.tpl"}