<?php
$this->get('', 'home@index');
$this->get('a', function(){
    model\name::create(['name' => 'b']);
});
$this->get('b', 'home@b');
$this->get('c', 'home@c');
