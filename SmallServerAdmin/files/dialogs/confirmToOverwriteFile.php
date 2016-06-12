<div id="confirmToOverwriteFile" class="modal" role="dialog" data-not-restore="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3>${Confirm}</h3>
      </div>
      <div class="modal-body">
        ${FILES_OVERWRITE_IT}
      </div>
      <div class="modal-footer">
        <button class="btn btn-primary" ng-click="Save(SaveAsPath, true, SaveAsOwnerName)">${Overwrite}</button>
        <button class="btn btn-default" ng-click="CancelOverwrite()">${Cancel}</button>
      </div>
    </div>
  </div>
</div>