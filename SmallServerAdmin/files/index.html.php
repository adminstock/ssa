<?#Page Title="File Manager" ?>
<html xmlns:php="http://aleksey.nemiro.ru/php-webforms">
  <body>

    <php:Content ID="MainContent">
      <div ng-controller="FileListController">

        <h2 class="pull-left">
          File Manager 
          <span class="ng-hide" ng-show="Loading" ng-cloak>
            <span class="glyphicon glyphicon-refresh fa-spin"></span>
          </span>
        </h2>

        <h2 class="btn-group pull-right" uib-dropdown>
          <button id="indicators" type="button" class="btn btn-default" uib-dropdown-toggle>
            <span class="glyphicon glyphicon-plus"></span> ${Create}
            <span class="caret"></span>
          </button>
          <ul uib-dropdown-menu aria-labelledby="indicators">
            <li role="menuitem">
              <a class="text-nowrap" ng-click="NewFile()">
                <span class="glyphicon glyphicon-file"></span>
                ${New file}
              </a>
            </li>
            <li role="menuitem">
              <a class="text-nowrap" ng-click="NewFolder()">
                <span class="glyphicon glyphicon-folder-close"></span>
                ${New folder}
              </a>
            </li>
          </ul>
        </h2>

        <div class="clearfix"></div>

        <div>
          <div class="form-group">

            <button class="btn btn-default" ng-disabled="Loading" ng-click="Search()" ng-hide="true">
              <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
              Search
            </button>
          </div>
          <div class="form-group">
            <input type="text" name="search" class="form-control" readonly="readonly" ng-model="SelectedItem.Path" />
          </div>
        </div>

        <div class="panel panel-default">
          <div class="panel-body">
            <treecontrol class="tree-classic" tree-model="Items" 
              options="Options" 
              on-node-toggle="ToggleItem(node, expanded)" 
              on-selection="ShowSelectedItem(node, selected, $parentNode)"
              selected-node="SelectedItem"
              expanded-nodes="ExpandedItems"
            >
              <span ng-hide="node.RenameMode">
                {{node.Name}}
                <small class="fa fa-angle-right gray"></small>
                <small class="gray" ng-show="node.Size < 1024">{{node.Size}} ${bytes}</small>
                <small class="gray" ng-show="node.Size >= 1024 && node.Size < 1048576">{{node.Size / 1024 | CurrencyFormat : { decimalDigits: 2 } }} ${KiB}</small>
                <small class="gray" ng-show="node.Size >= 1048576 && node.Size < 1073741824">{{node.Size / 1024 / 1024 | CurrencyFormat : { decimalDigits: 2 } }} ${MiB}</small>
                <small class="gray" ng-show="node.Size >= 1073741824">{{node.Size / 1024 / 1024 / 1024 | CurrencyFormat : { decimalDigits: 2 } }} ${GiB}</small>
                <span ng-show="node.Loading">
                  <span class="fa fa-spinner fa-pulse"></span>
                </span>
                <span ng-show="SelectedItem == node">
                  <!--<span class="glyphicon glyphicon-option-horizontal"></span>-->
                  <span class="btn-group" uib-dropdown>
                    <button id="{{node.Path}}" type="button" class="btn btn-default btn-xs" uib-dropdown-toggle>
                      <span class="caret"></span>
                    </button>
                    <ul uib-dropdown-menu aria-labelledby="{{node.Path}}">
                      <li ng-show="node.Type == 'Folder'">
                        <a class="text-nowrap" ng-click="NewFile()">
                          <span class="glyphicon glyphicon-file"></span>
                          ${New file}
                        </a>
                      </li>
                      <li ng-show="node.Type == 'Folder'">
                        <a class="text-nowrap" ng-click="NewFolder()">
                          <span class="glyphicon glyphicon-folder-close"></span>
                          ${New folder}
                        </a>
                      </li>
                      <li ng-show="node.Type == 'File'">
                        <a ng-click="Open(node)">
                          <span class="fa fa-file-text-o"></span>
                          ${Open}
                        </a>
                      </li>
                      <li ng-show="node.Type == 'File'">
                        <a ng-click="Open(node, 'hex')">
                          <span class="fa fa-file-code-o"></span>
                          ${HEX}
                        </a>
                      </li>
                      <li ng-show="node.Type == 'File'">
                        <a ng-click="ShowConfirmExecution(node)">
                          <span class="glyphicon glyphicon-play"></span>
                          ${Execute}
                        </a>
                      </li>
                      <li class="divider" ng-show="false"></li><!--node.Type == 'File'-->
                      <li ng-show="false">
                        <a ng-click="Download(node)">
                          <span class="fa fa-download"></span>
                          ${Download}
                        </a>
                      </li>
                      <li class="divider"></li><!-- ng-show="node.Type == 'File'"-->
                      <li>
                        <a ng-click="node.NewName = node.Name; node.RenameMode = true">
                          <span class="glyphicon glyphicon-edit"></span>
                          ${Rename}
                        </a>
                      </li>
                      <li>
                        <a ng-click="ShowConfirmToDelete(node)">
                          <span class="glyphicon glyphicon-trash"></span>
                          ${Delete}
                        </a>
                      </li>
                      <li class="divider"></li>
                      <li role="menuitem">
                        <a ng-click="Properties(node)">
                          <span class="glyphicon glyphicon-info-sign"></span>
                          ${Properties}
                        </a>
                      </li>
                    </ul>
                  </span>
                </span>
              </span>
              <span class="form-inline form-group" ng-show="node.RenameMode">
                <span class="input-group">
                  <input type="text" ng-model="node.NewName" class="form-control" ng-disabled="node.Loading" />
                  <span class="input-group-btn">
                    <button type="button" class="btn btn-success" title="Save" ng-disabled="node.NewName == '' || node.NewName == node.Name || node.Loading" ng-click="Rename(node)"><span class="glyphicon glyphicon-ok"></span></button>
                    <button type="button" class="btn btn-danger" title="Cancel" ng-click="node.RenameMode = false" ng-disabled="node.Loading"><span class="glyphicon glyphicon-remove"></span></button>
                    <span class="btn" ng-show="node.Loading"><span class="glyphicon glyphicon-refresh fa-spin"></span></span>
                  </span>
                </span>
              </span>
            </treecontrol>
          </div>
        </div>

        <?php
        include_once  \Nemiro\Server::MapPath('~/files/dialogs/confirmToDeleteItem.php');
        include_once  \Nemiro\Server::MapPath('~/files/dialogs/editItem.php');
        include_once  \Nemiro\Server::MapPath('~/files/dialogs/saveAs.php');
        include_once  \Nemiro\Server::MapPath('~/files/dialogs/createFolder.php');
        include_once  \Nemiro\Server::MapPath('~/files/dialogs/confirmToOverwriteFile.php');
        include_once  \Nemiro\Server::MapPath('~/files/dialogs/confirmToExecuteFile.php');
        include_once  \Nemiro\Server::MapPath('~/files/dialogs/executionResult.php');
        include_once  \Nemiro\Server::MapPath('~/files/dialogs/properties.php');
        include_once  \Nemiro\Server::MapPath('~/files/treeViewTemplate.html');
        ?>

      </div>

    </php:Content>

  </body>
</html>