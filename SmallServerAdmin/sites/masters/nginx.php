<div ng-show="SelectedConfToAdd == 'Nginx'">
  <div class="form-group">
    <label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 control-label">${Domain}:</label>
    <div class="col-xs-12 col-sm-8 col-md-9 col-lg-9">
      <input type="text" class="form-control" ng-model="CreateNewNginx.Domain" maxlength="100" autocomplete="off" placeholder="${For example}: example.org" />
    </div>
  </div>
  <div class="form-group">
    <label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 control-label">${RootPathShorten}:</label>
    <div class="col-xs-12 col-sm-8 col-md-9 col-lg-9">
      <div class="input-group">
        <input type="text" class="form-control" ng-model="CreateNewNginx.SelectedPath" autocomplete="off" />
        <div class="input-group-btn"><a class="btn btn-silver" ng-click="ShowSelectFolder(CreateNewNginx)">...</a></div>
      </div>
    </div>
  </div>
  <div class="form-group">
    <label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 control-label">${Logs}:</label>
    <div class="col-xs-12 col-sm-8 col-md-9 col-lg-9">
      <div class="btn-group" uib-dropdown>
        <button id="btnNginxLog" type="button" class="btn btn-default" uib-dropdown-toggle>
          {{['${Off}', '${Error Log}', '${Access Log}', '${Error & Access Log}'][CreateNewNginx.EventLogs]}}
          <span class="caret"></span>
        </button>
        <ul uib-dropdown-menu aria-labelledby="btnNginxLog">
          <li role="menuitem">
            <a ng-click="SelectNginxEventLog(0)">${Off}</a>
          </li>
          <li role="menuitem">
            <a ng-click="SelectNginxEventLog(1)">${Error Log}</a>
          </li>
          <li role="menuitem">
            <a ng-click="SelectNginxEventLog(2)">${Access Log}</a>
          </li>
          <li role="menuitem">
            <a ng-click="SelectNginxEventLog(3)">${Error & Access Log}</a>
          </li>
        </ul>
      </div>
    </div>
  </div>
  <hr />
  <div class="form-group">
    <label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 control-label">PHP:</label>
    <div class="col-xs-12 col-sm-8 col-md-9 col-lg-9">
      <div class="btn-group">
        <label class="btn btn-default" ng-model="CreateNewNginx.PhpMode" uib-btn-radio="'Off'">${Off}</label>
        <label class="btn btn-default" ng-model="CreateNewNginx.PhpMode" uib-btn-radio="'MOD'" ng-show="Config.WebServer.indexOf('apache') != -1">Mod-PHP</label>
        <label class="btn btn-default" ng-model="CreateNewNginx.PhpMode" uib-btn-radio="'FPM'">PHP-FPM</label>
      </div>
    </div>
  </div>
  <div class="form-group">
    <label class="col-xs-6 col-sm-3 col-md-2 col-lg-2 control-label">${Processes}:</label>
    <div class="col-xs-6 col-sm-2 col-md-2 col-lg-2">
      <select class="form-control" ng-disabled="CreateNewNginx.PhpMode != 'FPM'" ng-model="CreateNewNginx.PhpFastCgiProcessCount" ng-options="i for i in [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12]">
      </select>
    </div>
    <div class="hidden-xs col-sm-7 col-md-8 col-lg-8">
      <div class="btn-group">
        <button type="button" class="btn btn-default" ng-disabled="CreateNewNginx.PhpMode != 'FPM'" ng-model="CreateNewNginx.PhpFastCgiProcessCount" uib-btn-radio="i" ng-repeat="i in [1, 2, 4, 6, 8, 10, 12]">{{i}}</button>
      </div>
    </div>
  </div>
  <div class="form-group">
    <label class="col-xs-6 col-sm-3 col-md-2 col-lg-2 control-label">${Socket}:</label>
    <div class="col-xs-6 col-sm-9 col-md-10 col-lg-10">
      <div class="btn-group">
        <label class="btn btn-default" ng-disabled="CreateNewNginx.PhpMode != 'FPM'" ng-model="CreateNewNginx.PhpSocket" uib-btn-radio="'Unix'">Unix</label>
        <label class="btn btn-default" ng-disabled="CreateNewNginx.PhpMode != 'FPM'" ng-model="CreateNewNginx.PhpSocket" uib-btn-radio="'TCP'">TCP</label>
      </div>
    </div>
  </div>
  <hr />
  <div class="form-group">
    <label class="col-xs-6 col-sm-3 col-md-2 col-lg-2 control-label">ASP.NET:</label>
    <div class="col-xs-6 col-sm-9 col-md-10 col-lg-10">
      <div class="btn-group">
        <label class="btn btn-default" ng-model="CreateNewNginx.AspNetMode" uib-btn-radio="'Off'">${Off}</label>
        <label class="btn btn-default" ng-model="CreateNewNginx.AspNetMode" uib-btn-radio="'MOD'" ng-show="Config.WebServer.indexOf('apache') != -1">Mod-Mono</label>
        <label class="btn btn-default" ng-model="CreateNewNginx.AspNetMode" uib-btn-radio="'FASTCGI'">FastCGI</label>
      </div>
    </div>
  </div>
  <div class="form-group">
    <label class="col-xs-6 col-sm-3 col-md-2 col-lg-2 control-label">${Processes}:</label>
    <div class="col-xs-6 col-sm-2 col-md-2 col-lg-2">
      <select class="form-control" ng-disabled="CreateNewNginx.AspNetMode != 'FASTCGI'" ng-model="CreateNewNginx.AspNetFastCgiProcessCount" ng-options="i for i in [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12]">
      </select>
    </div>
    <div class="hidden-xs col-sm-7 col-md-8 col-lg-8">
      <div class="btn-group">
        <button type="button" class="btn btn-default" ng-disabled="CreateNewNginx.AspNetMode != 'FASTCGI'" ng-model="CreateNewNginx.AspNetFastCgiProcessCount" uib-btn-radio="i" ng-repeat="i in [1, 2, 4, 6, 8, 10, 12]">{{i}}</button>
      </div>
    </div>
  </div>
  <div class="form-group">
    <label class="col-xs-6 col-sm-3 col-md-2 col-lg-2 control-label">${Socket}:</label>
    <div class="col-xs-6 col-sm-9 col-md-10 col-lg-10">
      <div class="btn-group">
        <label class="btn btn-default" ng-disabled="CreateNewNginx.AspNetMode != 'FASTCGI'" ng-model="CreateNewNginx.AspNetSocket" uib-btn-radio="'Unix'">Unix</label>
        <label class="btn btn-default" ng-disabled="CreateNewNginx.AspNetMode != 'FASTCGI'" ng-model="CreateNewNginx.AspNetSocket" uib-btn-radio="'TCP'">TCP</label>
      </div>
    </div>
  </div>
</div>