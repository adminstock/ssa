<?php
namespace Settings
{

  require_once '../global.php';

  class Servers extends \Nemiro\UI\Page
  {

    public $CurrentServerAddress;
    public $CurrentServerIsDefault;

    function Load()
    {
      global $config;

      $this->CurrentServerAddress = $config['ssh_host'];
      $this->CurrentServerIsDefault = (!isset($_COOKIE['currentServer']) || $_COOKIE['currentServer'] == '');
    }

  }

  \Nemiro\App::Magic(__NAMESPACE__);

}