<div class="collapse navbar-collapse panel-nav">
  <nav  class="navmenu navmenu-default" role="navigation">
    <ul class="nav navmenu-nav">
      <?php
      global $config;
      $modules = explode(',', $config['modules']);
      foreach ($modules as $module) {
        if (is_file(\Nemiro\Server::MapPath('~/'.trim($module).'/menu.php')))
        {
          include \Nemiro\Server::MapPath('~/'.trim($module).'/menu.php');
        }
        else
        {
          echo '<li><a href="/'.$module.'"><span class="glyphicon glyphicon-th-large"></span> '.ucfirst(trim($module)).'</a></li>';
        }
      }
      ?>
      <li class="nav-divider"></li>
      <?php
        if (stripos($this->Parent->Culture, 'ru') !== FALSE)
        {
      ?>
        <li>
          <a href="http://www.reg.ru/?rid=76963">
            Регистрация доменов
          </a>
        </li>
        <li>
          <a href="https://www.ihor.ru/?from=112887">
            VDS хостинг
          </a>
        </li>
      <li>
        <a href="http://vk.com/club120230803">Техническая поддержка</a>
      </li>
      <?php 
        } else {
      ?>
        <li>
          <a href="http://www.reg.com/?rid=76963">Domain registration</a>
        </li>
        <li>
          <a href="https://en.ihor.ru/vds?from=112887">VDS hosting</a>
        </li>
      <?php 
        }
      ?>
      <li>
        <a href="https://github.com/adminstock">@adminstock</a>
      </li>
    </ul>
    
    <div class="hidden-xs">
      <hr />

      <?php
        if (stripos($this->Parent->Culture, 'ru') !== FALSE)
        {
      ?>
        <a class="twitter-timeline" href="https://twitter.com/AdmStockRussia" data-widget-id="725319651723513856" data-tweet-limit="1" data-chrome="nofooter noscrollbar noborders transparent">Новости AdminStock (Russia)</a>
        <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
      <?php 
        } else {
      ?>
        <a class="twitter-timeline" href="https://twitter.com/AdmStockNet" data-widget-id="725326952903614464" data-tweet-limit="1" data-chrome="nofooter noscrollbar noborders transparent">News of AdminStock</a>
        <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
      <?php 
        }
      ?>
    </div>
  </nav>
</div>