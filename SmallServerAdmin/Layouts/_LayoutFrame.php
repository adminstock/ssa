<?#Register Src="~/Controls/Header.php" TagPrefix="php" TagName="Header"?>
<?#Register Src="~/Controls/Footer.php" TagPrefix="php" TagName="Footer"?>
<!DOCTYPE html>

<html xmlns:php="http://aleksey.nemiro.ru/php-webforms" class="frame-page">
  <head>
    <title>SmallServerAdmin</title>
    <meta name="viewport" content="width=device-width" />
    <link rel="stylesheet" href="/Content/compiled.min.css" />
    <php:Head/>
  </head>
  <body>
    <php:Header ID="Header1" />

		<iframe src="${frame_src}" name="admin" frameborder="0" style="overflow:hidden;height:100%;width:100%">
			Your browser does not support frames.
		</iframe>

    <php:Footer ID="Footer1" />

    <script src="/Content/compiled.min.js" type="text/javascript"></script>
  </body>
</html>