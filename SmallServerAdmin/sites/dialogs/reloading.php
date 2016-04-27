<div id="reloading" class="modal" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h3>${Reloading}</h3>
      </div>
      <div class="modal-body">
        <table class="table">
          <tr ng-repeat="item in ReloadingItems">
            <td>{{item.Name}}</td>
            <td style="width: 24px; text-align: center;">
              <span ng-show="item.Status == 'Processing'"><i class="glyphicon glyphicon-refresh fa-spin"></i></span>
              <span ng-show="item.Status == 'Success'"><i class="glyphicon glyphicon-ok"></i></span>
              <span ng-show="item.Status == 'Error'"><i class="glyphicon glyphicon-remove"></i></span>
              <span ng-show="item.Status == 'Waiting'"><i class="glyphicon glyphicon-time"></i></span>
            </td>
          </tr>
        </table>
      </div>
      <div class="modal-footer">
        <button class="btn btn-primary" ng-disabled="Reloading" data-dismiss="modal" aria-hidden="true">${Ok}</button>
      </div>
    </div>
  </div>
</div>