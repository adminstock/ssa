<?#Page Title="Sites" ?>
<html xmlns:php="http://aleksey.nemiro.ru/php-webforms">
  <body>

    <php:Content ID="MainContent">
      <div ng-controller="SiteListController">

        <h2 class="pull-left">Sites</h2>
        <h2 class="pull-right">
          <a href="/sites/edit.php" class="btn btn-success">
            <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
            Create site
          </a>
        </h2>

        <div class="clearfix"></div>

        <div class="panel panel-default">
          <div class="panel-body form-inline">
            <div class="form-group">
              <input type="text" name="search" placeholder="Path or part of path" class="form-control" ng-disabled="Loading" ng-model="SearchString" />
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
            Loading list of sites. Please wait...
          </div>
        </div>

        <div class="panel panel-default ng-hide" ng-show="!Loading && Sites != null && Sites.length > 0" ng-cloak>
          <div class="panel-body">
            <table class="table table-hover">
              <thead>
                <tr>
                  <th class="col-xs-10 col-sm-5 col-md-5 col-lg-5">${Site name}</th>
                  <th class="hidden-xs col-xs-4 col-sm-4 col-md-4 col-lg-4">&nbsp;</th>
                  <th class="hidden-xs col-xs-1 col-sm-1 col-md-1 col-lg-1">&nbsp;</th>
                  <th class="col-xs-1 col-sm-1 col-md-1 col-lg-1">&nbsp;</th>
                  <th class="col-xs-1 col-sm-1 col-md-1 col-lg-1">&nbsp;</th>
                </tr>
              </thead>
              <tbody>
                <tr ng-repeat="site in Sites">
                  <td class="text-nowrap">
                    <span class="glyphicon glyphicon-globe"></span>
                    {{site.Name}}
                  </td>
                  <td class="hidden-xs">
                    <span class="label" ng-class="ConfIsEnabled('Nginx', site) ? 'label-success' : 'label-default'" ng-hide="site.Levels.indexOf('Nginx') == -1" style="margin-right: 4px;">
                      N<span class="hidden-sm">ginx</span>
                    </span>
                    <span class="label" ng-class="ConfIsEnabled('Apache', site) ? 'label-danger' : 'label-default'" ng-hide="site.Levels.indexOf('Apache') == -1" style="margin-right: 4px;">
                      A<span class="hidden-sm">pache</span>
                    </span>
                    <span class="label" ng-class="ConfIsEnabled('HTAN', site) ? 'label-primary' : 'label-default'" ng-hide="site.Levels.indexOf('HTAN') == -1">
                      H<span class="hidden-sm">TAN</span>
                    </span>
                    <span class="label label-default" ng-hide="site.Levels.length > 0"><span class="glyphicon glyphicon-remove"></span></span>
                  </td>
                  <td class="hidden-xs">
                    <div ng-show="site.Levels.length == 1">
                      <a class="btn btn-silver btn-sm" ng-show="site.IsEnabled" ng-click="SetStatus(site, 'all', false)" ng-disabled="site.Loading">
                        <span ng-show="site.Loading"><i class="glyphicon glyphicon-refresh fa-spin"></i></span>
                        <span ng-show="!site.Loading" class="glyphicon glyphicon-stop"></span>
                        <span class="hidden-xs hidden-sm hidden-md"> ${Stop}</span>
                        <span class="caret" style="color:transparent"></span>
                      </a>
                      <a class="btn btn-gray btn-sm" ng-show="!site.IsEnabled" ng-click="SetStatus(site, 'all', true)" ng-disabled="site.Loading">
                        <span ng-show="site.Loading"><i class="glyphicon glyphicon-refresh fa-spin"></i></span>
                        <span ng-show="!site.Loading" class="glyphicon glyphicon-play"></span>
                        <span class="hidden-xs hidden-sm hidden-md"> ${Start}</span>
                        <span class="caret" style="color:transparent"></span>
                      </a>
                    </div>
                    <div class="btn-group" ng-show="site.Levels.length > 1" uib-dropdown>
                      <button id="{{'btnStatus-' + site.Name}}" type="button" class="btn btn-sm" ng-class="ConfIsEnabled('all', site) ? 'btn-silver' : 'btn-gray'" uib-dropdown-toggle>
                        <span ng-show="site.Loading"><i class="glyphicon glyphicon-refresh fa-spin"></i></span>
                        <span ng-show="!site.Loading && ConfIsEnabled('all', site)" class="glyphicon glyphicon-stop"></span>
                        <span ng-show="!site.Loading && !ConfIsEnabled('all', site)" class="glyphicon glyphicon-play"></span>
                        <span class="hidden-xs hidden-sm hidden-md" ng-show="ConfIsEnabled('all', site)">${Stop}</span>
                        <span class="hidden-xs hidden-sm hidden-md" ng-show="!ConfIsEnabled('all', site)">${Start}</span>
                        <span class="caret"></span>
                      </button>
                      <ul uib-dropdown-menu aria-labelledby="{{'btnStatus-' + site.Name}}">
                        <li>
                          <a ng-show="site.IsEnabled" ng-click="SetStatus(site, 'all', false)" ng-disabled="site.Loading">
                            <span ng-show="site.Loading"><i class="glyphicon glyphicon-refresh fa-spin"></i></span>
                            <span ng-show="!site.Loading" class="glyphicon glyphicon-stop"></span>
                            ${Stop all}
                          </a>
                          <a ng-show="!site.IsEnabled" ng-click="SetStatus(site, 'all', true)" ng-disabled="site.Loading">
                            <span ng-show="site.Loading"><i class="glyphicon glyphicon-refresh fa-spin"></i></span>
                            <span ng-show="!site.Loading" class="glyphicon glyphicon-play"></span>
                            ${Start all}
                          </a>
                        </li>
                        <li class="divider"></li>
                        <li role="menuitem" ng-repeat="level in LevelsList" ng-hide="site.Levels.indexOf(level) == -1">
                          <a ng-show="ConfIsEnabled(level, site)" ng-click="SetStatus(site, level, false)" ng-disabled="site.Loading">
                            <span ng-show="site.Loading"><i class="glyphicon glyphicon-refresh fa-spin"></i></span>
                            <span ng-show="!site.Loading" class="glyphicon glyphicon-stop"></span>
                            {{level}}
                          </a>
                          <a ng-show="!ConfIsEnabled(level, site)" ng-click="SetStatus(site, level, true)" ng-disabled="site.Loading">
                            <span ng-show="site.Loading"><i class="glyphicon glyphicon-refresh fa-spin"></i></span>
                            <span ng-show="!site.Loading" class="glyphicon glyphicon-play"></span>
                            {{level}}
                          </a>
                        </li>
                      </ul>
                    </div>
                  </td>
                  <td><a href="/sites/edit.php#?name={{site.Name}}" class="btn btn-primary btn-sm"><span class="glyphicon glyphicon-edit"></span><span class="hidden-xs hidden-sm hidden-md"> ${Edit}</span></a></td>
                  <td>
                    <a class="btn btn-danger btn-sm" ng-click="ShowDialogToDelete(site.Name)"><span class="glyphicon glyphicon-trash"></span><span class="hidden-xs hidden-sm hidden-md"> ${Delete}</span></a>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <div class="well well-lg" ng-show="!Loading && (Sites == null || Sites.length == 0)" ng-cloak>
          <p>Sites not found...</p>
          <p>
            <a href="/sites/edit.php">
              <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
              Create a new site
            </a>
          </p>
        </div>

        <?php
        include_once  \Nemiro\Server::MapPath('~/sites/dialogs/confirmToDeleteSite.php');
				?>

      </div>

    </php:Content>

  </body>
</html>