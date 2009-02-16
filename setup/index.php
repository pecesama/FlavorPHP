<?php
	include('config.php');
	include('classes/mysql.class.php');

	/*
	 * Extrae las categorias del post pasado por id.
	 */
	function extraerTags($ID){
		global $db_source,$prefix;
		/*
		 * Importación de Categorías
		 */
		$m = new mysql();

		#SQL: extrae los tags de WP para el ID del post que se pase como parámetro.
		$m->query("SELECT t.term_id,t.slug,t.name,tr.object_id FROM $db_source.wp_terms as t \n
		inner join $db_source.wp_term_taxonomy as tt on tt.term_id = t.term_id \n
		inner join $db_source.wp_term_relationships as tr on tr.term_taxonomy_id = t.term_id \n
		WHERE tt.taxonomy = 'category' and tr.object_id=$ID \n
		");

		$sql_tags = "INSERT INTO ".$prefix."tags(tag_id,tag,urlfriendly) VALUES ";
		$insertTags = false;

		$sql_relation = "INSERT INTO ".$prefix."tags_rel(tag_id,post_id) VALUES ";
		$hasTags = false;
		/*
		 * Para cada tag del post extraido de WP
		 * 1.- Revisar si existe, si no, lo inserta.
		 */
		while($m->fetch()){
			$m2 = new mysql();
			/*
			 * Si no existe tag, lo insertamos.
			 */
			$m2->query("SELECT * FROM ".$prefix."tags WHERE tag_id=".$m->sql_quote($m->row['term_id'])."");
			if(!$m2->fetch()){
				$sql_tags .= "(".
				$m->sql_quote($m->row['term_id']).",".
				"'".$m->sql_quote($m->row['name'])."',".
				"'".$m->sql_quote($m->row['slug'])."'".
				"), ";
				$insertTags = true;
			}
			
			/*
			 * Buscamos si existe la relacion entre el tag y el post, si no, la creamos.
			 */
			$m2->query("SELECT * FROM ".$prefix."tags_rel WHERE tag_id=".$m->sql_quote($m->row['term_id'])." AND post_id=".$ID);
			if(!$m2->fetch()){
				$sql_relation .= " (".$m->sql_quote($m->row['term_id']).",".$m->sql_quote($ID)."), ";
				$hasTags = true;
			}
		}

		if($insertTags){
			$sql_tags = substr($sql_tags,0,-2).';';
			$m->query($sql_tags);
		}

		if($hasTags){
			$sql_relation = substr($sql_relation,0,-2).';';
			$m->query($sql_relation);
		}
	}

	$m = new mysql();
	$m->query("DROP TABLE IF EXISTS ".$prefix."tags");
	$m->query("CREATE TABLE ".$prefix."tags (
	tag_id INTEGER  NOT NULL AUTO_INCREMENT,
	tag varchar(255)  NOT NULL,
	urlfriendly varchar(255)  NOT NULL,
	PRIMARY KEY (tag_id)
	)");

	$m->query("DROP TABLE IF EXISTS ".$prefix."tags_rel");
	$m->query("CREATE TABLE ".$prefix."tags_rel(
	tag_id INTEGER  NOT NULL,
	post_id INTEGER  NOT NULL
	)");

	/*
	 * Importacion de Posts desde instalacion de WP
	 */
	$m = new mysql();
	//Creamos tabla de posts
	$m->query("DROP TABLE IF EXISTS ".$prefix."posts");
	$m->query("CREATE TABLE ".$prefix."posts(
		urlfriendly varchar(255) NOT NULL,
		title varchar(255) NOT NULL,
		content TEXT,
		status varchar(50),
		ID int(10) unsigned NOT NULL auto_increment,
		created datetime default NULL,
		modified datetime default NULL,
		PRIMARY KEY (ID),
		FULLTEXT INDEX (title, content, urlfriendly)
	) DEFAULT CHARSET=UTF8");

	//Extraemos Posts de WP
	$m->query("SELECT wp_posts.*, DATE_FORMAT(post_date,'%Y/%m/%d') as datestamp FROM $db_source.wp_posts");
	//Insertamos Posts de WP en la nueva instalacion
	$sqlStrs = array();
	while($m->fetch()){
		$sqlStr = "INSERT INTO ".$prefix."posts(urlfriendly,title,content,status,created,ID) VALUES\n";
		$sqlStr .= "(".
		"'".$m->sql_quote($m->row['post_name'])."',".//urlfriendly
		"'".$m->sql_quote($m->row['post_title'])."',".
		"'".$m->sql_quote($m->row['post_content'])."',".
		"'".$m->sql_quote($m->row['post_status'])."',".
		"'".$m->sql_quote($m->row['post_date'])."',".
		"{$m->row['ID']}".
		")\n";
		$sqlStrs[] = $sqlStr;
		extraerTags($m->row['ID']);//para cada post extrae sus tags e inserta.
	}
	foreach($sqlStrs as $sqlStr)
		$m->query($sqlStr);

	/*
	 * Importacion de Configuraciones de instalacion de WP
	 */
	$m = new mysql();
	//Creamos tabla de configuraciones
	$m->query("DROP TABLE IF EXISTS ".$prefix."configurations");
	$m->query("CREATE TABLE  ".$prefix."configurations (
		blogname varchar(255) NOT NULL,
		blogdescription varchar(255) NOT NULL,
		siteurl varchar(255) NOT NULL,
		current_theme varchar(255) NOT NULL,
		posts_per_page varchar(255) NOT NULL,
		ID_user int(10) unsigned NOT NULL auto_increment,
		PRIMARY KEY (ID_user)
	) DEFAULT CHARSET=UTF8");
	//Extraemos datos de WP
	$m->query("SELECT * FROM $db_source.wp_options");
	//Insertamos datos de WP en la nueva instalacion
	while($m->fetch()){
		$wp_options[$m->sql_quote($m->row['option_name'])] = $m->sql_quote($m->row['option_value']);
	}
	$sqlStr = "INSERT INTO ".$prefix."configurations(blogname,blogdescription,siteurl,current_theme,posts_per_page,ID_user) VALUES('{$wp_options['blogname']}','{$wp_options['blogdescription']}','$siteurl','stan512','{$wp_options['posts_per_page']}',1)\n";
	$m->query($sqlStr);

	/*
	 * Importacion de Configuraciones de instalacion de WP
	 */
	$m = new mysql();
	//Creamos tabla de configuraciones
	$m->query("DROP TABLE IF EXISTS ".$prefix."links");
	$m->query("CREATE TABLE  ".$prefix."links (
		name varchar(255) NOT NULL,
		link varchar(255) NOT NULL,
		created datetime default NULL,
		modified datetime default NULL,
		ID int(10) unsigned NOT NULL auto_increment,
		PRIMARY KEY (ID)
	) DEFAULT CHARSET=UTF8");
	//Extraemos datos de WPs
	$m->query("SELECT * FROM $db_source.wp_links");
	//Insertamos datos de WP en la nueva instalacion
	$sqlStrs = array();
	while($m->fetch()){
		$sqlStr = "INSERT INTO ".$prefix."links(name,link,ID) VALUES\n";
		$sqlStr .= "(".
		"'".$m->sql_quote($m->row['link_name'])."',".
		"'".$m->sql_quote($m->row['link_url'])."',".
		"'".$m->sql_quote($m->row['link_ID'])."'".
		")\n";
		
		$sqlStrs[] = $sqlStr;
	}
	foreach($sqlStrs as $sqlStr)
		$m->query($sqlStr);

	/*
	 * Importacion de Comentarios desde instalacion de WP
	 */
	$m = new mysql();
	//Creamos tabla de comentarios
	$m->query("DROP TABLE IF EXISTS ".$prefix."comments");
	$m->query("CREATE TABLE  ".$prefix."comments (
		suscribe varchar(255),
		user_id varchar(255),
		type varchar(255),
		status varchar(255),
		content TEXT,
		IP varchar(255),
		url varchar(255),
		email varchar(255),
		author varchar(255),
		created datetime default NULL,
		modified datetime default NULL,
		ID_post int(10) unsigned NOT NULL,
		ID int(10) unsigned NOT NULL auto_increment,
		PRIMARY KEY (ID)
	) DEFAULT CHARSET=UTF8");
	//Extraemos Comentarios de WP
	$m->query("SELECT wp_comments.* FROM $db_source.wp_comments");
	//Insertamos Comentarios de WP en la nueva instalacion
	$sqlStrs = array();
	while($m->fetch()){
		$sqlStr = "INSERT INTO ".$prefix."comments(suscribe,user_id,type,status,content,IP,url,email,author,created,ID_post,ID) VALUES\n";
		$sqlStr .= "(".
		"'".$m->sql_quote($m->row['comment_suscribe'])."',".//urlfriendly
		"'".$m->sql_quote($m->row['user_id'])."',".
		"'".$m->sql_quote($m->row['comment_type'])."',".
		"'".$m->sql_quote($m->row['comment_approved'])."',".
		"'".$m->sql_quote($m->row['comment_content'])."',".
		"'".$m->sql_quote($m->row['comment_author_IP'])."',".
		"'".$m->sql_quote($m->row['comment_author_url'])."',".
		"'".$m->sql_quote($m->row['comment_author_email'])."',".
		"'".$m->sql_quote($m->row['comment_author'])."',".
		"'".$m->sql_quote($m->row['comment_date'])."',".
		"{$m->row['comment_post_ID']},".
		"{$m->row['comment_ID']}".
		");\n";
		$sqlStrs[] = $sqlStr;
	}
	foreach($sqlStrs as $sqlStr)
		$m->query($sqlStr);

	/*
	 * Extraer usuarios
	 *   Hace falta recorrer la tabla de usuarios de WP e insertarlos aquí,
	 *   y de la tabla posts extraer para cada post el id_user
	 */
	$m = new mysql();
	$m->query("DROP TABLE IF EXISTS ".$prefix."users");
	$m->query("CREATE TABLE IF NOT EXISTS ".$prefix."users (
		id_user int(10) unsigned NOT NULL auto_increment,
		name varchar(100) default NULL,
		login varchar(100) NOT NULL default '',
		password varchar(64) NOT NULL default '',
		email varchar(100) default NULL,
		website varchar(150) default NULL,
		about text ,
		created datetime default NULL,
		modified datetime default NULL,
		PRIMARY KEY  (id_user)
	) DEFAULT CHARSET=utf8");

	$m->query("INSERT INTO ".$prefix."users (id_user, name, login, password, email, website, about) VALUES
	(1, 'Usuario demo', 'admin', md5('demo'), 'test@test.com', 'http://www.codice-cms.org/', 0x5072756562612064652070657266696c);");
	
	echo "here is!";
?>
