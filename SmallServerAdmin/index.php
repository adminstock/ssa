<?php

require_once 'global.php';

class Index extends \Nemiro\UI\Page
{

  public $Widgets = '';

  function Load()
  {
    global $config;
    //global $info;
    
    if (isset($config['widgets']) && count($config['widgets']) > 0)
    {
      foreach ($config['widgets'] as $widget => $settings) 
      {
        if ((bool)$settings['Enabled'] !== TRUE)
        {
          continue;
        }

        $widget = trim($widget);
        if (is_file(\Nemiro\Server::MapPath('~/'.$widget.'/widget.php')))
        {
          if (isset($settings['Format']))
          {
            ob_start();
            include \Nemiro\Server::MapPath('~/'.$widget.'/widget.php');
            $this->Widgets .= sprintf($settings['Format'], ob_get_clean());
          }
          else
          {
            $this->Widgets .= '<div class="well">';
            ob_start();
            include \Nemiro\Server::MapPath('~/'.$widget.'/widget.php');
            $this->Widgets .= ob_get_clean();
            $this->Widgets .= '</div>';
          }
        }
        else
        {
          $this->Widgets .= '<div class="alert alert-danger" role="alert">Widget of the module <strong>'.$widget.'</strong> is not found.</div>';
        }
      }
    }
  }

}

\Nemiro\App::Magic(__NAMESPACE__);