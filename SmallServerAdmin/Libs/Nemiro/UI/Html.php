<?php
namespace Nemiro\UI
{

  /*
   * Copyright © Aleksey Nemiro, 2009. All rights reserved.
   * 
   * Licensed under the Apache License, Version 2.0 (the "License");
   * you may not use this file except in compliance with the License.
   * You may obtain a copy of the License at
   * 
   * http://www.apache.org/licenses/LICENSE-2.0
   * 
   * Unless required by applicable law or agreed to in writing, software
   * distributed under the License is distributed on an "AS IS" BASIS,
   * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
   * See the License for the specific language governing permissions and
   * limitations under the License.
   */

  /**
   * Represents HTML elements.
   * 
   * @author     Aleksey Nemiro <aleksey@nemiro.ru>
   * @copyright  © Aleksey Nemiro, 2009. All rights reserved.
   */
  class Html
  {

    /**
     * Returns <label />.
     * 
     * @param \string $title The title.
     * @param \string $forId The for id.
     * @param \array $htmlAttributes An associative array that contains the HTML attributes to set for the element. For example: array('style' => 'color:red', 'data-my-value' => '123').
     * 
     * @return \string
     */
    public static function Label($title, $forId = NULL, $htmlAttributes = NULL)
    {
      if (($attr = Html::BuildAttributes($htmlAttributes)) !== '') $attr = ' '.$attr;

      if (!isset($forId) || $forId === '')
      {
        return sprintf('<label%s>%s</label>', $attr, $title);
      }
      else
      {
        return sprintf('<label for="%s"%s>%s</label>', $forId, $attr, $title);
      }
    }

    /**
     * Returns a check box input element.
     * 
     * @param \string $name The name of element.
     * @param \string $value The value. Default: true.
     * @param \bool $checked The checked status. Default: false.
     * @param \array $htmlAttributes An associative array that contains the HTML attributes to set for the element. For example: array('style' => 'color:red', 'data-my-value' => '123').
     * 
     * @return string
     */
    public static function CheckBox($name, $value = 'true', $checked = false, $htmlAttributes = NULL)
    {
      $result = '<input type="checkbox" ';
      $result .= 'name="'.$name.'" id="'.$name.'" ';
      if ($value != NULL && strlen($value) > 0) $result .= 'value="'.$value.'" ';
      if (\Nemiro\Server::IsPostBack() && isset($_POST[$name]) && $_POST[$name] === $value)
      {
        $result .= 'checked="checked" ';
      }
      if (($attr = Html::BuildAttributes($htmlAttributes)) !== '') $result .= $attr.' ';
      $result .= '/>';

      return $result;
    }

    /**
     * Returns a list of checkbox.
     * 
     * @param \string $name Then element name.
     * @param \array[] $list The list of values. For example: array(array('value' => '1', 'title' => 'First item'), array('value' => '2', 'title' => 'Second item'))
     * @param \array $htmlAttributes An associative array that contains the HTML attributes to set for the element. For example: array('style' => 'color:red', 'data-my-value' => '123').
     * 
     * @return \string
     */
    public static function CheckBoxList($name, $list, $htmlAttributes = NULL)
    {
      $result = '';
      if (($attr = Html::BuildAttributes($htmlAttributes)) !== '') $attr .= ' ';
      for ($i = 0; $i <= count($list) - 1; $i++)
      {
        if (strlen($result) > 0) $result .= '<br />';
        $result .= '<input type="checkbox" ';
        $result .= 'name="'.$name.'[]" id="'.$name.$i.'" ';
        if ($list[$i]['value'] != NULL && strlen($list[$i]['value']) > 0) $result .= 'value="'.$list[$i]['value'].'" ';
        if (\Nemiro\Server::IsPostBack() && isset($_POST[$name]) && in_array($list[$i]['value'], $_POST[$name]))
        {
          $result .= 'checked="checked" ';
        }
        $result .= $attr;
        $result .= '/>';
        if ($list[$i]['title'] != NULL && strlen($list[$i]['title']) > 0)
        {
          $result .= ' '.Html::Label($list[$i]['title'], $name.$i);
        }
      }
      return $result;
    }

    /**
     * Returns a radio box input element
     * 
     * @param \string $name The name of the element.
     * @param \string $id The id of the element.
     * @param \string $value The value. Default: true.
     * @param \bool $checked The checked status. Default: false.
     * @param \array $htmlAttributes An associative array that contains the HTML attributes to set for the element. For example: array('style' => 'color:red', 'data-my-value' => '123').
     * 
     * @return \string
     */
    public static function RadioButton($name, $id, $value = 'true', $checked = false, $htmlAttributes = NULL)
    {
      if (!isset($id) || $id == '') $id = $name;
      $result = '<input type="radio" ';
      $result .= 'name="'.$name.'" id="'.$id.'" ';
      if ($value != NULL && strlen($value) > 0) $result .= 'value="'.$value.'" ';
      if (\Nemiro\Server::IsPostBack() && isset($_POST[$name]) && $_POST[$name] === $value)
      {
        $result .= 'checked="checked" ';
      }
      if (($attr = Html::BuildAttributes($htmlAttributes)) !== '') $result .= $attr.' ';
      $result .= '/>';

      return $result;
    }

    /**
     * Returns a list of radio.
     * 
     * @param \string $name Then element name.
     * @param \array[] $list The list of values. For example: array(array('value' => '1', 'title' => 'First item'), array('value' => '2', 'title' => 'Second item'))
     * @param \array $htmlAttributes An associative array that contains the HTML attributes to set for the element. For example: array('style' => 'color:red', 'data-my-value' => '123').
     * 
     * @return \string
     */
    public static function RadioButtonList($name, $list, $htmlAttributes = NULL)
    {
      $result = '';
      if (($attr = Html::BuildAttributes($htmlAttributes)) !== '') $attr .= ' ';
      for ($i = 0; $i <= count($list) - 1; $i++)
      {
        if (strlen($result) > 0) $result .= '<br />';
        $result .= '<input type="radio" ';
        $result .= 'name="'.$name.'[]" id="'.$name.$i.'" ';
        if ($list[$i]['value'] != NULL && strlen($list[$i]['value']) > 0) $result .= 'value="'.$list[$i]['value'].'" ';
        if (\Nemiro\Server::IsPostBack() && isset($_POST[$name]) && in_array($list[$i]['value'], $_POST[$name]))
        {
          $result .= 'checked="checked" ';
        }
        $result .= $attr;
        $result .= '/>';
        if ($list[$i]['title'] != NULL && strlen($list[$i]['title']) > 0)
        {
          $result .= ' '.Html::Label($list[$i]['title'], $name.$i);
        }
      }
      return $result;
    }

    /**
     * Returns a text input element.
     * 
     * @param \string $name The name of the form field.
     * @param \string $value The value of the text input element.
     * @param \array $htmlAttributes An associative array that contains the HTML attributes to set for the element. For example: array('style' => 'color:red', 'data-my-value' => '123').
     * 
     * @return \string
     */
    public static function TextBox($name, $value = '', $htmlAttributes = NULL)
    {
      return \Nemiro\UI\Html::Input('text', $name, $value, $htmlAttributes);
    }

    /**
     * Returns a password input element.
     * 
     * @param \string $name The name of the form field.
     * @param \string $value The value of the password input element.
     * @param \array $htmlAttributes An associative array that contains the HTML attributes to set for the element. For example: array('style' => 'color:red', 'data-my-value' => '123').
     * 
     * @return \string
     */
    public static function Password($name, $value = '', $htmlAttributes = NULL)
    {
      return \Nemiro\UI\Html::Input('password', $name, $value, $htmlAttributes);
    }

    /**
     * Returns a hidden input element.
     * 
     * @param \string $name The name of the form field.
     * @param \string $value The value of the hidden input element.
     * @param \array $htmlAttributes An associative array that contains the HTML attributes to set for the element. For example: array('style' => 'color:red', 'data-my-value' => '123').
     * 
     * @return \string
     */
    public static function Hidden($name, $value = '', $htmlAttributes = NULL)
    {
      return \Nemiro\UI\Html::Input('hidden', $name, $value, $htmlAttributes);
    }

    /**
     * Returns a textarea element.
     * 
     * @param \string $name The element name.
     * @param \string $value Then text of textarea.
     * @param \int|NULL $rows The rows count.
     * @param \array $htmlAttributes An associative array that contains the HTML attributes to set for the element. For example: array('style' => 'color:red', 'data-my-value' => '123').
     * 
     * @return \string
     */
    public static function TextArea($name, $value = '', $rows = NULL, $htmlAttributes = NULL)
    {
      $result = '<textarea ';
      $result .= 'name="'.$name.'" id="'.$name.'"';

      if ($rows !== NULL && (int)$rows > 0)
      {
        $result .= ' rows="'.$rows.'"';
      }
      
      if (($attr = Html::BuildAttributes($htmlAttributes)) !== '') $attr .= ' '.$attr;
      $result .= $attr;

      $result .= '>';
      if (\Nemiro\Server::IsPostBack() && isset($_POST[$name]))
      {
        $result .= $_POST[$name];
      }
      else
      {
        if ($value != NULL && strlen($value) > 0) $result .= $value;
      }
      $result .= '</textarea>';

      return $result;
    }
    
    /**
     * Summary of DropDownList
     * @param \string $name The name of the form field to return.
     * @param \string $value The selected value.
     * @param \array[] $list A item list that are used to populate the drop-down list. For example: array(array('value' => '1', 'title' => 'First item'), array('value' => '2', 'title' => 'Second item', 'selected' => 'true'))
     * 
     * @return string
     */
    public static function DropDownList($name, $list, $htmlAttributes = NULL)
    {
      $result = '';
      if (($attr = Html::BuildAttributes($htmlAttributes)) !== '') $attr .= ' ';
      $result .= '<select name="'.$name.'" id="'.$name.'"';
      if (($attr = Html::BuildAttributes($htmlAttributes)) !== '') $result .= ' '.$attr;
      $result .= '>';

      for ($i = 0; $i <= count($list) - 1; $i++)
      {
        $value = ''; $title = ''; $selected = false;

        if (is_array($list[$i]))
        {
          $value = $list[$i]['value'];
          $title = $list[$i]['title'];
          $selected = !\Nemiro\Server::IsPostBack() && isset($list[$i]['selected']) && (bool)$list[$i]['selected'] == TRUE;
        }
        else
        {
          $value = $i;
          $title = $list[$i];
        }

        if (!$selected)
        {
          $selected = (\Nemiro\Server::IsPostBack() && isset($_POST[$name]) && $value == $_POST[$name]);
        }

        $result .= '<option ';

        if ($value != NULL && strlen($value) > 0) $result .= 'value="'.$value.'"';

        if ($selected)
        {
          $result .= ' selected="selected"';
        }

        $result .= '>';

        if ($title != NULL && strlen($title) > 0)
        {
          $result .= $title;
        }

        $result .= '</option>';
      }
      $result .= '</select>';
      return $result;
    }
    
    /**
     * Returns a multi-select select element.
     * 
     * @param mixed $name The name of the form field to return.
     * @param \int $size The rows count. Default: 10.
     * @param \array[] $list A item list that are used to populate the drop-down list. For example: array(array('value' => '1', 'title' => 'First item'), array('value' => '2', 'title' => 'Second item', 'selected' => 'true'))
     * @param \array $htmlAttributes An associative array that contains the HTML attributes to set for the element. For example: array('style' => 'color:red', 'data-my-value' => '123').
     * 
     * @return string
     */
    public static function ListBox($name, $size = 10, $list, $htmlAttributes = NULL)
    {
      if (!isset($htmlAttributes) || count($htmlAttributes) <= 0)
      {
        $htmlAttributes = array();
      }
      $htmlAttributes['size'] = isset($size) ? $size : 1;

      return \Nemiro\UI\Html::DropDownList($name, $list, $htmlAttributes);
    }

    /**
     * Outputs a list of pages.
     * 
     * @param mixed $page Current page number.
     * @param \int $maxPageList Maximum pages on the list. Default: 10.
     * @param \int $maxDataPerPage Maximum records on the one page. Default: 25.
     * @param \int $recordCount Total records count.
     * @param \string $url The url. Default: $_SERVER['REQUEST_URI'].
     * @param \string $queryDelimiter Separator page list in the $url. Default: 'page='.
     * @param \string $anchor Anchor, which will be added to the links. Including the #.
     * @param \array $allowQueryParameters The list of allowed query keys.
     * @return string
     */
    public static function Pagination($page, $recordCount, $maxDataPerPage = 25, $maxPageList = 10, $url = NULL, $queryDelimiter = 'page=', $allowQueryParameters = NULL, $anchor = '')
    {
      if ($url == NULL || $url == '') 
      {
        $url = $_SERVER['REQUEST_URI'];
        if (strpos($url, '?') !== FALSE)
        {
          $url = substr($url, 0, strpos($url, '?'));

          if (isset($allowQueryParameters) && count($allowQueryParameters) > 0)
          {
            parse_str($_SERVER['QUERY_STRING'], $query);
            $new_query = array();

            foreach ($query as $key => $value)
            {
              if (in_array($key, $allowQueryParameters) !== TRUE)
              {
                continue;
              }
              $new_query[$key] = $value;
            }

            if (count($new_query) > 0)
            {
              $url .= '?'.http_build_query($new_query, '', '&amp;');
            }
          }
        }
      }

      if ((int)$page <= 0) $page = 1;
      
      if (strpos($queryDelimiter, '/') === FALSE)
      {
        if (strpos($url, '?') === FALSE)
        {
          $queryDelimiter = '?'.$queryDelimiter;
        }
        else
        {
          $queryDelimiter = '&'.$queryDelimiter;
        }
      }

      $result = '<ul class="pagination">';

      // calculate the number of pages
      $part = ceil($recordCount / $maxDataPerPage);

      if ($part <= 1) $part = 1;
      
      // first page
      if ($page >= $maxPageList) 
      {
        $is = $page - ($maxPageList - 2); 
      } 
      else 
      {
        $is = 1;
      }

      if ($is <= 0) $is = 1;
      
      $partTo = $is + $part;
      if ($partTo > $part) $partTo = $part;

      $ic = 0;
      
      if ($is > 1) 
      {
        $result .= '<li><a href="'.$url.$queryDelimiter.($is - 1).$anchor.'" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>';
      }
      
      for ($i = $is; $i <= $partTo; $i++) 
      {
        if ($ic === $maxPageList) 
        {
          $result .= '<li><a href="'.$url.$queryDelimiter.$i.$anchor.'" aria-label="Next"><span aria-hidden="true">&raquo;</span></a></li>';
          break;
        }

        $result .= '<li'.($i == $page ? ' class="active"' : '').'><a href="'.$url.$queryDelimiter.$i.$anchor.'">'.$i.'</a></li>';

        $ic++;
      }

      $result .= '</ul>';
      
      return $result;
    }

    /**
     * Returns <input />.
     * 
     * @param \string $name The name of the element.
     * @param \string $type The type of the input.
     * @param \string $value The value of the input.
     * @param \array $htmlAttributes The additional attributes. Associative array - key = value.
     * 
     * @return \string
     */
    private static function Input($type, $name, $value, $htmlAttributes = NULL)
    {
      if (!isset($htmlAttributes) || count($htmlAttributes) <= 0)
      {
        $htmlAttributes = array();
      }

      $htmlAttributes['type'] = $type;

      $result = '<input ';
      $result .= Html::BuildAttributes($htmlAttributes).' ';
      $result .= 'name="'.$name.'" id="'.$name.'" ';

      if (\Nemiro\Server::IsPostBack() && isset($_POST[$name]))
      {
        $result .= 'value="'.$_POST[$name].'" ';
      }
      else
      {
        if ($value != NULL && strlen($value) > 0) $result .= 'value="'.$value.'" ';
      }

      $result .= '/>';

      return $result;
    }

    /**
     * Builds a string of attributes.
     * 
     * @param \array $htmlAttributes 
     * 
     * @return \string
     */
    private static function BuildAttributes($htmlAttributes)
    {
      if (!isset($htmlAttributes) || count($htmlAttributes) <= 0)
      {
        return '';
      }
      
      $result = '';
      
      foreach ($htmlAttributes as $key => $value)
      {
        if ($result != '') $result .= ' ';
        // if ($key == '_class') $key = 'class';
        $result .= sprintf('%s="%s"', $key, str_replace('"', '\"', $value));
      }

      return $result;
    }

  }

}
?>