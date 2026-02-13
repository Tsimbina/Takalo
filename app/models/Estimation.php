<?php

namespace app\models;

use PDO;

class Estimation
{
    private $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    /**
     * Retourne la liste des objets dont le prix est compris entre [prix - x%, prix + x%] du prix de l'objet de référence
     * @param int $idObjet
     * @param float $pourcent
     * @return array
     */
    public function getEstimationObjet(int $idObjet, float $pourcent): array
    {
        // Récupérer le prix de l'objet de référence
        $stmt = $this->db->prepare('SELECT prix FROM objet WHERE id = ?');
        $stmt->execute([$idObjet]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) return [];
        $prix = (float)$row['prix'];
        $min = $prix - ($prix * $pourcent / 100);
        $max = $prix + ($prix * $pourcent / 100);

        // Récupérer les objets dans l'intervalle (hors objet de référence)
        $sql = 'SELECT o.*, c.libele AS categorie FROM objet o INNER JOIN categorie c ON c.id = o.idCateg WHERE o.id != ? AND o.prix BETWEEN ? AND ? ORDER BY o.prix ASC';
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$idObjet, $min, $max]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
