<?php

namespace app\controllers;

use Flight;

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

        $defaultEmail = 'admin@takalo.local';
        $defaultPassword = 'admin123';

        if ($email === $defaultEmail && $password === $defaultPassword) {
            $_SESSION['admin_authenticated'] = true;
            $_SESSION['admin_email'] = $email;
            Flight::redirect('/admin');
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
