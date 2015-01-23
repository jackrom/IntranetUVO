

CREATE TABLE `controladores` (
  `ID` int(11) NOT NULL auto_increment,
  `controlador` varchar(25) NOT NULL,
  `activo` tinyint(1) NOT NULL,
  PRIMARY KEY  (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;



CREATE TABLE IF NOT EXISTS `grupos` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `grupos`
--

INSERT INTO `grupos` (`ID`, `nombre`) VALUES
(1, 'General');

-- --------------------------------------------------------



CREATE TABLE IF NOT EXISTS `miembros_de_grupos` (
  `usuario` int(11) NOT NULL,
  `grupo` int(11) NOT NULL,
  PRIMARY KEY (`usuario`,`grupo`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


CREATE TABLE IF NOT EXISTS `usuarios` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `usuario` varchar(255) NOT NULL,
  `password_hash` varchar(40) NOT NULL,
  `password_salt` varchar(5) NOT NULL,
  `email` varchar(255) NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT '0',
  `admin` tinyint(1) NOT NULL DEFAULT '0',
  `baneado` tinyint(1) NOT NULL DEFAULT '0',
  `pwd_reset_key` varchar(15) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


