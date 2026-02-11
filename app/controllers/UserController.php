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

    public function showLogin(): void
    {
        $this->ensureSessionStarted();

        $error = $_SESSION['user_login_error'] ?? null;
        unset($_SESSION['user_login_error']);

        Flight::render('loginUser', [
            'error' => $error,
        ]);
    }

    public function handleLogin(): void
    {
        $this->ensureSessionStarted();

        $loginOrEmail = (string)($_POST['login'] ?? '');
        $password = (string)($_POST['password'] ?? '');

        try {
            $userModel = new User(Flight::db());
            $user = $userModel->verifyLogin($loginOrEmail, $password);

            if ($user !== null) {
                $_SESSION['user_authenticated'] = true;
                $_SESSION['user_id'] = (int)($user['id'] ?? 0);
                $_SESSION['user_login'] = (string)($user['login'] ?? '');
                $_SESSION['user_email'] = (string)($user['email'] ?? '');

                Flight::redirect('/');
                return;
            }

            $_SESSION['user_login_error'] = 'Identifiants invalides.';
            Flight::redirect('/login');
        } catch (\Throwable $e) {
            $_SESSION['user_login_error'] = 'Erreur serveur lors de la connexion.';
            Flight::redirect('/login');
        }
    }

    public function showRegister(): void
    {
        $this->ensureSessionStarted();

        $error = $_SESSION['user_register_error'] ?? null;
        $success = $_SESSION['user_register_success'] ?? null;
        unset($_SESSION['user_register_error'], $_SESSION['user_register_success']);

        Flight::render('inscripition', [
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
