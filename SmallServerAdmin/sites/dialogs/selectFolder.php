<div id="selectFolder" class="modal" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3>${Select folder} <span class="ng-hide" ng-show="Loading"><span class="glyphicon glyphicon-refresh fa-spin"></span></span></h3>
      </div>
      <div class="modal-body">
        <treecontrol class="tree-classic" tree-model="Folders" 
          options="SelectFolderOptions" 
          on-node-toggle="ToggleFolder(node, expanded)" 
          on-selection="ShowSelectedFolder(node, selected, $parentNode)"
          selected-node="SelectedFolder"
        >
          <span ng-hide="node.RenameMode">
            {{node.Name}}
            <span ng-show="SelectedFolder == node">
              <span class="glyphicon glyphicon-option-horizontal"></span>
              <a class="btn btn-default btn-xs" title="Rename" ng-click="node.NewName = node.Name; node.RenameMode = true"><span class="glyphicon glyphicon-edit"></span></a>
              <a class="btn btn-danger btn-xs" title="Delete" ng-click="ShowConfirmToDeleteFolder(node)"><span class="glyphicon glyphicon-trash"></span></a>
            </span>
          </span>
          <span class="form-inline form-group" ng-show="node.RenameMode">
            <span class="input-group">
              <input type="text" ng-model="node.NewName" class="form-control" ng-disabled="node.Loading" />
              <span class="input-group-btn">
                <button type="button" class="btn btn-success" title="Save" ng-disabled="node.NewName == '' || node.NewName == node.Name || node.Loading" ng-click="RenameFolder(node)"><span class="glyphicon glyphicon-ok"></span></button>
                <button type="button" class="btn btn-danger" title="Cancel" ng-click="node.RenameMode = false" ng-disabled="node.Loading"><span class="glyphicon glyphicon-remove"></span></button>
                <span class="btn" ng-show="node.Loading"><span class="glyphicon glyphicon-refresh fa-spin"></span></span>
              </span>
            </span>
          </span>
        </treecontrol>
      </div>
      <div class="modal-footer">
        <div class="form-group"><input type="text" class="form-control" ng-model="SelectedFolder.Path" autocomplete="off" readonly="readonly" /></div>
        <button class="btn btn-default pull-left" ng-click="ShowCreateFolder()" ng-disabled="SelectedFolder == null || SelectedFolder.Path == ''">${Create folder}</button>
        <button class="btn btn-primary" ng-click="SelectPath()" ng-disabled="SelectedFolder == null || SelectedFolder.Path == ''">${Select}</button>
        <button class="btn btn-default" data-dismiss="modal" aria-hidden="true">${Cancel}</button>
      </div>
    </div>
  </div>
</div>