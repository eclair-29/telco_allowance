<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class HomeController extends Controller
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
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = auth()->user();
        // Role::create(['name' => 'publisher']);
        // Role::create(['name' => 'approver']);

        // $user->assignRole('publisher');
        $user->assignRole('approver');
        // return $user->hasRole('publisher');

        return view('home');
    }
}
