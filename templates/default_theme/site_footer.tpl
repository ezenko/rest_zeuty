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
              <div class="item">
                <img alt="" src="img/fake/img1.jpg" />
              </div>
              <div class="item">
                <img alt="" src="img/fake/img1.jpg" />
              </div>
              <div class="item">
                <img alt="" src="img/fake/img1.jpg" />
              </div>
              <div class="item">
                <img alt="" src="img/fake/img1.jpg" />
              </div>
              <div class="item">
                <img alt="" src="img/fake/img1.jpg" />
              </div>
              <div class="item">
                <img alt="" src="img/fake/img1.jpg" />
              </div>
              <div class="item">
                <img alt="" src="img/fake/img1.jpg" />
              </div>
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
