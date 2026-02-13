<?php

namespace app\models;

use PDO;

class Statistique
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function getTotalUsers(): int
    {
        $stmt = $this->db->query('SELECT COUNT(*) AS total FROM user');
        $row = $stmt ? $stmt->fetch(PDO::FETCH_ASSOC) : null;
        return (int)($row['total'] ?? 0);
    }

    public function getTotalExchanges(): int
    {
        $stmt = $this->db->query('SELECT COUNT(*) AS total FROM echange');
        $row = $stmt ? $stmt->fetch(PDO::FETCH_ASSOC) : null;
        return (int)($row['total'] ?? 0);
    }

    public function getPendingExchanges(): int
    {
        $stmt = $this->db->prepare('SELECT COUNT(*) AS total FROM echange WHERE idStatutEchange = ?');
        $stmt->execute([3]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)($row['total'] ?? 0);
    }

    public function getCompletedExchanges(): int
    {
        $stmt = $this->db->prepare('SELECT COUNT(*) AS total FROM echange WHERE idStatutEchange = ?');
        $stmt->execute([1]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)($row['total'] ?? 0);
    }

    public function getRefusedExchanges(): int
    {
        $stmt = $this->db->prepare('SELECT COUNT(*) AS total FROM echange WHERE idStatutEchange = ?');
        $stmt->execute([2]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)($row['total'] ?? 0);
    }

    public function getTodayNewUsers(): int
    {
        return 0;
    }

    public function getMonthlyNewUsers(int $year): array
    {
        return array_fill(0, 12, 0);
    }

    public function getMonthlyExchanges(int $year): array
    {
        $stmt = $this->db->prepare(
            'SELECT MONTH(dateEchange) AS m, COUNT(*) AS total
             FROM echange
             WHERE YEAR(dateEchange) = ?
             GROUP BY MONTH(dateEchange)
             ORDER BY m'
        );
        $stmt->execute([$year]);

        $months = array_fill(0, 12, 0);
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $m = (int)($row['m'] ?? 0);
            if ($m >= 1 && $m <= 12) {
                $months[$m - 1] = (int)($row['total'] ?? 0);
            }
        }

        return $months;
    }

    public function getUsersGrowthPercentThisMonth(): float
    {
        return 0.0;
    }

    public function getExchangesGrowthPercentThisMonth(): float
    {
        return 0.0;
    }
}
