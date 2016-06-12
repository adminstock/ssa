<li class="dropdown <?=(stripos($_SERVER['REQUEST_URI'], 'svn') !== FALSE ? 'open' : '')?>">
  <a href="/svn" class="dropdown-toggle" data-toggle="dropdown">
    <span class="fa fa-sitemap"></span> ${Menu_Subversion}
    <b class="caret"></b>
  </a>
  <ul class="dropdown-menu navmenu-nav" role="menu">
    <li><a href="/svn/repositories.php"><span class="fa fa-hdd-o"></span> ${Menu_Repositories}</a></li>
    <li><a href="/svn/users.php"><span class="fa fa-user"></span> ${Menu_Users}</a></li>
    <li><a href="/svn/groups.php"><span class="fa fa-users"></span> ${Menu_Groups}</a></li>
  </ul>
</li>