<?php
namespace Nemiro\Apache
{

  /*
   * Copyright © Aleksey Nemiro, 2015. All rights reserved.
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

  require_once 'Directive.php';
  require_once 'DirectiveGroup.php';
  require_once 'DirectiveCollection.php';

  /**
   * Represents Apache configuration.
   * 
   * This class allows to work with Apache configuration files.
   * 
   * @author      Aleksey Nemiro <aleksey@nemiro.ru>
   * @copyright   © Aleksey Nemiro, 2015. All rights reserved.
   * @version     1.0 (2015-10-25) / PHP 5 >= 5.5 / Apache >= 2.4
   * @code
   * ########################################################################
   * # The following example shows how to work with 
   * # the existing configuration files.
   * ########################################################################
   * # include the class file (use own path of the file location)
   * require_once './Nemiro/Apache/Conf.php';
   * 
   * # import class
   * use Nemiro\Apache\Conf as ApacheConf;
   * 
   * # load config
   * $conf = new ApacheConf('/etc/apache2/sites-available/example.org.conf');
   * 
   * # get values
   * var_dump($conf['VirtualHost']);
   * var_dump($conf['VirtualHost']->ParametersAsString());
   * var_dump($conf['VirtualHost']['DocumentRoot']->ParametersAsString());
   * var_dump($conf['VirtualHost']['ServerName']->ParametersAsString());
   * var_dump($conf['VirtualHost']['Alias']);
   * 
   * # directory count
   * var_dump($conf['VirtualHost']->Directives['Directory']->ChildCount());
   * 
   * # get first directory
   * $location = $conf['VirtualHost']['Directory']->FirstChild();
   * var_dump($location);
   * 
   * # set values
   * $conf['VirtualHost']['ServerName']->Parameters = array('example.org', 'www.example.org');
   * $conf['VirtualHost']['DocumentRoot']->Parameters = array('/home/example.org/www');
   * 
   * # create a new directive
   * $new_directory = ApacheConf::CreateDirective('Directory');
   * $new_directory->AddDirective('AllowOverride', 'All');
   * $new_directory->AddDirective('Allow', array('from', 'all'));
   * $new_directory->AddDirective('Require', array('all', 'granted'));
   * 
   * # add the new Directory section to the VirtualHost section
   * $new_directory->AddTo($conf['VirtualHost']);
   * 
   * # save
   * $conf->Save();
   * 
   * # or save as...
   * # $conf->Save('newFileName.conf');
   * @endcode
   * @code
   * ########################################################################
   * # The following example shows how to create a new configuration file.
   * ########################################################################
   * # include the class file (use own path of the file location)
   * require_once './Nemiro/Apache/Conf.php';
   * 
   * # import class
   * use Nemiro\Apache\Conf as ApacheConf;
   * 
   * # create a new file
   * $conf = new ApacheConf();
   * 
   * # create and add VirtualHost section
   * $conf->Add(ApacheConf::CreateDirective('VirtualHost'));
   * # add parameter
   * $conf['VirtualHost']->AddParameter('127.0.0.1:8080');
   * 
   * # add directives to the VirtualHost
   * $conf['VirtualHost']->AddDirective('ServerName', array('example.org', 'www.example.org'));
   * $conf['VirtualHost']->AddDirective('DocumentRoot', '/home/example.org/www');
   * 
   * # create a new Directory section
   * $directory = ApacheConf::CreateDirective('Directory');
   * 
   * # add patameter
   * $directory->AddParameter('/usr/share/phpmyadmin');
   * 
   * # add directives
   * $directory->AddDirective('AllowOverride', 'All');
   * $directory->AddDirective('Allow', array('from', 'all'));
   * $directory->AddDirective('Require', array('all', 'granted'));
   * $directory->AddDirective('DirectoryIndex', array('index.php', 'index.html', 'index.htm'));
   * 
   * # add the Directory to the VirtualHost section
   * $directory->AddTo($conf['VirtualHost']);
   * 
   * # save to file
   * $conf->Save('example.org.conf');
   * @endcode
   */
  class Conf extends DirectiveCollection
  {

    /**
     * The conf file path.
     * 
     * @var \string
     */
    public $Path;

    function __construct($path = NULL)
    {
      parent::__construct();

      $this->Path = $path;
      
      if (($path != NULL && $path != '') || !file_exists($path))
      {
        // get file
        $content = file_get_contents($this->Path);

        // todo: mask
        // remove comments
        $content = preg_replace('/(\#([^\r\n]+))/', '', $content);

        // normalization newline characters
        $content = preg_replace('/[\r\n]+/', "\n", $content);

        // join lines
        $content = preg_replace('/(.+)(\x5C{1})([\n])+/', '\1 ', $content);

        // parse
        $this->ParseString($content);
      }
    }

    #region public methods

    /**
     * Parses a config data from string.
     * 
     * @param \string $source The data to parse.
     * @return void
     */
    public function ParseString($source)
    {
      $this->Parse($this, $source, 0);
    }

    /**
     * Saves the configuration to file.
     * 
     * @param \string $path Path to save. If not specified, will use the file path specified during initialization.
     */
    public function Save($path = NULL)
    {
      if ($path == NULL || $path == '')
      {
        $path = $this->Path;
      }

      if ($path == NULL || $path == '')
      {
        throw new \InvalidArgumentException('Culd not to save file. Path is required, value can not be empty.');
      }

      if (!isset($this->Items) || count($this->Items) == 0)
      {
        throw new \ErrorException('No data to save.');
      }

      $file = fopen($path, 'w+');

      if ($file === FALSE)
      {
        throw new \ErrorException('Culd not to save file.');
      }

      $this->WriteToFile($file, $this, 0);

      // remove last line break
      ftruncate($file, ftell($file) - 1);

      fclose($file);
    }

    /**
     * Returns current instance as string.
     * 
     * @return \string
     */
    public function GetAsString()
    {
      if (!isset($this->Items) || count($this->Items) == 0)
      {
        return '';
      }

      $stream = fopen('php://memory', 'w+');

      if ($stream === FALSE)
      {
        throw new \ErrorException('Culd not convert the instance to string.');
      }

      // write
      $this->WriteToFile($stream, $this, 0);

      // remove last line break
      ftruncate($stream, ftell($stream) - 1);

      // read
      rewind($stream);
      $result = stream_get_contents($stream);

      // close
      fclose($stream);

      return $result;
    }

    #endregion
    #region private methods

    /**
     * Parses a config data.
     * 
     * @param DirectiveCollection $parent An instance of collection in which to place the result of data parsing. 
     * @param \string $source The data to parse.
     * @param \int $level The nesting level. Default: 0.
     * @return void
     */
    private function Parse($parent, $source, $level = 0)
    {
      // masking quotes
      $masked = $this->MaskingQuotes($source);

      // search and each derictives
      preg_match_all('/^(\s*)(?<is_section>\<{0,1})(?<directive>[\w\d\x5F]+)(\s+|\>{1})/im', $masked, $matches, PREG_OFFSET_CAPTURE);

      $index = 0; $maxEndIdx = -1;

      for ($i = 0; $i < count($matches['directive']); $i++)
      {
        $is_section = $matches['is_section'][$i][0] !== '';
        $directive = $matches['directive'][$i];
        $index = $directive[1];

        // data may have already been processed
        if ($index <= $maxEndIdx)
        {
          continue;
        }

        // get name
        $name = trim($directive[0]);
        // get name size
        $len = strlen($name);
        
        if (!$is_section)
        {
          #region is directive

          # search end of line
          if (($endOfLine = strpos($source, "\n", $index)) === FALSE)
          {
            $endOfLine = strlen($source);
          }

          $parameters = trim(substr($source, $index + $len, $endOfLine - ($index + $len)));
          if ($parent->ContainsDirective($name))
          {
            if ($parameters == '')
            {
              // no parameters, ignore it
              continue;
            }
            // is not unique derictive name and not group, transform to group
            if (!$parent->Items[$name]->IsGroup())
            {
              $group = new DirectiveGroup($name);
              $group->AddDirective($parent->Items[$name]->Parameters);
              $group->AddDirective($this->ParseParameters($parameters));
              $parent->Items[$name] = $group;
            }
            else
            {
              // is group
              $parent->Items[$name]->AddDirective($this->ParseParameters($parameters));
            }
          }
          else
          {
            // new derictive
            $parent->Add(new Directive($name, $this->ParseParameters($parameters)));
          }

          #endregion
        }
        else
        {
          #region is section

          // select all before the first triangular brackets (>)
          if (($endOfDeclaration = strpos($source, '>', $index)) === FALSE)
          {
            throw new \ErrorException('Invalid file format.');
          }

          // get parameters
          $parameters = trim(substr($source, $index + $len + 1, $endOfDeclaration - ($index + $len) - 1));
          if ($parameters != '')
          {
            $parameters = $this->ParseParameters($parameters);
          }
          else
          {
            $parameters = NULL;
          }

          // search the closing tag
          $open = '<'.$name;
          $close = '</'.$name.'>';

          if (($endIdx = $this->SearchEndsBlock($masked, $index, $open, $close)) === FALSE)
          {
            continue;
          }

          // is unique derictive name
          if ($parent->ContainsDirective($name))
          {
            // is not unique derictive name and not group, transform
            if (!$parent[$name]->IsGroup())
            {
              $group = new DirectiveGroup($name);
              $group->AddDirective($parent->Items[$name]);
              $group->AddDirective($parameters);
              $parent->Items[$name] = $group;
            }
            else
            {
              // is group, add new element
              $parent->Items[$name]->AddDirective($parameters);
            }
            // get last element
            $new = $parent->Items[$name]->LastChild()->Directives;
          }
          else
          {
            // new derictive
            $new = $parent->Add(new Directive($name, $parameters))->Directives;
          }

          // get block content
          $body = substr($source, $endOfDeclaration + 1, $endIdx - $endOfDeclaration - 1);

          // parse block
          $this->Parse($new, $body, $level + 1);

          // remeber index
          if ($endIdx > $maxEndIdx)
          {
            $maxEndIdx = $endIdx;
          }

          #endregion
        }
      }
    }
    
    /**
     * Parses a string parameter and returns an array.
     * 
     * @param \string $source The string to parse.
     * @return \array
     */
    private function ParseParameters($source)
    {
      $masked = $this->MaskingQuotes($source);
      $result = array();
      $last = 0;

      while(($start = strpos($masked, ' ', $last)) !== FALSE)
      {
        if ($last == $start) 
        {
          $last++;
          continue;
        }
        $result[] = $this->Dequote(substr($source, $last, $start - $last));
        $last = $start + 1;
      }

      if ($last == 0 && $source != '')
      {
        $result[] = $this->Dequote($source);
      }
      else if ($last != strlen($source) && substr($source, $last) != '')
      {
        $result[] = $this->Dequote(substr($source, $last));
      }

      return $result;
    }

    /**
     * Removes quotes left and right. 
     * 
     * @param \string $value The text for processing.
     * @return \string
     */
    private function Dequote($value)
    {
      if ($value == NULL || strlen($value) == 0)
      {
        return '';
      }

      return preg_replace('/^([\"\']{1})(.*)\1$/', '\2', $value);
    }

    /**
     * Masking a text in quotes.
     * 
     * @param \string $source The text to parse.
     */
    private function MaskingQuotes($source)
    {
      $maxEndIdx = -1;
      preg_match_all('/[\"\']{1}/m', $source, $matches, PREG_OFFSET_CAPTURE);

      foreach ($matches[0] as $m)
      {
        $start = $m[1];

        // data may have already been processed
        if ($start <= $maxEndIdx)
        {
          continue;
        }

        // search closed quotes
        if (($end = $this->SearchEndsBlock($source, $start + 1, $m[0], $m[0], '\\')) === FALSE)
        {
          continue;
        }

        // masking (chr(2) - any unused char)
        $source = substr_replace($source, str_repeat(chr(2), $end - $start + 1), $start, $end - $start + 1);

        // remeber index
        if ($end > $maxEndIdx)
        {
          $maxEndIdx = $end;
        }
      }

      return $source;
    }

    /**
     * Searchs end block.
     * 
     * @param \string $source The source.
     * @param \int $start The start position.
     * @param \string $open The string that indicates the beginning of the block.
     * @param \string $close The string that indicates the ending of the block.
     * @param \string $escape Set the escape character. Defaults as a backslash (\).
     * @return \string
     */
    private function SearchEndsBlock($value, $start, $open, $close, $escape = '\\')
    {
      // search from the beginning of the end of the block parent
      $endIdx = $this->SearchChar($value, $close, $start, $escape);

      // search for the next open-block from the start of the parent block
      $nextOpenIndex = $this->SearchChar($value, $open, $start, $escape);

      $openingCount = 0;

      while (($nextOpenIndex !== FALSE && $nextOpenIndex < $endIdx) || $openingCount > 0)
      {
        // select all from the start of parent to the end found
        $part = substr($value, $start, $endIdx - $start);
        // counting the number of blocks
        $openingCount += substr_count($part, $open);
        // closed block is the latest open
        $openingCount--;
        // search the next closed block from the current closed
        $start = $endIdx + strlen($close);
        if ($this->SearchChar($value, $close, $start, $escape) === FALSE)
        {
          break;
        }
        // search the next open block from the closed
        $endIdx = $this->SearchChar($value, $close, $start, $escape);
      }

      return $endIdx;
    }
    
    /**
     * Searches are not escapes in the specified string.
     * 
     * @param \string $value 
     * @param \string $search 
     * @param \int $start 
     * @param \string $escape
     * @return \int|\FALSE
     */
    private function SearchChar($value, $search, $start, $escape = '\\')
    {
      $result = FALSE;

      while (($result = strpos($value, $search, $start)) !== FALSE)
      {
        // check prev char
        if (($prevChar = substr($value, $start - 1 - strlen($escape), 1)) === FALSE || $prevChar != $escape)
        {
          break;
        }
      }

      return $result;
    }

    /**
     * Writes a directives to file.
     * 
     * @param \resource $file A file system pointer resource that is typically created using fopen().
     * @param DirectiveCollection $directives Directives to save.
     * @param \int $level Directive nesting level (it affects the number of indents).
     * @return void
     */
    private function WriteToFile($file, $directives, $level, $groupName = NULL)
    {
      if(!isset($directives) || count($directives) == 0)
      {
        return;
      }

      $last_is_section = FALSE;
      $i = 0; $count = $directives->Count();
      foreach ($directives->Items as $directive)
      {
        $name = ($groupName == NULL ? $directive->Name : $groupName);

        // is group
        if ($directive->IsGroup() && $directive->HasChild())
        {
          // additional line break
          if (!$last_is_section && !$directive->IsDirective())
          {
            fwrite($file, "\n");
          }
          // write array
          $this->WriteToFile($file, $directive->Directives, $level, $directive->Name);
          continue;
        }

        // write indents
        fwrite($file, str_repeat("\t", $level));

        // check for parameters or children
        if (!$directive->HasParameters() && !$directive->HasChild())
        {
          // is empty, skip it
          continue;
        }

        // write directive body
        if ($directive->HasChild())
        {
          // open section
          fwrite($file, "<$name");
          // write parameters
          if ($directive->HasParameters())
          {
            fwrite($file, " ");
            fwrite($file, $directive->ParametersAsString());
          }
          fwrite($file, ">\n");
          // write childs
          $this->WriteToFile($file, $directive->Directives, $level + 1);
          // write indents
          fwrite($file, str_repeat("\t", $level));
          // close section
          fwrite($file, "</$name>\n");
          $last_is_section = TRUE;
          // additional line break
          if ($i < $count - 1)
          {
            fwrite($file, "\n");
          }
        }
        else
        {
          // write directive name
          fwrite($file, "$name ");
          // write parameters
          fwrite($file, $directive->ParametersAsString());
          // line break
          fwrite($file, "\n");
          // is not section
          $last_is_section = FALSE;
        }

        $i++;
      }
    }
    
    #endregion
    #region static static methods

    /**
     * Creates a new directive or section.
     * 
     * @param \string $name The element name.
     * @param \string|\string[] $parameters The list of parameters. Default: NULL.
     * @return Directive
     */
    public static function CreateDirective($name, $parameters = NULL)
    {
      return new Directive($name, $parameters);
    }
    
    /**
     * Creates a new group.
     * 
     * @param \string $name The group name.
     * @return DirectiveGroup
     */
    public static function CreateGroup($name)
    {
      return new DirectiveGroup($name);
    }

    /**
     * Creates a new instance of the Conf class from file path.
     * 
     * @param \string $path The path to config file.
     * @return Conf
     */
    public static function CreateFromFile($path)
    {
      return new Conf($path);
    }

    /**
     * Creates a new instance of the Conf class from string value.
     * 
     * @param \string $content The config file data.
     * @return Conf
     */
    public static function CreateFromString($content)
    {
      $result = new Conf();
      $result->ParseString($content);
      return $result;
    }

    /**
     * Creates a new instance of the Conf class.
     * 
     * @return Conf
     */
    public static function Create()
    {
      return new Conf();
    }

    #endregion
    
  }

}