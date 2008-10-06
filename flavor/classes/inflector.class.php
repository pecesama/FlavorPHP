<?php

/**
  * Pluralize English words.
  *
  * Inflector is taken from CakePHP project. Thanks!
  * 
  * @static
  */
class inflector {

	public static function pluralize($word)
    {
		$word = strtolower($word);
        $plural = array('/(s)tatus$/i' => '\1\2tatuses',
									'/(quiz)$/i' => '\1zes',
									'/^(ox)$/i' => '\1\2en',
									'/([m|l])ouse$/i' => '\1ice',
									'/(matr|vert|ind)(ix|ex)$/i'  => '\1ices',
									'/(x|ch|ss|sh)$/i' => '\1es',
									'/([^aeiouy]|qu)y$/i' => '\1ies',
									'/(hive)$/i' => '\1s',
									'/(?:([^f])fe|([lr])f)$/i' => '\1\2ves',
									'/sis$/i' => 'ses',
									'/([ti])um$/i' => '\1a',
									'/(p)erson$/i' => '\1eople',
									'/(m)an$/i' => '\1en',
									'/(c)hild$/i' => '\1hildren',
									'/(buffal|tomat)o$/i' => '\1\2oes',
									'/(alumn|bacill|cact|foc|fung|nucle|radi|stimul|syllab|termin|vir)us$/i' => '\1i',
									'/us$/' => 'uses',
									'/(alias)$/i' => '\1es',
									'/(ax|cri|test)is$/i' => '\1es',
									'/s$/' => 's',
									'/$/' => 's',);
        
        $uncountable = array('.*[nrlm]ese', '.*deer', '.*fish', '.*measles', '.*ois', '.*pox', '.*sheep', 'Amoyese',
											'bison', 'Borghese', 'bream', 'breeches', 'britches', 'buffalo', 'cantus', 'carp', 'chassis', 'clippers',
											'cod', 'coitus', 'Congoese', 'contretemps', 'corps', 'debris', 'diabetes', 'djinn', 'eland', 'elk',
											'equipment', 'Faroese', 'flounder', 'Foochowese', 'gallows', 'Genevese', 'Genoese', 'Gilbertese', 'graffiti',
											'headquarters', 'herpes', 'hijinks', 'Hottentotese', 'information', 'innings', 'jackanapes', 'Kiplingese',
											'Kongoese', 'Lucchese', 'mackerel', 'Maltese', 'media', 'mews', 'moose', 'mumps', 'Nankingese', 'news',
											'nexus', 'Niasese', 'Pekingese', 'Piedmontese', 'pincers', 'Pistoiese', 'pliers', 'Portuguese', 'proceedings',
											'rabies', 'rice', 'rhinoceros', 'salmon', 'Sarawakese', 'scissors', 'sea[- ]bass', 'series', 'Shavese', 'shears',
											'siemens', 'species', 'swine', 'testes', 'trousers', 'trout', 'tuna', 'Vermontese', 'Wenchowese',
											'whiting', 'wildebeest', 'Yengeese',);

        $irregular = array('atlas' => 'atlases',
										'beef' => 'beefs',
										'brother' => 'brothers',
										'child' => 'children',
										'corpus' => 'corpuses',
										'cow' => 'cows',
										'ganglion' => 'ganglions',
										'genie' => 'genies',
										'genus' => 'genera',
										'graffito' => 'graffiti',
										'hoof' => 'hoofs',
										'loaf' => 'loaves',
										'man' => 'men',
										'money' => 'monies',
										'mongoose' => 'mongooses',
										'move' => 'moves',
										'mythos' => 'mythoi',
										'numen' => 'numina',
										'occiput' => 'occiputs',
										'octopus' => 'octopuses',
										'opus' => 'opuses',
										'ox' => 'oxen',
										'penis' => 'penises',
										'person' => 'people',
										'sex' => 'sexes',
										'soliloquy' => 'soliloquies',
										'testis' => 'testes',
										'trilby' => 'trilbys',
										'turf' => 'turfs',);

        $lowercased_word = strtolower($word);

        foreach ($uncountable as $_uncountable){
            if(substr($lowercased_word,(-1*strlen($_uncountable))) == $_uncountable){
                return $word;
            }
        }

        foreach ($irregular as $_plural=> $_singular){
            if (preg_match('/('.$_plural.')$/i', $word, $arr)) {
                return preg_replace('/('.$_plural.')$/i', substr($arr[0],0,1).substr($_singular,1), $word);
            }
        }

        foreach ($plural as $rule => $replacement) {
            if (preg_match($rule, $word)) {
                return preg_replace($rule, $replacement, $word);
            }
        }
        return false;

    }
}
?>