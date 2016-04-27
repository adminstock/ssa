<?#Page Title="Svn Repositories" ?>
<html xmlns:php="http://aleksey.nemiro.ru/php-webforms">
  <body>

    <php:Content ID="MainContent">
      <div ng-controller="SvnRepositoriesController">

        <h2 class="pull-left">Svn Repositories</h2>
        <h2 class="pull-right">
          <a ng-click="Edit()" class="btn btn-success">
            <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
            Create repository
          </a>
        </h2>

        <div class="clearfix"></div>

        <div class="panel panel-default">
          <div class="panel-body form-inline">
            <div class="form-group">
              <input type="text" name="search" placeholder="Path or part of path" class="form-control" ng-disabled="Loading" ng-model="Search" />
            </div>
            <button class="btn btn-default" ng-disabled="Loading" ng-click="SearchRepositories()">
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
            Loading list of repositories. Please wait...
          </div>
        </div>

        <div class="panel panel-default ng-hide" ng-show="!Loading && Repositories != null && Repositories.length > 0" ng-cloak>
          <div class="panel-body">
            <table class="table table-hover">
              <thead>
                <tr>
                  <th class="col-xs-10 col-sm-10 col-md-10 col-lg-10">${Repository}</th>
                  <th class="col-xs-1 col-sm-1 col-md-1 col-lg-1">&nbsp;</th>
                  <th class="col-xs-1 col-sm-1 col-md-1 col-lg-1">&nbsp;</th>
                </tr>
              </thead>
              <tbody>
                <tr ng-repeat="r in Repositories">
                  <td class="text-nowrap">
                    <span class="glyphicon glyphicon-folder-close"></span>
                    {{r.RelativePath == '/' ? 'root' : r.Name}}
                  </td>
                  <td><a class="btn btn-primary btn-sm" ng-click="Edit(r.Name)"><span class="glyphicon glyphicon-edit"></span><span class="hidden-xs hidden-sm hidden-md"> ${Edit}</span></a></td>
                  <td>
                    <a class="btn btn-danger btn-sm" ng-click="ShowDialogToDelete(r.Name)" ng-hide="r.RelativePath == '/'"><span class="glyphicon glyphicon-trash"></span><span class="hidden-xs hidden-sm hidden-md"> ${Delete}</span></a>
                    <a class="btn btn-danger btn-sm" ng-show="r.RelativePath == '/'" disabled="disabled"><span class="glyphicon glyphicon-trash"></span><span class="hidden-xs hidden-sm hidden-md"> ${Delete}</span></a>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <div class="well well-lg" ng-show="!Loading && (Repositories == null || Repositories.length == 0)" ng-cloak>
          <p>Repositories not found...</p>
          <p>
            <a ng-click="Edit()">
              <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
              Create a new repository
            </a>
          </p>
        </div>

        <?php 
          include \Nemiro\Server::MapPath('~/svn/repository.php');
          include \Nemiro\Server::MapPath('~/svn/confirmToRemoveRepository.php');
        ?>

      </div>

    </php:Content>

  </body>
</html>