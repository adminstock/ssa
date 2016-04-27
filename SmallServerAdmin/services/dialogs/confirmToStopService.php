<div id="confirmToStopService" class="modal" role="dialog" data-not-restore="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3>Confirm</h3>
      </div>
      <div class="modal-body">
        Are you sure you want to stop the service <strong>{{SelectedServiceToStop.Name}}</strong>?
        <div ng-show="SelectedServiceToStop.Name.toLowerCase() == 'ssh'">
          <br /><br />
          <div class="alert alert-danger">
            Stop the <strong>SSH</strong> service will result in a loss of control over the server.<br />
            Control Panel will stop working.
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-danger pull-left" ng-click="StopService()">${Yes, stop the service}</button>
        <button class="btn btn-default pull-left" data-dismiss="modal" aria-hidden="true">${Cancel}</button>
      </div>
    </div>
  </div>
</div>