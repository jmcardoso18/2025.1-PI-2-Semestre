CREATE DATABASE LojaSistema;
USE LojaSistema;

-- Tabela tipo de usuario
CREATE TABLE tipoUsuario (
    id_tipo_usuario INT PRIMARY KEY,
    descricao VARCHAR(30)
);

-- Tabela Usuario
CREATE TABLE usuario (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    cnpj VARCHAR(18), -- uma mesma empresa pode ter dois cadastro um sendo fornecedor outro como cliente
    razao_social VARCHAR(255),
    nome_fantasia VARCHAR(255),
    inscricao_estadual VARCHAR(20),
    contato VARCHAR(255),
    telefone VARCHAR(20),
    email VARCHAR(255) unique,
    tipo_usuario INT,
    cep VARCHAR(10),
    logradouro VARCHAR(255),
    numero int,
    complemento VARCHAR(255),
    bairro VARCHAR(100),
    cidade VARCHAR(100),
    estado VARCHAR(2),
    login VARCHAR(100) unique,
    senha VARCHAR(255),
    FOREIGN KEY (tipo_usuario) REFERENCES tipoUsuario(id_tipo_usuario)
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
    preco_custo_unidade DECIMAL(10,2),
    FOREIGN KEY (cod_categoria) REFERENCES Categoria(cod_categoria)
);

-- Tabela de junção Produto e Fornecedor
CREATE TABLE FornecedorCategoria (
    id_forcate INT AUTO_INCREMENT PRIMARY KEY,
    id_produto INT,
    id_fornecedor INT,
    FOREIGN KEY (id_produto) REFERENCES Produtos(codigo_produto),
    FOREIGN KEY (id_fornecedor) REFERENCES usuario(id_usuario)
);

-- Tabela Orçamento
CREATE TABLE orcamento (
    id_orcamento INT AUTO_INCREMENT PRIMARY KEY,
    id_cliente INT,
    data_orcamento DATETIME,
    FOREIGN KEY (id_cliente) REFERENCES usuario(id_usuario)
);

-- Tabela Orcamento e produto, como produto é multivalorado é necessário uma tabela ligando
CREATE TABLE orcamentoproduto(
	 id_orcamento INT,
	 codigo_produto INT,
    quantidade INT,
    PRIMARY KEY (id_orcamento, codigo_produto),
    FOREIGN KEY (id_orcamento) REFERENCES orcamento(id_orcamento),
    FOREIGN KEY (codigo_produto) REFERENCES Produtos(codigo_produto)
);

-- Tabela Cotação
CREATE TABLE cotacao (
    id_cotacao INT AUTO_INCREMENT PRIMARY KEY,
    id_fornecedor INT,
    data_cotacao DATETIME,
    prazo_entrega VARCHAR(50),
    FOREIGN KEY (id_fornecedor) REFERENCES usuario(id_usuario)
);

-- Tabela cotacao e produto

CREATE TABLE cotproduto(
	 id_cotacao INT,
	 codigo_produto INT,
    quantidade INT,
    valor_unitario DECIMAL(10,2),
    PRIMARY KEY (id_cotacao, codigo_produto),
    FOREIGN KEY (id_cotacao) REFERENCES cotacao(id_cotacao),
    FOREIGN KEY (codigo_produto) REFERENCES Produtos(codigo_produto)
);
-- Tabela Compra
CREATE TABLE compra (
    id_compra INT AUTO_INCREMENT PRIMARY KEY,
    id_fornecedor INT,
    data_compra DATETIME,
    prazo_entrega VARCHAR(50),
    data_pagamento DATE,
    status_pagamento VARCHAR(50),
    FOREIGN KEY (id_fornecedor) REFERENCES usuario(id_usuario)
);

-- Tabela produto e compra 
CREATE TABLE produtocompra(
	 id_compra INT,
	 codigo_produto INT,
    quantidade INT,
    valor_unitario DECIMAL(10,2),
    valor_total_compra DECIMAL(10,2),
    PRIMARY KEY (id_compra, codigo_produto),
	 FOREIGN KEY (id_compra) REFERENCES compra(id_compra),
    FOREIGN KEY (codigo_produto) REFERENCES Produtos(codigo_produto)
	);

-- Tabela Venda
CREATE TABLE venda (
    id_venda int PRIMARY KEY,
    data_venda DATE NOT NULL,
    id_cliente INT,
    valor_total_compra DECIMAL(10,2),
    condicao_pagamento VARCHAR(100),
    data_pagamento DATE,
    status_pagamento VARCHAR(50),
    prazo_entrega VARCHAR(50),
    FOREIGN KEY (id_cliente) REFERENCES usuario(id_usuario)
);

-- tabela produto/venda
CREATE TABLE produtovenda(
	 id_venda INT,
	 codigo_produto INT,
    quantidade INT,
    preco_compra DECIMAL(10,2),
    margem_lucro DECIMAL(5,2),
    impostos DECIMAL(5,2),
    preco_venda DECIMAL(10,2),
    id_frete INT,
    PRIMARY KEY (id_venda, codigo_produto),
    FOREIGN KEY (id_frete) REFERENCES frete (id_frete),
    FOREIGN KEY (id_venda) REFERENCES venda(id_venda),
    FOREIGN KEY (codigo_produto) REFERENCES Produtos(codigo_produto)
);
-- Tabela Frete
CREATE TABLE frete (
    id_frete INT AUTO_INCREMENT PRIMARY KEY,
    data_entrega DATE,
    transportadora VARCHAR(100),
    valor DECIMAL(10,2),
    tipo_transporte VARCHAR(50),
);
