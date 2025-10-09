<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class ConnexionController extends BaseController
{
    public function index()
    {
        return view('connexion');
    }
    public function login()
    {
        return redirect('dashboard');
    }
}
