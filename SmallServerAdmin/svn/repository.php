<div id="svnRep" class="modal" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3>{{Source.RelativePath == '/' ? 'root' : Source.Name}}</h3>
      </div>
      <div class="modal-body">
        <form id="svnRepEditor" class="form-horizontal">
          <fieldset>
            <div class="form-group">
              <label class="col-xs-12 col-sm-12 col-md-12 col-lg-12">${Repository name}:</label>
              <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" ng-hide="Current.RelativePath == '/'">
                <input type="text" class="form-control" ng-model="Current.Name" maxlength="50" ng-required="Current.RelativePath != '/'" autocomplete="off" />
              </div>
              <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" ng-show="Current.RelativePath == '/'">
                <input type="text" class="form-control" value="root" disabled="disabled" />
              </div>
            </div>
            <div class="form-group">
              <label class="col-xs-12 col-sm-12 col-md-12 col-lg-12">${Permissions}:</label>
              <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 form-inline">
                <input type="text" class="form-control" ng-model="PermissionsForObject" maxlength="50" autocomplete="off" placeholder="${@group or username, or * for all}" style="width: 75%" />
                <a class="btn btn-default" ng-click="AddPermission()" ng-disabled="!PermissionsForObject || PermissionsForObject == ''">
                  ${Add}
                </a>
              </div>
            </div>
            <hr />
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
              <table class="table table-hover">
                <thead>
                  <tr>
                    <th class="col-xs-9 col-sm-9 col-md-9 col-lg-9">${Group or username}</th>
                    <th class="col-xs-1 col-sm-1 col-md-1 col-lg-1 text-center">${Read}</th>
                    <th class="col-xs-1 col-sm-1 col-md-1 col-lg-1 text-center">${Write}</th>
                    <th class="col-xs-1 col-sm-1 col-md-1 col-lg-1">&nbsp;</th>
                  </tr>
                </thead>
                <tbody>
                  <tr ng-repeat="permission in Current.Permissions">
                    <td class="text-nowrap checkbox">{{permission.ObjectType == 'group' ? '@' : ''}}{{permission.ObjectName}}</td>
                    <td class="text-center"><input type="checkbox" ng-model="permission.Read" /></td>
                    <td class="text-center"><input type="checkbox" ng-model="permission.Write" /></td>
                    <td class="text-center"><a class="btn btn-danger btn-sm" ng-click="DeletePermission(permission)"><span class="glyphicon glyphicon-trash"></span></a></td>
                  </tr>
                </tbody>
                <tfoot ng-show="!Current.Permissions || Current.Permissions.length == 0">
                  <tr>
                    <td colspan="4" class="text-center">${No rules...}</td>
                  </tr>
                </tfoot>
              </table>
            </div>
          </fieldset>
        </form>
      </div>
      <div class="modal-footer">
        <button class="btn btn-primary" ng-click="Save()">${Save}</button>
        <button class="btn btn-default" data-dismiss="modal" aria-hidden="true">${Cancel}</button>
      </div>
    </div>
  </div>
</div>