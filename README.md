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

### Dealing with lists
search for a value inside a list
```php
if($db->search_list("users", "something@email.com")){
    echo 'value exists';
}
```
Save hashes to list (HashList) while mapping each value in list to key in hash
```php
$data = [
    "firstName" => "Mary",
    "lastName" => "Poppins",
    "email" => "marypoppins@email.com",
    "location" => "USA, New York"
];
$list = "users";
$userID = "mary123"
$db->inserthashlist($userID, $data, $list);
```

Return all data in a HashList as json
```php
$list = "users";
echo $db->gethashlist($list);
```

