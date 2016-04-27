<?php
namespace Nemiro
{

  /*
   * Copyright © Aleksey Nemiro, 2007. All rights reserved.
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
   * Represents server tools.
   * 
   * @author     Aleksey Nemiro <aleksey@nemiro.ru>
   * @copyright  © Aleksey Nemiro, 2007. All rights reserved.
   */
  class Server
  {
    
    /**
     * Gets the collection of HTTP query string variables.
     * 
     * @var \array()
     */
    public static $QueryString = array();
    
    /**
     * Gets a collection of form variables.
     * 
     * @var \array()
     */
    public static $Form = array();

    /**
     * Gets information about the URL of the current request.
     * 
     * @var \array()
     */
    public static $Url = '';

    /**
     * Gets information about the URL of the client's previous request that linked to the current URL.
     * 
     * @var \string
     */
    public static $UrlReferrer = '';

    /**
     * Gets the IP host address of the remote client.
     * 
     * @var \string
     */
    public static $UserHostAddress = '';

    function __construct()
    {
      Server::$UserHostAddress = $_SERVER['REMOTE_ADDR'];
      Server::$UrlReferrer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : NULL;
      Server::$Url = parse_url($_SERVER['REQUEST_URI']);
      Server::$QueryString = $_GET;
      Server::$Form = $_POST;
    }

    /**
     * Returns TRUE, if the callback was.
     * 
     * @return \bool
     */
    public static function IsPostBack()
    {
      return $_SERVER['REQUEST_METHOD'] === 'POST';
    }
    
    /**
     * Converts relative URL to absolute.
     * 
     * @param \string $relativeUrl 
     * 
     * @return \string
     */
    public static function ResolveUrl($relativeUrl) 
    {
      $correntServer = $_SERVER['SERVER_NAME'];
      $currentPage = $_SERVER['PHP_SELF'];
      $scheme = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https:' : 'http:');

      if ($relativeUrl == NULL || $relativeUrl === '') 
      {
        // если указана пустая строка, возвращаем текущий путь
        return $scheme.'//'.$correntServer.$currentPage;
      } 
      else 
      {
        // если указан путь ~/, то превращаем его в нормальный
        $url = parse_url(substr($relativeUrl, 1, strlen($relativeUrl)));
        return $scheme.'//'.$correntServer.$url['path'];
      }
    }

    /**
     * Returns the physical file path that corresponds to the specified virtual path.
     * 
     * @param \string $path The virtual path in the application. 
     * 
     * @return \string
     */
    public static function MapPath($path)
    {
      $root = defined('MAIN_PATH') ? MAIN_PATH : $_SERVER['DOCUMENT_ROOT'];
      if ($path == NULL || $path === '') 
      {
        return $root;
      }
      
      // $path = str_replace('\\', '/', $path);

      if (substr($path, 0, 1) == '~')
      {
        $path = $root.substr($path, 1, strlen($path) - 1);
      }

      return \Nemiro\Server::NormalizePathSeparators($path);
    }

    private static function NormalizePathSeparators($path)
    {
      return str_replace(array('/', '\\'), (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN' ? '\\' : '/'), $path);
    }

    /**
     * Redirects to another address.
     * 
     * @param \string $url Address to redirect.
     * @param \int $code HTTP Status code. Default: 302 - Moved Temporary.
     */
    public static function Redirect($url, $code = 302)
    {
      if ($code != NULL || $code === 302)
      {
        header('HTTP/1.1 302 Moved Temporary');
      }
      else
      {
        header('HTTP/1.1 301 Moved Permanently');
      }
      header('Location: '.$url);
    }

  }

}
?>