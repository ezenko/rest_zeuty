{include file="$gentemplates/mail_top.tpl"}
{$mail_content.hey} {$data.fname} {$data.sname}!

{$mail_content.text_1}

{section name=p loop=$data.search_result}
{$mail_content.view}

{$data.search_result[p].login}
{if $data.search_result[p].id_type eq 1}{$mail_content.need_realty}{elseif $data.search_result[p].id_type eq 2}{$mail_content.have_realty}{elseif $data.search_result[p].id_type eq 3}{$mail_content.buy_realty}{elseif $data.search_result[p].id_type eq 4}{$mail_content.sell_realty}{/if} {$data.search_result[p].realty_type}
{if $data.search_result[p].country_name || $data.search_result[p].region_name || $data.search_result[p].city_name}
{$data.search_result[p].country_name}{if $data.search_result[p].region_name}, {$data.search_result[p].region_name}{/if}{if $data.search_result[p].city_name}, {$data.search_result[p].city_name}{/if}
{/if}
{if $data.search_result[p].id_type eq 1 || $data.search_result[p].id_type eq 2}
{$mail_content.month_payment_in_line}{else}{$mail_content.price}{/if}: {if $data.search_result[p].id_type eq 1 || $data.search_result[p].id_type eq 3}{$mail_content.from} {$data.search_result[p].min_payment} {$mail_content.upto} {$data.search_result[p].max_payment}{else}{$data.search_result[p].min_payment}{/if} {$cur}


{/section}
{include file="$gentemplates/mail_bottom.tpl"}