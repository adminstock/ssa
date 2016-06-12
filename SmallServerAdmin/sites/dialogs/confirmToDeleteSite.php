<div id="confirmToDeleteSite" class="modal" role="dialog" data-not-restore="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3>${Confirm}</h3>
      </div>
      <div class="modal-body">
        ${You are about to delete the} <strong>{{SelectedItemToRemove}}</strong>.<br />
        ${SITES_CONFIRM_SITE_DELETE}<br />
        <div class="form-group">
          <input type="text" class="form-control" ng-model="ConfirmNameToRemove" autocomplete="off" />
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-danger pull-left" ng-click="Delete()">${Delete}</button>
        <button class="btn btn-default pull-left" data-dismiss="modal" aria-hidden="true">${Cancel}</button>
      </div>
    </div>
  </div>
</div>