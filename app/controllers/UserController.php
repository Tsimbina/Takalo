<?php

namespace app\controllers;

use app\models\User;
use Flight;

class UserController
{
    private function ensureSessionStarted(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
    }

    public function showRegister(): void
    {
        $this->ensureSessionStarted();

        $error = $_SESSION['user_register_error'] ?? null;
        $success = $_SESSION['user_register_success'] ?? null;
        unset($_SESSION['user_register_error'], $_SESSION['user_register_success']);

        Flight::render('registerUser', [
            'error' => $error,
            'success' => $success,
        ]);
    }

    public function handleRegister(): void
    {
        $this->ensureSessionStarted();

        $email = (string)($_POST['email'] ?? '');
        $login = (string)($_POST['login'] ?? '');
        $password = (string)($_POST['password'] ?? '');
        $passwordConfirm = (string)($_POST['password_confirm'] ?? '');

        try {
            $userModel = new User(Flight::db());
            $result = $userModel->register($email, $login, $password, $passwordConfirm);

            if (!empty($result['success'])) {
                $_SESSION['user_register_success'] = 'Inscription réussie. Vous pouvez vous connecter.';
                Flight::redirect('/register');
                return;
            }

            $_SESSION['user_register_error'] = (string)($result['error'] ?? 'Inscription impossible.');
            Flight::redirect('/register');
        } catch (\Throwable $e) {
            $_SESSION['user_register_error'] = 'Erreur serveur lors de l\'inscription.';
            Flight::redirect('/register');
        }
    }
}
