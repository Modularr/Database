<?php
class Database
{
    public $pdo;
    private $query,$res;
    private $host   = 'localhost';
    private $type   = 'mysql';
    private $dbname = 'test';
    private $user   = 'root';
    private $pass   = '';
    public function __construct($data=null,$pass=null,$user=null,$host=null,$type=null)
    {
        if( isset($data) || isset($pass) || isset($user) || isset($host) || isset($type) ) {
            if(is_array($data)) {
                if(isset($data['dbname'])) { $this->dbname = $data['dbname']; }
                if(isset($data['pass']))   { $this->pass   = $data['pass'];   }
                if(isset($data['user']))   { $this->user   = $data['user'];   }
                if(isset($data['host']))   { $this->host   = $data['host'];   }
                if(isset($data['type']))   { $this->type   = $data['type'];   }
            } else {
                if(isset($data)) { $this->dbname = $data; }
                if(isset($pass)) { $this->pass   = $pass; }
                if(isset($user)) { $this->user   = $user; }
                if(isset($host)) { $this->host   = $host; }
                if(isset($type)) { $this->type   = $type; }
            }
            $this->connect();
        }
    }
    public function connect($data=null,$pass=null,$user=null,$host=null,$type=null)
    {
        if( isset($data) || isset($pass) || isset($user) || isset($host) || isset($type) ) {
            if(isset($data)) { $this->dbname = $data; }
            if(isset($pass)) { $this->pass   = $pass; }
            if(isset($user)) { $this->user   = $user; }
            if(isset($host)) { $this->host   = $host; }
            if(isset($type)) { $this->type   = $type; }
        }
        if(isset($this->type) || isset($this->host) || isset($this->$user) || isset($this->pass) || isset($this->dbname)) {
            try {
                $this->pdo = new PDO($this->type.':host='.$this->host.';dbname='.$this->dbname, $this->user, $this->pass);
                $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $this->pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            } catch(PDOException $e) {
                echo 'ERROR: ' . $e->getMessage();
            }
        }
    }
    public function setPdo($obj)
    {
        $this->pdo = $obj;
        $this->pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    }
    public function getPdo()
    {
        return $this->pdo;
    }
    public function quote($string,$remove_quotes=false)
    {
        $data = $this->pdo->quote($string);
        if($remove_quotes) {
            $data = substr($data, 1, -1);
        }
        return $data;
    }
    public function query($query, $params = array())
    {
        $this->query = $this->pdo->prepare($query);
        if(!empty($params)) {
            $this->query->execute($params);
        } else {
            $this->query->execute();
        }

        return $this->query;
    }
    public function fetch_assoc($query)
    {
        return $query->fetch(PDO::FETCH_ASSOC);
    }
    public function fetch_safe_assoc($query)
    {
        $res = $this->walk_recursive($query->fetch(PDO::FETCH_ASSOC), 'htmlspecialchars');
        return $res;
    }
    public function fetch_object($query)
    {
        return $query->fetch(PDO::FETCH_OBJ);
    }
    public function fetch_safe_object($query)
    {
        $res = $this->walk_recursive($query->fetch(PDO::FETCH_OBJ), 'htmlspecialchars');
        return $res;
    }
    public function fetchAll($query)
    {
        return $query->fetchAll(PDO::FETCH_OBJ);
    }
    public function fetchAll_safe($query)
    {
        $res = $this->walk_recursive($query->fetchAll(PDO::FETCH_OBJ), 'htmlspecialchars');
        return $res;
    }
    public function num_rows($query)
    {
        return $query->rowCount();
    }
    public function insert_id()
    {
        $id = $this->pdo->lastInsertId();
        return $id;
    }
    public function insert($table, $data)
    {
        $keys = ""; $values = "";
        foreach($data as $key => $value) {
            $keys .= "$key,";
        } $keys = substr($keys, 0, -1);
        foreach($data as $key => $value) {
            $values .= $this->quote($value).",";
        } $values = substr($values, 0, -1);
        $statement = "INSERT INTO $table ($keys) VALUES ($values)";
        $this->query($statement);
    }
    public function update($table, $data)
    {
        $values = "";
        foreach($data as $key => $value) {
            $values .= "$key = ".$this->quote($value).", ";
        } $values = substr($values, 0, -2);
        $statement = "UPDATE $table SET $values";
        $this->query($statement);
    }
    public function store($table, $match='id', $data)
    {
        $query = $this->query("SELECT $match FROM $table WHERE $match = ".$this->quote($data['url']));
        if($this->num_rows($query) == 0) {
            $this->insert($table, $data);
        } else {
            $this->update($table, $data);
        }
    }
    public function walk_recursive($obj, $closure)
    {
        if ( is_object($obj) )
        {
            $newObj = new stdClass();
            foreach ($obj as $property => $value)
            {
                $newProperty = $closure($property);
                $newValue = $this->walk_recursive($value, $closure);
                $newObj->$newProperty = $newValue;
            }
            return $newObj;
        }
        elseif ( is_array($obj) )
        {
            $newArray = array();
            foreach ($obj as $key => $value)
            {
                $key = $closure($key);
                $newArray[$key] = $this->walk_recursive($value, $closure);
            }
            return $newArray;
        }
        else
        {
            return $closure($obj);
        }
    }
}
