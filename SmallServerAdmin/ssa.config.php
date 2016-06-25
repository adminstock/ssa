<?php
// panel config for default server
$config = [];

// active modules (separated by commas)
$config['modules'] = 'users,svn,dbadmin,sites,files,monitoring,services,ssh,settings';

// the list of widgets to display on the main page
$config['widgets']['monitoring'] = ['Enabled' => TRUE];
$config['widgets']['services'] = ['Enabled' => TRUE, 'Format' => '<div>%s</div>', 'NgInit' => 'SearchString = \'nginx,apache,htan\'; Load()' ];
$config['widgets']['sites'] = ['Enabled' => TRUE, 'Format' => '<div>%s</div>'];

// uncomment to enable logging
// $config['ssa_log_path'] = '../.logs/ssa.log';

// files
$config['files_auto_reload'] = [
  // allow sudo systemctl daemon-reload
  'daemon' => TRUE,
  // allow sudo service apache2 reload
  'apache' => TRUE,
  // allow sudo service nginx reload
  'nginx' => TRUE
];

// subversion
$config['svn_authz'] = '/etc/apache2/dav_svn.authz';
$config['svn_passwd'] = '/etc/apache2/dav_svn.passwd';
$config['svn_repositories'] = '/var/svn/';
$config['svn_default_group'] = 'everyone';
$config["svn_username_pattern"] = '^([A-Za-z]+)([A-Za-z0-9_.-]*)$';
$config["svn_username_invalid_message"] = 'The username can contain letters of the English alphabet, numbers, hyphens and underscores. The username must start with a letter.';
$config["svn_password_pattern"] = '^(.{1,24})$';
$config["svn_password_invalid_message"] = 'The password must contain 1 to 24 characters.';
$config["svn_groupname_pattern"] = '^([A-Za-z]+)([A-Za-z0-9_.-]*)$';
$config["svn_groupname_invalid_message"] = 'The name can contain letters of the English alphabet, numbers, hyphens and underscores. The name must start with a letter.';

// web server
$config['web_mode'] = 'nginx'; // nginx+apache | nginx | apache
$config['web_apache_path'] = '/etc/apache2';
$config['web_nginx_path'] = '/etc/nginx';
$config['web_htan_enabled'] = TRUE; // true if htan-runner is installed; otherwise false
$config['web_htan_path'] = '/etc/htan';
$config["web_sitename_pattern"] = '^([A-Za-z0-9_-]+)([A-Za-z0-9_.-]*)$';
$config["web_sitename_invalid_message"] = 'Site name must begin with the letters of the English alphabet or numbers. The name must not contain special characters, except for: dash, the underscore character and dot.';

// dbadmin
$config['dbadmin_list'] = [
  ['file_name' => 'phpmyadmin', 'title' => 'MySql'],
  ['file_name' => 'phppgadmin', 'title' => 'PostgreSql']
];

// settings (module)
$config['settings_default_branch'] = 'master';
$config['settings_update_sources'] = 
[
  'master' => 
  [
    'Title' => 'Stable',
    'VersionUrl' => 'https://raw.githubusercontent.com/adminstock/ssa/master/SmallServerAdmin/.version',
    'ChangeLogUrl' => 'https://raw.githubusercontent.com/adminstock/ssa/master/CHANGELOG.md',
    'SsaUrl' => 'https://github.com/adminstock/ssa.git/trunk/SmallServerAdmin'
  ],
  'dev' =>
  [
    'Title' => 'Develop',
    'Description' => 'The official main branch of the development a new versions.',
    'VersionUrl' => 'https://raw.githubusercontent.com/adminstock/ssa/dev/SmallServerAdmin/.version',
    'ChangeLogUrl' => 'https://raw.githubusercontent.com/adminstock/ssa/dev/CHANGELOG.md',
    'SsaUrl' => 'https://github.com/adminstock/ssa.git/branches/dev/SmallServerAdmin'
  ]
];

// client-side config
$config['client'] = [
  'WebServer' => isset($config['web_mode']) ? $config['web_mode'] : NULL,
  'ApacheHost' => '127.0.0.1',
  'ApachePort' => 8080,
  'LogFolderName' => '.logs',
  'PhpFastCgiPort' => 9001,
  'AspNetFastCgiPort' => 9100,
  'ServerAddress' => isset($config['ssh_host']) ? $config['ssh_host'] : NULL,
  'ServerName' => isset($config['server_name']) ? $config['server_name'] : NULL,
  'HtanEnabled' => isset($config['web_htan_enabled']) ? $config['web_htan_enabled'] : NULL,
  'DefaultBranch' => isset($config['settings_default_branch']) ? $config['settings_default_branch'] : NULL
];