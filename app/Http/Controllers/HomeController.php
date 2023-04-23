<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
        return view('dashboard.home');
    }

    public function setSideBarToggle(Request $request)
    {
        $valueToggle= $request->sidenav_toggled=='true';
        $isMobile= $request->isMobile=='true';
        session(['sideBarToggle' => $valueToggle && !$isMobile?"sidenav-toggled":""]);
        //var_dump($valueToggle);

        return response()->json([
        'sideBarToggle'=>session('sideBarToggle'),
        
        ]);
    }
}
