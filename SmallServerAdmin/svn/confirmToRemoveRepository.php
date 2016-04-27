<div id="confirmSvnRepositoryRemove" class="modal" role="dialog" data-not-restore="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3>Confirm</h3>
      </div>
      <div class="modal-body">
        You are about to delete repository <strong>{{SelectedItemToRemove}}</strong>.<br />
        Recover data after deletion will not be possible.<br />
        For confirmation, enter repository name, which should be removed:<br />
        <div class="form-group">
          <input type="text" class="form-control" ng-model="ConfirmNameToRemove" maxlength="200" />
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-danger pull-left" ng-click="Delete()">${Delete}</button>
        <button class="btn btn-default pull-left" data-dismiss="modal" aria-hidden="true">${Cancel}</button>
      </div>
    </div>
  </div>
</div>