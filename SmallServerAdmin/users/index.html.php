<?#Page Title="${Users}" ?>
<html xmlns:php="http://aleksey.nemiro.ru/php-webforms">
  <body>

    <php:Content ID="MainContent">
      <div ng-controller="UserListController">

        <h2 class="pull-left">${Users}</h2>
        <h2 class="pull-right">
          <a href="/users/edit.php" class="btn btn-success">
            <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
            ${Add user}
          </a>
        </h2>

        <div class="clearfix"></div>

        <div class="panel panel-default">
          <div class="panel-body form-inline">
            <div class="form-group">
              <input type="text" name="search" placeholder="${Login, Full name or phone}" class="form-control" ng-disabled="Loading" ng-model="Search" />
            </div>
            <button class="btn btn-default" ng-disabled="Loading" ng-click="SearchUsers()">
              <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
              ${Search}
            </button>
            <button class="btn btn-default" ng-disabled="Loading" ng-click="ResetSearch()">
              <span class="glyphicon glyphicon-erase" aria-hidden="true"></span>
              ${Reset}
            </button>
          </div>
        </div>

        <div class="panel panel-default ng-hide" ng-show="Loading" ng-cloak>
          <div class="panel-body">
            <span class="glyphicon glyphicon-refresh fa-spin"></span>
            ${Loading list of users. Please wait...}
          </div>
        </div>

        <div class="panel panel-default ng-hide" ng-show="!Loading && Users != null && Users.Items.length > 0" ng-cloak>
          <div class="panel-body">
            <table style="width:100%" class="table table-hover">
              <thead>
                <tr>
                  <th>${Login}</th>
                  <th class="hidden-xs hidden-sm">${Full name}</th>
                  <th class="hidden-xs hidden-sm">${Home path}</th>
                  <th class="hidden-xs">${Shell}</th>
                  <th>&nbsp;</th>
                  <th>&nbsp;</th>
                </tr>
              </thead>
              <tbody>
                <tr ng-repeat="user in Users.Items">
                  <td class="text-nowrap">
                    <span class="glyphicon glyphicon-user"></span>
                    {{user.Login}}
                  </td>
                  <td class="hidden-xs hidden-sm">{{user.FullName}}</td>
                  <td class="hidden-xs hidden-sm">{{user.HomePath}}</td>
                  <td class="hidden-xs">{{user.Shell}}</td>
                  <td><a href="/users/edit.php#?login={{user.Login}}" class="btn btn-primary btn-sm"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span><span class="hidden-xs hidden-sm hidden-md"> ${Edit}</span></a></td>
                  <td>
                    <a href="#" class="btn btn-danger btn-sm" ng-click="ShowDialogToDeleteUser(user.Login)" ng-hide="user.Login == 'root'"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span><span class="hidden-xs hidden-sm hidden-md"> ${Delete}</span></a>
                    <span class="btn btn-danger btn-sm" ng-show="user.Login == 'root'" disabled="disabled"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span><span class="hidden-xs hidden-sm hidden-md"> ${Delete}</span></span>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="panel-footer" ng-cloak>
            <uib-pagination total-items="Users.TotalRecords" ng-model="Users.CurrentPage" items-per-page="Users.DataPerPage" ng-change="LoadUsers()" boundary-links="false" direction-links="false"></uib-pagination>
          </div>
        </div>

        <div class="well well-lg" ng-show="!Loading && (Users == null || Users.Items.length == 0)" ng-cloak>
          <p>${Users not found...}</p>
          <p>
            <a href="/users/edit.php">
              <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
              ${Add a new user}
            </a>
          </p>
        </div>

        <div id="confirmUserRemove" class="modal" role="dialog" data-not-restore="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3>${Confirm}</h3>
              </div>
              <div class="modal-body" style="overflow:auto;">
                ${You are about to delete the} <strong>{{SelectedUserToRemove}}</strong>.<br />
                ${Recover data after deletion will not be possible.}<br />
                ${For confirmation, enter username, which should be removed:}<br />
                <div class="form-group">
                  <input type="text" class="form-control" ng-model="ConfirmLoginToRemove" autocomplete="off" />
                </div>
                <div class="form-group checkbox">
                  <label>
                    <input type="checkbox" ng-model="RemoveHome" /> ${remove home folder}
                  </label>
                </div>
              </div>
              <div class="modal-footer">
                <button class="btn btn-danger pull-left" ng-click="DeleteUser()">${Delete}</button>
                <button class="btn btn-default pull-left" data-dismiss="modal" aria-hidden="true">${Cancel}</button>
              </div>
            </div>
          </div>
        </div>
      </div>

    </php:Content>

  </body>
</html>