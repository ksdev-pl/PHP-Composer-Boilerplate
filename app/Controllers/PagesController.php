<?php namespace App\Controllers;

class PagesController
{
    public function home()
    {
        require_once VIEWS . 'home.php';
    }
}
