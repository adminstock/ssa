<div id="confirmToExecuteFile" class="modal" role="dialog" data-not-restore="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3>${Confirm}</h3>
      </div>
      <div class="modal-body">
        ${Are you sure you want to execute the} <strong>{{SelectedItem.Path}}</strong>?<br /><br />
        ${It may not be safe.}<br /><br />
        ${If you really want to execute this file, you can specify additional parameters.}<br /><br />
        <form class="form-horizontal">
          <div class="form-group">
            <label class="col-xs-12 col-sm-12 col-md-12 col-lg-12">${Arguments}:</label>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
              <input type="text" class="form-control" ng-model="ExecuteArguments" placeholder="${List of parameters with which the file will be launched}" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-xs-12 col-sm-12 col-md-12 col-lg-12">${Username}:</label>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
              <input type="text" class="form-control" ng-model="ExecuteAs" autocomplete="off" placeholder="${Username or empty for root}" />
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button class="btn btn-primary" ng-click="Execute(ExecuteArguments, ExecuteAs)">${Execute}</button>
        <button class="btn btn-default" data-dismiss="modal" aria-hidden="true">${Cancel}</button>
      </div>
    </div>
  </div>
</div>