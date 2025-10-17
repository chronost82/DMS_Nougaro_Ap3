<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class LogoutController extends BaseController
{
    public function logout()
    {
        auth()->logout();
        return redirect('/login');
    }
}
