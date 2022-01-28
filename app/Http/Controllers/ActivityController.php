<?php

namespace App\Http\Controllers;

class ActivityController extends Controller
{
    public function index()
    {
        $this->authorize('lihat aktifitas user');

        return view('users.activities');

    }
}
