<?php

class l10n extends singleton {
	
	protected $gettext;
	protected $input;
	protected $language=NULL;
	
	public static function getInstance() {
		return parent::getInstance(get_class());
	}

	public function __($string){
		if(is_null($this->language)){
			$this->setLanguage("en");
		}
		return $this->gettext->translate($string);
	}
	
	public function T_ngettext($single, $plural, $number) {
		return $this->gettext->ngettext($single, $plural, $number);
	}
	
	public function setLanguage($lang){
		$this->language = $lang;
		$this->input = new streams(dirname(__FILE__).DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR."languages".DIRECTORY_SEPARATOR.$this->language.DIRECTORY_SEPARATOR."messages.mo");	
		$this->gettext = new gettext($this->input);
	}
	
	public function getLocalization() {
		$languages = array();
		$lang_path = dirname(__FILE__).DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR."languages";
		$lang_dir = @ dir($lang_path);
		if ($lang_dir) {
			while (($folder = $lang_dir->read()) !== false) {
				if (preg_match('|^\.+$|', $folder))
					continue;
				$locale_folder = @ dir($lang_path.DIRECTORY_SEPARATOR.$folder);
				if($locale_folder){
					while (($file = $locale_folder->read()) !== false) {
						if (preg_match('|^\.+$|', $file))
						continue;
						if ($file == 'messages.mo'){
							$languages[$folder] = $this->displayLanguage($folder);
						}
					}
				}
			}
		}
		asort($languages);
		return $languages;
	}
	
	public function displayLanguage($lang){
		$out = "";
		if(strpos($lang, '-')==2){
			$lang = explode('-',$lang);
		}
		switch((is_array($lang))? $lang[0] : $lang){
			case 'af': $out = $this->__('Afrikaans'); break;
			case 'sq': $out = $this->__('Albanian'); break;
			case 'ar': $out = $this->__('Arabic'); break;
			case 'eu': $out = $this->__('Basque'); break;
			case 'bg': $out = $this->__('Bulgarian'); break;
			case 'be': $out = $this->__('Belarusian'); break;
			case 'ca': $out = $this->__('Catalan'); break;
			case 'zh': $out = $this->__('Chinese'); break;
			case 'hr': $out = $this->__('Croatian'); break;
			case 'cs': $out = $this->__('Czech'); break;
			case 'da': $out = $this->__('Danish'); break;
			case 'nl': $out = $this->__('Dutch'); break;
			case 'en': $out = $this->__('English'); break;
			case 'et': $out = $this->__('Estonian'); break;
			case 'fo': $out = $this->__('Faeroese'); break;
			case 'fa': $out = $this->__('Farsi'); break;
			case 'fi': $out = $this->__('Finnish'); break;
			case 'fr': $out = $this->__('French'); break;
			case 'gd': $out = $this->__('Gaelic'); break;
			case 'de': $out = $this->__('German'); break;
			case 'el': $out = $this->__('Greek'); break;
			case 'he': $out = $this->__('Hebrew'); break;
			case 'hi': $out = $this->__('Hindi'); break;
			case 'hu': $out = $this->__('Hungarian'); break;
			case 'is': $out = $this->__('Icelandic'); break;
			case 'id': $out = $this->__('Indonesian'); break;
			case 'it': $out = $this->__('Italian'); break;
			case 'ja': $out = $this->__('Japanese'); break;
			case 'ko': $out = $this->__('Korean'); break;
			case 'lv': $out = $this->__('Latvian'); break;
			case 'lt': $out = $this->__('Lithuanian'); break;
			case 'mk': $out = $this->__('Macedonian'); break;
			case 'ms': $out = $this->__('Malaysian'); break;
			case 'mt': $out = $this->__('Maltese'); break;
			case 'no': $out = $this->__('Norwegian'); break;
			case 'pl': $out = $this->__('Polish'); break;
			case 'pt': $out = $this->__('Portuguese'); break;
			case 'rm': $out = $this->__('Rhaeto-Romanic'); break;
			case 'ro': $out = $this->__('Romanian'); break;
			case 'ru': $out = $this->__('Russian'); break;
			case 'sz': $out = $this->__('Sami'); break;
			case 'sr': $out = $this->__('Serbian'); break;
			case 'sk': $out = $this->__('Slovak'); break;
			case 'sl': $out = $this->__('Slovenian'); break;
			case 'sb': $out = $this->__('Sorbian'); break;
			case 'es': $out = $this->__('Spanish'); break;
			case 'sx': $out = $this->__('Sutu'); break;
			case 'sv': $out = $this->__('Swedish'); break;
			case 'th': $out = $this->__('Thai'); break;
			case 'ts': $out = $this->__('Tsonga'); break;
			case 'tn': $out = $this->__('Tswana'); break;
			case 'tr': $out = $this->__('Turkish'); break;
			case 'uk': $out = $this->__('Ukrainian'); break;
			case 'ur': $out = $this->__('Urdu'); break;
			case 've': $out = $this->__('Venda'); break;
			case 'vi': $out = $this->__('Vietnamese'); break;
			case 'xh': $out = $this->__('Xhosa'); break;
			case 'ji': $out = $this->__('Yiddish'); break;
			case 'zu': $out = $this->__('Zulu'); break;
		}
		if(is_array($lang)){
			$country = strtolower($lang[1]);
			switch($country){
				//Aca una lista con los paises. No se como tendría que hacer para hacer insensible a mayusculas o minusculas: es-MX o es-mx ¿?
				case 'sa': $out .= " (". $this->__('Saudi Arabia'). ")"; break;
				case 'iq': $out .= " (". $this->__('Iraq'). ")"; break;
				case 'eg': $out .= " (". $this->__('Egypt'). ")"; break;
				case 'ly': $out .= " (". $this->__('Libya'). ")"; break;
				case 'dz': $out .= " (". $this->__('Algeria'). ")"; break;
				case 'ma': $out .= " (". $this->__('Morocco'). ")"; break;
				case 'tn': $out .= " (". $this->__('Tunisia'). ")"; break;
				case 'om': $out .= " (". $this->__('Oman'). ")"; break;
				case 'ye': $out .= " (". $this->__('Yemen'). ")"; break;
				case 'sy': $out .= " (". $this->__('Syria'). ")"; break;
				case 'jo': $out .= " (". $this->__('Jordan'). ")"; break;
				case 'lb': $out .= " (". $this->__('Lebanon'). ")"; break;
				case 'kw': $out .= " (". $this->__('Kuwait'). ")"; break;
				case 'ae': $out .= " (". $this->__('U.A.E.'). ")"; break;
				case 'bh': $out .= " (". $this->__('Bahrain'). ")"; break;
				case 'qa': $out .= " (". $this->__('Qatar'). ")"; break;
				case 'tw': $out .= " (". $this->__('Taiwan'). ")"; break;
				case 'cn': $out .= " (". $this->__('PRC'). ")"; break;
				case 'hk': $out .= " (". $this->__('Hong Kong SAR'). ")"; break;
				case 'sg': $out .= " (". $this->__('Singapore'). ")"; break;
				case 'be': $out .= " (". $this->__('Belgium'). ")"; break;
				case 'us': $out .= " (". $this->__('United States'). ")"; break;
				case 'gb': $out .= " (". $this->__('United Kingdom'). ")"; break;
				case 'au': $out .= " (". $this->__('Australia'). ")"; break;
				case 'ca': $out .= " (". $this->__('Canada'). ")"; break;
				case 'nz': $out .= " (". $this->__('New Zealand'). ")"; break;
				case 'ie': $out .= " (". $this->__('Ireland'). ")"; break;
				case 'za': $out .= " (". $this->__('South Africa'). ")"; break;
				case 'jm': $out .= " (". $this->__('Jamaica'). ")"; break;
				case 'bz': $out .= " (". $this->__('Belize'). ")"; break;
				case 'tt': $out .= " (". $this->__('Trinidad'). ")"; break;
				case 'ch': $out .= " (". $this->__('Switzerland'). ")"; break;
				case 'lu': $out .= " (". $this->__('Luxembourg'). ")"; break;
				case 'at': $out .= " (". $this->__('Austria'). ")"; break;
				case 'li': $out .= " (". $this->__('Liechtenstein'). ")"; break;
				case 'br': $out .= " (". $this->__('Brazil'). ")"; break;
				case 'pt': $out .= " (". $this->__('Portugal'). ")"; break;
				case 'mo': $out .= " (". $this->__('Republic of Moldova'). ")"; break;
				case 'sz': $out .= " (". $this->__('Lappish'). ")"; break;
				case 'mx': $out .= " (". $this->__('Mexico'). ")"; break;
				case 'gt': $out .= " (". $this->__('Guatemala'). ")"; break;
				case 'cr': $out .= " (". $this->__('Costa Rica'). ")"; break;
				case 'pa': $out .= " (". $this->__('Panama'). ")"; break;
				case 'do': $out .= " (". $this->__('Dominican Republic'). ")"; break;
				case 've': $out .= " (". $this->__('Venezuela'). ")"; break;
				case 'co': $out .= " (". $this->__('Colombia'). ")"; break;
				case 'pe': $out .= " (". $this->__('Peru'). ")"; break;
				case 'ar': $out .= " (". $this->__('Argentina'). ")"; break;
				case 'ec': $out .= " (". $this->__('Ecuador'). ")"; break;
				case 'cl': $out .= " (". $this->__('Chile'). ")"; break;
				case 'uy': $out .= " (". $this->__('Uruguay'). ")"; break;
				case 'py': $out .= " (". $this->__('Paraguay'). ")"; break;
				case 'bo': $out .= " (". $this->__('Bolivia'). ")"; break;
				case 'sv': $out .= " (". $this->__('El Salvador'). ")"; break;
				case 'hn': $out .= " (". $this->__('Honduras'). ")"; break;
				case 'ni': $out .= " (". $this->__('Nicaragua'). ")"; break;
				case 'pr': $out .= " (". $this->__('Puerto Rico'). ")"; break;
				case 'fi': $out .= " (". $this->__('Finland'). ")"; break;
			}
		}
		return $out;
	}
}
?>