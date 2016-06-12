<div id="createFolderDialog" class="modal" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true" ng-hide="CreationFolder">×</button>
        <h3>${Create folder} <span ng-show="CreationFolder"><span class="glyphicon glyphicon-refresh fa-spin"></span></span></h3>
      </div>
      <div class="modal-body">
        <form class="form-horizontal">
          <div class="form-group">
            <label class="col-xs-12 col-sm-12 col-md-12 col-lg-12">${Folder name}:</label>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
              <input type="text" class="form-control" ng-model="NewFolderName" autocomplete="off" ng-disabled="CreationFolder" />
            </div>
          </div>
          <div class="form-group">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 checkbox">
              <label>
                <input type="checkbox" ng-model="NewFolderCreateParents" /> ${create all parents}
              </label>
            </div>
          </div>
          <div class="form-group">
            <label class="col-xs-12 col-sm-12 col-md-12 col-lg-12">${Owner}:</label>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
              <input type="text" class="form-control" ng-model="NewFolderOwnerName" autocomplete="off" placeholder="${Owner name or empty for root}" ng-disabled="CreationFolder" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-xs-12 col-sm-12 col-md-12 col-lg-12">${Group}:</label>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
              <input type="text" class="form-control" ng-model="NewFolderGroupName" autocomplete="off" placeholder="${Group name or empty for owner group}" ng-disabled="CreationFolder" />
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <span ng-show="CreationFolder"><span class="glyphicon glyphicon-refresh fa-spin"></span></span>
        <button class="btn btn-primary" ng-click="CreateFolder()" ng-disabled="NewFolderName == '' || CreationFolder">${Create}</button>
        <button class="btn btn-default" data-dismiss="modal" aria-hidden="true" ng-disabled="CreationFolder">${Cancel}</button>
      </div>
    </div>
  </div>
</div>