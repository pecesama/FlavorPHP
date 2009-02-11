<?php

class antispam extends singleton {
	
	protected $registry;
	protected $path;
	
	public function __construct() {
		$this->registry = registry::getInstance();
		$this->path = $this->registry["path"];		
	}	
	
	public static function getInstance() {
		return parent::getInstance(get_class());
	}	
	
	public function isSpam($value) {
		$valid = false;
		
		if (preg_match( "/^\d+$/", $value)) { $valid = true; }
		elseif (preg_match( "/^\d+$/", $value)) { $valid = true; }		
		elseif (preg_match("#^<strong>[^.]+\.\.\.</strong>#", $value)) { $valid = true; }
		elseif (preg_match("/\[link=(.+?)\](.+?)\[\/link\]/", $value)) { $valid = true; }
		elseif (preg_match("/\[url=(.+?)\](.+?)\[\/url\]/", $value)) { $valid = true; }
		elseif (3 <= preg_match_all("/a href=/", strtolower($value), $matches)) { $valid = true; } 
		elseif ($this->isBadWord($value)) { return true; } 
		else { $valid = false; }
		return $valid;
	}
	
	private function isBadWord( $str = "" ) {
		$bads = array ("-online", "4u", "adipex", "advicer", "baccarrat", "blackjack", "bllogspot", "booker", "byob", "car-rental-e-site", "car-rentals-e-site", "carisoprodol", "casino", "casinos", "chatroom", "cholesterol", "cialis", "coolcoolhu", "coolhu", "credit-card-debt", "credit-report-4u", "cwas", "cyclen", "cyclobenzaprine", "dating-e-site", "day-trading", "debt-consolidation", "debt-consolidation-consultant", "discreetordering", "duty-free", "dutyfree", "equityloans", "fioricet", "flowers-leading-site", "freenet-shopping", "freenet", "gambling-", "hair-loss", "health-insurancedeals-4u", "homeequityloans", "homefinance", "holdem", "holdempoker", "holdemsoftware", "holdemtexasturbowilson", "hotel-dealse-site", "hotele-site", "hotelse-site", "incest", "insurance-quotesdeals-4u", "insurancedeals-4u", "jrcreations", "levitra", "macinstruct", "milf", "mortgage-4-u", "mortgagequotes", "online-gambling", "onlinegambling-4u", "ottawavalleyag", "ownsthis", "palm-texas-holdem-game", "paxil", "penis", "pharmacy", "phentermine", "poker-chip", "poze", "pussy", "puto", "rental-car-e-site", "ringtones", "roulette", "sesso", "sex", "sexo", "shemale", "shoes", "slot-machine", "texas-holdem", "thorcarlson", "top-site", "top-e-site", "tramadol", "trim-spa", "ultram", "valeofglamorganconservatives", "viagra", "vioxx", "xanax", "zolus");
		for($i=0; $i < sizeof($bads); $i++) {
			if (eregi($bads[$i],$str)) { return true; }
		}
		return false;
	}
	

}
?>