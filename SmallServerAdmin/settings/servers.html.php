<?#Page Title="${Servers}" ?>
<?#Register Src="~/Controls/ServersList.php" TagPrefix="php" TagName="ServersList"?>
<html xmlns:php="http://aleksey.nemiro.ru/php-webforms">
  <body>

    <php:Content ID="MainContent">
			<div ng-controller="PanelServersController" ng-init="GetServers()">
				<h2>${Servers}</h2>
				<php:ServersList ID="ServersList1" />
			</div>
    </php:Content>

  </body>
</html>