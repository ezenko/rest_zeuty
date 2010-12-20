{include file="$gentemplates/mail_top.tpl"}
{$mail_content.hey} {$data.agent_name}!

{$mail_content.text_0} {$data.company_name} {$mail_content.text_1}.
{$mail_content.approve_link} {$data.approve_link}
{include file="$gentemplates/mail_bottom.tpl"}