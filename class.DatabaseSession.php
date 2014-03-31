<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Stephanie
 * Date: 20/11/2013
 * Time: 09:20
 * To change this template use File | Settings | File Templates.
 */

class DatabaseSession {
    protected $dbConn;

    public function __construct(Database $dbConn)
    {
        date_default_timezone_set('Europe/London');
        $this->dbConn = $dbConn;
        $details = array(
            'host' => 'localhost',
            'username' => 'h019047b',
            'password' => 'h019047b',
            'database' => 'h019047b'
        );
        $this->dbConn->connect($details);
        session_set_save_handler(
            array($this, 'open'),
            array($this, 'close'),
            array($this, 'read'),
            array($this, 'write'),
            array($this, 'destroy'),
            array($this, 'gc')
        );
        session_start();
    }
    public function open($save_path, $name)
    {
        return true;
    }

    public function close()
    {
        return true;
    }

    public function read($session_id)
    {
        $parameters = array(
          'fields' => array('data'),
          'table' => 'sessions'
        );
        $this->dbConn->select($parameters);

        $whereParams = array(
            'column' => 'id',
            'rule' => '=',
            'value' => $session_id
        );
        $this->dbConn->where($whereParams);

        $result = $this->dbConn->fetch();
        if(is_object($result))
        {
            return $result->data;
        }

    }

    public function write($session_id, $session_data)
    {
        $parameters = array(
            'fields' => array('id'),
            'table' => 'sessions'
        );
        $this->dbConn->select($parameters);

        $whereParams = array(
            'column' => 'id',
            'rule' => '=',
            'value' => $session_id
        );
        $this->dbConn->where($whereParams);
        $result = $this->dbConn->fetch();

        if(is_object($result))
        {
            $updateParameters = array(
                'columns'=>array('data'),
                'values'=>array($session_data),
                'table'=>'sessions'
            );
            $this->dbConn->update($updateParameters);
            $this->dbConn->run();
        }
        else
        {
            $date = time();
            $parameters = array(
                'columns' => array('id', 'data', 'date_created'),
                'values'=>array(array($session_id, $session_data, $date)),
                'table' => 'sessions'
            );
            $this->dbConn->insert($parameters);

            return $this->dbConn->run();
        }
    }

    public function destroy($session_id)
    {

        $this->dbConn->delete('sessions');

        $whereParams = array(
            'column' => 'id',
            'rule' => '=',
            'value' => $session_id
        );

        $this->dbConn->where($whereParams);

        return $this->dbConn->run();
    }

    public function gc($maxlifetime)
    {
        $expectedTime = time() - $maxlifetime;
        $whereParams = array(
            'column' => 'date_created',
            'rule' => '<',
            'value' => $expectedTime
        );

        $this->dbConn->delete('sessions');
        $this->dbConn->where($whereParams);
        $this->dbConn->run();
        return true;
    }
}