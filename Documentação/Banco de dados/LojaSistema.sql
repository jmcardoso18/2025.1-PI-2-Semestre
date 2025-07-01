-- Script de criação do banco de dados 
-- Criando o banco
CREATE DATABASE lojasistema;

-- chamando o banco--
USE lojasistema;

-- Tabela: tipoUsuario
CREATE TABLE tipoUsuario (
    id_tipo_usuario INT PRIMARY KEY AUTO_INCREMENT,
    descricao VARCHAR(50) NOT NULL
);

-- Tabela: usuario
CREATE TABLE usuario (
    id_usuario INT PRIMARY KEY AUTO_INCREMENT,
    cnpj VARCHAR(18),
    razao_social VARCHAR(255),
    inscricao_estadual VARCHAR(20),
    contato VARCHAR(100),
    telefone VARCHAR(20),
    email VARCHAR(100),
    tipo_usuario INT,
    cep VARCHAR(10),
    logradouro VARCHAR(100),
    numero INT,
    complemento VARCHAR(50),
    bairro VARCHAR(50),
    cidade VARCHAR(50),
    estado VARCHAR(2),
    login VARCHAR(50),
    senha VARCHAR(100),
    FOREIGN KEY (tipo_usuario) REFERENCES tipoUsuario(id_tipo_usuario)
);

-- Tabela: categoria
CREATE TABLE categoria (
    id_categoria INT PRIMARY KEY AUTO_INCREMENT,
    descricao VARCHAR(100) NOT NULL
);

-- Tabela: produtos
CREATE TABLE produtos (
    id_produto INT PRIMARY KEY AUTO_INCREMENT,
    descricao VARCHAR(100),
    ncm VARCHAR(20),
    marca VARCHAR(50),
    unidade_medida VARCHAR(20),
    fk_categoria_id_categoria INT,
    FOREIGN KEY (fk_categoria_id_categoria) REFERENCES categoria(id_categoria)
);

-- Tabela: transportadora
CREATE TABLE transportadora (
    id_transportadora INT PRIMARY KEY AUTO_INCREMENT,
    descricao VARCHAR(100),
    data_entrega DATE,
    valor DECIMAL(10,2),
    tipo_transportadora VARCHAR(50)
);

-- Tabela: tipo_operacao
CREATE TABLE tipo_operacao (
    id_tipo_operacao INT PRIMARY KEY AUTO_INCREMENT,
    descricao VARCHAR(100)
);

-- Tabela: operacao
CREATE TABLE operacao (
    id_operacao INT PRIMARY KEY AUTO_INCREMENT,
    fk_usuario_id_usuario INT,
    data_operacao DATE,
    prazo_entrega VARCHAR(50),
    data_pagamento DATE,
    status_pagamento VARCHAR(50),
    valor_total_compra DECIMAL(10,2),
    fk_transportadora_id_transportadora INT,
    fk_tipo_operacao_id_tipo_operacao INT,
    FOREIGN KEY (fk_usuario_id_usuario) REFERENCES usuario(id_usuario),
    FOREIGN KEY (fk_transportadora_id_transportadora) REFERENCES transportadora(id_transportadora),
    FOREIGN KEY (fk_tipo_operacao_id_tipo_operacao) REFERENCES tipo_operacao(id_tipo_operacao)
);

-- Tabela: fornecedor_categoria
CREATE TABLE fornecedor_categoria (
    id_fornecedor INT,
    id_categoria INT,
    PRIMARY KEY (id_fornecedor, id_categoria),
    FOREIGN KEY (id_fornecedor) REFERENCES usuario(id_usuario),
    FOREIGN KEY (id_categoria) REFERENCES categoria(id_categoria)
);

-- Tabela: operacao_produto
CREATE TABLE operacao_produto (
    id_operacao INT,
    id_produto INT,
    quantidade INT,
    valor_unitario DECIMAL(10,2),
    valor_total_produtos DECIMAL(10,2),
    margem_lucro DECIMAL(5,2),
    imposto DECIMAL(5,2),
    preco_venda DECIMAL(10,2),
    PRIMARY KEY (id_operacao, id_produto),
    FOREIGN KEY (id_operacao) REFERENCES operacao(id_operacao),
    FOREIGN KEY (id_produto) REFERENCES produtos(id_produto)
);


-- Script de Inserção dos dados 
-- Inserções: tipoUsuario
INSERT INTO tipoUsuario (descricao) VALUES
('Cliente'),
('Fornecedor'),
('Administrador');

-- Inserções: categoria
INSERT INTO categoria (descricao) VALUES
('Informática'),
('Iluminação'),
('Cabos Elétricos'),
('Tubos e PEAD'),
('Pré-moldados');

-- Inserções: tipo_operacao
INSERT INTO tipo_operacao (descricao) VALUES
('Venda'),
('Compra'),
('Orçamento'),
('Cotação');

-- Inserções: usuario
INSERT INTO usuario (cnpj, razao_social, inscricao_estadual, contato, telefone, email, tipo_usuario, cep, logradouro, numero, complemento, bairro, cidade, estado, login, senha) VALUES
('12.345.678/0001-01', 'Comercial Tech LTDA', '1234567890', 'João Lima', '(11)98765-4321', 'joao@comtech.com', 1, '01001-000', 'Rua Alfa', 123, 'Sala 5', 'Centro', 'São Paulo', 'SP', 'joaotech', 'senha123'),
('98.765.432/0001-99', 'Distribuidora Elétrica SA', '9876543210', 'Maria Souza', '(21)99876-5432', 'maria@disteletrica.com', 2, '20000-000', 'Av. Beta', 456, '', 'Bairro Industrial', 'Rio de Janeiro', 'RJ', 'mariasouza', 'segura321'),
('22.333.444/0001-55', 'Admin Serviços Ltda', '2233445566', 'Carlos Mendes', '(31)91234-5678', 'carlos@adminsrv.com', 3, '30000-000', 'Rua Gama', 789, 'Conj. C', 'Centro', 'Belo Horizonte', 'MG', 'carluxo', 'adm123');

-- Inserções: produtos
INSERT INTO produtos (descricao, ncm, marca, unidade_medida, fk_categoria_id_categoria) VALUES
('Notebook Acer Aspire 5', '84713012', 'Acer', 'un', 1),
('Lâmpada LED 9W', '85392900', 'Philips', 'un', 2),
('Cabo Flexível 4mm Azul', '85444900', 'Sil', 'm', 3),
('Tubo PEAD 60mm', '39172100', 'Tigre', 'm', 4),
('Bloco Estrutural 39x19x14', '68109100', 'Premol', 'un', 5);

-- Inserções: transportadora
INSERT INTO transportadora (descricao, data_entrega, valor, tipo_transportadora) VALUES
('TransVia Express', '2025-07-01', 120.00, 'Terrestre'),
('Carga Rápida LTDA', '2025-07-03', 75.50, 'Rodoviária'),
('Entrega Aérea Brasil', '2025-07-02', 250.00, 'Aérea');

-- Inserções: operacao
INSERT INTO operacao (fk_usuario_id_usuario, data_operacao, prazo_entrega, data_pagamento, status_pagamento, valor_total_compra, fk_transportadora_id_transportadora, fk_tipo_operacao_id_tipo_operacao) VALUES
(1, '2025-06-20', '7 dias', '2025-06-27', 'Pago', 6400.00, 1, 1),
(2, '2025-06-22', '10 dias', NULL, 'Pendente', 3400.00, 2, 2),
(1, '2025-06-25', '5 dias', '2025-06-30', 'Pago', 1200.00, 3, 3);

-- Inserções: fornecedor_categoria
INSERT INTO fornecedor_categoria (id_fornecedor, id_categoria) VALUES
(2, 1),
(2, 3),
(2, 4);

-- Inserções: operacao_produto
INSERT INTO operacao_produto (id_operacao, id_produto, quantidade, valor_unitario, valor_total_produtos, margem_lucro, imposto, preco_venda) VALUES
(1, 1, 2, 3200.00, 6400.00, 20.00, 15.00, 3680.00),
(2, 3, 500, 6.00, 3000.00, 25.00, 10.00, 7.50),
(2, 2, 40, 10.00, 400.00, 30.00, 5.00, 13.00),
(3, 5, 300, 4.00, 1200.00, 15.00, 8.00, 4.83);

-- Procedure , trigger, viem e função -- 
-- PRocedure
DELIMITER $$

CREATE PROCEDURE inserir_usuario (
    IN p_cnpj VARCHAR(18),
    IN p_razao_social VARCHAR(255),
    IN p_inscricao_estadual VARCHAR(20),
    IN p_contato VARCHAR(100),
    IN p_telefone VARCHAR(20),
    IN p_email VARCHAR(100),
    IN p_tipo_usuario INT,
    IN p_cep VARCHAR(10),
    IN p_logradouro VARCHAR(100),
    IN p_numero INT,
    IN p_complemento VARCHAR(50),
    IN p_bairro VARCHAR(50),
    IN p_cidade VARCHAR(50),
    IN p_estado VARCHAR(2),
    IN p_login VARCHAR(50),
    IN p_senha VARCHAR(100)
)
BEGIN
    INSERT INTO usuario (
        cnpj, razao_social, inscricao_estadual, contato, telefone,
        email, tipo_usuario, cep, logradouro, numero, complemento,
        bairro, cidade, estado, login, senha
    )
    VALUES (
        p_cnpj, p_razao_social, p_inscricao_estadual, p_contato, p_telefone,
        p_email, p_tipo_usuario, p_cep, p_logradouro, p_numero, p_complemento,
        p_bairro, p_cidade, p_estado, p_login, p_senha
    );
END$$

DELIMITER ;

-- Trigger --
DELIMITER $$

CREATE TRIGGER trg_atualiza_valor_total_operacao
AFTER INSERT ON operacao_produto
FOR EACH ROW
BEGIN
    UPDATE operacao
    SET valor_total_compra = (
        SELECT SUM(valor_total_produtos)
        FROM operacao_produto
        WHERE id_operacao = NEW.id_operacao
    )
    WHERE id_operacao = NEW.id_operacao;
END$$

DELIMITER ;

-- Função 

DELIMITER $$

CREATE FUNCTION resumo_operacoes_por_usuario(
    p_id_usuario INT,
    p_tipo_operacao VARCHAR(50)
) RETURNS VARCHAR(255)
DETERMINISTIC
BEGIN
    DECLARE tipo_id INT;
    DECLARE total_operacoes INT DEFAULT 0;
    DECLARE valor_total DECIMAL(10,2) DEFAULT 0.00;
    DECLARE resultado VARCHAR(255);

    -- Buscar o ID do tipo de operação
    SELECT id_tipo_operacao INTO tipo_id
    FROM tipo_operacao
    WHERE descricao = p_tipo_operacao
    LIMIT 1;

    -- Calcular total de operações e valor total
    SELECT 
        COUNT(*), 
        IFNULL(SUM(valor_total_compra), 0.00)
    INTO 
        total_operacoes, 
        valor_total
    FROM operacao
    WHERE fk_usuario_id_usuario = p_id_usuario
      AND fk_tipo_operacao_id_tipo_operacao = tipo_id;

    -- Montar string de resposta
    SET resultado = CONCAT('Operações: ', total_operacoes, ', Total: R$ ', FORMAT(valor_total, 2));

    RETURN resultado;
END$$

DELIMITER ;


-- View -- 
CREATE VIEW historico_precos_produto AS
SELECT 
    p.id_produto,
    p.descricao AS nome_produto,
    o.id_operacao,
    o.data_operacao,
    op.preco_venda,
    op.valor_unitario,
    op.quantidade
FROM 
    produtos p
JOIN operacao_produto op ON p.id_produto = op.id_produto
JOIN operacao o ON o.id_operacao = op.id_operacao
ORDER BY 
    p.id_produto, o.data_operacao DESC;

