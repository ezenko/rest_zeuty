{include file="$gentemplates/mail_top.tpl"}
{$mail_content.contact_name}: {$data.name}
{$mail_content.contact_email}: {$data.email}
{$mail_content.message_text}: {$data.body}
{$mail_content.contact_href}: {$data.href}
{include file="$gentemplates/mail_bottom.tpl"}