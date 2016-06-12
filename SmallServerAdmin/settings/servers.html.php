<?#Page Title="${Servers}" ?>
<?#Register Src="~/Controls/ServersList.php" TagPrefix="php" TagName="ServersList"?>
<html xmlns:php="http://aleksey.nemiro.ru/php-webforms">
  <body>

    <php:Content ID="MainContent">
      <div ng-controller="PanelServersController" ng-init="GetServers()">
        <h2>${Servers}</h2>

        <php:ServersList ID="ServersList1" />

        <div ng-show="CurrentServerConnectionError" class="alert alert-danger">
          <h4><span class="glyphicon glyphicon-remove-sign"></span> ${Connection error}</h4>
          <p>${Unable to connect to the} <strong><?=$this->CurrentServerAddress?></strong>.</p>
          <?php
            if ($this->CurrentServerIsDefault)
            {
          ?>
          <p>${Check the server settings in the file} <strong>/ssa.config.php:</strong></p>
          <?php
            } else {
          ?>
          <p>${Check the server settings in the file} <strong>/servers/<?=$_COOKIE['currentServer']?>.php:</strong></p>
          <?php
            }
          ?>
          <hr />
          <ui-codemirror ui-codemirror-opts="{ lineNumbers: true, matchBrackets: true, mode: 'php', theme: 'default', readOnly: true }">&lt;php
// ssh
$config['ssh_host'] = 'SERVER ADDRESS HERE';
$config['ssh_port'] = '22'; // port number (default: 22)
$config['ssh_user'] = 'USER NAME HERE';
$config['ssh_password'] = 'PASSWORD HERE';
$config['ssh_required_password'] = TRUE; // recommended always TRUE</ui-codemirror>
        </div>
      </div>
    </php:Content>

  </body>
</html>