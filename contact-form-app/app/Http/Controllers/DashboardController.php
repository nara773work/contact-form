<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
    if ($request->user()) {
            return view('dashboard', ['user' => $request->user()]);
        }

    return redirect('/login');
    }
}
