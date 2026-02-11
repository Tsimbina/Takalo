DROP TABLE IF EXISTS imageObjet;
DROP TABLE IF EXISTS objet;


-- -----------------------------
-- 2️⃣ Création des tables
-- -----------------------------

-- Table CATEGORIE


-- Table OBJET
CREATE TABLE IF NOT EXISTS objet (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    titre VARCHAR(190) NOT NULL,
    prix DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    idProprio INT UNSIGNED NOT NULL,
    idCateg INT UNSIGNED NOT NULL,
    description TEXT,
    PRIMARY KEY (id),
    KEY fk_objet_user (idProprio),
    KEY fk_objet_categorie (idCateg),
    CONSTRAINT fk_objet_user FOREIGN KEY (idProprio)
        REFERENCES user(id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_objet_categorie FOREIGN KEY (idCateg)
        REFERENCES categorie(id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table IMAGE_OBJET
CREATE TABLE IF NOT EXISTS imageObjet (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    idObjet INT UNSIGNED NOT NULL,
    image VARCHAR(255) NOT NULL,
    alt VARCHAR(255),
    PRIMARY KEY (id),
    KEY fk_image_objet (idObjet),
    CONSTRAINT fk_image_objet FOREIGN KEY (idObjet)
        REFERENCES objet(id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;