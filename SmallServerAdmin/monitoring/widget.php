<div ng-controller="MonitoringController" ng-init="ProcessesRefreshInterval = 0;">
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

  <div class="pull-right">
    <button type="button" class="btn btn-default btn-xs" ng-click="Refresh()" ng-disabled="RefreshDisabled"><span class="glyphicon glyphicon-refresh"></span></button>
    <div class="btn-group" uib-dropdown>
      <button id="indicators" type="button" class="btn btn-default btn-xs" uib-dropdown-toggle>
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
  </div>

  <div class="clearfix"></div>

</div>