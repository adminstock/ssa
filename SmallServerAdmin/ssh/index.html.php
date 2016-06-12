<?#Page Title="${Menu_SSH}" ?>
<html xmlns:php="http://aleksey.nemiro.ru/php-webforms">
  <body>

    <php:Content ID="MainContent">
      <div ng-controller="SshController">
        <h2>${Menu_SSH}</h2>
        <ui-codemirror ui-codemirror-opts="{lineNumbers: true, matchBrackets: true, mode: 'shell', theme: 'erlang-dark', onLoad: CodeMirror_Loaded }"></ui-codemirror>

        <div ng-show="Loading">
          <span class="glyphicon glyphicon-refresh fa-spin"></span>
          ${Processing...}
        </div>
      </div>
    </php:Content>

  </body>
</html>