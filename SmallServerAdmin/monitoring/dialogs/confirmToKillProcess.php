<div id="confirmToKillProcess" class="modal" role="dialog" data-not-restore="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3>Confirm</h3>
      </div>
      <div class="modal-body">
        Are you sure you want to kill the process <strong>{{SelectedProcessToKill.Name}}</strong> (PID: <strong>{{SelectedProcessToKill.PID}}</strong>)?
        <br /><br />
        Signal: 
        <div class="btn-group">
          <label class="btn btn-default btn-xs" ng-model="KillSignal" uib-btn-radio="'SIGTERM'">SIGTERM</label>
          <label class="btn btn-default btn-xs" ng-model="KillSignal" uib-btn-radio="'SIGKILL'">SIGKILL (-9)</label>
          <label class="btn btn-default btn-xs" ng-model="KillSignal" uib-btn-radio="'SIGSTOP'">SIGSTOP</label>
          <label class="btn btn-default btn-xs" ng-model="KillSignal" uib-btn-radio="'SIGTSTP'">SIGTSTP</label>
          <label class="btn btn-default btn-xs" ng-model="KillSignal" uib-btn-radio="'SIGINT'">SIGINT</label>
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-danger pull-left" ng-click="KillProcess()">${Yes, kill the process}</button>
        <button class="btn btn-default pull-left" data-dismiss="modal" aria-hidden="true">${Cancel}</button>
      </div>
    </div>
  </div>
</div>