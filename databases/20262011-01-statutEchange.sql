CREATE TABLE statutEchange (
    id INT PRIMARY KEY AUTO_INCREMENT,
    libelle VARCHAR(255) NOT NULL
);

INSERT INTO statutEchange (libelle) VALUES ('Accepter'), ('Refuser'), ('En cours');