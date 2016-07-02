<?#Page Title="${Servers}" ?>
<?#Register Src="~/Controls/ServersList.php" TagPrefix="php" TagName="ServersList"?>
<html xmlns:php="http://aleksey.nemiro.ru/php-webforms">
  <body>

    <php:Content ID="MainContent">
      <div ng-controller="PanelServersController" ng-init="GetServers()">
        <h2 class="pull-left">${Servers}</h2>
				<h2 class="pull-right">
					<button class="btn btn-success" ng-show="PreparingServerForm" disabled="disabled" ng-cloak>
						<span class="fa fa-spinner fa-pulse fa-fw"></span>
						${Add server}
					</button>
					<button class="btn btn-success" ng-click="ShowEditor(null)" ng-hide="PreparingServerForm">
						<span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
						${Add server}
					</button>
				</h2>

				<div class="clearfix"></div>

        <php:ServersList ID="ServersList1" />

        <div ng-show="CurrentServerConnectionError" class="alert alert-danger" ng-cloak>
          <h4><span class="glyphicon glyphicon-remove-sign"></span> ${Connection error}</h4>
          <p>${Unable to connect to the} <strong><?=$this->CurrentServerAddress?></strong>.</p>
					<p>${Check the server settings.}</p>
        </div>

				<?php
        include_once  \Nemiro\Server::MapPath('~/settings/dialogs/server.php');
        include_once  \Nemiro\Server::MapPath('~/settings/dialogs/confirmToDeleteServer.php');
				?>
      </div>
    </php:Content>

  </body>
</html>