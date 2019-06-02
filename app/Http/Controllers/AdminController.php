<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

class AdminController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     *
     * Админка
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function main() {
        return view('admin.main', []);
    }
}
