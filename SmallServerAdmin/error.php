<?php

require_once 'global.php';

class Error extends \Nemiro\UI\Page
{

  public $ErrorCode = '';
  public $ReturnUrl;

  function Load()
  {
    if (isset($_GET['code']))
    {
      $this->ErrorCode = strtoupper($_GET['code']);
    }

    $this->ReturnUrl = $_GET['returnUrl'];
  }

}

\Nemiro\App::Magic(__NAMESPACE__);