<?php
namespace Nemiro\UI
{

  /*
   * Copyright © Aleksey Nemiro, 2007-2016. All rights reserved.
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

  use \Nemiro\App as App;

  /*
   * * ------------------------------------
   * * History  YYYY-MM-DD
   * * ------------------------------------
   * * Created: 2007-03-04
   * * Updated: 2008-05-24
   * * Updated: 2009-05-09
   * * Updated: 2015-10-17
   * * Updated: 2016-02-08
   * * Updated: 2016-04-20
   * * ------------------------------------
   */

  /**
   * The base class for pages.
   * 
   * @author      Aleksey Nemiro <aleksey@nemiro.ru>
   * @copyright   © Aleksey Nemiro, 2007-2009, 2015-2016. All rights reserved.
   */
  class Page
  {

    #region fields

    private $IsBuilt = false;

    /**
     * Ready to display the HTML code.
     * 
     * @var \string
     */
    private $OutputHtml;

    /**
     * Indicates a file HTML.
     * 
     * @var \bool
     */
    private $HtmlExists;
    
    /**
     * The user accept languages list.
     * 
     * @var \string[]
     */
    private $UserAcceptLanguage = array();

    /**
     * The list of registered controls.
     * 
     * @var \string['parent'] The name of parent: Template or Page.
     *        \string['tagPrefix'] The tag prefix of control. For example: php, asp, html, anyname.
     *          \string['tagName'] The tag name.
     *            \Nemiro\UI\Control The control instance.
     */
    private $RegisteredControls = array();

    /**
     * Indicates the the work in debug mode.
     * 
     * @var bool
     */
    public $Debug;
    
    /**
     * Enable caching or not.
     * @var bool
     */
    public $Cache;

    /**
     * Idicates the need to optimize the HTML.
     * 
     * @var \bool
     */
    public $Optimized;

    /**
     * The file name of template.
     * 
     * @var \string
     */
    public $Layout;

    /**
     * The encoding of the page.
     * 
     * @var \string
     */
    public $Encode;

    /**
     * Current culture.
     * 
     * @var \string
     */
    public $Culture;

    /**
     * The title of the page (<title></title>).
     * 
     * @var \string
     */
    public $Title;

    /**
     * The list of the content blocks.
     * 
     * @var \array
     */
    public $Content = array();
    
    /**
     * The meta tags of the page.
     * 
     * @var \array
     */
    public $Meta = array();

    /**
     * The client script list of the page.
     * 
     * @var \string[]
     */
    public $Scripts = array();

    /**
     * The list of placed controls.
     * 
     * @var \Nemiro\UI\Control[]
     */
    public $Controls = array();
    
    /**
     * The list of resources for the current culture.
     * 
     * @var \array
     */
    public $Resources = array();

    #endregion
    #region constructor

    /**
     * Initializes an instance of the Page class.
     */
    function __construct()
    {
      $this->Init();
    }

    #endregion
    #region private methods

    /**
     * Initializes the page.
     */
    private function Init()
    {
      # $this->TraceLog = array();
      # $this->Content = array();
      # $this->Controls = array();
      # $this->Meta = array();
      
      $this->Layout = defined('PAGE_DEFAULT_TEMPLATE') ? PAGE_DEFAULT_TEMPLATE : '';
      $this->Cache = defined('PAGE_DEFAULT_CACHE') ? PAGE_DEFAULT_CACHE : false;
      $this->Optimized = defined('PAGE_COMPRESS_HTML') ? PAGE_COMPRESS_HTML : false;
      $this->Title = defined('PAGE_DEFAULT_TITLE') ? PAGE_DEFAULT_TITLE : '';
      $this->Encode = defined('PAGE_DEFAULT_ENCODE') ? PAGE_DEFAULT_ENCODE : 'utf-8';
      $this->Culture = defined('PAGE_DEFAULT_CULTURE') ? PAGE_DEFAULT_CULTURE : '';

      if (defined('META_DESCRIPTION'))
      { 
        $this->Meta['DESCRIPTION'] = META_DESCRIPTION; 
      }

      if (defined('META_KEYWORDS'))
      { 
        $this->Meta['KEYWORDS'] = META_KEYWORDS; 
      }

      if (defined('META_AUTHOR'))
      { 
        $this->Meta['AUTHOR'] = META_AUTHOR; 
      }

      if (defined('META_URL'))
      { 
        $this->Meta['URL'] = META_URL;
      }

      if (defined('META_ROBOTS'))
      { 
        $this->Meta['ROBOTS'] = META_ROBOTS;
      } 

      # current culture
      $acceptLangs = explode(';', $_SERVER['HTTP_ACCEPT_LANGUAGE']);
      foreach ($acceptLangs as $al)
      {
        if (strpos($al, 'q=') !== FALSE) { continue; }

        if (strpos($al, ',') !== FALSE)
        {
          $this->UserAcceptLanguage = array_merge($this->UserAcceptLanguage, explode(',', $al));
        }
        else
        {
          $this->UserAcceptLanguage[] = $al;
        }
      }

      if (count($this->UserAcceptLanguage) > 0)
      {
        $this->Culture = $this->UserAcceptLanguage[0];
      }

      # parse directive
      $this->HtmlExists = false;
      $html_path = dirname($_SERVER['SCRIPT_FILENAME']).DIRECTORY_SEPARATOR.basename($_SERVER['SCRIPT_FILENAME'], '.php').'.html.php';
      $content = NULL;
      
      if (file_exists($html_path))
      { # html page is exists, parse it
        $content = file_get_contents($html_path); 
      }
      else if (!file_exists($html_path) && get_class($this) == 'Nemiro\UI\Page')
      { # it is default page instance
        ob_clean();
        $content = file_get_contents(App::GetScriptPath().App::GetScriptName().'.php');
        $content = preg_replace('#(require_once|require|include_once|include)\s+([\x22\x27]{1})(.*?global.php)([\x22\x27]{1});#', '', $content);
        $content = preg_replace('#(\\\\*?)Nemiro\\\\App\:\:Magic\(([^\)]*)\);#', '', $content);
      }

      # parse page directive
      $hasDirective = FALSE;
      if ($content != NULL)
      {
        $pattern = '|\<\?\#Page([^\?]*)\?\>|i';
        if (preg_match($pattern, $content, $m) > 0)
        {
          $hasDirective = TRUE;
          # normalization
          $directive = $m[0];
          $directive = str_replace('<?#', '<', $directive);
          $directive = str_replace('?>', '/>', $directive);
          # parse
          $x = new \SimpleXMLElement($directive);
          foreach ($x->attributes() as $key => $value)
          {
            if (strcasecmp($key, 'Culture') === 0)
            {
              $this->Culture = $value->__toString();
            }
            else if (strcasecmp($key, 'Layout') === 0)
            {
              $this->Layout = $value->__toString();
            }
            else if (strcasecmp($key, 'Title') === 0)
            {
              $this->Title = $value->__toString();
            }
            else if (strcasecmp($key, 'Optimized') === 0)
            {
              $this->Optimized = (bool)$value->__toString();
            }
            else if (strcasecmp($key, 'Cache') === 0)
            {
              $this->Cache = (bool)$value->__toString();
            }
          }
        }
        # remove directive
        $content = preg_replace($pattern, '', $content);
      }

      # log
      \Nemiro\Console::Info('Root: %s.', MAIN_PATH);
      \Nemiro\Console::Info('Page: %s.', App::GetScriptName());
      \Nemiro\Console::Info('Page directive: %s.', ($hasDirective ? 'TRUE' : 'FALSE'));
      \Nemiro\Console::Info('Debug mode: %s.', $this->Debug ? 'TRUE' : 'FALSE');
      \Nemiro\Console::Info('Cache mode: %s.', $this->Cache ? 'TRUE' : 'FALSE');
      \Nemiro\Console::Info('Optimized: %s.', $this->Optimized ? 'TRUE' : 'FALSE');
      \Nemiro\Console::Info('Template: %s.', $this->Layout);

      \Nemiro\Console::Info('Accept Language: %s.', implode(', ', $this->UserAcceptLanguage));
      \Nemiro\Console::Info('Current Culture: %s.', $this->Culture);

      # parse controls and content block
      if ($content != NULL)
      {
        # parse controls
        $content = $this->RegisterControls('Page', $content);
        # search content blocks
        if (preg_match_all('|<php:Content(\s{1,})ID(\s*)=(\s*)"([^\"]+)">(.*?)</php:Content>|is', $content, $m))
        {
          $this->HtmlExists = true;
          for ($i = 0; $i < count($m[4]); $i++)
          {
            \Nemiro\Console::Info('-- Added content block: %s.', $m[4][$i]);
            $this->Content[$m[4][$i]] = $m[5][$i];
          }
        }
      }
    }

    /**
     * Buld the page.
     */
    public function Build()
    {
      if ($this->IsBuilt)
      {
        throw new \ErrorException('Output stream of the page is closed.');
      }

      $this->PreLoad();

      $this->InitHeaders();
      $this->LoadTemplate();
      $this->LoadResources();

      $this->Load();
      
      $this->Render();

      $this->LoadComplete();

      $this->IsBuilt = true;
    }

    /**
     * Loads a template for the page.
     */
    private function LoadTemplate()
    {
      \Nemiro\Console::Info('Template loading.');
      if ($this->Layout != NULL && strlen($this->Layout) > 0)
      {
        $result = file_get_contents(\Nemiro\Server::MapPath($this->Layout));
        if (!$result) 
        {
          \Nemiro\Console::Error('-- File %s not found.', $this->Layout);
        }
        else
        {
          # parse controls
          $this->OutputHtml = $this->RegisterControls('Template', $result);
        }
      }
      \Nemiro\Console::Info('Template loaded.');
    }
    
    /**
     * Parses text and extracts information about controls.
     * 
     * @param \string $parent The parent name. "Template" for template.
     * @param \string $result Text to parse.
     * 
     * @return \string
     */
    private function RegisterControls($parent, $result)
    {
      $pattern = '|\<\?\#Register([^\?]*)\?\>|i';

      if (preg_match_all($pattern, $result, $m) > 0)
      {
        \Nemiro\Console::Info('-- Total registered controls: %s.', count($m[0]));

        foreach ($m[0] as $k => $v)
        {
          # reset
          $c_src = $c_tagName = $c_tagPrefix = $c_class = NULL;

          # normalization
          $v = str_replace('<?#', '<', $v);
          $v = str_replace('?>', '/>', $v);

          # parse attributes
          $x = new \SimpleXMLElement($v);
          
          foreach ($x->attributes() as $key => $value)
          {
            if (strcasecmp($key, 'Src') === 0)
            {
              $c_src = $value->__toString();
            }
            else if (strcasecmp($key, 'TagName') === 0)
            {
              $c_tagName = $value->__toString();
            }
            else if (strcasecmp($key, 'TagPrefix') === 0)
            {
              $c_tagPrefix = $value->__toString();
            }
            else if (strcasecmp($key, 'ClassName') === 0)
            {
              $c_class = $value->__toString();
            }
          }

          # has data
          if (isset($c_src) && isset($c_tagName) && isset($c_tagPrefix))
          { 
            $c_html_src = \Nemiro\Server::MapPath(substr_replace($c_src, '.html.php', -4));

            if (file_exists($c_html_src))
            {
              # control with external html file
              require(\Nemiro\Server::MapPath($c_src));
              # the class name is not specified
              if (!isset($c_class))
              {
                # get file name
                $c_class = basename($c_src, '.php');
              }
              # check class for the control
              if (class_exists($c_class))
              {
                # class found, create a new instance
                $c = new $c_class($c_src, $c_tagPrefix, $c_tagName, $this);
                # get properties
                if (($fields = get_class_vars(get_class($c))) !== FALSE)
                {
                  foreach ($fields as $name => $value) 
                  {
                    if (array_search($name, \Nemiro\UI\Control::$ProtectedFields) !== FALSE)
                    {
                      continue;
                    }

                    $c->DefaultValues[$name] = $c->$name;
                  }
                }
              }
              else
              {
                \Nemiro\Console::Error('Class %s for %s not found.', $c_class, $c_src);
              }
            }
            else
            {
              # control without html
              $c = new Control($c_src, $c_tagPrefix, $c_tagName, $this);
            }

            # add control to list
            $this->RegisterControl($parent, $c);
          }
        }
        # remove control registration blocks
        $result = preg_replace($pattern, '', $result);
      }

      return $result;
    }

    /**
     * Adds control to collection.
     * 
     * @param \string $parent The parent name. "Template" for template.
     * @param \string $prefix The tag prefix. For example: php, asp, test.
     * @param \string $name The tag name. For example: TextBox, RadioButton etc.
     * @param \Nemiro\UI\Control $control The control instance.
     */
    private function RegisterControl($parent, $control)
    {
      \Nemiro\Console::Info("-- AddControl <%s:%s>, source: %s.", $control->TagPrefix, $control->TagName, $control->Source);
      $this->RegisteredControls[$parent][$control->TagPrefix][$control->TagName] = $control;
    }
    
    /**
     * Loads text resources for the current culture.
     */
    private function LoadResources()
    {
      \Nemiro\Console::Info('Resources loading.');

      # global resources
      $this->MergeResources('~/global.json');
      # global resources for current culture
      if ($this->Culture != NULL && $this->Culture != '')
      {
        if (!$this->MergeResources('~/global.'.$this->Culture.'.json') && strpos($this->Culture, '-') !== FALSE)
        {
          $this->MergeResources('~/global.'.explode('-', $this->Culture)[0].'.json');
        }
      }

      $sciptName = App::GetScriptPath().App::GetScriptName();

      # load default resourses for current page
      $this->MergeResources($sciptName.'.json');

      # load resourses for current culture
      if ($this->Culture != NULL && $this->Culture != '')
      {
        if (!$this->MergeResources($sciptName.'.'.$this->Culture.'.json') && strpos($this->Culture, '-') !== FALSE)
        {
          $this->MergeResources($sciptName.'.'.explode('-', $this->Culture)[0].'.json');
        }
      }
      
      \Nemiro\Console::Info('Resources loaded.');
    }

    private function MergeResources($path)
    {
      $path = \Nemiro\Server::MapPath($path);

      if (file_exists($path))
      {
        if (!($data = json_decode(file_get_contents($path), TRUE)))
        {
          \Nemiro\Console::Error('Resources file "%s" parse error #%s.', $path, json_last_error());
          return FALSE;
        }
        else
        {
          if (!isset($this->Resources)) $this->Resources = array();
          foreach ($data as $item)
          {
            $this->Resources[$item['Key']] = $item['Value'];
          }
          return TRUE;
        }
      }
      else
      {
        return FALSE;
      }
    }

    /**
     * Adds HTTP headers.
     */
    private function InitHeaders()
    {
      \Nemiro\Console::Info('Adding HTTP headers.');
      # caching --------------- ---------------------------------
      if (!$this->Cache)
      {
        header('Expire: Mon, 1 Jan 1990 01:01:01 GMT');
        header('Cache-Control: no-cache, must-revalidate');
        header('Pragma: no-cache');
        header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');

        \Nemiro\Console::Info('-- Expire: Mon, 1 Jan 1990 01:01:01 GMT');
        \Nemiro\Console::Info('-- Cache-Control: no-cache, must-revalidate');
        \Nemiro\Console::Info('-- Pragma: no-cache');
        \Nemiro\Console::Info('-- Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
      }
      # --- -----------------------------------------------------
      header('Content-Type: text/html; charset='.$this->Encode);
      \Nemiro\Console::Info('-- Content-Type: text/html; charset=%s', $this->Encode);
      \Nemiro\Console::Info('HTTP headers added.');
    }

    /**
     * Generates and output data.
     */
    private function Render() 
    {
      \Nemiro\Console::Info('Generating data to output.');
      $result = $this->OutputHtml;

      # set default structure
      if (strlen($result) <= 0)
      {
        $result = '<!DOCTYPE html><html><head><title></title></head><body></body></html>';
      }

      # search and set <title></title>
      $pattern = '|\<title\>([^\<]*)\</title\>|i';
      if (preg_match($pattern, $result) > 0)
      {
        if ($this->Title != NULL && $this->Title != '') 
        {
          $newTitle = $this->Title;
          $result = preg_replace($pattern, '<title>'.$newTitle.'</title>', $result);
        }
      }

      # set meta data
      $h = '';
      $h .= '<meta http-equiv="Content-Type" content="text/html;charset='.$this->Encode.'" />';
      $h .= '<meta name="generator" content="PHP WebForms Engine by Aleksey Nemiro // v.3.1 // http://aleksey.nemiro.ru" />';
      
      # custom meta data
      if ($this->Meta != NULL && count($this->Meta) > 0)
      {
        foreach ($this->Meta as $k => $v)
        {
          if ($v != NULL && count($v) > 0) $h .= '<meta name="'.$k.'" content="'.$v.'" />';
        }
      }

      # client scripts
      if ($this->Scripts != NULL && count($this->Scripts) > 0)
      {
        foreach ($this->Scripts as $s)
        { 
          $h .= '<script src="'.$s.'" type="text/javascript"></script>';
        }
      }

      # add head
      $pattern = '|<head>(.*)</head>|is';
      $result = preg_replace($pattern, '<head>$1'.$h.'</head>', $result);

      # controls
      $result = $this->RanderControl('Template', NULL, $result);

      # content blocks
      if ($this->Content != NULL && count($this->Content) > 0)
      {
        foreach ($this->Content as $k => $v)
        {
          # controls
          $v = $this->RanderControl('Page', NULL, $v);
          # content
          if ($this->HtmlExists)
          { # required server code processing
            $result = preg_replace('|\<php\:'.$k.'(\s*)\/\>|', $this->Execute($v), $result); # str_replace('<php:'.$k.'/>'
          }
          else
          {
            $result = preg_replace('|\<php\:'.$k.'(\s*)\/\>|', $v, $result); # str_replace('<php:'.$k.'/>', $v, $result);
          }
        }
      }

      # localization
      $localizationIteration = 0;
      while (preg_match_all('|\${(?<key>[^\}]+)}|', $result, $resources) > 0 && $localizationIteration < 2)
      {
        foreach ($resources['key'] as $key)
        {
          if (isset($this->Resources[$key]))
          {
            $result = str_replace('${'.$key.'}', $this->Resources[$key], $result);
          }
          else
          {
            \Nemiro\Console::Warning('[%s] Resource key "%s" not found.', $localizationIteration, $key);
          }
        }
        $localizationIteration++;
      }

      # clear
      $result = preg_replace('|<php:([^\>]+?)/>|is', '', $result);
      $result = preg_replace('| xmlns:php="http://aleksey.nemiro.ru/php-webforms"|is', '', $result);

      # compress
      if ($this->Optimized)
      {
        $result = $this->Optimization($result);
      }

      $this->OutputHtml = $result;

      \Nemiro\Console::Info('Generating data is completed.');

      \Nemiro\Console::Info('Output.');

      echo $this->OutputHtml;
    }

    private function RanderControl($parent, $prefix, $result)
    {
      if ($this->RegisteredControls != NULL && count($this->RegisteredControls) > 0)
      {
        # masking quotes
        $masked = \Nemiro\Text::MaskingQuotes($result);
        # processing
        foreach ($this->RegisteredControls as $owner => $namespace)
        {
          if ($owner == $parent)
          {
            foreach ($namespace as $controls)
            {
              foreach ($controls as $control)
              {
                $controlEnd = FALSE;
                $controlStart = 0;
                $controlOpen = '<'.$control->Name;
                $controlClose = '</'.$control->Name.'>';
                $controlNameLen = strlen($control->Name);
                $controlContent = '';
                $controlContentMasked = '';
                $i = 1;

                while (($controlStart = strpos($masked, $controlOpen, $controlStart)) !== FALSE)
                {
                  # select all before the first triangular brackets (>)
                  if (($controlEndOfDeclaration = strpos($masked, '>', $controlStart)) === FALSE)
                  {
                    \Nemiro\Console::Warning('Invalid declaration format: <%s />.', $control->Name);
                    $controlStart++;
                    continue;
                  }

                  # reset
                  \Nemiro\UI\Control::ResetInstance($control);

                  # get attributes
                  $attributes = trim(substr($result, $controlStart + $controlNameLen + 1, $controlEndOfDeclaration - ($controlStart + $controlNameLen) - 1), ' /');
                  
                  # check the penultimate symbol 
                  if (substr($result, $controlEndOfDeclaration - 1, 1) != '/')
                  {
                    # search closed block
                    $controlEnd = \Nemiro\Text::SearchEndsBlock($masked, $controlStart + 1, $controlOpen, $controlClose);
                  }

                  # set id
                  $control->ID = sprintf('%s%s_%s%s', $parent, (isset($prefix) && $prefix != '' ? '_'.$prefix : ''), $control->TagName, $i);

                  # get body
                  if ($controlEnd !== FALSE)
                  {
                    $controlContent = substr($result, $controlStart, $controlEnd - $controlStart + strlen($controlClose) + 1);
                    $controlContentMasked = substr($masked, $controlStart, $controlEnd - $controlStart + strlen($controlClose) + 1);
                    $body = substr($result, $controlEndOfDeclaration + 1, $controlEnd - $controlEndOfDeclaration - 1);
                    # parse childs
                    if ($this->ParseControlChild($parent, NULL, $control, $body, substr($masked, $controlEndOfDeclaration + 1, $controlEnd - $controlEndOfDeclaration - 1), 0) === FALSE)
                    {
                      # parse control content
                      if (property_exists($control, 'Content'))
                      {
                        $control->Content = $this->Execute($body);
                      }
                    }
                  }
                  else
                  {
                    $controlContent = substr($result, $controlStart, $controlEndOfDeclaration - $controlStart + 1);
                    $controlContentMasked = substr($masked, $controlStart, $controlEndOfDeclaration - $controlStart + 1);
                  }

                  # set attributes
                  if ($attributes != '')
                  {
                    # parse attributes
                    $x = new \SimpleXMLElement('<item '.$attributes.' />');
                    
                    foreach ($x->attributes() as $attrKey => $attrValue)
                    {
                      if (!property_exists($control, $attrKey))
                      {
                        \Nemiro\Console::Warning('Property "%s" not found in the "%s" instance.', $attrKey, $control->TagName);
                      }

                      $control->$attrKey = $attrValue->__toString();
                    }
                  }

                  # set fields from the page load handler
                  if (isset($this->Controls[$control->ID]) && ($fields = get_object_vars($this->Controls[$control->ID])) !== FALSE)
                  {
                    foreach ($fields as $fieldName => $fieldValue) 
                    {
                      if (!property_exists($control, $fieldName))
                      {
                        \Nemiro\Console::Warning('Property "%s" not found in the "%s" instance.', $fieldName, $control->TagName);
                      }

                      $control->$fieldName = $fieldValue;
                    }
                  }

                  # replace
                  $control->Render();
                  $result = str_replace($controlContent, $control->Body, $result, $count);
                  $masked = str_replace($controlContentMasked, str_repeat(chr(0), strlen($control->Body)), $masked);
                  \Nemiro\Console::Info('-- RangeControl: %s => %s; %s; total: %s', $owner, $control->Name, $attributes, $count);

                  $controlStart += strlen($control->Body) + 1;

                  # add control to collection
                  $this->Controls[$control->ID] = $control;

                  $i++;
                }
              }
            }
          }
        }
      }

      return $result;
    }

    private function ParseControlChild($scope, $parent, &$control, $source, $masked, $level)
    {
      if (!preg_match_all('/\<(?<prefix>[\w\d]+):(?<tag>[\w\d\x5F]+)/im', $masked, $matches, PREG_OFFSET_CAPTURE))
      {
        return FALSE;
      }

      $maxEndIndex = -1;

      for ($i = 0; $i < count($matches['tag']); $i++)
      {
        $index = $matches['prefix'][$i][1];
        $endIndex = FALSE;

        if ($index <= $maxEndIndex)
        {
          continue;
        }

        $prefix = $matches['prefix'][$i][0];
        $name = $matches['tag'][$i][0];
        $nameLen = strlen($prefix) + strlen($name) + 1;
        
        if (!is_array($control) && !property_exists($control, $name))
        {
          # \Nemiro\Console::Warning('Property "%s" not found in the "%s" class.', $name, get_class($control));
          $control->Content = $this->RanderControl($scope, $control->ID, $source);
          continue;
        }

        if (!is_array($control) && !isset($control->$name))
        {
          \Nemiro\Console::Warning('%s->%s can not be NULL.', get_class($control), $name);
          continue;
        }

        # select all before the first triangular brackets (>)
        if (($endOfDeclaration = strpos($masked, '>', $index)) === FALSE)
        {
          \Nemiro\Console::Warning('Invalid declaration format: <%s>.', $name);
          continue;
        }

        # instance
        if (!is_array($control))
        {
          $instance = $control->$name;
          # set id
          $instance->ID = sprintf('%s_%s%s', ($parent != NULL ? $parent->ID : ''), $control->TagName, ($i + 1));
        }
        else
        {
          if (!class_exists($name))
          {
            \Nemiro\Console::Warning('Class %s not found.', $name);
            continue;
          }

          $instance = new $name();
          $control[] = $instance;
          # set id
          $instance->ID = sprintf('%s_%s%s', ($parent != NULL ? $parent->ID : ''), $name, ($i + 1));
        }
        
        # get attributes
        if ($endOfDeclaration - ($index + $nameLen + 1) > 0)
        {
          $attributes = trim(substr($source, $index + $nameLen + 1, $endOfDeclaration - ($index + $nameLen + 1)), ' /');
        }
        else
        {
          $attributes = '';
        }

        # set attributes
        if ($attributes != '')
        {
          $x = new \SimpleXMLElement('<item '.$attributes.' />');
          
          foreach ($x->attributes() as $attrKey => $attrValue)
          {
            if (!isset($instance->$attrKey))
            {
              \Nemiro\Console::Warning('%s->%s->%s can not be NULL.', get_class($control), $name, $attrKey);
              continue;
            }

            if (!property_exists($instance, $attrKey))
            {
              \Nemiro\Console::Warning('Property "%s" not found in the "%s" instance.', $attrKey, $name);
            }

            $instance->$attrKey = $attrValue->__toString();
          }
        }

        # check the penultimate symbol 
        if (substr($source, $endOfDeclaration - 1, 1) != '/')
        {
          # search closed block
          $endIndex = \Nemiro\Text::SearchEndsBlock($masked, $index + 1, '<'.$prefix.':'.$name, '</'.$prefix.':'.$name.'>');
        }

        if ($endIndex !== FALSE)
        {
          $new_source = substr($source, $endOfDeclaration + 1, $endIndex - $endOfDeclaration - 1);
          $new_masked = substr($masked, $endOfDeclaration + 1, $endIndex - $endOfDeclaration - 1);

          # parse childs of child
          if ($this->ParseControlChild($scope, $control, $instance, $new_source, $new_masked, $level + 1) === FALSE)
          {
            if (!is_array($instance) && !property_exists($instance, 'Content'))
            {
              \Nemiro\Console::Warning('Property "%s" not found in the "%s" class.', 'Content', get_class($instance));
            }
            else
            {
              $instance->Content = $this->Execute($new_source);
            }
          }
                    
          # remeber index
          if ($endIndex > $maxEndIndex)
          {
            $maxEndIndex = $endIndex;
          }
        }

        $control->$name = $instance;
      }

      return TRUE;
    }

    /**
     * Optimizes html-code of the page.
     * 
     * @param \string $value The HTML code to optimization.
     * 
     * @return \string
     */
    private function Optimization($value)
    {
      \Nemiro\Console::Info('HTML Optimization. Input: %s Kb.', round(strlen($value) / 1024, 2));
      $value = preg_replace('/\015\012|\015|\012/', PHP_EOL, $value);
      $value = $this->CompressScriptAndStyle($value);
      $value = $this->ClearHTMLComments($value);
      // \r\n in a textarea
      $value = preg_replace_callback
      (
        '#<textarea([^>]*?)>(.*?)</textarea>#si', 
        create_function
        (
          '$matches',
          '
          $z = str_replace(PHP_EOL, "\z1310", $matches[2]);
          return "<textarea".$matches[1].">".$z."</textarea>";
          '
        ), $value
      );
      // --
      $value = preg_replace('/\s+/', ' ', $value); // spaces
      $value = preg_replace('/((?<!\?>)'.PHP_EOL.')[\s]+/m', '\1', $value);
      $value = preg_replace('/\t+/', '', $value);
      // \r\n to textarea
      $value = str_replace('\z1310', '\r\n', $value);
      \Nemiro\Console::Info('HTML Optimization is completed. Output: %s Kb.', round(strlen($value) / 1024, 2));
      return $value;
    }
    
    private function CompressScriptAndStyle($html)
    {
      $compress_scripts = true;
      $compress_css = true;
      $use_script_callback = false;
      $compressed = array();
      $parts = array();

      preg_match_all("!(<(style|script)[^>]*>(?:\\s*<\\!--)?)(.*?)((?://-->\\s*)?</(style|script)>)!is", $html, $scriptparts);

      for ($i=0; $i<count($scriptparts[0]); $i++)
      {
        $code = trim($scriptparts[3][$i]);
        $not_empty = !empty($code);
        $is_script = ($compress_scripts && $scriptparts[2][$i] == 'script');
        if($not_empty && ($is_script || ($compress_css && $scriptparts[2][$i] == 'style')))
        {
          if($is_script && $use_script_callback)
          {
            $callback_args = array();
            if(gettype($callback_args) !== 'array')
            {
              $callback_args = array($callback_args);
            }
            array_unshift($callback_args, $code);
            $minified = call_user_func_array(NULL, $callback_args);
          }
          else
          {
            $minified = $this->HTMLCodeCompress($code);
          }
          array_push($parts, $scriptparts[0][$i]);
          array_push($compressed, trim($scriptparts[1][$i]).$minified.trim($scriptparts[4][$i]));
        }
      }

      return str_replace($parts, $compressed, $html);
    }

    private function HTMLCodeCompress($code)
    {
      $code = preg_replace('/\/\*(?!-)[\x00-\xff]*?\*\//', '', $code);
      $code = preg_replace('/\\/\\/[^\\n\\r]*[\\n\\r]/', '', $code);
      $code = preg_replace('/\\/\\*[^*]*\\*+([^\\/][^*]*\\*+)*\\//', '', $code);
      $code = preg_replace('/\s+/', ' ', $code);
      return preg_replace('/\s?([\{\};\=\(\)\/\+\*-])\s?/', '\\1', $code);
    }
    
    private function ClearHTMLComments($html)
    {
      # conditions for Internet Explorer
      $keep_conditionals = false;

      // check current request browser
      $msie = '|msie\s(.*).*(win)|i';
      $keep_conditionals = (isset($_SERVER['HTTP_USER_AGENT']) && preg_match($msie, $_SERVER['HTTP_USER_AGENT']));

      // remove conditions for Internet Explorer
      if ($keep_conditionals)
      {
        $html = str_replace(array('<!--[if', '<![endif]-->'), 'zIECOND', $html);
      }
      
      // remove comments
      $html = preg_replace('|<!--([^\d]*?)-->|', '', $html); // sape id ignored :)

      // restore conditions for Internet Explorer
      if ($keep_conditionals)
      {
        $html = str_replace('zIECOND', array('<!--[if', '<![endif]-->'), $html);
      }

      return $html;
    }
    # -----------------------------------------

    /**
     * Executes the specified code.
     * 
     * @param \string $code The code to execute.
     * 
     * @return \string
     */
    private function Execute($code)
    {
      ob_start();
      eval('?'.'>'.$code); #.'<'.'?php'
      return ob_get_clean();
    }

    #endregion
    #region public methods

    /**
     * Page pre-load handler.
     */
    public function PreLoad()
    { /* individually for each page */ }

    /**
     * Page loading handler.
     */
    public function Load()
    { /* individually for each page */ }

    /**
     * Page loaded handler.
     */
    public function LoadComplete()
    { /* individually for each page */ }  

    /**
     * Sets keywords to the page meta.
     * 
     * @param \string $value 
     */
    public function SetKeyWords($value)
    {
      $this->Meta['KEYWORDS'] = $value;
    }

    /**
     * Sets decription to the page meta.
     * 
     * @param \string $value 
     */
    public function SetDescription($value)
    {
      $this->Meta['DESCRIPTION'] = $value;
    }

    #endregion

  }

}
?>