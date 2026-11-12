-- RetroBox - Loja de games e consoles retrô
-- Script do banco de dados

DROP DATABASE IF EXISTS retrobox;
CREATE DATABASE retrobox CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE retrobox;

-- Tabela usuarios
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    tipo ENUM('admin', 'funcionario') DEFAULT 'funcionario',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabela clientes
CREATE TABLE clientes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    telefone VARCHAR(20) NOT NULL,
    senha VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabela plataformas
CREATE TABLE plataformas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(50) NOT NULL UNIQUE,
    fabricante VARCHAR(50) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabela jogos
CREATE TABLE jogos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(100) NOT NULL,
    descricao TEXT,
    preco DECIMAL(10,2) NOT NULL,
    estoque INT NOT NULL DEFAULT 0,
    ano_lancamento INT,
    conservacao ENUM('Lacrado', 'Completo', 'Só o cartucho') DEFAULT 'Completo',
    imagem VARCHAR(255),
    plataforma_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (plataforma_id) REFERENCES plataformas(id)
        ON DELETE RESTRICT ON UPDATE CASCADE
);

-- Tabela pedidos
CREATE TABLE pedidos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cliente_id INT NOT NULL,
    data_pedido DATETIME DEFAULT CURRENT_TIMESTAMP,
    status ENUM('Pendente', 'Pago', 'Enviado', 'Entregue', 'Cancelado') DEFAULT 'Pendente',
    total DECIMAL(10,2) NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (cliente_id) REFERENCES clientes(id)
        ON DELETE RESTRICT ON UPDATE CASCADE
);

-- Tabela itens_pedido
CREATE TABLE itens_pedido (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pedido_id INT NOT NULL,
    jogo_id INT NOT NULL,
    quantidade INT NOT NULL DEFAULT 1,
    preco_unitario DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (pedido_id) REFERENCES pedidos(id)
        ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (jogo_id) REFERENCES jogos(id)
        ON DELETE RESTRICT ON UPDATE CASCADE
);

-- Dados de usuarios e clientes

INSERT INTO usuarios (nome, email, senha, tipo) VALUES
('Kauã Santos Soares', 'admin@retrobox.com', '$2y$10$pIhCZzHsBoxLUz7gx80rN.WE6fn.4m6Fyq0re5v7ZdPKUx2k96me.', 'admin');

INSERT INTO clientes (nome, email, telefone, senha) VALUES
('Ana Souza', 'ana@email.com', '(11) 98888-1111', '$2y$10$M.V/H5fwdrL6av5EDix1Tu5kCwHCUtmTUW3mX/EiaCrKt3OYyQTUi'),
('Bruno Lima', 'bruno@email.com', '(21) 97777-2222', '$2y$10$M.V/H5fwdrL6av5EDix1Tu5kCwHCUtmTUW3mX/EiaCrKt3OYyQTUi');

INSERT INTO plataformas (nome, fabricante) VALUES
('Super Nintendo', 'Nintendo'),
('Mega Drive', 'Sega'),
('PlayStation 1', 'Sony'),
('Nintendo 64', 'Nintendo'),
('Game Boy', 'Nintendo');

INSERT INTO jogos (titulo, descricao, preco, estoque, ano_lancamento, conservacao, imagem, plataforma_id) VALUES
('Super Mario World', 'Clássico de plataforma com Mario e Yoshi.', 189.90, 5, 1990, 'Completo', NULL, 1),
('Donkey Kong Country', 'Aventura de plataforma com gráficos pré-renderizados.', 159.90, 3, 1994, 'Só o cartucho', NULL, 1),
('Sonic the Hedgehog 2', 'Velocidade e fases icônicas com Sonic e Tails.', 129.90, 4, 1992, 'Completo', NULL, 2),
('Streets of Rage 2', 'Beat em up clássico para jogar em dupla.', 149.90, 2, 1992, 'Só o cartucho', NULL, 2),
('Crash Bandicoot', 'Plataforma 3D que marcou o PlayStation.', 99.90, 6, 1996, 'Completo', NULL, 3),
('Final Fantasy VII', 'RPG épico em 3 CDs.', 249.90, 1, 1997, 'Lacrado', NULL, 3),
('Super Mario 64', 'O primeiro Mario em 3D.', 219.90, 3, 1996, 'Só o cartucho', NULL, 4),
('Pokémon Red', 'Capture e treine Pokémon na região de Kanto.', 179.90, 2, 1996, 'Só o cartucho', NULL, 5);

INSERT INTO pedidos (cliente_id, status, total) VALUES
(1, 'Entregue', 389.70),
(2, 'Pendente', 249.90);

INSERT INTO itens_pedido (pedido_id, jogo_id, quantidade, preco_unitario) VALUES
(1, 1, 1, 189.90),
(1, 5, 2, 99.90),
(2, 6, 1, 249.90);
