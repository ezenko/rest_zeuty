        </div>
    </div>
    <div id="bottom-container">
    	<div id="bottom-image-corner"></div>
      <div id="pre-footer" class="clearfix">
        <div class="centered-content clearfix">
          <div class="header clearfix">
            <span class="title">Развлечения</span>
            <a class="prev"></a>
            <a class="next"></a>
            <a class="all">Все развлечения</a>
          </div>
          <div class="scrollable">
            <div class="items-container clearfix">
              {foreach item=r from=$entertaiments key=k}
              {*if $k mod 3 == 0 and $k != 0}
              </div><div>
              {/if*}
              <div class="item">
                <a href="/entertainment.php?id={$r.id}"><img alt="{$r.caption}" title="{$r.caption}" src="/uploades/entertaiments/{$r.image}" /></a>
              </div>
              
              {/foreach}
              
            </div>
          </div>
        </div>
      </div>
      <div id="footer">
        <div class="centered-content glass-block clearfix">
            <div id="rights">
              2011&nbsp;&copy;&nbsp;Your company name. Все права защищены.
            </div>
            <div id="f-menu">
              <ul>
                {section name=m loop=$bottom_menu}
                <li><a href="{$bottom_menu[m].href}">{$bottom_menu[m].value}</a></li>
                {/section}
              </ul>
            </div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
