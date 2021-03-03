
--
-- tabela `certificado`
--

CREATE TABLE IF NOT EXISTS `certificado` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `evento` varchar(200) NOT NULL,
  `organizador_evento` varchar(100) NOT NULL,
  `nome_participante` varchar(100) NOT NULL,
  `email_participante` varchar(100) NOT NULL,
  `hash_validacao` varchar(32) NOT NULL,
  `data` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `hash_validacao` (`hash_validacao`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


--
-- tabela `config`
--

CREATE TABLE `config` (
  `id` int(11) NOT NULL,
  `url_site` varchar(100) NOT NULL,
  `nome_assinatura` varchar(255) NOT NULL,
  `titulo_assinatura` varchar(255) DEFAULT NULL,
  `endereco` varchar(255) DEFAULT NULL,
  `rodape` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



--
-- tabela `usuario`
--

CREATE TABLE IF NOT EXISTS `usuario` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `login` varchar(20) NOT NULL,
  `senha` varchar(32) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;


INSERT INTO `usuario` (`id`, `nome`, `email`, `login`, `senha`) VALUES
(1, 'Administrador', NULL, 'admin', '21232f297a57a5a743894a0e4a801fc3');
