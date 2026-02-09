<?php

namespace app\models;

use PDO;

class Admin
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function authenticate(string $login, string $passwd): bool
    {
        $login = trim($login);
        if ($login === '' || $passwd === '') {
            return false;
        }

        return $this->findByCredentials($login, $passwd) !== null;
    }

    public function findByCredentials(string $login, string $passwd): ?array
    {
        $stmt = $this->db->prepare('SELECT id, login FROM admin WHERE login = ? AND passwd = ? LIMIT 1');
        $stmt->execute([$login, $passwd]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row !== false ? $row : null;
    }
}
