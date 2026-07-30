CREATE DATABASE IF NOT EXISTS bd_mundo CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE bd_mundo;

CREATE TABLE IF NOT EXISTS continentes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    populacao BIGINT NOT NULL,
    area DECIMAL(15,2) NOT NULL,
    total_paises INT DEFAULT 0
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS paises (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    continente_id INT NOT NULL,
    populacao BIGINT NOT NULL,
    area DECIMAL(15,2) NOT NULL,
    idioma VARCHAR(100) NOT NULL,
    clima VARCHAR(100) NOT NULL,
    regime_politico VARCHAR(100) NOT NULL,
    moeda VARCHAR(100) NOT NULL,
    FOREIGN KEY (continente_id) REFERENCES continentes(id) ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS cidades (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    pais_id INT NOT NULL,
    populacao BIGINT NOT NULL,
    area DECIMAL(15,2) NOT NULL,
    clima VARCHAR(100) NOT NULL,
    data_fundacao DATE NOT NULL,
    FOREIGN KEY (pais_id) REFERENCES paises(id) ON DELETE RESTRICT
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS governantes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(150) NOT NULL,
    partido_politico VARCHAR(100) NOT NULL,
    data_nascimento DATE NOT NULL,
    idade INT NOT NULL,
    data_inicio_mandato DATE NOT NULL,
    data_final_mandato DATE NOT NULL,
    pais_id INT DEFAULT NULL,
    cidade_id INT DEFAULT NULL,
    FOREIGN KEY (pais_id) REFERENCES paises(id) ON DELETE SET NULL,
    FOREIGN KEY (cidade_id) REFERENCES cidades(id) ON DELETE SET NULL
) ENGINE=InnoDB;


INSERT INTO continentes (nome, populacao, area, total_paises) VALUES 
('América do Sul', 430000000, 17840000.00, 1),
('Europa', 746000000, 10180000.00, 1)
ON DUPLICATE KEY UPDATE id=id;

INSERT INTO paises (nome, continente_id, populacao, area, idioma, clima, regime_politico, moeda) VALUES 
('Brasil', 1, 214000000, 8515767.00, 'Português', 'Tropical', 'República Presidencialista', 'Real'),
('França', 2, 67000000, 643801.00, 'Francês', 'Temperado', 'República Semipresidencialista', 'Euro')
ON DUPLICATE KEY UPDATE id=id;

INSERT INTO cidades (nome, pais_id, populacao, area, clima, data_fundacao) VALUES 
('São José dos Campos', 1, 730000, 1099.00, 'Subtropical', '1767-07-27'),
('Paris', 2, 2160000, 105.00, 'Temperado', '0250-01-01')
ON DUPLICATE KEY UPDATE id=id;