# Database

An easy to use class for Database queries in PHP.

- [API](#api)
- [Examples](#data-examples)
- [Install](#installation)

## API
```php
Database::connect($db='test',$pass='',$user='root',$host='localhost',$type='mysql');
Database::query($query, $params = array());
Database::fetchAll($query);
Database::fetchAll_safe($query);
Database::fetch_object($query);
Database::fetch_safe_object($query);
Database::num_rows($query);
```

See Below for usage.

### Connecting
```php
Database::connect('database','password','username','host');
```

Note the reverse parameters. We do this because of the ommitable variables.

### Query
```php
$query = Database::query("SELECT * FROM table WHERE id = ?", [$_GET['id']]);
```

This is a query with bind parameters.
First argument is the statement, second argument is an array of parameters (optional)

Note: We passed the query into a variable for later re-use.

### Fetch and **Safe Fetch**
This is regular returned object. You still need to apply htmlspecialchars yourself.
```php
$table = Database::fetch_object($query);
```

This is safe returned object. htmlspecialchars is applied to all the objects's properties.
```php
$table = Database::fetch_safe_object($query);
```

### Num Rows
```php
Database::num_rows($query); # Equivalent of $pdo->rowCount();
```

### Data Examples
```php
# Loop Objects
while($entry = Database::fetch_safe_object($query))
{
	# Because of fetch_safe_object we don't need to apply htmlspecialchars
    echo '<a href="page?id='.$entry->id.'">'.$entry->name.'</a><br />';
}
# Single Object
$entry = Database::fetch_safe_object($query);
echo $entry->name;

# Loop Objects Using Foreach instead with Fetchall
foreach(Database::fetchAll_safe($query) as $entry)
{
	# Because of fetchAll_safe we don't need to apply htmlspecialchars
    echo '<a href="page?id='.$entry->id.'">'.$entry->name.'</a><br />';
}
# Single Object
$entry = Database::fetchAll_safe($query);
echo $entry[0]->name;
```

## Installation

via Composer:
```json
{
    "require": {
        "modularr/database": "1.*"
    }
}
```
Then run:

	composer update

Or install like so:

	composer require modularr/database

make sure you have:
```php
require 'vendor/autoload.php';
```

Manual:

1. Download [Release](https://github.com/Modularr/Database/releases) Or copy file manually
2. Include **Database.php** (found under **src/**)
3. Check out the example
