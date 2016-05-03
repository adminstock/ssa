<div id="confirmToDeleteItems" class="modal" role="dialog" data-not-restore="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3>${Delete}</h3>
      </div>
      <div class="modal-body">
        Are you sure you want to delete <strong>{{SelectedItems.length}}</strong> items?<br /><br />
				<span style="color:red">Recover data after deletion will not be possible.</span>
        <br /><br />
        For confirmation, enter the number of items that will be removed:<br />
        <div class="form-group">
          <input type="text" class="form-control" ng-model="ConfirmItemsToRemove" placeholder="Enter: {{SelectedItems.length}}" />
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-danger" ng-click="DeleteItems()" ng-disabled="ConfirmItemsToRemove == '' || ConfirmItemsToRemove != SelectedItems.length">${Delete}</button>
        <button class="btn btn-default" data-dismiss="modal" aria-hidden="true" ng-click="CloseConfirmItems()">${Cancel}</button>
      </div>
    </div>
  </div>
</div>