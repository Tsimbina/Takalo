<?php

namespace App\Models;

use PDO;

class Echange {
    private $db;
    private $table = 'echange';

    public function __construct($db) {
        $this->db = $db;
    }

    public function creerEchange($idObjet1, $idObjet2, $idProposeur, $idDestinataire) {
        $query = "INSERT INTO " . $this->table . " 
                 (idObjet1, idObjet2, idProposeur, idDestinataire, idStatutEchange) 
                 VALUES (:idObjet1, :idObjet2, :idProposeur, :idDestinataire, 1)"; // 1 = statut "en attente"
        
        $stmt = $this->db->prepare($query);
        
        $stmt->bindParam(':idObjet1', $idObjet1);
        $stmt->bindParam(':idObjet2', $idObjet2);
        $stmt->bindParam(':idProposeur', $idProposeur);
        $stmt->bindParam(':idDestinataire', $idDestinataire);
        
        if($stmt->execute()) {
            return $this->db->lastInsertId();
        }
        
        return false;
    }

    public function echangeExiste($idObjet1, $idObjet2) {
        $query = "SELECT id FROM " . $this->table . " 
                 WHERE (idObjet1 = :idObjet1 AND idObjet2 = :idObjet2)
                 OR (idObjet1 = :idObjet2 AND idObjet2 = :idObjet1)
                 AND idStatutEchange = 3"; //en attente
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':idObjet1', $idObjet1);
        $stmt->bindParam(':idObjet2', $idObjet2);
        $stmt->execute();
        
        return $stmt->rowCount() > 0;
    }

    public function getEchangesByUser($userId) {
        $query = "SELECT e.*, 
                 o1.nom as nomObjet1, o1.description as descriptionObjet1, o1.photo as photoObjet1,
                 o2.nom as nomObjet2, o2.description as descriptionObjet2, o2.photo as photoObjet2,
                 se.libelle as statut
                 FROM " . $this->table . " e
                 JOIN objet o1 ON e.idObjet1 = o1.id
                 JOIN objet o2 ON e.idObjet2 = o2.id
                 JOIN statutEchange se ON e.idStatutEchange = se.id
                 WHERE e.idProposeur = :userId OR e.idDestinataire = :userId
                 ORDER BY e.dateEchange DESC";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':userId', $userId);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function mettreAJourStatut($echangeId, $nouveauStatut) {
        $query = "UPDATE " . $this->table . " 
                 SET idStatutEchange = :statut 
                 WHERE id = :id";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':statut', $nouveauStatut);
        $stmt->bindParam(':id', $echontreId);
        
        return $stmt->execute();
    }

    public function estUtilisateurImplique($echangeId, $userId) {
        $query = "SELECT id FROM " . $this->table . " 
                 WHERE id = :echangeId 
                 AND (idProposeur = :userId OR idDestinataire = :userId)";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':echangeId', $echangeId);
        $stmt->bindParam(':userId', $userId);
        $stmt->execute();
        
        return $stmt->rowCount() > 0;
    }

    public function getEchangeById($echangeId) {
        $query = "SELECT e.*, 
                 o1.nom as nomObjet1, o1.description as descriptionObjet1, o1.photo as photoObjet1,
                 o2.nom as nomObjet2, o2.description as descriptionObjet2, o2.photo as photoObjet2,
                 se.libelle as statut
                 FROM " . $this->table . " e
                 JOIN objet o1 ON e.idObjet1 = o1.id
                 JOIN objet o2 ON e.idObjet2 = o2.id
                 JOIN statutEchange se ON e.idStatutEchange = se.id
                 WHERE e.id = :id";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $echangeId);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
