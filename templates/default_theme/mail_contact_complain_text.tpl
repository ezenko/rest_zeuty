{include file="$gentemplates/mail_top.tpl"}
{$mail_content.name}: {$data.name}
{$mail_content.email}: {$data.email}
{$mail_content.complaint_reason}: {$data.complaint_reason}
{$mail_content.your_comment}: {$data.your_comment}
{$mail_content.href}: {$data.href}
{include file="$gentemplates/mail_bottom.tpl"}