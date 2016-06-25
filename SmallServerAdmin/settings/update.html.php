<?#Page Title="${Update SmallServerAdmin}" ?>
<html xmlns:php="http://aleksey.nemiro.ru/php-webforms">
  <body>

    <php:Content ID="MainContent">
      <div ng-controller="PanelUpdateController">

        <div class="panel panel-default ng-hide" ng-show="!Loading" ng-cloak>
          <div class="panel-heading"><h4>SmallServerAdmin</h4></div>
          <div class="panel-body">
            <h5 ng-hide="Stable !== undefined && Stable.Updated">v<?=file_get_contents(\Nemiro\Server::MapPath('~/.version'))?></h5>

            <div class="ng-hide" ng-show="Checking" ng-cloak>
              <span class="glyphicon glyphicon-refresh fa-spin"></span>
              ${Checking for updates. Please wait...}
            </div>

						<div class="ng-hide" ng-hide="Stable === undefined || Stable == null || Stable.NeedUpdate" ng-cloak>
							${This is the latest version!}
						</div>

						<div class="ng-hide" ng-show="Stable.Updated" ng-cloak>
							${SSA_UPDATED}
						</div>

            <div class="ng-hide alert alert-warning" ng-show="Stable.NeedUpdate && !Stable.Updated" ng-cloak>
              <h4>${Available a new version:}</h4>
              <h4 ng-show="!Stable.Changes">v{{Stable.LatestVersion}}</h4>
              <div ng-show="Stable.Changes"><div ng-bind-html="Stable.Changes | Markdown"></div></div>

              <button type="button" class="btn btn-primary" ng-click="UpdateTo(Stable)" ng-disabled="Updating">
                <span class="glyphicon glyphicon-refresh fa-spin" ng-show="Updating"></span>
                ${Update to} v{{Stable.LatestVersion}}
              </button>
            </div>

            <div class="alert alert-danger" ng-show="Updating">
              <span class="glyphicon glyphicon-exclamation-sign"></span>
              ${Do not turn off the computer and do not close this page!}
            </div>
          </div>

					<div class="panel-heading" ng-show="AvailableDeveloperVersion"><h4>${Updates from other sources}</h4></div>
					<div class="panel-body">
						<div class="alert alert-danger" ng-show="AvailableDeveloperVersion">${UNRELEASED_UPDATION_WARNING}</div>
						<div class="well" ng-show="info.NeedUpdate && info.Branch != Config.DefaultBranch" ng-repeat="info in CheckingResults">
							<h3>
								${Branch}:
								<span ng-show="info.BranchTitle && info.BranchTitle != ''">{{info.BranchTitle}} ({{info.Branch}})</span>
								<span ng-show="!info.BranchTitle || info.BranchTitle == ''">{{info.Branch}}</span>
							</h3>

							<div ng-show="info.BranchDescription && info.BranchDescription != ''"><em>{{info.BranchDescription}}</em></div>

							<h4 ng-show="!info.Changes">v{{info.LatestVersion}}</h4>
							<div ng-show="info.Changes"><div ng-bind-html="info.Changes | Markdown"></div></div>

							<button type="button" class="btn btn-default" ng-click="UpdateTo(info)" ng-disabled="Updating" ng-hide="info.Updated">
								<span class="glyphicon glyphicon-refresh fa-spin" ng-show="Updating"></span>
								${Update to} v{{info.LatestVersion}}-{{info.Branch}}
							</button>

              <div ng-show="info.Updated" class="alert alert-success">${Successfully updated!}</div>
						</div>
					</div>
        </div>
        
      </div>
    </php:Content>

  </body>
</html>