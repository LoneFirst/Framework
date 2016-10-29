<?php
namespace controllers;

use models\name;

class homeController
{
    public function index()
    {
        view('status')->push('title', 'Oops!')->push('error', '404')->render();
    }
}
