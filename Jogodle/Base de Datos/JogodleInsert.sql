-- 1. Insertar datos de jogos
INSERT INTO jogo (nombre, lanzamiento, descripcion) VALUES
-- Multiplataforma
('Minecraft', 2011, 'Juego de construcción con bloques en un mundo abierto'),
('Fortnite', 2017, 'Battle Royale con construcción y skins icónicas'),
('Among Us', 2018, 'Juego de deducción social en una nave espacial'),
('Call of Duty: Warzone', 2020, 'Battle Royale gratuito de la saga Call of Duty'),
('Rocket League', 2015, 'Fútbol con coches impulsados por cohetes'),

-- Nintendo Switch
('The Legend of Zelda: Breath of the Wild', 2017, 'Aventura de mundo abierto en Hyrule'),
('Super Mario Odyssey', 2017, 'Mario viaja a distintos reinos con su sombrero Cappy'),
('Animal Crossing: New Horizons', 2020, 'Simulación de vida en una isla paradisíaca'),
('Splatoon 3', 2022, 'Shooter competitivo con tinta y pulpos'),
('Pokémon Escarlata y Púrpura', 2022, 'Nueva generación de Pokémon en mundo abierto'),

-- Xbox
('Halo 3', 2007, 'Nueva entrega de la saga de shooters de Master Chief'),
('Forza Horizon 5', 2021, 'Juego de carreras en mundo abierto en México'),
('Gears 5', 2019, 'Shooter en tercera persona de la saga Gears of War'),
('Sea of Thieves', 2018, 'Aventura pirata multijugador en mundo abierto'),
('Microsoft Flight Simulator', 2020, 'Simulador de vuelo ultra realista'),

-- PlayStation
('God of War (2018)', 2018, 'Aventura de Kratos y Atreus en la mitología nórdica'),
('The Last of Us Part I', 2013, 'Aventura postapocalíptica con Joel y Ellie'),
('Spider-Man: Miles Morales', 2020, 'Aventura del héroe arácnido Miles Morales'),
('Horizon Forbidden West', 2022, 'Aventura en un mundo postapocalíptico con máquinas'),
('Gran Turismo 7', 2022, 'Simulador de carreras de automóviles'),

-- PC
('League of Legends', 2009, 'MOBA competitivo con más de 140 campeones'),
('Counter-Strike 2', 2023, 'Shooter táctico multijugador competitivo'),
('Valorant', 2020, 'Shooter táctico con personajes y habilidades únicas'),
('World of Warcraft', 2004, 'MMORPG épico de fantasía con razas y clases'),
('Project Zomboid', 2013, 'Simulador de supervivencia zombie en mundo abierto');

-- 2. Insertar personajes principales
INSERT INTO personaje (jogo_id, nombre, imagen_ruta) VALUES
-- Multiplataforma
(1, 'Steve', 'steve.png'),
(2, 'Jonesy', 'jonesy.png'),
(3, 'Tripulante Rojo', 'rojo.png'),
(4, 'Captain Price', 'price.png'),
(5, 'Octane', 'octane.png'),

-- Nintendo Switch
(6, 'Link', 'link.png'),
(7, 'Mario', 'mario.png'),
(8, 'Tom Nook', 'toomnook.png'),
(9, 'Inkling', 'inkling.png'),
(10, 'Pikachu', 'pikachu.png'),

-- Xbox
(11, 'Master Chief', 'chief.png'),
(12, 'Horizon', 'forza.webp'),
(13, 'Marcus Fenix', 'marcus.webp'),
(14, 'Pirata', 'pirate.webp'),
(15, 'Piloto', 'plane.webp'),

-- PlayStation
(16, 'Kratos', 'kratos.png'),
(17, 'Joel', 'joel.png'),
(18, 'Miles Morales', 'miles.png'),
(19, 'Aloy', 'aloy.png'),
(20, 'Conductor', 'turismo.webp'),

-- PC
(21, 'Jinx', 'jinx.png'),
(22, 'Terrorista', 'terrorist.png'),
(23, 'Phoenix', 'phoenix.png'),
(24, 'Invoker', 'invoker.png'),
(25, 'Spiffo', 'spiffo.png');

-- 3. Insertar palabras para el ahorcado
INSERT INTO palabras (jogo_id, palabra) VALUES
-- Multiplataforma
(1, 'Minar'), (1, 'Infierno'), (1, 'Dragón'),
(2, 'Scar'), (2, 'Loot'), (2, 'Tormenta'),
(3, 'Tarea'), (3, 'Traicion'), (3, 'Sabotaje'),
(4, 'Extraccion'), (4, 'Zombies'), (4, 'Bajas'),
(5, 'Boost'), (5, 'Gol'), (5, 'Aerial'),

-- Nintendo Switch
(6, 'Espada'), (6, 'Exploracion'), (6, 'Santuarios'),
(7, 'Saltos'), (7, 'Ciudad'), (7, 'Champiñion'),
(8, 'Aldea'), (8, 'Animal'), (8, 'Fósil'),
(9, 'Pintura'), (9, 'Equipo'), (9, '4vs4'),
(10, 'Ataque'), (10, 'Región'), (10, 'Cristal'),

-- Xbox
(11, 'Futuro'), (11, 'Disparo'), (11, 'Aliado'),
(12, 'Carreras'), (12, 'Exploracion'), (12, 'Exclusivo'),
(13, 'Soldado'), (13, 'Subsuelo'), (13, 'Mutantes'),
(14, 'Isla'), (14, 'Barco'), (14, 'Tesoro'),
(15, 'Avión'), (15, 'Simulacion'), (15, 'Piloto'),

-- PlayStation
(16, 'Dios'), (16, 'Hacha'), (16, 'Reino'),
(17, 'Infección'), (17, 'Hongo'), (17, 'Supervivencia'),
(18, 'Traje'), (18, 'Telaraña'), (18, 'Barrio'),
(19, 'Cazar'), (19, 'Robot'), (19, 'Salvaje'),
(20, 'Circuito'), (20, 'Simulacion'), (20, 'Realismo'),

-- PC
(21, 'Campeones'), (21, 'Grieta'), (21, 'Toxico'),
(22, 'Bomba'), (22, 'Realismo'), (22, 'Tactico'),
(23, 'Habilidades'), (23, 'Bomba'), (23, 'Ronda'),
(24, 'Mazmorra'), (24, 'Orco'), (24, 'Arena'),
(25, 'Zombie'), (25, 'Supervivencia'), (25, 'Hiperrealista');

-- 4. Insertar estadísticas de los jogos
INSERT INTO statsJogo (jogo_id, tipo, valor) VALUES
-- Multiplataforma
(1, 'plataforma', 'Multiplataforma'), (1, 'año', '2011'), (1, 'modoJogo', 'Un jugador/Multijugador'), (1, 'perspectiva', 'Primera persona/Tercera persona'),
(2, 'plataforma', 'Multiplataforma'), (2, 'año', '2017'), (2, 'modoJogo', 'Battle Royale'), (2, 'perspectiva', 'Tercera persona'),
(3, 'plataforma', 'Multiplataforma'), (3, 'año', '2018'), (3, 'modoJogo', 'Multijugador'), (3, 'perspectiva', 'Vista asimétrica'),
(4, 'plataforma', 'Multiplataforma'), (4, 'año', '2020'), (4, 'modoJogo', 'Battle Royale'), (4, 'perspectiva', 'Primera persona'),
(5, 'plataforma', 'Multiplataforma'), (5, 'año', '2015'), (5, 'modoJogo', 'Multijugador'), (5, 'perspectiva', 'Tercera persona'),

-- Nintendo Switch
(6, 'plataforma', 'Nintendo Switch'), (6, 'año', '2017'), (6, 'modoJogo', 'Un jugador'), (6, 'perspectiva', 'Tercera persona'),
(7, 'plataforma', 'Nintendo Switch'), (7, 'año', '2017'), (7, 'modoJogo', 'Un jugador'), (7, 'perspectiva', 'Tercera persona'),
(8, 'plataforma', 'Nintendo Switch'), (8, 'año', '2020'), (8, 'modoJogo', 'Un jugador/Multijugador'), (8, 'perspectiva', 'Tercera persona'),
(9, 'plataforma', 'Nintendo Switch'), (9, 'año', '2022'), (9, 'modoJogo', 'Multijugador'), (9, 'perspectiva', 'Tercera persona'),
(10, 'plataforma', 'Nintendo Switch'), (10, 'año', '2022'), (10, 'modoJogo', 'Un jugador/Multijugador'), (10, 'perspectiva', 'Tercera persona'),

-- Xbox
(11, 'plataforma', 'Xbox'), (11, 'año', '2007'), (11, 'modoJogo', 'Un jugador/Multijugador'), (11, 'perspectiva', 'Primera persona'),
(12, 'plataforma', 'Xbox'), (12, 'año', '2021'), (12, 'modoJogo', 'Un jugador/Multijugador'), (12, 'perspectiva', 'Primera persona/Tercera persona'),
(13, 'plataforma', 'Xbox'), (13, 'año', '2019'), (13, 'modoJogo', 'Un jugador/Multijugador'), (13, 'perspectiva', 'Tercera persona'),
(14, 'plataforma', 'Xbox'), (14, 'año', '2018'), (14, 'modoJogo', 'Multijugador'), (14, 'perspectiva', 'Primera persona'),
(15, 'plataforma', 'Xbox'), (15, 'año', '2020'), (15, 'modoJogo', 'Un jugador'), (15, 'perspectiva', 'Primera persona'),

-- PlayStation
(16, 'plataforma', 'PlayStation'), (16, 'año', '2018'), (16, 'modoJogo', 'Un jugador'), (16, 'perspectiva', 'Tercera persona'),
(17, 'plataforma', 'PlayStation'), (17, 'año', '2013'), (17, 'modoJogo', 'Un jugador'), (17, 'perspectiva', 'Tercera persona'),
(18, 'plataforma', 'PlayStation'), (18, 'año', '2020'), (18, 'modoJogo', 'Un jugador'), (18, 'perspectiva', 'Tercera persona'),
(19, 'plataforma', 'PlayStation'), (19, 'año', '2022'), (19, 'modoJogo', 'Un jugador'), (19, 'perspectiva', 'Tercera persona'),
(20, 'plataforma', 'PlayStation'), (20, 'año', '2022'), (20, 'modoJogo', 'Un jugador/Multijugador'), (20, 'perspectiva', 'Primera persona'),

-- PC
(21, 'plataforma', 'PC'), (21, 'año', '2009'), (21, 'modoJogo', 'Multijugador'), (21, 'perspectiva', 'Tercera persona'),
(22, 'plataforma', 'PC'), (22, 'año', '2023'), (22, 'modoJogo', 'Multijugador'), (22, 'perspectiva', 'Primera persona'),
(23, 'plataforma', 'PC'), (23, 'año', '2020'), (23, 'modoJogo', 'Multijugador'), (23, 'perspectiva', 'Primera persona'),
(24, 'plataforma', 'PC'), (24, 'año', '2004'), (24, 'modoJogo', 'Multijugador'), (24, 'perspectiva', 'Tercera persona'),
(25, 'plataforma', 'PC'), (25, 'año', '2013'), (25, 'modoJogo', 'Un jugador/Multijugador'), (25, 'perspectiva', 'Tercera persona');