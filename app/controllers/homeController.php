<?php
namespace controllers;

use models\name;

class homeController
{
    public function index()
    {
        view('home');
    }

    public function test()
    {
        view('test', ['title' => 'hello world'])->push('www', 'xxx')->render();
    }
}
