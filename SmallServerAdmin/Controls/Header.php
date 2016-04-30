<?php global $config; ?>
<nav class="navbar navbar-default">
  <div class="container">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".panel-nav">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>                        
      </button>
      <a class="navbar-brand" ng-click="SelectServer()" title="${All servers}"><span class="fa fa-server"></span></a>
      <a href="/" class="navbar-brand" title="${Dashboard}"><?=$config['ssh_host']?></a>
      <span class="navbar-brand hidden-xs">/</span>
      <span class="navbar-brand hidden-xs">
        <?=$this->Parent->Title?>
      </span>
    </div>
    <ul class="nav navbar-nav navbar-right collapse navbar-collapse panel-nav lang">
      <li class="dropdown">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
          <img src="<?=(stripos($this->Parent->Culture, 'ru') !== FALSE ? '/Content/images/ru.png' : '/Content/images/en.png')?>" alt="" title="" width="22" height="22" />
          <span class="caret"></span>
        </a>
        <ul class="dropdown-menu">
          <li><a href="?lang=ru"><img src="/Content/images/ru.png" alt="RU" title="" width="22" height="22" /></a></li>
          <li><a href="?lang=en"><img src="/Content/images/en.png" alt="EN" title="" width="22" height="22" /></a></li>
        </ul>
      </li>
      <?php
      if (isset($_SERVER['PHP_AUTH_USER']))
      {
      ?>
        <li><a href="/logout.php"><span class="glyphicon glyphicon-off"></span> ${Logout}</a></li>
      <?php
      }
      ?>
    </ul>
  </div>
</nav>