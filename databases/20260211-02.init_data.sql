-- ----- CATEGORIES -----
INSERT INTO categorie (libele) VALUES
('Électronique'),
('Vêtements'),
('Livres');


-- ----- USERS -----
INSERT INTO user (email, login, passwd) VALUES
('alice@example.com', 'alice', 'passAlice123'),
('bob@example.com', 'bob', 'passBob123'),
('carol@example.com', 'carol', 'passCarol123');


-- ----- OBJETS -----
INSERT INTO objet (titre, prix, detail, idProprio, idCateg, description) VALUES
('iPhone 14', 1200.00, '128GB - Bleu', 1, 1, 'Smartphone Apple en excellent état'),
('T-shirt Marvel', 25.50, 'Taille M - Neuf', 2, 2, 'T-shirt neuf taille M'),
('Le Petit Prince', 10.00, 'Édition poche', 3, 3, 'Livre classique pour enfants'),
('MacBook Pro', 2500.00, '14 pouces - M1 Pro', 1, 1, 'MacBook Pro 2021, presque neuf');


-- ----- IMAGE_OBJET -----
INSERT INTO imageObjet (idObjet, image, alt) VALUES
(1, 'iphone14_front.jpg', 'iPhone 14 vue de face'),
(1, 'iphone14_back.jpg', 'iPhone 14 vue arrière'),
(2, 'tshirt_marvel.jpg', 'T-shirt Marvel'),
(3, 'le_petit_prince.jpg', 'Le Petit Prince'),
(4, 'macbook_pro.jpg', 'MacBook Pro');
