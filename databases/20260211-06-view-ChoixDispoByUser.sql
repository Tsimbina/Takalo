-- Vue pour récupérer les objets disponibles d'un utilisateur pour une proposition d'échange
-- Un objet est "disponible" s'il n'est PAS déjà impliqué dans un échange "En cours" (statut 3)
CREATE OR REPLACE VIEW vw_choixDispoByUserForProposition AS
SELECT 
    o.id,
    o.titre,
    o.prix,
    o.description,
    o.idProprio,
    o.idCateg,
    c.libele AS categorie,
    (SELECT io.image FROM imageObjet io WHERE io.idObjet = o.id ORDER BY io.id ASC LIMIT 1) AS image
FROM objet o
INNER JOIN categorie c ON c.id = o.idCateg
WHERE o.id NOT IN (
    -- Exclure les objets déjà engagés dans un échange "En cours" (idStatutEchange = 3)
    SELECT e.idObjet1 FROM echange e WHERE e.idStatutEchange = 3
)
ORDER BY o.id DESC;
