<?php

namespace App\Controllers;

use App\Helpers\DB;

class PagesController extends Controller
{
    public function home()
    {
        echo $this->view->render('home');
    }
}
