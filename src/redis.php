<?php
/**
 * Redis
 * @category  Database Access 
 */

class hooli{

    /**
     * By default redis runs on port 6379
     * Setting additional useful constants
     */
    const PORT = 6379;
    const HANDLER = 'redis';
    protected $redis; 

    /**
     * @param string host           most likely IP of the redis server
     * @param string password       password if any
     * @param string port           if it's any different from the default 6379
     * 
     * Internal Magic method to make the hooli object
     * Also calls the internal connect method
     */
    function __construct($host = null, $password = null, $port = null){
        $this->connect($host, $password, $port);
    }

    /**
     * @param string host           most likely IP of the redis server
     * @param string password       password if any
     * @param string port           if it's any different from the default 6379
     * 
     * Connects using the native PHP Redis Class
     */
    public function connect($host = null, $password = null, $port = null)
    {
        $this->redis = new Redis();
        $this->redis->connect($host, self::PORT);  
        $this->redis->auth($password); 
     
    } 

    /**
     * 
     * Tests whether Redis is active
     * Standard Redis Ping-Pong
     * 
     * @return string
     */
    public function ping(){
        return $this->redis->ping();
    }

    /**
     * @param string host       Host that is to keep a hold of 
     * 
     * Redis can be used as a session store, so this method is to save you all the hustle
     */
    public function set_session($host){
        ini_set('session.save_handler', self::HANDLER);
        ini_set('session.save_path', 'tcp://'.$host.':'.self::PORT);  
    }

    /**
     * @param string key
     * 
     * This method returns the value stored in a specific key
     * @return string|array
     */
    public function getvalue($key){
        return $this->redis->get($key);
    }

    /**
     * @param string key    Redis Key to be checked
     * 
     * Method checks if a key exists among the stored keys
     * 
     * @return bool
     */
    public function checkvalue($key){ 
        if($this->redis->exists($key)):
            return true;
        else:
            return false;
        endif;
    }

    /**
     * @param string key        Redis Key to be set
     * @param string value      Value to be set
     * 
     * Method sets appropriate value to the passed key
     * 
     * @return void
     */
    public function setvalue($key,$value ){
        $value =  $this->redis->set($key, $value);  
    }

    /**
     * @param string key        Redis Key
     * @param string value      Value to be cached
     * @param int min           The minimum amount of time for the cache
     * 
     * Method caches a value for a specific period of time
     * 
     * @return string|int
     */
    public function cachevalue($key,$value, $min = 1 ){
        $value =  $this->redis->set($key, $value); 
        $this->expire($key, $min);
        return $value;
    }

    /**
     * @param string key        Redis Key
     * 
     * Method deletes the value stored in the passed key
     * 
     * @return void
     */
    public function delete($key){
       $this->redis->del($key);  
    }

    /**
     * @param string key        Redis Key
     * @param int minutes       Time in minutes when the passed key should expire
     * 
     * Method deletes key after the passed minutes
     * 
     * @return void
     */
    private function expire($key, $minutes){
        $seconds = $minutes * 60; 
        $this->redis->expire($key, $seconds);
    }

    /**
     * @param string hashname     Name of the redis hash
     * @param string key          Redis Key
     * @param string|int value    Value to the corresponding Key
     * 
     * Method sets a key value pair in a hash
     * 
     * @return void
     */
    public function inserthash($hashname, $key, $value){
        $this->redis->hSet($hashname, $key, $value); 
    }

    /**
     * @param string hashname     Name of the redis hash
     * @param string key          Redis Key
     * @param string|int value    Value to the corresponding Key
     * 
     * Method replaces a key value pair in a hash
     * 
     * @return void
     */
    public function replacehash($hashname, $key, $value){
        if($this->redis->hExists($hashname, $key)){
            $this->redis->hSet($hashname, $key, $value); 
        }
    }

    public function checkhash($hashname, $key){
        if($this->redis->hExists($hashname, $key)){
            return true;
        }
    }

    /**
     * @param string hashname       Name of the Redis Hash
     * @param array data            Associative array containing data to be stored
     * 
     * Method creates a hash based on an associative array
     * 
     * @return void 
     */
    public function createhash($hashname, $data){
        $this->redis->del($hashname);  
        foreach($data as $key => $value) 
        { 
           $this->redis->hSet($hashname, $key, $value); 
        }
    }

    /**
     * @param string hashname       Name of the Redis Hash
     * 
     * Method converts and returns hash content as JSON
     * 
     * @return JSON
     */
    public function gethashjson($hashname){
        header( 'Content-Type: application/json; charset=utf-8' );
        return json_encode($this->redis->hGetAll($hashname),  TRUE | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }

    /**
     * @param string hashname       Name of Redis Hash
     * 
     * Method returns all the contents of a Redis Hash
     * 
     * @return mixed
     */
    public function gethash($hashname){ 
        return $this->redis->hGetAll($hashname);
    }

    /**
     * @param string hashname       Name of Redis Hash
     * @param string key            Redis Key
     * 
     * Method returns the value of a specific key in a redis hash
     * 
     * @return string|int
     */
    public function gethashvalue($hashname, $key){
        return $this->redis->hGet($hashname, $key);
    }

    /**
     * @param string hashname       Name of Redis Hash
     * 
     * Method returns number of keys in a Redis Hash 
     * 
     * @return int
     */

    public function countkeys($hashname){
        return $this->redis->hKeys($hashname);
    }

    /**
     * @param string listname       Name of the Redis List
     * @param mixed value           Value to be inserted into the list
     * 
     * Method inserts value to a list from the left
     * 
     * @return void
     */
    public function insertlist($listname, $value){ 
        $this->redis->lpush($listname, $value);
    }

    /**
     * @param string listname       Namw od the redis list
     * 
     * Method counts the number of items in a redis list
     * 
     * @return int 
     */
    public function countlist($listname){
        return $this->redis->llen($listname); 
    }

    public function removefromlist($listname){
        $this->redis->lRem($listname, 'A', 2);
    }

    /**
     * @param string listname       Namw od the redis list
     * 
     * Method deletes a redis list
     * 
     * @return void
     */
    public function deletelist($listname){
        $this->redis->del($listname);
    }

    /**
     * @param string listname       Namw od the redis list
     * 
     * Method takes a list and coverts it to JSON
     * 
     * @return JSON
     */
    public function getlistjson($listname){
        header( 'Content-Type: application/json; charset=utf-8' );
       return json_encode($this->redis->lrange($listname, 0, -1),TRUE | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT );
    }

    /**
     * @param string listname       Namw od the redis list
     * 
     * Method gets all elements in a list 
     * 
     * @return mixed
     */
    public function getlist($listname){
        return $this->redis->lrange($listname, 0, -1);
    }


     /*
    * fetching specified number of keys in a list
    * $num is the set number of keys to be fetched
    */

    public function getlistjsonNum($listname,$num){
        header( 'Content-Type: application/json; charset=utf-8' );
       return json_encode($this->redis->lrange($listname, 0, $num),TRUE | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT );
    }

     /*
    * fetching specified number of keys in a list
    * $num is the set number of keys to be fetched
    */

    public function getlistnum($listname,$num){
        return $this->redis->lrange($listname, 0, $num);
    }


    /**
     * @param string listname       Namw od the redis list
     * @param string value          The value to be found
     * 
     * Method takes a list and a value and findout whether the value eexists in the list
     * 
     * @return bool
     */
    public function search_list($list, $value){
        if($this->redis->lRem($list, $value, 0)){
            $this->redis->rpush($list, $value );
            return true;
        }else{
            return false;
        }
    }

    /**
     * @param string hashid         The HashID hosting the value to be incremented
     * @param int value             The value to be incremented
     * 
     * Method increases the value specified in the hash by one
     * 
     * @return void
     */
    public function increase($hashid, $value){
        $this->redis->hincrby($hashid, $value, 1);
    }  

    /**
     * @param string hashid         The HashID hosting the value to be incremented
     * @param int value             The value to be incremented
     * @param int number            The number by which the number is to be incremented
     * 
     * Method increases the value specified in the hash by the number
     * 
     * @return void
     */
    public function increaseby($hashid, $value, $number){
        $this->redis->hincrby($hashid, $value, $number);
    }  

    /**
     * @param string hasid          The name of the hash
     * @param mixed data           The data to be stored
     * 
     * Method save hashes to list while mapping each value in list to key in hash and
     * builds a document like structure for data storage like firebase and mongodb
     * 
     * @return void
     */
    public function inserthashlist($hashid, $data, $list){
        $this->redis->del($hashid);  
        foreach($data as $key => $value){ 
           $this->redis->hSet($hashid, $key, $value);  
        }
        $this->insertlist($list, $hashid); 
    }
   
    /**
     * @param string list           Name of the Redis list 
     * 
     * Method creates a hashed list and returns it as JSON
     * 
     * @return JSON
     */
    public function gethashlist($list){
        $all = $this->getlist($list);
        foreach ($all as $c){ 
            $data[] = $this->gethash($c); 
        }
        return json_encode($data, TRUE | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }

 /*
    * fetching specified number of list
    * $num reperesents the number of list to fetch
    */

    /*
    * fetching specified number of hashes
    * $num reperesents the number of hashes to fetch from a list
    */
    public function gethashlistnum($list,$num){
        $all = $this->getlistnum($list,$num);
            foreach ($all as $c){ 
                $data[] = $this->gethash($c); 
            }
    
        return json_encode($data, TRUE | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }


    /*
    * streams : using redis for queing and messaging
    * add to stream
    */
    public function streamadd($data, $streamname){
        $this->redis->xAdd($streamname, "*", $data); #data is an array
    }

    

}

  
 

?>