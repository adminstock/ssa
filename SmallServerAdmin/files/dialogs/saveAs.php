<div id="saveAsDialog" class="modal" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3>${Save As...}</h3>
      </div>
      <div class="modal-body">
        <form class="form-horizontal">
          <div class="form-group">
            <label class="col-xs-12 col-sm-12 col-md-12 col-lg-12">${Name or full path}:</label>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
              <input type="text" class="form-control" ng-model="SaveAsPath" autocomplete="off" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-xs-12 col-sm-12 col-md-12 col-lg-12">${Owner}:</label>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
              <input type="text" class="form-control" ng-model="SaveAsOwnerName" autocomplete="off" placeholder="${Owner name or empty for root}" />
            </div>
          </div>
          <div class="form-group">
	          <label class="col-xs-12 col-sm-12 col-md-12 col-lg-12">${Group}:</label>
	          <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		          <input type="text" class="form-control" ng-model="SaveAsGroupName" autocomplete="off" placeholder="${Group name or empty for owner group}" />
	          </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button class="btn btn-primary" ng-click="Save(SaveAsPath, false, SaveAsOwnerName, SaveAsGroupName)" ng-disabled="SaveAsPath == ''">${Save}</button>
        <button class="btn btn-default" data-dismiss="modal" aria-hidden="true">${Cancel}</button>
      </div>
    </div>
  </div>
</div>