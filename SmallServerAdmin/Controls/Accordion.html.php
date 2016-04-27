<div class="panel-group" id="<?=$this->ID?>" role="tablist" aria-multiselectable="true">
  <?php
  foreach ($this->Items as $item)
  {
  ?>
  <div class="panel panel-default">
    <div class="panel-heading" role="tab" id="<?=sprintf('heading_%s_%s', $this->ID, $item->Key)?>">
      <h4 class="panel-title">
        <a role="button" data-toggle="collapse" data-parent="#<?=$this->ID?>" href="#<?=sprintf('%s_%s', $this->ID, $item->Key)?>" aria-expanded="true" aria-controls="<?=sprintf('%s_%s', $this->ID, $item->Key)?>">
          <?=$item->Title?>
        </a>
      </h4>
    </div>
    <div id="<?=sprintf('%s_%s', $this->ID, $item->Key)?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="<?=sprintf('heading_%s_%s', $this->ID, $item->Key)?>">
      <div class="panel-body">
        <?=$item->Content?>
      </div>
    </div>
  </div>
  <?php } ?>
</div>