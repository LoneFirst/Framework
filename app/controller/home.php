<?php
namespace controller;

use model\name;

class home
{
    public function index()
    {
        // view('home');
        name::create(['name' => 'a']);

    }
}
