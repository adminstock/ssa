<?php
# 1. Place this file in the root of your site;
# 2. Make your changes;
# 3. Rename the file to global.php.
# ---------------------------------------------------------------

# base path for including
# set_include_path($_SERVER['DOCUMENT_ROOT']);

# config.php from the root path
require_once 'config.php';
# app.php from the WebForms.PHP
require_once $_SERVER['DOCUMENT_ROOT'].'/Nemiro/App.php';

# import and init application class
use Nemiro\App as App;
App::Init();

# set event handlers
App::AddHandler('Application_BeginRequest');
# you are not required to use all the handlers
# App::AddHandler('Application_EndRequest');
# App::AddHandler('Application_IncludedFile');
App::AddHandler('Application_Error');
# you can use custom handler names
App::AddHandler('Session_Start', 'MyHandler');

# include files from folder using Import.php
App::IncludeFile('~/Nemiro/Collections');

# include database clients
# get from https://github.com/alekseynemiro/Nemiro.Data.PHP
# App::IncludeFile('~/Nemiro/Data');

# include your modules
# App::IncludeFile('~/user.php');
# App::IncludeFile('~/your/path/here.php');

# application event handlers
function Application_BeginRequest()
{
  # echo 'Processing...';
}

# function Application_IncludedFile($path)
# {
#    echo sprintf('Included: %s', $path);
# }

function Application_Error($exception)
{
  # echo sprintf('Error: %s', $exception->getMessage());
}

function MyHandler()
{
  # echo 'Session is started!';
}
?>