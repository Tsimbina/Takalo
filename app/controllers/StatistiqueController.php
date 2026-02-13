<?php

namespace app\controllers;

use Flight;
use app\models\Statistique;

class StatistiqueController
{
    private function ensureSessionStarted(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
    }

    public function admin(): void
    {
        $this->ensureSessionStarted();

        if (empty($_SESSION['admin_authenticated'])) {
            Flight::redirect('/admin/login');
            return;
        }

        try {
            $statsModel = new Statistique(Flight::db());
            $year = (int)date('Y');

            $stats = [
                'totalUsers' => $statsModel->getTotalUsers(),
                'totalExchanges' => $statsModel->getTotalExchanges(),
                'usersGrowth' => $statsModel->getUsersGrowthPercentThisMonth(),
                'exchangesGrowth' => $statsModel->getExchangesGrowthPercentThisMonth(),
                'todayUsers' => $statsModel->getTodayNewUsers(),
                'pendingExchanges' => $statsModel->getPendingExchanges(),
                'completedExchanges' => $statsModel->getCompletedExchanges(),
                'refusedExchanges' => $statsModel->getRefusedExchanges(),
                'monthlyUsers' => $statsModel->getMonthlyNewUsers($year),
                'monthlyExchanges' => $statsModel->getMonthlyExchanges($year),
            ];
        } catch (\Throwable $e) {
            $stats = [
                'totalUsers' => 0,
                'totalExchanges' => 0,
                'usersGrowth' => 0,
                'exchangesGrowth' => 0,
                'todayUsers' => 0,
                'pendingExchanges' => 0,
                'completedExchanges' => 0,
                'refusedExchanges' => 0,
                'monthlyUsers' => array_fill(0, 12, 0),
                'monthlyExchanges' => array_fill(0, 12, 0),
            ];
        }

        Flight::render('statistique/admin', [
            'stats' => $stats,
        ]);
    }
}
