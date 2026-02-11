<?php

namespace app\models;

use PDO;

class Objet
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function getAllByUser(int $idUser): array
    {
        $stmt = $this->db->prepare(
            'SELECT o.id, o.titre, o.prix, o.detail, o.description, o.idProprio, o.idCateg, c.libele AS categorie
             FROM objet o
             JOIN categorie c ON c.id = o.idCateg
             WHERE o.idProprio = ?
             ORDER BY o.id DESC'
        );
        $stmt->execute([$idUser]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
