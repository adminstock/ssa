<?#Page Title="${Monitoring}" ?>
<html xmlns:php="http://aleksey.nemiro.ru/php-webforms">
  <body>

    <php:Content ID="MainContent">
      <div ng-controller="MonitoringController">

        <h2 class="pull-left">${Monitoring}</h2>
        <h2 class="pull-right">
          <button type="button" class="btn btn-default" ng-click="Refresh()" ng-disabled="RefreshDisabled">
            <span class="glyphicon glyphicon-refresh"></span>
            ${Refresh}
          </button>
          <div class="btn-group" uib-dropdown>
            <button id="indicators" type="button" class="btn btn-default" uib-dropdown-toggle>
              <span class="glyphicon glyphicon-scale"></span>
              <span class="caret"></span>
            </button>
            <ul uib-dropdown-menu aria-labelledby="indicators">
              <li role="menuitem">
                <a class="text-nowrap" ng-click="SetIndicatorsRefreshInterval(-1)">
                  <span ng-show="IndicatorsRefreshInterval != -1" class="fa fa-circle-o"></span>
                  <span ng-show="IndicatorsRefreshInterval == -1" class="fa fa-dot-circle-o"></span>
                  ${Off}
                </a>
              </li>
              <li role="menuitem" ng-repeat="interval in [1, 3, 5, 10, 30, 45, 60, 120, 300, 600]">
                <a class="text-nowrap" ng-click="SetIndicatorsRefreshInterval(interval)">
                  <span ng-show="IndicatorsRefreshInterval != interval" class="fa fa-circle-o"></span>
                  <span ng-show="IndicatorsRefreshInterval == interval" class="fa fa-dot-circle-o"></span>
                  <span ng-show="interval<60">{{interval}} ${sec.}</span>
                  <span ng-show="interval>=60">{{interval/60}} ${min.}</span>
                </a>
              </li>
            </ul>
          </div>
          <div class="btn-group" uib-dropdown>
            <button id="processList" type="button" class="btn btn-default" uib-dropdown-toggle>
              <span class="glyphicon glyphicon-list-alt"></span>
              <span class="caret"></span>
            </button>
            <ul uib-dropdown-menu aria-labelledby="processList">
              <li role="menuitem">
                <a class="text-nowrap" ng-click="SetProcessesRefreshInterval(-1)">
                  <span ng-show="ProcessesRefreshInterval != -1" class="fa fa-circle-o"></span>
                  <span ng-show="ProcessesRefreshInterval == -1" class="fa fa-dot-circle-o"></span>
                  ${Off}
                </a>
              </li>
              <li role="menuitem" ng-repeat="interval in [1, 3, 5, 10, 30, 45, 60, 120, 300, 600, 1200, 1800, 3600]">
                <a class="text-nowrap" ng-click="SetProcessesRefreshInterval(interval)">
                  <span ng-show="ProcessesRefreshInterval != interval" class="fa fa-circle-o"></span>
                  <span ng-show="ProcessesRefreshInterval == interval" class="fa fa-dot-circle-o"></span>
                  <span ng-show="interval<60">{{interval}} ${sec.}</span>
                  <span ng-show="interval>=60">{{interval/60}} ${min.}</span>
                </a>
              </li>
            </ul>
          </div>
        </h2>
        
        <div class="clearfix"></div>

        <div class="row">
          <div class="col-xs-6 col-sm-6 col-md-4 col-lg-4">
            <h6 class="text-center">CPU</h6>
            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6" ng-repeat="cpu in CPU track by $index" ng-hide="$index==0">
              <span ng-show="$index == 0" class="label text-nowrap" ng-class="cpu >= 65 && cpu < 80 ? 'label-warning' : (cpu >= 80 ? 'label-danger' : 'label-primary')" style="width:100%; display: inline-block;">ALL: {{cpu}}%</span>
              <span ng-show="$index != 0" class="label text-nowrap" ng-class="cpu >= 65 && cpu < 80 ? 'label-warning' : (cpu >= 80 ? 'label-danger' : 'label-default')" style="width:100%; display: inline-block;">CPU{{$index}}: {{cpu}}%</span>
            </div>
          </div>
          <div class="col-xs-6 col-sm-6 col-md-4 col-lg-4">
            <h6 class="text-center">RAM</h6>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
              <span class="label label-primary text-nowrap" style="width:100%; display: inline-block;">${Total}: {{MemoryTotal}} ${Mb} ({{MemoryTotal / 1024 | CurrencyFormat : { decimalDigits: 2 } }} ${Gb})</span>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
              <span class="label text-nowrap" style="width:100%; display: inline-block;" ng-class="MemoryFreePercent <= 35 && MemoryFreePercent > 25 ? 'label-warning' : (MemoryFreePercent <= 25 ? 'label-danger' : 'label-success')">${Free}: {{MemoryFree}} ${Mb} ({{MemoryFree / 1024 | CurrencyFormat : { decimalDigits: 2 } }} ${Gb})</span>
            </div>
          </div>
          <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
            <h6 class="text-center">HDD</h6>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
              <span class="label label-primary text-nowrap" style="width:100%; display: inline-block;">${Total}: {{HDDTotal}} ${Gb}</span>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
              <span class="label label-success text-nowrap" style="width:100%; display: inline-block;">${Free}: {{HDDFree}} ${Gb}</span>
            </div>
          </div>
        </div>

        <hr />

        <div class="row">
          <div class="col-xs-6 col-sm-6 col-md-4 col-lg-4">
            <highchart id="cpu" config="ChartCPU" class="indicators"></highchart>
          </div>
          <div class="col-xs-6 col-sm-6 col-md-4 col-lg-4">
            <highchart id="memory" config="ChartMemory" class="indicators"></highchart>
          </div>
          <div class="col-xs-6 col-sm-6 col-md-4 col-lg-4">
            <highchart id="hdd" config="ChartHDD" class="indicators"></highchart>
          </div>
        </div>

        <hr />


        <div class="panel panel-default">
          <div class="panel-body form-inline">
            <div class="form-group">
              <input type="text" name="search" placeholder="${Process name or ID}" class="form-control" ng-model="SearchStringInput" />
            </div>
            <button class="btn btn-default" ng-disabled="Loading" ng-click="Search()">
              <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
              ${Search}
            </button>
            <button class="btn btn-default" ng-disabled="Loading" ng-click="ResetSearch()">
              <span class="glyphicon glyphicon-erase" aria-hidden="true"></span>
              ${Reset}
            </button>
            <span ng-show="Loading" ng-cloak>
              &nbsp;
              <span class="glyphicon glyphicon-refresh fa-spin"></span>
            </span>
          </div>
        </div>

        <div class="panel panel-default" ng-cloak>
          <div class="panel-body">
            <table class="table cell-align-middle">
              <thead>
                <tr>
                  <th class="col-xs-1 col-sm-1 col-md-1 col-lg-1 text-center">
                    <a ng-click="SortField = 'PID'; SortReverse = !SortReverse" class="text-nowrap">
                      ${PID}
                      <span ng-show="SortField == 'PID' && !SortReverse" class="fa fa-caret-down"></span>
                      <span ng-show="SortField == 'PID' && SortReverse" class="fa fa-caret-up"></span>
                    </a>
                  </th>
                  <th class="col-xs-10 col-sm-7 col-md-7 col-lg-3">
                    <a ng-click="SortField = 'Name'; SortReverse = !SortReverse" class="text-nowrap">
                      ${Process name}
                      <span ng-show="SortField == 'Name' && !SortReverse" class="fa fa-caret-down"></span>
                      <span ng-show="SortField == 'Name' && SortReverse" class="fa fa-caret-up"></span>
                    </a>
                  </th>
                  <th class="hidden-xs hidden-sm hidden-md col-lg-2">
                    <a ng-click="SortField = 'Username'; SortReverse = !SortReverse" class="text-nowrap">
                      ${User}
                      <span ng-show="SortField == 'Username' && !SortReverse" class="fa fa-caret-down"></span>
                      <span ng-show="SortField == 'Username' && SortReverse" class="fa fa-caret-up"></span>
                    </a>
                  </th>
                  <th class="hidden-xs col-sm-1 col-md-1 col-lg-1 text-center">
                    <a ng-click="SortField = 'Status'; SortReverse = !SortReverse" class="text-nowrap">
                      ${Status}
                      <span ng-show="SortField == 'Status' && !SortReverse" class="fa fa-caret-down"></span>
                      <span ng-show="SortField == 'Status' && SortReverse" class="fa fa-caret-up"></span>
                    </a>
                  </th>
                  <th class="hidden-xs col-sm-1 col-md-1 col-lg-1 text-center text-nowrap">
                    <a ng-click="SortField = 'CPU'; SortReverse = !SortReverse" class="text-nowrap">
                      ${CPU} (%)
                      <span ng-show="SortField == 'CPU' && !SortReverse" class="fa fa-caret-down"></span>
                      <span ng-show="SortField == 'CPU' && SortReverse" class="fa fa-caret-up"></span>
                    </a>
                  </th>
                  <th class="hidden-xs col-sm-1 col-md-1 col-lg-1 text-center text-nowrap">
                    <a ng-click="SortField = 'Memory'; SortReverse = !SortReverse" class="text-nowrap">
                      ${RAM} (${Mb})
                      <span ng-show="SortField == 'Memory' && !SortReverse" class="fa fa-caret-down"></span>
                      <span ng-show="SortField == 'Memory' && SortReverse" class="fa fa-caret-up"></span>
                    </a>
                  </th>
                  <th class="hidden-xs hidden-sm hidden-md col-lg-1 text-center text-nowrap">
                    <a ng-click="SortField = 'VSZ'; SortReverse = !SortReverse" class="text-nowrap">
                      ${VSZ} (${Mb})
                      <span ng-show="SortField == 'VSZ' && !SortReverse" class="fa fa-caret-down"></span>
                      <span ng-show="SortField == 'VSZ' && SortReverse" class="fa fa-caret-up"></span>
                    </a>
                  </th>
                  <th class="hidden-xs hidden-sm hidden-md col-lg-1 text-center text-nowrap">
                    <a ng-click="SortField = 'RSS'; SortReverse = !SortReverse" class="text-nowrap">
                      ${RSS} (${Mb})
                      <span ng-show="SortField == 'RSS' && !SortReverse" class="fa fa-caret-down"></span>
                      <span ng-show="SortField == 'RSS' && SortReverse" class="fa fa-caret-up"></span>
                    </a>
                  </th>
                  <th class="col-xs-1 col-sm-1 col-md-1 col-lg-1">&nbsp;</th>
                </tr>
              </thead>
              <tbody>
                <tr ng-repeat="process in Processes | orderBy : SortField : SortReverse" ng-class="GetProcessStatusCss(process.Status)">
                  <td class="text-nowrap text-center">{{process.PID}}</td>
                  <td class="text-nowrap">
                    <span popover-trigger="mouseenter" uib-popover="{{process.Command}}">
                      {{process.Name}}
                    </span>
                  </td>
                  <td class="hidden-xs hidden-sm hidden-md text-nowrap">{{process.Username}}</td>
                  <td class="hidden-xs text-center">
                    <span ng-show="!process.Loading">{{process.Status}}</span>
                    <span ng-show="process.Loading" class="glyphicon glyphicon-option-horizontal"></span>
                  </td>
                  <td class="hidden-xs text-nowrap text-center">{{process.CPU}}</td>
                  <td class="hidden-xs text-nowrap text-center">{{(MemoryTotal * process.Memory / 100) | CurrencyFormat : { decimalDigits: 2 } }}</td>
                  <td class="hidden-xs hidden-sm hidden-md text-nowrap text-center">{{(process.VSZ / 1024) | CurrencyFormat : { decimalDigits: 2 } }}</td>
                  <td class="hidden-xs hidden-sm hidden-md text-nowrap text-center">{{(process.RSS / 1024) | CurrencyFormat : { decimalDigits: 2 } }}</td>
                  <td class="text-center">
                    <a class="btn btn-danger btn-sm" ng-click="ConfirmKill(process)">
                      <span ng-show="process.Loading"><i class="glyphicon glyphicon-refresh fa-spin"></i></span>
                      <span ng-show="!process.Loading" class="glyphicon glyphicon-remove-circle"></span>
                      <span class="hidden-xs hidden-sm hidden-md">${Kill}</span>
                    </a>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <div class="well well-lg text-center" ng-show="!Loading && (Processes == null || Processes.length == 0)" ng-cloak>
          <p>${Procesess not found...}</p>
        </div>

        <?php
        include_once \Nemiro\Server::MapPath('~/monitoring/dialogs/confirmToKillProcess.php');
        ?>

      </div>

    </php:Content>

  </body>
</html>