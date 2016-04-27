<?php
namespace Nemiro\Nginx\Test
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

  require_once '../Conf.php';

  use Nemiro\Nginx\Conf as NginxConf;

  class ConfTest
  {
    
    function __construct()
    {
      $this->LoadTest();
      $this->SaveTest();
      $this->CreateTest();
    }

    private function LoadTest()
    {
      echo '<h1>LoadTest</h1>';

      $conf = new NginxConf('test.conf');
      $i = 1;

      echo sprintf('Test #%d: Get directive by name … ', $i);
      if ($conf['server'] != NULL)
      {
        $this->Success();
      }
      else
      {
        $this->Fail();
      }
      echo '<br />';

      $i++;
      echo sprintf('Test #%d: Get directive from Directives … ', $i);
      if ($conf['server']->Directives['root']->ParametersAsString() == '/home/example.org/html')
      {
        $this->Success();
      }
      else
      {
        $this->Fail();
        var_dump($conf['server']->Directives['root']->Parameters[0]);
      }
      echo '<br />';

      $i++;
      echo sprintf('Test #%d: ParametersAsString … ', $i);
      if ($conf['server']['server_name']->ParametersAsString() == 'example.org')
      {
        $this->Success();
      }
      else
      {
        $this->Fail();
        var_dump($conf['server']['server_name']->ParametersAsString());
      }
      echo '<br />';

      $i++;
      echo sprintf('Test #%d: Get parameter by index … ', $i);
      if ($conf['server']->Directives['root']->Parameters[0] == '/home/example.org/html')
      {
        $this->Success();
      }
      else
      {
        $this->Fail();
        var_dump($conf['server']->Directives['root']->Parameters[0]);
      }
      echo '<br />';

      $i++;
      echo sprintf('Test #%d: Parameters with spaces by index … ', $i);
      if ($conf['server']['auth_basic']->Parameters[0] == 'Test server')
      {
        $this->Success();
      }
      else
      {
        $this->Fail();
        var_dump($conf['server']['auth_basic']->Parameters);
      }
      echo '<br />';

      $i++;
      echo sprintf('Test #%d: Parameters with spaces via ParametersAsString … ', $i);
      if ($conf['server']['auth_basic']->ParametersAsString() == '"Test server"')
      {
        $this->Success();
      }
      else
      {
        $this->Fail();
        var_dump($conf['server']['auth_basic']->Parameters);
      }
      echo '<br />';

      $i++;
      echo sprintf('Test #%d: Groups … ', $i);
      if ($conf['server']['location']->IsGroup())
      {
        $this->Success();
      }
      else
      {
        $this->Fail();
        var_dump($conf['server']['location']->IsGroup());
      }
      echo '<br />';

      $i++;
      echo sprintf('Test #%d: Get group item #1 … ', $i);
      if ($conf['server']['location'][0]['proxy_pass']->ParametersAsString() == 'http://127.0.0.1:8080')
      {
        $this->Success();
      }
      else
      {
        $this->Fail();
        var_dump($conf['server']['location'][0]);
      }
      echo '<br />';

      $i++;
      echo sprintf('Test #%d: Get group item #2 (explicit) … ', $i);
      if ($conf['server']->Directives['location']->Directives[1]->Directives['expires']->Parameters[0] == 'max')
      {
        $this->Success();
      }
      else
      {
        $this->Fail();
        var_dump($conf['server']['location'][1]);
      }
      echo '<br />';
    
      $i++;
      echo sprintf('Test #%d: FirstChild and ParametersAsString … ', $i);
      if ($conf['server']['location']->FirstChild()->ParametersAsString() == '/')
      {
        $this->Success();
      }
      else
      {
        $this->Fail();
        var_dump($conf['server']['location']->FirstChild());
      }
      echo '<br />';
      
      $i++;
      echo sprintf('Test #%d: LastChild and Parameters by index … ', $i);
      if ($conf['server']->Directives['location']->LastChild()->Parameters[1] == '^(?<page>[\w\d]+)([\.]{1}).ashx$')
      {
        $this->Success();
      }
      else
      {
        $this->Fail();
        var_dump($conf['server']['location']->LastChild());
      }
      echo '<br />';

      $i++;
      echo sprintf('Test #%d: Access to a non-existent directive … ', $i);
      if ($conf['server123'] == NULL)
      {
        $this->Success();
      }
      else
      {
        $this->Fail();
      }
      echo '<br />';

      $i++;
      echo sprintf('Test #%d: Access to a non-existent directive from Directives … ', $i);
      if ($conf->Items['server123'] == NULL)
      {
        $this->Success();
      }
      else
      {
        $this->Fail();
      }
      echo '<br />';
    }

    private function SaveTest()
    {
      echo '<h1>SaveTest</h1>';

      $conf = new NginxConf('test.conf');

      $conf['server']['server_name']->Parameters = array('kbyte.ru', 'www.kbyte.ru', 'forum.kbyte.ru');
      $conf['server']['root']->Parameters = array('/home/kbyte.ru/www');

      $new_location = NginxConf::CreateDirective('location');

      $new_location->AddParameter(array('~*', '\.aspx$'));
      $new_location->AddDirective('index', array('Default.aspx', 'default.aspx'));
      $new_location->AddDirective('proxy_pass', array('http://127.0.0.1:8080'));

      $proxy_set_header = NginxConf::CreateGroup('proxy_set_header');
      $proxy_set_header->AddDirective(array('X-Real-IP', '$remote_addr'));
      $proxy_set_header->AddDirective(array('X-Forwarded-For', '$remote_addr'));
      $proxy_set_header->AddDirective(array('Host', '$host'));
      $proxy_set_header->AddTo($new_location);

      $new_location->AddTo($conf['server']);

      $conf->Save('test-save.conf');

      // read
      $conf = new NginxConf('test-save.conf');

      $i = 1;
      echo sprintf('Test #%d: Simple directives … ', $i);
      if 
      (
        $conf['server']['server_name'][0] == 'kbyte.ru' && 
        $conf['server']['server_name'][1] == 'www.kbyte.ru' && 
        $conf['server']['server_name']->ParametersAsString() == 'kbyte.ru www.kbyte.ru forum.kbyte.ru' &&
        $conf['server']->Directives['root']->ParametersAsString() == '/home/kbyte.ru/www'
      )
      {
        $this->Success();
      }
      else
      {
        $this->Fail();
        var_dump($conf['server']['server_name']);
        var_dump($conf['server']->Directives['root']);
      }
      echo '<br />';

      $i++;
      echo sprintf('Test #%d: Block directives … ', $i);
      if ($conf['server']->Directives['location']->LastChild()->ChildCount() == 3)
      {
        $this->Success();
      }
      else
      {
        $this->Fail();
        var_dump($conf['server']['location']->LastChild());
      }
      echo '<br />';

      // output
      echo '<pre>'.file_get_contents('test-save.conf').'</pre>';

      // remove
      unlink('test-save.conf');
    }

    private function CreateTest()
    {
      echo '<h1>CreateTest</h1>';

      $conf = new NginxConf();
      $conf->Add(NginxConf::CreateDirective('server'));
      $conf['server']->AddDirective('server_name', array('example.org', 'www.example.org'));
      $conf['server']->AddDirective('root', array('/home/example.org/www'));
      $location = NginxConf::CreateDirective('location', '/');
      $location->AddDirective('index', array('index.php', 'index.html', 'index.htm'));
      $location->AddTo($conf['server']);

      // save
      $conf->Save('test-create.conf');

      // read
      $conf = new NginxConf('test-create.conf');

      $i = 1;
      echo sprintf('Test #%d: server_name … ', $i);
      if ($conf['server']->Directives['server_name']->Parameters[0] == 'example.org')
      {
        $this->Success();
      }
      else
      {
        $this->Fail();
        var_dump($conf['server']['server_name']);
      }
      echo '<br />';

      $i++;
      echo sprintf('Test #%d: root … ', $i);
      if ($conf['server']->Directives['root']->Parameters[0] == '/home/example.org/www')
      {
        $this->Success();
      }
      else
      {
        $this->Fail();
        var_dump($conf['server']['root']);
      }
      echo '<br />';

      $i++;
      echo sprintf('Test #%d: location … ', $i);
      if ($conf['server']['location']['index'][0] == 'index.php')
      {
        $this->Success();
      }
      else
      {
        $this->Fail();
        var_dump($conf['server']['location']);
      }
      echo '<br />';

      // output
      echo '<pre>'.file_get_contents('test-create.conf').'</pre>';

      // remove
      unlink('test-create.conf');
    }

    private function Success()
    {
      echo '<span style="color:#008000;">[ Success ]</span>';
    }

    private function Fail()
    {
      echo '<span style="color:red">[ Fail ]</span>';
    }

  }


  new ConfTest();
}