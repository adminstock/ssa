<?#Page Title="${User Editor}" ?>
<html xmlns:php="http://aleksey.nemiro.ru/php-webforms">
  <body>

    <php:Content ID="MainContent">
      <div ng-controller="UserEditorController">

        <div id="loading" class="alert alert-info ng-hide" ng-show="Loading" ng-cloak>
          <span class="glyphicon glyphicon-refresh fa-spin"></span>
          ${Loading data. Please wait...}
        </div>

        <div id="saving" class="alert alert-info ng-hide" ng-show="Saving || Creation" ng-cloak>
          <span class="glyphicon glyphicon-refresh fa-spin"></span>
          ${Saving data. Please wait...}
        </div>

        <div id="success" class="alert alert-success ng-hide" ng-show="Success" ng-cloak>
          <span class="glyphicon glyphicon-ok"></span>
          ${Data saved successfully!}
        </div>

        <div class="panel panel-default ng-hide" ng-show="!Loading" ng-cloak>
          <div class="panel-heading"><h4>${Account}</h4></div>
          <div class="panel-body">
            <form id="accountForm" class="form-horizontal">
              <fieldset ng-disabled="Saving || Creation">
                <div class="form-group">
                  <label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 control-label">${Login}:</label>
                  <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3" ng-show="!IsNew && !SetLogin">
                    <a ng-click="SetLogin = true;" class="btn">{{User.Login}}</a>
                  </div>
                  <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3" ng-show="IsNew || SetLogin">
                    <input type="text" class="form-control" ng-model="User.Login" maxlength="50"  ng-required="IsNew || SetLogin" autocomplete="off" />
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 control-label">${Password}:</label>
                  <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3" ng-show="IsNew || SetPassword">
                    <input type="password" class="form-control" ng-model="NewPassword" maxlength="24" ng-required="IsNew || SetPassword" autocomplete="off" />
                  </div>
                  <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3" ng-show="!IsNew && !SetPassword">
                    <a class="btn" ng-click="SetPassword = true;">${Set new password}</a>
                  </div>
                </div>
                <div class="form-group" ng-show="IsNew || SetPassword">
                  <label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 control-label">${Confirm password}:</label>
                  <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
                    <input type="password" class="form-control" ng-model="ConfirmPassword" maxlength="24" compare-to="NewPassword" ng-required="IsNew || SetPassword" autocomplete="off" />
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 control-label">${Shell}:</label>
                  <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
                    <select class="form-control" ng-model="User.Shell" ng-options="shell.Key as shell.Value for shell in ShellList.Items"></select>
                  </div>
                </div>
                <div class="form-group ng-hide" ng-show="!IsNew" ng-cloak>
                  <div class="col-sm-offset-3 col-md-offset-2 col-lg-offset-2 col-xs-12 col-sm-6 col-md-3 col-lg-3">
                    <button class="btn btn-primary" ng-disabled="!IsNew && !(SetLogin || SetPassword || User.Shell != SourceUser.Shell)" ng-click="SaveAccount()">${Save}</button>&nbsp;
                    <button class="btn btn-default" ng-show="!IsNew && (SetLogin || SetPassword || User.Shell != SourceUser.Shell)" ng-click="SetLogin = SetPassword = false; User.Login = SourceUser.Login; NewPassword = ConfirmPassword = ''; User.Shell = SourceUser.Shell">${Cancel}</button>
                  </div>
                </div>
              </fieldset>
            </form>
          </div>
        </div>

        <div class="panel panel-default ng-hide" ng-show="!Loading" ng-cloak>
          <div class="panel-heading"><h4>${GECOS Fields}</h4></div>
          <div class="panel-body">
            <form id="gecosForm" class="form-horizontal">
              <fieldset ng-disabled="Saving || Creation">
                <div class="form-group">
                  <label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 control-label">${Full name}:</label>
                  <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
                    <input type="text" class="form-control" ng-model="User.FullName" maxlength="50" />
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 control-label">${Email}:</label>
                  <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
                    <input type="text" class="form-control" ng-model="User.Email" maxlength="50" />
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 control-label">${Address}:</label>
                  <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
                    <input type="text" class="form-control" ng-model="User.Address" maxlength="50" />
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 control-label">${Phone work}:</label>
                  <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
                    <input type="text" class="form-control" ng-model="User.PhoneWork" maxlength="50" />
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 control-label">${Phone home}:</label>
                  <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
                    <input type="text" class="form-control" ng-model="User.PhoneHome" maxlength="50" />
                  </div>
                </div>
                <div class="form-group ng-hide" ng-show="!IsNew" ng-cloak>
                  <div class="col-sm-offset-3 col-md-offset-2 col-lg-offset-2 col-xs-12 col-sm-6 col-md-3 col-lg-3">
                    <button class="btn btn-primary" ng-click="SaveGECOS()">${Save}</button>
                  </div>
                </div>
              </fieldset>
            </form>
          </div>
        </div>

        <div class="panel panel-default ng-hide" ng-show="!Loading" ng-cloak>
          <div class="panel-heading"><h4>${Groups}</h4></div>
          <div class="panel-body">
            <fieldset ng-disabled="Saving || Creation">
              <ul class="list-group" style="overflow-y:auto; height:375px;">
                <li ng-repeat="group in Groups" class="list-group-item checkbox"><label><input type="checkbox" ng-checked="User.Groups != null && User.Groups.indexOf(group.Name) > -1" ng-click="GroupClick(group)" ng-disabled="group.Id == User.GroupId" /> {{group.Name}}</label></li>
              </ul>
            </fieldset>
          </div>
          <div class="panel-footer ng-hide" ng-show="!IsNew" ng-cloak>
            <button class="btn btn-primary" ng-disabled="Saving" ng-click="SaveGroups()">${Save}</button>
          </div>
        </div>

        <div class="panel panel-default ng-hide" ng-show="!Loading && IsNew" ng-cloak>
          <div class="panel-heading"><h4>${Other}</h4></div>
          <div class="panel-body">
            <fieldset ng-disabled="Saving || Creation">
              <div class="form-group checkbox">
                <label>
                  <input type="checkbox" ng-model="IsSystem"  /> ${System user}
                </label>
              </div>
              <div class="form-group checkbox">
                <label>
                  <input type="checkbox" ng-model="NoCreateHome" /> ${No create home}
                </label>
              </div>
            </fieldset>
          </div>
        </div>

        <hr />
        <button class="btn btn-primary ng-hide" ng-show="IsNew" ng-click="CreateUser()" ng-cloak>${Save}</button>
        <a href="/users" class="btn btn-default">${Back to list}</a>
      </div>
    </php:Content>

  </body>
</html>