CREATE TABLE IF NOT EXISTS echange (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    idObjet1 INT UNSIGNED NOT NULL,
    idObjet2 INT UNSIGNED NOT NULL,
    idProposeur INT UNSIGNED NOT NULL,
    idDestinataire INT UNSIGNED NOT NULL,
    idStatutEchange INT NOT NULL,
    dateEchange DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY idx_echange_objet1 (idObjet1),
    KEY idx_echange_objet2 (idObjet2),
    KEY idx_echange_proposeur (idProposeur),
    KEY idx_echange_destinataire (idDestinataire),
    KEY idx_echange_statut (idStatutEchange),
    KEY idx_echange_date (dateEchange),
    CONSTRAINT fk_echange_objet1 FOREIGN KEY (idObjet1)
        REFERENCES objet(id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_echange_objet2 FOREIGN KEY (idObjet2)
        REFERENCES objet(id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_echange_proposeur FOREIGN KEY (idProposeur)
        REFERENCES user(id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_echange_destinataire FOREIGN KEY (idDestinataire)
        REFERENCES user(id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_echange_statut FOREIGN KEY (idStatutEchange)
        REFERENCES statutEchange(id) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
