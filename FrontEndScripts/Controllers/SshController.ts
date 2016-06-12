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
   * Represents the SSH controller.
   */
  export class SshController implements Nemiro.IController {

    public Scope: any;
    public Context: Nemiro.AngularContext;

    /** Command execution indicator. */
    public get Execution(): boolean {
      return this.Scope.Execution;
    }
    public set Execution(value: boolean) {
      this.Scope.Execution = value;
    }

    constructor(context: Nemiro.AngularContext) {
      var $this = this;
      
      $this.Context = context;
      $this.Scope = $this.Context.Scope;

      $this.Scope.CodeMirror_Loaded = (editor) => {
        editor.focus();

        editor.on('keyHandled',(editor: CodeMirror.Editor, name: string, event: KeyboardEvent) => {
          $this.Terminal_KeyHandled($this, editor, name, event);
        });
      };
    }

    private Terminal_KeyHandled($this: SshController, editor: CodeMirror.Editor, name: string, event: KeyboardEvent): void {
      //console.log(name, event.keyCode);
      if (event.keyCode != 13) {
        return;
      }

      var doc = editor.getDoc();
      var line = doc.lastLine() - 1
      var cmd = doc.getLine(line);

      // add command to history
      //doc.setHistory(cmd);

      if (cmd == '') {
        return;
      }

      // local commands
      if (cmd == 'clear' || cmd == 'cls') {
        doc.setValue('');
        return;
      }

      editor.setOption('readOnly', true);

      // mark string as read-only
      doc.markText(CodeMirror.Pos(line, 0), CodeMirror.Pos(line, cmd.length), { readOnly: true });
      
      $this.Execution = true;

      // create request
      var apiRequest = new ApiRequest<Models.SshResult>($this.Context, 'Ssh.Execute', cmd);

      // handler successful response to a request to api
      apiRequest.SuccessCallback = (response) => {
        var output: string = '';

        if (response.data.Result != '') {
          output += response.data.Result;
        }

        if (response.data.Error != '') {
          if (output.length > 0) {
            output += '\n';
          }

          output += response.data.Error;
        }

        output = output.replace(/\r+/gm, '');

        // output
        var lines = output.split('\n');

        for (var i = 0; i < lines.length; i++) {
          var startLine = doc.lastLine();
          doc.replaceRange(lines[i] + '\n', CodeMirror.Pos(startLine, 0), null);
          // mark line as read-only
          doc.markText(CodeMirror.Pos(startLine, 0), CodeMirror.Pos(startLine, lines[i].length), { readOnly: true });
        }
      };

      apiRequest.CompleteCallback = () => {
        doc.setCursor(CodeMirror.Pos(doc.lastLine(), 0));
        editor.setOption('readOnly', false);
        $this.Execution = false;
      }

      // execute
      apiRequest.Execute();
    }

  }

} 