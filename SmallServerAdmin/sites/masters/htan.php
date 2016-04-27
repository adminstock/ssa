<div ng-show="SelectedConfToAdd == 'HTAN'">
  <div class="alert alert-danger" ng-show="AvailableLevels.indexOf('Nginx') != -1">
    Recommended to first create a configuration for <a ng-click="SelectConfToAdd('Nginx')">Nginx</a>.
  </div>
  <div class="form-group">
    <label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 control-label">${Domain}:</label>
    <div class="col-xs-12 col-sm-8 col-md-9 col-lg-9">
      <input type="text" class="form-control" ng-model="CreateNewHTAN.Domain" maxlength="100" autocomplete="off" placeholder="For example: example.org" />
    </div>
  </div>
  <div class="form-group">
    <label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 control-label">${RootPathShorten}:</label>
    <div class="col-xs-12 col-sm-8 col-md-9 col-lg-9">
      <div class="input-group">
        <input type="text" class="form-control" ng-model="CreateNewHTAN.SelectedPath" autocomplete="off" />
        <div class="input-group-btn"><a class="btn btn-silver" ng-click="ShowSelectFolder(CreateNewHTAN)">...</a></div>
      </div>
    </div>
  </div>
  <div class="form-group">
    <label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 control-label">FastCGI:</label>
    <div class="col-xs-12 col-sm-8 col-md-9 col-lg-9">
      <div ng-repeat="item in CreateNewHTAN.FastCGI" ng-show="CreateNewHTAN.FastCGI.length > 0">
        <div class="form-group checkbox" style="margin: 0 0 8px 0;">
          <label>
            <input type="checkbox" ng-checked="item.Enabled" ng-click="item.Enabled = !item.Enabled" /> {{item.Socket}}
          </label>
        </div>
        <div class="form-group" style="margin: 0 0 8px 0;">
          <label>ASP.NET:</label>
          <div class="btn-group">
            <label class="btn btn-default" ng-model="item.AspNetVersion" uib-btn-radio="'4.5'" ng-disabled="!item.Enabled">4.5</label>
            <label class="btn btn-default" ng-model="item.AspNetVersion" uib-btn-radio="'4.0'" ng-disabled="!item.Enabled">4.0</label>
            <label class="btn btn-default" ng-model="item.AspNetVersion" uib-btn-radio="'2.0'" ng-disabled="!item.Enabled">2.0</label>
            <label class="btn btn-default" ng-model="item.AspNetVersion" uib-btn-radio="'1.0'" ng-disabled="!item.Enabled">1.0</label>
          </div>
        </div>
        <div class="form-group form-inline" style="margin: 0 0 8px 0;">
          <input type="text" class="form-control" ng-model="item.User" placeholder="${User}" ng-disabled="!item.Enabled" />
          <input type="text" class="form-control" ng-model="item.Group" placeholder="${Group}" ng-disabled="!item.Enabled" />
        </div>
        <hr ng-show="$index != CreateNewHTAN.FastCGI.length - 1" />
      </div>
      <div ng-show="CreateNewHTAN.FastCGI.length == 0">
        fastcgi_pass not found. <a ng-click="ReinitCurrentConf()">Re-init this by Nginx config</a>.<br />
        <small>(current HTAN config will be overwritten)</small>
      </div>
    </div>
  </div>
</div>