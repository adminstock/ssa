<ul class="nav nav-tabs" role="tablist">
<?php
  $i = 0;
  foreach ($this->Items as $item)
  {
?>
  <li class="<?=($i == 0 ? 'active' : '')?>"><a href="#<?=sprintf('%s_%s', $this->ID, $item->Key)?>" aria-controls="<?=sprintf('%s_%s', $this->ID, $item->Key)?>" role="tab" data-toggle="tab"><?=$item->Title?></a></li>
<?php
    $i++;
  }
?>
</ul>

<div class="tab-content">
<?php
  $i = 0;
  foreach ($this->Items as $item)
  {
?>
  <div role="tabpanel" class="<?=($i == 0 ? 'tab-pane active' : 'tab-pane')?>" id="<?=sprintf('%s_%s', $this->ID, $item->Key)?>"><?=$item->Content?></div>
<?php
    $i++;
  }
?>
</div>