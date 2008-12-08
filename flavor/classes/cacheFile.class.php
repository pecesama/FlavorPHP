<?php

class cacheFile implements ArrayAccess {

	public function get($key, $expiration = false){
		if (! $expiration) {
			$expiration = Cache::CACHE_TIME;
		}
		$file = $this->getCacheFile($key);
		if ($this->isLocked($file)) $this->waitForLock($file);
		if (file_exists($file) && is_readable($file)) {
			if (($mtime = filemtime($file)) !== false && (time() - $mtime) < $expiration) {
				if (($data = @file_get_contents($file)) !== false) {
					return unserialize($data);
				}
			}
		}
		return false;
	}

	public function set($key, $value){
		$cacheDir = $this->getCacheDir($key);
		$file = $this->getCacheFile($key);
		$data = serialize($value);
		if ($this->isLocked($file)) {
			$this->waitForLock($file);
		}
		if (! is_dir($cacheDir)) {
			if (! @mkdir($cacheDir, 0755, true)) {
				throw new Exception('The cache directory could not be created');
			}
		}
		$this->createLock($file);
		if (! @file_put_contents($file, $data)) {
			$this->removeLock($file);
			throw new Exception('Could not store data in cache file');
		}
		$this->removeLock($file);
	}

	public function delete($key){
		$file = $this->getCacheFile($key);
		if (! @unlink($file)) {
			throw new Exception("Cache file could not be deleted");
		}
	}

	private function createLock($cacheFile){
		$cacheDir = dirname($cacheFile);
		if (! is_dir($cacheDir)) {
			if (! @mkdir($cacheDir, 0755, true)) {
				// make sure the failure isn't because of a concurency issue
				if (! is_dir($cacheDir)) {
					throw new Exception('The cache directory could not be created');
				}
			}
		}
		@touch($cacheFile . '.lock');
	}

	private function removeLock($cacheFile){
		@unlink($cacheFile . '.lock');
	}
	
	function isLocked($cacheFile){
		return file_exists($cacheFile . '.lock');
	}
	
	private function waitForLock($cacheFile){
		$tries = 0;
		// wainting to the file to be fully written
		while ($tries <= 20 && $this->isLocked($cacheFile)){
			clearstatcache();
			usleep(200);
			$tries ++;
		};
		if ($this->isLocked($cacheFile)) {
			$this->removeLock($cacheFile);
		}
		// its safe now
	}

	private function getCacheDir($hash){
		return Cache::CACHE_ROOT . '/' . substr($hash, 0, 3);
	}

	private function getCacheFile($hash){
		return $this->getCacheDir($hash) . '/' . $hash;
	}

	
	public function __set($key, $value){
		return $this->set($key, $value);
	}
	
	public function __get($key){
		return $this->get($key);
	}
	
	public function offsetExists($offset) {
		//NO implementation yet
	}	

	public function offsetSet($offset, $value) {
		return $this->set($offset, $value);
	}
	
	public function offsetGet($offset) {
		return $this->get($offset);
	}

	public function offsetUnset($offset) {
		return $this->delete($offset);
	}


}