<?php

namespace App\Controllers;

class PagesController extends Controller
{
    public function home()
    {
        echo $this->view->render('home');
    }
}
