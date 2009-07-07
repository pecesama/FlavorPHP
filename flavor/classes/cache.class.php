<?php
/**
 * Cache Class
 *
 * @package Cache
 * <code>
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
 * </code>
 * @see Singleton
 * @abstract
 **/
abstract class Cache extends Singleton {
	
	/**
	 * Define el tipo de cache que se va a usar.
	 * @var string
	 **/
	const CACHE_TYPE = 'cacheFile';
	
	/**
	 * Define el tiempo que se va a retener un objeto en cache.
	 * El valor por defecto es media hora (1800)
	 * @var integer
	 **/
	const CACHE_TIME = 1800;
	
	/**
	 * Directorio donde se guardara el cache, de ser necesario.
	 *
	 * @var string
	 **/
	const CACHE_ROOT = '/tmp/flavor';

	/**
	 * Retorna una instancia de la clase elegida para cache.
	 * 
	 * @return Cache
	 **/
	public static function getInstance() {
		return parent::getInstance(Cache::CACHE_TYPE);
	}

	
}