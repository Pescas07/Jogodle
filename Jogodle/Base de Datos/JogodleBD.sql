create database Jogodle;
use Jogodle;
CREATE TABLE usuario (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR (100) NOT NULL UNIQUE,
    contra VARCHAR(255) NOT NULL,
    foto VARCHAR(255) DEFAULT 'default.png',
    ganadas INT DEFAULT '0',
    intentos INT DEFAULT '0'
);

-- Tabla principal de jogos
CREATE TABLE jogo (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(100) NOT NULL UNIQUE,
    lanzamiento INT,
    descripcion TEXT
);

-- Tabla de personajes para el jogo
CREATE TABLE personaje (
    id INT PRIMARY KEY AUTO_INCREMENT,
    jogo_id INT NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    imagen_ruta VARCHAR(255) NOT NULL, 
    FOREIGN KEY (jogo_id) REFERENCES jogo(id)
);

-- Tabla de palabras para el jogo
CREATE TABLE palabras (
    id INT PRIMARY KEY AUTO_INCREMENT,
    jogo_id INT NOT NULL,
    palabra VARCHAR(50) NOT NULL,
    FOREIGN KEY (jogo_id) REFERENCES jogo(id)
);

-- Tabla de estadísticas para el jogo
CREATE TABLE statsJogo (
    id INT PRIMARY KEY AUTO_INCREMENT,
    jogo_id INT NOT NULL,
    tipo ENUM('plataforma', 'año', 'perspectiva', 'modoJogo') NOT NULL,
    valor TEXT NOT NULL, 
    FOREIGN KEY (jogo_id) REFERENCES jogo(id)
);