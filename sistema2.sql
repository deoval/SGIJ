-- phpMyAdmin SQL Dump
-- version 4.0.4.1
-- http://www.phpmyadmin.net
--
-- Máquina: 127.0.0.1
-- Data de Criação: 15-Nov-2013 às 21:43
-- Versão do servidor: 5.5.32
-- versão do PHP: 5.4.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de Dados: `sistema2`
--
CREATE DATABASE IF NOT EXISTS `sistema2` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `sistema2`;

-- --------------------------------------------------------

--
-- Estrutura da tabela `advogado`
--

CREATE TABLE IF NOT EXISTS `advogado` (
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
  KEY `fk_usuario_advogado1_idx` (`advogado_id_advogado`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Extraindo dados da tabela `advogado`
--

INSERT INTO `advogado` (`id_advogado`, `rg`, `cpf`, `numero_oab`, `endereco`, `numero`, `cep`, `bairro`, `cidade`, `estado`, `advogado_id_advogado`) VALUES
(1, '145523696-8', '015.436.985-22', 'RJ123456', 'Rua 123', '14', '11232222', 'Realengo', 'Rio de Janeiro', 'ES', 1),
(2, '12121212-9', '035.103.510-35', 'RJ000000', 'Rua das Flores 1', '33.250', '12312-312', 'Realengo', 'Rio De Janeiro', 'Rio de Janeiro', 3),
(4, '34', '555.777.888-99', '3333333333333333332', 'rrt', '699', '23223-444', 'oleria', 'Rio De Janeiro', 'RJ', 5);

-- --------------------------------------------------------

--
-- Estrutura da tabela `cliente`
--

CREATE TABLE IF NOT EXISTS `cliente` (
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
  `telefone_alternativo` varchar(20) DEFAULT NULL,
  `telefone_celular` varchar(20) DEFAULT NULL,
  `fax` varchar(20) DEFAULT NULL,
  `observacao` text,
  PRIMARY KEY (`id_cliente`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Extraindo dados da tabela `cliente`
--

INSERT INTO `cliente` (`id_cliente`, `nome`, `email`, `tipo_cliente`, `tipo_pessoa`, `estado`, `cidade`, `bairro`, `endereco`, `cep`, `numero`, `telefone`, `telefone_alternativo`, `telefone_celular`, `fax`, `observacao`) VALUES
(1, 'Amelia', 'amelia@yahoo.com.br', 'Mensalista', 'pessoa_fisica', 'MG', 'Dren', 'Cosmos', 'R. Adriana', '43544-666', '44', '21 34567789', '21 26898999', '21 99887777', '21 25554333', 'Contato por telefone Celular'),
(2, 'Sandra Gomides', 'soniagomides@yahoo.com.br', 'Varejista', 'pessoa_fisica', 'RJ', 'Rio de Janeiro', 'São Cristovão', 'R. Bela', '22777-123', '19', '21 23478899', '', '', '', ''),
(3, 'Henpes', 'henpes@henpes.com', 'Mensalista', 'pessoa_juridica', 'RJ', 'Rio de Janeiro', 'Penha', 'R. Farinha', '21098-889', '56', '21 287636666', '21 342555788', '', '21 654455522', 'Contato com secretária'),
(4, 'Adrian Carvalho ', 'Adrianc@gmail.com', 'Varejista', 'pessoa_fisica', 'RJ', 'Rio de Janeiro', 'Olaria', 'R. Sabiá', '23455-555', '234', '21 33764884', '21 32565688', '21 98880988', '21 26567777', 'Atende em qualquer telefone'),
(6, 'aser', 's@www.com', 'Mensalista', 'pessoa_fisica', 'RJ', 'Rio De Janeiro', 'ed', 'r. lol', '55555-555', '22', '21 23333333', '', '', '', ''),
(7, 'James Weslley', 'james_wjr@hotmail.com', 'Mensalista', 'pessoa_fisica', 'RJ', 'Rio De Janeiro', 'Realengo', 'Rua das Flores 1', '32312-123', '33.250', '2123123213', '', '', '', '');

-- --------------------------------------------------------

--
-- Estrutura da tabela `pagamentos`
--

CREATE TABLE IF NOT EXISTS `pagamentos` (
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
  KEY `fk_pagamentos_1_idx` (`processos_id_processo`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Extraindo dados da tabela `pagamentos`
--

INSERT INTO `pagamentos` (`id_pagamento`, `plano_pagamento`, `valor`, `forma_pagamento`, `parcelas_pagas`, `vencimento`, `tempo_atraso`, `status_pagamento`, `processos_id_processo`) VALUES
(1, 'parcelado', '350.00', 'deposito em conta', '3', '2013-10-25 19:03:24', '0', 'quitado', 5),
(2, 'y', '5000.00', 'cheque', '1', '2013-10-25 21:56:24', '0', 'quitado', 9),
(3, 'tres', '3000.00', 'deposito em conta', '1', '2013-11-15 21:15:47', '0', 'pendente', 5),
(5, 'regular', '34500.00', 'cheque', '1', '2013-11-18 16:09:12', '0', 'pendente', 11);

-- --------------------------------------------------------

--
-- Estrutura da tabela `pessoa_fisica`
--

CREATE TABLE IF NOT EXISTS `pessoa_fisica` (
  `id_pessoa_fisica` int(11) NOT NULL AUTO_INCREMENT,
  `rg` varchar(20) DEFAULT NULL,
  `cpf` varchar(20) DEFAULT NULL,
  `id_codigo_cliente_fisica` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_pessoa_fisica`),
  KEY `id_codigo_cliente_fisico` (`id_codigo_cliente_fisica`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Extraindo dados da tabela `pessoa_fisica`
--

INSERT INTO `pessoa_fisica` (`id_pessoa_fisica`, `rg`, `cpf`, `id_codigo_cliente_fisica`) VALUES
(1, '2233', '153.434.343-43', 1),
(2, '10189829-2', '080.027.234-25', 2),
(3, '234444444444', '347.878.498-08', 4),
(5, '2222', '153.434.343-43', 6),
(6, '1232332322', '434.341.434-34', 7);

-- --------------------------------------------------------

--
-- Estrutura da tabela `pessoa_juridica`
--

CREATE TABLE IF NOT EXISTS `pessoa_juridica` (
  `id_pessoa_juridica` int(11) NOT NULL AUTO_INCREMENT,
  `inscricao_estadual` varchar(50) DEFAULT NULL,
  `inscricao_municipal` varchar(50) DEFAULT NULL,
  `cnpj` varchar(50) DEFAULT NULL,
  `id_codigo_cliente_juridica` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_pessoa_juridica`),
  KEY `id_codigo_cliente_juridico` (`id_codigo_cliente_juridica`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Extraindo dados da tabela `pessoa_juridica`
--

INSERT INTO `pessoa_juridica` (`id_pessoa_juridica`, `inscricao_estadual`, `inscricao_municipal`, `cnpj`, `id_codigo_cliente_juridica`) VALUES
(1, '2378748798984', '009092892032389', '76.365.566/6330-99', 3);

-- --------------------------------------------------------

--
-- Estrutura da tabela `prazos_do_processo`
--

CREATE TABLE IF NOT EXISTS `prazos_do_processo` (
  `id_prazo` int(11) NOT NULL AUTO_INCREMENT,
  `tipo_de_prazo` varchar(50) DEFAULT NULL,
  `data_inicio` datetime DEFAULT NULL,
  `data_limite` datetime DEFAULT NULL,
  `id_num_processo` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_prazo`),
  KEY `id_num_processo` (`id_num_processo`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Extraindo dados da tabela `prazos_do_processo`
--

INSERT INTO `prazos_do_processo` (`id_prazo`, `tipo_de_prazo`, `data_inicio`, `data_limite`, `id_num_processo`) VALUES
(1, 'contestacao', '2013-10-28 00:00:00', '2013-11-13 00:00:00', 5),
(3, 'contestacao', '2013-11-12 00:00:00', '2013-11-27 00:00:00', 5),
(4, 'contestacao', '2013-11-15 00:00:00', '2013-11-30 00:00:00', 6),
(5, 'contestacao', '2013-11-15 00:00:00', '2013-11-30 00:00:00', 5);

-- --------------------------------------------------------

--
-- Estrutura da tabela `processos`
--

CREATE TABLE IF NOT EXISTS `processos` (
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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;

--
-- Extraindo dados da tabela `processos`
--

INSERT INTO `processos` (`id_processo`, `cliente`, `advogado_alocado`, `natureza_da_acao`, `tipo_acao`, `data_abertura`, `posicao_cliente`, `status_processo`, `localizacao_documentos`, `numero_processo_tj`) VALUES
(5, 1, 3, 'pequenas causas', 'Injúria', '2013-10-24 00:00:00', 'Reu', 'em andamento', 'Pasta 1', '536782879802'),
(6, 1, 3, 'trabalhista', 'Trabalhador lesado', '2013-10-26 00:00:00', 'Autor', 'em andamento', 'Pasta 2', '746738999930038930'),
(7, 1, 1, 'penal', 'roubo', '2013-10-25 00:00:00', 'Autor', 'em andamento', 'Pasta 1', '677557576514333'),
(8, 1, 1, 'penal', 'y', '2013-10-25 00:00:00', 'Reu', 'em andamento', '66', '9999999999'),
(9, 2, 1, 'penal', 'ty', '2013-10-25 00:00:00', 'Reu', 'em andamento', 'i', '777'),
(11, 2, 5, 'familia', 'rrt', '2013-11-18 00:00:00', 'Autor', 'em andamento', 'salra', '33');

-- --------------------------------------------------------

--
-- Estrutura da tabela `tarefas`
--

CREATE TABLE IF NOT EXISTS `tarefas` (
  `id_tarefa` int(11) NOT NULL AUTO_INCREMENT,
  `data_e_hora` datetime DEFAULT NULL,
  `tarefa` varchar(3000) DEFAULT NULL,
  `advogado_id_advogado` int(11) NOT NULL,
  PRIMARY KEY (`id_tarefa`),
  KEY `fk_tarefas_advogado1_idx` (`advogado_id_advogado`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Extraindo dados da tabela `tarefas`
--

INSERT INTO `tarefas` (`id_tarefa`, `data_e_hora`, `tarefa`, `advogado_id_advogado`) VALUES
(1, '2013-11-19 08:11:54', 'Ida ao Fórum Barra', 3),
(3, '2013-11-18 08:08:00', 'Abrir relatório de tarefas', 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `usuario`
--

CREATE TABLE IF NOT EXISTS `usuario` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(100) DEFAULT NULL,
  `senha` varchar(100) DEFAULT NULL,
  `cargo` varchar(50) DEFAULT NULL,
  `nome` varchar(250) DEFAULT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `telefone_alternativo` varchar(20) DEFAULT NULL,
  `telefone_celular` varchar(20) DEFAULT NULL,
  `fax` varchar(20) DEFAULT NULL,
  `observacao` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Extraindo dados da tabela `usuario`
--

INSERT INTO `usuario` (`id`, `login`, `senha`, `cargo`, `nome`, `telefone`, `telefone_alternativo`, `telefone_celular`, `fax`, `observacao`) VALUES
(1, 'admin', 'YXNk', 'advogado_socio', 'Administrador', '(21)12345678', NULL, NULL, '', 'obs qualquer'),
(2, 'secretaria', 'YXNk', 'secretaria', 'Secretaria', '(22)22334455', '(23)3456-9876', '(99)9999-0000', 'fax1 - 99887766', 'obs'),
(3, 'advogado1', 'YXNk', 'advogado', 'Advogado 1', '(21)98887666', '(19)92365-9658', NULL, NULL, 'ops'),
(5, 'alex', 'YXNk', 'advogado', 'Alex ', '21 34877889', '', '', '', '');

--
-- Constraints for dumped tables
--

--
-- Limitadores para a tabela `advogado`
--
ALTER TABLE `advogado`
  ADD CONSTRAINT `fk_usuario_advogado1` FOREIGN KEY (`advogado_id_advogado`) REFERENCES `usuario` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `pagamentos`
--
ALTER TABLE `pagamentos`
  ADD CONSTRAINT `fk_pagamentos_1` FOREIGN KEY (`processos_id_processo`) REFERENCES `processos` (`id_processo`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `pessoa_fisica`
--
ALTER TABLE `pessoa_fisica`
  ADD CONSTRAINT `pessoa_fisica_ibfk_1` FOREIGN KEY (`id_codigo_cliente_fisica`) REFERENCES `cliente` (`id_cliente`);

--
-- Limitadores para a tabela `pessoa_juridica`
--
ALTER TABLE `pessoa_juridica`
  ADD CONSTRAINT `pessoa_juridica_ibfk_1` FOREIGN KEY (`id_codigo_cliente_juridica`) REFERENCES `cliente` (`id_cliente`);

--
-- Limitadores para a tabela `prazos_do_processo`
--
ALTER TABLE `prazos_do_processo`
  ADD CONSTRAINT `prazos_do_processo_ibfk_1` FOREIGN KEY (`id_num_processo`) REFERENCES `processos` (`id_processo`) ON DELETE CASCADE ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
