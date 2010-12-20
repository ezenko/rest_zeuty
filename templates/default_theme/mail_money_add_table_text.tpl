{include file="$gentemplates/mail_top.tpl"}
{$mail_content.hey} {$data.name}!

{$mail_content.account_add} {$data.add_on_account} {if $cur_symbol}{$cur_symbol}{else}{$data.cur}{/if}. {$mail_content.account_total} {$data.account} {if $cur_symbol}{$cur_symbol}{else}{$data.cur}{/if}.
{include file="$gentemplates/mail_bottom.tpl"}