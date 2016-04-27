<?php
namespace Nemiro
{

  /*
   * Copyright © Aleksey Nemiro, 2008-2009, 2016. All rights reserved.
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
   * The helper class for working with strings.
   * 
   * @author     Aleksey Nemiro <aleksey@nemiro.ru>
   * @copyright  © Aleksey Nemiro, 2008-2009, 2016. All rights reserved.
   */
  class Text
  {

    /**
     * Removes BOM from the string.
     * 
     * @param \string $value The string for processing.
     * 
     * @return \string
     */
    public static function ClearUTF8BOM($value)
    {
      return str_replace("\xEF\xBB\xBF", '', $value);
    }

    /**
     * Determines whether the beginning of a string matches with a specified string.
     * 
     * @param \string $str The string to check.
     * @param \string $search The string to compare. 
     * 
     * @return \bool
     */
    public static function StartsWith($str, $comp) {
      $str = \Nemiro\Text::ClearUTF8BOM($str);
      return $comp === '' || strrpos($str, $comp, -strlen($str)) !== FALSE;
    }

    /**
     * Determines whether the end of a string matches with a specified string.
     * 
     * @param \string $str The string to check.
     * @param \string $comp The string to compare. 
     * 
     * @return \bool
     */
    public static function EndsWith($str, $comp) {
      $str = \Nemiro\Text::ClearUTF8BOM($str);
      return $comp === '' || (($temp = strlen($str) - strlen($comp)) >= 0 && strpos($str, $comp, $temp) !== FALSE);
    }

    /**
     * Adds the spaces to a long words.
     * 
     * @param \string $value The string to processing.
     * @param \int $n The maximum length of a word. Default: 15 chars.
     * 
     * @return \string
     */
    static function SplitText($value, $n = 15)
    {
      $r = ''; $ic = strlen($value);
      if ($ic <= $n) return $value;
      for ($i = 0; $i <= $ic; $i += $n)
      {
        $ie = ($i + $n > $ic ? $ic - $i : $n);
        $r .= substr($value, $i, $ie);
        if (substr($value, $i, $ie) >= $n && !strpos(substr($value, $i, $ie), ' '))
        {
          $r .= ' ';
        }
      }
      return trim($r);
    }

    /**
     * Encodes a string to be displayed in a browser.
     * 
     * @param \string $value The text string to encode.
     * 
     * @return \string
     */
    static function HtmlEncode($value, $encoding = 'UTF-8')
    {
      return htmlspecialchars($value, ENT_NOQUOTES, $encoding); // ENT_COMPAT
    }

    /**
     * Encodes a string to be displayed in a browser.
     * In contrast to the HtmlEncode, the method also encodes the double quotes.
     * 
     * @param \string $value The text string to encode.
     * 
     * @return \string
     */
    static function QHtmlEncode($value, $encoding = 'UTF-8')
    {
      return htmlspecialchars($value, ENT_COMPAT, $encoding); // преобразуются двойные кавычки, одиночные остаются без изменений
    }  

    /**
     * Removes slashes from the specified text.
     * 
     * @param \string $value The string to processing.
     * 
     * @return \array|\string
     */
    static function ClearSlash($value)
    {
      if (get_magic_quotes_gpc())
      {
        //return str_replace("\\'", "'", str_replace("\\\"", "\"", $str));
        return is_array($value) ? array_map('DeleteSlash', $value) : stripslashes($value);
      }
      else
      {
        return $value;
      }
    }

    /**
     * Closes all open html tag in the specified line.
     * 
     * @param \string $html HTML text for processing.
     * 
     * @return \string
     */
    static function CloseAllTags($html)
    {
      // search tags
      preg_match_all('|<([a-z]+)( .*)?(?!/)>|iU', $html, $result);
      $openedTags = $result[1];

      preg_match_all('|</([a-z]+)>|iU', $html, $result);
      $closedTags = $result[1];

      $openedCount = count($openedTags);

      // all tags is closed
      if (count($closedTags) == $openedCount) return $html;
      
      $openedTags = array_reverse($openedTags);

      // close tags
      for ($i = 0; $i < $openedCount; $i++)
      {
        if ($openedTags[$i] != 'hr' && $openedTags[$i] != 'br' && $openedTags[$i] != 'img')
        {
          if (!in_array($openedTags[$i], $closedTags))
          {
            $html .= '</'.$openedTags[$i].'>';
          }
          else
          {
            unset($closedTags[array_search($openedTags[$i], $closedTags)]);
          }
        }
      }

      return $html;
    }

    /**
     * Parses BB-tags.
     * 
     * @param \string $str Text to parse.
     * @param \bool $clearBB Indicates the need to remove all the BB tags. Default: false.
     * 
     * @return \string
     */
    static function ConvertBB($str, $clearBB = false)
    {
      if ($clearBB)
      {  // text plain
        $result = $str;
        $result = str_ireplace("[q]", "* QUOTE:\r\n", $result);
        $result = str_ireplace("[/q]", "\r\n--------------------------\r\n", $result);
        $result = str_ireplace("[quote]", "* QUOTE:\r\n", $result);
        $result = str_ireplace("[/quote]", "\r\n--------------------------\r\n", $result);
        $result = str_ireplace("[c]", "* CODE:\r\n", $result);
        $result = str_ireplace("[/c]", "\r\n--------------------------\r\n", $result);
        $result = str_ireplace("[code]", "* CODE:\r\n", $result);
        $result = str_ireplace("[/code]", "\r\n--------------------------\r\n", $result);
        $result = str_ireplace("[b]", "", $result);
        $result = str_ireplace("[/b]", "", $result);
        $result = str_ireplace("[i]", "", $result);
        $result = str_ireplace("[/i]", "", $result);
        $result = str_ireplace("[u]", "", $result);
        $result = str_ireplace("[/u]", "", $result);
        $result = str_ireplace("[red]", "", $result);
        $result = str_ireplace("[/red]", "", $result);
        $result = str_ireplace("[green]", "", $result);
        $result = str_ireplace("[/green]", "", $result);
        $result = str_ireplace("[blue]", "", $result);
        $result = str_ireplace("[/blue]", "", $result);
        $result = preg_replace("':([\w\d-\_]+):'si", "[$1]", $result);
        return $result;
      }
      
      // bb-code
      $result = Text::HtmlEncode($str);
      // links
      $search = array
      (
        "'(.)((http|https|ftp)://[\w\d-]+\.[\w\d-]+[^\s<\"\']*[^.,;\s<\"\'\)]+)'si",
        "'([^/])(www\.[\w\d-]+\.[\w\d-]+[^\s<\"\']*[^.,;\s<\"\'\)]+)'si",
        "'([^\w\d-\.])([\w\d-\.]+@[\w\d-\.]+\.[\w]+[^.,;\s<\"\'\)]+)'si"
      );

      $replace = array
      (
        '$1<noindex><a href="$2" target="_blank" rel="external nofollow">$2</a></noindex>',
        '$1<noindex><a href="http://$2" target="_blank" rel="external nofollow">$2</a></noindex>',
        '$1<noindex><a href="mailto:$2" rel="external nofollow">$2</a></noindex>'
      );

      $result = preg_replace($search, $replace, $result);
      
      // other tags
      $result = str_ireplace("[q]", "<div class=\"q\"><q>", $result);
      $result = str_ireplace("[/q]", "</q></div>", $result);

      $result = str_ireplace("[quote]", "<q>", $result);
      $result = str_ireplace("[/quote]", "</q>", $result);

      $result = str_ireplace("[b]", "<strong>", $result);
      $result = str_ireplace("[/b]", "</strong>", $result);

      $result = str_ireplace("[i]", "<em>", $result);
      $result = str_ireplace("[/i]", "</em>", $result);
      
      $result = str_ireplace("[u]", "<span style=\"text-decoration:underline\">", $result);
      $result = str_ireplace("[/u]", "</span>", $result);
      
      $result = str_ireplace("[red]", "<span style=\"color:red\">", $result);
      $result = str_ireplace("[/red]", "</span>", $result);
      
      $result = str_ireplace("[green]", "<span style=\"color:green\">", $result);
      $result = str_ireplace("[/green]", "</span>", $result);

      $result = str_ireplace("[blue]", "<span style=\"color:blue\">", $result);
      $result = str_ireplace("[/blue]", "</span>", $result);
      
      $result = str_replace("\r\n", "\n", $result);
      $result = str_replace("\n", "<br />", $result);

      // code
      /*$result = str_ireplace("[c]", "<div class=\"codeBlock\">", $result);
      $result = str_ireplace("[/c]", "</div>", $result);
      
      $result = str_ireplace("[code]", "<div class=\"codeBlock\">", $result);
      $result = str_ireplace("[/code]", "</div>", $result);*/
      preg_match_all("#\[(c|code)\](.*?)\[/\\1\]#si", $result, $matchs);
      if ($matchs != NULL && count($matchs) >= 2)
      {
        foreach ($matchs[2] as $v)
        {
          $result = preg_replace("#\[(c|code)\](".preg_quote($v, '#').")\[/\\1\]#si", $v, $result); // GetCode(str_replace("<br />", "\n", $v))
        }
      }
      #echo str_replace("\r\n", "<br />", $result);
      
      // smiles
      $result = preg_replace("':([\w\d-\_]+):'si", "<img src='/images/smiles/$1.gif' alt='$1' title='$1' style='border:none;' />", $result);
      
      // close all tags
      $result = Text::CloseAllTags($result);

      return $result;
    }

    /**
     * Validates email addresses.
     * 
     * @param \string $email The string to test.
     * 
     * @return \bool
     */
    static function IsValidEmail($email)
    {
      return preg_match("/^([0-9a-z]([-_.]?[0-9a-z])*@[0-9a-z]([-.]?[0-9a-z])*\\.[a-wyz][a-z](fo|g|l|m|mes|o|op|pa|ro|seum|t|u|v|z)?)$/i", strtolower($email)) === 1;
    }

    /**
     * Возвращает строку на транслите.
     * 
     * @param \string $value Строка, которую следует преобразовать в транслит.
     * 
     * @return \string
     */
    static function TranslateString($value)
    {
      $alpha = array
      (
        'й' => 'i', 'ц' => 'c', 'у' => 'u', 'к' => 'k', 'е' => 'e', 'н' => 'n', 'г' => 'g', 'ш' => 'sh', 'щ' => 'sch',
        'з' => 'z', 'х' => 'kh', 'ъ' => '', 'ф' => 'f', 'ы' => 'u', 'в' => 'v', 'а' => 'a', 'п' => 'p', 'р' => 'r', 
        'о' => 'o', 'л' => 'l', 'д' => 'd', 'ж' => 'j', 'э' => 'e', 'я' => 'ya', 'ч' => 'ch', 'с' => 's', 'м' => 'm', 
        'и' => 'i', 'т' => 't', 'ь' => '', 'б' => 'b', 'ю' => 'u', 'ё' => 'e', 'Й' => 'I', 'Ц' => 'C', 'У' => 'U', 
        'К' => 'K', 'Е' => 'E', 'Н' => 'N', 'Г' => 'G', 'Ш' => 'Sh', 'Щ' => 'Sch', 'З' => 'Z', 'Х' => 'Kh', 'Ъ' => '', 
        'Ф' => 'F', 'Ы' => 'U', 'В' => 'V', 'А' => 'A', 'П' => 'P', 'Р' => 'R', 'О' => 'O', 'Л' => 'L', 'Д' => 'D', 
        'Ж' => 'J', 'Э' => 'E', 'Я' => 'Ya', 'Ч' => 'Ch', 'С' => 'S', 'М' => 'M', 'И' => 'I', 'Т' => 'T', 'Ь' => '', 
        'Б' => 'B', 'Ю' => 'U', 'Ё' => 'E'
      );

      $result = '';

      for ($i = 0; $i <= strlen($value); $i++)
      {
        if ($alpha[$value[$i]] != NULL)
        {
          $result .= $alpha[$value[$i]];
        }
        else
        {
          $result .= $value[$i];
        }
      }

      return $result;
    }

    /**
     * Masking a text in quotes.
     * 
     * @param \string $source The text to parse.
     */
    public static function MaskingQuotes($source)
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
        if (($end = Text::SearchEndsBlock($source, $start + 1, $m[0], $m[0], '\\')) === FALSE)
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
     * 
     * @return \string
     */
    public static function SearchEndsBlock($value, $start, $open, $close, $escape = '\\')
    {
      // search from the beginning of the end of the block parent
      $endIdx = Text::SearchChar($value, $close, $start, $escape);

      // search for the next open-block from the start of the parent block
      $nextOpenIndex = Text::SearchChar($value, $open, $start, $escape);

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
        if (Text::SearchChar($value, $close, $start, $escape) === FALSE)
        {
          break;
        }
        // search the next open block from the closed
        $endIdx = Text::SearchChar($value, $close, $start, $escape);
      }

      return $endIdx;
    }
    
    /**
     * Searches are not escapes in the specified string.
     * 
     * @param \string $value The string to search in.
     * @param \string $search The needed string.
     * @param \int $start The index of the char from which to start the search.
     * @param \string $escape Escape character. Default \.
     * 
     * @return \int|\FALSE
     */
    public static function SearchChar($value, $search, $start, $escape = '\\')
    {
      $result = FALSE;

      while (($result = strpos($value, $search, $start)) !== FALSE)
      {
        // check prev char
        if (($prevChar = substr($value, $start - 1 - strlen($escape), 1)) === FALSE || $prevChar != $escape)
        {
          break;
        }
        $start++;
      }

      return $result;
    }

    /**
     * Escapes specified characters in the specified string.
     * 
     * @param \string $value The string to proccessing.
     * @param \string[] $chars The chars to escape. Default quote (").
     * 
     * @return \string
     */
    public static function EscapeString($value, $chars = ['"'])
    {
      if (!isset($chars) || !is_array($chars))
      { 
        $chars = ['"'];
      }

      return preg_replace('/(['.preg_quote(implode('', $chars)).']+)/', '\\\\\1', $value);
    }

    /**
     * Removes specified characters from the specified string.
     * 
     * @param \string $value The string to proccessing.
     * @param \string[] $chars The chars to remove.
     * 
     * @return \string
     */
    public static function RemoveChars($value, $chars)
    {
      if (!isset($chars) || !is_array($chars))
      { 
        throw new \ErrorException('Char array is required.');
      }

      return preg_replace('/(['.preg_quote(implode('', $chars)).']+)/', '', $value);
    }

  }

}
?>