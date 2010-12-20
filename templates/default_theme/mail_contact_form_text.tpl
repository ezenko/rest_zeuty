{include file="$gentemplates/mail_top.tpl"}
{$mail_content.message_subject}: {$data.m_subject}
{$mail_content.message_text}: {$data.body}
{include file="$gentemplates/mail_bottom.tpl"}