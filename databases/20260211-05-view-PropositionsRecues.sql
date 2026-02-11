-- Vue pour afficher les propositions d'échange reçues par l'utilisateur
-- et aussi les objets n'ayant aucun échange
CREATE OR REPLACE VIEW vw_choixDispoByUserForProposition AS
SELECT 
    -- Informations sur l'échange (s'il existe)
    e.id AS idEchange,
    e.idObjet1 AS objetPropose,
    e.idObjet2 AS objetConvoite,
    e.idProposeur,
    e.idDestinataire,
    e.idStatutEchange,
    se.libelle AS statutLibelle,
    e.dateEchange,
    
    -- Informations sur l'objet proposé (objet principal)
    o1.id AS id_objetPropose,
    o1.titre AS titre_objetPropose,
    o1.prix AS prix_objetPropose,
    o1.description AS desc_objetPropose,
    c1.libele AS categorie_objetPropose,
    (SELECT image FROM imageObjet WHERE idObjet = o1.id ORDER BY id ASC LIMIT 1) AS image_objetPropose,
    
    -- Informations sur l'objet convoité (si l'échange existe)
    o2.id AS id_objetConvoite,
    o2.titre AS titre_objetConvoite,
    o2.prix AS prix_objetConvoite,
    o2.description AS desc_objetConvoite,
    c2.libele AS categorie_objetConvoite,
    (SELECT image FROM imageObjet WHERE idObjet = o2.id ORDER BY id ASC LIMIT 1) AS image_objetConvoite,
    
    -- Informations utilisateur - Proposeur
    u_proposeur.id AS id_proposeur,
    u_proposeur.login AS login_proposeur,
    u_proposeur.email AS email_proposeur,
    
    -- Informations utilisateur - Destinataire
    u_destinataire.id AS id_destinataire,
    u_destinataire.login AS login_destinataire,
    u_destinataire.email AS email_destinataire
    
FROM objet o1
-- On lie les échanges (même s’il n’y en a pas)
LEFT JOIN echange e ON e.idObjet1 = o1.id
LEFT JOIN objet o2 ON e.idObjet2 = o2.id
LEFT JOIN categorie c1 ON o1.idCateg = c1.id
LEFT JOIN categorie c2 ON o2.idCateg = c2.id
LEFT JOIN statutEchange se ON e.idStatutEchange = se.id
LEFT JOIN user u_proposeur ON e.idProposeur = u_proposeur.id
LEFT JOIN user u_destinataire ON e.idDestinataire = u_destinataire.id

-- Exclure seulement les échanges "En cours"
WHERE (e.idStatutEchange IS NULL OR e.idStatutEchange != 3)
ORDER BY e.dateEchange DESC;
