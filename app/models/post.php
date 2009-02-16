<?php

class post extends models {

	public function countPosts($extra = array('status'=>'publish','tag'=>null)) { 
		$sql = "SELECT \n";
			$sql .= "\tcount(*) as total \n";
		$sql .= "FROM posts as p\n";
			$sql .= $extra['tag']?"\tinner join tags_rel as tr on tr.post_id = p.ID \n":null;
			$sql .= $extra['tag']?"\tinner join tags as t on t.tag_id = tr.tag_id \n":null;
		$sql .= "WHERE 1=1 \n";
			$sql .= $extra['status']?"\tAND status='".$extra['status']."' \n":null;
			$sql .= $extra['tag']?"\tAND t.urlfriendly='".$this->sql_escape($extra['tag'])."' \n":null;

		$valid = $this->findBySql($sql);
		
		if(empty($valid) == false)
			return $valid['total'];
		return 0;
	}

	/*
	 * Genera un url amigable a partir de una cadena de texto
	 * Function aVictorada De la Rocheada. /LaOnda
	 * Use bajo su propio riesgo.
	 */
	function buildUrl($name,$id=null,$validateExists=true){
		$name = str_replace('-',' ',$name);
		$a = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿŔŕ ¡?_.';
		$b = 'AAAAAAACEEEEIIIIDNOOOOOOUUUUYbsaaaaaaaceeeeiiiidnoooooouuuyybyRr     ';
		$name = strtr(utf8_decode($name), utf8_decode($a), $b);
		$name = trim($name); //sin espacios a los extremos.
		$values = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z',
							'a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z',
							'1','2','3','4','5','6','7','8','9','0',
							' ','-');
		$out = '';
		$len = strlen($name);
		for($chr=0;$chr<=$len-1;$chr++){
				if(in_array($name[$chr],$values,true))
				$out.= $name[$chr];
			}

		$out = preg_replace('/ +/','-',$out); //reduce todos espacios repetidos a uno solo.

		$c=0;
		if($validateExists){
			#Check if this urlFriendly exists on table posts
			do{ 
				++$c;
				/*
				 * id se utilizar para evitar compare con el mismo registro y agregue _2 al final.
				 */
				if($id)
				$this->db->query("SELECT urlFriendly FROM posts WHERE urlFriendly='$out".($c>1?"_$c":'')."' and id<>$id");
					else
				$this->db->query("SELECT urlFriendly FROM posts WHERE urlFriendly='$out".($c>1?"_$c":'')."'");
			}while($this->db->fetchRow());
		}
		
		$urlFriendly = $out.($c>1?"_$c":'');
		
		return $urlFriendly;
	}
	
	public function getTags($post_id,$type=null){
		$post_id = (int) $post_id;

		$sql = "SELECT t.tag,t.urlfriendly \n";
		$sql .= "FROM tags as t \n";
			$sql .= "\tinner join tags_rel as tr on tr.tag_id = t.tag_id \n";
			$sql .= "\tinner join posts as p on p.ID = tr.post_id \n";
		$sql .= "WHERE p.ID = $post_id \n";
		$sql .= "ORDER BY t.tag";

		$this->db->query($sql);
		
		$tags = array();
		while($row = $this->db->fetchRow())
			$tags[] = $row; 
		
		if($type=='string'){
			$out = null;
			foreach($tags as $tag)
				$out .= strpos($tag['tag'],' ')?"\"{$tag['tag']}\" ":$tag['tag'].' ';
			return $out;
		}
		
		return $tags;
	}
	
	public function updateTags($post_id,$tags_raw=''){		
		if(get_magic_quotes_gpc())
			$tags_raw = stripslashes($tags_raw);

		/* 
		 * Extrayendo Etiquetas que estan entre "tag" o 'tag'.
		 */
		$tags_raw = preg_replace('/\s+?/',' ',$tags_raw);		
		$tags_regexp = '/[\"\']((?:[^\S]|[\s\w])+?)[\"\']/';
		preg_match_all($tags_regexp,$tags_raw,$tags);
		$tags_raw = preg_replace($tags_regexp,'',$tags_raw);
		unset($tags[0]);
		$tags = $tags[1];

		/*
		 * Extraemos el resto de tags separados por espacios.
		 */		
		$tags = array_merge($tags,array_unique(explode(' ',$tags_raw)));
		unset($tags_raw);

		/*
		 * Actualizamos tags para el $post_id pasado como parametro.
		 * $tags contiene todos los tags que se relacionaran con la entrada actual.
		 */
		foreach($tags as $tag){
			if($tag != ''){
				$tag = $this->sql_escape($tag);

				$tag_id = $this->tagExists($tag);
				if($tag_id){
					#1.- Si no existe la relacion agregarla
					$this->db->query("select * from tags_rel where post_id=$post_id and tag_id=$tag_id");
					$row = $this->db->fetchRow();
					if(!$row)
						$this->db->query("insert into tags_rel(post_id,tag_id) values($post_id,$tag_id)");
				}else{
					$tag_id = $this->addTag($tag);
					$this->db->query("insert into tags_rel(post_id,tag_id) values($post_id,$tag_id)");
				}
			}
		}
	}
	
	public function tagExists($tag){
		$tag = $this->sql_escape($tag);
		$this->db->query("select * from tags as t where t.urlfriendly = '".$this->buildUrl($tag,null,false)."'"); 
		if($row = $this->db->fetchRow())
			return $row['tag_id'];
		return false;
	}
	
	public function addTag($tag){
		$tag = $this->sql_escape($tag);
		$this->db->query("insert into tags(tag,urlfriendly) values('".$tag."','".$this->buildUrl($tag,null,false)."')");
		return $this->db->lastId();
	}
	
	public function getPostsByTag($tag,$limitQuery){
		$tag = $this->sql_escape($tag);
		$sql = "SELECT \n";
			$sql .= "\tp.ID,p.urlfriendly,p.title,IF(POSITION('<!--more-->' IN p.content)>0,MID(p.content,1,POSITION('<!--more-->' IN p.content)-1),p.content) as content \n";
		$sql .= "FROM posts as p\n";
			$sql .= "\tinner join tags_rel as tr on tr.post_id = p.ID \n";
			$sql .= "\tinner join tags as t on t.tag_id = tr.tag_id \n";
		$sql .= "WHERE \n";
			$sql .= "\tp.status='publish' and t.urlfriendly='$tag' \n";
		$sql .= "ORDER BY \n";
			$sql .= "\tID DESC\n";
		$sql .= "LIMIT \n";
			$sql .= "\t$limitQuery";
		
		$this->db->query($sql);

		$rows = array();
		while($row = $this->db->fetchRow()){
			$rows[] = $row;
		}
		return $rows;
	}
	
}

?>
