<?#Page Title="Error" ?>
<html xmlns:php="http://aleksey.nemiro.ru/php-webforms">
  <body>

    <php:Content ID="MainContent">
      <h2>Error</h2>
      <p>
      <?php
        switch ($this->ErrorCode)
        {
          case 'AUTHENTICATION_FAILED':
            echo 'Unable to perform SSH authentication. Check the username and password.';
            break;

          default:
            echo 'Error: '.$this->ErrorCode;
            break;
        }
      ?>
      </p>
    </php:Content>

  </body>
</html>