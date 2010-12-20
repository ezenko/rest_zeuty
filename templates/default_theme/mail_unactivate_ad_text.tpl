{include file="$gentemplates/mail_top.tpl"}
{$mail_content.hey} {$data.fname} {$data.sname}!

{$mail_content.unactivate_ad}
{$mail_content.for_activate} {$server}{$site_root}/rentals.php?sel=my_ad&id_ad={$data.id_ad}
{include file="$gentemplates/mail_bottom.tpl"}