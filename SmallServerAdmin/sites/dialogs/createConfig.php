<div id="createConfig" class="modal" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3>${Create}</h3>
      </div>
      <div class="modal-body">
        <form class="form-horizontal">
          <div class="form-group">
            <label class="col-xs-12 col-sm-12 col-md-12 col-lg-12">${Domain}:</label>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
              <input type="text" class="form-control" ng-model="CreateNew.Domain" maxlength="100" autocomplete="off" placeholder="For example: example.org" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-xs-12 col-sm-12 col-md-12 col-lg-12">${Root path}:</label>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
              <div class="input-group">
                <input type="text" class="form-control" ng-model="CreateNew.SelectedPath" autocomplete="off" />
                <div class="input-group-btn"><a class="btn btn-silver" ng-click="ShowSelectFolder(CreateNew)">...</a></div>
              </div>
            </div>
          </div>
          <uib-accordion>
            <uib-accordion-group heading="PHP">
              <div class="form-group">
                <label class="col-xs-12 col-sm-4 col-md-3 col-lg-3 control-label">PHP:</label>
                <div class="col-xs-12 col-sm-8 col-md-9 col-lg-9">
                  <div class="btn-group">
                    <label class="btn btn-default" ng-model="CreateNew.PhpMode" uib-btn-radio="'Off'">${Off}</label>
                    <label class="btn btn-default" ng-model="CreateNew.PhpMode" uib-btn-radio="'FPM'">PHP-FPM</label>
                    <label class="btn btn-default" ng-model="CreateNew.PhpMode" uib-btn-radio="'MOD'">Mod-PHP</label>
                  </div>
                </div>
              </div>
            </uib-accordion-group>
            <uib-accordion-group heading="ASP.NET">
              <div class="form-group">
                <label class="col-xs-12 col-sm-4 col-md-3 col-lg-3 control-label">ASP.NET:</label>
                <div class="col-xs-12 col-sm-8 col-md-9 col-lg-9">
                  <div class="btn-group">
                    <label class="btn btn-default" ng-model="CreateNew.AspNetMode" uib-btn-radio="'Off'">${Off}</label>
                    <label class="btn btn-default" ng-model="CreateNew.AspNetMode" uib-btn-radio="'FASTCGI'">FastCGI</label>
                    <label class="btn btn-default" ng-model="CreateNew.AspNetMode" uib-btn-radio="'MOD'">Mod-Mono</label>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label class="col-xs-12 col-sm-4 col-md-3 col-lg-3 control-label">${Version}:</label>
                <div class="col-xs-12 col-sm-8 col-md-9 col-lg-9">
                  <div class="btn-group">
                    <label class="btn btn-default" ng-model="CreateNew.AspNetVersion" uib-btn-radio="'4.5'">4.5</label>
                    <label class="btn btn-default" ng-model="CreateNew.AspNetVersion" uib-btn-radio="'4.0'">4.0</label>
                    <label class="btn btn-default" ng-model="CreateNew.AspNetVersion" uib-btn-radio="'2.0'">2.0</label>
                    <label class="btn btn-default" ng-model="CreateNew.AspNetVersion" uib-btn-radio="'1.0'">1.0</label>
                  </div>
                </div>
              </div>
              <div class="form-group" ng-show="CreateNew.AspNetMode == 'MOD'">
                <label class="col-xs-12 col-sm-4 col-md-3 col-lg-3 control-label">MONO_IOMAP:</label>
                <div class="col-xs-12 col-sm-8 col-md-9 col-lg-9">
                  <input type="checkbox" ng-model="CreateNew.AspNetIOMapForAll" bs-switch switch-on-text="${On}" switch-off-text="${Off}" switch-on-color="success" switch-off-color="danger" />
                </div>
              </div>
              <div class="form-group" ng-show="CreateNew.AspNetMode == 'MOD'">
                <label class="col-xs-12 col-sm-4 col-md-3 col-lg-3 control-label">${Debug}:</label>
                <div class="col-xs-12 col-sm-8 col-md-9 col-lg-9">
                  <input type="checkbox" ng-model="CreateNew.AspNetDebug" bs-switch switch-on-text="${On}" switch-off-text="${Off}" switch-on-color="success" switch-off-color="danger" />
                </div>
              </div>
              <div class="form-group" ng-show="CreateNew.AspNetMode == 'FASTCGI'">
                <label class="col-xs-12 col-sm-4 col-md-3 col-lg-3 control-label">${Processes}:</label>
                <div class="col-xs-6 col-sm-3 col-md-3 col-lg-3">
                  <select class="form-control" ng-model="CreateNew.AspNetFastCgiProcessCount" ng-options="i for i in [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12]">
                  </select>
                </div>
              </div>
              <div class="form-group" ng-show="CreateNew.AspNetMode == 'FASTCGI'">
                <label class="col-xs-12 col-sm-4 col-md-3 col-lg-3 control-label">${Socket}:</label>
                <div class="col-xs-12 col-sm-8 col-md-9 col-lg-9">
                  <div class="btn-group">
                    <label class="btn btn-default" ng-model="CreateNew.AspNetSocket" uib-btn-radio="'Unix'">Unix</label>
                    <label class="btn btn-default" ng-model="CreateNew.AspNetSocket" uib-btn-radio="'TCP'">TCP</label>
                  </div>
                </div>
              </div>
            </uib-accordion-group>
          </uib-accordion>
        </form>
      </div>
      <div class="modal-footer">
        <button class="btn btn-primary" ng-click="Create()" ng-disabled="CreateNew.Domain == '' || CreateNew.RootPath == '' || CreateNew.RootPath == '/home' || CreateNew.RootPath == '/home/'">${Create}</button>
        <button class="btn btn-default" ng-click="CreateDefault()" data-dismiss="modal" aria-hidden="true">${Cancel}</button>
      </div>
    </div>
  </div>
</div>