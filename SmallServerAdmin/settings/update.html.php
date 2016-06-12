<?#Page Title="${Update SmallServerAdmin}" ?>
<html xmlns:php="http://aleksey.nemiro.ru/php-webforms">
  <body>

    <php:Content ID="MainContent">
      <div ng-controller="PanelUpdateController">

        <div class="panel panel-default ng-hide" ng-show="!Loading" ng-cloak>
          <div class="panel-heading"><h4>SmallServerAdmin</h4></div>
          <div class="panel-body">
            <h5 ng-hide="Updated">v<?=file_get_contents(\Nemiro\Server::MapPath('~/.version'))?></h5>

            <div class="ng-hide" ng-show="Checking" ng-cloak>
              <span class="glyphicon glyphicon-refresh fa-spin"></span>
              ${Checking for updates. Please wait...}
            </div>

            <div class="ng-hide" ng-hide="NeedUpdate === undefined || NeedUpdate" ng-cloak>
              ${This is the latest version!}
            </div>

            <div class="ng-hide" ng-show="Updated" ng-cloak>
              ${SSA_UPDATED}
            </div>

            <div class="ng-hide alert alert-warning" ng-show="NeedUpdate !== undefined && NeedUpdate && !Updated" ng-cloak>
              <h4>${Available a new version:}</h4>
              <h4>v{{NewVersion}}</h4>
              <pre ng-show="Changes">{{Changes}}</pre>

              <button type="button" class="btn btn-primary" ng-click="UpdateToNewVersion()" ng-disabled="Updating">
                <span class="glyphicon glyphicon-refresh fa-spin" ng-show="Updating"></span>
                ${Update to} v{{NewVersion}}
              </button>
            </div>

            <div class="alert alert-danger" ng-show="Updating">
              <span class="glyphicon glyphicon-exclamation-sign"></span>
              ${Do not turn off the computer and do not close this page!}
            </div>
          </div>
        </div>
        
      </div>
    </php:Content>

  </body>
</html>