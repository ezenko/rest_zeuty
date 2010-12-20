{include file="$gentemplates/mail_top.tpl"}
{$mail_content.name}: {$data.login}
{$mail_content.email}: {$data.email}
{$mail_content.reason}: {$data.reason}
{$mail_content.comments}: {$data.comments}
{include file="$gentemplates/mail_bottom.tpl"}