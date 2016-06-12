<?#Page Title="${Svn Groups}" ?>
<html xmlns:php="http://aleksey.nemiro.ru/php-webforms">
  <body>

    <php:Content ID="MainContent">
      <div ng-controller="SvnGroupsController">

        <h2 class="pull-left">${Svn Groups}</h2>
        <h2 class="pull-right">
          <a ng-click="EditGroup()" class="btn btn-success">
            <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
            ${Add group}
          </a>
        </h2>

        <div class="clearfix"></div>

        <div class="panel panel-default ng-hide" ng-show="Loading" ng-cloak>
          <div class="panel-body">
            <span class="glyphicon glyphicon-refresh fa-spin"></span>
            ${Loading list of groups. Please wait...}
          </div>
        </div>

        <div class="panel panel-default ng-hide" ng-show="!Loading && Groups != null && Groups.length > 0" ng-cloak>
          <div class="panel-body">
            <table class="table table-hover">
              <thead>
                <tr>
                  <th class="col-xs-10 col-sm-10 col-md-10 col-lg-10">${Group}</th>
                  <th class="col-xs-1 col-sm-1 col-md-1 col-lg-1">&nbsp;</th>
                  <th class="col-xs-1 col-sm-1 col-md-1 col-lg-1">&nbsp;</th>
                </tr>
              </thead>
              <tbody>
                <tr ng-repeat="group in Groups">
                  <td class="text-nowrap">
                    <span class="glyphicon glyphicon-th-large"></span>
                    {{group.Name}} <span class="label" ng-class="group.Members.length == 0 ? 'label-default' : 'label-success'" title="Total members: {{group.Members.length}}">{{group.Members.length}}</span>
                  </td>
                  <td><a class="btn btn-primary btn-sm" ng-click="EditGroup(group)"><span class="glyphicon glyphicon-edit"></span><span class="hidden-xs hidden-sm hidden-md"> ${Edit}</span></a></td>
                  <td><a class="btn btn-danger btn-sm" ng-click="ShowDialogToDeleteGroup(group.Name)"><span class="glyphicon glyphicon-trash"></span><span class="hidden-xs hidden-sm hidden-md"> ${Delete}</span></a></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <div class="well well-lg" ng-show="!Loading && (Groups == null || Groups.length == 0)" ng-cloak>
          <p>${Groups not found...}</p>
          <p>
            <a ng-click="EditGroup()">
              <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
              ${Add a new group}
            </a>
          </p>
        </div>

        <?php 
          include \Nemiro\Server::MapPath('~/svn/group.php');
          include \Nemiro\Server::MapPath('~/svn/confirmToRemoveGroup.php');
        ?>

      </div>

    </php:Content>

  </body>
</html>