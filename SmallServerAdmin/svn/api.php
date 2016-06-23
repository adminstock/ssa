<?php
namespace Api
{

  /*
   * Copyright © Aleksey Nemiro, 2016. All rights reserved.
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

  \Nemiro\App::IncludeFile('~/ssh/api.php');
  \Nemiro\App::IncludeFile('~/svn/models/SvnUser.php');
  \Nemiro\App::IncludeFile('~/svn/models/SvnGroup.php');
  \Nemiro\App::IncludeFile('~/svn/models/SvnUserToEdit.php');
  \Nemiro\App::IncludeFile('~/svn/models/SvnGroupToEdit.php');
  \Nemiro\App::IncludeFile('~/svn/models/SvnRepository.php');
  \Nemiro\App::IncludeFile('~/svn/models/SvnRepositoryPermission.php');

  /**
   * Subversion server management.
   * 
   * @author     Aleksey Nemiro <aleksey@nemiro.ru>
   * @copyright  Aleksey Nemiro, 2016
   */
  class Svn
  {

    /**
     * SSH client.
     * 
     * @var SSH
     */
    private $SshClient = NULL;

    function __construct()
    {
      $this->SshClient = new SSH();
    }

    #region ..Groups..

    /**
     * Returns the groups list.
     * 
     * @return \Models\SvnGroup[]
     */
    public function GetGroups()
    {
      global $config;

      $authz = $this->GetAuthz();
      $result = [];

      if (!isset($authz['groups']))
      {
        return $result;
      }

      foreach($authz['groups'] as $group => $users)
      {
        if ($group == $config['svn_default_group'])
        {
          continue;
        }

        $g = new \Models\SvnGroup();
        $g->Name = $group;
        $g->Members = ($users != '' ? explode(',', $users) : []);

        ksort($g->Members);

        $result[] = $g;
      }

      return $result;
    }

    /**
     * Returns the names of groups.
     * 
     * @return \string[]
     */
    public function GetGroupNames()
    {
      global $config;

      $authz = $this->GetAuthz();
      $result = [];

      if (!isset($authz['groups']))
      {
        return $result;
      }

      $result = array_keys($authz['groups']);

      if (isset($result[$config['svn_default_group']]))
      {
        unset($result[$config['svn_default_group']]);
      }

      ksort($result);

      return $result;
    }

    /**
     * Returns the data of single group.
     * 
     * @return \Models\SvnGroupToEdit
     */
    public function GetGroup($data)
    {
      if (!isset($data['name']) || $data['name'] == '')
      {
        throw new \ErrorException('Group name is required.');
      }

      $authz = $this->GetAuthz();

      if (!isset($authz['groups']))
      {
        return NULL;
      }

      $result = new \Models\SvnGroupToEdit();

      foreach($authz['groups'] as $group => $users)
      {
        if ($group == $data['name'])
        {
          $result->Group = new \Models\SvnGroup();
          $result->Group->Name = $group;
          $result->Group->Members = ($users != '' ? explode(',', $users) : []);
          break;
        }
      }

      if ($result->Group == NULL)
      {
        throw new \ErrorException('Group not found.');
      }

      $result->Users = $this->GetUniqueSortedLogins($authz['groups']);

      return $result;
    }

    /**
     * Saves group.
     * 
     * @param mixed $data The group to save.
     * 
     * @throws \ErrorException 
     */
    public function SaveGroup($data)
    {
      global $config;

      if (!isset($data['Current']))
      {
        throw new \ErrorException('Current is required.');
      }

      if (!isset($data['Current']['Name']) || $data['Current']['Name'] == '')
      {
        throw new \ErrorException('Group name is required.');
      }

      if ((bool)$data['IsNew'] === TRUE)
      {
        if (preg_match('/'.$config["svn_groupname_pattern"].'/', $data['Current']['Name']) !== 1)
        {
          throw new \ErrorException('Invalid group name.'.(isset($config["svn_groupname_invalid_message"]) ? "\n\n".$config["svn_groupname_invalid_message"] : ''));
        }
      }
      else
      {
        if (!isset($data['Source']))
        {
          throw new \ErrorException('Source is required.');
        }

        if ($data['Current']['Name'] != $data['Source']['Name'] && preg_match('/'.$config["svn_groupname_pattern"].'/', $data['Current']['Name']) !== 1)
        {
          throw new \ErrorException('Invalid group name.'.(isset($config["svn_groupname_invalid_message"]) ? "\n\n".$config["svn_groupname_invalid_message"] : ''));
        }
      }

      $authz = $this->GetAuthz();

      // get all groups
      $groupsList = array_keys($authz['groups']);

      if ((bool)$data['IsNew'] === TRUE)
      {
        // check name
        if (array_search($data['Current']['Name'], $groupsList) !== FALSE)
        {
          throw new \ErrorException('Group "'.$data['Current']['Name'].'" already exists. Please, input other group name and try again.');
        }

        // add group and members
        $authz['groups'][$data['Current']['Name']] = (isset($data['Current']['Members']) ? implode(',', $data['Current']['Members']) : '');

        // save $authz
        $this->SetAuthz($authz);
      }
      else
      {
        // need to rename group
        if ($data['Current']['Name'] != $data['Source']['Name'])
        {
          // check new name
          if (array_search($data['Current']['Name'], $groupsList) !== FALSE)
          {
            throw new \ErrorException('Group "'.$data['Current']['Name'].'" already exists. Please, input other group name and try again.');
          }

          // remove old group
          unset($authz['groups'][$data['Source']['Name']]);

          // rename group in repositories
          foreach($authz as $section => $keys)
          {
            if ($section == 'groups')
            {
              continue;
            }

            foreach($keys as $entry => $access)
            {
              if ($entry == '@'.$data['Source']['Name'])
              {
                // add rules for new group
                $authz[$section]['@'.$data['Current']['Name']] = $authz[$section][$entry];
                // remove old rules
                unset($authz[$section][$entry]);
              }
            }

          }
        }

        // set members
        $authz['groups'][$data['Current']['Name']] = (isset($data['Current']['Members']) ? implode(',', $data['Current']['Members']) : '');

        // save $authz
        $this->SetAuthz($authz);
      }

      return ['Success' => TRUE];
    }

    public function DeleteGroup($data)
    {
      global $config;

      $groupName = $data['name'];
      $authz = $this->GetAuthz();

      foreach($authz as $section => $keys)
      {
        if ($section == 'groups')
        {
          // remove from groups list
          unset($authz[$section][$groupName]);
          continue;
        }

        // remove permissions
        foreach($keys as $entry => $access)
        {
          if ($entry == '@'.$groupName)
          {
            unset($authz[$section]['@'.$groupName]);
          }
        }

      }

      // save authz
      $this->SetAuthz($authz);

      return ['Success' => TRUE];
    }

    #endregion
    #region ..Users..

    /**
     * Returns logins list of all subversion users.
     * 
     * @param mixed $data
     * 
     * @return \string[]
     */
    public function GetLogins($data)
    {
      $authz = $this->GetAuthz();
      return $this->GetUniqueSortedLogins($authz['groups']);
    }

    /**
     * Returns subversion users.
     * 
     * @param mixed $data
     * 
     * @return \Models\SvnUser[]
     */
    public function GetUsers($data)
    {
      global $config;

      $authz = $this->GetAuthz();
      $result = [];

      if (!isset($authz['groups']))
      {
        return $result;
      }
      
      $search = (isset($data) && isset($data['search']) ? $data['search'] : '');

      foreach($authz['groups'] as $group => $users)
      {
        if ($users == '')
        {
          continue;
        }

        $usersList = explode(',', $users);

        foreach($usersList as $login)
        {
          if ($search != '' && stripos($login, $search) === FALSE)
          {
            continue;
          }

          $u = NULL;

          foreach ($result as $eu)
          {
            if ($eu->Login == $login)
            {
              $u = $eu;
              break;
            }
          }

          if ($u == NULL) 
          { 
            $u = new \Models\SvnUser(); 
            $u->Login = $login;
            $result[] = $u;
          }

          if ($group != $config['svn_default_group'])
          {
            if (!isset($u->Groups)) { $u->Groups = []; }
            $u->Groups[] = $group;
          }
        }

      }

      return $result;
    }
    
    /**
     * Returns user data and all groups list.
     * 
     * @param mixed $data 
     */
    public function GetUser($data)
    {
      global $config;

      if (!isset($data['login']) || $data['login'] == '')
      {
        throw new \ErrorException('User login is required.');
      }

      $authz = $this->GetAuthz();

      if (!isset($authz['groups']))
      {
        return NULL;
      }

      $result = new \Models\SvnUserToEdit();
      $result->User = new \Models\SvnUser();
      $result->User->Login = $data['login'];

      $result->Groups = [];
      $result->User->Groups = [];
      
      foreach($authz['groups'] as $group => $users)
      {
        if ($group == $config['svn_default_group'])
        {
          continue;
        }

        if ($users != '')
        {
          $usersList = explode(',', $users);

          if (array_search($result->User->Login, $usersList) !== FALSE)
          {
            $result->User->Groups[] = $group;
          }
        }

        $result->Groups[] = $group;
      }

      return $result;
    }

    public function SaveUser($data)
    {
      global $config;

      if (!isset($data['Current']))
      {
        throw new \ErrorException('Current is required.');
      }

      if (!isset($data['Current']['Login']) || $data['Current']['Login'] == '')
      {
        throw new \ErrorException('User login is required.');
      }

      if ((bool)$data['IsNew'] === TRUE)
      {
        if (preg_match('/'.$config["svn_username_pattern"].'/', $data['Current']['Login']) !== 1)
        {
          throw new \ErrorException('Invalid username.'.(isset($config["svn_username_invalid_message"]) ? "\n\n".$config["svn_username_invalid_message"] : ''));
        }

        if (!isset($data['Current']['Password']) || $data['Current']['Password'] == '')
        {
          throw new \ErrorException('User password is required.');
        }

        if (preg_match('/'.$config["svn_password_pattern"].'/', $data['Current']['Password']) !== 1)
        {
          throw new \ErrorException('Invalid password.'.(isset($config["svn_password_invalid_message"]) ? "\n\n".$config["svn_password_invalid_message"] : ''));
        }
      }
      else
      {
        if ((bool)$data['SetLogin'] === TRUE && preg_match('/'.$config["svn_username_pattern"].'/', $data['Current']['Login']) !== 1)
        {
          throw new \ErrorException('Invalid username.'.(isset($config["svn_username_invalid_message"]) ? "\n\n".$config["svn_username_invalid_message"] : ''));
        }

        if ((bool)$data['SetPassword'] === TRUE && preg_match('/'.$config["svn_password_pattern"].'/', $data['Current']['Password']) !== 1)
        {
          throw new \ErrorException('Invalid password.'.(isset($config["svn_password_invalid_message"]) ? "\n\n".$config["svn_password_invalid_message"] : ''));
        }
      }

      $authz = $this->GetAuthz();

      // get all users
      $users = $this->GetUniqueSortedLogins($authz['groups']);

      // is new user
      if ((bool)$data['IsNew'])
      {

        // check login
        if (array_search($data['Current']['Login'], $users) !== FALSE)
        {
          throw new \ErrorException('Username "'.$data['Current']['Login'].'" already exists. Please, input other username and try again.');
        }

        // create user
        if ($authz['groups'][$config['svn_default_group']] != '')
        {
          $authz['groups'][$config['svn_default_group']] .= ',';
        }
        $authz['groups'][$config['svn_default_group']] .= $data['Current']['Login'];

        // add user to groups
        if (count($data['Current']['Groups']) > 0)
        {
          foreach($data['Current']['Groups'] as $group)
          {
            if ($authz['groups'][$group] != '')
            {
              $authz['groups'][$group] .= ',';
            }
            $authz['groups'][$group] .= $data['Current']['Login'];
          }
        }

        // save $authz
        $this->SetAuthz($authz);

        // set password
        $shell_result = $this->SshClient->Execute
        (
          'sudo bash -c "if [[ ! -f '.$config['svn_passwd'].' ]]; then '.
          'htpasswd -mbc '.$config['svn_passwd'].' \''.$data['Current']['Login'].'\' \''.$data['Current']['Password'].'\' >> /dev/null 2>&1 && echo \'OK\' || echo \'Failed to set password.\'; '.
          'else '.
          'htpasswd -mb '.$config['svn_passwd'].' \''.$data['Current']['Login'].'\' \''.$data['Current']['Password'].'\' >> /dev/null 2>&1 && echo \'OK\' || echo \'Failed to set password.\'; '.
          'fi;"'
        );

        if ($shell_result->Result != 'OK')
        {
          if ($shell_result->Result == '')
          {
            throw new \ErrorException($shell_result->Error);
          }
          else
          {
            throw new \ErrorException($shell_result->Result);
          }
        }

      }
      else
      {

        // new login need
        if ((bool)$data['SetLogin'] === TRUE)
        {
          // check login
          if ($data['Current']['Login'] != $data['Source']['Login'] && array_search($data['Current']['Login'], $users) !== FALSE)
          {
            throw new \ErrorException('Username "'.$data['Current']['Login'].'" already exists. Please, input other username and try again.');
          }

          // search old login and set new
          foreach($authz['groups'] as $group => $users)
          {
            $newUsersList = [];
            $usersList = ($users != '' ? explode(',', $users) : []);

            foreach($usersList as $username)
            {
              $newUsersList[] = ($username == $data['Source']['Login'] ? $data['Current']['Login'] : $username);
            }

            $authz['groups'][$group] = implode(',', $newUsersList);
          }

          // TODO: Change login for repositories

          // save $authz
          $this->SetAuthz($authz);

          // change login in password file
          $shell_result = $this->SshClient->Execute('sudo sed -i -r "s/^'.$data['Source']['Login'].'(:.*)/'.$data['Current']['Login'].'\1/" '.$config['svn_passwd']);
          if ($shell_result->Error != '')
          {
            throw new \ErrorException('Failed to set password: '.$shell_result->Error);
          }
        }

        // new password need
        if ((bool)$data['SetPassword'] === TRUE)
        {
          $shell_result = $this->SshClient->Execute
          (
            'sudo bash -c "if [[ ! -f '.$config['svn_passwd'].' ]]; then '.
            'htpasswd -mbc '.$config['svn_passwd'].' \''.$data['Current']['Login'].'\' \''.$data['Current']['Password'].'\' >> /dev/null 2>&1 && echo \'OK\' || echo \'Failed to set password.\'; '.
            'else '.
            'htpasswd -mb '.$config['svn_passwd'].' \''.$data['Current']['Login'].'\' \''.$data['Current']['Password'].'\' >> /dev/null 2>&1 && echo \'OK\' || echo \'Failed to set password.\'; '.
            'fi;"'
          );

          if ($shell_result->Result != 'OK')
          {
            if ($shell_result->Result == '')
            {
              throw new \ErrorException($shell_result->Error);
            }
            else
            {
              throw new \ErrorException($shell_result->Result);
            }
          }
        }

        // groups
        if (count(array_diff($data['Source']['Groups'], $data['Current']['Groups'])) > 0 || count(array_diff($data['Current']['Groups'], $data['Source']['Groups'])) > 0)
        {
          foreach($authz['groups'] as $group => $users)
          {

            $usersList = ($users != '' ? explode(',', $users) : []);

            // is default group
            if ($group == $config['svn_default_group'])
            {
              if (array_search($data['Current']['Login'], $usersList) === FALSE) 
              {
                // add user to default group
                $usersList[] = $data['Current']['Login'];
                $authz['groups'][$group] = implode(',', $usersList);
              }
              // next group
              continue;
            }

            // has not users in the group
            if ($users == '')
            {
              if (array_search($group, $data['Current']['Groups']) !== FALSE)
              {
                // add user to group
                $authz['groups'][$group] = $data['Current']['Login'];
              }
              // next group
              continue;
            }

            if (array_search($group, $data['Current']['Groups']) !== FALSE && array_search($data['Current']['Login'], $usersList) === FALSE)
            {
              // add user to group
              $authz['groups'][$group] .= ','.$data['Current']['Login'];
              // next group
              continue;
            }

            if (array_search($group, $data['Current']['Groups']) === FALSE && array_search($data['Current']['Login'], $usersList) !== FALSE)
            {
              // remove user from group
              unset($usersList[array_search($data['Current']['Login'], $usersList)]);
              $authz['groups'][$group] = implode(',', $usersList);
              // next group
              continue;
            }

          }

          // save $authz
          $this->SetAuthz($authz);
        }
      }

    }

    public function DeleteUser($data)
    {
      global $config;

      $login = $data['login'];
      $authz = $this->GetAuthz();

      // delete from authz

      foreach($authz as $section => $keys)
      {
        if ($section == 'groups')
        {
          foreach($keys as $group => $users)
          {
            $usersList = ($users != '' ? explode(',', $users) : []);

            if (array_search($login, $usersList) !== FALSE)
            {
              // remove user from group
              unset($usersList[array_search($login, $usersList)]);
              $authz['groups'][$group] = (count($usersList) > 0 ? implode(',', $usersList) : '');
            }
          }

          continue;
        }

        // remove permissions
        foreach($keys as $entry => $access)
        {
          if ($entry == $login)
          {
            unset($authz[$section][$login]);
          }
        }

      }

      // save authz
      $this->SetAuthz($authz);

      // delete password
      $shell_result = $this->SshClient->Execute('sudo htpasswd -D '.$login.' >> /dev/null 2>&1 && echo "OK" || echo "Failed to remove password."');
      if ($shell_result->Result != 'OK')
      {
        //throw new \ErrorException($shell_result->Result);
      }

      return ['Success' => TRUE];
    }

    #endregion
    #region ..Repositories..

    /**
     * Returns the repositories list.
     * 
     * @return \Models\SvnRepository[]
     */
    public function GetRepositories($data)
    {
      global $config;

      $result = [];

      $rootPath = $config['svn_repositories'];

      if (\Nemiro\Text::EndsWith($rootPath, '/'))
      {
        $rootPath = substr($rootPath, 0, -1);
      }

      $search = (isset($data) && isset($data['search']) ? '*'.$data['search'].'*' : '*');

      $shell_result = $this->SshClient->Execute('sudo bash -c "cd '.$rootPath.'; find '.$search.' -maxdepth 0 -type d 2> /dev/null"');
      if ($shell_result->Error != '')
      {
        throw new \ErrorException($shell_result->Error);
      }

      $folders = explode("\n", $shell_result->Result);

      // root
      $root = new \Models\SvnRepository();
      $root->RelativePath = '/';
      $root->AbsolutePath = $rootPath;
      $root->Name = '';
      $result[] = $root;

      // other
      foreach($folders as $folder)
      {
        if ($folder == '')
        {
          continue;
        }

        $r = new \Models\SvnRepository();
        $r->RelativePath = '/'.$folder;
        $r->AbsolutePath = $rootPath.'/'.$folder;
        $r->Name = $folder;

        $result[] = $r;
      }

      return $result;
    }

    /**
     * Returns the data of single repository.
     * 
     * @return \Models\SvnRepository
     */
    public function GetRepository($data)
    {
      global $config;

      if (!isset($data['name']) || $data['name'] == '')
      {
        // throw new \ErrorException('Repository name is required.');
        $data['name'] = ''; // root
      }

      $path = $config['svn_repositories'];

      if (\Nemiro\Text::EndsWith($path, '/'))
      {
        $path = substr($path, 0, -1);
      }

      $path .= '/'.$data['name'];

      // check path
      $shell_result = $this->SshClient->Execute('sudo bash -c \'[[ -d "'.$path.'" ]] && echo "OK" || echo "Not found."\'');
      if ($shell_result->Result != 'OK')
      {
        throw new \ErrorException('Repository "'.$path.'" not found.');
      }

      $result = new \Models\SvnRepository();
      $result->AbsolutePath = $path;
      $result->RelativePath = '/'.$data['name'];
      $result->Name = $data['name'];
      $result->Permissions = [];

      // get permissions
      $authz = $this->GetAuthz();

      if (isset($authz[$data['name'].':/']))
      {
        foreach($authz[$data['name'].':/'] as $key => $value)
        {
          $p = new \Models\SvnRepositoryPermission();
          $p->ObjectName = $key;
          $p->Read = (strpos($value, 'r') !== FALSE);
          $p->Write = (strpos($value, 'w') !== FALSE);

          $result->Permissions[] = $p;
        }
      }

      return $result;
    }

    /**
     * Saves repository.
     * 
     * @param mixed $data Data to save.
     */
    public function SaveRepository($data)
    {
      global $config;

      $rootPath = $config['svn_repositories'];

      if (\Nemiro\Text::EndsWith($rootPath, '/'))
      {
        $rootPath = substr($rootPath, 0, -1);
      }

      if (!isset($data['Current']))
      {
        throw new \ErrorException('Current is required.');
      }

      if ((!isset($data['Current']['Name']) || $data['Current']['Name'] == '') && $data['Current']['RelativePath'] != '/')
      {
        throw new \ErrorException('Repository name is required.');
      }

      if ($data['Current']['RelativePath'] != '/' && preg_match('/^[A-Za-z0-9_.-]+$/', $data['Current']['Name']) !== 1)
      {
        throw new \ErrorException('Invalid repository name.');
      }

      $authz = $this->GetAuthz();

      // need rename
      if (isset($data['Source']) && isset($data['Source']['RelativePath']) && $data['Source']['RelativePath'] != '' && $data['Source']['Name'] != $data['Current']['Name'])
      {
        // check new folder name
        $shell_result = $this->SshClient->Execute('sudo bash -c \'[[ -d "'.$rootPath.'/'.$data['Current']['Name'].'" ]] && echo "OK" || echo "Not found."\'');
        if ($shell_result->Result == 'OK')
        {
          throw new \ErrorException('Repository "'.$rootPath.'/'.$data['Current']['Name'].'" already  exists. Please input other name and try again.');
        }

        // move repository
        $shell_result = $this->SshClient->Execute('sudo mv "'.$rootPath.'/'.$data['Source']['Name'].'" "'.$rootPath.'/'.$data['Current']['Name'].'"');
        if ($shell_result->Error != '')
        {
          throw new \ErrorException($shell_result->Error);
        }

        // rename section with repository rules
        if (isset($authz[$data['Source']['Name'].':/']))
        {
          $authz[$data['Current']['Name'].':/'] = $authz[$data['Source']['Name'].':/'];
          unset($authz[$data['Source']['Name'].':/']);

          // save authz
          $this->SetAuthz($authz);
        }
      }
      else
      {
        // check path
        if (isset($data['Source']) && isset($data['Source']['RelativePath']) && $data['Source']['RelativePath'] != '')
        {
          // path must exist
          $shell_result = $this->SshClient->Execute('sudo bash -c \'[[ ! -d "'.$rootPath.'/'.$data['Current']['Name'].'" ]] && sudo mkdir -p "'.$rootPath.'/'.$data['Current']['Name'].'"\'');
          if ($shell_result->Error != '')
          {
            throw new \ErrorException($shell_result->Error);
          }
        }
        else
        {
          // is new repository, path must not exist
          $shell_result = $this->SshClient->Execute('sudo bash -c \'[[ ! -d "'.$rootPath.'/'.$data['Current']['Name'].'" ]] && echo "OK" || echo "Fail"\'');
          if ($shell_result->Result != 'OK')
          {
            throw new \ErrorException('Repository "'.$rootPath.'/'.$data['Current']['Name'].'" already  exists. Please input other name and try again.');
          }
          // create
          $shell_result = $this->SshClient->Execute('sudo svnadmin create "'.$rootPath.'/'.$data['Current']['Name'].'"'); // --fs-type fsfs
          if ($shell_result->Error != '')
          {
            throw new \ErrorException('Failed to create the repository: '.$shell_result->Error);
          }
        }

        // save permissions
        if (isset($data['Current']['Permissions']) && count($data['Current']['Permissions']) > 0)
        {
          $users = $this->GetUniqueSortedLogins($authz['groups']);
          $groups = array_keys($authz['groups']);

          $authz[$data['Current']['Name'].':/'] = [];

          foreach($data['Current']['Permissions'] as $permission)
          {
            // check object name
            if ($permission['ObjectName'] != '*' && array_search($permission['ObjectName'], $users) === FALSE && array_search(substr($permission['ObjectName'], 1), $groups) === FALSE)
            {
              throw new \ErrorException('Object "'.$permission['ObjectName'].'" not found.');
            }

            // create rule
            $p = '';

            if ((bool)$permission['Read'] === TRUE)
            {
              $p .= 'r';
            }

            if ((bool)$permission['Write'] === TRUE)
            {
              $p .= 'w';
            }

            // add rule
            $authz[$data['Current']['Name'].':/'][$permission['ObjectName']] = $p;
          }

          // save authz
          $this->SetAuthz($authz);
        }

        // is new, create folder
        if (!isset($data['Source']) || !isset($data['Source']['RelativePath']) || $data['Source']['RelativePath'] == '')
        {
          $shell_result = $this->SshClient->Execute('sudo mkdir -p "'.$rootPath.'/'.$data['Current']['Name'].'"');
          if ($shell_result->Error != '')
          {
            throw new \ErrorException($shell_result->Error);
          }
        }
      }

      return ['Success' => TRUE];
    }

    /**
     * Deletes repositry.
     * 
     * @param mixed $data Repository name and delete options.
     */
    public function DeleteRespository($data)
    {
      global $config;

      if (!isset($data['name']) || $data['name'] == '')
      {
        throw new \ErrorException('Repository name is required.');
      }

      $authz = $this->GetAuthz();

      $rootPath = $config['svn_repositories'];

      if (\Nemiro\Text::EndsWith($rootPath, '/'))
      {
        $rootPath = substr($rootPath, 0, -1);
      }

      // remove folder
      $shell_result = $this->SshClient->Execute('sudo bash -c \'[[ -d "'.$rootPath.'/'.$data['name'].'" ]] && sudo rm -r -f "'.$rootPath.'/'.$data['name'].'"\'');
      if ($shell_result->Error != '')
      {
        throw new \ErrorException($shell_result->Error);
      }

      // remove from authz
      if (isset($authz[$data['name'].':/']))
      {
        unset($authz[$data['name'].':/']);
        // save authz
        $this->SetAuthz($authz);
      }

      return ['Success' => TRUE];
    }

    #endregion
    #region ..Private methods..

    /**
     * Returns authz file.
     * 
     * @return \array
     */
    private function GetAuthz()
    {
      global $config;

      $shell_result = $this->SshClient->Execute('sudo cat '.$config['svn_authz']); // /etc/apache2/dav_svn.authz
      
      if ($shell_result->Error != '')
      {
        throw new \ErrorException($shell_result->Error);
      }

      return parse_ini_string($shell_result->Result, TRUE);
    }

    private function SetAuthz($array)
    {
      global $config;

      $ini = array();

      foreach ($array as $key => $value)
      {
        if (is_array($value))
        {
          $ini[] = "[$key]";

          foreach($value as $key2 => $value2) 
          {
            $ini[] = "$key2=$value2";
          }
        }
        else 
        {
          $ini[] = "$key=$value";
        }
      }

      $ini = implode("\n", $ini);

      $shell_result = $this->SshClient->Execute("sudo bash -c 'cat <<\EOF > ".$config['svn_authz']."\n".$ini."\nEOF'");
      
      if ($shell_result->Error != '')
      {
        throw new \ErrorException($shell_result->Error);
      }
    }

    private function GetUniqueSortedLogins($groups)
    {
      $result = array_unique(array_filter(explode(',', str_replace(' ', '', implode(',', array_values($groups)))), function ($itm) { return isset($itm) && $itm != ''; }));
      ksort($result);

      return $result;
    }

    #endregion

  }

}