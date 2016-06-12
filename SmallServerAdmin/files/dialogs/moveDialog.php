<div id="moveDialog" class="modal" role="dialog" data-not-restore="false">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true" ng-hide="Moving">×</button>
        <h3>${Selected items}</h3>
      </div>
      <div class="modal-body">
        <table class="table table-hover cell-align-middle" ng-show="SelectedItems.length > 0" ng-cloak>
          <thead>
            <tr>
              <th class="col-xs-11 col-sm-11 col-md-11 col-lg-11">${Path}</th>
              <th class="col-xs-1 col-sm-1 col-md-1 col-lg-1">&nbsp;</th>
            </tr>
          </thead>
          <tbody>
            <tr ng-repeat="item in SelectedItems" ng-class="{'success': SelectedItemsCompleted[$index] == 'Success', 'danger': SelectedItemsCompleted[$index] == 'Fail', 'warning': Moving && (!SelectedItemsCompleted[$index] || SelectedItemsCompleted[$index] == '') }">
              <td class="col-xs-11 col-sm-11 col-md-11 col-lg-11">
                <input type="text" class="form-control" ng-model="item" readonly="readonly" />
              </td>
              <td class="col-xs-1 col-sm-1 col-md-1 col-lg-1 text-center">
                <button type="button" class="btn btn-default col-xs-12 col-sm-12 col-md-12 col-lg-12" ng-click="Select(item)" ng-hide="Moving || Moved"><span class="glyphicon glyphicon-minus"></span></button>
                <span ng-show="Moving && (!SelectedItemsCompleted[$index] || SelectedItemsCompleted[$index] == '')" class="btn btn-default">
                  <span class="glyphicon glyphicon-minus fa-spin fa-1x fa-fw"></span>
                </span>
                <span ng-show="SelectedItemsCompleted[$index] == 'Success'">
                  <span class="glyphicon glyphicon-ok"></span>
                </span>
                <span ng-show="SelectedItemsCompleted[$index] == 'Fail'">
                  <span class="glyphicon glyphicon-remove"></span>
                </span>
              </td>
            </tr>
          </tbody>
        </table>
        <div ng-show="SelectedItems.length <= 0" class="text-center">
          <br />
          ${No selected items...}
          <br /><br />
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-success pull-left" ng-click="ConfirmToMoveItems()" ng-disabled="SelectedItems.length <= 0 || Moving || Moved">
          <span class="glyphicon glyphicon-transfer"></span>
          ${Move}
        </button>
        <button class="btn btn-primary pull-left" ng-click="ConfirmToCopyItems()" ng-disabled="SelectedItems.length <= 0 || Moving || Moved">
          <span class="glyphicon glyphicon-copy"></span>
          ${Copy}
        </button>
        <button class="btn btn-danger pull-left" ng-click="ConfirmToDeleteItems()" ng-disabled="SelectedItems.length <= 0 || Moving || Moved">
          <span class="glyphicon glyphicon-trash"></span>
          ${Delete}
        </button>
        <button class="btn btn-default" data-dismiss="modal" aria-hidden="true" ng-disabled="Moving">${Cancel}</button>
      </div>
    </div>
  </div>
</div>
