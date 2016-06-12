<div id="confirmToMoveItems" class="modal" role="dialog" data-not-restore="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3>${Move}</h3>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <label>${Target path}:</label>
          <input type="text" ng-model="MoveTargetPath" class="form-control" />
        </div>
        <div class="form-group">
          <div class="btn-group">
            <label class="btn btn-default" ng-model="MoveItemsMode" uib-btn-radio="'Force'">${overwrite existing}</label>
            <label class="btn btn-default" ng-model="MoveItemsMode" uib-btn-radio="'NoClobber'">${skip existing}</label>
          </div>
        </div>
        <div class="checkbox">
          <label>
            <input type="checkbox" ng-model="MoveItemsBackup"> ${make a backup copy of the target files}
          </label>
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-primary" ng-click="MoveItems()">${Execute}</button>
        <button class="btn btn-default" data-dismiss="modal" aria-hidden="true" ng-click="CloseConfirmItems()">${Cancel}</button>
      </div>
    </div>
  </div>
</div>