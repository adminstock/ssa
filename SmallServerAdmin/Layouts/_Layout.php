<?#Register Src="~/Controls/Header.php" TagPrefix="php" TagName="Header"?>
<?#Register Src="~/Controls/Menu.php" TagPrefix="php" TagName="Menu"?>
<?#Register Src="~/Controls/Footer.php" TagPrefix="php" TagName="Footer"?>
<?#Register Src="~/Controls/ClientSideConfig.php" TagPrefix="php" TagName="ClientSideConfig"?>
<?#Register Src="~/Controls/StaticIncludes.php" TagPrefix="php" TagName="StaticIncludes"?>
<?#Register Src="~/Controls/ServersList.php" TagPrefix="php" TagName="ServersList"?>
<!DOCTYPE html>

<html xmlns:php="http://aleksey.nemiro.ru/php-webforms">
  <head>
    <title>SmallServerAdmin</title>
    <meta name="viewport" content="width=device-width" />
    <php:Head/>
  </head>
  <body ng-app="SmallServerAdmin" ng-controller="MasterController">
    <php:Header ID="Header1" />

    <div id="container" class="container">
      
      <div class="row">
        <div class="col-xs-12 col-sm-4 col-md-3 col-lg-3">
          <php:Menu ID="Menu1" />
        </div>
        <div class="col-xs-12 col-sm-8 col-md-9 col-lg-9">
          <php:MainContent/>
        </div>
      </div>

      <div id="progress" class="modal" role="dialog" data-not-restore="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header" ng-show="ProgressTitle != null && ProgressTitle != ''">
              <h3 ng-bind-html="ProgressTitle"></h3>
            </div>
            <div class="modal-body text-center">
              <p>&nbsp;</p>
              <p><span class="fa fa-spinner fa-pulse fa-5x"></span></p>
              <p ng-bind-html="ProgressMessage"></p>
              <p>&nbsp;</p>
            </div>
          </div>
        </div>
      </div>

      <div id="servers" class="modal" role="dialog" data-not-restore="true" ng-controller="PanelServersController" ng-init="DisableShowConnectionError = true;">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h3><span class="fa fa-server"></span> ${Servers}</h3>
            </div>
            <div class="modal-body">
              <php:ServersList ID="ServersList1" />
            </div>
          </div>
        </div>
      </div>

      <php:ClientSideConfig ID="ClientSideConfig1" />
    </div>

    <php:Footer ID="Footer1" />
    <php:StaticIncludes ID="StaticIncludes1" />
  </body>
</html>