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
}
