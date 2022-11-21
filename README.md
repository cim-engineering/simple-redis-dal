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

### Check if eveything works
Simple check
```php
$db->ping();
```

### Handling simple values
Set a value
```php
$key = "username";
$db->setvalue($key, "MaryPoppins");
```

Get the value
```php 
$username = $db->getvalue($key);
```

Delete a value
```php
$key = "username";
$db->delete($key);
```

Cache a value
```php 
$key = "session_id";
$value = "12345";
$time = 20 // Time is in minutes

$db->cachevalue($key, $value, $time);
```

Expire a value
```php 
$key = "session_id";
$time = 1 // Time in minutes

$db->expire($key, $time);
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

Count the keys in a hash
```php 
$HashName = 'marypoppins@email.com';
$count = $db->countkeys($HashName);
```

Increase a value in a hash
```php 
$HashName = 'marypoppins@email.com';
$key = 'age'; // The value of this key must be an integer
$increaseBy = 4;
$db->increase($HashName, $key); // This method increases the value by one
$db->increaseby($HashName, $key, $increaseBy); // This method increases the value by the specified number 
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
Get a specific number of elements from a list
```php 
$List = "users";
$num = 10;
$users = $db->getlistnum($List, $num); // Returns an array
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

## Collaborators ✨

<!-- readme: collaborators -start -->
<table>
<tr>
    <td align="center">
        <a href="https://github.com/timek">
            <img src="https://avatars.githubusercontent.com/u/2828143?v=4" width="100;" alt="timek"/>
            <br />
            <sub><b>Tim3k</b></sub>
        </a>
    </td>
    <td align="center">
        <a href="https://github.com/kusaasira">
            <img src="https://avatars.githubusercontent.com/u/10392992?v=4" width="100;" alt="kusaasira"/>
            <br />
            <sub><b>Joshua Kusaasira</b></sub>
        </a>
    </td>
    <td align="center">
        <a href="https://github.com/Marshud">
            <img src="https://avatars.githubusercontent.com/u/63245157?v=4" width="100;" alt="Marshud"/>
            <br />
            <sub><b>Marshud</b></sub>
        </a>
    </td></tr>
</table>
<!-- readme: collaborators -end -->
