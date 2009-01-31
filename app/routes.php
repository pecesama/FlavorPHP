<?php
/*
	http://path/hola/0..N/page/0..N manda llamar a controller index/page/0..N
	$this->add('hola/([0-9]+)/page/([0-9]+)','index/page/$1');
	
	http://path/cpanel/edit/3 manda llamar a controller index/1
	$this->add('cpanel/edit/3','index/1');
	
	http:/path/hola/[0..N] manda llamar a index/action/[valorAqui]
	$this->add('hola/([0-9]+)','index/actionX/$1');
	
	http://path/wtf manda llamar controller dos/index
	$this->add('wtf','dos');
	
	Cualquier cosa la manda como parámetro  index/index/parametro
	$this->add('(.*)','index/index/$1');
	
	Tambien se puede mandar como segundo parametro un array como el siguiente:
	$this->add('(.*)',array('controller'=>'index','action'=>'index','params'=>'$1'));	
*/
?>
