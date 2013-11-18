CREATE DATABASE  IF NOT EXISTS `simples_teste3` /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_bin */;
USE `simples_teste3`;

DROP TABLE IF EXISTS `usuario`;

CREATE TABLE `usuario` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(100) DEFAULT NULL,
  `senha` varchar(100) DEFAULT NULL,
  `cargo` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
LOCK TABLES `usuario` WRITE;
INSERT INTO `usuario` VALUES (1,'admin','YXNk','advogado_socio'),(2,'secretaria','YXNk','secretaria'),(3,'advogado1','YXNk','advogado');
UNLOCK TABLES;

DROP TABLE IF EXISTS `processos`;

CREATE TABLE `processos` (
  `id_processo` int(11) NOT NULL AUTO_INCREMENT,
  `cliente` int(11) NOT NULL,
  `advogado_alocado` int(11) NOT NULL,
  `natureza_da_acao` varchar(100) DEFAULT NULL,
  `tipo_acao` varchar(100) DEFAULT NULL,
  `data_abertura` datetime DEFAULT NULL,
  `posicao_cliente` varchar(250) DEFAULT NULL,
  `status_processo` varchar(100) DEFAULT NULL,
  `localizacao_documentos` text,
  `numero_processo_tj` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id_processo`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `cliente`;

CREATE TABLE `cliente` (
  `id_cliente` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(250) DEFAULT NULL,
  `email` varchar(250) DEFAULT NULL,
  `tipo_cliente` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id_cliente`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `dados_cliente`;

CREATE TABLE `dados_cliente` (
  `id_dados_cliente` int(11) NOT NULL AUTO_INCREMENT,
  `tipo_pessoa` varchar(50) DEFAULT NULL,
  `estado` varchar(100) DEFAULT NULL,
  `cidade` varchar(100) DEFAULT NULL,
  `bairro` varchar(100) DEFAULT NULL,
  `endereco` varchar(100) DEFAULT NULL,
  `cep` varchar(20) DEFAULT NULL,
  `numero` varchar(20) DEFAULT NULL,
  `dados_cliente_id_dados_cliente` int(11) NOT NULL,
  PRIMARY KEY (`id_dados_cliente`),
  KEY `fk_cliente_dados_cliente1_idx` (`dados_cliente_id_dados_cliente`),
  CONSTRAINT `fk_cliente_dados_cliente1` FOREIGN KEY (`dados_cliente_id_dados_cliente`) REFERENCES `cliente` (`id_cliente`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `telefones_clientes`;

CREATE TABLE `telefones_clientes` (
  `id_telefone` int(11) NOT NULL AUTO_INCREMENT,
  `numero_telefone` varchar(20) DEFAULT NULL,
  `id_telefone_cliente` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_telefone`),
  KEY `id_telefone_cliente` (`id_telefone_cliente`),
  CONSTRAINT `telefones_ibfk_1` FOREIGN KEY (`id_telefone_cliente`) REFERENCES `cliente` (`id_cliente`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `advogado`;

CREATE TABLE `advogado` (
  `id_advogado` int(11) NOT NULL AUTO_INCREMENT,
  `endereco` varchar(250) DEFAULT NULL,
  `cep` varchar(20) DEFAULT NULL,
  `estado` varchar(100) DEFAULT NULL,
  `cidade` varchar(100) DEFAULT NULL,
  `bairro` varchar(100) DEFAULT NULL,
  `numero` varchar(20) DEFAULT NULL,
  `cpf` varchar(20) DEFAULT NULL,
  `rg` varchar(20) DEFAULT NULL,
  `nome` varchar(250) DEFAULT NULL,
  `numero_oab` varchar(100) DEFAULT NULL,
  `advogado_id_advogado` int(11) NOT NULL,
  PRIMARY KEY (`id_advogado`),
  KEY `fk_usuario_advogado1_idx` (`advogado_id_advogado`),
  CONSTRAINT `fk_usuario_advogado1` FOREIGN KEY (`advogado_id_advogado`) REFERENCES `usuario` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
LOCK TABLES `advogado` WRITE;
INSERT INTO `advogado` VALUES (1,'Não tem endereco definido','sem cep definido','Rio de Janeiro','Rio de Janeiro','Realengo','sem numero definido','0987654','123456789','Administrador','0192834',1),(3,'Rua qualquer','121212','RJ','Rio de Janeiro','Realengo','1','123456789','123333333','Advogado 1','1234',3);
UNLOCK TABLES;

DROP TABLE IF EXISTS `pagamentos`;

CREATE TABLE `pagamentos` (
  `id_pagamento` int(11) NOT NULL AUTO_INCREMENT,
  `tempo_atraso` varchar(100) DEFAULT NULL,
  `plano_pagamento` varchar(100) DEFAULT NULL,
  `valor` varchar(50) DEFAULT NULL,
  `parcelas_pagas` varchar(100) DEFAULT NULL,
  `forma_pagamento` varchar(100) DEFAULT NULL,
  `status_pagamento` varchar(100) DEFAULT NULL,
  `vencimento` datetime DEFAULT NULL,
  `processos_id_processo` int(11) NOT NULL,
  PRIMARY KEY (`id_pagamento`),
  KEY `fk_pagamentos_processos1_idx` (`processos_id_processo`),
  KEY `fk_pagamentos_1_idx` (`processos_id_processo`),
  CONSTRAINT `fk_pagamentos_1` FOREIGN KEY (`processos_id_processo`) REFERENCES `processos` (`id_processo`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `pessoa_fisica`;

CREATE TABLE `pessoa_fisica` (
  `id_pessoa_fisica` int(11) NOT NULL AUTO_INCREMENT,
  `rg` varchar(20) DEFAULT NULL,
  `cpf` varchar(20) DEFAULT NULL,
  `id_codigo_cliente_fisica` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_pessoa_fisica`),
  KEY `id_codigo_cliente_fisico` (`id_codigo_cliente_fisica`),
  CONSTRAINT `pessoa_fisica_ibfk_1` FOREIGN KEY (`id_codigo_cliente_fisica`) REFERENCES `cliente` (`id_cliente`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `telefones_advogados`;

CREATE TABLE `telefones_advogados` (
  `id_telefone` int(11) NOT NULL AUTO_INCREMENT,
  `numero_telefone` varchar(20) DEFAULT NULL,
  `id_telefone_advogado` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_telefone`),
  KEY `id_telefone_advogado` (`id_telefone_advogado`),
  CONSTRAINT `telefones_ibfk_2` FOREIGN KEY (`id_telefone_advogado`) REFERENCES `usuario` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
LOCK TABLES `telefones_advogados` WRITE;
/*!40000 ALTER TABLE `telefones_advogados` DISABLE KEYS */;
INSERT INTO `telefones_advogados` VALUES (1,' ',1),(2,' ',2),(3,' ',3);
UNLOCK TABLES;

DROP TABLE IF EXISTS `pessoa_juridica`;

CREATE TABLE `pessoa_juridica` (
  `id_pessoa_juridica` int(11) NOT NULL AUTO_INCREMENT,
  `inscricao_estadual` varchar(50) DEFAULT NULL,
  `inscricao_municipal` varchar(50) DEFAULT NULL,
  `cnpj` varchar(50) DEFAULT NULL,
  `id_codigo_cliente_juridica` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_pessoa_juridica`),
  KEY `id_codigo_cliente_juridico` (`id_codigo_cliente_juridica`),
  CONSTRAINT `pessoa_juridica_ibfk_1` FOREIGN KEY (`id_codigo_cliente_juridica`) REFERENCES `cliente` (`id_cliente`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `tarefas`;

CREATE TABLE `tarefas` (
  `id_tarefa` int(11) NOT NULL AUTO_INCREMENT,
  `data_e_hora` datetime DEFAULT NULL,
  `tarefa` text,
  `advogado_id_advogado` int(11) NOT NULL,
  PRIMARY KEY (`id_tarefa`),
  KEY `fk_tarefas_advogado1_idx` (`advogado_id_advogado`),
  CONSTRAINT `fk_tarefas_advogado1` FOREIGN KEY (`advogado_id_advogado`) REFERENCES `advogado` (`id_advogado`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `prazos`;

CREATE TABLE `prazos` (
  `id_prazo` int(11) NOT NULL AUTO_INCREMENT,
  `tempo_do_prazo` varchar(50) DEFAULT NULL,
  `data_inicio` datetime DEFAULT NULL,
  `data_limite` datetime DEFAULT NULL,
  `id_num_processo` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_prazo`),
  KEY `id_num_processo` (`id_num_processo`),
  CONSTRAINT `prazos_ibfk_1` FOREIGN KEY (`id_num_processo`) REFERENCES `processos` (`id_processo`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

