<?php
# 1. Place this file in the root of your site;
# 2. Make your changes;
# 3. Rename the file to config.php.
# ---------------------------------------------------------------
# page caching (true|false)
define('PAGE_DEFAULT_CACHE', false);
# HTML compress (true|false)
define('PAGE_COMPRESS_HTML', false);
# debug mode (true|false)
define('DEBUG_MODE', true);
# root path
define('MAIN_PATH', $_SERVER['DOCUMENT_ROOT']);
# ---------------------------------------------------------------
# default layout
define('PAGE_DEFAULT_TEMPLATE', '~/layouts/main.php');
# default page title
define('PAGE_DEFAULT_TITLE', '%Your title here%');
# default encode
define('PAGE_DEFAULT_ENCODE', 'utf-8');
# default culture
define('PAGE_DEFAULT_CULTURE', 'en');
# meta tags
define('META_DESCRIPTION', '');
define('META_KEYWORDS', '');
define('META_AUTHOR', 'Aleksey Nemiro (VVO-JOK-LED)');
define('META_URL', '');
define('META_ROBOTS', 'ALL');
# ---------------------------------------------------------------
?>