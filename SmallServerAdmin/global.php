<?php
# WebForms config
require_once 'config.php';
# WebForms.PHP Application
require_once $_SERVER['DOCUMENT_ROOT'].'/Libs/Nemiro/App.php';
# SmallServerAdmin default config
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

# config for selected server
if (isset($_COOKIE['currentServer']) && $_COOKIE['currentServer'] != '')
{
  if (file_exists(\Nemiro\Server::MapPath('~/servers/'.$_COOKIE['currentServer'].'.php')))
  {
    # config found, include
    \Nemiro\Console::Info('Server config: '.$_COOKIE['currentServer'].'.php.');
    require_once \Nemiro\Server::MapPath('~/servers/'.$_COOKIE['currentServer'].'.php');
    # fix client-side config
    $config['client']['ServerAddress'] = $config['ssh_host'];
    $config['client']['ServerName'] = $config['server_name'];
  }
  else
  {
    # config not found, remove from cookies
    \Nemiro\Console::Warning('Server config "'.$_COOKIE['currentServer'].'.php" not found.');
    unset($_COOKIE['currentServer']);
    setcookie('currentServer', null, -1, '/');
  }
}

# language
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