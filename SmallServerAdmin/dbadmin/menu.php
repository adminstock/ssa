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
        if (!isset($item['url'])) 
        {
          $item['url'] = $item['file_name'];
        }

        if (!\Nemiro\Text::StartsWith($item['url'], '/') && !\Nemiro\Text::StartsWith($item['url'], 'http'))
        {
          $item['url'] = '/'.$item['url'];
        }
        else if (\Nemiro\Text::StartsWith($item['url'], '\\'))
        {
          $item['url'] = '/'.substr($item['url'], 1);
        }
    ?>
      <li><a href="<?=$item['url']?>" target="_blank"><span class="fa fa-database"></span> <?=(!isset($item['title']) || $item['title'] == '' ? basename($item['url'], '.php') : $item['title'])?></a></li>
    <?php
      }
    ?>
  </ul>
  <?php
  }
  ?>
</li>