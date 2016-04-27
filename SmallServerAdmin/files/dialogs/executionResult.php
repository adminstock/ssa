<div id="executionResult" class="modal" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3>${Result}</h3>
      </div>
      <div class="modal-body">
        <ui-codemirror ui-codemirror-opts="{ lineNumbers: true, theme: 'default', readOnly: true, mode: 'shell' }" ng-model="ExecutionResult" ui-refresh="!SelectedItem.Loading"></ui-codemirror>
      </div>
    </div>
  </div>
</div>