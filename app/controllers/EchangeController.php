<?php

namespace app\controllers;

use Flight;

class EchangeController
{
    public function index(): void
    {
        Flight::render('user/echange/echange');
    }
}
