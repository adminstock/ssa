### ApacheConf.PHP [![Latest Stable Version](https://poser.pugx.org/aleksey.nemiro/apacheconf.php/v/stable)](https://packagist.org/packages/aleksey.nemiro/apacheconf.php) [![Total Downloads](https://poser.pugx.org/aleksey.nemiro/apacheconf.php/downloads)](https://packagist.org/packages/aleksey.nemiro/apacheconf.php) [![License](https://poser.pugx.org/aleksey.nemiro/apacheconf.php/license)](https://packagist.org/packages/aleksey.nemiro/apacheconf.php)

This is a set of classes for working with configuration files of the **Apache2** web server.

Code licensed under **Apache License Version 2.0**.

### Requirements

* PHP5 >= 5.5, PHP7;
* Apache2 >= 2.4.

**NOTE:** Working with the earlier versions were not tested, but it is possible that everything is working.

### How to use?

Include **Conf.php** file and import the class ``Nemiro\Apache\Conf``.

```PHP
# include the class file (use own path of the file location)
require_once 'Apache/Conf.php';

# import class
use Nemiro\Apache\Conf as ApacheConf;
```

#### Load config from file

```PHP
# create instance and load config from file
$conf = new ApacheConf('/etc/apache2/sites-available/example.org.conf');
# or
# $conf = ApacheConf::CreateFromFile('/etc/apache2/sites-available/example.org.conf');

# get values
var_dump($conf['VirtualHost']);
var_dump($conf['VirtualHost']->ParametersAsString());
var_dump($conf['VirtualHost']['DocumentRoot']->ParametersAsString());
var_dump($conf['VirtualHost']['ServerName']->ParametersAsString());
var_dump($conf['VirtualHost']['Alias']);
```

#### Load config from string

```PHP
# config data
$str = '<VirtualHost 127.0.0.1:80>
	DocumentRoot /home/example.org/www
  ServerName example.org

  <Location />
	  AuthType Basic
		AuthUserFile users.pwd
		Require valid-user
	</Location>
</VirtualHost>';

# parse string
$conf = ApacheConf::CreateFromString($str);

# get values
var_dump($conf['VirtualHost']);
var_dump($conf['VirtualHost']->ParametersAsString());
var_dump($conf['VirtualHost']['ServerName']->ParametersAsString());

# get location
$location = $conf['VirtualHost']['Location'][0];
var_dump($location);
```

#### Save to file

```PHP
# load from file
$conf = ApacheConf::CreateFromFile('/etc/apache2/sites-available/example.org.conf');

# set values
$conf['VirtualHost']['ServerName']->Parameters = array('example.org', 'www.example.org');
$conf['VirtualHost']['DocumentRoot']->Parameters = array('/home/example.org/www');

# create a new directive
$new_directory = ApacheConf::CreateDirective('Directory');
$new_directory->AddDirective('AllowOverride', 'All');
$new_directory->AddDirective('Allow', array('from', 'all'));
$new_directory->AddDirective('Require', array('all', 'granted'));

# add the new Directory section to the VirtualHost section
$new_directory->AddTo($conf['VirtualHost']);

# save
$conf->Save();

# or save as...
# $conf->Save('newFileName.conf');
```

#### Get string from current instance

```PHP
# load from file
$conf = new ApacheConf::CreateFromFile('/etc/apache2/sites-available/example.org.conf');

# set values
$conf['VirtualHost']['ServerName']->Parameters = array('example.org', 'www.example.org');
$conf['VirtualHost']['DocumentRoot']->Parameters = array('/home/example.org/www');

# create a new directive
$new_directory = ApacheConf::CreateDirective('Directory');
$new_directory->AddDirective('AllowOverride', 'All');
$new_directory->AddDirective('Allow', array('from', 'all'));
$new_directory->AddDirective('Require', array('all', 'granted'));

# add the new Directory section to the VirtualHost section
$new_directory->AddTo($conf['VirtualHost']);

# get as string
$string = $conf->GetAsString();

# show string
var_dump($string);
```

#### Create a new config

```PHP
# create an instance
$conf = new ApacheConf();

# create VirtualHost
$virtualHost = ApacheConf::CreateDirective('VirtualHost', '192.168.100.39:8080');
$virtualHost->AddDirective('DocumentRoot', '/home/example.org/www');
$virtualHost->AddDirective('ServerName', 'example.org');

# add to conf
$conf->Add($virtualHost);

# create directory
$directory = ApacheConf::CreateDirective('Directory');
$directory->AddDirective('AllowOverride', 'All');
$directory->AddDirective('Allow', array('from', 'all'));
$directory->AddDirective('Require', array('all', 'granted'));

# add the new Directory section to the VirtualHost section
$directory->AddTo($virtualHost);

# get as string
$string = $conf->GetAsString();

# show string
var_dump($string);

# or save
# $conf->Save('newFileName.conf');
```