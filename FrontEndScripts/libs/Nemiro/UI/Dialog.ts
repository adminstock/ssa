/*
 * Copyright © Aleksey Nemiro, 2015. All rights reserved.
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
module Nemiro.UI {

  /** 
   * Provides a dialog box.
   */
  export class Dialog {

    /** Dialog template. */
    private static ModalTemplate: string = '<div class="modal" role="dialog"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button></div><div class="modal-body" style="overflow:auto;"></div><div class="modal-footer"></div></div></div></div>';

    /** The jQuery modal. */
    public $modal: JQuery = null;

    private _Title: string|JQuery|Element;

    /** The dialog title. */
    public get Title(): string|JQuery|Element {
      return this._Title;
    }
    public set Title(value: string|JQuery|Element) {
      var oldValue = this._Title;
      this._Title = value;
      if (oldValue != value) {
        if (this.$modal != null) {
          $('.modal-header', this.$modal).empty().append(value);
          if (value != null && value != '') {
            $('.modal-header', this.$modal).show();
          } else {
            $('.modal-header', this.$modal).hide();
          }
        }
      }
    }

    /** The dialog content. */
    public Content: string|JQuery|Element;

    /** The footer content. */
    public Footer: string|JQuery|Element;

    /** Dialog width. */
    public Width: string = null;

    /** Dialog height. */
    public Height: string = null;

    /** Show the close button in the header (default true - yes). */
    public DisplayCloseButton: boolean = true;

    /** 
     * Prevent dialog closing when click on the background 
     * (default false - are allowed to close the dialog when click on the background).
     */
    public get DisableOverlayClose(): boolean {
      return this.$modal.data('bs.modal').options.backdrop == 'static';
    }
    public set DisableOverlayClose(value: boolean) {
      this.$modal.data('bs.modal').options.backdrop = (value ? 'static' : 'true');
    }

    /** Do not restore the dialog box after the show other windows. */
    public get DontRestore(): boolean {
      if (this.$modal.attr('data-not-restore') == undefined) {
        return false;
      } else {
        return this.$modal.attr('data-not-restore').toLowerCase() == 'true';
      }
    }
    public set DontRestore(value: boolean) {
      if (value) {
        this.$modal.attr('data-not-restore', 'true');
      }
      else {
        if (this.$modal.attr('data-not-restore') != undefined) {
          this.$modal.removeAttr('data-not-restore');
        }
      }
    }

    /** Gets or sets the visibility status of the dialog. */
    public get Visible(): boolean {
      return $(this.$modal).css('display') != 'none';
    }
    public set Visible(value: boolean) {
      if (value) {
        this.Show();
      } else {
        this.Close();
      }
    }

    private _HiddenCallback: { (dialog: Dialog): void; };

    public get HiddenCallback(): { (dialog: Dialog): void; } {
      return this._HiddenCallback;
    }
    public set HiddenCallback(value: { (dialog: Dialog): void; }) {
      this._HiddenCallback = value;
    }

    // NOTE: I think it will be possible to do more features, but it will increase the file size.

    /**
     * Creates a new modal window.
     * 
     * @param title The dialog title. Allowed html.
     * @param content The content.
     * @param footer The footer content.
     * @param width The dialog width.
     * @param height The dialog height.
     * @param displayCloseButton Show the close button in the header (default true - yes). 
     * @param disableOverlayClose Prevent dialog closing when click on the background (default false - are allowed to close the dialog when click on the background).
     * @param dontRestore Do not restore the dialog box after the show other windows.
     */
    constructor(title: string|JQuery|Element, content: string|JQuery|Element|boolean, footer?: string|JQuery|Element, width?: string, height?: string, displayCloseButton?: boolean, disableOverlayClose?: boolean, dontRestore?: boolean) {
      if (typeof this.Init === 'undefined') { return; }
      if (displayCloseButton === undefined || displayCloseButton == null) { displayCloseButton = true; }
      if (disableOverlayClose === undefined || disableOverlayClose == null) { disableOverlayClose = false; }

      if (typeof content == 'boolean') {
        // creates an instance of the class of an existing dialog
        this.$modal = $(title);
        this.$modal.modal({ backdrop: (disableOverlayClose ? 'static' : 'true'), show: false });
      } else {
        // creates a new modal window.
        this.$modal = $(Dialog.ModalTemplate);
        this.$modal.modal({ backdrop: (disableOverlayClose ? 'static' : 'true'), show: false });

        this._Title = title;
        this.Content = <any>content;
        this.Footer = footer;
        this.Width = width;
        this.Height = height;
        this.DisplayCloseButton = displayCloseButton;
        this.DontRestore = dontRestore;

        this.Init();
      }
    }

    /*
     * Initializes a modal window in accordance with the specified parameters.
     */
    private Init(): void {
      // console.log('Dialog.Init');
      // console.log(this.$modal);

      var $this = this;

      if ($this.Width !== undefined && $this.Width) {
        $this.$modal.width($this.Width);
      } else {
        $this.$modal.width(null);
      }

      if ($this.Height !== undefined && $this.Height) {
        $('.modal-body', $this.$modal).height($this.Height);
      } else {
        $('.modal-body', $this.$modal).height(null);
      }

      if ($this.Title !== undefined && $this.Title) {
        $('.modal-header', $this.$modal).append($this.Title);
        $('.modal-header', $this.$modal).show();
        if (!$this.DisplayCloseButton) {
          $('.modal-header button.close:first', $this.$modal).hide();
        }
      } else {
        $('.modal-header', $this.$modal).hide();
      }

      $('.modal-body', $this.$modal).append($this.Content);

      if ($this.Footer !== undefined && $this.Footer) {
        $('.modal-footer', $this.$modal).append($this.Footer);
        $('.modal-footer', $this.$modal).show();
      } else {
        $('.modal-footer', $this.$modal).hide();
      }

      if ($this.DontRestore) {
        $this.$modal.attr('data-not-restore', 'true');
      }

      $this.$modal.on('hidden.bs.modal', () => { $(this).remove(); });
      $this.$modal.modal({ backdrop: ($this.DisableOverlayClose ? 'static' : 'true'), show: false }); //, keyboard: false
    }

    /**
     * Displays the dialog box.
     */
    public Show(): void {
      // this.Init();

      this.$modal.off('hidden.bs.modal');

      if (this.HiddenCallback !== undefined && this.HiddenCallback != null && typeof this.HiddenCallback === 'function') {
        this.$modal.on('hidden.bs.modal',() => {
          this.HiddenCallback(this);
        });
      }

      this.$modal.modal('show');
    }

    /** 
     * Closes the dialog box.
     */
    public Close(): void {
      this.$modal.modal('hide');
    }

    /**
     * creates a new window from an existing element.
     * 
     * @param element The element from which to create a window (Dialog).
     */
    public static CreateFromElement(element: string|JQuery|Element): Dialog {
      if (element === undefined || element == null) {
        throw new Error('Необходимо указать элемент, из которого следует создать диалоговое окно.');
      }

      return new Dialog($(element), true);
    }

    /**
     * Creates and shows the dialog.
     * 
     * @param title The dialog title. Allowed html.
     * @param content The content.
     * @param footer The footer content.
     * @param width The dialog width.
     * @param height The dialog height.
     * @param displayCloseButton Show the close button in the header (default true - yes). 
     * @param disableOverlayClose Prevent dialog closing when click on the background (default false - are allowed to close the dialog when click on the background).
     * @param dontRestore Do not restore the dialog box after the show other windows.
     */
    public static ShowDialog(title: string|JQuery|Element, content: string|JQuery|Element, footer?: string|JQuery|Element, width?: string, height?: string, displayCloseButton?: boolean, disableOverlayClose?: boolean, dontRestore?: boolean): Dialog {
      var modal = new Dialog(title, content, footer, width, height, displayCloseButton, disableOverlayClose, dontRestore);
      modal.Show();

      return modal;
    }

    /**
     * Shows alert.
     * 
     * @param title The dialog title. Only text, without html.
     * @param message The message. Allowed html.
     * @param buttonTitle The Ok button title. Default: Ok.
     * @param hiddenCallback Callback function.
     */
    public static Alert(message: string|JQuery|Element, title?: string, buttonTitle?: string, hiddenCallback?: { (dialog: Dialog): void; }): Dialog {
      // remember all open modal windows
      var restoreModal = new Array();
      $('.modal').each((i, e) => {
        if ($(e).css('display') != 'none') {
          if ($(e).attr('data-not-restore') === undefined || $(e).attr('data-not-restore') != 'true') {
            restoreModal[restoreModal.length] = e;
          }
          $(e).modal('hide');
        }
      });

      if (title !== undefined && title && title.length > 0) {
        title = '<h3>' + title + '</h3>';
      }

      var footer = null;

      if (buttonTitle === undefined || !buttonTitle || buttonTitle.length <= 0) {
        buttonTitle = 'Ok';
      }

      footer = '<a href="#" class="btn btn-primary" data-dismiss="modal" aria-hidden="true">' + buttonTitle + '</a>';

      var dialog = new Dialog(title, message, footer, null, null, true, false, false);
      
      dialog.HiddenCallback = (sender: Dialog) => {
        if (restoreModal.length > 0) {
          // restore windows
          $(restoreModal).each((i, e) => {
            $(e).modal('show');
          });
        }

        if (hiddenCallback !== undefined && hiddenCallback && typeof hiddenCallback == 'function') {
          hiddenCallback(dialog);
        }

        $(sender.$modal).remove();
      }
       
      /*$(alrt).on('shown', function () {
        $(this).css("top", ($(window).height() - $(this).height()) / 2).css("left", ($(window).width() - $(this).width()) / 2);
        return this;
      });*/

      dialog.Show();

      return dialog;
    }

    public static Confirm(message: string|JQuery|Element, title?: string, buttonAcceptTitle?: string, buttonCancelTitle?: string, resultCallback?: { (dialog: Dialog, result: boolean): void; }): Dialog {
      // remember all open modal windows
      var restoreModal = new Array();
      $('.modal').each((i, e) => {
        if ($(e).css('display') != 'none') {
          if ($(e).attr('data-not-restore') === undefined || $(e).attr('data-not-restore') != 'true') {
            restoreModal[restoreModal.length] = this;
          }
          $(e).modal('hide');
        }
      });

      if (title !== undefined && title && title.length > 0) {
        title = '<h3>' + title + '</h3>';
      }

      var footer = null;

      if (buttonAcceptTitle === undefined || !buttonAcceptTitle || buttonAcceptTitle.length <= 0) {
        buttonAcceptTitle = 'Ok';
      }

      if (buttonCancelTitle === undefined || !buttonCancelTitle || buttonCancelTitle.length <= 0) {
        buttonCancelTitle = 'Cancel';
      }

      footer = $
      (
        '<a href="#" class="btn btn-primary" data-dismiss="modal" aria-hidden="true">' + buttonAcceptTitle + '</a>' +
        '<a href="#" class="btn btn-danger" data-dismiss="modal" aria-hidden="true">' + buttonCancelTitle + '</a>'
      );

      if (resultCallback !== undefined && resultCallback && typeof resultCallback == 'function') {

        $('a:first', footer).click(() => {
          resultCallback(dialog, true);
        });

        $('a:last', footer).click(() => {
          resultCallback(dialog, false);
        });

      }

      var dialog = new Dialog(title, message, footer, null, null, true, false, false);

      dialog.HiddenCallback = (sender: Dialog) => {
        if (restoreModal.length > 0) {
          // restore windows
          $(restoreModal).each((i, e) => {
            $(e).modal('show');
          });
        }

        $(sender.$modal).remove();
      }

      dialog.Show();

      return dialog;
    }

  }

}