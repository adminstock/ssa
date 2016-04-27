<div id="propertiesDialog" class="modal" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true" ng-show="!FileInfo.Saving">×</button>
        <h3>${Properties}: {{FileInfoSource.Name}}</h3>
      </div>
      <div class="modal-body">
        <form class="form-horizontal">
          <div class="form-group">
            <label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 control-label">${Path}:</label>
            <div class="col-xs-12 col-sm-9 col-md-10 col-lg-10">
              <input type="text" class="form-control" ng-model="FileInfo.Path" autocomplete="off" readonly="readonly" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 control-label">${Size}:</label>
            <div class="col-xs-12 col-sm-9 col-md-10 col-lg-10">
              <p class="form-control-static">
                <span ng-show="FileInfo.Size < 1048576">{{FileInfo.Size / 1024 | CurrencyFormat : { decimalDigits: 2 } }} ${KiB}</span>
                <span ng-show="FileInfo.Size >= 1048576 && FileInfo.Size < 1073741824">{{FileInfo.Size / 1024 / 1024 | CurrencyFormat : { decimalDigits: 2 } }} ${MiB}</span>
                <span ng-show="FileInfo.Size >= 1073741824">{{FileInfo.Size / 1024 / 1024 / 1024 | CurrencyFormat : { decimalDigits: 2 } }} ${GiB}</span>
                ({{FileInfo.Size | CurrencyFormat }} ${bytes})
              </p>
            </div>
          </div>
          <div class="form-group hidden-xs hidden-sm">
            <label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 control-label">${Created}:</label>
            <div class="col-xs-12 col-sm-9 col-md-10 col-lg-10">
              <p class="form-control-static" ng-show="FileInfo.DateCreated != null || FileInfo.DateLastModified != null">{{FileInfo.DateCreated != null ? FileInfo.DateCreated : (FileInfo.DateLastModified != null ? FileInfo.DateLastModified : null) | date : 'yyyy-MM-dd HH:mm:ss'}}</p>
              <p class="form-control-static" ng-show="FileInfo.DateCreated == null && FileInfo.DateLastModified == null">-</p>
            </div>
          </div>
          <div class="form-group hidden-xs hidden-sm">
            <label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 control-label">${Modified}:</label>
            <div class="col-xs-12 col-sm-9 col-md-10 col-lg-10">
              <p class="form-control-static" ng-show="FileInfo.DateLastModified != null">{{FileInfo.DateLastModified != null ? FileInfo.DateLastModified : null | date : 'yyyy-MM-dd HH:mm:ss'}}</p>
              <p class="form-control-static" ng-show="FileInfo.DateLastModified == null">-</p>
            </div>
          </div>
          <div class="form-group hidden-xs hidden-sm">
            <label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 control-label">${Accessed}:</label>
            <div class="col-xs-12 col-sm-9 col-md-10 col-lg-10">
              <p class="form-control-static" ng-show="FileInfo.DateLastAccess != null">{{FileInfo.DateLastAccess != null ? FileInfo.DateLastAccess : null | date : 'yyyy-MM-dd HH:mm:ss'}}</p>
              <p class="form-control-static" ng-show="FileInfo.DateLastAccess == null">-</p>
            </div>
          </div>
          <div class="form-group">
            <label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 control-label">${Name}:</label>
            <div class="col-xs-12 col-sm-9 col-md-10 col-lg-10">
              <input type="text" class="form-control" ng-model="FileInfo.Name" autocomplete="off" ng-disabled="FileInf.Saving" placeholder="Name: {{FileInfoSource.Name}}" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 control-label">${Owner}:</label>
            <div class="col-xs-12 col-sm-9 col-md-10 col-lg-10">
              <input type="text" class="form-control" ng-model="FileInfo.Username" autocomplete="off" placeholder="Owner name: {{FileInfoSource.Username}}" ng-disabled="FileInf.Saving" ng-change="FileInfoChanged = true" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 control-label">${Group}:</label>
            <div class="col-xs-12 col-sm-9 col-md-10 col-lg-10">
              <input type="text" class="form-control" ng-model="FileInfo.GroupName" autocomplete="off" placeholder="Group name: {{FileInfoSource.GroupName}}" ng-disabled="FileInf.Saving" ng-change="FileInfoChanged = true" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 control-label">
              ${Permissions}:
            </label>
            <div class="col-xs-12 col-sm-9 col-md-10 col-lg-10">
              <div class="input-group btn-group">
                <div class="input-group-addon" style="min-width: 100px;">${owner}</div>
                <label class="btn btn-default btn-sm" ng-change="ChangePermissions(400)" ng-model="FileInfo.Permissions400" uib-btn-checkbox ng-disabled="FileInf.Saving" ng-click="FileInfoChanged = true">
                  <span class="fa fa-check-square-o" ng-show="FileInfo.Permissions400"></span>
                  <span class="fa fa-square-o" ng-hide="FileInfo.Permissions400"></span>
                  ${read}
                </label>
                <label class="btn btn-default btn-sm" ng-change="ChangePermissions(200)" ng-model="FileInfo.Permissions200" uib-btn-checkbox ng-disabled="FileInf.Saving" ng-click="FileInfoChanged = true">
                  <span class="fa fa-check-square-o" ng-show="FileInfo.Permissions200"></span>
                  <span class="fa fa-square-o" ng-hide="FileInfo.Permissions200"></span>
                  ${write}
                </label>
                <label class="btn btn-default btn-sm" ng-change="ChangePermissions(100)" ng-model="FileInfo.Permissions100" uib-btn-checkbox ng-disabled="FileInf.Saving" ng-click="FileInfoChanged = true">
                  <span class="fa fa-check-square-o" ng-show="FileInfo.Permissions100"></span>
                  <span class="fa fa-square-o" ng-hide="FileInfo.Permissions100"></span>
                  ${execution}
                </label>
                &nbsp;=&nbsp;
                <span class="badge">{{FileInfo.Permissions}}</span>
              </div>
            </div>
            <div class="col-sm-offset-3 col-md-offset-2 col-lg-offset-2 col-xs-12 col-sm-9 col-md-10 col-lg-10">
              <div class="input-group btn-group">
                <div class="input-group-addon" style="min-width: 100px;">${group}</div>
                <label class="btn btn-default btn-sm" ng-change="ChangePermissions(40)" ng-model="FileInfo.Permissions40" uib-btn-checkbox ng-disabled="FileInf.Saving" ng-click="FileInfoChanged = true">
                  <span class="fa fa-check-square-o" ng-show="FileInfo.Permissions40"></span>
                  <span class="fa fa-square-o" ng-hide="FileInfo.Permissions40"></span>
                  ${read}
                </label>
                <label class="btn btn-default btn-sm" ng-change="ChangePermissions(20)" ng-model="FileInfo.Permissions20" uib-btn-checkbox ng-disabled="FileInf.Saving" ng-click="FileInfoChanged = true">
                  <span class="fa fa-check-square-o" ng-show="FileInfo.Permissions20"></span>
                  <span class="fa fa-square-o" ng-hide="FileInfo.Permissions20"></span>
                  ${write}
                </label>
                <label class="btn btn-default btn-sm" ng-change="ChangePermissions(10)" ng-model="FileInfo.Permissions10" uib-btn-checkbox ng-disabled="FileInf.Saving" ng-click="FileInfoChanged = true">
                  <span class="fa fa-check-square-o" ng-show="FileInfo.Permissions10"></span>
                  <span class="fa fa-square-o" ng-hide="FileInfo.Permissions10"></span>
                  ${execution}
                </label>
              </div>
            </div>
            <div class="col-sm-offset-3 col-md-offset-2 col-lg-offset-2 col-xs-12 col-sm-9 col-md-10 col-lg-10">
              <div class="input-group btn-group">
                <div class="input-group-addon" style="min-width: 100px;">${other}</div>
                <label class="btn btn-default btn-sm" ng-change="ChangePermissions(4)" ng-model="FileInfo.Permissions4" uib-btn-checkbox ng-disabled="FileInf.Saving" ng-click="FileInfoChanged = true">
                  <span class="fa fa-check-square-o" ng-show="FileInfo.Permissions4"></span>
                  <span class="fa fa-square-o" ng-hide="FileInfo.Permissions4"></span>
                  ${read}
                </label>
                <label class="btn btn-default btn-sm" ng-change="ChangePermissions(2)" ng-model="FileInfo.Permissions2" uib-btn-checkbox ng-disabled="FileInf.Saving" ng-click="FileInfoChanged = true">
                  <span class="fa fa-check-square-o" ng-show="FileInfo.Permissions2"></span>
                  <span class="fa fa-square-o" ng-hide="FileInfo.Permissions2"></span>
                  ${write}
                </label>
                <label class="btn btn-default btn-sm" ng-change="ChangePermissions(1)" ng-model="FileInfo.Permissions1" uib-btn-checkbox ng-disabled="FileInf.Saving" ng-click="FileInfoChanged = true">
                  <span class="fa fa-check-square-o" ng-show="FileInfo.Permissions1"></span>
                  <span class="fa fa-square-o" ng-hide="FileInfo.Permissions1"></span>
                  ${execution}
                </label>
              </div>
            </div>
          </div>
          <div class="form-group">
            <label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 control-label">&nbsp;</label>
            <div class="col-xs-12 col-sm-9 col-md-10 col-lg-10 checkbox">
              <label>
                <!-- || (FileInfoSource.Permissions == FileInfo.Permissions && FileInfoSource.Username == FileInfo.Username && FileInfoSource.GroupName == FileInfo.GroupName)-->
                <input type="checkbox" ng-model="UpdateRecursive" ng-disabled="FileInfo.Type != 'Folder'" /> ${recursive for all child files and folders}
              </label>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <span ng-show="FileInfo.Saving"><span class="glyphicon glyphicon-refresh fa-spin"></span></span>
        <button class="btn btn-default" ng-click="SaveProperties()" ng-disabled="FileInfo.Saving || FileInfo.Name == '' || FileInfo.Username == '' || FileInfo.GroupName == '' || (FileInfo.Type == 'Folder' && !FileInfoChanged) || (FileInfo.Type != 'Folder' && FileInfoSource.Name == FileInfo.Name && FileInfoSource.Permissions == FileInfo.Permissions && FileInfoSource.Username == FileInfo.Username && FileInfoSource.GroupName == FileInfo.GroupName)">${Apply}</button>
        <button class="btn btn-default" data-dismiss="modal" aria-hidden="true" ng-disabled="FileInf.Saving">${Cancel}</button>
      </div>
    </div>
  </div>
</div>