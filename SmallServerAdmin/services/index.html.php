<?#Page Title="Services" ?>
<html xmlns:php="http://aleksey.nemiro.ru/php-webforms">
  <body>

    <php:Content ID="MainContent">
      <div ng-controller="ServiceListController">

        <h2 class="pull-left">Services</h2>

        <div class="clearfix"></div>

        <div class="panel panel-default">
          <div class="panel-body form-inline">
            <div class="form-group">
              <input type="text" name="search" placeholder="Name or part of name" class="form-control" ng-disabled="Loading" ng-model="SearchString" />
            </div>
            <button class="btn btn-default" ng-disabled="Loading" ng-click="Search()">
              <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
              Search
            </button>
            <button class="btn btn-default" ng-disabled="Loading" ng-click="ResetSearch()">
              <span class="glyphicon glyphicon-erase" aria-hidden="true"></span>
              Reset
            </button>
          </div>
        </div>

        <div class="panel panel-default ng-hide" ng-show="Loading" ng-cloak>
          <div class="panel-body">
            <span class="glyphicon glyphicon-refresh fa-spin"></span>
            Loading list of services. Please wait...
          </div>
        </div>

        <div class="panel panel-default ng-hide" ng-show="!Loading && Services != null && Services.length > 0" ng-cloak>
          <div class="panel-body">
            <table class="table table-hover cell-align-middle">
              <thead>
                <tr>
                  <th class="col-xs-9 col-sm-9 col-md-9 col-lg-9">${Service name}</th>
                  <th class="col-xs-1 col-sm-1 col-md-1 col-lg-1 text-center">${Status}</th>
                  <th class="col-xs-1 col-sm-1 col-md-1 col-lg-1">&nbsp;</th>
                  <th class="col-xs-1 col-sm-1 col-md-1 col-lg-1">&nbsp;</th>
                </tr>
              </thead>
              <tbody>
                <tr ng-repeat="service in Services" ng-class="service.Status == 'Stopped' ? 'status-stopped' : ''">
                  <td class="text-nowrap">
                    <span class="fa fa-cog"></span>
                    {{service.Name}}
                  </td>
                  <td class="text-center">
                    <span ng-show="!service.Loading">{{service.Status}}</span>
                    <span ng-show="service.Loading" class="glyphicon glyphicon-option-horizontal"></span>
                  </td>
                  <td class="text-center">
                    <a class="btn btn-silver btn-sm" ng-show="service.Status == 'Started'" ng-click="SetStatus(service, 'Stopped')" ng-disabled="service.Loading">
                      <span ng-show="service.Loading"><i class="glyphicon glyphicon-refresh fa-spin"></i></span>
                      <span ng-show="!service.Loading" class="glyphicon glyphicon-stop"></span>
                      <span class="hidden-xs hidden-sm hidden-md"> ${Stop}</span>
                    </a>
                    <a class="btn btn-gray btn-sm" ng-show="service.Status != 'Started'" ng-click="SetStatus(service, 'Started')" ng-disabled="service.Loading">
                      <span ng-show="service.Loading"><i class="glyphicon glyphicon-refresh fa-spin"></i></span>
                      <span ng-show="!service.Loading" class="glyphicon glyphicon-play"></span>
                      <span class="hidden-xs hidden-sm hidden-md"> ${Start}</span>
                    </a>
                  </td>
                  <td class="text-center">
                    <a class="btn btn-silver btn-sm" ng-disabled="service.Status != 'Started' || service.Loading" ng-click="SetStatus(service, 'Reload')">
                      <span ng-show="service.Loading"><i class="glyphicon glyphicon-refresh fa-spin"></i></span>
                      <span ng-show="!service.Loading" class="glyphicon glyphicon-refresh"></span>
                      <span class="hidden-xs hidden-sm hidden-md"> ${Reload}</span>
                    </a>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <div class="well well-lg text-center" ng-show="!Loading && (Services == null || Services.length == 0)" ng-cloak>
          <p>Services not found...</p>
        </div>

        <?php
        include_once  \Nemiro\Server::MapPath('~/services/dialogs/confirmToStopService.php');
        ?>

      </div>

    </php:Content>

  </body>
</html>