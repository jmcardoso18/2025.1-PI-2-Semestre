-- Script para teste do banco de dados.
-- Selecionar o banco
USE LojaSistema;

-- Tipos de Usuário
INSERT INTO tipoUsuario (id_tipo_usuario, descricao) VALUES
(1, 'Cliente'),
(2, 'Fornecedor');

-- Usuários
INSERT INTO usuario (cnpj, razao_social, nome_fantasia, inscricao_estadual, contato, telefone, email, tipo_usuario,
                     cep, logradouro, numero, complemento, bairro, cidade, estado, login, senha)
VALUES
('12.345.678/0001-00', 'Loja do João Ltda', 'João Store', '123456789', 'João Silva', '11999999999', 'joao@email.com', 1,
 '01001-000', 'Rua das Flores', '100', '', 'Centro', 'São Paulo', 'SP', 'joao123', 'senha123'),

('98.765.432/0001-00', 'Distribuidora Global S/A', 'Global Distrib', '987654321', 'Maria Souza', '11988888888', 'maria@email.com', 2,
 '02002-000', 'Avenida Brasil', '200', 'Bloco A', 'Jardins', 'São Paulo', 'SP', 'mariaforn', 'senha456');

-- Categorias
INSERT INTO categoria (cod_categoria, descricao) VALUES
(1, 'Eletrônicos'),
(2, 'Alimentos');

-- Produtos
INSERT INTO produtos (codigo_produto, descricao, cod_categoria, ncm, marca, unidade_medida, preco_custo_unidade) VALUES
(1, 'Celular XYZ', 1, '85171231', 'TechBrand', 'unidade', 1200.00),
(2, 'Chocolate 70g', 2, '18069000', 'Delícias', 'unidade', 2.50);

-- Produto x Fornecedor
INSERT INTO fornecedorCategoria (id_produto, id_fornecedor) VALUES
(1, 2),
(2, 2);

-- Orçamento
INSERT INTO orcamento (id_cliente, data_orcamento) VALUES
(1, NOW());

-- Orçamento x Produto
INSERT INTO orcamentoproduto (id_orcamento, codigo_produto, quantidade) VALUES
(1, 1, 3),
(1, 2, 10);

-- Cotação
INSERT INTO cotacao (id_fornecedor, data_cotacao, prazo_entrega) VALUES
(2, NOW(), '5 dias');

-- Cotação x Produto
INSERT INTO cotproduto (id_cotacao, codigo_produto, quantidade, valor_unitario) VALUES
(1, 1, 3, 1300.00),
(1, 2, 10, 3.00);

-- Compra
INSERT INTO compra (id_fornecedor, data_compra, prazo_entrega, data_pagamento, status_pagamento) VALUES
(2, NOW(), '7 dias', CURDATE(), 'Pago');

-- Produto x Compra
INSERT INTO produtocompra (id_compra, codigo_produto, quantidade, valor_unitario, valor_total_compra) VALUES
(1, 1, 3, 1300.00, 3900.00),
(1, 2, 10, 3.00, 30.00);

-- Venda
INSERT INTO venda (id_venda, data_venda, id_cliente, valor_total_compra, condicao_pagamento, data_pagamento, status_pagamento, prazo_entrega) VALUES
(1, CURDATE(), 1, 4600.00, 'À vista', CURDATE(), 'Pago', '2 dias');

-- Produto x Venda
INSERT INTO produtovenda (id_venda, codigo_produto, quantidade, preco_compra, margem_lucro, impostos, preco_venda) VALUES
(1, 1, 2, 1300.00, 30.00, 10.00, 1800.00),
(1, 2, 5, 3.00, 50.00, 5.00, 6.75);

-- Frete
INSERT INTO frete (id_venda, data_entrega, transportadora, valor, tipo_transporte) VALUES
(1, DATE_ADD(CURDATE(), INTERVAL 2 DAY), 'Rápido Express', 50.00, 'Rodoviário');
