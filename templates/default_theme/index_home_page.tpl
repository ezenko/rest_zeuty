{include file="$gentemplates/site_top.tpl"}
<div id="middle-container">
  <h2>Новости</h2>
  {foreach from=$frontpage item=page}
    <div class="frontpage">
      <div class="fp-title">{$page.caption}</div>
      <div class="content">
        {if $page.image}
          <img class="fp-image" style="border: 3px solid white;" src="/uploades/frontpage/{$page.image}" />
        {/if}
        {$page.content}
      </div>
      <a href="{$page.link}">Подробнее</a>
    </div>
  {/foreach}
</div>
{include file="$gentemplates/site_footer.tpl"}