-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 21/06/2025 às 19:00
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `lojasistema`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `categoria`
--

CREATE TABLE `categoria` (
  `cod_categoria` int(11) NOT NULL,
  `descricao` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `compra`
--

CREATE TABLE `compra` (
  `id_compra` int(11) NOT NULL,
  `id_fornecedor` int(11) DEFAULT NULL,
  `data_compra` datetime DEFAULT NULL,
  `prazo_entrega` varchar(50) DEFAULT NULL,
  `data_pagamento` date DEFAULT NULL,
  `status_pagamento` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `cotacao`
--

CREATE TABLE `cotacao` (
  `id_cotacao` int(11) NOT NULL,
  `id_fornecedor` int(11) DEFAULT NULL,
  `data_cotacao` datetime DEFAULT NULL,
  `prazo_entrega` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `cotproduto`
--

CREATE TABLE `cotproduto` (
  `id_cotacao` int(11) NOT NULL,
  `codigo_produto` int(11) NOT NULL,
  `quantidade` int(11) DEFAULT NULL,
  `valor_unitario` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `fornecedorcategoria`
--

CREATE TABLE `fornecedorcategoria` (
  `id_forcate` int(11) NOT NULL,
  `id_produto` int(11) DEFAULT NULL,
  `id_fornecedor` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `frete`
--

CREATE TABLE `frete` (
  `id_frete` int(11) NOT NULL,
  `data_entrega` date DEFAULT NULL,
  `transportadora` varchar(100) DEFAULT NULL,
  `valor` decimal(10,2) DEFAULT NULL,
  `tipo_transporte` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `orcamento`
--

CREATE TABLE `orcamento` (
  `id_orcamento` int(11) NOT NULL,
  `id_cliente` int(11) DEFAULT NULL,
  `data_orcamento` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `orcamentoproduto`
--

CREATE TABLE `orcamentoproduto` (
  `id_orcamento` int(11) NOT NULL,
  `codigo_produto` int(11) NOT NULL,
  `quantidade` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `produtocompra`
--

CREATE TABLE `produtocompra` (
  `id_compra` int(11) NOT NULL,
  `codigo_produto` int(11) NOT NULL,
  `quantidade` int(11) DEFAULT NULL,
  `valor_unitario` decimal(10,2) DEFAULT NULL,
  `valor_total_compra` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `produtos`
--

CREATE TABLE `produtos` (
  `codigo_produto` int(11) NOT NULL,
  `descricao` varchar(255) NOT NULL,
  `cod_categoria` int(11) DEFAULT NULL,
  `ncm` varchar(25) DEFAULT NULL,
  `marca` varchar(100) DEFAULT NULL,
  `unidade_medida` varchar(20) DEFAULT NULL,
  `preco_custo_unidade` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `produtovenda`
--

CREATE TABLE `produtovenda` (
  `id_venda` int(11) NOT NULL,
  `codigo_produto` int(11) NOT NULL,
  `quantidade` int(11) DEFAULT NULL,
  `preco_compra` decimal(10,2) DEFAULT NULL,
  `margem_lucro` decimal(5,2) DEFAULT NULL,
  `impostos` decimal(5,2) DEFAULT NULL,
  `preco_venda` decimal(10,2) DEFAULT NULL,
  `id_frete` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tipousuario`
--

CREATE TABLE `tipousuario` (
  `id_tipo_usuario` int(11) NOT NULL,
  `descricao` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `tipousuario`
--

INSERT INTO `tipousuario` (`id_tipo_usuario`, `descricao`) VALUES
(0, 'admin'),
(1, 'cliente'),
(2, 'fornecedor');

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuario`
--

CREATE TABLE `usuario` (
  `id_usuario` int(11) NOT NULL,
  `cnpj` varchar(18) DEFAULT NULL,
  `razao_social` varchar(255) DEFAULT NULL,
  `nome_fantasia` varchar(255) DEFAULT NULL,
  `inscricao_estadual` varchar(20) DEFAULT NULL,
  `contato` varchar(255) DEFAULT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `tipo_usuario` int(11) DEFAULT NULL,
  `cep` varchar(10) DEFAULT NULL,
  `logradouro` varchar(255) DEFAULT NULL,
  `numero` int(11) DEFAULT NULL,
  `complemento` varchar(255) DEFAULT NULL,
  `bairro` varchar(100) DEFAULT NULL,
  `cidade` varchar(100) DEFAULT NULL,
  `estado` varchar(2) DEFAULT NULL,
  `login` varchar(100) DEFAULT NULL,
  `senha` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `usuario`
--

INSERT INTO `usuario` (`id_usuario`, `cnpj`, `razao_social`, `nome_fantasia`, `inscricao_estadual`, `contato`, `telefone`, `email`, `tipo_usuario`, `cep`, `logradouro`, `numero`, `complemento`, `bairro`, `cidade`, `estado`, `login`, `senha`) VALUES
(6, '32.754.123/6444-44', 'Rocha Fornecedor', 'Felipe Rafael Rocha', '12335447789955252', 'Felipe Rafael Rocha', '(19) 99976-8070', 'feliprocha196@gmail.com', 2, '13607-805', 'FRANCISCO BORGES', 1321, 'Casa', 'Jd. Santa Olivia 2', 'ARARAS', 'SP', 'Felipe Rafael Rocha', '$2y$10$kavxU8WReRXaTHKcwghJMOJCOCpZqp8m6Kufohdl1n/6DtEDRGlRa'),
(7, '32.656.584/8848-18', 'Isabela Rocha', 'Belinha BB', '1561165154546456', 'Isabela Borsonello Rocha', '(19) 99976-9625', 'Isabela@gmail.com', 1, '13607-610', 'Rua João Antonio', 1122, 'Casa', 'Ouro Verde', 'Araras', 'SP', 'Isabela Rocha', '$2y$10$DYpvRWkTa.1G8912DeriduihMDwDlHFb4EPScLFfNsIl8ROe5nSpm');

-- --------------------------------------------------------

--
-- Estrutura para tabela `venda`
--

CREATE TABLE `venda` (
  `id_venda` int(11) NOT NULL,
  `data_venda` date NOT NULL,
  `id_cliente` int(11) DEFAULT NULL,
  `valor_total_compra` decimal(10,2) DEFAULT NULL,
  `condicao_pagamento` varchar(100) DEFAULT NULL,
  `data_pagamento` date DEFAULT NULL,
  `status_pagamento` varchar(50) DEFAULT NULL,
  `prazo_entrega` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `categoria`
--
ALTER TABLE `categoria`
  ADD PRIMARY KEY (`cod_categoria`);

--
-- Índices de tabela `compra`
--
ALTER TABLE `compra`
  ADD PRIMARY KEY (`id_compra`),
  ADD KEY `id_fornecedor` (`id_fornecedor`);

--
-- Índices de tabela `cotacao`
--
ALTER TABLE `cotacao`
  ADD PRIMARY KEY (`id_cotacao`),
  ADD KEY `id_fornecedor` (`id_fornecedor`);

--
-- Índices de tabela `cotproduto`
--
ALTER TABLE `cotproduto`
  ADD PRIMARY KEY (`id_cotacao`,`codigo_produto`),
  ADD KEY `codigo_produto` (`codigo_produto`);

--
-- Índices de tabela `fornecedorcategoria`
--
ALTER TABLE `fornecedorcategoria`
  ADD PRIMARY KEY (`id_forcate`),
  ADD KEY `id_produto` (`id_produto`),
  ADD KEY `id_fornecedor` (`id_fornecedor`);

--
-- Índices de tabela `frete`
--
ALTER TABLE `frete`
  ADD PRIMARY KEY (`id_frete`);

--
-- Índices de tabela `orcamento`
--
ALTER TABLE `orcamento`
  ADD PRIMARY KEY (`id_orcamento`),
  ADD KEY `id_cliente` (`id_cliente`);

--
-- Índices de tabela `orcamentoproduto`
--
ALTER TABLE `orcamentoproduto`
  ADD PRIMARY KEY (`id_orcamento`,`codigo_produto`),
  ADD KEY `codigo_produto` (`codigo_produto`);

--
-- Índices de tabela `produtocompra`
--
ALTER TABLE `produtocompra`
  ADD PRIMARY KEY (`id_compra`,`codigo_produto`),
  ADD KEY `codigo_produto` (`codigo_produto`);

--
-- Índices de tabela `produtos`
--
ALTER TABLE `produtos`
  ADD PRIMARY KEY (`codigo_produto`),
  ADD KEY `cod_categoria` (`cod_categoria`);

--
-- Índices de tabela `produtovenda`
--
ALTER TABLE `produtovenda`
  ADD PRIMARY KEY (`id_venda`,`codigo_produto`),
  ADD KEY `codigo_produto` (`codigo_produto`),
  ADD KEY `id_frete` (`id_frete`);

--
-- Índices de tabela `tipousuario`
--
ALTER TABLE `tipousuario`
  ADD PRIMARY KEY (`id_tipo_usuario`);

--
-- Índices de tabela `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `login` (`login`),
  ADD KEY `tipo_usuario` (`tipo_usuario`);

--
-- Índices de tabela `venda`
--
ALTER TABLE `venda`
  ADD PRIMARY KEY (`id_venda`),
  ADD KEY `id_cliente` (`id_cliente`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `compra`
--
ALTER TABLE `compra`
  MODIFY `id_compra` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `cotacao`
--
ALTER TABLE `cotacao`
  MODIFY `id_cotacao` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `fornecedorcategoria`
--
ALTER TABLE `fornecedorcategoria`
  MODIFY `id_forcate` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `frete`
--
ALTER TABLE `frete`
  MODIFY `id_frete` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `orcamento`
--
ALTER TABLE `orcamento`
  MODIFY `id_orcamento` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `compra`
--
ALTER TABLE `compra`
  ADD CONSTRAINT `compra_ibfk_1` FOREIGN KEY (`id_fornecedor`) REFERENCES `usuario` (`id_usuario`);

--
-- Restrições para tabelas `cotacao`
--
ALTER TABLE `cotacao`
  ADD CONSTRAINT `cotacao_ibfk_1` FOREIGN KEY (`id_fornecedor`) REFERENCES `usuario` (`id_usuario`);

--
-- Restrições para tabelas `cotproduto`
--
ALTER TABLE `cotproduto`
  ADD CONSTRAINT `cotproduto_ibfk_1` FOREIGN KEY (`id_cotacao`) REFERENCES `cotacao` (`id_cotacao`),
  ADD CONSTRAINT `cotproduto_ibfk_2` FOREIGN KEY (`codigo_produto`) REFERENCES `produtos` (`codigo_produto`);

--
-- Restrições para tabelas `fornecedorcategoria`
--
ALTER TABLE `fornecedorcategoria`
  ADD CONSTRAINT `fornecedorcategoria_ibfk_1` FOREIGN KEY (`id_produto`) REFERENCES `produtos` (`codigo_produto`),
  ADD CONSTRAINT `fornecedorcategoria_ibfk_2` FOREIGN KEY (`id_fornecedor`) REFERENCES `usuario` (`id_usuario`);

--
-- Restrições para tabelas `orcamento`
--
ALTER TABLE `orcamento`
  ADD CONSTRAINT `orcamento_ibfk_1` FOREIGN KEY (`id_cliente`) REFERENCES `usuario` (`id_usuario`);

--
-- Restrições para tabelas `orcamentoproduto`
--
ALTER TABLE `orcamentoproduto`
  ADD CONSTRAINT `orcamentoproduto_ibfk_1` FOREIGN KEY (`id_orcamento`) REFERENCES `orcamento` (`id_orcamento`),
  ADD CONSTRAINT `orcamentoproduto_ibfk_2` FOREIGN KEY (`codigo_produto`) REFERENCES `produtos` (`codigo_produto`);

--
-- Restrições para tabelas `produtocompra`
--
ALTER TABLE `produtocompra`
  ADD CONSTRAINT `produtocompra_ibfk_1` FOREIGN KEY (`id_compra`) REFERENCES `compra` (`id_compra`),
  ADD CONSTRAINT `produtocompra_ibfk_2` FOREIGN KEY (`codigo_produto`) REFERENCES `produtos` (`codigo_produto`);

--
-- Restrições para tabelas `produtos`
--
ALTER TABLE `produtos`
  ADD CONSTRAINT `produtos_ibfk_1` FOREIGN KEY (`cod_categoria`) REFERENCES `categoria` (`cod_categoria`);

--
-- Restrições para tabelas `produtovenda`
--
ALTER TABLE `produtovenda`
  ADD CONSTRAINT `produtovenda_ibfk_1` FOREIGN KEY (`id_venda`) REFERENCES `venda` (`id_venda`),
  ADD CONSTRAINT `produtovenda_ibfk_2` FOREIGN KEY (`codigo_produto`) REFERENCES `produtos` (`codigo_produto`),
  ADD CONSTRAINT `produtovenda_ibfk_3` FOREIGN KEY (`id_frete`) REFERENCES `frete` (`id_frete`);

--
-- Restrições para tabelas `usuario`
--
ALTER TABLE `usuario`
  ADD CONSTRAINT `usuario_ibfk_1` FOREIGN KEY (`tipo_usuario`) REFERENCES `tipousuario` (`id_tipo_usuario`);

--
-- Restrições para tabelas `venda`
--
ALTER TABLE `venda`
  ADD CONSTRAINT `venda_ibfk_1` FOREIGN KEY (`id_cliente`) REFERENCES `usuario` (`id_usuario`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
