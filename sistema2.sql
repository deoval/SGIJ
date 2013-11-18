CREATE DATABASE  IF NOT EXISTS `Sistema2` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `Sistema2`;


DROP TABLE IF EXISTS `usuario`;
CREATE TABLE `usuario` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(100) DEFAULT NULL,
  `senha` varchar(100) DEFAULT NULL,
  `cargo` varchar(50) DEFAULT NULL,
  `nome` varchar(250) DEFAULT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=latin1;

LOCK TABLES `usuario` WRITE;
/*!40000 ALTER TABLE `usuario` DISABLE KEYS */;
INSERT INTO `usuario` VALUES (1,'admin','YXNk','advogado_socio','Administrador','123445'),(2,'secretaria','YXNk','secretaria','Secretaria','2152526546'),(3,'advogado1','YXNk','advogado','Advogado 1','');
/*!40000 ALTER TABLE `usuario` ENABLE KEYS */;
UNLOCK TABLES;

DROP TABLE IF EXISTS `cliente`;
CREATE TABLE `cliente` (
  `id_cliente` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(250) DEFAULT NULL,
  `email` varchar(250) DEFAULT NULL,
  `tipo_cliente` varchar(50) DEFAULT NULL,
  `tipo_pessoa` varchar(50) DEFAULT NULL,
  `estado` varchar(100) DEFAULT NULL,
  `cidade` varchar(100) DEFAULT NULL,
  `bairro` varchar(100) DEFAULT NULL,
  `endereco` varchar(100) DEFAULT NULL,
  `cep` varchar(20) DEFAULT NULL,
  `numero` varchar(20) DEFAULT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id_cliente`)
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
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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




DROP TABLE IF EXISTS `advogado`;

CREATE TABLE `advogado` (
  `id_advogado` int(11) NOT NULL AUTO_INCREMENT,
  `rg` varchar(20) DEFAULT NULL,
  `cpf` varchar(20) DEFAULT NULL,
  `numero_oab` varchar(100) DEFAULT NULL,
  `endereco` varchar(250) DEFAULT NULL,
  `numero` varchar(20) DEFAULT NULL,
  `cep` varchar(20) DEFAULT NULL,
  `bairro` varchar(100) DEFAULT NULL,
  `cidade` varchar(100) DEFAULT NULL,
  `estado` varchar(100) DEFAULT NULL,
  `advogado_id_advogado` int(11) NOT NULL,
  PRIMARY KEY (`id_advogado`),
  KEY `fk_usuario_advogado1_idx` (`advogado_id_advogado`),
  CONSTRAINT `fk_usuario_advogado1` FOREIGN KEY (`advogado_id_advogado`) REFERENCES `usuario` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

LOCK TABLES `advogado` WRITE;
/*!40000 ALTER TABLE `advogado` DISABLE KEYS */;
INSERT INTO `advogado` VALUES (1,'145523696-8','015.436.985-22','RJ123456','Rua 123','14','11232222','Realengo','Rio de Janeiro','RJ',1),(2,'12121212-9','035.103.510-35','RJ000000','Rua das Flores 1','33.250','12312-312','Realengo','Rio De Janeiro','Rio de Janeiro',3);
/*!40000 ALTER TABLE `advogado` ENABLE KEYS */;
UNLOCK TABLES;

DROP TABLE IF EXISTS `tarefas`;
CREATE TABLE `tarefas` (
  `id_tarefa` int(11) NOT NULL AUTO_INCREMENT,
  `data_e_hora` datetime DEFAULT NULL,
  `tarefa` varchar(3000) DEFAULT NULL,
  `advogado_id_advogado` int(11) NOT NULL,
  PRIMARY KEY (`id_tarefa`),
  KEY `fk_tarefas_advogado1_idx` (`advogado_id_advogado`),
  CONSTRAINT `fk_tarefas_advogado1` FOREIGN KEY (`advogado_id_advogado`) REFERENCES `advogado` (`id_advogado`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


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
  `localizacao_documentos` varchar(2000) DEFAULT NULL,
  `numero_processo_tj` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id_processo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `pagamentos`;
CREATE TABLE `pagamentos` (
  `id_pagamento` int(11) NOT NULL AUTO_INCREMENT,
  `plano_pagamento` varchar(100) DEFAULT NULL,
  `valor` varchar(50) DEFAULT NULL,
  `forma_pagamento` varchar(100) DEFAULT NULL,
  `parcelas_pagas` varchar(100) DEFAULT NULL,
  `vencimento` datetime DEFAULT NULL,
  `tempo_atraso` varchar(100) DEFAULT NULL,
  `status_pagamento` varchar(100) DEFAULT NULL,
  `processos_id_processo` int(11) NOT NULL,
  PRIMARY KEY (`id_pagamento`),
  KEY `fk_pagamentos_processos1_idx` (`processos_id_processo`),
  KEY `fk_pagamentos_1_idx` (`processos_id_processo`),
  CONSTRAINT `fk_pagamentos_1` FOREIGN KEY (`processos_id_processo`) REFERENCES `processos` (`id_processo`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `prazos_do_processo`;
CREATE TABLE `prazos_do_processo` (
  `id_prazo` int(11) NOT NULL AUTO_INCREMENT,
  `tipo_de_prazo` varchar(50) DEFAULT NULL,
  `data_inicio` datetime DEFAULT NULL,
  `data_limite` datetime DEFAULT NULL,
  `id_num_processo` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_prazo`),
  KEY `id_num_processo` (`id_num_processo`),
  CONSTRAINT `prazos_ibfk_1` FOREIGN KEY (`id_num_processo`) REFERENCES `processos` (`id_processo`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



