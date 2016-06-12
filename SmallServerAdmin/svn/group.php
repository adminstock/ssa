<div id="svnGroup" class="modal" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3>{{SourceGroup.Name}}</h3>
      </div>
      <div class="modal-body">
        <form id="svnGroupEditor" class="form-horizontal">
          <fieldset>
            <div class="form-group">
              <label class="col-xs-12 col-sm-12 col-md-12 col-lg-12">${Group name}:</label>
              <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <input type="text" class="form-control" ng-model="CurrentGroup.Name" maxlength="50" required="required" autocomplete="off" />
              </div>
            </div>
            <div class="form-group">
              <label class="col-xs-12 col-sm-12 col-md-12 col-lg-12">${Members}:</label>
              <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="overflow-y:auto; max-height:275px;">
                <ul class="list-group" ng-show="Users" ng-cloak>
                  <li ng-repeat="user in Users" class="list-group-item checkbox"><label><input type="checkbox" ng-checked="CurrentGroup.Members != null && CurrentGroup.Members.indexOf(user) > -1" ng-click="UserClick(user)" /> {{user}}</label></li>
                </ul>
                <div ng-show="!Users" ng-cloak>
                  <p>${No users...}</p>
                </div>
              </div>
            </div>
          </fieldset>
        </form>
      </div>
      <div class="modal-footer">
        <button class="btn btn-primary" ng-click="SaveGroup()">${Save}</button>
        <button class="btn btn-default" data-dismiss="modal" aria-hidden="true">${Cancel}</button>
      </div>
    </div>
  </div>
</div>