<div id="confirmSvnUserRemove" class="modal" role="dialog" data-not-restore="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3>${Confirm}</h3>
      </div>
      <div class="modal-body">
        ${Do you want to delete the} <strong>{{SelectedUserToRemove}}</strong>?<br /><br />
        ${Repositories will not be affected.}
      </div>
      <div class="modal-footer">
        <button class="btn btn-danger pull-left" ng-click="DeleteUser()">${Delete}</button>
        <button class="btn btn-default pull-left" data-dismiss="modal" aria-hidden="true">${Cancel}</button>
      </div>
    </div>
  </div>
</div>