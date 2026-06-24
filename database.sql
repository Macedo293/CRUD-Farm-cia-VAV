-- =====================================================
-- CRUD FARMÁCIA VAV - Script de Banco de Dados
-- =====================================================
-- Disciplina: Desenvolvimento Web
-- Descrição: Script responsável por criar o banco de
-- dados, a tabela "produtos" e inserir alguns registros
-- de exemplo para facilitar os testes do sistema.
-- =====================================================

-- Cria o banco de dados, caso ele ainda não exista
CREATE DATABASE IF NOT EXISTS farmacia_vav
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_general_ci;

-- Seleciona o banco de dados para uso
USE farmacia_vav;

-- =====================================================
-- Tabela: produtos
-- Armazena os medicamentos/produtos do estoque da farmácia
-- =====================================================
CREATE TABLE IF NOT EXISTS produtos (
    id INT(11) NOT NULL AUTO_INCREMENT,        -- Chave primária, gerada automaticamente
    nome VARCHAR(150) NOT NULL,                -- Nome do produto/medicamento
    fabricante VARCHAR(100) NOT NULL,          -- Nome do fabricante/laboratório
    preco DECIMAL(10,2) NOT NULL DEFAULT 0.00, -- Preço com 2 casas decimais (ex: 1234.56)
    estoque INT(11) NOT NULL DEFAULT 0,        -- Quantidade disponível em estoque
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- =====================================================
-- Inserção de dados de exemplo (seed)
-- Útil para testar a listagem (index.php) sem precisar
-- cadastrar produtos manualmente antes
-- =====================================================
INSERT INTO produtos (nome, fabricante, preco, estoque) VALUES
('Paracetamol 750mg', 'EMS', 12.50, 150),
('Dipirona Sódica 1g', 'Neo Química', 8.90, 200),
('Amoxicilina 500mg', 'Eurofarma', 24.75, 80),
('Ibuprofeno 400mg', 'Medley', 15.30, 120),
('Omeprazol 20mg', 'Germed', 18.40, 95),
('Soro Fisiológico 500ml', 'Sanobiol', 7.20, 60),
('Loratadina 10mg', 'Cimed', 11.99, 0),
('Vitamina C 1g', 'Sundown', 22.00, 40);