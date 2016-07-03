# SmallServerAdmin (SSA)

This control panel for small **Debian** and **Ubuntu** servers.

The interaction with a server is performed via **SSH**.

The panel has a modular structure. One can easily create new modules 
or enhance existing ones.

## Features

* User Management:
  * viewing, creating, editing and deleting users.
* Sites Management:
  * supports Nginx, Nginx + Apache;
  * list of sites;
  * config files editor;
  * enabling and disabling sites;
  * creating a configuration-based templates;
  * deleting sites.
* File Management: 
  * list of files and folders;
  * creation of new folders and text files;
  * viewing and editing files;
  * moving, copying and deleting files and folders;
  * permissions management.
* Subversion Management:
  * users and groups;
  * repositories.
* Server Monitoring: 
  * list of processes;
  * CPU, RAM and HDD usage.
* Service Management: start, stop and reload;
* SSH client: unilateral execution of commands.

## License

**SmallServerAdmin** is licensed under the **Apache License Version 2.0**.

See also [Third-Party License](THIRDPARTYNOTICE.md).

## Requirements

Server requirements to manage (administrable server):

* Debian 7 or Debian 8, or Ubuntu Server 16;
* OpenSSH >= 6.7;
* sudo >= 1.8.10;
* sysstat >= 11.0.1;
* Nginx >= 1.6 and/or Apache >= 2.4 (for web server);
* [htan-runner](https://github.com/adminstock/htan-runner) for ASP.NET FastCGI;
* See also README files for individual modules.

The panel can be located on any server.

Server requirements for the control panel:

* Linux, Windows, Mac OS/OS X;
* Apache and/or Nginx, or IIS, or another web server with PHP support;
* PHP5 >= 5.5 or PHP7 with ssh2 extension;

_**NOTE:** Earlier versions have not been tested._

_**NOTE:** For Windows required PHP v5.5._

## Installation & Configuration

### Server Configuration (administrable server)

If **SmallServerAdmin** will be located on the managed (administrable) server, 
it is recommended to use **[HTAN](https://github.com/adminstock/htan)** to automatic install **SmallServerAdmin**:

#### Debian

```Bash
# root access is required
su -l root

# update packages
apt-get update && apt-get upgrade

# prerequisites
apt-get install -y less libpcre3 git

# clone htan to /usr/lib/htan
git clone https://github.com/adminstock/htan.git /usr/lib/htan

# create symbolic links to htan
[[ -f /sbin/htan ]] || ln -s /usr/lib/htan/run /sbin/htan
[[ -f /usr/sbin/htan ]] || ln -s /usr/lib/htan/run /usr/sbin/htan

# set permissions
chmod u=rwx /usr/lib/htan/run

# run
htan --yes --install=ssa
```

#### Ubuntu Server

```Bash
# update packages
sudo apt-get update && sudo apt-get upgrade

# prerequisites
sudo apt-get install -y less libpcre3 git

# clone htan to /usr/lib/htan
sudo git clone https://github.com/adminstock/htan.git /usr/lib/htan

# create symbolic links to htan
[[ -f /sbin/htan ]] || sudo ln -s /usr/lib/htan/run /sbin/htan
[[ -f /usr/sbin/htan ]] || sudo ln -s /usr/lib/htan/run /usr/sbin/htan

# set permissions
sudo chmod u=rwx /usr/lib/htan/run

# run
sudo htan --yes --install=ssa
```

#### Manually installation

To manually install and configure the server, use the following instructions below.

All commands to the server are performed through **sudo**.
On the server, you must install and configure **sudo**.

For **Debian** only, istall **sudo**:

```Bash
su -l root
apt-get -y sudo
```

The interaction with the server will be carried out via **SSH**.
Usually on a server must already be installed **OpenSSH** package, 
but if it is not, you need to install **OpenSSH**.

To obtain information about the system used **sysstat**, which is also necessary to install.

```Bash
sudo apt-get -y install openssh-server sysstat
```

And also recommended **etckeeper** to install:

```Bash
sudo apt-get -y install etckeeper
[[ ! -d "/etc/.git" ]] && cd /etc && sudo etckeeper init
```

The best practice is to create a single user on whose behalf will be server management.

For example, add **ssa** user:

```Bash
sudo adduser ssa --shell /bin/bash --no-create-home --gecos 'SmallServerAdmin'
```

_**NOTE:** You can use any name instead of **ssa**._

_**ATTENTION:** Remember user password. It is required for the Panel Configuration._

Add the user to the **sudo** group:

```Bash
sudo usermod -a -G sudo ssa
```

And restart **sudo**:

```Bash
sudo service sudo restart
```

If the connection via **SSH** is limited to narrow the list of users, 
add the created user in the list of allowed.

Open `/etc/ssh/sshd_config`:

```Bash
sudo nano /etc/ssh/sshd_config
```

Add user name to the `AllowUsers` and save changes:

```Bash
AllowUsers ssa
```

_**NOTE:** List of users separated by spaces. For example: `user1 user2 ssa userN`._

This is the minimum configuration that must be done.

If you have connection problems, try restarting the server.

### Panel Configuration

General (default) panel settings contained in the **ssh.config.php** file.
The parameters are stored in the global variable `$config` as an associative array.

In addition, each server can have its own settings, which are located in the **/servers** folder.

The panel has a modular design. Each module has its own set of parameters. 
For details see the **README** file of a specific module.

#### SSH

_**NOTE:** If you use automatic installation with **[HTAN](https://github.com/adminstock/htan)**, then change the settings of **SSH** is not need._

To operate the panel, you must configure the **SSH** connection settings.

By first opening the panel in a browser, you will be redirected to the servers management page where you can add and configure a new server.
If it does not, open or create the **/servers/default.php** file and specify the connection settings to the server.

```PHP
// ssh server address
$config['ssh_host'] = '192.168.56.139';
// connection port (default: 22)
$config['ssh_port'] = '22';
// username (for example: ssa)
$config['ssh_user'] = 'username';
// user password
$config['ssh_password'] = 'password';
// required password for sudo user
$config['ssh_required_password'] = TRUE;
```

If the connection settings are incorrect, the panel can not connect to the server.

All commands are executed via **sudo**. 
If the value `ssh_required_password` is `TRUE`, for each command 
will use the user password (`ssh_password`).

If you are the only one user on the server, you can disable the password.
For this set the `FALSE` to the `ssh_required_password`.

And also allow the execution of commands without entering a password:

```Bash
sudo bash -c 'echo "ssa ALL=(ALL) NOPASSWD:ALL" | (EDITOR="tee -a" visudo)'
```

_**NOTE:** At the same time, the password will still be used to connect to the server._

_**WARNING:** If the server has other users, then for security reasons 
it is not recommended to disable the requirement of password._

#### Modules

In the parameter `modules` you can specify a list of required modules, 
separated by commas. These modules will be displayed in the menu in this order.

```PHP
$config['modules'] = 'users,svn,sites,files,monitoring,services,ssh';
```

The absence of modules in the list does not restrict access to them.

Modules in the menu will be displayed in the order in which they are specified in the list.

In the file **ssa.config.php** specified the default list of modules that will be used for all servers, who have not their own list of modules.

#### Widgets

Some modules have widgets that can be displayed on the main page.

Widget settings are located in the root element - `widgets`, 
the child elements that contain settings for specific module: 
`$config['widgets']['moduleName']`.

Each widget must contain the required parameter `Enabled`, which indicates 
the need to display the widget panel on the main page.

In the optional parameter `Format`, you can specify a custom format to display 
widget on the page.

In addition, the widget may comprise any other individual parameters.
For more information, see the README file of a specific module.

```PHP
$config['widgets']['monitoring'] = ['Enabled' => TRUE];
$config['widgets']['services'] = [
  'Enabled' => TRUE, 
  'Format' => '<div>%s</div>', 
  'NgInit' => 'SearchString = \'nginx,apache\'; Load()' 
];
$config['widgets']['sites'] = [
  'Enabled' => TRUE, 
  'Format' => '<div>%s</div>'
];
```

## See Also

* [Change Log](CHANGELOG.md)
* [Contributing to SmallServerAdmin](CONTRIBUTING.md)
* [Hosting Tools (HTAN)](https://github.com/adminstock/htan)