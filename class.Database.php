<?php
class Database
{
    protected $connection;
    public function connect($config){
        $dsn = 'mysql:dbname=' . $config['database'] . ';host=' . $config['host'] . ';port=3306';
        try {
            $this->connection = new PDO($dsn, $config['username'], $config['password']);
        } catch(PDOException $e) {
            die('Could not connect to the database:<br/>' . $e);
        }
}
    public $query;
    public $count = 0;
    public $results;

    public function select($parameters)
    {
        $fields = $parameters['fields'];
        $fieldClause = implode(", ", $fields);
        $table = $parameters['table'];
        $this->query = 'SELECT ' . $fieldClause . ' FROM `' . $table . '`';
    }
    public function where($parameters)
    {
        $column = $parameters['column'];
        $rule = $parameters['rule'];
        $value = $parameters['value'];


        if($this->count > 0 )
        {
            $this->query .= ' AND ' . $column . ' ' . $rule . " '" . $value . "'" ;
        }
        else
        {
            $this->query .= ' WHERE ' . $column . ' ' . $rule . " '" . $value . "'" ;
        }
        $this->count++;
    }

    public function insert($parameters)
    {
         $table = $parameters['table'];
         $columns = $parameters['columns'];
         $columnString = '(' . implode(', ', $columns). ')';
         $values = $parameters['values'];

         $arr = array();
         for($i=0; $i < count($values); $i++)
         {
             $arr[] = "('" . implode("', '", $values[$i]) . "')";
         }
         $valuesString = implode(', ', $arr);

         $this->query = 'INSERT INTO `' . $table . '` ' . $columnString . ' VALUES ' . $valuesString ;

    }
    public function update($parameters)
    {
        $table = $parameters['table'];
        $columns = $parameters['columns'];
        $values = $parameters['values'];

        $arr = array();
        for($i=0; $i < count($columns); $i++)
        {
            $arr[] = $columns[$i] . ' = ' . "'" . $values[$i] . "'";
        }

        $updateString = implode(', ', $arr);

        $this->query = 'UPDATE `' . $table . '` SET ' . $updateString;


    }

    public function delete($table)
    {
        $this->query = 'DELETE FROM `' . $table .'`';
    }

    public function run()
    {
        $this->count = 0;
        $this->results = $this->connection->prepare($this->query);
        $this->results->execute();

    }

    public function fetch()
    {
        $this->run();
        return $this->results->fetch(PDO::FETCH_OBJ);
    }
}