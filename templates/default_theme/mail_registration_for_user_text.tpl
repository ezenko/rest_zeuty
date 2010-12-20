{include file="$gentemplates/mail_top.tpl"}
{$mail_content.hey} {$data.fname} {$data.sname}!

{$mail_content.reg_congratulate}
{$mail_content.reg_params}:
{$mail_content.login}: {$data.login}
{$mail_content.password}: {$data.pass}
{if $data.confirm_link && $mail_content.confirmation_link}

{$mail_content.confirmation_link}: {$data.confirm_link}
{/if}
{include file="$gentemplates/mail_bottom.tpl"}