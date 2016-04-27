<?php
require_once 'AccordionItem.php';

/**
 * Represents Accordion.
 */
class Accordion extends Nemiro\UI\Control
{

  /**
   * List of panels.
   * 
   * @var AccordionItem[]
   */
  public $Items = array();

}
?>