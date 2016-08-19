<?php
namespace controller;

use model\name;

class home
{
    public function index()
    {
        view('home', ['n' => 'hi']);
    }
    public function b()
    {
        name::delete(['name' => 'a']);
    }
    public function c()
    {
        name::update(['name' => 'a'], ['name' => 'b']);
    }
}
