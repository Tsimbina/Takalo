<?php

namespace app\controllers;

use Flight;
use app\models\Estimation;

class EstimationController
{
    public function estimation($idObjet, $pourcent )
    {
        $idObjet = (int)$idObjet;
        $pourcent = (float)$pourcent;
        $estimationModel = new Estimation(Flight::db());
        $objets = $estimationModel->getEstimationObjet($idObjet, $pourcent);
        Flight::render('user/objet/estimation', [
            'objets' => $objets,
            'idObjet' => $idObjet,
            'pourcent' => $pourcent
        ]);
    }
}
