<div id="confirmToDeleteFolder" class="modal" role="dialog" data-not-restore="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3>${Confirm}</h3>
      </div>
      <div class="modal-body">
        ${Are you sure you want to delete the} <strong>{{SelectedFolderToDelete.Path}}</strong>?
      </div>
      <div class="modal-footer">
        <button class="btn btn-danger" ng-click="DeleteFolder()">${Delete}</button>
        <button class="btn btn-default" data-dismiss="modal" aria-hidden="true">${Cancel}</button>
      </div>
    </div>
  </div>
</div>