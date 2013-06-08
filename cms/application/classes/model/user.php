<?php

/*
 * user.php
 * 
 * Copyright 2012 Marcin Biernacki <phisikus@o2.pl>
 * 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
 * MA 02110-1301, USA.
 * 
 * 
 */

defined('SYSPATH') or die('No direct script access.');

class Model_User extends Model_Auth_User
{

    protected $_table_columns = array(
        'id' => array('type' => 'int', 'is_nullable' => false),
        'email' => array('type' => 'string', 'is_nullable' => false),
        'username' => array('type' => 'string', 'is_nullable' => false),
        'password' => array('type' => 'string', 'is_nullable' => false),
        'logins' => array('type' => 'int', 'is_nullable' => false),
        'last_login' => array('type' => 'int', 'is_nullable' => false),
        'full_name' => array('type' => 'string', 'is_nullable' => true),
    );

    protected $has_and_belongs_to_many = array('roles');
    public $_table_name = 'users';

    /*
     *
     * Users
     *
     */

    public function getUser($id)
    {
        if ($id > 0)
            return ORM::factory('user')->where('id', '=', $id)->find();
        else
            return false;
    }

    public function getAll()
    {
        return ORM::factory('user')->find_all();
    }

    public function getUserByUsername($username)
    {
        return ORM::factory('user')->where('username', '=', $username)->find();
    }


    public function getAllUsers($page_size, $page_number)
    {
        if ($page_number == 0)
            return ceil(ORM::factory('user')->count_all() / $page_size);
        else {
            $offset = $page_size * ($page_number - 1);
            return ORM::factory('user')->limit($page_size)->offset($offset)->find_all();
        }
    }

    public function getUsersLike1($field, $value)
    {
        return ORM::factory('user')->where($field, 'like', '%' . $value . '%')->find_all();
    }

    public function getUsersWhere1($field, $value)
    {
        return ORM::factory('user')->where($field, '=', $value)->find_all();
    }

    public function getAllUsersSimpleSearch($page_size, $page_number, $se)
    {
        $se = strToLower($se);

        if ($page_number == 0) {
            $result = ORM::factory('user')->where('username', 'like', '%' . $se . '%')->find_all();
            if (count($result) == 0) {
                $result = ORM::factory('user')->where('full_name', 'like', '%' . $se . '%')->find_all();
                if (count($result) == 0)
                    $result = ORM::factory('user')->where('email', 'like', '%' . $se . '%')->find_all();
            }
            return ceil(count($result) / $page_size);
        } else {
            $offset = $page_size * ($page_number - 1);
            $result = ORM::factory('user')->where('username', 'like', '%' . $se . '%')
                ->limit($page_size)->offset($offset)->find_all();
            if (count($result) == 0) {
                $result = ORM::factory('user')->where('full_name', 'like', '%' . $se . '%')
                    ->limit($page_size)->offset($offset)->find_all();
                if (count($result) == 0)
                    $result = ORM::factory('user')->where('email', 'like', '%' . $se . '%')
                        ->limit($page_size)->offset($offset)->find_all();
            }
            return $result;
        }


    }

    public function isUser($username)
    {
        $res = ORM::factory('user')->where('username', '=', $username)->find();
        if (!empty($res))
            return $res->id;
        else
            return 0;
    }

    public function isUserEmail($email)
    {
        $res = ORM::factory('user')->where('email', '=', $email)->find();
        if (!empty($res))
            return $res->id;
        else
            return 0;
    }


    public function getUserRole($id)
    {
        return ORM::factory('role')->where('user_id', '=', $id)->find_all();
    }

    public function isUserRole($id, $idr)
    {
        $user = ORM::factory('user')->where('id', '=', $id)->find();
        return $user->has('roles', ORM::factory('role')->where('id', '=', $idr)->find());
    }

    public function setUserProfile($id, $username, $email, $full_name, $password = "")
    {
        if ($id != 0) $user = ORM::factory('user')->where('id', '=', $id)->find();
        else {
            $user = ORM::factory('user');

        }

        $user->username = $username;
        $user->email = $email;
        $user->full_name = $full_name;
        if ($password != "") $user->password = $password;


        $user->save();


        if ($id == 0) {
            $uz = ORM::factory('user')->where('username', '=', $username)->find();
            $uz->addRole('login');
        }


        return true;
    }

    public function setUserPassword($id, $pass)
    {
        if ($id != 0) $user = ORM::factory('user')->where('id', '=', $id)->find();
        else $user = ORM::factory('user');
        $user->password = $pass;
        $user->save();
    }


    public function deleteUser($id)
    {
        if ($id != 0) {
            if (ORM::factory('user')->where('id', '=', $id)->count_all() > 0) {
                $user = ORM::factory('user')->where('id', '=', $id)->find();

                // delete information about roles
                foreach ($user->roles->find_all() as $role)
                    $user->remove('roles', $role);

                $user->delete();
                return true;

            } else
                return false;
        }
    }

    /*
     *
     * Roles
     *
     */

    public function getRole($id)
    {
        return ORM::factory('role')->where('id', '=', $id)->find();
    }

    public function getRoles()
    {
        return ORM::factory('role')->find_all();
    }

    public function hasUserRole($id, $idr)
    {
        if ($id != 0) {
            $userz = ORM::factory('user')->where('id', '=', $id)->find();
            return $userz->has('roles', $idr);
        }

    }

    public function getUserRoles($id)
    {
        if ($id != 0) {
            $userz = ORM::factory('user')->where('id', '=', $id)->find();
            return $userz->roles->find_all();
        }
    }

    public function addRole($role_name)
    {
        $this->add('roles', ORM::factory('role', array('name' => $role_name)));
        $this->save();

    }

}

?>
