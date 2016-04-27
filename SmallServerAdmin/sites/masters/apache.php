<div ng-show="SelectedConfToAdd == 'Apache'">
  <div class="form-group">
    <label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 control-label">${Domain}:</label>
    <div class="col-xs-12 col-sm-8 col-md-9 col-lg-9">
      <input type="text" class="form-control" ng-model="CreateNewApache.Domain" maxlength="100" autocomplete="off" placeholder="For example: example.org" />
    </div>
  </div>
  <div class="form-group">
    <label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 control-label">${RootPathShorten}:</label>
    <div class="col-xs-12 col-sm-8 col-md-9 col-lg-9">
      <div class="input-group">
        <input type="text" class="form-control" ng-model="CreateNewApache.SelectedPath" autocomplete="off" />
        <div class="input-group-btn"><a class="btn btn-silver" ng-click="ShowSelectFolder(CreateNewApache)">...</a></div>
      </div>
    </div>
  </div>
  <div class="form-group">
    <label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 control-label">${Logs}:</label>
    <div class="col-xs-12 col-sm-8 col-md-9 col-lg-9">
      <div class="btn-group" uib-dropdown>
        <button id="btnApacheLog" type="button" class="btn btn-default" uib-dropdown-toggle>
          {{['${Off}', '${Error Log}', '${Access Log}', '${Error & Access Log}'][CreateNewApache.EventLogs]}}
          <span class="caret"></span>
        </button>
        <ul uib-dropdown-menu aria-labelledby="btnApacheLog">
          <li role="menuitem">
            <a ng-click="SelectApacheEventLog(0)">${Off}</a>
          </li>
          <li role="menuitem">
            <a ng-click="SelectApacheEventLog(1)">${Error Log}</a>
          </li>
          <li role="menuitem">
            <a ng-click="SelectApacheEventLog(2)">${Access Log}</a>
          </li>
          <li role="menuitem">
            <a ng-click="SelectApacheEventLog(3)">${Error & Access Log}</a>
          </li>
        </ul>
      </div>
    </div>
  </div>
  <hr />
  <div class="form-group">
    <label class="col-xs-6 col-sm-3 col-md-2 col-lg-2 control-label">ASP.NET:</label>
    <div class="col-xs-6 col-sm-9 col-md-10 col-lg-10">
      <div class="btn-group">
        <label class="btn btn-default" ng-model="CreateNewApache.AspNetVersion" uib-btn-radio="'Off'">${Off}</label>
        <label class="btn btn-default" ng-model="CreateNewApache.AspNetVersion" uib-btn-radio="'4.5'">4.5</label>
        <label class="btn btn-default" ng-model="CreateNewApache.AspNetVersion" uib-btn-radio="'4.0'">4.0</label>
        <label class="btn btn-default" ng-model="CreateNewApache.AspNetVersion" uib-btn-radio="'2.0'">2.0</label>
        <label class="btn btn-default" ng-model="CreateNewApache.AspNetVersion" uib-btn-radio="'1.0'">1.0</label>
      </div>
    </div>
  </div>
  <div class="form-group">
    <label class="col-xs-6 col-sm-3 col-md-2 col-lg-2 control-label">MONO_IOMAP:</label>
    <div class="col-xs-6 col-sm-9 col-md-10 col-lg-10">
      <input type="checkbox" ng-model="CreateNewApache.AspNetIOMapForAll" bs-switch switch-on-text="${On}" switch-off-text="${Off}" switch-on-color="success" switch-off-color="danger" />
    </div>
  </div>
  <div class="form-group">
    <label class="col-xs-6 col-sm-3 col-md-2 col-lg-2 control-label">${Debug}:</label>
    <div class="col-xs-6 col-sm-9 col-md-10 col-lg-10">
      <input type="checkbox" ng-model="CreateNewApache.AspNetDebug" bs-switch switch-on-text="${On}" switch-off-text="${Off}" switch-on-color="success" switch-off-color="danger" />
    </div>
  </div>
  <div class="form-group">
    <label class="col-xs-6 col-sm-3 col-md-2 col-lg-2 control-label">${Mono panel}:</label>
    <div class="col-xs-6 col-sm-9 col-md-10 col-lg-10">
      <input type="checkbox" ng-model="CreateNewApache.MonoCtrl" bs-switch switch-on-text="${On}" switch-off-text="${Off}" switch-on-color="success" switch-off-color="danger" />
    </div>
  </div>
  <hr />
  <div class="form-group">
    <label class="col-xs-6 col-sm-3 col-md-2 col-lg-2 control-label">WebDav SVN:</label>
    <div class="col-xs-6 col-sm-9 col-md-10 col-lg-10">
      <input type="checkbox" ng-model="CreateNewApache.WebDavSvn" bs-switch switch-on-text="${On}" switch-off-text="${Off}" switch-on-color="success" switch-off-color="danger" />
    </div>
  </div>
</div>