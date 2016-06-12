<?#Register Src="~/Controls/Header.php" TagPrefix="php" TagName="Header"?>
<?#Register Src="~/Controls/Footer.php" TagPrefix="php" TagName="Footer"?>
<?#Register Src="~/Controls/StaticIncludes.php" TagPrefix="php" TagName="StaticIncludes"?>
<!DOCTYPE html>

<html xmlns:php="http://aleksey.nemiro.ru/php-webforms" class="frame-page">
  <head>
    <title>SmallServerAdmin</title>
    <meta name="viewport" content="width=device-width" />
    <php:Head/>
  </head>
  <body ng-controller="MasterController">
    <php:Header ID="Header1" />

    <iframe src="${frame_src}" name="admin" frameborder="0" style="overflow:hidden;height:100%;width:100%">
      Your browser does not support frames.
    </iframe>

    <php:Footer ID="Footer1" />
    <php:StaticIncludes ID="StaticIncludes1" />
  </body>
</html>