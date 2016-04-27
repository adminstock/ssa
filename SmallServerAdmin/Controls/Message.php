<?php
/**
 * Represents message control.
 */
class Message extends Nemiro\UI\Control
{

  /**
   * The message text.
   * 
   * @var string
   */
  public $Content = '';

  /**
   * Gets or sets the message type. Allowed: Danger (default), Warning, Info, Success.
   * @var mixed
   */
  public $Type = 'Danger';

  /**
   * 
   * @var bool
   */
  public $ShowIcon = true;

  /**
   * Returns css class for the message block.
   * 
   * @return string
   */
  function GetCssClass()
  {
    switch (strtolower($this->Type))
    {
      case 'warning':
        return 'alert alert-warning';

      case 'info':
      case 'question':
        return 'alert alert-info';
              
      case 'success':
        return 'alert alert-success';

      default:
        return 'alert alert-danger';
    }
  }

  /**
   * Returns icon for the message.
   * 
   * @return string
   */
  function GetIcon()
  {
    switch (strtolower($this->Type))
    {
      case 'warning':
        return '<span class="glyphicon glyphicon-warning-sign"></span> ';

      case 'info':
        return '<span class="glyphicon glyphicon-info-sign"></span> ';

      case 'question':
        return '<span class="glyphicon glyphicon-question-sign"></span> ';
      
      case 'success':
        return '<span class="glyphicon glyphicon-ok-sign"></span> ';

      default:
        return '<span class="glyphicon glyphicon-remove-sign"></span> ';
    }
  }
}
?>