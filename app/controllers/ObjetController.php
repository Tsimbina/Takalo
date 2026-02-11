<?php

namespace app\controllers;

use Flight;
use app\models\Objet;

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

        $idUser = (int)($_SESSION['user_id'] ?? 0);

        try {
            $objetModel = new Objet(Flight::db());
            $objets = $objetModel->getAllByUser($idUser);
        } catch (\Throwable $e) {
            $objets = [];
        }

        Flight::render('objet', [
            'objets' => $objets,
        ]);
    }
}
