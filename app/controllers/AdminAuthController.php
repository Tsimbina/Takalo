<?php

namespace app\controllers;

use Flight;
use app\models\Admin;

class AdminAuthController
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

        $error = $_SESSION['admin_login_error'] ?? null;
        unset($_SESSION['admin_login_error']);

        Flight::render('loginAdmin', [
            'error' => $error,
        ]);
    }

    public function handleLogin(): void
    {
        $this->ensureSessionStarted();

        $email = trim((string)($_POST['email'] ?? ''));
        $password = (string)($_POST['password'] ?? '');

        try {
            $adminModel = new Admin(Flight::db());
            $admin = $adminModel->findByCredentials($email, $password);

            if ($admin !== null) {
                $_SESSION['admin_authenticated'] = true;
                $_SESSION['admin_email'] = (string)($admin['login'] ?? $email);
                Flight::redirect('/admin');
                return;
            }
        } catch (\Throwable $e) {
            $_SESSION['admin_login_error'] = 'Erreur serveur lors de la connexion.';
            Flight::redirect('/admin/login');
            return;
        }

        $_SESSION['admin_login_error'] = 'Identifiants invalides.';
        Flight::redirect('/admin/login');
    }

    public function logout(): void
    {
        $this->ensureSessionStarted();

        unset($_SESSION['admin_authenticated'], $_SESSION['admin_email']);
        Flight::redirect('/admin/login');
    }

    public function dashboard(): void
    {
        $this->ensureSessionStarted();

        if (empty($_SESSION['admin_authenticated'])) {
            Flight::redirect('/admin/login');
            return;
        }

        $email = (string)($_SESSION['admin_email'] ?? 'admin');

        Flight::render('adminDashboard', [
            'email' => $email,
        ]);
    }
}
