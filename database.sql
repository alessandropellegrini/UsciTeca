CREATE TABLE IF NOT EXISTS `markers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `lat` float(10,6) NOT NULL,
  `lng` float(10,6) NOT NULL,
  `nome` varchar(60) NOT NULL,
  `indirizzo` varchar(70) NOT NULL,
  `luogo` varchar(70) NOT NULL,
  `provincia` varchar(2) NOT NULL,
  `contatto` varchar(30) NOT NULL,
  `telefono` varchar(10) NOT NULL,
  `cellulare` varchar(10) NOT NULL,
  `email` varchar(30) NOT NULL,
  `tipo` varchar(30) NOT NULL,
  `posti` int(11) NOT NULL,
  `pending` int(11) NOT NULL,
  `fuoco` tinyint(1) NOT NULL,
  `acqua` tinyint(1) NOT NULL,
  `campo` tinyint(1) NOT NULL,
  `gratis` tinyint(1) NOT NULL,
  `gioco` tinyint(1) NOT NULL,
  `ombra` tinyint(1) NOT NULL,
  `inserimento` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `aggiornamento` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=23 ;
