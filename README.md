# Database

An easy to use class for Database queries in PHP.

- [API](#api)
- [Connections](#connections)
- [Examples](#data-examples)
- [Install](#installation)

## API
```php
DB::connect($db='test',$pass='',$user='root',$host='localhost',$type='mysql');
DB::getPdo();
DB::setPdo($db);
DB::quote();
DB::query($query, $params = array());
DB::fetchAll($query);
DB::fetchAll_safe($query);
DB::fetch_assoc($query);
DB::fetch_safe_assoc($query);
DB::fetch_object($query);
DB::fetch_safe_object($query);
DB::num_rows($query);
```

See Below for usage.

## Connections

Our class lets you connect to PDO whichever way you choose, This maximizes flexibility by letting you simply give us your already existing object, or by using our class as your primary Database package and asking for the PDO object if you need to pass it to other libraries.


### Connection Syntax

We let you connect using multiple syntax to make it easy to use, available both in the `__construct()` as well as `connect()`

You can either use an associative array or the default which uses reverse order to let you define the most important values first, and lets you default irrelevant values such as host or type to it's defaults ('mysql' and 'localhost')

### Via Instantiation

```php
$db = new Database($db='test',$pass='',$user='root',$host='localhost',$type='mysql'); # Default Syntax
$db = new Database(['host'=>$host,'dbname'=>$database,'user'=>$username,'pass'=>$password]); # Alternative Syntax
```

### Via Method

```php
$db->connect(DB,PASS,USER,HOST); # Establish a Connection With PDO
```

### Via Existing PDO Object

```php
$db->setPdo($pdo); # Assign PDO Connection to the Database Class
```

## Usage and Use Case

A facade is optional but has all the same functionality of the main class.

# Regular Use Case

```php
$db = new Database(DB,PASS,USER,HOST); # Establish a Connection
$query = $db->query("SELECT * FROM table");
while($item = $db->fetch_object($query))
{
    echo'#'.htmlspecialchars($item->id).': '.htmlspecialchars($item->name).'<br>';
}
```

# Facades Use Case

### Create Facade from PDO Object

```php
$db = new Database(DB,PASS,USER,HOST); # Establish a Connection
DB::Facade($db); # Initiate Database object Facade
```

### Connect Via Facade

```php
DB::connect('database','pass','user','host');
```

### Use Case

```php
$query = DB::query("SELECT * FROM table");
while($item = DB::fetch_object($query))
{
    echo'#'.htmlspecialchars($item->id).': '.htmlspecialchars($item->name).'<br>';
}
```

### Query
```php
$query = DB::query("SELECT * FROM table WHERE id = ?", [$_GET['id']]);
```

This is a query with bind parameters.
First argument is the statement, second argument is an array of parameters (optional)

Note: We passed the query into a variable for later re-use.

### Fetch and **Safe Fetch**
This is regular returned object. You still need to apply htmlspecialchars yourself.
```php
$table = DB::fetch_object($query);
```

This is safe returned object. htmlspecialchars is applied to all the objects's properties.
```php
$table = DB::fetch_safe_object($query);
```

### Num Rows
```php
DB::num_rows($query); # Equivalent of $pdo->rowCount();
```

### Data Examples
```php
# Loop Objects
while($entry = DB::fetch_safe_object($query))
{
	# Because of fetch_safe_object we don't need to apply htmlspecialchars
    echo '<a href="page?id='.$entry->id.'">'.$entry->name.'</a><br />';
}
# Single Object
$entry = DB::fetch_safe_object($query);
echo $entry->name;

# Loop Objects Using Foreach instead with Fetchall
foreach(DB::fetchAll_safe($query) as $entry)
{
	# Because of fetchAll_safe we don't need to apply htmlspecialchars
    echo '<a href="page?id='.$entry->id.'">'.$entry->name.'</a><br />';
}
# Single Object
$entry = DB::fetchAll_safe($query);
echo $entry[0]->name;
```

## Installation

via Composer:

    composer require modularr/database

Or install like so:
```json
{
    "require": {
        "modularr/database": "2.*"
    }
}
```

Manual:

1. Download [Release](https://github.com/Modularr/Database/releases) Or copy file manually
2. Include **Main.php** found under **src/** (this includes both **Database.php** and **Facade.php**)
