<div ng-show="LoadingServers">
	<span class="glyphicon glyphicon-refresh fa-spin"></span>
	Loading servers list. Please wait...
</div>
<table class="table table-hover cell-align-middle ng-hide" ng-hide="LoadingServers" ng-cloak>
	<tbody>
		<tr ng-repeat="server in Servers" ng-class="{'success': server.Address == Config.ServerAddress && !CurrentServerConnectionError && !ConnectionTesting, 'danger': server.Address == Config.ServerAddress && CurrentServerConnectionError, 'warning': server.Address == Config.ServerAddress && ConnectionTesting, 'text-muted': server.Disabled}">
			<td class="col-xs-10 col-sm-10 col-md-10 col-lg-10">
				<h4 ng-show="server.Name">{{server.Name}} <small>({{server.Address}})</small></h4>
				<h4 ng-hide="server.Name">{{server.Address}}</h4>
				<small ng-show="server.Description && server.Description != '' && (!CurrentServerConnectionError || (CurrentServerConnectionError && server.Address != Config.ServerAddress))">{{server.Description}}</small>
        <small ng-show="server.Address == Config.ServerAddress && CurrentServerConnectionError" class="red"><span class="glyphicon glyphicon-exclamation-sign"></span> ${Unable to connect to the server.}</small>
			</td>
			<td class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
				<button type="button" class="btn col-xs-12 col-sm-12 col-md-12 col-lg-12" ng-class="server.Disabled ? 'btn-gray' : 'btn-primary'" ng-show="server.Address != Config.ServerAddress" ng-click="ConnectToServer(server)" ng-disabled="server.Disabled">
          <span ng-show="server.Disabled">${Disabled}</span>
          <span ng-hide="server.Disabled">${Connect}</span>
        </button>
				<button type="button" class="btn btn-success col-xs-12 col-sm-12 col-md-12 col-lg-12" ng-show="server.Address == Config.ServerAddress && !CurrentServerConnectionError && !ConnectionTesting" disabled="disabled">${Current}</button>
				<button type="button" class="btn btn-danger col-xs-12 col-sm-12 col-md-12 col-lg-12" ng-show="server.Address == Config.ServerAddress && CurrentServerConnectionError" disabled="disabled">${Current}</button>
				<button type="button" class="btn btn-warning col-xs-12 col-sm-12 col-md-12 col-lg-12" ng-show="server.Address == Config.ServerAddress && ConnectionTesting" disabled="disabled">
					<span class="fa fa-spinner fa-pulse fa-fw"></span>
					${Testing...}
				</button>
			</td>
		</tr>
	</tbody>
</table>