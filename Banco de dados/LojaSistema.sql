CREATE DATABASE LojaSistema;
USE LojaSistema;

-- Tabela Usuario
CREATE TABLE Usuario (
    cnpj VARCHAR(18) PRIMARY KEY,
    razao_social VARCHAR(255),
    nome_fantasia VARCHAR(255),
    inscricao_estadual VARCHAR(20),
    contato VARCHAR(255),
    telefone VARCHAR(20),
    email VARCHAR(255),
    tipo_usuario VARCHAR(20),
    cep VARCHAR(10),
    logradouro VARCHAR(255),
    numero VARCHAR(10),
    complemento VARCHAR(255),
    bairro VARCHAR(100),
    cidade VARCHAR(100),
    estado VARCHAR(2),
    login VARCHAR(25),
    senha VARCHAR(20)
);

-- Tabela Categoria
CREATE TABLE Categoria (
    cod_categoria INT PRIMARY KEY,
    descricao VARCHAR(255) NOT NULL
);

-- Tabela Produtos
CREATE TABLE Produtos (
    codigo_produto INT PRIMARY KEY,
    descricao VARCHAR(255) NOT NULL,
    cod_categoria INT,
    ncm VARCHAR(25),
    marca VARCHAR(100),
    unidade_medida VARCHAR(20),
    preco_custo_unidade DOUBLE,
    FOREIGN KEY (cod_categoria) REFERENCES Categoria(cod_categoria)
);

-- Tabela de junção Produto e Fornecedor
CREATE TABLE FornecedorCategoria (
    id_forcate INT AUTO_INCREMENT PRIMARY KEY,
    id_produto INT,
    id_fornecedor VARCHAR(18),
    FOREIGN KEY (id_produto) REFERENCES Produtos(codigo_produto),
    FOREIGN KEY (id_fornecedor) REFERENCES Usuario(cnpj)
);

-- Tabela Orçamento
CREATE TABLE orcamento (
    id_orcamento INT AUTO_INCREMENT PRIMARY KEY,
    id_Cliente VARCHAR(18),
    data_Orcamento DATETIME,
    codigo_produto INT,
    quantidade INT,
    FOREIGN KEY (codigo_produto) REFERENCES Produtos(codigo_produto),
    FOREIGN KEY (id_Cliente) REFERENCES Usuario(cnpj)
);

-- Tabela Cotação
CREATE TABLE cotacao (
    id_cotacao INT AUTO_INCREMENT PRIMARY KEY,
    id_orcamento INT,
    id_fornecedor VARCHAR(18),
    data_cotacao DATETIME,
    codigo_produto INT,
    quantidade INT,
    valor_unitario DOUBLE(10,2),
    prazo_entrega VARCHAR(50),
    FOREIGN KEY (id_orcamento) REFERENCES orcamento(id_orcamento),
    FOREIGN KEY (codigo_produto) REFERENCES Produtos(codigo_produto),
    FOREIGN KEY (id_fornecedor) REFERENCES Usuario(cnpj)
);

-- Tabela Compra
CREATE TABLE compra (
    id_compra INT AUTO_INCREMENT PRIMARY KEY,
    id_fornecedor VARCHAR(18),
    data_compra DATETIME,
    codigo_produto INT,
    quantidade INT,
    valor_unitario DOUBLE(10,2),
    valor_total_compra DOUBLE(10,2),
    prazo_entrega VARCHAR(50),
    FOREIGN KEY (codigo_produto) REFERENCES Produtos(codigo_produto),
    FOREIGN KEY (id_fornecedor) REFERENCES Usuario(cnpj)
);

-- Tabela Venda
CREATE TABLE venda (
    id_venda VARCHAR(200) PRIMARY KEY,
    data_venda DATE NOT NULL,
    id_Cliente VARCHAR(18),
    cod_produto INT,
    preco_compra DOUBLE,
    margem_lucro DOUBLE,
    impostos DOUBLE,
    preco_venda DOUBLE,
    condicao_pagamento VARCHAR(100),
    prazo_entrega VARCHAR(50),
    FOREIGN KEY (id_Cliente) REFERENCES Usuario(cnpj),
    FOREIGN KEY (cod_produto) REFERENCES Produtos(codigo_produto)
);

-- Tabela Frete
CREATE TABLE frete (
    id_frete INT AUTO_INCREMENT PRIMARY KEY,
    id_venda VARCHAR(200),
    data_entrega DATE,
    transportadora VARCHAR(100),
    valor DOUBLE,
    tipo_transporte VARCHAR(50),
    FOREIGN KEY (id_venda) REFERENCES venda(id_venda)
);
