<li class="dropdown <?=(stripos($_SERVER['REQUEST_URI'], 'settings') !== FALSE ? 'open' : '')?>">
  <a href="/svn" class="dropdown-toggle" data-toggle="dropdown">
    <span class="fa fa-wrench"></span> ${Menu_Settings}
    <b class="caret"></b>
  </a>
  <ul class="dropdown-menu navmenu-nav" role="menu">
    <!--li><a href="/settings/modules.php"><span class="glyphicon glyphicon-th-large"></span> ${Menu_Modules}</a></li-->
    <li><a href="/settings/update.php"><span class="glyphicon glyphicon-download-alt"></span> ${Menu_Update}</a></li>
  </ul>
</li>