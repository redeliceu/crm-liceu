<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

class UserController extends Controller
{
    //
    public function list()
    {
        return Inertia::render('users/List');
    }
}
