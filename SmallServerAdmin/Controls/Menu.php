<div id="nav" class="collapse navbar-collapse">
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
  </nav>
</div>