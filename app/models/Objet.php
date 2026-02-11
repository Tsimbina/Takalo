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
}
