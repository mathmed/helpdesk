-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: 09-Jul-2018 às 09:04
-- Versão do servidor: 5.7.22-0ubuntu0.16.04.1
-- PHP Version: 7.0.28-0ubuntu0.16.04.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `chamados`
--


CREATE TABLE `cw_chamados` (
  `id` int(11) NOT NULL,
  `descricao` varchar(300) NOT NULL,
  `tipo` int(5) NOT NULL,
  `unidade` int(11) NOT NULL,
  `setor` int(11) NOT NULL,
  `grau` int(11) NOT NULL,
  `prazo` date NOT NULL,
  `data_chamado` datetime NOT NULL,
  `status_atual` varchar(15) NOT NULL,
  `emissor` int(11) NOT NULL,
  `responsavel` int(11) DEFAULT NULL,
  `outro_emissor` varchar(30) DEFAULT NULL,
  `nremarcadas` int(11) DEFAULT NULL,
  `nota` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



CREATE TABLE `cw_comentarios` (
  `id` int(11) NOT NULL,
  `id_chamado` int(11) NOT NULL,
  `comentario` varchar(300) NOT NULL,
  `emissor` int(11) NOT NULL,
  `data_comentario` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



CREATE TABLE `cw_historico` (
  `id` int(11) NOT NULL,
  `responsavel` int(11) NOT NULL,
  `acao` varchar(30) NOT NULL,
  `data_acao` datetime NOT NULL,
  `id_chamado` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



CREATE TABLE `cw_setores` (
  `id` int(11) NOT NULL,
  `setor` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



INSERT INTO `cw_setores` (`id`, `setor`) VALUES (1, 'Padrão');


CREATE TABLE `cw_tipos` (
  `id` int(11) NOT NULL,
  `tipo_chamado` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `cw_tipos` (`id`, `tipo_chamado`) VALUES (3, 'Padrão');


CREATE TABLE `cw_unidades` (
  `id` int(11) NOT NULL,
  `unidade` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


INSERT INTO `cw_unidades` (`id`, `unidade`) VALUES (1, 'Padrão');


CREATE TABLE `cw_usuarios` (
  `id` int(11) NOT NULL,
  `email` varchar(40) NOT NULL,
  `usuario` varchar(20) NOT NULL,
  `senha` varchar(20) NOT NULL,
  `nome` varchar(20) NOT NULL,
  `setor` int(11) NOT NULL,
  `cargo` int(11) NOT NULL,
  `sede` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



INSERT INTO `cw_usuarios` (`id`, `email`, `usuario`, `senha`, `nome`, `setor`, `cargo`, `sede`) VALUES
(1, 'email@email.com', 'admin', 'admin', 'admin', 1, 2, 1);


ALTER TABLE `cw_chamados`
  ADD PRIMARY KEY (`id`);


ALTER TABLE `cw_comentarios`
  ADD PRIMARY KEY (`id`);


ALTER TABLE `cw_historico`
  ADD PRIMARY KEY (`id`);


ALTER TABLE `cw_setores`
  ADD PRIMARY KEY (`id`);


ALTER TABLE `cw_tipos`
  ADD PRIMARY KEY (`id`);


ALTER TABLE `cw_unidades`
  ADD PRIMARY KEY (`id`);


ALTER TABLE `cw_usuarios`
  ADD PRIMARY KEY (`id`);


ALTER TABLE `cw_chamados`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

ALTER TABLE `cw_comentarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

ALTER TABLE `cw_historico`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

ALTER TABLE `cw_setores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

ALTER TABLE `cw_tipos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

ALTER TABLE `cw_unidades`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

ALTER TABLE `cw_usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;