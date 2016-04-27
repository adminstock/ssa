<?#Page Title="Sites / Editor" ?>
<html xmlns:php="http://aleksey.nemiro.ru/php-webforms">
  <body>

    <php:Content ID="MainContent">
      <div ng-controller="SiteEditorController">

        <div id="loading" class="alert alert-info ng-hide" ng-show="Loading" ng-cloak>
          <span class="glyphicon glyphicon-refresh fa-spin"></span>
          Loading data. Please wait...
        </div>

        <div id="saving" class="alert alert-info ng-hide" ng-show="Saving" ng-cloak>
          <span class="glyphicon glyphicon-refresh fa-spin"></span>
          Saving data. Please wait...
        </div>

        <div id="success" class="alert alert-success ng-hide" ng-show="!Saving && !Loading && Success" ng-cloak>
          <span class="glyphicon glyphicon-ok"></span>
          Data saved successfully!
        </div>

        <div class="panel panel-default ng-hide" ng-show="!Loading" ng-cloak>
          <div class="panel-heading"><h4>${Common}</h4></div>
          <div class="panel-body">
            <form id="siteForm" class="form-horizontal">
              <div class="form-group">
                <label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 control-label">${Site name}:</label>
                <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
                  <input type="text" class="form-control" ng-model="Site.Name" maxlength="100" autocomplete="off" placeholder="For example: example.org" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 control-label">${Status}:</label>
                <div class="col-xs-12 col-sm-9 col-md-10 col-lg-10"><input type="checkbox" ng-model="Site.IsEnabled" bs-switch switch-change="SiteStatusChanged()" switch-on-text="${On}" switch-off-text="${Off}" switch-on-color="success" switch-off-color="danger" /></div>
              </div>
            </form>
          </div>
        </div>

        <div class="panel panel-default ng-hide" ng-show="!Loading" ng-cloak>
          <div class="panel-heading"><h4>${Config}</h4></div>
          <div class="panel-body code-mirror-auto-height">
            <uib-tabset>
              <uib-tab ng-repeat="item in Site.Conf" select="TabClick(item.Level)" active="ActiveTab[item.Level]">
                <uib-tab-heading>
                  <input type="checkbox" ng-checked="item.Enabled" ng-click="ConfEnabledClick(item); $event.stopPropagation();" ng-disabled="!Site.IsEnabled" />
                  {{item.Level}} &nbsp;
                  <button type="button" class="close" ng-click="ConfirmDeleteConf(item)">×</button>
                </uib-tab-heading>
                <ui-codemirror ui-codemirror-opts="{ lineNumbers: true, matchBrackets: true, mode: '{{item.Level == 'Nginx' ? 'nginx' : 'xml'}}', theme: 'default' }" ng-model="item.Source" ui-refresh="EditorChanged"></ui-codemirror>
              </uib-tab>
              <uib-tab active="addConfTab" ng-click="PrepareAddConf()" ng-show="CanAddConf">
                <uib-tab-heading>
                  <span class="glyphicon glyphicon-plus"></span>
                </uib-tab-heading>
                <div class="panel panel-default">
                  <div class="panel-heading form-horizontal">
                    <div class="form-group">
                      <label class="col-xs-12 col-sm-3 col-md-2 col-lg-2 control-label">${Add}:</label>
                      <div class="col-xs-12 col-sm-8 col-md-9 col-lg-9">
                        <div class="btn-group" uib-dropdown>
                          <button id="btnAddConf" type="button" class="btn btn-default" uib-dropdown-toggle>
                            {{SelectedConfToAdd || '${Select one}'}}
                            <span class="caret"></span>
                          </button>
                          <ul uib-dropdown-menu aria-labelledby="btnAddConf">
                            <li role="menuitem" ng-repeat="level in AvailableLevels">
                              <a ng-click="SelectConfToAdd(level)">{{level}}</a>
                            </li>
                          </ul>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="panel-body form-horizontal" ng-show="SelectedConfToAdd">
                    <?php include_once \Nemiro\Server::MapPath('~/sites/masters/nginx.php'); ?>
                    <?php include_once \Nemiro\Server::MapPath('~/sites/masters/apache.php'); ?>
                    <?php include_once \Nemiro\Server::MapPath('~/sites/masters/htan.php'); ?>
                  </div>
                  <div class="panel-footer" ng-show="SelectedConfToAdd">
                    <div class="col-xs-offset-6 col-sm-offset-3 col-md-offset-2 col-lg-offset-2">
                      <button type="button" class="btn btn-default" ng-click="AddConf()">${Add}</button>
                    </div>
                  </div>
                </div>
              </uib-tab>
            </uib-tabset>
          </div>
        </div>

        <div class="btn-group" uib-dropdown>
          <button id="split-button" type="button" class="btn btn-primary" ng-disabled="Saving" ng-click="Save(false)">
            <span ng-show="Saving"><i class="glyphicon glyphicon-refresh fa-spin"></i></span>
            <span ng-show="!Saving"><i class="glyphicon glyphicon-download"></i></span>
            ${Save}
          </button>
          <button id="split-button" type="button" class="btn btn-primary" uib-dropdown-toggle ng-disabled="Saving">
            <span class="caret"></span>
            <span class="sr-only">${Save}</span>
          </button>
          <ul class="dropdown-menu" uib-dropdown-menu role="menu" aria-labelledby="split-button">
            <li role="menuitem">
              <a ng-click="Save(false)">
                ${Save}
              </a>
            </li>
            <li role="menuitem">
              <a ng-click="Save(true)">
                ${Save and Reload}
              </a>
            </li>
          </ul>
        </div>

        <a href="/sites" class="btn btn-default pull-right" ng-disabled="Saving">
          ${Back to list}
        </a>

        <hr />

        <uib-accordion ng-show="ActiveTab['Apache'] || ActiveTab['Nginx'] || ActiveTab['HTAN']">
          <uib-accordion-group is-open="Help.IsOpened">
            <uib-accordion-heading>
              <span class='glyphicon glyphicon-info-sign'></span> ${Help}
              <span class="glyphicon" ng-class="{'glyphicon-chevron-down': Help.IsOpened, 'glyphicon-chevron-right': !status.open}"></span>
            </uib-accordion-heading>
            <div class="panel-body">
              <div ng-show="ActiveTab['Apache']">
                <?php include_once \Nemiro\Server::MapPath('~/sites/help/apache.php'); ?>
              </div>
              <div ng-show="ActiveTab['Nginx']">
                <?php include_once \Nemiro\Server::MapPath('~/sites/help/nginx.php'); ?>
              </div>
              <div ng-show="ActiveTab['HTAN']">
                <?php include_once \Nemiro\Server::MapPath('~/sites/help/htan.php'); ?>
              </div>
              <hr />
              <button type="button" class="btn btn-default btn-sm" ng-click="Help.IsOpened = !Help.IsOpened">${Hide}</button>
            </div>
          </uib-accordion-group>
        </uib-accordion>

        <?php
        include_once \Nemiro\Server::MapPath('~/sites/dialogs/confirmToDeleteConf.php');
        include_once \Nemiro\Server::MapPath('~/sites/dialogs/createConfig.php');
        include_once \Nemiro\Server::MapPath('~/sites/dialogs/selectFolder.php');
        include_once \Nemiro\Server::MapPath('~/sites/dialogs/createFolder.php');
        include_once \Nemiro\Server::MapPath('~/sites/dialogs/confirmToDeleteFolder.php');
        include_once \Nemiro\Server::MapPath('~/sites/dialogs/reloading.php');
        ?>
      </div>
    </php:Content>

  </body>
</html>