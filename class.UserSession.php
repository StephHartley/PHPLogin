<?php

require_once('class.DatabaseSession.php');

class UserSession extends DatabaseSession {

    public function logIn($username, $password)
    {
        if($this->isLoggedIn()){
            $this->logOut();
        }


        $parameters = array(
            'fields' => array('users_id'),
            'table' => 'users'
        );
        $this->dbConn->select($parameters);

        $whereParamsOne = array(
            'column' => 'users_username',
            'rule' => '=',
            'value' => $username
        );
        $whereParamsTwo = array(
            'column' => 'users_password',
            'rule' => '=',
            'value' => $password
        );

        $this->dbConn->where($whereParamsOne);
        $this->dbConn->where($whereParamsTwo);

        $result = $this->dbConn->fetch();

        if(is_object($result) && $result->users_id > 0)
        {
             $_SESSION['logged_in'] = true;
             $_SESSION['id'] = $result->users_id ;
        }
        else
        {
            $_SESSION['logged_in'] = false;
        }

    }

    public function logOut()
    {
        $id = session_id();
        $this->destroy($id);
        unset($_SESSION);

        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
        session_destroy();
    }

    public function isLoggedIn()
    {
        if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'])
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public function username()
    {
        if(isset($_SESSION['id']))
        {
            $parameters = array(
              'fields' => array('users_username'),
              'table' => 'users'
            );

            $this->dbConn->select($parameters);

            $whereParams = array(
               'column' => 'users_id',
               'rule' => '=',
               'value' => $_SESSION['id']
            );

            $this->dbConn->where($whereParams);
            $result = $this->dbConn->fetch();

            return $result->users_username;
        }
    }

    public function authorisation()
    {
        if(isset($_SESSION['id']))
        {
            $parameters = array(
                'fields' => array('users_authorisation'),
                'table' => 'users'
            );

            $this->dbConn->select($parameters);

            $whereParams = array(
                'column' => 'users_id',
                'rule' => '=',
                'value' => $_SESSION['id']
            );

            $this->dbConn->where($whereParams);
            $result = $this->dbConn->fetch();

            return $result->users_authorisation;
        }

    }

}