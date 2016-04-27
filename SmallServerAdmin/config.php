<?php
# WebForms config
# ---------------------------------------------------------------
# page caching (true|false)
define('PAGE_DEFAULT_CACHE', false);
# HTML compress (true|false)
define('PAGE_COMPRESS_HTML', false);
# debug mode (true|false)
define('DEBUG_MODE', false);
# root path
define('MAIN_PATH', $_SERVER['DOCUMENT_ROOT']);
# ---------------------------------------------------------------
# default layout
define('PAGE_DEFAULT_TEMPLATE', '~/Layouts/_Layout.php');
# default page title
define('PAGE_DEFAULT_TITLE', 'SmallServerAdmin');
# default encode
define('PAGE_DEFAULT_ENCODE', 'utf-8');
# default culture
define('PAGE_DEFAULT_CULTURE', 'en');
# meta tags
define('META_AUTHOR', 'Aleksey Nemiro');
define('META_ROBOTS', 'none');
# ---------------------------------------------------------------
?>