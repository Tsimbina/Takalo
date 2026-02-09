<?php

namespace app\controllers;

use Flight;
use app\models\Vehicule;

class ApiVehiculeController
{

    protected $db;

    public function __construct()
    {
        // Utilise Flight pour récupérer la DB
        $this->db = Flight::db();
    }

    public function getStatVehicule()
    {
        $vh = new Vehicule($this->db);
        Flight::render('vehicule', ['vehicules' => $vh->getStatVehicule()]);
    }
    public function getStatDispoVehicule($date)
    {
         $vh = new Vehicule($this->db);
        Flight::render('stat_dispo', ['vehicules' => $vh->getStatDispoVehicule($date)]);
    }
}
