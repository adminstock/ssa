### NginxConf.PHP [![Latest Stable Version](https://poser.pugx.org/aleksey.nemiro/nginxconf.php/v/stable)](https://packagist.org/packages/aleksey.nemiro/nginxconf.php) [![Total Downloads](https://poser.pugx.org/aleksey.nemiro/nginxconf.php/downloads)](https://packagist.org/packages/aleksey.nemiro/nginxconf.php) [![License](https://poser.pugx.org/aleksey.nemiro/nginxconf.php/license)](https://packagist.org/packages/aleksey.nemiro/nginxconf.php)

**NginxConf.PHP** - небольшой набор классов для работы с файлами конфигурации **Nginx**.

Исходный код **NginxConf.PHP** предоставляется на условиях лицензии **Apache License Version 2.0**.

### Требования

* PHP5 >= 5.5, PHP7;
* Nginx >= 1.9.

**Примечание:** Работа с ранними версиями просто не проверялась, но в теории возможна.

### Как использовать

Подключите файл **Conf.php** и импортируйте класс ``Nemiro\Nginx\Conf``.

```PHP
# подключаем файл Conf.php (укажите правильный путь к файлу)
require_once 'Nginx/Conf.php';

# создаем синоним класса Conf - NginxConf
use Nemiro\Nginx\Conf as NginxConf;
```

#### Загрузка файла конфигурации

```PHP
# создаем экземпляр класса Conf с указанием пути к файлу конфигурации,
# который следует прочитать и разобрать
$conf = new NginxConf('/etc/nginx/sites-available/example.org.conf');
# альтернативный вариант:
# $conf = NginxConf::CreateFromFile('/etc/nginx/sites-available/example.org.conf');

# для примера, выводим содержимое элемента server
var_dump($conf['server']);

# методом ContainsChild можно проверить существование элемента
if ($conf['server']->ContainsChild('listen'))
{
  var_dump($conf['server']['listen']->ParametersAsString());
}

# выводим параметры
var_dump($conf['server']['server_name']->ParametersAsString());
var_dump($conf['server']['root']->ParametersAsString());

# выводим элементы location
var_dump($conf['server']['location']);
```

#### Загрузка конфигурации из строки

```PHP
# формируем строку с данными конфигурации
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

# разбираем строку
$conf = NginxConf::CreateFromString($str);

# для примера, выводим элементы
var_dump($conf['server']);
var_dump($conf['server']['server_name']->ParametersAsString());
var_dump($conf['server']['root']->ParametersAsString());

# выводим первый элемент location
$location = $conf['server']['location'][0];
var_dump($location);

# выводим второй элемент location
$location2 = $conf['server']['location'][1];
var_dump($location2);
```

#### Сохранение файла

```PHP
# загружаем файл
$conf = NginxConf::CreateFromFile('/etc/nginx/sites-available/example.org.conf');

# только в качестве примера
# ---------------------------------------------------------------------------------------
# меняем параметры
$conf['server']['server_name']->Parameters = array('example.org', 'www.example.org');
$conf['server']['root']->Parameters = array('/home/example.org/www');

# создаем новый элемент location
$new_location = NginxConf::CreateDirective('location');

# добавляем одиночные элементы
$new_location->AddDirective('index', array('Default.aspx', 'default.aspx'));
$new_location->AddDirective('proxy_pass', array('http://127.0.0.1:8080'));

# добавляем группы элементов
$proxy_set_header = NginxConf::CreateGroup('proxy_set_header');
$proxy_set_header->AddDirective(array('X-Real-IP', '$remote_addr'));
$proxy_set_header->AddDirective(array('X-Forwarded-For', '$remote_addr'));
$proxy_set_header->AddDirective(array('Host', '$host'));

# добавляем созданную группу proxy_set_header в созданный элемент location 
$proxy_set_header->AddTo($new_location);

# помещаем созданный элемент location в элемент server
$new_location->AddTo($conf['server']);
# ---------------------------------------------------------------------------------------

# записываем изменения в исходный файл
$conf->Save();

# или можно указать имя файла, в который будут сохранены данные
# $conf->Save('newFileName.conf');
```

#### Получение конфигурации в виде строки из текущего экземпляра класса

```PHP
# загружаем файл
$conf = new NginxConf::CreateFromFile('/etc/nginx/sites-available/example.org.conf');

# только в качестве примера
# ---------------------------------------------------------------------------------------
# меняем параметры
$conf['server']['server_name']->Parameters = array('example.org', 'www.example.org');
$conf['server']['root']->Parameters = array('/home/example.org/www');

# создаем новый элемент location
$new_location = NginxConf::CreateDirective('location');

# добавляем одиночные элементы
$new_location->AddDirective('index', array('Default.aspx', 'default.aspx'));
$new_location->AddDirective('proxy_pass', array('http://127.0.0.1:8080'));

# добавляем группы элементов
$proxy_set_header = NginxConf::CreateGroup('proxy_set_header');
$proxy_set_header->AddDirective(array('X-Real-IP', '$remote_addr'));
$proxy_set_header->AddDirective(array('X-Forwarded-For', '$remote_addr'));
$proxy_set_header->AddDirective(array('Host', '$host'));

# добавляем созданную группу proxy_set_header в созданный элемент location 
$proxy_set_header->AddTo($new_location);

# помещаем созданный элемент location в элемент server
$new_location->AddTo($conf['server']);
# ---------------------------------------------------------------------------------------

# получаем конфигурацию в виде строки
$string = $conf->GetAsString();

# выводим строку
var_dump($string);
```

#### Создание конфигурации с нуля

```PHP
# создаем пустой экземпляр класса Conf
$conf = new NginxConf();

# создаем корневой элемент server
$conf->Add(NginxConf::CreateDirective('server'));

# добавляем элементы в server
$conf['server']->AddDirective('server_name', array('example.org', 'www.example.org'));
$conf['server']->AddDirective('root', array('/home/example.org/www'));

# создаем новый элемент location
$location = NginxConf::CreateDirective('location');

# добавляем в location дочерние элементы
$location->AddDirective('index', array('index.php', 'index.html', 'index.htm'));

# помещаем location в server
$location->AddTo($conf['server']);

# получаем конфигурацию в виде строки
$string = $conf->GetAsString();

# выводим строку
var_dump($string);

# или можно сохранить конфигурацию в файл
# $conf->Save('example.org.conf');
```