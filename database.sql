-- =====================================================
-- CRUD FARMÁCIA VAV - Script de Banco de Dados
-- =====================================================
-- Contexto: Disciplina de Desenvolvimento Web
-- Objetivo: Criação do banco de dados, da tabela de produtos
-- e inserção de dados iniciais para testes.
-- =====================================================

-- Criação do Banco de Dados (Schema)
CREATE DATABASE IF NOT EXISTS farmacia_vav
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_general_ci;

USE farmacia_vav;

-- =====================================================
-- Estrutura da Tabela: produtos
-- Armazena o inventário/estoque da farmácia
-- =====================================================
CREATE TABLE IF NOT EXISTS produtos (
    id INT(11) NOT NULL AUTO_INCREMENT,        -- Código identificador automático (Chave Primária)
    nome VARCHAR(150) NOT NULL,                -- Nome comercial do produto/medicamento
    fabricante VARCHAR(100) NOT NULL,          -- Laboratório ou fabricante
    preco DECIMAL(10,2) NOT NULL DEFAULT 0.00, -- Valor do produto (suporta até 2 casas decimais)
    estoque INT(11) NOT NULL DEFAULT 0,        -- Quantidade atual em estoque
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- =====================================================
-- Dados de Exemplo (Carga Inicial / Seeding)
-- Popula a tabela para permitir testes imediatos no sistema
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
