--
-- Table structure for table `markers`
--

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
  `inserimento` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fuoco` tinyint(1) NOT NULL,
  `acqua` tinyint(1) NOT NULL,
  `campo` tinyint(1) NOT NULL,
  `gratis` tinyint(1) NOT NULL,
  `gioco` tinyint(1) NOT NULL,
  `ombra` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=23 ;

--
-- Dumping data for table `markers`
--

INSERT INTO `markers` (`id`, `lat`, `lng`, `nome`, `indirizzo`, `luogo`, `provincia`, `contatto`, `telefono`, `cellulare`, `email`, `tipo`, `posti`, `pending`, `inserimento`, `fuoco`, `acqua`, `campo`, `gratis`, `gioco`, `ombra`) VALUES
(1, 42.222092, 12.139463, 'B.-P. Park', 'Strada Senza Nome', 'Bassano Romano', 'VT', 'Elio Caruso', '3380000000', '0600000000', 'info@bppark.it', 'entrambi', 304, 0, '0000-00-00 00:00:00', 1, 1, 1, 1, 1, 1),
(2, 41.944935, 12.332153, 'Prova', 'Via Romentino', '00123 Roma, Italia', 'RM', 'Pippo', '', '', 'indirizzo@email.it', 'tenda', 0, 0, '0000-00-00 00:00:00', 1, 0, 0, 0, 0, 0),
(3, 41.879017, 12.425237, 'Sede Roma 132', 'Via dei Baglioni, 24', '00164 Roma, Italia', 'RM', 'Pippo', '0600000000', '3350000000', 'pippo@disney.it', 'accantonamento', 30, 0, '0000-00-00 00:00:00', 0, 0, 1, 0, 0, 0),
(8, 42.220573, 12.188064, 'Monastero San Vincenzo', 'Via San Vincenzo, 88', '01030 Bassano Romano VT, Italia', 'VT', 'Don Valerio', '076163400', '', 'ospitalita@silvestrini.org', 'accantonamento', 40, 0, '0000-00-00 00:00:00', 0, 0, 0, 1, 0, 0),
(10, 41.855244, 12.951550, 'Prova con email', 'Via Sulle Mura, 10', 'Rocca di cave RM, Italia', 'RM', 'Pierino', '1', '', '', 'tenda', 0, 1, '2014-11-11 14:23:49', 0, 0, 0, 0, 0, 0),
(11, 41.740578, 12.097363, 'Nel mare!', 'Via del Faro', 'Italia', 'RM', 'Piroga', '', '', 'a@a.it', 'accantonamento', 9999999, 1, '2014-11-11 15:26:21', 0, 0, 0, 0, 0, 0),
(12, 41.740578, 12.097363, 'Nel mare!', 'Via del Faro', 'Italia', 'RM', 'Piroga', '', '', 'a@a.it', 'accantonamento', 9999999, 1, '2014-11-11 15:26:38', 0, 0, 0, 0, 0, 0),
(13, 41.740578, 12.097363, 'Nel mare!', 'Via del Faro', 'Italia', 'RM', 'Piroga', '', '', 'a@a.it', 'accantonamento', 9999999, 1, '2014-11-11 15:28:32', 0, 0, 0, 0, 0, 0),
(14, 41.740578, 12.097363, 'Nel mare!', 'Via del Faro', 'Italia', 'RM', 'Piroga', '', '', 'a@a.it', 'accantonamento', 9999999, 1, '2014-11-11 15:28:43', 0, 0, 0, 0, 0, 0),
(16, 41.668808, 12.539563, 'Pomezia', 'Via Laurentina, 23', '00040 Santa Procula RM, Italia', 'RM', 'Pomeziani', '0612345678', '', '', 'tenda', 0, 0, '2014-11-11 15:48:59', 0, 1, 0, 1, 0, 0),
(17, 41.865952, 12.465479, 'GDL', 'Via Oderisi da Gubbio, 118-122', '00146 Roma, Italia', 'RM', 'Nando', '33333333', '3333333', 'ter@ter.it', 'entrambi', 5, 0, '2014-11-11 16:29:39', 1, 0, 1, 0, 0, 1),
(18, 42.071724, 12.808728, 'Prova', 'Strada Provinciale 29a', 'Palombara Sabina RM, Italia', 'RM', 'Paperino', '123', '', '', 'tenda', 0, 0, '2014-11-13 14:55:04', 0, 1, 0, 0, 1, 0),
(19, 41.531197, 12.468152, 'Ultima prova', 'Via Campania, 6', 'Italia', '', 'Paperoga', '123', '', '', 'tenda', 0, 0, '2014-11-14 07:45:38', 0, 0, 0, 0, 1, 1),
(20, 41.836315, 12.832895, 'Posto', 'Piazza del Mercato', 'Zagarolo RM, Italia', 'RM', 'Paperino', '1234567890', '', '', 'tenda', 0, 0, '2014-11-23 17:56:08', 0, 0, 1, 0, 1, 1),
(21, 41.961533, 12.124829, 'ssssssssssssssssssssssssssssssssssssssssssssssssssssssssssss', 'A12 Civitavecchia - Romassssssssssssssssssssssssssssssssssssssssssssss', 'Ladispoli RM, Italiassssssssssssssssssssssssssssssssssssssssssssssssss', 'RM', 'ssssssssssssssssssssssssssssss', '1111111111', '1111111111', 'aaaa@aaaaaaaaaaaaaaaaaaaaaa.it', 'entrambi', 999, 0, '2014-11-23 18:01:16', 1, 1, 1, 1, 1, 1),
(22, 41.713928, 12.355542, '123456789012345678901234567890', '123456789012345678901234567890', 'Quartiere XXXV Lido di Castel Fusano, Lido di Ostia RM, Italia', 'RM', '123456789012345678901234567890', '1234567890', '1234567890', '2345678901234567890123456@a.it', 'entrambi', 999, 0, '2014-11-23 18:05:54', 1, 1, 1, 1, 1, 1);
