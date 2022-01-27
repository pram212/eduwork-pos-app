<?php

namespace App\Http\Controllers;

class ActivityController extends Controller
{
    public function index()
    {
        $this->authorize('lihat activity');

        return view('users.activities');

    }
}
