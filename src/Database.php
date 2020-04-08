<?php
class Database
{
    public static $pdo,$query,$res;
    public static function connect($db='test',$pass='',$user='root',$host='localhost',$type='mysql')
    {
        try {
            self::$pdo = new PDO("$type:host=$host;dbname=$db", $user, $pass);
            self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            echo 'ERROR: ' . $e->getMessage();
        }
    }
    public static function query($query, $params = array())
    {
        self::$query = self::$pdo->prepare($query);
        if(!empty($params)) {
            self::$query->execute($params);
        } else {
            self::$query->execute();
        }
        
        return self::$query;
    }
    public static function fetch_object($query)
    {
        return $query->fetch(PDO::FETCH_OBJ);
    }
    public static function fetch_safe_object($query)
    {
        $res = self::walk_recursive($query->fetch(PDO::FETCH_OBJ), 'htmlspecialchars');
        return $res;
    }
    public static function num_rows($query)
    {
        return $query->rowCount();
    }
    
    public static function walk_recursive($obj, $closure)
    {
        if ( is_object($obj) )
        {
            $newObj = new stdClass();
            foreach ($obj as $property => $value)
            {
                $newProperty = $closure($property);
                $newValue = self::walk_recursive($value, $closure);
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
                $newArray[$key] = self::walk_recursive($value, $closure);
            }
            return $newArray;
        }
        else
        {
            return $closure($obj);
        }
    }
}
