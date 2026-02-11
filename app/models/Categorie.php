<?php

namespace app\models;

use PDO;

class Categorie{
    private PDO $db;

    public function __construct(PDO $db){
        $this->db = $db;
    }

    public function updateCateg($id, $libelle){
        $sql = "UPDATE categorie SET libele = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$libelle, $id]);
    }

    public function deleteCateg($id){
        $sql = "DELETE FROM categorie WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }

    public function insertCateg($libelle){
        $sql = "INSERT INTO categorie(libele) VALUES (?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$libelle]);
    }

    public function getAll(){
        $sql = "SELECT * FROM categorie";
        $stmt = $this->db->prepare($sql);
        if (!$stmt->execute()) {
            return [];
        }
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}