<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: wang
 * Date: 15/1/7
 * Time: 上午11:29
 */



class Cache {
    private $host ;
    private $port ;
    private $memcached;
    function __construct(){
        $this->host = '127.0.0.1';
        $this->port = '11211';
        if(!$this->memcached){
            $this->memcached = new Memcached();
            $this->memcached->addServer($this->host,$this->port);
        }
    }

    function get($key){
        return $this->memcached->get($key);
    }
    function set($key,$value,$expiration = null){
        $this->memcached->set($key,$value,$expiration);
    }


}

?>