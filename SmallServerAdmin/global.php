<?php
# WebForms config
require_once 'config.php';
# WebForms.PHP Application
require_once $_SERVER['DOCUMENT_ROOT'].'/Libs/Nemiro/App.php';
# SmallServerAdmin config
require_once 'ssa.config.php';

# import and init application class
use Nemiro\App as App;

App::Init();

# set event handlers
App::AddHandler('Application_BeginRequest');
App::AddHandler('Application_PageCreated');

# include database clients
# get from https://github.com/alekseynemiro/Nemiro.Data.PHP
# App::IncludeFile('~/Nemiro/Data');

$CurrentLang = (isset($_COOKIE['lang']) ? $_COOKIE['lang'] : PAGE_DEFAULT_CULTURE);

# application event handlers
function Application_BeginRequest()
{
  global $CurrentLang;

  if (isset($_GET['lang']) && $CurrentLang != $_GET['lang'] || (isset($_GET['lang']) && $_GET['lang'] == 'en'))
  {
    setcookie('lang', $_GET['lang'], time() + 2592000);

    unset($_GET['lang']);
    
    if (count($_GET) > 0)
    {
      \Nemiro\Server::$Url['query'] = $_GET;
    }
    else
    {
      unset(\Nemiro\Server::$Url['query']);
    }

    \Nemiro\Server::Redirect(\Nemiro\Server::$Url['path'].(isset(\Nemiro\Server::$Url['query']) ? '?'.http_build_query(\Nemiro\Server::$Url['query']) : '').(isset(\Nemiro\Server::$Url['fragment']) ? '#'.\Nemiro\Server::$Url['fragment'] : ''), 301);
  }
}

/**
 * @param \Nemiro\UI\Page $page 
 */
function Application_PageCreated($page)
{
  global $CurrentLang;

  if (isset($CurrentLang))
  {
    $page->Culture = $CurrentLang;
  }
}
?>