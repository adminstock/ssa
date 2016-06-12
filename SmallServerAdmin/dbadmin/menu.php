<li class="dropdown <?=(stripos($_SERVER['REQUEST_URI'], 'dbadmin') !== FALSE ? 'open' : '')?>">
  <a href="/svn" class="dropdown-toggle" data-toggle="dropdown">
    <span class="fa fa-database"></span> ${Menu_DBAdmin}
    <b class="caret"></b>
  </a>
  <?php if (isset($config['dbadmin_list']) && count($config['dbadmin_list']) > 0) {?>
  <ul class="dropdown-menu navmenu-nav" role="menu">
    <?php
      foreach($config['dbadmin_list'] as $item)
      {
    ?>
      <li><a href="/dbadmin/<?=$item['file_name']?>" target="_blank"><span class="fa fa-database"></span> <?=(!isset($item['title']) || $item['title'] == '' ? basename($item['file_name'], '.php') : $item['title'])?></a></li>
    <?php
      }
    ?>
  </ul>
  <?php
  }
  ?>
</li>