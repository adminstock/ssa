<?php global $info; ?>
<footer class="text-center small">
  <hr />
    SmallServerAdmin v<?=(file_exists(\Nemiro\Server::MapPath('~/.version')) ? file_get_contents(\Nemiro\Server::MapPath('~/.version')) : '')?>
  <br />
</footer>