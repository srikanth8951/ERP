<?php

namespace App\Controllers;

class Seeder extends BaseController
{
    public function index()
    {
        $seeder = \Config\Database::seeder();
        $seeder->call('CurrencySeeder');
    }
}