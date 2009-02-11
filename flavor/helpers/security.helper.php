<?php

class security extends singleton {
	
	private $validTags;
	private $badAtributes = 'javascript:|onclick|ondblclick|onmousedown|onmouseup|onmouseover|onmousemove|onmouseout|onkeypress|onkeydown|onkeyup';
	
	public static function getInstance() {
		return parent::getInstance(get_class());
	}
	
	public function clean($source, $strict=false) {		
		if ($strict) {
			$this->validTags = '<blockquote><code><em><i><strong><b><a><br>';
		} else {
			$this->validTags = '<p><ol><ul><li><a><abbr><acronym><blockquote><code><pre><em><i><strong><b><del><br><span><div><img>';
		}
		if (is_array($source)) {
			foreach($source as $key => $value) {
				if (is_string($value)) $source[$key] = $this->filterTags($this->decode($value));
			}
			return $source;
		} else if (is_string($source)) {
			return $this->filterTags($this->decode($source));
		} else {
			return $source;
		}
	}
	
	private function decode($source) {
		$source = html_entity_decode($source, ENT_QUOTES, "ISO-8859-1");
		$source = preg_replace('/&#(\d+);/me',"chr(\\1)", $source);
		$source = preg_replace('/&#x([a-f0-9]+);/mei',"chr(0x\\1)", $source);
		return $source;
	}
	
	private function filterTags($source) {		
		$source = strip_tags($source, $this->validTags);
		return preg_replace('/<(.*?)>/ie', "'<'.security::removeBadAtributes('\\1').'>'", $source);
	}

	private static function removeBadAtributes($sourceTag) {		
		$sourceTag = stripslashes($sourceTag);
		$sourceTag = preg_replace("/security::badAtributes/i", "niceTry", $sourceTag);
		return $sourceTag;
	}
}
?>