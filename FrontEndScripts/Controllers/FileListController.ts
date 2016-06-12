/*
 * Copyright © Aleksey Nemiro, 2016. All rights reserved.
 * 
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 * 
 * http://www.apache.org/licenses/LICENSE-2.0
 * 
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
module SmallServerAdmin.Controllers {

  /**
   * Represents the file manager controller.
   */
  export class FileListController implements Nemiro.IController {

    //#region Properties

    public Scope: any;
    public Context: Nemiro.AngularContext;

    private SourceTitle: string;

    /** Gets or sets window title (document.title). */
    public get Title(): string {
      return this.Context.Window.document.title;
    }
    public set Title(value: string) {
      if (value !== undefined && value != null && value != '') {
        this.Context.Window.document.title = value + ' - ' + this.SourceTitle;
      }
      else {
        this.Context.Window.document.title = this.SourceTitle;
      }
    }

    /** The list of files and folders. */
    public get Items(): Array<Models.FileSystemItem> {
      return this.Scope.Items;
    }
    public set Items(value: Array<Models.FileSystemItem>) {
      this.Scope.Items = value;
    }

    public get ExpandedItems(): Array<Models.FileSystemItem> {
      return this.Scope.ExpandedItems;
    }
    public set ExpandedItems(value: Array<Models.FileSystemItem>) {
      this.Scope.ExpandedItems = value;
    }

    /** Search string. */
    public get SearchString(): string {
      return this.Scope.SearchString;
    }
    public set SearchString(value: string) {
      this.Scope.SearchString = value;
    }

    /** Loading indicator. */
    public get Loading(): boolean {
      return this.Scope.Loading;
    }
    public set Loading(value: boolean) {
      this.Scope.Loading = value;
    }

    public get SelectedItem(): Models.FileSystemItem {
      return this.Scope.SelectedItem;
    }
    public set SelectedItem(value: Models.FileSystemItem) {
      this.Scope.SelectedItem = value;
    }

    public get SelectedItemToDelete(): Models.FileSystemItem {
      return this.Scope.SelectedItemToDelete;
    }
    public set SelectedItemToDelete(value: Models.FileSystemItem) {
      this.Scope.SelectedItemToDelete = value;
    }

    public get EditableFile(): Models.FileSystemItem {
      return this.Scope.EditableFile;
    }
    public set EditableFile(value: Models.FileSystemItem) {
      this.Scope.EditableFile = value;
    }

    public get ConfirmItemNameToRemove(): string {
      return this.Scope.ConfirmItemNameToRemove;
    }
    public set ConfirmItemNameToRemove(value: string) {
      this.Scope.ConfirmItemNameToRemove = value;
    }

    public get CurrentItem(): Models.FileSystemItem {
      return this.Scope.CurrentItem;
    }
    public set CurrentItem(value: Models.FileSystemItem) {
      this.Scope.CurrentItem = value;
    }

    public get CurrentFileContent(): string {
      return this.Scope.CurrentFileContent;
    }
    public set CurrentFileContent(value: string) {
      this.Scope.CurrentFileContent = value;
    }

    public get EditorMode(): string {
      return this.Scope.EditorMode;
    }
    public set EditorMode(value: string) {
      this.Scope.EditorMode = (value == null || value == '' ? 'plain' : value);
    }

    public get FileViewMode(): string {
      return this.Scope.FileViewMode;
    }
    public set FileViewMode(value: string) {
      this.Scope.FileViewMode = value;
    }

    public get SaveAsPath(): string {
      return this.Scope.SaveAsPath;
    }
    public set SaveAsPath(value: string) {
      this.Scope.SaveAsPath = value;
    }

    public get SaveAsOwnerName(): string {
      return this.Scope.SaveAsOwnerName;
    }
    public set SaveAsOwnerName(value: string) {
      this.Scope.SaveAsOwnerName = value;
    }

    public get SaveAsGroupName(): string {
      return this.Scope.SaveAsGroupName;
    }
    public set SaveAsGroupName(value: string) {
      this.Scope.SaveAsGroupName = value;
    }

    public get ExecuteArguments(): string {
      return this.Scope.ExecuteArguments;
    }
    public set ExecuteArguments(value: string) {
      this.Scope.ExecuteArguments = value;
    }

    public get ExecuteAs(): string {
      return this.Scope.ExecuteAs;
    }
    public set ExecuteAs(value: string) {
      this.Scope.ExecuteAs = value;
    }

    public get ExecutionResult(): string {
      return this.Scope.ExecutionResult;
    }
    public set ExecutionResult(value: string) {
      this.Scope.ExecutionResult = value;
    }

    public get FileInfoSource(): Models.FileSystemItemInfo {
      return this.Scope.FileInfoSource;
    }
    public set FileInfoSource(value: Models.FileSystemItemInfo) {
      this.Scope.FileInfoSource = value;
    }

    public get FileInfo(): Models.FileSystemItemInfo {
      return this.Scope.FileInfo;
    }
    public set FileInfo(value: Models.FileSystemItemInfo) {
      this.Scope.FileInfo = value;
    }

    public get UpdateRecursive(): boolean {
      return this.Scope.UpdateRecursive;
    }
    public set UpdateRecursive(value: boolean) {
      this.Scope.UpdateRecursive = value;
    }

    public get FileInfoChanged(): boolean {
      return this.Scope.FileInfoChanged;
    }
    public set FileInfoChanged(value: boolean) {
      this.Scope.FileInfoChanged = value;
    }

    //#region new folder

    public get NewFolderName(): string {
      return this.Scope.NewFolderName;
    }
    public set NewFolderName(value: string) {
      this.Scope.NewFolderName = value;
    }

    public get NewFolderOwnerName(): string {
      return this.Scope.NewFolderOwnerName;
    }
    public set NewFolderOwnerName(value: string) {
      this.Scope.NewFolderOwnerName = value;
    }

    public get NewFolderGroupName(): string {
      return this.Scope.NewFolderGroupName;
    }
    public set NewFolderGroupName(value: string) {
      this.Scope.NewFolderGroupName = value;
    }

    public get NewFolderCreateParents(): boolean {
      return this.Scope.NewFolderCreateParents;
    }
    public set NewFolderCreateParents(value: boolean) {
      this.Scope.NewFolderCreateParents = value;
    }

    public get CreationFolder(): boolean {
      return this.Scope.CreationFolder;
    }
    public set CreationFolder(value: boolean) {
      this.Scope.CreationFolder = value;
    }

    //#endregion
    //#region multiple selected items

    private SelectedItemsAction: string;

    public get SelectedItems(): Array<string> {
      return this.Scope.SelectedItems;
    }
    public set SelectedItems(value: Array<string>) {
      this.Scope.SelectedItems = value;
    }

    /**
      * Complete status: Success | Fail
      */
    public get SelectedItemsCompleted(): Array<string> {
      return this.Scope.SelectedItemsCompleted;
    }
    public set SelectedItemsCompleted(value: Array<string>) {
      this.Scope.SelectedItemsCompleted = value;
    }

    public get MoveTargetPath(): string {
      return this.Scope.MoveTargetPath;
    }
    public set MoveTargetPath(value: string) {
      this.Scope.MoveTargetPath = value;
    }

    /**
      * Force | NoClobber
      */
    public get MoveItemsMode(): string {
      return this.Scope.MoveItemsMode;
    }
    public set MoveItemsMode(value: string) {
      this.Scope.MoveItemsMode = value;
    }

    public get MoveItemsBackup(): boolean {
      return this.Scope.MoveItemsBackup;
    }
    public set MoveItemsBackup(value: boolean) {
      this.Scope.MoveItemsBackup = value;
    }

    /** Unique session id for backup suffix. */
    private MoveBackupSessionId: string;

    /**
      * Force | NoClobber | Update
      */
    public get CopyItemsMode(): string {
      return this.Scope.CopyItemsMode;
    }
    public set CopyItemsMode(value: string) {
      this.Scope.CopyItemsMode = value;
    }

    /**
      * Copy | Symbolic | Hard
      */
    public get CopyItemsLinksMode(): string {
      return this.Scope.CopyItemsLinksMode;
    }
    public set CopyItemsLinksMode(value: string) {
      this.Scope.CopyItemsLinksMode = value;
    }

    public get CopyItemsRecursive(): boolean {
      return this.Scope.CopyItemsRecursive;
    }
    public set CopyItemsRecursive(value: boolean) {
      this.Scope.CopyItemsRecursive = value;
    }

    public get CopyItemsBackup(): boolean {
      return this.Scope.CopyItemsBackup;
    }
    public set CopyItemsBackup(value: boolean) {
      this.Scope.CopyItemsBackup = value;
    }

    public get ConfirmItemsToRemove(): string {
      return this.Scope.ConfirmItemsToRemove;
    }
    public set ConfirmItemsToRemove(value: string) {
      this.Scope.ConfirmItemsToRemove = value;
    }

    public get Moving(): boolean {
      return this.Scope.Moving;
    }
    public set Moving(value: boolean) {
      this.Scope.Moving = value;
    }

    public get Moved(): boolean {
      return this.Scope.Moved;
    }
    public set Moved(value: boolean) {
      this.Scope.Moved = value;
    }

    //#endregion
    //#region dialogs

    private ConfirmToDeleteItem: Nemiro.UI.Dialog;
    private FileViewer: Nemiro.UI.Dialog;
    private SaveAsDialog: Nemiro.UI.Dialog;
    private ConfirmToOverwriteFile: Nemiro.UI.Dialog;
    private ConfirmToExecuteFile: Nemiro.UI.Dialog;
    private ExecutionResultDialog: Nemiro.UI.Dialog;
    private PropertiesDialog: Nemiro.UI.Dialog;
    private CreateFolderDialog: Nemiro.UI.Dialog;
    private MoveDialog: Nemiro.UI.Dialog;
    private ConfirmToMoveItems: Nemiro.UI.Dialog;
    private ConfirmToCopyItems: Nemiro.UI.Dialog;
    private ConfirmToDeleteItems: Nemiro.UI.Dialog;

    private Editor: CodeMirror.Editor;

    //#endregion
    //#region keyboard

    private KeyShiftPressed: boolean = false;
    private KeyCtrlPressed: boolean = false;
    private KeyAltPressed: boolean = false;

    //#endregion

    //#endregion
    //#region Constructor

    constructor(context: Nemiro.AngularContext) {
      var $this = this;

      $this.Context = context;
      $this.Scope = $this.Context.Scope;

      $this.SourceTitle = $this.Context.Window.document.title;

      $this.SearchString = $this.Context.Location.search()['search'];

      $this.ConfirmToDeleteItem = Nemiro.UI.Dialog.CreateFromElement($('#confirmToDeleteItem'));
      $this.FileViewer = Nemiro.UI.Dialog.CreateFromElement($('#editItem'));
      $this.FileViewer.DisableOverlayClose = true;
      $this.FileViewer.HiddenCallback = (dialog: Nemiro.UI.Dialog) => {
        $this.Title = '';
      }

      $this.SaveAsDialog = Nemiro.UI.Dialog.CreateFromElement($('#saveAsDialog'));

      $this.ConfirmToOverwriteFile = Nemiro.UI.Dialog.CreateFromElement($('#confirmToOverwriteFile'));
      $this.ConfirmToOverwriteFile.DisableOverlayClose = true;

      $this.ConfirmToExecuteFile = Nemiro.UI.Dialog.CreateFromElement($('#confirmToExecuteFile'));
      $this.ExecutionResultDialog = Nemiro.UI.Dialog.CreateFromElement($('#executionResult'));

      $this.PropertiesDialog = Nemiro.UI.Dialog.CreateFromElement($('#propertiesDialog'));

      $this.CreateFolderDialog = Nemiro.UI.Dialog.CreateFromElement($('#createFolderDialog'));

      //#region global key handlers

      $(document).on('keydown', function (e) {
        $this.KeyAltPressed = e.altKey;
        $this.KeyShiftPressed = e.shiftKey;
        $this.KeyCtrlPressed = e.ctrlKey;
      });

      $(document).on('keyup', function (e) {
        if ($this.KeyAltPressed && !e.altKey) {
          $this.KeyAltPressed = false;
        }
        if ($this.KeyShiftPressed && !e.shiftKey) {
          $this.KeyShiftPressed = false;
        }
        if ($this.KeyCtrlPressed && !e.ctrlKey) {
          $this.KeyCtrlPressed = false;
        }
      });

      //#endregion

      (<any>$('#editItem')).draggable({
        handle: ".modal-header"
      }); 

      (<any>$('.modal-content', '#editItem')).resizable({
        resize: () => {
          var container = $('.modal-content', '#editItem');
          var h = container.height() - $('.modal-header', '#editItem').outerHeight();
          $this.Editor.setSize(container.width(), h);

          var b = $('.modal-body', '#editItem');
          b.width(container.width());
          b.height(h);
        }
      }); 

      $this.EditorMode = 'plain';

      $this.Scope.Editor_Loaded = (editor) => {
        $this.Editor = editor;
        editor.focus();
      };

      $this.Scope.Load = () => { $this.GetList($this); }

      $this.Scope.Search = () => {
        $this.Context.Location.search('search', $this.SearchString);
        $this.GetList($this);
      }

      $this.Scope.ResetSearch = () => {
        $this.SearchString = '';
        $this.Context.Location.search('search', null);
        $this.GetList($this);
      }

      $this.Scope.ToggleItem = (node: Models.FileSystemItem, expanded) => {
        console.log('ToggleItem', node, node.Children, expanded);
        if (expanded && (node.Children == undefined || node.Children == null)) {
          if (node.Loading === undefined || !node.Loading) {
            $this.Context.Location.search('path', node.Path);
            $this.CurrentItem = node;
            $this.GetList($this);
          }
        } else if (!expanded) {
          node.Children = null;
        }
      }

      $this.Scope.ShowSelectedItem = (node: Models.FileSystemItem, selected, $parentNode: Models.FileSystemItem) => {
        //console.log('ShowSelectedItem', selected, node);
        $this.SelectedItem = node;
        $this.SelectedItem.Parent = $parentNode;
        $this.MoveTargetPath = node.Path;
      }

      $this.Scope.ShowConfirmToDelete = (item: Models.FileSystemItem) => {
        $this.SelectedItemToDelete = item;
        $this.ConfirmItemNameToRemove = '';
        $this.ConfirmToDeleteItem.Show();
      }

      $this.Scope.Delete = () => { $this.Delete($this); }

      $this.Scope.Open = (item: Models.FileSystemItem, mode?: string) => {
        $this.Open($this, item, mode);
      }

      $this.Scope.Reopen = () => {
        $this.Open($this, $this.EditableFile, $this.FileViewMode, false);
      }

      $this.Scope.ShowConfirmExecution = (item: Models.FileSystemItem) => {
        $this.ExecuteArguments = '';
        // $this.ExecuteAs = '';
        $this.ConfirmToExecuteFile.Show();
      }

      $this.Scope.Execute = (args: string, login: string) => {
        $this.Execute($this, $this.SelectedItem, args, login);
      }

      $this.Scope.Download = (item: Models.FileSystemItem) => {
        $this.Download($this, item);
      }

      $this.Scope.Properties = (item: Models.FileSystemItem) => {
        $this.Properties($this, item);
      }

      $this.Scope.SetEditorMode = (mode: string) => {
        $this.EditorMode = mode;
        $this.Editor.setOption('mode', mode);

        var modeList = [];
        if ($this.Context.Window.localStorage['Files.EditorMode'] !== undefined && $this.Context.Window.localStorage['Files.EditorMode'] != null && $this.Context.Window.localStorage['Files.EditorMode'] != '') {
          modeList = $.parseJSON($this.Context.Window.localStorage['Files.EditorMode']) || [];
        }

        var hasMode = false;
        angular.forEach(modeList,(item, index) => {
          if (item['path'] == $this.SelectedItem.Path) {
            hasMode = true;
            item['mode'] = mode;
            return;
          }
        });

        if (!hasMode) {
          modeList.push({ 'path': $this.SelectedItem.Path, 'mode': mode});
        }

        if (modeList.length > 100) {
          modeList.shift();
        }

        $this.Context.Window.localStorage['Files.EditorMode'] = $.toJSON(modeList);
      }

      $this.Scope.Save = (newPath?: string, overwrite?: boolean, owner?: string, group?: string) => {
        $this.Save($this, newPath, overwrite, owner, group);
      }

      $this.Scope.SaveAs = () => {
        if ($this.EditableFile.IsNew) {
          $this.SaveAsPath = $this.EditableFile.Path;
        } else {
          $this.SaveAsPath = $this.EditableFile.Name;
        }
        $this.SaveAsOwnerName = $this.SaveAsGroupName = '';
        $this.SaveAsDialog.Show();
      }

      $this.Scope.CancelOverwrite = () => {
        $this.ConfirmToOverwriteFile.Close();
        $this.SaveAsDialog.Show();
      }

      $this.Scope.Rename = (node: Models.FileSystemItem) => {
        $this.Rename($this, node);
      }

      $this.Scope.ChangePermissions = (value: number) => {
        $this.ChangePermissions($this, value);
      }

      $this.Scope.SaveProperties = () => {
        $this.SaveProperties($this);
      }

      $this.Scope.NewFile = () => {
        $this.Editor.setOption('mode', null);
        $this.EditorMode = '';
        $this.FileViewMode = '';

        $this.EditableFile = new Models.FileSystemItem();
        $this.EditableFile.Type = 'File';
        $this.EditableFile.Name = 'new_file';
        $this.EditableFile.Loading = false;
        $this.EditableFile.IsNew = true;

        var item = $this.SelectedItem || $this.CurrentItem;

        if (item !== undefined && item != null) {
          if (item.Type == 'Folder') {
            $this.EditableFile.Path = item.Path + '/' + $this.EditableFile.Name;
          }
          else {
            $this.EditableFile.Path = $this.DirectoryName(item.Path) + '/' + $this.EditableFile.Name;
          }
        }

        $this.CurrentFileContent = '';

        $this.FileViewer.Show();

        $this.Editor.scrollTo(0, 0);
        $this.Editor.refresh();
      }

      $this.Scope.NewFolder = () => {
        if ($this.SelectedItem !== undefined && $this.SelectedItem != null) {
          if ($this.SelectedItem.Type == 'Folder') {
            $this.NewFolderName = $this.SelectedItem.Path + '/';
          }
          else {
            $this.NewFolderName = $this.DirectoryName($this.SelectedItem.Path) + '/';
          }
        }
        else {
          $this.NewFolderName = '/';
        }

        $this.NewFolderOwnerName = $this.NewFolderGroupName = '';
        $this.NewFolderCreateParents = false;

        $this.CreateFolderDialog.Show();
      }

      $this.Scope.CreateFolder = () => {
        $this.CreateFolder($this);
      }

      $this.Scope.Options = {
        isLeaf: (node: Models.FileSystemItem) => {
          return node.Type == 'File';
        },
        isSelectable: (node: Models.FileSystemItem) => {
          return node.Loading === undefined || !node.Loading;
        },
        nodeChildren: 'Children',
        allowDeselect: false,
        templateUrl: 'treeViewTemplate.html',
        Open: $this.Scope.Open,
        MouseUp: (e: MouseEvent, node: Models.FileSystemItem) => {
          if (e.which == 3) {
            // this is right click, show menu
            (<any>node).ContextMenuVisible = true;
          }

          // ctrl is pushed
          if ($this.KeyCtrlPressed) {
            // select / unselect
            $this.Scope.Select(node.Path);
          }
        }
      };

      //#region move, copy and delete

      $this.MoveDialog = Nemiro.UI.Dialog.CreateFromElement($('#moveDialog'));
      $this.MoveDialog.HiddenCallback = () => {
        if ($this.Moved) {
          // items is moved, reset form
          $this.SelectedItems = [];
          $this.SelectedItemsCompleted = [];
          $this.Moved = false;
          if ($this.SelectedItemsAction == 'Delete') {
            $this.GetList($this); // Nemiro.Utility.DirectoryName($this.MoveTargetPath)
          } else {
            $this.GetList($this, $this.MoveTargetPath);
          }
        }
      }

      $this.ConfirmToMoveItems = Nemiro.UI.Dialog.CreateFromElement($('#confirmToMoveItems'));
      $this.ConfirmToCopyItems = Nemiro.UI.Dialog.CreateFromElement($('#confirmToCopyItems'));
      $this.ConfirmToDeleteItems = Nemiro.UI.Dialog.CreateFromElement($('#confirmToDeleteItems'));

      $this.MoveItemsMode = 'Force';
      $this.CopyItemsMode = 'Force';
      $this.CopyItemsLinksMode = 'None';
      $this.CopyItemsRecursive = true;

      $this.SelectedItems = new Array<string>();
      $this.SelectedItemsCompleted = new Array<string>();

      $this.Scope.ClearSelection = () => {
        $this.SelectedItems = [];
        $this.SelectedItemsCompleted = [];
      };

      $this.Scope.ShowMoveDialog = () => {
        $this.MoveDialog.Show();
      };

      $this.Scope.MoveItems = () => {
        $this.MoveItems($this);
      };

      $this.Scope.CopyItems = () => {
        $this.CopyItems($this);
      };

      $this.Scope.DeleteItems = () => {
        $this.DeleteItems($this);
      };

      $this.Scope.ConfirmToMoveItems = () => {
        $this.MoveDialog.Close();
        $this.ConfirmToMoveItems.Show();
      };

      $this.Scope.ConfirmToDeleteItems = () => {
        $this.MoveDialog.Close();
        $this.ConfirmItemsToRemove = '';
        $this.ConfirmToDeleteItems.Show();
      };

      $this.Scope.ConfirmToCopyItems = () => {
        $this.MoveDialog.Close();
        $this.ConfirmToCopyItems.Show();
      };

      $this.Scope.CloseConfirmItems = () => {
        $this.MoveDialog.Show();
      };

      $this.Scope.Select = (path: string) => {
        if ($this.SelectedItems.indexOf(path) == -1) {
          $this.SelectedItemsCompleted.push('');
          $this.SelectedItems.push(path);
        } else {
          $this.SelectedItemsCompleted.splice($this.SelectedItems.indexOf(path), 1);
          $this.SelectedItems.splice($this.SelectedItems.indexOf(path), 1);
        }
      };

      //#endregion

      if ($this.Context.Location.search()['path'] !== undefined && $this.Context.Location.search()['path'] != null && $this.Context.Location.search()['path'] != '') {
        $this.GetList($this, $this.Context.Location.search()['path']);
      } else {
        $this.GetList($this);
      }
    }

    //#endregion
    //#region Methods

    private GetList($this: FileListController, goTo?: string): void {
      //if (goTo !== undefined && goTo != null && goTo != '' && goTo[0] != '/') {
      //  goTo = '/' + goTo;
      //}

      console.log('GetList', goTo);

      $this = $this || this;

      if ($this.Loading) {
        return;
      }

      var path = '/';

      if ($this.CurrentItem !== undefined && $this.CurrentItem != null) {
        path = $this.CurrentItem.Path;
        $this.CurrentItem.Loading = true;
      }

      $this.Loading = true;

      // create request
      var apiRequest = new ApiRequest<Array<Models.FileSystemItem>>($this.Context, 'Files.GetList', { path: path, search: $this.SearchString });

      // handler successful response to a request to api
      apiRequest.SuccessCallback = (response) => {
        if ($this.CurrentItem === undefined || $this.CurrentItem == null) {
          $this.Items = response.data;
        } else {
          $this.CurrentItem.Children = response.data;
        }

        // goto path
        if (goTo !== undefined && goTo != null && goTo != '') {
          // select item
          var items = null;
          if ($this.CurrentItem === undefined || $this.CurrentItem == null) {
            items = $this.Items;
          }
          else {
            items = $this.CurrentItem.Children;
          }

          if ($this.ExpandedItems === undefined || $this.ExpandedItems == null) {
            $this.ExpandedItems = new Array<Models.FileSystemItem>();
          }

          if (path == '/') {
            path = '';
          } else {
            path += '/';
          }

          //console.log('path',(path + goTo));

          angular.forEach(items,(item: Models.FileSystemItem, i: number) => {
            if ((path + goTo).indexOf(item.Path) == 0) {
              $this.CurrentItem = item;

              var segments = goTo.split('/');
              
              if (segments[0] == '') {
                segments.shift();
                if (segments.length > 0) { segments.shift(); }
              } else {
                segments.shift();
              }

              $this.Loading = false;

              if (segments.length > 0) {
                // console.log('CurrentItem', $this.CurrentItem);
                $this.GetList($this, segments.join('/'));
              }
              else {
                $this.GetList($this);
              }

              $this.ExpandedItems.push(item);

              return;
            }
          });
        }
      };
      
      apiRequest.CompleteCallback = () => {
        if ($this.CurrentItem !== undefined && $this.CurrentItem != null) {
          $this.CurrentItem.Loading = false;
        }

        $this.Loading = false;
      };

      // execute
      apiRequest.Execute();
    }

    private Delete($this: FileListController): void {
      $this.SelectedItemToDelete.Loading = true;

      // create request
      var apiRequest = new ApiRequest<any>($this.Context, 'Files.Delete', { Path: $this.SelectedItemToDelete.Path });

      // handler successful response to a request to api
      apiRequest.SuccessCallback = (response) => {
        $this.ConfirmToDeleteItem.Close();
        var path = '';
        if ($this.SelectedItemToDelete.Parent !== undefined && $this.SelectedItemToDelete.Parent != null) {
          path = $this.SelectedItemToDelete.Parent.Path;
        }
        //$this.SelectedItem = null; // TODO: fix it
        $this.CurrentItem = null;
        $this.GetList($this, path);
      };

      apiRequest.CompleteCallback = () => {
        $this.SelectedItemToDelete.Loading = false;
      }

      // execute
      apiRequest.Execute();
    }

    private Save($this: FileListController, newPath?: string, overwrite?: boolean, owner?: string, group?: string): void {
      $this.EditableFile.Loading = true;

      $this.SaveAsDialog.Close();
      $this.ConfirmToOverwriteFile.Close();

      newPath = newPath || null;
      overwrite = overwrite || null;
      owner = owner || null;
      group = group || null;

      // create request
      var apiRequest = new ApiRequest<any>($this.Context, 'Files.Save', { path: $this.EditableFile.Path, newPath: newPath, content: $this.CurrentFileContent, overwrite: overwrite, owner: owner, group: group });

      // handler successful response to a request to api
      apiRequest.SuccessCallback = (response) => {
        if (response.data.OverwriteRequest !== undefined && response.data.OverwriteRequest) {
          $this.ConfirmToOverwriteFile.Show();
        } else {
          $this.SaveAsDialog.Close();

          if (newPath != null && newPath != '') {
            if (newPath.indexOf('/') == -1) {
              newPath = $this.DirectoryName($this.EditableFile.Path) + '/' + newPath;
            }
            $this.CurrentItem = null;
            $this.GetList($this, $this.DirectoryName(newPath));
            $this.FileViewer.Close();
          }
        }
      };

      apiRequest.CompleteCallback = () => {
        $this.EditableFile.Loading = false;
        $this.FileViewer.DisplayCloseButton = true;
      }

      // execute
      apiRequest.Execute();
    }

    private Open($this: FileListController, item: Models.FileSystemItem, mode?: string, scrollToTop?: boolean): void {
      // console.log('Open', item.Path, mode);

      item.Loading = true;

      $this.EditableFile = item;

      if (mode === undefined || mode == null) {
        mode = '';
      }

      if (scrollToTop === undefined || scrollToTop == null) {
        scrollToTop = true;
      }

      $this.FileViewMode = mode;

      // Loading the file contents...
      $this.Scope.$parent.ShowProgress(App.Resources.LoadingFileContents, App.Resources.Loading);

      var apiRequest = new ApiRequest<any>($this.Context, 'Files.Get', { path: item.Path, mode: mode });

      apiRequest.SuccessCallback = (response) => {
        $this.CurrentFileContent = response.data.Content;

        var hasEditorMode = false;
        if (mode == '' && $this.Context.Window.localStorage['Files.EditorMode'] !== undefined && $this.Context.Window.localStorage['Files.EditorMode'] != null && $this.Context.Window.localStorage['Files.EditorMode'] != '') {
          var modeList = $.parseJSON($this.Context.Window.localStorage['Files.EditorMode']) || [];
          angular.forEach(modeList, (fileItem) => {
            if (fileItem['path'] == item.Path) {
              $this.Editor.setOption('mode', fileItem['mode']);
              $this.EditorMode = fileItem['mode'];
              hasEditorMode = true;
              return;
            }
          });
        }

        if (!hasEditorMode) {
          if (/^\#\!/.test($this.CurrentFileContent) || /^.+\.(sh|shell|bash)$/i.test(item.Path)) {
            $this.Editor.setOption('mode', 'shell');
            $this.EditorMode = 'shell';
          }
          else if (/^\<\?xml/.test($this.CurrentFileContent) || /^.+\.(xml|xsl|config)$/i.test(item.Path)) {
            $this.Editor.setOption('mode', 'xml');
            $this.EditorMode = 'xml';
          }
          else if (/^\<\?php/.test($this.CurrentFileContent) || /^.+\.php(3|4|5|)$/i.test(item.Path)) {
            $this.Editor.setOption('mode', 'php');
            $this.EditorMode = 'php';
          }
          else if (/^.+\.(py)$/i.test(item.Path)) {
            $this.Editor.setOption('mode', 'python');
            $this.EditorMode = 'python';
          }
          else if (/^.+\.(c|cpp|cs|java)$/i.test(item.Path)) {
            $this.Editor.setOption('mode', 'clike');
            $this.EditorMode = 'clike';
          }
          else if (/^.+\.(pl|pm)$/i.test(item.Path)) {
            $this.Editor.setOption('mode', 'perl');
            $this.EditorMode = 'perl';
          }
          else if (/^.+\.(ini)$/i.test(item.Path)) {
            $this.Editor.setOption('mode', 'properties');
            $this.EditorMode = 'properties';
          }
          else if (/^.+\.(js|json)$/i.test(item.Path)) {
            $this.Editor.setOption('mode', 'javascript');
            $this.EditorMode = 'javascript';
          }
          else if (/^.+\.(md)$/i.test(item.Path)) {
            $this.Editor.setOption('mode', 'markdown');
            $this.EditorMode = 'markdown';
          }
          else if (/^.+\.(css|scss|sass|less)$/i.test(item.Path)) {
            $this.Editor.setOption('mode', 'css');
            $this.EditorMode = 'css';
          }
          else if (/^.+\.((s|d|)htm(l|))$/i.test(item.Path)) {
            $this.Editor.setOption('mode', 'htmlmixed');
            $this.EditorMode = 'htmlmixed';
          }
          else if (/^.+\.(sql)$/i.test(item.Path)) {
            $this.Editor.setOption('mode', 'sql');
            $this.EditorMode = 'sql';
          }
          else if (/server(\s*)\{/.test($this.CurrentFileContent)) {
            $this.Editor.setOption('mode', 'nginx');
            $this.EditorMode = 'nginx';
          }
          else if (/\<VirtualHost/.test($this.CurrentFileContent)) {
            $this.Editor.setOption('mode', 'shell');
            $this.EditorMode = 'shell';
          }
          else {
            $this.Editor.setOption('mode', null);
            $this.EditorMode = '';
          }
        }

        $this.FileViewer.Show();

        if (scrollToTop) {
          $this.Editor.scrollTo(0, 0);
        }

        $this.Editor.refresh();

        $this.Title = $this.EditableFile.Name;
      };

      apiRequest.CompleteCallback = () => {
        item.Loading = false;
        $this.Scope.$parent.CloseProgress();
      }

      apiRequest.Execute();
    }

    private Download($this: FileListController, item: Models.FileSystemItem): void {
      // TODO
    }

    private Properties($this: FileListController, item: Models.FileSystemItem): void {
      item.Loading = true;

      $this.Scope.$parent.ShowProgress(App.Resources.GettingFileInfo, App.Resources.Loading);

      var apiRequest = new ApiRequest<Models.FileSystemItemInfo>($this.Context, 'Files.Info', { path: item.Path });

      apiRequest.SuccessCallback = (response) => {
        $this.FileInfo = response.data;
        $this.FileInfoSource = $.parseJSON($.toJSON(response.data));
        var permissions = parseInt($this.FileInfo.Permissions.toString(), 8);
        for (var i = 1; i < 512; i = i * 2) {
          //console.log('Permissions', i.toString(8), $this.HasPermissions(permissions, i));
          $this.FileInfo['Permissions' + i.toString(8)] = $this.HasPermissions(permissions, i);
        }
        $this.UpdateRecursive = false;

        $this.Context.Timeout(() => {
          $this.FileInfoChanged = false; // delayed because triggers the change event
        }, 250);

        $this.PropertiesDialog.Show();
      };

      apiRequest.CompleteCallback = () => {
        item.Loading = false;
        $this.Scope.$parent.CloseProgress();
      }

      apiRequest.Execute();
    }

    private HasPermissions(permissions: number, value: number): boolean {
      return (permissions & value) == value;
    }

    private Execute($this: FileListController, item: Models.FileSystemItem, args: string, login: string): void {
      item.Loading = true;

      $this.ConfirmToExecuteFile.Close();

      var apiRequest = new ApiRequest<any>($this.Context, 'Files.Execute', { path: item.Path, args: args, login: login });

      apiRequest.SuccessCallback = (response) => {
        $this.ExecutionResult = response.data.Content;
        $this.ExecutionResultDialog.Show();
      };

      apiRequest.CompleteCallback = () => {
        item.Loading = false;
      }

      apiRequest.Execute();
    }

    private Rename($this: FileListController, node: Models.FileSystemItem): void {
      node.Loading = true;

      var apiRequest = new ApiRequest<any>($this.Context, 'Files.Rename', { path: node.Path, name: node.NewName });

      apiRequest.SuccessCallback = (response) => {
        node.Path = response.data.Path;
        node.Name = node.NewName;
        node.RenameMode = false;
      };

      apiRequest.CompleteCallback = () => {
        node.Loading = false;
      }

      apiRequest.Execute();
    }

    private ChangePermissions($this: FileListController, value: number) {
      var permissions = parseInt($this.FileInfo.Permissions.toString(), 8);
      var value = parseInt(value.toString(), 8);

      if ($this.HasPermissions(permissions, value)) {
        // remove
        permissions = permissions & ~value;
      } else {
        // add
        permissions = permissions | value;
      }

      $this.FileInfo.Permissions = <any>permissions.toString(8);

      //console.log('Permissions', $this.FileInfo.Permissions);
    }

    private SaveProperties($this: FileListController): void {
      $this.FileInfo.Saving = true;

      var apiRequest = new ApiRequest<Models.FileSystemItemInfo>($this.Context, 'Files.SaveInfo', { Source: $this.FileInfoSource, Current: $this.FileInfo, Recursive: $this.UpdateRecursive });

      apiRequest.SuccessCallback = (response) => {
        $this.FileInfo = response.data;
        $this.FileInfoSource = $.parseJSON($.toJSON(response.data));
        var permissions = parseInt($this.FileInfo.Permissions.toString(), 8);
        for (var i = 1; i < 512; i = i * 2) {
          $this.FileInfo['Permissions' + i.toString(8)] = $this.HasPermissions(permissions, i);
        }

        $this.UpdateRecursive = false;
        $this.FileInfoChanged = false;

        $this.CurrentItem = null;
        $this.GetList($this, $this.FileInfo.Path);
      };

      apiRequest.CompleteCallback = () => {
        $this.FileInfo.Saving = false;
      }

      apiRequest.Execute();
    }

    private CreateFolder($this: FileListController): void {
      $this.CreationFolder = true;

      var apiRequest = new ApiRequest<any>($this.Context, 'Files.CreateFolder', { Path: $this.NewFolderName, Owner: $this.NewFolderOwnerName, Group: $this.NewFolderGroupName, Parents: $this.NewFolderCreateParents });

      apiRequest.SuccessCallback = (response) => {
        $this.CurrentItem = null;
        $this.GetList($this, $this.NewFolderName);
        $this.CreateFolderDialog.Close();
      };

      apiRequest.CompleteCallback = () => {
        $this.CreationFolder = false;
      }

      apiRequest.Execute();
    }

    private DirectoryName(path: string): string {
      return Nemiro.Utility.DirectoryName(path);
    }
    
    private MoveItems($this: FileListController): void {
      $this.Moving = true;
      
      $this.MoveDialog.DisplayCloseButton = true;
      $this.MoveDialog.DisableOverlayClose = true;
      $this.MoveDialog.Show();

      $this.ConfirmToMoveItems.Close();

      $this.SelectedItemsAction = 'Move';
      
      $this.MoveNextItem($this, 'Move', 0);
    }
    
    private CopyItems($this: FileListController): void {
      $this.Moving = true;
      
      $this.MoveDialog.DisplayCloseButton = true;
      $this.MoveDialog.DisableOverlayClose = true;
      $this.MoveDialog.Show();

      $this.ConfirmToCopyItems.Close();

      $this.SelectedItemsAction = 'Copy';

      $this.MoveNextItem($this, 'Copy', 0);
    }
    
    private DeleteItems($this: FileListController): void {
      $this.Moving = true;
      
      $this.MoveDialog.DisplayCloseButton = true;
      $this.MoveDialog.DisableOverlayClose = true;
      $this.MoveDialog.Show();

      $this.ConfirmToDeleteItems.Close();

      $this.SelectedItemsAction = 'Delete';

      $this.MoveNextItem($this, 'Delete', 0);
    }

    private MoveNextItem($this: FileListController, action: string, index: number): void {
      var data = null;

      if (action == 'Move') {
        data = {
          Path: $this.SelectedItems[index],
          TargetPath: $this.MoveTargetPath,
          Mode: $this.MoveItemsMode,
          Backup: $this.MoveItemsBackup,
          Suffix: $this.MoveBackupSessionId // TODO
        };
      }
      else if (action == 'Copy') {
        data = {
          Path: $this.SelectedItems[index],
          TargetPath: $this.MoveTargetPath,
          Mode: $this.CopyItemsMode,
          Links: $this.CopyItemsLinksMode,
          Backup: $this.CopyItemsBackup,
          Recursive: $this.CopyItemsRecursive
          // Suffix: $this.CopyBackupSessionId // TODO
        };
      }
      else if (action == 'Delete') {
        data = { Path: $this.SelectedItems[index] };
      }

      var apiRequest = new ApiRequest<any>($this.Context, 'Files.' + action, data);

      apiRequest.SuccessCallback = (response) => {
        $this.SelectedItemsCompleted[index] = 'Success';
      };

      apiRequest.ErrorCallback = (response) => {
        $this.SelectedItemsCompleted[index] = 'Fail';
        apiRequest.ApiError(response);
      };

      apiRequest.CompleteCallback = () => {
        index++;
        if (index < $this.SelectedItems.length) {
          $this.MoveNextItem($this, action, index);
        } else {
          $this.Moved = true;
          $this.Moving = false;
        }
      }

      apiRequest.Execute();
    }
    
    //#endregion

  }

} 