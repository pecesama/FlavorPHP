<?php

/**
* Cache class
*
* Quick guide to use this class:
*
* 	Getting the Cache object:
* 		$cache = Cache::getInstance();
* 
* 	Putting data:
* 		$cache->set($hash_key,$value);
* 		or 'a la' array style:
* 		$cache[$hash_key] = $value;
* 
* 	Getting data:
* 		$data = $cache->get($hash_key);
* 		or 'a la' array style:
* 		$data = $cache[$hash_key];
* 
* 	Deleting data:
* 		$cache->delete($hash_key);
* 		or:
* 		unset($cache[$hash_key]);
* 
*/

abstract class Cache extends Singleton {
	// Basic config
	const CACHE_TYPE = 'cacheFile';
	// Default time is half an hour
	const CACHE_TIME = 1800;
	// Root dir for caching
	const CACHE_ROOT = '/tmp/flavor';
												
	public static function getInstance() {
		return parent::getInstance(Cache::CACHE_TYPE);
	}

	
}