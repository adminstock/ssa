<div id="svnUser" class="modal" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3>{{SourceUser.Login}}</h3>
      </div>
      <div class="modal-body">
        <form id="svnUserEditor" class="form-horizontal">
          <fieldset>
            <div class="form-group">
              <label class="col-xs-12 col-sm-12 col-md-12 col-lg-12">${Username}:</label>
              <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" ng-show="!IsNew && !SetLogin">
                <a ng-click="SetLogin = true;" class="btn">{{CurrentUser.Login}}</a>
              </div>
              <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" ng-show="IsNew || SetLogin">
                <input type="text" class="form-control" ng-model="CurrentUser.Login" maxlength="50"  ng-required="IsNew || SetLogin" autocomplete="off" />
              </div>
            </div>
            <div class="form-group">
              <label class="col-xs-12 col-sm-12 col-md-12 col-lg-12">${Password}:</label>
              <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" ng-show="IsNew || SetPassword">
                <input type="password" class="form-control" ng-model="CurrentUser.Password" maxlength="24" ng-required="IsNew || SetPassword" autocomplete="off" />
              </div>
              <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" ng-show="!IsNew && !SetPassword">
                <a class="btn" ng-click="SetPassword = true;">${Set new password}</a>
              </div>
            </div>
            <div class="form-group" ng-show="IsNew || SetPassword">
              <label class="col-xs-12 col-sm-12 col-md-12 col-lg-12">${Confirm password}:</label>
              <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <input type="password" class="form-control" ng-model="ConfirmPassword" maxlength="24" compare-to="CurrentUser.Password" ng-required="IsNew || SetPassword" autocomplete="off" />
              </div>
            </div>
            <div class="form-group">
              <label class="col-xs-12 col-sm-12 col-md-12 col-lg-12">${Groups}:</label>
              <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="overflow-y:auto; max-height:275px;">
                <ul class="list-group" ng-show="Groups != null && Groups.length > 0" ng-cloak>
                  <li ng-repeat="group in Groups" class="list-group-item checkbox"><label><input type="checkbox" ng-checked="CurrentUser.Groups != null && CurrentUser.Groups.indexOf(group) > -1" ng-click="GroupClick(group)" /> {{group}}</label></li>
                </ul>
                <div ng-show="Groups == null || Groups.length == 0" ng-cloak>
                  <p>${No groups...}</p>
                </div>
              </div>
            </div>
          </fieldset>
        </form>
      </div>
      <div class="modal-footer">
        <button class="btn btn-primary" ng-click="SaveUser()">${Save}</button>
        <button class="btn btn-default" data-dismiss="modal" aria-hidden="true">${Cancel}</button>
      </div>
    </div>
  </div>
</div>