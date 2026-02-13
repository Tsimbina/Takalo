<?php

namespace app\models;

use PDO;

class User
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function existsByEmail(string $email): bool
    {
        $stmt = $this->db->prepare('SELECT id FROM user WHERE email = ? LIMIT 1');
        $stmt->execute([trim($email)]);
        return $stmt->fetchColumn() !== false;
    }

    public function existsByLogin(string $login): bool
    {
        $stmt = $this->db->prepare('SELECT id FROM user WHERE login = ? LIMIT 1');
        $stmt->execute([trim($login)]);
        return $stmt->fetchColumn() !== false;
    }

    public function verifyLogin(string $loginOrEmail, string $passwd): ?array
    {
        $loginOrEmail = trim($loginOrEmail);
        if ($loginOrEmail === '' || $passwd === '') {
            return null;
        }

        $stmt = $this->db->prepare('SELECT id, email, login FROM user WHERE (email = ? OR login = ?) AND passwd = ? LIMIT 1');
        $stmt->execute([$loginOrEmail, $loginOrEmail, $passwd]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row !== false ? $row : null;
    }

    public function register(string $email, string $login, string $passwd, string $passwdConfirm): array
    {
        $email = trim($email);
        $login = trim($login);

        if ($email === '' || $login === '' || $passwd === '' || $passwdConfirm === '') {
            return ['success' => false, 'error' => 'Veuillez remplir tous les champs.'];
        }

        if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            return ['success' => false, 'error' => 'Email invalide.'];
        }

        if ($passwd !== $passwdConfirm) {
            return ['success' => false, 'error' => 'Les mots de passe ne correspondent pas.'];
        }

        if (strlen($passwd) < 8) {
            return ['success' => false, 'error' => 'Le mot de passe doit contenir au moins 8 caractères.'];
        }

        if ($this->existsByEmail($email) === true) {
            return ['success' => false, 'error' => 'Cet email est déjà utilisé.'];
        }

        if ($this->existsByLogin($login) === true) {
            return ['success' => false, 'error' => 'Ce login est déjà utilisé.'];
        }

        $stmt = $this->db->prepare('INSERT INTO user (email, login, passwd) VALUES (?, ?, ?)');
        $stmt->execute([$email, $login, $passwd]);

        return ['success' => true, 'user_id' => (int)$this->db->lastInsertId()];
    }

    public function getAllHistoriqueObjet(int $idObjet): array
    {
        if ($idObjet <= 0) {
            return [];
        }

        $currentOwnerStmt = $this->db->prepare('SELECT idProprio FROM objet WHERE id = ?');
        $currentOwnerStmt->execute([$idObjet]);
        $currentOwnerId = (int)($currentOwnerStmt->fetchColumn() ?: 0);

        $stmt = $this->db->prepare(
            'SELECT owner_id, MIN(dt) AS first_dt
             FROM (
                 SELECT
                     CASE
                         WHEN e.idObjet1 = ? THEN e.idProposeur
                         WHEN e.idObjet2 = ? THEN e.idDestinataire
                     END AS owner_id,
                     e.dateEchange AS dt
                 FROM echange e
                 WHERE e.idStatutEchange = 1 AND (e.idObjet1 = ? OR e.idObjet2 = ?)

                 UNION ALL

                 SELECT
                     CASE
                         WHEN e.idObjet1 = ? THEN e.idDestinataire
                         WHEN e.idObjet2 = ? THEN e.idProposeur
                     END AS owner_id,
                     e.dateEchange AS dt
                 FROM echange e
                 WHERE e.idStatutEchange = 1 AND (e.idObjet1 = ? OR e.idObjet2 = ?)
             ) t
             WHERE owner_id IS NOT NULL
             GROUP BY owner_id
             ORDER BY first_dt ASC'
        );

        $stmt->execute([
            $idObjet, $idObjet, $idObjet, $idObjet,
            $idObjet, $idObjet, $idObjet, $idObjet,
        ]);

        $owners = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $ownerRows = [];
        foreach ($owners as $r) {
            $oid = (int)($r['owner_id'] ?? 0);
            if ($oid <= 0) {
                continue;
            }
            $ownerRows[] = [
                'owner_id' => $oid,
                'date' => $r['first_dt'] ?? null,
            ];
        }

        $ownerIds = array_map(static fn($r) => (int)$r['owner_id'], $ownerRows);

        if ($currentOwnerId > 0 && !in_array($currentOwnerId, $ownerIds, true)) {
            $ownerRows[] = [
                'owner_id' => $currentOwnerId,
                'date' => null,
            ];
            $ownerIds[] = $currentOwnerId;
        }

        if (empty($ownerIds)) {
            return [];
        }

        $placeholders = implode(',', array_fill(0, count($ownerIds), '?'));
        $userStmt = $this->db->prepare("SELECT id, login, email FROM user WHERE id IN ($placeholders)");
        $userStmt->execute($ownerIds);
        $users = $userStmt->fetchAll(PDO::FETCH_ASSOC);

        $usersById = [];
        foreach ($users as $u) {
            $usersById[(int)($u['id'] ?? 0)] = $u;
        }

        $result = [];
        foreach ($ownerRows as $row) {
            $oid = (int)($row['owner_id'] ?? 0);
            if ($oid <= 0 || empty($usersById[$oid])) {
                continue;
            }

            $u = $usersById[$oid];
            $u['date'] = $row['date'] ?? null;
            $result[] = $u;
        }

        return $result;
    }
}
