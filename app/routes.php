<?php
$this->get('home/:id', 'home@index');
$this->get('home', function(){
    echo 'hi';
});
