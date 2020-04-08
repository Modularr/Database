<?php if (!class_exists('Database')) { include 'Database.php'; }
class DB
{
    public static $db;
    public static function Facade($obj)
    {
        self::$db = $obj;
    }
    public static function connect($db=null,$pass=null,$user=null,$host=null,$type=null)
    {
        self::$db = new Database($db,$pass,$user,$host,$type);
    }
    public static function setPdo($obj)
    {
        return self::$db->setPdo($obj);
    }
    public static function getPdo()
    {
        return self::$db->getPdo();
    }
    public static function quote($string,$remove_quotes=false)
    {
        return self::$db->quote($string,$remove_quotes);
    }
    public static function query($query, $params = array())
    {
        return self::$db->query($query, $params);
    }
    public static function fetch_assoc($query)
    {
        return self::$db->fetch_assoc($query);
    }
    public static function fetch_safe_assoc($query)
    {
        return self::$db->fetch_safe_assoc($query);
    }
    public static function fetch_object($query)
    {
        return self::$db->fetch_object($query);
    }
    public static function fetch_safe_object($query)
    {
        return self::$db->fetch_safe_object($query);
    }
    public static function fetchAll($query)
    {
        return self::$db->fetchAll($query);
    }
    public static function fetchAll_safe($query)
    {
        return self::$db->fetchAll_safe($query);
    }
    public static function num_rows($query)
    {
        return self::$db->num_rows($query);
    }
    public static function insert_id()
    {
        return self::$db->insert_id($query);
    }

    public static function walk_recursive($obj, $closure)
    {
         self::$db->walk_recursive($obj, $closure);
    }
}
