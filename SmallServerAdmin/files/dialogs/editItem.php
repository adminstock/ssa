<div id="editItem" class="modal" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="btn btn-default btn-xs" ng-click="Save(null, true)" ng-hide="EditableFile.IsNew" ng-disabled="EditableFile.Loading || FileViewMode != ''">
          <span class="glyphicon glyphicon-floppy-disk" ng-show="!EditableFile.Loading"></span>
          <span ng-show="EditableFile.Loading"><span class="glyphicon glyphicon-refresh fa-spin"></span></span>
          ${Save}
        </button>
        <button type="button" class="btn btn-default btn-xs" ng-click="SaveAs()" ng-disabled="EditableFile.Loading || FileViewMode != ''">
          ${Save As...}
        </button>
        <button type="button" class="btn btn-default btn-xs" ng-click="Reopen()" ng-hide="EditableFile.IsNew" ng-disabled="EditableFile.Loading">
          <span class="glyphicon glyphicon-refresh" ng-show="!EditableFile.Loading"></span>
          <span ng-show="EditableFile.Loading"><span class="glyphicon glyphicon-refresh fa-spin"></span></span>
          ${Reopen}
        </button>
        <span ng-show="EditableFile.Loading">${Saving...}</span>
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true" ng-show="!EditableFile.Loading">×</button>
        <h3>
          {{EditableFile.Name}}
          <span class="btn-group" uib-dropdown>
            <button id="editorMode" type="button" class="btn btn-silver btn-xs" uib-dropdown-toggle>
              {{EditorMode}}
              <span class="caret"></span>
            </button>
            <ul uib-dropdown-menu aria-labelledby="editorMode">
              <li><a ng-click="SetEditorMode(null)">Plain</a></li>
              <li><a ng-click="SetEditorMode('shell')">Shell</a></li>
              <li><a ng-click="SetEditorMode('xml')">XML</a></li>
              <li><a ng-click="SetEditorMode('properties')">INI</a></li>
              <li><a ng-click="SetEditorMode('markdown')">Markdown</a></li>
              <li><a ng-click="SetEditorMode('htmlmixed')">HTML</a></li>
							<li><a ng-click="SetEditorMode('javascript')">JavaScript</a></li>
              <li><a ng-click="SetEditorMode('css')">CSS</a></li>
              <li><a ng-click="SetEditorMode('php')">PHP</a></li>
							<li><a ng-click="SetEditorMode('perl')">Perl</a></li>
							<li><a ng-click="SetEditorMode('python')">Python</a></li>
							<li><a ng-click="SetEditorMode('sql')">SQL</a></li>
              <li><a ng-click="SetEditorMode('http')">HTTP</a></li>
              <li><a ng-click="SetEditorMode('nginx')">Nginx</a></li>
							<li><a ng-click="SetEditorMode('clike')">C</a></li>
            </ul>
          </span>
          <button type="button" class="btn btn-default btn-xs" ng-click="CurrentFileContent = ''" ng-disabled="EditableFile.Loading" title="${Clear content}">
            <span class="glyphicon glyphicon-erase"></span>
          </button>
        </h3>
      </div>
      <div class="modal-body">
        <ui-codemirror ui-codemirror-opts="{ lineNumbers: true, theme: 'default', autoRefresh: true, onLoad: Editor_Loaded }" ng-model="CurrentFileContent" ng-disabled="EditableFile.Loading"></ui-codemirror>
      </div>
    </div>
  </div>
</div>