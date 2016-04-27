### NginxConf.PHP [![Latest Stable Version](https://poser.pugx.org/aleksey.nemiro/nginxconf.php/v/stable)](https://packagist.org/packages/aleksey.nemiro/nginxconf.php) [![Total Downloads](https://poser.pugx.org/aleksey.nemiro/nginxconf.php/downloads)](https://packagist.org/packages/aleksey.nemiro/nginxconf.php) [![License](https://poser.pugx.org/aleksey.nemiro/nginxconf.php/license)](https://packagist.org/packages/aleksey.nemiro/nginxconf.php)

Classes for working with configuration files of the **Nginx** web server.

Code licensed under **Apache License Version 2.0**.

### Requirements

* PHP5 >= 5.5, PHP7;
* Nginx >= 1.9.

**NOTE:** Working with the earlier versions were not tested, but it is possible that everything is working.

### How to use

Include **Conf.php** file and import the class ``Nemiro\Nginx\Conf``.

```PHP
# include the class file (use own path of the file location)
require_once 'Nginx/Conf.php';

# import class
use Nemiro\Nginx\Conf as NginxConf;
```

#### Load config from file

```PHP
# create instance and load config from file
$conf = new NginxConf('/etc/nginx/sites-available/example.org.conf');
# or
# $conf = NginxConf::CreateFromFile('/etc/nginx/sites-available/example.org.conf');

# get values
var_dump($conf['server']);

if ($conf['server']->ContainsChild('listen'))
{
  print_r($conf['server']['listen']->ParametersAsString());
}

var_dump($conf['server']['server_name']->ParametersAsString());
var_dump($conf['server']['root']->ParametersAsString());
var_dump($conf['server']['location']);
```

#### Load config from string

```PHP
# config data
$str = 'server {
  # server name
  server_name            example.org;
  root                   /home/example.org/html; # root path
  auth_basic             "Test server";
  auth_basic_user_file   /home/example.org/.htpasswd;

  # location #1
  location / {
    proxy_set_header X-Real-IP $remote_addr;
    proxy_set_header X-Forwarded-For  $remote_addr;
    proxy_set_header Host $host;
    proxy_pass http://127.0.0.1:8080;
  }

  # location #2
  location ~* \.(js|css|png|jpg|jpeg|gif|ico)$ {
    expires max;
    log_not_found off;
  }

}';

# parse string
$conf = NginxConf::CreateFromString($str);

# get values
var_dump($conf['server']);
var_dump($conf['server']['server_name']->ParametersAsString());
var_dump($conf['server']['root']->ParametersAsString());

# get first location
$location = $conf['server']['location'][0];
var_dump($location);

# get second location
$location2 = $conf['server']['location'][1];
var_dump($location2);
```

#### Save to file

```PHP
# load from file
$conf = NginxConf::CreateFromFile('/etc/nginx/sites-available/example.org.conf');

# set values
$conf['server']['server_name']->Parameters = array('example.org', 'www.example.org');
$conf['server']['root']->Parameters = array('/home/example.org/www');

# create a new directive
$new_location = NginxConf::CreateDirective('location');

# single name directives
$new_location->AddDirective('index', array('Default.aspx', 'default.aspx'));
$new_location->AddDirective('proxy_pass', array('http://127.0.0.1:8080'));

# directives with same name (group)
$proxy_set_header = NginxConf::CreateGroup('proxy_set_header');
$proxy_set_header->AddDirective(array('X-Real-IP', '$remote_addr'));
$proxy_set_header->AddDirective(array('X-Forwarded-For', '$remote_addr'));
$proxy_set_header->AddDirective(array('Host', '$host'));

# add the proxy_set_header to the new location
$proxy_set_header->AddTo($new_location);

# add the new location to the server directive
$new_location->AddTo($conf['server']);

# save
$conf->Save();

# or save as...
# $conf->Save('newFileName.conf');
```

#### Get string from current instance

```PHP
# load from file
$conf = new NginxConf::CreateFromFile('/etc/nginx/sites-available/example.org.conf');

# set values
$conf['server']['server_name']->Parameters = array('example.org', 'www.example.org');
$conf['server']['root']->Parameters = array('/home/example.org/www');

# create a new directive
$new_location = NginxConf::CreateDirective('location');

# single name directives
$new_location->AddDirective('index', array('Default.aspx', 'default.aspx'));
$new_location->AddDirective('proxy_pass', array('http://127.0.0.1:8080'));

# directives with same name (group)
$proxy_set_header = NginxConf::CreateGroup('proxy_set_header');
$proxy_set_header->AddDirective(array('X-Real-IP', '$remote_addr'));
$proxy_set_header->AddDirective(array('X-Forwarded-For', '$remote_addr'));
$proxy_set_header->AddDirective(array('Host', '$host'));

# add the proxy_set_header to the new location
$proxy_set_header->AddTo($new_location);

# add the new location to the server directive
$new_location->AddTo($conf['server']);

# get as string
$string = $conf->GetAsString();

# show string
var_dump($string);
```

#### Create a new config

```PHP
# create an instance
$conf = new NginxConf();

# create and add server directive
$conf->Add(NginxConf::CreateDirective('server'));

# add directives to server directive
$conf['server']->AddDirective('server_name', array('example.org', 'www.example.org'));
$conf['server']->AddDirective('root', array('/home/example.org/www'));

# create a new location directive
$location = NginxConf::CreateDirective('location');

# add sub-directives
$location->AddDirective('index', array('index.php', 'index.html', 'index.htm'));

# add the location to the server directive
$location->AddTo($conf['server']);

# save
$conf->Save('example.org.conf');

# get as string
$string = $conf->GetAsString();

# show string
var_dump($string);

# or save
# $conf->Save('newFileName.conf');
```