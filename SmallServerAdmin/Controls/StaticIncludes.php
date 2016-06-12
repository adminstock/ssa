<link rel="stylesheet" href="/Content/compiled.min.css?v=<?=filemtime(\Nemiro\Server::MapPath('~/Content/compiled.min.css'))?>" />
<?php
  // include resources for current culture
  if ($this->Parent->Culture != PAGE_DEFAULT_CULTURE && file_exists(\Nemiro\Server::MapPath('~/Content/local/'.$this->Parent->Culture.'.min.js')))
  {
    echo '<script src="/Content/local/'.$this->Parent->Culture.'.min.js?v='.filemtime(\Nemiro\Server::MapPath('~/Content/local/'.$this->Parent->Culture.'.min.js')).'" type="text/javascript"></script>';
  }
?>
<script src="/Content/compiled.js?v=<?=filemtime(\Nemiro\Server::MapPath('~/Content/compiled.js'))?>" type="text/javascript"></script>