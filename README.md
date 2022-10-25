# README #

Simple Redis Abstraction Layer

<hr>

### Installation with composer
Install library via composer
```
composer require tsk/simple-redis-dal
```

### Initialization
Simple initialization
```php
$db = new hooli("127.0.0.0", "password");
```
### Dealing with Hashes
Create a hash / Store data into a hash (record types structured as collections of field-value pairs)
```php
$data = [
    "firstName" => "Mary",
    "lastName" => "Poppins",
    "email" => "marypoppins@email.com",
    "location" => "USA, New York"
];
$HashName = 'marypoppins@email.com';
$db->createhash($HashName, $data);
```
Check if a value exists in a hash
```php
$HashName = 'marypoppins@email.com';
$db->checkhash($HashName, "firstName");
```

Update / replace a single value in a hash
```php
$HashName = 'marypoppins@email.com';
$db->replacehash($HashName, "location", "Nigeria, Lagos");
```

Insert new value into hash
```php
$HashName = 'marypoppins@email.com';
$db->inserthash($HashName, "age", 32);
```

Fetch all values in a single hash
```php
$HashName = 'marypoppins@email.com';
$db->gethash($HashName);
```


### Dealing with lists
Insert value into a list
```php
$List = "users";
$db->insertlist($List, "something@email.com");
```

search for a value inside a list
```php
$List = "users";
if($db->search_list($List, "something@email.com")){
    echo 'value exists';
}
```

Count number of values /entries in a List
```php
$List = "users";
$db->countlist($List);
```

Delete a List
```php
$List = "users";
$db->deletelist($List);
```

Return all values in a List as JSON
```php
$List = "users";
$db->getlistjson($List);
```

### Dealing with HashLists
Save hashes to list (HashList) while mapping each value in list to key in hash
```php
$data = [
    "firstName" => "Mary",
    "lastName" => "Poppins",
    "email" => "marypoppins@email.com",
    "location" => "USA, New York"
];
$list = "users";
$userID = "mary123";
$db->inserthashlist($userID, $data, $list);
```

Return all data in a HashList as json
```php
$list = "users";
echo $db->gethashlist($list);
```

