<?php

namespace app\controllers;

use Flight;

class ObjetController
{
    private function ensureSessionStarted(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
    }

    public function index(): void
    {
        $this->ensureSessionStarted();

        if (empty($_SESSION['user_authenticated'])) {
            Flight::redirect('/login');
            return;
        }

        Flight::render('objet');
    }
}
