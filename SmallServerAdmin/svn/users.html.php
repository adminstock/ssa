<?#Page Title="${Svn Users}" ?>
<html xmlns:php="http://aleksey.nemiro.ru/php-webforms">
  <body>

    <php:Content ID="MainContent">
      <div ng-controller="SvnUsersController">

        <h2 class="pull-left">${Svn Users}</h2>
        <h2 class="pull-right">
          <a ng-click="EditUser()" class="btn btn-success">
            <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
            ${Add user}
          </a>
        </h2>

        <div class="clearfix"></div>

        <div class="panel panel-default">
          <div class="panel-body form-inline">
            <div class="form-group">
              <input type="text" name="search" placeholder="${User name}" class="form-control" ng-disabled="Loading" ng-model="Search" />
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

        <div class="panel panel-default ng-hide" ng-show="!Loading && Users != null && Users.length > 0" ng-cloak>
          <div class="panel-body">
            <table class="table table-hover">
              <thead>
                <tr>
                  <th class="col-xs-6 col-sm-6 col-md-4 col-lg-4">${Username}</th>
                  <th class="hidden-xs hidden-sm col-md-6 col-lg-6">${Groups}</th>
                  <th class="col-xs-1 col-sm-1 col-md-1 col-lg-1">&nbsp;</th>
                  <th class="col-xs-1 col-sm-1 col-md-1 col-lg-1">&nbsp;</th>
                </tr>
              </thead>
              <tbody>
                <tr ng-repeat="user in Users">
                  <td class="text-nowrap">
                    <span class="glyphicon glyphicon-user"></span>
                    {{user.Login}}
                  </td>
                  <td class="hidden-xs hidden-sm">
                    {{user.Groups.join(', ')}}
                  </td>
                  <td><a class="btn btn-primary btn-sm" ng-click="EditUser(user)"><span class="glyphicon glyphicon-edit"></span><span class="hidden-xs hidden-sm hidden-md"> ${Edit}</span></a></td>
                  <td><a class="btn btn-danger btn-sm" ng-click="ShowDialogToDeleteUser(user.Login)"><span class="glyphicon glyphicon-trash"></span><span class="hidden-xs hidden-sm hidden-md"> ${Delete}</span></a></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <div class="well well-lg" ng-show="!Loading && (Users == null || Users.length == 0)" ng-cloak>
          <p>${Users not found...}</p>
          <p>
            <a ng-click="EditUser()">
              <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
              ${Add a new user}
            </a>
          </p>
        </div>

        <?php 
          include \Nemiro\Server::MapPath('~/svn/user.php');
          include \Nemiro\Server::MapPath('~/svn/confirmToRemoveUser.php');
        ?>

      </div>

    </php:Content>

  </body>
</html>