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

  \Nemiro\App::IncludeFile('~/users/models/Group.php');
  \Nemiro\App::IncludeFile('~/users/models/User.php');
  \Nemiro\App::IncludeFile('~/users/models/CreateUser.php');
  \Nemiro\App::IncludeFile('~/users/models/PagedList.php');
  \Nemiro\App::IncludeFile('~/users/models/UsersList.php');
  \Nemiro\App::IncludeFile('~/users/models/AccountUpdate.php');
  \Nemiro\App::IncludeFile('~/ssh/api.php');

  /**
   * API of SmallServerAdmin.
   * 
   * @author     Aleksey Nemiro <aleksey@nemiro.ru>
   * @copyright  Aleksey Nemiro, 2016
   */
  class Users
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

    #region ..Public methods..

    public function GetGroups()
    {
      return $this->GetGroupsList();
    }

    public function GetUsers($data)
    {
      return $this->GetUsersList
      (
        isset($data['page']) ? (int)$data['page'] : 1,
        isset($data['limit']) ? (int)$data['limit'] : NULL,
        isset($data['search']) ? $data['search'] : NULL
      );
    }

    public function GetUserByLogin($data)
    {
      if (!isset($data['login']) || $data['login'] == '')
      {
        throw new \ErrorException('Login is required. Value cannot be empty.');
      }
      // get user data
      $user = $this->GetUsersList(1, 0, $data['login'])->Items;
      
      if (count($user) == 1)
      {
        $user = $user[0];
      }
      else if (count($user) > 1)
      {
        foreach($user as $u)
        {
          if ($u->Login == $data['login'])
          {
            $user = $u;
            break;
          }
        }

        if (count($user) > 1)
        {
          $user = NULL;
        }
      }
      else if (count($user) == 0)
      {
        $user = NULL;
      }

      if ($user == NULL) 
      {
        throw new \ErrorException('User "' . $data['login'] . '" not found.');
      }

      // get user groups
      $shell_result = $this->SshClient->Execute('sudo groups '.$user->Login)->Result;
      $user->Groups = explode(' ', trim(explode(':', $shell_result)[1]));
      
      return $user;
    }
    /**
     * Creates a new user.
     * 
     * @param array $data The user data.
     */
    public function CreateUser($data)
    {
      $gecos = 
        \Nemiro\Text::RemoveChars(\Nemiro\Text::EscapeString($data['FullName'], ['"', ',']), [':', chr(13), chr(10)]).','.
        \Nemiro\Text::RemoveChars(\Nemiro\Text::EscapeString($data['Address'], ['"', ',']), [':', chr(13), chr(10)]).','.
        \Nemiro\Text::RemoveChars(\Nemiro\Text::EscapeString($data['PhoneWork'], ['"', ',']), [':', chr(13), chr(10)]).','.
        \Nemiro\Text::RemoveChars(\Nemiro\Text::EscapeString($data['PhoneHome'], ['"', ',']), [':', chr(13), chr(10)]).','.
        \Nemiro\Text::RemoveChars(\Nemiro\Text::EscapeString($data['Email'], ['"', ',']), [':', chr(13), chr(10)]);

      $cmd = 
      'sudo adduser '.$data['Login'].' '.
      '--quiet '. // --force-badname
      '--shell '.$data['Shell'].
      ($data['NoCreateHome'] ? ' --no-create-home ' : ' ').
      ($data['IsSystem'] ? '--system ' : ' ').
      '--disabled-password --gecos "'.$gecos.'" && '.
      'echo "OK"';

      $shell_result = $this->SshClient->Execute($cmd);

      if ($shell_result->Result != 'OK')
      {
        throw new \ErrorException($shell_result->Error);
      }

      // set password
      // '(echo "'.\Nemiro\Text::EscapeString($data['Login']).':'.\Nemiro\Text::EscapeString($data['Password']).'" | sudo chpasswd) && echo "OK"'
      $login = \Nemiro\Text::EscapeString($data['Login']);
      $password = \Nemiro\Text::EscapeString($data['Password']);
      $shell_result = $this->SshClient->Execute('sudo bash -c \'echo -e "'.$password.'\n'.$password.'" | passwd "'.$login.'"\' && echo "OK"');

      if ($shell_result->Result != 'OK')
      {
        throw new \ErrorException('Fail to set password for '.$data['Login'].': '.$shell_result->Error);
      }

      // add to groups
      if (isset($data['Groups']) && count($data['Groups']) > 0)
      {
        $shell_result = $this->SshClient->Execute('sudo usermod --groups '.implode(',', $data['Groups']).' '.$data['Login'].' && echo "OK"');
      }

      if ($shell_result->Result != 'OK')
      {
        throw new \ErrorException('Unable to add user '.$data['Login'].' to groups '.implode(', ', $data['Groups']).': '.$shell_result->Error);
      }

      $this->GetUserByLogin(['login' => $data['Login']]);
    }

    /**
     * Deletes user.
     * 
     * @param mixed $data User to remove.
     */
    public function DeleteUser($data)
    {
      $cmd = 'sudo userdel '.((bool)$data['RemoveHome'] ? '--remove ' : '').$data['Login'].' && echo "OK"';

      $shell_result = $this->SshClient->Execute($cmd);

      if ($shell_result->Result != 'OK')
      {
        throw new \ErrorException($shell_result->Error);
      }

      return ['Success' => TRUE];
    }

    /**
     * Updates user GECOS data.
     * 
     * @param mixed $data The user data.
     */
    public function UpdateUserGECOS($data)
    {
      $gecos = \Nemiro\Text::RemoveChars(\Nemiro\Text::EscapeString($data['FullName'], ['"', ',']), [':', chr(13), chr(10)]).','.
               \Nemiro\Text::RemoveChars(\Nemiro\Text::EscapeString($data['Address'], ['"', ',']), [':', chr(13), chr(10)]).','.
               \Nemiro\Text::RemoveChars(\Nemiro\Text::EscapeString($data['PhoneWork'], ['"', ',']), [':', chr(13), chr(10)]).','.
               \Nemiro\Text::RemoveChars(\Nemiro\Text::EscapeString($data['PhoneHome'], ['"', ',']), [':', chr(13), chr(10)]).','.
               \Nemiro\Text::RemoveChars(\Nemiro\Text::EscapeString($data['Email'], ['"', ',']), [':', chr(13), chr(10)]);

      $cmd = 'sudo usermod --comment "'.$gecos.'" '.$data['Login'].' && echo "OK"';

      $shell_result = $this->SshClient->Execute($cmd);

      if ($shell_result->Result != 'OK')
      {
        throw new \ErrorException($shell_result->Error);
      }

      return ['Success' => TRUE];
    }
    
    /**
     * Updates user groups.
     */
    public function UpdateUserGroups($data)
    {
      $shell_result = $this->SshClient->Execute('sudo usermod --groups '.implode(',', $data['Groups']).' '.$data['Login'].' && echo "OK"');

      if ($shell_result->Result != 'OK')
      {
        throw new \ErrorException('Failed to to update the list of user groups: '.$shell_result->Error);
      }

      return ['Success' => TRUE];
    }

    /**
     * Updates user account.
     * 
     * @param mixed $data Data to update.
     */
    public function UpdateUserAccount($data)
    {
      $shell_result = $this->SshClient->Execute('sudo grep "'.$data['Login'].'" /etc/passwd');

      if ($shell_result->Error != '')
      {
        throw new \ErrorException($shell_result->Error);
      }

      $user = $this->ParseUser($shell_result->Result);

      // change username
      if (isset($data['SetLogin']) && (bool)$data['SetLogin'] && $data['NewLogin'] != $data['Login'])
      {
        // rename
        $shell_result = $this->SshClient->Execute('sudo usermod --login '.$data['NewLogin'].' '.$data['Login'].' && echo "OK"');

        if ($shell_result->Result != 'OK')
        {
          throw new \ErrorException('Failed to change the user name: '.$shell_result->Error);
        }

        // change home path
        if ($user->HomePath != ''){
          $shell_result = $this->SshClient->Execute('sudo usermod --home /home/'.$data['NewLogin'].' --move-home '.$data['NewLogin'].' && echo "OK"');

          if ($shell_result->Result != 'OK')
          {
            throw new \ErrorException('Failed to change the user home directory: '.$shell_result->Error);
          }
        }

        $data['Login'] = $data['NewLogin'];
      }

      // change password
      if (isset($data['SetPassword']) && (bool)$data['SetPassword'] && isset($data['NewPassword']) && $data['NewPassword'] != '')
      {
        // $shell_result = $this->SshClient->Execute('sudo usermod --password '.$data['NewPassword'].' '.$data['Login'].' && echo "OK"');
        $shell_result = $this->SshClient->Execute('sudo bash -c \'echo -e "'.$data['NewPassword'].'\n'.$data['NewPassword'].'" | passwd "'.$data['Login'].'"\' && echo "OK"');

        if ($shell_result->Result != 'OK')
        {
          throw new \ErrorException('Failed to set a new password: '.$shell_result->Error);
        }
      }

      // change shell
      if (isset($data['SetShell']) && (bool)$data['SetShell'] && isset($data['NewShell']) && $data['NewShell'] != '')
      {
        $shell_result = $this->SshClient->Execute('sudo usermod --shell '.$data['NewShell'].' '.$data['Login'].' && echo "OK"');

        if ($shell_result->Result != 'OK')
        {
          throw new \ErrorException('Failed to set a new shell: '.$shell_result->Error);
        }
      }

      return ['Success' => TRUE];
    }

    #endregion
    #region ..Private methods..

    /**
     * Returns list of users.
     * 
     * @param string $search The user filter string.
     * @param int $page Current page number.
     * @param int $dataPerPage The number of data on a single page.
     * 
     * @return \Models\UsersList
     */
    private function GetUsersList($page, $dataPerPage, $search)
    {
      if (!isset($page) || (int)$page <= 0) { $page = 1; }
      if (!isset($dataPerPage) || (int)$dataPerPage <= 0) { $dataPerPage = PHP_INT_MAX; }

      $page--;

      $command = [];
    
      if (isset($search) && $search != '')
      {
        $command[] = 'sudo grep "'.\Nemiro\Text::EscapeString($search).'*" /etc/passwd | wc -l';
        $command[] = 'sudo cat /etc/passwd | grep "'.\Nemiro\Text::EscapeString($search).'*" | sudo sed -n "'.($page == 0 ? 1 : ($page * $dataPerPage) + 1).','.(($page * $dataPerPage) + $dataPerPage).'"p';
      }
      else
      {
        $command[] = 'sudo wc -l < /etc/passwd';
        $command[] = 'sed -n "'.($page == 0 ? 1 : ($page * $dataPerPage) + 1).','.(($page * $dataPerPage) + $dataPerPage).'"p /etc/passwd';
      }

      $shell_result = $this->SshClient->Execute($command);

      $result = new \Models\UsersList();
      $result->TotalRecords = (int)$shell_result[0]->Result;
      $result->CurrentPage = (int)$page + 1;
      $result->DataPerPage = (int)$dataPerPage;

      $users = preg_split('/[\r\n]+/', $shell_result[1]->Result, -1, PREG_SPLIT_NO_EMPTY);

      foreach ($users as $user)
      {
        $result->Items[] = $this->ParseUser($user);
      }

      return $result;
    }

    /**
     * Parses user from string.
     * 
     * @param string $value 
     * @return \Models\User
     */
    private function ParseUser($value)
    {
      // login : password : UID : GID : GECOS : home : shell
      // aleksey:x:1000:1000:Aleksey Nemiro,,,:/home/aleksey:/bin/bash
      // GECOS: full name, address, work phome, home phone, email
      $fields = explode(':', $value);
      $u = new \Models\User();
      $u->Login = isset($fields[0]) ? $fields[0] : NULL;
      $u->Password = isset($fields[1]) ? $fields[1] : NULL;
      $u->Id = isset($fields[2]) ? $fields[2] : NULL;
      $u->GroupId = isset($fields[3]) ? $fields[3] : NULL;
      $u->HomePath = isset($fields[5]) ? $fields[5] : NULL;
      $u->Shell = isset($fields[6]) ? $fields[6] : NULL;

      // GECOS
      if (isset($fields[4]) && $fields[4] != '')
      {
        $fields = explode(',', str_replace('\,', chr(1), $fields[4]));
        $u->FullName = isset($fields[0]) ? str_replace(chr(1), ',', $fields[0]) : NULL;
        $u->Address = isset($fields[1]) ? str_replace(chr(1), ',', $fields[1]) : NULL;
        $u->PhoneWork = isset($fields[2]) ? str_replace(chr(1), ',', $fields[2]) : NULL;
        $u->PhoneHome = isset($fields[3]) ? str_replace(chr(1), ',', $fields[3]) : NULL;
        $u->Email = isset($fields[4]) ? str_replace(chr(1), ',', $fields[4]) : NULL;
      }

      return $u;
    }

    /**
     * Returns list of groups.
     * 
     * @return \Models\Group[]
     */
    private function GetGroupsList()
    {
      $shell_result = $this->SshClient->Execute('sudo cat /etc/group')->Result;
      $groups = preg_split('/[\r\n]+/', $shell_result, -1, PREG_SPLIT_NO_EMPTY);
      $result = array();

      foreach ($groups as $group)
      {
        // name : password : GID : member1,member2...
        // mysql:x:117:
        $fields = explode(':', $group);
        $g = new \Models\Group();
        $g->Name = $fields[0];
        $g->Password = $fields[1];
        $g->Id = $fields[2];
        $g->Members = explode(',', $fields[3]);

        $result[] = $g;
      }
    
      return $result;
    }

    #endregion

  }

}