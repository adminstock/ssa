<div ng-show="LoadingServers">
  <span class="glyphicon glyphicon-refresh fa-spin"></span>
  ${Loading servers list. Please wait...}
</div>
<table class="table table-hover cell-align-middle" ng-hide="LoadingServers" ng-cloak>
  <tbody>
    <tr ng-repeat="server in Servers" ng-class="{'success': (server.Address == Config.ServerAddress && server.Name == Config.ServerName) && !CurrentServerConnectionError && !ConnectionTesting, 'danger': (server.Address == Config.ServerAddress && server.Name == Config.ServerName) && CurrentServerConnectionError, 'warning': (server.Address == Config.ServerAddress && server.Name == Config.ServerName) && ConnectionTesting, 'text-muted': server.Disabled}">
      <td class="<?=($this->NoControl != 'TRUE' ? 'col-xs-8 col-sm-8 col-md-8 col-lg-8' : 'col-xs-10 col-sm-10 col-md-10 col-lg-10')?>">
        <h4 ng-show="server.Name">{{server.Name}} <small>({{server.Address}})</small></h4>
        <h4 ng-hide="server.Name">{{server.Address}}</h4>
        <small ng-show="server.Description && server.Description != '' && (!CurrentServerConnectionError || (CurrentServerConnectionError && server.Address != Config.ServerAddress && server.Name != Config.ServerName))">{{server.Description}}</small>
        <small ng-show="(server.Address == Config.ServerAddress  && server.Name == Config.ServerName) && CurrentServerConnectionError" class="red"><span class="glyphicon glyphicon-exclamation-sign"></span> ${Unable to connect to the server.}</small>
      </td>
      <td class="col-xs-2 col-sm-2 col-md-2 col-lg-2 text-center">
        <button type="button" class="btn btn-sm col-xs-12 col-sm-12 col-md-12 col-lg-12" ng-class="server.Disabled ? 'btn-gray' : 'btn-silver'" ng-show="server.Address != Config.ServerAddress || server.Name != Config.ServerName" ng-click="ConnectToServer(server)" ng-disabled="server.Disabled" ng-cloak>
          <span ng-show="server.Disabled">${Disabled}</span>
          <span ng-hide="server.Disabled">${Connect}</span>
        </button>
        <div class="green" ng-show="(server.Address == Config.ServerAddress && server.Name == Config.ServerName) && !CurrentServerConnectionError && !ConnectionTesting" ng-cloak>${Connected}</div>
        <div class="red" ng-show="(server.Address == Config.ServerAddress && server.Name == Config.ServerName) && CurrentServerConnectionError" ng-cloak>${Connection error}</div>
        <div class="brown" ng-show="(server.Address == Config.ServerAddress && server.Name == Config.ServerName) && ConnectionTesting" ng-cloak>
          <span class="fa fa-spinner fa-pulse fa-fw"></span>
          ${Testing...}
        </div>
      </td>
      <?php
      if ($this->NoControl != 'TRUE') {
      ?>
			<td class="col-xs-1 col-sm-1 col-md-1 col-lg-1">
				<a class="btn btn-primary btn-sm" ng-click="ShowEditor(server)"><span class="glyphicon glyphicon-edit"></span><span class="hidden-xs hidden-sm hidden-md"> ${Edit}</span></a>
      </td>
			<td class="col-xs-1 col-sm-1 col-md-1 col-lg-1">
				<a class="btn btn-danger btn-sm" ng-click="ShowDialogToDelete(server)"><span class="glyphicon glyphicon-trash"></span><span class="hidden-xs hidden-sm hidden-md"> ${Delete}</span></a>
      </td>
      <?php
      }
      ?>
    </tr>
  </tbody>
</table>