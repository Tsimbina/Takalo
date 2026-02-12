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
            'SELECT o.id, o.titre, o.prix, o.description, o.idProprio, o.idCateg, c.libele AS categorie,
                    (SELECT io.image FROM imageObjet io WHERE io.idObjet = o.id ORDER BY io.id ASC LIMIT 1) AS image
             FROM objet o
             JOIN categorie c ON c.id = o.idCateg
             WHERE o.idProprio = ?
             ORDER BY o.id DESC'
        );
        $stmt->execute([$idUser]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllObjetExceptUser(int $idUserToExcept): array
    {
        $stmt = $this->db->prepare(
            'SELECT o.id, o.titre, o.prix, o.description, o.idProprio, o.idCateg, c.libele AS categorie,
                    (SELECT io.image FROM imageObjet io WHERE io.idObjet = o.id ORDER BY io.id ASC LIMIT 1) AS image
             FROM objet o
             JOIN categorie c ON c.id = o.idCateg
             WHERE o.idProprio != ?
             ORDER BY o.id DESC'
        );
        $stmt->execute([$idUserToExcept]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getByIdAndUser(int $idObjet, int $idUser): ?array
    {
        $stmt = $this->db->prepare(
            'SELECT o.*, c.libele AS categorie 
             FROM objet o
             JOIN categorie c ON c.id = o.idCateg
             WHERE o.id = ? AND o.idProprio = ?'
        );
        $stmt->execute([$idObjet, $idUser]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    public function getImagesByObjet(int $idObjet): array
    {
        $stmt = $this->db->prepare(
            'SELECT id, image, alt FROM imageObjet WHERE idObjet = ? ORDER BY id ASC'
        );
        $stmt->execute([$idObjet]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateWithImages(
        int $idObjet,
        string $titre,
        float $prix,
        string $description,
        int $idCateg,
        ?array $imagesFile,
        string $uploadDirAbsolute,
        array $deleteImages = []
    ): bool {
        $titre = trim($titre);
        $description = trim($description);

        if ($titre === '' || $idObjet <= 0 || $idCateg <= 0) {
            throw new \InvalidArgumentException('Champs invalides.');
        }

        $this->db->beginTransaction();

        try {
            // Mettre à jour l'objet
            $stmt = $this->db->prepare(
                'UPDATE objet SET titre = ?, prix = ?, description = ?, idCateg = ? WHERE id = ?'
            );
            $stmt->execute([$titre, $prix, $description, $idCateg, $idObjet]);

            // Supprimer les images demandées
            if (!empty($deleteImages)) {
                foreach ($deleteImages as $imageId) {
                    $imageId = (int)$imageId;
                    if ($imageId > 0) {
                        // Récupérer le chemin du fichier avant suppression
                        $imgStmt = $this->db->prepare('SELECT image FROM imageObjet WHERE id = ? AND idObjet = ?');
                        $imgStmt->execute([$imageId, $idObjet]);
                        $imgRow = $imgStmt->fetch(PDO::FETCH_ASSOC);
                        
                        if ($imgRow) {
                            $relativePath = (string)($imgRow['image'] ?? '');
                            if (substr($relativePath, 0, 5) === 'data/') {
                                $absPath = rtrim($uploadDirAbsolute, '/') . '/' . substr($relativePath, strlen('data/'));
                                if (is_file($absPath)) {
                                    @unlink($absPath);
                                }
                            }
                        }
                        
                        // Supprimer de la base de données
                        $delStmt = $this->db->prepare('DELETE FROM imageObjet WHERE id = ? AND idObjet = ?');
                        $delStmt->execute([$imageId, $idObjet]);
                    }
                }
            }

            // Ajouter les nouvelles images
            $images = $this->normalizeFilesArray($imagesFile);
            if (!empty($images)) {
                if (!is_dir($uploadDirAbsolute)) {
                    if (!mkdir($uploadDirAbsolute, 0775, true) && !is_dir($uploadDirAbsolute)) {
                        throw new \RuntimeException('Impossible de créer le dossier upload.');
                    }
                }

                $insertImageStmt = $this->db->prepare(
                    'INSERT INTO imageObjet (idObjet, image, alt) VALUES (?, ?, ?)'
                );

                foreach ($images as $img) {
                    if (($img['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
                        continue;
                    }

                    $tmpName = (string)($img['tmp_name'] ?? '');
                    $originalName = (string)($img['name'] ?? '');

                    if ($tmpName === '' || !is_uploaded_file($tmpName)) {
                        continue;
                    }

                    $ext = strtolower((string)pathinfo($originalName, PATHINFO_EXTENSION));
                    $allowed = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
                    if (!in_array($ext, $allowed, true)) {
                        continue;
                    }

                    $fileName = sprintf('objet_%d_%s.%s', $idObjet, bin2hex(random_bytes(8)), $ext);
                    $destination = rtrim($uploadDirAbsolute, '/').'/'.$fileName;

                    if (!move_uploaded_file($tmpName, $destination)) {
                        continue;
                    }

                    $relativePath = 'data/'.$fileName;
                    $alt = $titre;
                    $insertImageStmt->execute([$idObjet, $relativePath, $alt]);
                }
            }

            $this->db->commit();
            return true;
        } catch (\Throwable $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    public function deleteObjet($idObjet){
        $sql = "DELETE FROM objet WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$idObjet]);
    }

    public function createWithImages(
        string $titre,
        float $prix,
        string $description,
        int $idProprio,
        int $idCateg,
        ?array $imagesFile,
        string $uploadDirAbsolute
    ): int {
        $titre = trim($titre);
        $description = trim($description);

        if ($titre === '' || $idProprio <= 0 || $idCateg <= 0) {
            throw new \InvalidArgumentException('Champs invalides.');
        }

        $this->db->beginTransaction();

        try {
            $stmt = $this->db->prepare(
                'INSERT INTO objet (titre, prix, idProprio, idCateg, description)
                 VALUES (?, ?, ?, ?, ?)'
            );
            $stmt->execute([$titre, $prix, $idProprio, $idCateg, $description]);

            $idObjet = (int)$this->db->lastInsertId();
            if ($idObjet <= 0) {
                throw new \RuntimeException('Insertion objet échouée.');
            }

            $images = $this->normalizeFilesArray($imagesFile);
            if (!empty($images)) {
                if (!is_dir($uploadDirAbsolute)) {
                    if (!mkdir($uploadDirAbsolute, 0775, true) && !is_dir($uploadDirAbsolute)) {
                        throw new \RuntimeException('Impossible de créer le dossier upload.');
                    }
                }

                $insertImageStmt = $this->db->prepare(
                    'INSERT INTO imageObjet (idObjet, image, alt) VALUES (?, ?, ?)'
                );

                foreach ($images as $img) {
                    if (($img['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
                        continue;
                    }

                    $tmpName = (string)($img['tmp_name'] ?? '');
                    $originalName = (string)($img['name'] ?? '');

                    if ($tmpName === '' || !is_uploaded_file($tmpName)) {
                        continue;
                    }

                    $ext = strtolower((string)pathinfo($originalName, PATHINFO_EXTENSION));
                    $allowed = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
                    if (!in_array($ext, $allowed, true)) {
                        continue;
                    }

                    $fileName = sprintf('objet_%d_%s.%s', $idObjet, bin2hex(random_bytes(8)), $ext);
                    $destination = rtrim($uploadDirAbsolute, '/').'/'.$fileName;

                    if (!move_uploaded_file($tmpName, $destination)) {
                        continue;
                    }

                    $relativePath = 'data/'.$fileName;
                    $alt = $titre;
                    $insertImageStmt->execute([$idObjet, $relativePath, $alt]);
                }
            }

            $this->db->commit();
            return $idObjet;
        } catch (\Throwable $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    private function normalizeFilesArray(?array $files): array
    {
        if (empty($files) || empty($files['name'])) {
            return [];
        }

        if (!is_array($files['name'])) {
            return [$files];
        }

        $normalized = [];
        $count = count($files['name']);
        for ($i = 0; $i < $count; $i++) {
            $normalized[] = [
                'name' => $files['name'][$i] ?? null,
                'type' => $files['type'][$i] ?? null,
                'tmp_name' => $files['tmp_name'][$i] ?? null,
                'error' => $files['error'][$i] ?? null,
                'size' => $files['size'][$i] ?? null,
            ];
        }

        return $normalized;
    }

    public function deleteByIdAndUser(int $idObjet, int $idUser, string $uploadDirAbsolute): bool
    {
        if ($idObjet <= 0 || $idUser <= 0) {
            return false;
        }

        $this->db->beginTransaction();

        try {
            $stmt = $this->db->prepare('SELECT idProprio FROM objet WHERE id = ?');
            $stmt->execute([$idObjet]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if (empty($row)) {
                $this->db->rollBack();
                return false;
            }

            if ((int)($row['idProprio'] ?? 0) !== $idUser) {
                $this->db->rollBack();
                return false;
            }

            $imgStmt = $this->db->prepare('SELECT image FROM imageObjet WHERE idObjet = ?');
            $imgStmt->execute([$idObjet]);
            $images = $imgStmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($images as $img) {
                $path = (string)($img['image'] ?? '');
                if ($path === '') {
                    continue;
                }

                if (substr($path, 0, 5) === 'data/') {
                    $abs = rtrim($uploadDirAbsolute, '/') . '/' . substr($path, strlen('data/'));
                    if (is_file($abs)) {
                        @unlink($abs);
                    }
                }
            }

            $del = $this->db->prepare('DELETE FROM objet WHERE id = ? AND idProprio = ?');
            $del->execute([$idObjet, $idUser]);

            if ($del->rowCount() < 1) {
                $this->db->rollBack();
                return false;
            }

            $this->db->commit();
            return true;
        } catch (\Throwable $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    /**
     * Récupère les objets disponibles d'un utilisateur pour proposer un échange
     * Utilise la vue SQL vw_choixDispoByUserForProposition qui exclut
     * les objets déjà impliqués dans un échange "En cours" (statut 3)
     * @param int $idUser ID de l'utilisateur connecté (proposeur)
     * @return array Liste des objets disponibles
     */
    public function getAllChoixDispo(int $idUser): array
    {
        $stmt = $this->db->prepare(
            'SELECT * FROM vw_choixDispoByUserForProposition 
             WHERE idProprio = ?
             ORDER BY id DESC'
        );
        $stmt->execute([$idUser]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Vérifie qu'un objet est disponible pour l'échange via la vue SQL
     * @param int $idObjet ID de l'objet à vérifier
     * @param int $idUser ID du propriétaire attendu
     * @return bool true si l'objet est disponible
     */
    public function isObjetDisponible(int $idObjet, int $idUser): bool
    {
        $stmt = $this->db->prepare(
            'SELECT id FROM vw_choixDispoByUserForProposition 
             WHERE id = ? AND idProprio = ?'
        );
        $stmt->execute([$idObjet, $idUser]);
        return (bool) $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Crée une proposition d'échange dans la base de données
     * Vérifie via la vue SQL que l'objet sélectionné est bien disponible
     * @param int $idObjetPropose ID de l'objet proposé par l'utilisateur
     * @param int $idObjetCible ID de l'objet convoité
     * @param int $idProposeur ID de l'utilisateur qui propose
     * @return bool true si l'échange a été créé
     * @throws \RuntimeException si l'objet n'est pas disponible ou si l'objet cible est introuvable
     */
    public function creerProposition(int $idObjetPropose, int $idObjetCible, int $idProposeur): bool
    {
        // Vérifier que l'objet proposé est disponible via la vue
        if (!$this->isObjetDisponible($idObjetPropose, $idProposeur)) {
            throw new \RuntimeException('Objet sélectionné non disponible ou déjà en échange.');
        }

        // Récupérer le propriétaire de l'objet cible
        $stmt = $this->db->prepare('SELECT idProprio FROM objet WHERE id = ? AND idProprio != ?');
        $stmt->execute([$idObjetCible, $idProposeur]);
        $targetRow = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$targetRow) {
            throw new \RuntimeException('Objet cible non trouvé.');
        }

        $idDestinataire = (int) $targetRow['idProprio'];

        // Insérer l'échange (statut 3 = "En cours")
        $insertStmt = $this->db->prepare(
            'INSERT INTO echange (idObjet1, idObjet2, idProposeur, idDestinataire, idStatutEchange, dateEchange)
             VALUES (?, ?, ?, ?, 3, NOW())'
        );
        return $insertStmt->execute([$idObjetPropose, $idObjetCible, $idProposeur, $idDestinataire]);
    }

    /**
     * Récupère les propositions d'échange reçues par un utilisateur (statut != "En cours")
     * @param int $idUser ID de l'utilisateur destinataire
     * @return array Liste des propositions reçues avec tous les détails
     */
    public function getPropositionsRecuesParUser(int $idUser): array
    {
        $stmt = $this->db->prepare(
            'SELECT * FROM vw_choixDispoByUserForProposition 
             WHERE id_destinataire = ?
             ORDER BY dateEchange DESC'
        );
        $stmt->execute([$idUser]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupère les propositions d'échange reçues par un utilisateur avec un statut spécifique
     * @param int $idUser ID de l'utilisateur destinataire
     * @param int $idStatut ID du statut (1=Accepter, 2=Refuser)
     * @return array Liste filtrée des propositions
     */
    public function getPropositionsRecuesParUserAvecStatut(int $idUser, int $idStatut): array
    {
        $stmt = $this->db->prepare(
            'SELECT * FROM vw_choixDispoByUserForProposition 
             WHERE id_destinataire = ? AND idStatutEchange = ?
             ORDER BY dateEchange DESC'
        );
        $stmt->execute([$idUser, $idStatut]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupère une proposition d'échange spécifique
     * @param int $idEchange ID de l'échange
     * @param int $idUser ID de l'utilisateur (pour vérifier qu'il est destinataire)
     * @return array|null Détails de la proposition ou null
     */
    public function getPropositionDetail(int $idEchange, int $idUser): ?array
    {
        $stmt = $this->db->prepare(
            'SELECT * FROM vw_choixDispoByUserForProposition 
             WHERE idEchange = ? AND id_destinataire = ?'
        );
        $stmt->execute([$idEchange, $idUser]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }
}
