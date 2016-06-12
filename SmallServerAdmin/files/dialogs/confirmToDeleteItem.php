<div id="confirmToDeleteItem" class="modal" role="dialog" data-not-restore="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3>${Confirm}</h3>
      </div>
      <div class="modal-body">
        ${Are you sure you want to delete the} <strong>{{SelectedItemToDelete.Path}}</strong>?<br /><br />
        <div ng-show="SelectedItemToDelete.Type == 'Folder'">
          ${All child files and folders will be deleted.}
        </div>
        ${Recover data after deletion will not be possible.}
        <br /><br />
        ${FILES_CONFIRM_FILE_DELETETION}<br />
        <div class="form-group">
          <input type="text" class="form-control" ng-model="ConfirmItemNameToRemove" placeholder="${Enter}: {{SelectedItemToDelete.Name}}" />
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-danger" ng-click="Delete()" ng-disabled="ConfirmItemNameToRemove == '' || ConfirmItemNameToRemove != SelectedItemToDelete.Name">${Delete}</button>
        <button class="btn btn-default" data-dismiss="modal" aria-hidden="true">${Cancel}</button>
      </div>
    </div>
  </div>
</div>