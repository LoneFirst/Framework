<?php
namespace controllers;

use models\name;

class homeController
{
    public function index()
    {
        view('status')->render();
    }

    public function s($s, $l)
    {
        name::create(['name' => $s, 'last name' => $l]);
    }
}
