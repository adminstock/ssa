<div id="createFolder" class="modal" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3>${Create folder}</h3>
      </div>
      <div class="modal-body">
        <form class="form-horizontal">
          <div class="form-group">
            <label class="col-xs-12 col-sm-12 col-md-12 col-lg-12">${Root path}:</label>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
              <input type="text" class="form-control" ng-model="SelectedFolder.Path" autocomplete="off" readonly="readonly" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-xs-12 col-sm-12 col-md-12 col-lg-12">${Folder name}:</label>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
              <input type="text" class="form-control" ng-model="NewFolderName" autocomplete="off" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-xs-12 col-sm-12 col-md-12 col-lg-12">${Owner}:</label>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
              <input type="text" class="form-control" ng-model="NewFolderOwnerName" autocomplete="off" placeholder="Owner name or empty for root" />
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button class="btn btn-primary" ng-click="CreateFolder()" ng-disabled="NewFolderName == ''">${Create}</button>
        <button class="btn btn-default" data-dismiss="modal" aria-hidden="true">${Cancel}</button>
      </div>
    </div>
  </div>
</div>