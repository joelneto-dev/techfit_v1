CREATE DATABASE IF NOT EXISTS techfit_db;
USE techfit_db;

-- Tabela principal de usuários
CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    cpf VARCHAR(14) DEFAULT NULL,
    data_nascimento DATE DEFAULT NULL,
    telefone VARCHAR(15) DEFAULT NULL,
    cep VARCHAR(9) DEFAULT NULL,
    estado VARCHAR(2) DEFAULT NULL,
    cidade VARCHAR(60) DEFAULT NULL,
    rua VARCHAR(120) DEFAULT NULL,
    numero VARCHAR(10) DEFAULT NULL,
    bairro VARCHAR(60) DEFAULT NULL,
    objetivo VARCHAR(50) DEFAULT NULL,
    plano VARCHAR(50) DEFAULT 'Basic',
    ciclo_plano VARCHAR(20) DEFAULT 'monthly',
    metodo_pagamento VARCHAR(20) DEFAULT NULL,
    peso DECIMAL(5,2) DEFAULT NULL,
    altura DECIMAL(5,2) DEFAULT NULL,
    preferencia_tema VARCHAR(20) DEFAULT 'light',
    status VARCHAR(20) DEFAULT 'pendente',
    codigo_ativacao VARCHAR(20) DEFAULT NULL,
    data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    data_ativacao TIMESTAMP NULL DEFAULT NULL
);

-- Tabela de correio eletrônico (Fake Email)
CREATE TABLE IF NOT EXISTS correio_eletronico (
    id INT AUTO_INCREMENT PRIMARY KEY,
    destinatario_email VARCHAR(100) NOT NULL,
    assunto VARCHAR(200) NOT NULL,
    corpo TEXT NOT NULL,
    lida BOOLEAN DEFAULT FALSE,
    data_envio TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_destinatario (destinatario_email)
);

-- Tabela de alunos (mantida para compatibilidade)
CREATE TABLE IF NOT EXISTS alunos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    plano VARCHAR(50)
);

-- Dados de exemplo
INSERT INTO alunos (nome, email, plano) VALUES 
('Ana Clara', 'ana@email.com', 'Gold'),
('Bruno Silva', 'bruno@email.com', 'Basic');