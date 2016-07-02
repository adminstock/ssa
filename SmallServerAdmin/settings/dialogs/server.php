<div id="serverDialog" class="modal" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true" ng-show="!SavingServer && !LoadingServer">×</button>
				<h3>${Server}</h3>
      </div>
      <div class="modal-body">
        <div ng-show="LoadingServer">
					<span><span class="glyphicon glyphicon-refresh fa-spin"></span></span> ${Loading config of the server. Please wait...}
        </div>
        <form class="form-horizontal" ng-hide="LoadingServer" ng-cloak>
					<uib-accordion>
						<uib-accordion-group heading="SSH" is-open="Accordion.SshOpened">
							<div class="form-group">
								<label class="col-xs-12 col-sm-4 col-md-3 col-lg-3 control-label">${Host}:</label>
								<div class="col-xs-12 col-sm-8 col-md-9 col-lg-9">
									<input type="text" class="form-control" ng-model="Server.Address" maxlength="100" required="required" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-xs-12 col-sm-4 col-md-3 col-lg-3 control-label">${Port}:</label>
								<div class="col-xs-12 col-sm-6 col-md-5 col-lg-3">
									<input type="number" max="65535" min="1" class="form-control" ng-model="Server.Port" required="required" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-xs-12 col-sm-4 col-md-3 col-lg-3 control-label">${Login}:</label>
								<div class="col-xs-12 col-sm-8 col-md-9 col-lg-9">
									<input type="text" class="form-control" ng-model="Server.Username" maxlength="100" required="required" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-xs-12 col-sm-4 col-md-3 col-lg-3 control-label">${Password}:</label>
								<div class="col-xs-12 col-sm-8 col-md-9 col-lg-9">
									<input type="password" class="form-control" ng-model="Server.Password" maxlength="200" required="required" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-xs-12 col-sm-12 col-md-3 col-lg-3 control-label hidden-xs hidden-sm">&nbsp;</label>
								<div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
									<label>
										<input type="checkbox" ng-model="Server.RequiredPassword" /> ${always requires a password (recommended)}
									</label>
								</div>
							</div>
						</uib-accordion-group>
						<uib-accordion-group heading="${Info}" is-open="Accordion.InfoOpened">
							<div class="form-group">
								<label class="col-xs-12 col-sm-4 col-md-3 col-lg-3 control-label">${Name}:</label>
								<div class="col-xs-12 col-sm-8 col-md-9 col-lg-9">
									<input type="text" class="form-control" ng-model="Server.Name" maxlength="50" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-xs-12 col-sm-4 col-md-3 col-lg-3 control-label">${Description}:</label>
								<div class="col-xs-12 col-sm-8 col-md-9 col-lg-9">
									<textarea class="form-control" ng-model="Server.Description" rows="3"></textarea>
								</div>
							</div>
						</uib-accordion-group>
						<uib-accordion-group heading="${Modules}" is-open="Accordion.ModulesOpened">
              <div ng-show="LoadingModules">
								<span class="glyphicon glyphicon-refresh fa-spin"></span>
								${Loading...}
              </div>
              <table class="table table-hover" ng-show="!LoadingModules" ng-cloak>
                <thead>
                  <tr>
                    <th>
											<label>
												<input type="checkbox" ng-click="SelectModules($event)" ng-checked="AllModulesSelected" /> &nbsp;
												${All Modules}
											</label>
                    </th>
                    <!--TODO: module config-->
                  </tr>
                </thead>
                <tbody dnd-list="Server.Modules">
									<tr ng-repeat="module in Server.Modules" dnd-draggable="module" dnd-effect-allowed="move" dnd-moved="ModuleMoved($index, module)">
										<td>
										  <label style="font-weight: normal;">
											  <input type="checkbox" ng-checked="module.Enabled" ng-click="ModuleClick(module)" /> &nbsp;
												{{module.Name}}
											</label>
										</td>
									</tr>
                </tbody>
              </table>
						</uib-accordion-group>
					</uib-accordion>
        </form>
      </div>
      <div class="modal-footer">
        <span ng-show="Server.Saving"><span class="glyphicon glyphicon-refresh fa-spin"></span></span>
        <button class="btn btn-primary" ng-click="SaveServer()" ng-disabled="SavingServer || LoadingServer || !Server.Address || Server.Address == '' || !Server.Username || Server.Username == '' || !Server.Port || Server.Port <= 0">${Save}</button>
        <button class="btn btn-default" data-dismiss="modal" aria-hidden="true" ng-disabled="SavingServer || LoadingServer">${Cancel}</button>
      </div>
    </div>
  </div>
</div>