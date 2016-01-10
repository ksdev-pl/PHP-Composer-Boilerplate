<?php

namespace App\Controllers;

class Controller
{
    protected $view;

    public function __construct()
    {
        $this->view = new \League\Plates\Engine(APP . '/views/');
    }
}
