CREATE TABLE IF NOT EXISTS `comments` (
  `suscribe` varchar(255) DEFAULT NULL,
  `user_id` varchar(255) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `content` text,
  `IP` varchar(255) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `author` varchar(255) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `ID_post` int(10) unsigned NOT NULL,
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`ID`)
)DEFAULT CHARSET=utf8;

INSERT INTO `comments` (`suscribe`, `user_id`, `type`, `status`, `content`, `IP`, `url`, `email`, `author`, `created`, `modified`, `ID_post`, `ID`) VALUES
('0', '1', '', '1', 'Maecenas mollis orci sit amet elit. Vivamus euismod enim iaculis dolor. Sed et dolor vitae nibh accumsan commodo. Quisque elementum odio a purus egestas tempor. Quisque elit. Nulla et est. Fusce diam diam, ullamcorper quis, molestie eu, convallis blandit, libero. Cras volutpat nulla sit amet nisl. Aliquam sit amet urna in nisl congue luctus. Nulla malesuada nunc vitae ligula. Aenean gravida. ', '127.0.0.1', 'http://codice-cms.org/lorenita', 'lorena@codice-cms.org', 'Lorena', '2009-02-05 11:08:30', '2009-02-05 11:08:30', 175, 25427),
('0', '1', '', '1', 'Maecenas mollis orci sit amet elit. Vivamus euismod enim iaculis dolor. Sed et dolor vitae nibh accumsan commodo. Quisque elementum odio a purus egestas tempor. Quisque elit. Nulla et est. Fusce diam diam, ullamcorper quis, molestie eu, convallis blandit, libero. Cras volutpat nulla sit amet nisl. Aliquam sit amet urna in nisl congue luctus. Nulla malesuada nunc vitae ligula. Aenean gravida. \r\n\r\nMaecenas mollis orci sit amet elit. Vivamus euismod enim iaculis dolor. Sed et dolor vitae nibh accumsan commodo. Quisque elementum odio a purus egestas tempor. Quisque elit. Nulla et est. Fusce diam diam, ullamcorper quis, molestie eu, convallis blandit, libero. Cras volutpat nulla sit amet nisl. Aliquam sit amet urna in nisl congue luctus. Nulla malesuada nunc vitae ligula. Aenean gravida. ', '127.0.0.1', 'http://test.com', 'laura@test.com', 'Laura', '2009-02-05 11:08:59', '2009-02-05 11:08:59', 175, 25428),
('0', '1', '', '1', 'Hola Laura, no entendÃ­ bien a que te refieres con "Â¿Maecenas...?" Â¡Explicate!', '127.0.0.1', 'http://mis-algoritmos.com', 'vyk2rr@gmail.com', 'Victor De la Rocha', '2009-02-05 11:09:53', '2009-02-05 11:09:53', 175, 25429),
('0', '1', '', '1', 'Hey!! fui censurado!!', '127.0.0.1', 'http://pecesama.net', 'pecesama@gmail.com', 'Pedro Santana', '2009-02-05 11:11:43', '2009-02-05 11:11:43', 175, 25430),
('0', '1', '', '1', 'eh? wtf?! de que hablas Pedro?', '127.0.0.1', 'http://mis-algoritmos.com', 'vyk2rr@gmail.com', 'Victor De la Rocha', '2009-02-05 11:12:11', '2009-02-05 11:12:11', 175, 25431);

CREATE TABLE IF NOT EXISTS `configurations` (
  `blogname` varchar(255) NOT NULL,
  `blogdescription` varchar(255) NOT NULL,
  `siteurl` varchar(255) NOT NULL,
  `current_theme` varchar(255) NOT NULL,
  `posts_per_page` varchar(255) NOT NULL,
  `ID_user` int(10) unsigned NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`ID_user`)
)DEFAULT CHARSET=utf8;

INSERT INTO `configurations` (`blogname`, `blogdescription`, `siteurl`, `current_theme`, `posts_per_page`, `ID_user`) VALUES
('Codice CMS', 'La pÃ¡gina que siempre estÃ¡ en onda :)', 'http://localhost/cms', 'stan512', '3', 1);

CREATE TABLE IF NOT EXISTS `links` (
  `name` varchar(255) NOT NULL,
  `link` varchar(255) NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`ID`)
)DEFAULT CHARSET=utf8;

INSERT INTO `links` (`name`, `link`, `created`, `modified`, `ID`) VALUES
('Pedro Santana', 'http://www.pecesama.net/', NULL, NULL, 2),
('Diagramas de flujo', 'http://www.mis-algoritmos.com/ejemplos/diagramas-flujo.html', NULL, NULL, 16),
('Programador PHP', 'http://www.vbracco.com.ar', NULL, NULL, 17),
('raVen', 'http://www.raven.com.ar/', NULL, NULL, 21),
('El ticus', 'http://www.elticus.com', NULL, NULL, 22);

CREATE TABLE IF NOT EXISTS `posts` (
  `urlfriendly` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text,
  `status` varchar(50) DEFAULT NULL,
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`ID`),
  FULLTEXT KEY `title` (`title`,`content`,`urlfriendly`)
)DEFAULT CHARSET=utf8;

INSERT INTO `posts` (`urlfriendly`, `title`, `content`, `status`, `ID`, `created`, `modified`) VALUES
('Post-1', 'Post 1 !"#$%&/()=?Â¡!', '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. In a leo at elit convallis bibendum. Mauris pellentesque est quis turpis. Quisque ligula urna, imperdiet ut, dignissim eu, laoreet non, elit. Nunc congue consequat metus. Cras et leo. Nulla facilisi. Sed lorem dui, congue viverra, vestibulum eu, lacinia dignissim, neque. Ut sodales. Etiam nibh dui, rhoncus a, sagittis a, commodo non, velit. Pellentesque quis pede. Praesent posuere. Cras justo lacus, consequat nec, mollis eget, vehicula sit amet, libero. Integer nec nibh quis tortor feugiat euismod. Nam a turpis vitae eros eleifend imperdiet. Morbi at elit ac lacus semper vehicula. Donec vestibulum justo sed eros. Duis commodo. Aenean tincidunt volutpat felis. In in sapien.</p>\r\n\r\n<!--more-->\r\n\r\n<p>Ut lacinia tincidunt velit. Donec eros magna, ornare vel, viverra sit amet, pellentesque vel, libero. Nullam ac magna. Aenean ultrices justo a augue facilisis bibendum. Etiam vitae neque. Nam in diam at lacus venenatis hendrerit. Quisque ultrices lorem quis elit. Cras egestas est ut quam. Sed lacinia, felis eget mattis vulputate, neque sapien elementum enim, a fringilla libero quam et risus. Aliquam dictum. Curabitur libero. Proin erat lacus, suscipit sed, fermentum et, placerat vel, felis. Vivamus dictum facilisis velit.</p>\r\n\r\n<p>Maecenas mollis orci sit amet elit. Vivamus euismod enim iaculis dolor. Sed et dolor vitae nibh accumsan commodo. Quisque elementum odio a purus egestas tempor. Quisque elit. Nulla et est. Fusce diam diam, ullamcorper quis, molestie eu, convallis blandit, libero. Cras volutpat nulla sit amet nisl. Aliquam sit amet urna in nisl congue luctus. Nulla malesuada nunc vitae ligula. Aenean gravida.</p>\r\n\r\n<p>Donec tempus, erat a semper fermentum, risus diam posuere nulla, vel sollicitudin eros ligula vitae lectus. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Proin hendrerit mi id velit. Proin diam dolor, venenatis id, ultrices sit amet, imperdiet ut, dui. Morbi convallis augue ut metus. Mauris viverra augue vitae metus. Phasellus tristique mi sit amet nibh. Fusce porta libero in arcu. Ut mattis. In eget metus ac ligula sagittis faucibus. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Vestibulum cursus. Ut a lorem. Aenean leo.</p>\r\n\r\n<p>Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Praesent vitae libero. Nunc vitae augue sit amet sapien interdum viverra. Donec id sem. Nam sem. Aenean ut nisl. Donec sed est et magna pharetra condimentum. Integer sit amet eros ut lacus rhoncus laoreet. Sed tortor. Quisque venenatis mauris vitae justo. Aliquam tempus nisi a ligula. Sed magna augue, facilisis a, egestas id, viverra id, orci. </p>', 'publish', 175, '2009-02-05 11:07:38', '2009-02-05 11:13:20'),
('Post-2', 'Post 2', '<ul>\r\n<li>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</li>\r\n<li>Fusce tincidunt egestas nisi.</li>\r\n<li>Pellentesque eget dui ac sem mollis sollicitudin.</li>\r\n<li>Donec vehicula arcu at nulla.</li>\r\n<li>Donec in odio elementum augue tempor fermentum.</li>\r\n<li>Praesent aliquam vestibulum eros.</li>\r\n<li>Nullam vestibulum aliquet neque.</li>\r\n</ul>\r\n\r\n<!--more-->\r\n\r\n<ul>\r\n<li>Nunc a tellus id velit congue interdum.</li>\r\n<li>Praesent ac diam sit amet justo sodales ullamcorper.</li>\r\n<li>Suspendisse hendrerit porttitor purus.</li>\r\n<li>Curabitur tempor rhoncus lacus.</li>\r\n<li>Aliquam mattis turpis sit amet lectus.</li>\r\n<li>Praesent lacinia tortor vestibulum justo.</li>\r\n</ul>\r\n\r\n<ul>\r\n<li>In in ante faucibus enim ultricies lacinia.</li>\r\n<li>Maecenas id elit ut nibh pretium adipiscing.</li>\r\n<li>Maecenas accumsan nisl sed mauris.</li>\r\n\r\n<li>Etiam in eros eget quam cursus accumsan.</li>\r\n<li>Nullam sed pede nec urna placerat aliquam.</li>\r\n<li>Nunc auctor eros ac orci.</li>\r\n<li>Sed vitae risus eget nulla malesuada pharetra.</li>\r\n</ul>', 'publish', 176, '2009-02-05 11:15:29', '2009-02-05 11:15:29'),
('Post-3', 'Post 3', '<p>Post 3 Post 3 Post 3 Post 3 Post 3 Post 3 Post 3 Post 3 Post 3 Post 3 Post 3 Post 3 Post 3 Post 3 Post 3 Post 3 Post 3 Post 3 Post 3 Post 3 Post 3 Post 3 Post 3 Post 3 Post 3 Post 3 Post 3 Post 3 Post 3 Post 3 Post 3 Post 3 Post 3 Post 3 Post 3 Post 3 Post 3 Post 3 Post 3 Post 3 Post 3 Post 3 Post 3 Post 3 Post 3 Post 3 Post 3 Post 3 Post 3 Post 3 Post 3 Post 3 Post 3 Post 3 Post 3 Post 3 </p>\r\n\r\n<p>Post 3 Post 3 Post 3 Post 3 Post 3 Post 3 Post 3 Post 3 Post 3 Post 3 Post 3 Post 3 Post 3 Post 3 Post 3 Post 3 Post 3 Post 3 Post 3 Post 3 Post 3 Post 3 Post 3 Post 3 Post 3 Post 3 Post 3 Post 3 Post 3 Post 3 Post 3 Post 3 Post 3 Post 3 Post 3 Post 3 Post 3 Post 3 Post 3 Post 3 Post 3 Post 3 Post 3 Post 3 Post 3 Post 3 Post 3 Post 3 Post 3 Post 3 Post 3 Post 3 Post 3 Post 3 Post 3 Post 3 </p>\r\n\r\n<p>Post 3 Post 3 Post 3 Post 3 Post 3 Post 3 Post 3 Post 3 Post 3 Post 3 Post 3 Post 3 Post 3 Post 3 Post 3 Post 3 Post 3 Post 3 Post 3 Post 3 Post 3 Post 3 Post 3 Post 3 Post 3 Post 3 Post 3 Post 3 Post 3 Post 3 Post 3 Post 3 Post 3 Post 3 Post 3 Post 3 Post 3 Post 3 Post 3 Post 3 Post 3 Post 3 Post 3 Post 3 Post 3 Post 3 Post 3 Post 3 Post 3 Post 3 Post 3 Post 3 Post 3 Post 3 Post 3 Post 3 </p>', 'publish', 177, '2009-02-05 11:16:15', '2009-02-05 11:16:15'),
('Cuatroooooo', 'Cuatroooooo', '<p>CUATRO</p>\r\n\r\n<p>CUATRO</p>\r\n\r\n<p>CUATRO</p>\r\n\r\n<p>CUATRO</p>\r\n\r\n<p>CUATRO</p>\r\n\r\n<p>CUATRO</p>\r\n\r\n<p>CUATRO</p>', 'publish', 178, '2009-02-05 11:17:17', '2009-02-05 11:17:17');

CREATE TABLE IF NOT EXISTS `tags` (
  `tag_id` int(11) NOT NULL AUTO_INCREMENT,
  `tag` varchar(255) NOT NULL,
  `urlfriendly` varchar(255) NOT NULL,
  PRIMARY KEY (`tag_id`)
)DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `tags_rel` (
  `tag_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL
)DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `users` (
  `id_user` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `login` varchar(100) NOT NULL DEFAULT '',
  `password` varchar(64) NOT NULL DEFAULT '',
  `email` varchar(100) DEFAULT NULL,
  `website` varchar(150) DEFAULT NULL,
  `about` text,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id_user`)
)DEFAULT CHARSET=utf8;


INSERT INTO `users` (`id_user`, `name`, `login`, `password`, `email`, `website`, `about`, `created`, `modified`) VALUES
(1, 'Usuario demo', 'admin', md5('demo'), 'test@test.com', 'http://www.codice-cms.org/', 'Prueba de perfil', NULL, NULL);
