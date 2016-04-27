<?php
  global $config;
?>
<input type="hidden" id="config" value="<?=str_replace('"', '&quot;', json_encode($config['client']))?>" />