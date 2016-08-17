<?php
$this->get('', 'home@index');
$this->get('a', function(){
    model\name::create(['name' => 'b']);
});
