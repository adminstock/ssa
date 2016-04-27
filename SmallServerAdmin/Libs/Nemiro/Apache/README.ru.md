### ApacheConf.PHP [![Latest Stable Version](https://poser.pugx.org/aleksey.nemiro/apacheconf.php/v/stable)](https://packagist.org/packages/aleksey.nemiro/apacheconf.php) [![Total Downloads](https://poser.pugx.org/aleksey.nemiro/apacheconf.php/downloads)](https://packagist.org/packages/aleksey.nemiro/apacheconf.php) [![License](https://poser.pugx.org/aleksey.nemiro/apacheconf.php/license)](https://packagist.org/packages/aleksey.nemiro/apacheconf.php)

**ApacheConf.PHP** - небольшой набор классов для работы с файлами конфигурации **Apache2**.

Исходный код **ApacheConf.PHP** предоставляется на условиях лицензии **Apache License Version 2.0**.

### Требования

* PHP5 >= 5.5, PHP7;
* Apache2 >= 2.4.

**Примечание:** Работа с ранними версиями просто не проверялась, но в теории возможна.

### Как использовать?

Подключите файл **Conf.php** и импортируйте класс ``Nemiro\Apache\Conf``.

```PHP
# подключаем файл Conf.php (укажите правильный путь к файлу)
require_once 'Apache/Conf.php';

# создаем синоним класса Conf - ApacheConf
use Nemiro\Apache\Conf as ApacheConf;
```

#### Загрузка файла конфигурации

```PHP
# создаем экземпляр класса Conf с указанием пути к файлу конфигурации,
# который следует прочитать и разобрать
$conf = new ApacheConf('/etc/apache2/sites-available/example.org.conf');
# альтернативный вариант:
# $conf = ApacheConf::CreateFromFile('/etc/apache2/sites-available/example.org.conf');

# получаем и выводим содержимое секций и значения отдельных параметров
var_dump($conf['VirtualHost']);
var_dump($conf['VirtualHost']->ParametersAsString());
var_dump($conf['VirtualHost']['DocumentRoot']->ParametersAsString());
var_dump($conf['VirtualHost']['ServerName']->ParametersAsString());
var_dump($conf['VirtualHost']['Alias']);
```

#### Загрузка конфигурации из строки

```PHP
# формируем строку с данными конфигурации
$str = '<VirtualHost 127.0.0.1:80>
	DocumentRoot /home/example.org/www
  ServerName example.org

  <Location />
	  AuthType Basic
		AuthUserFile users.pwd
		Require valid-user
	</Location>
</VirtualHost>';

# разбираем строку
$conf = ApacheConf::CreateFromString($str);

# получаем и выводим содержимое секций и значения отдельных параметров
var_dump($conf['VirtualHost']);
var_dump($conf['VirtualHost']->ParametersAsString());
var_dump($conf['VirtualHost']['ServerName']->ParametersAsString());

# получаем первый элемент Location
$location = $conf['VirtualHost']['Location'][0];
# выводим
var_dump($location);
```

#### Сохранение файла

```PHP
# загружаем файл
$conf = ApacheConf::CreateFromFile('/etc/apache2/sites-available/example.org.conf');

# только в качестве примера
# ---------------------------------------------------------------------------------------
# меняем параметры
$conf['VirtualHost']['ServerName']->Parameters = array('example.org', 'www.example.org');
$conf['VirtualHost']['DocumentRoot']->Parameters = array('/home/example.org/www');

# создаем новую секцию Directory
$new_directory = ApacheConf::CreateDirective('Directory');
$new_directory->AddDirective('AllowOverride', 'All');
$new_directory->AddDirective('Allow', array('from', 'all'));
$new_directory->AddDirective('Require', array('all', 'granted'));

# добавляем созданную секцию Directory в секцию VirtualHost
$new_directory->AddTo($conf['VirtualHost']);
# ---------------------------------------------------------------------------------------

# записываем изменения в исходный файл
$conf->Save();

# или можно указать имя файла, в который будут сохранены данные
# $conf->Save('newFileName.conf');
```

#### Получение конфигурации в виде строки из текущего экземпляра класса

```PHP
# загружаем файл
$conf = new ApacheConf::CreateFromFile('/etc/apache2/sites-available/example.org.conf');

# только в качестве примера
# ---------------------------------------------------------------------------------------
# изменение параметров
$conf['VirtualHost']['ServerName']->Parameters = array('example.org', 'www.example.org');
$conf['VirtualHost']['DocumentRoot']->Parameters = array('/home/example.org/www');

# создание новой секции Directory
$new_directory = ApacheConf::CreateDirective('Directory');
$new_directory->AddDirective('AllowOverride', 'All');
$new_directory->AddDirective('Allow', array('from', 'all'));
$new_directory->AddDirective('Require', array('all', 'granted'));

# добавляем созданную секцию Directory в секцию VirtualHost
$new_directory->AddTo($conf['VirtualHost']);
# ---------------------------------------------------------------------------------------

# получаем конфигурацию в виде строки
$string = $conf->GetAsString();

# выводим строку
var_dump($string);
```

#### Создание конфигурации с нуля

```PHP
# создаем пустой экземпляр класса Conf
$conf = new ApacheConf();

# создаем секцию VirtualHost
$virtualHost = ApacheConf::CreateDirective('VirtualHost', '192.168.100.39:8080');
$virtualHost->AddDirective('DocumentRoot', '/home/example.org/www');
$virtualHost->AddDirective('ServerName', 'example.org');

# добавляем секцию в conf
$conf->Add($virtualHost);

# создаем новую директорию
$directory = ApacheConf::CreateDirective('Directory');
$directory->AddDirective('AllowOverride', 'All');
$directory->AddDirective('Allow', array('from', 'all'));
$directory->AddDirective('Require', array('all', 'granted'));

# добавляем директорию в секцию VirtualHost
$directory->AddTo($virtualHost);

# получаем конфигурацию в виде строки
$string = $conf->GetAsString();

# выводим строку
var_dump($string);

# или можно сохранить конфигурацию в файл
# $conf->Save('newFileName.conf');
```