<div id="confirmToCopyItems" class="modal" role="dialog" data-not-restore="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h3>${Copy}</h3>
			</div>
			<div class="modal-body">
				<div class="form-group">
					<label>${Target path}:</label>
					<input type="text" ng-model="MoveTargetPath" class="form-control" />
				</div>
				<div class="form-group">
					<div class="btn-group">
						<label class="btn btn-default" ng-model="CopyItemsLinksMode" uib-btn-radio="'None'">${copy}</label>
						<label class="btn btn-default" ng-model="CopyItemsLinksMode" uib-btn-radio="'Symbolic'">${create symbolic links} (-s)</label>
						<label class="btn btn-default" ng-model="CopyItemsLinksMode" uib-btn-radio="'Hard'">${create hard links} (-l)</label>
					</div>
				</div>
				<div class="form-group">
					<div class="btn-group">
						<label class="btn btn-default" ng-model="CopyItemsMode" uib-btn-radio="'Force'">${overwrite existing} (-f)</label>
						<label class="btn btn-default" ng-model="CopyItemsMode" uib-btn-radio="'NoClobber'">${skip existing} (-n)</label>
						<label class="btn btn-default" ng-model="CopyItemsMode" uib-btn-radio="'Update'">${update for newer} (-u)</label>
					</div>
				</div>
				<div class="checkbox">
					<label>
						<input type="checkbox" ng-model="CopyItemsRecursive"> ${recursive} (-r)
					</label>
				</div>
				<div class="checkbox">
					<label>
						<input type="checkbox" ng-model="CopyItemsBackup"> ${make a backup copy of the target files} (-b)
					</label>
				</div>
			</div>
			<div class="modal-footer">
				<button class="btn btn-primary" ng-click="CopyItems()">${Execute}</button>
				<button class="btn btn-default" data-dismiss="modal" aria-hidden="true" ng-click="CloseConfirmItems()">${Cancel}</button>
			</div>
		</div>
	</div>
</div>