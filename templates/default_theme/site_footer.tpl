        <div id="right-container">
        	<div id="tourist" class="clearfix">
            <h2>Активный отдых</h2>
            <ul>
              <li><a class="folder-item">Серфинг</a></li>
            	<li><a class="folder-item">Дайвинг</a></li>
            	<li><a class="folder-item">Горнолыжный отдых</a></li>
            	<li><a class="folder-item">Лыжные походы</a></li>
            	<li><a class="folder-item">Рыбалка</a></li>
            	<li><a class="folder-item">Фотоохота</a></li>
            	<li><a class="folder-item">Прыжки с парашутом</a></li>
            	<li><a class="folder-item">Горный туризм</a></li>
            	<li><a class="folder-item">Водный туризм</a></li>
            	<li><a class="folder-item">Эскурсионные туры</a></li>
            	<li><a class="folder-item">Конный туризм</a></li>
            	<li><a class="folder-item">Вело туризм</a></li>
            	<li><a class="folder-item">Джипинг</a></li>
              <li><a class="folder-item">Спелео туризм</a></li>
              <li><a class="folder-item">Скалолазание</a></li>
              <li><a class="folder-item">Туристические походы</a></li>
              <li><a class="folder-item">Места боевой славы</a></li>
            </ul>
          </div>
          <div id="hot-tours" class="clearfix">
          	<h2 class="gold">Горящие предложения</h2>
            <div class="clearfix scrollable">
            	<a class="prev"></a>
              <div class="container">
              	<div class="item">
                	<img alt="" src="img/fake/img1.jpg" />
                  <span class="title">Сочи</span>
                  <span class="price">Антигуа: от 670$</span>
                  <a class="order">Заказать</a>
                </div>
                <div class="item">
                	<img alt="" src="img/fake/img1.jpg" />
                  <span class="title">Сочи</span>
                  <span class="price">Антигуа: от 670$</span>
                  <a class="order">Заказать</a>
                </div>
              </div>
              <a class="next"></a>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div id="bottom-container">
      <div id="pre-footer" class="clearfix">
        <div class="centered-content clearfix">
          <div class="header clearfix">
            <span class="title">Развлечения</span>
            <a class="all">Все развлечения</a>
          </div>
          <div class="scrollable">
            <div class="container clearfix">
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