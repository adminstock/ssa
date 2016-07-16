<?php
// WebForms config
require_once 'config.php';
// WebForms.PHP Application
require_once $_SERVER['DOCUMENT_ROOT'].'/Libs/Nemiro/App.php';
// SmallServerAdmin default config
require_once 'ssa.config.php';

// import and init application class
use Nemiro\App as App;

App::Init();

// set event handlers
App::AddHandler('Application_PageCreated');

// config for selected server
if (isset($_COOKIE['currentServer']) && $_COOKIE['currentServer'] != '')
{
  if (file_exists(\Nemiro\Server::MapPath('~/servers/'.$_COOKIE['currentServer'].'.php')))
  {
    // config found, include
    \Nemiro\Console::Info('Server config: '.$_COOKIE['currentServer'].'.php.');
    require_once \Nemiro\Server::MapPath('~/servers/'.$_COOKIE['currentServer'].'.php');
  }
  else
  {
    // config not found, remove from cookies
    \Nemiro\Console::Warning('Server config "'.$_COOKIE['currentServer'].'.php" not found.');
    unset($_COOKIE['currentServer']);
    setcookie('currentServer', null, -1, '/');
  }
}

if (!isset($config['ssh_host']) || $config['ssh_host'] == '')
{
  // default config
  $default_configs = ['default', 'DEFAULT', 'Default'];

  foreach($default_configs as $fileName)
  {
    if (file_exists(\Nemiro\Server::MapPath('~/servers/'.$fileName.'.php')))
    {
      // config found, include
      \Nemiro\Console::Info('Server config: '.$fileName.'.php.');
      require_once \Nemiro\Server::MapPath('~/servers/'.$fileName.'.php');
      setcookie('currentServer', $fileName, time() + 2592000, '/');
      break;
    }
  }

  unset($default_configs);
  unset($fileName);
}

# fix client-side config
if (isset($config['ssh_host']) && $config['ssh_host'] != '')
{
  $config['client']['ServerAddress'] = $config['ssh_host'];
  $config['client']['ServerName'] = (isset($config['server_name']) ? $config['server_name'] : NULL);
}
  
#region localization

// get current language
if (isset($_COOKIE['lang']) && $_COOKIE['lang'] != '')
{
  if (strpos($_COOKIE['lang'], ',') !== FALSE)
  {
    $CurrentLang = explode(',', $_COOKIE['lang'])[0];
    setcookie('lang', $CurrentLang, time() + 2592000, '/');
  }
  else
  {
    $CurrentLang = $_COOKIE['lang'];
  }
}
else
{
  $acceptLangs = explode(';', $_SERVER['HTTP_ACCEPT_LANGUAGE']);
  foreach ($acceptLangs as $al)
  {
    if (strpos($al, 'q=') !== FALSE) { continue; }

    if (strpos($al, ',') !== FALSE)
    {
      $CurrentLang = explode(',', $al)[0];
    }
    else
    {
      $CurrentLang = $al;
    }

    if ($CurrentLang != '')
    {
      break;
    }
  }
}

if ($CurrentLang == NULL || $CurrentLang == '')
{
  $CurrentLang = PAGE_DEFAULT_CULTURE;
}

// set to client-side
$config['client']['Lang'] = $CurrentLang;

if (isset($_GET['lang']) && $CurrentLang != $_GET['lang'] || (isset($_GET['lang']) && $_GET['lang'] == 'en'))
{
  setcookie('lang', $_GET['lang'], time() + 2592000, '/');

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
  return;
}

#endregion
#region ssh2 test

if (($currentScriptName = App::GetScriptName()) != 'error' && $currentScriptName != 'api' && $currentScriptName != 'servers' && $currentScriptName != 'logout')
{
  // check ssh2
  if (extension_loaded('ssh2') === FALSE)
  {
    \Nemiro\Server::Redirect('/error.php?code=SSH2_REQUIRED&returnUrl='.$_SERVER['REQUEST_URI']);
    return;
  }

  // check server status
  if (!isset($config['ssh_host']) || $config['ssh_host'] == '' || (isset($config['server_disabled']) && $config['server_disabled'] === TRUE))
  {
    if (file_exists(\Nemiro\Server::MapPath('~/settings/servers.php')))
    {
      \Nemiro\Server::Redirect('/settings/servers.php#?server_required=true&returnUrl='.$_SERVER['REQUEST_URI']);
    }
    else
    {
      \Nemiro\Server::Redirect('/error.php?code=SERVER_REQUIRED&returnUrl='.$_SERVER['REQUEST_URI']);
    }

    return;
  }

  // check connection
  if (!($sshConnection = ssh2_connect($config['ssh_host'], (int)$config['ssh_port'])))
  {
    if (file_exists(\Nemiro\Server::MapPath('~/settings/servers.php')))
    {
      \Nemiro\Server::Redirect('/settings/servers.php#?connection_failed=true&returnUrl='.$_SERVER['REQUEST_URI']);
    }
    else
    {
      \Nemiro\Server::Redirect('/error.php?code=CONNECTION_FAILED&returnUrl='.$_SERVER['REQUEST_URI']);
    }

    return;
  }

  // check password
  if (!ssh2_auth_password($sshConnection, $config['ssh_user'], $config['ssh_password']))
  {
    if (file_exists(\Nemiro\Server::MapPath('~/settings/servers.php')))
    {
      \Nemiro\Server::Redirect('/settings/servers.php#?authentication_failed=true&returnUrl='.$_SERVER['REQUEST_URI']);
    }
    else
    {
      \Nemiro\Server::Redirect('/error.php?code=AUTHENTICATION_FAILED&returnUrl='.$_SERVER['REQUEST_URI']);
    }

    return;
  }
}

#endregion
#region application event handlers

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

#endregion