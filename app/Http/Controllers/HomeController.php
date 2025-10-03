<?php

namespace App\Http\Controllers;

use App\Services\DashboardServices;
use Illuminate\Http\Request;

class HomeController extends Controller
{

    public function __construct( private DashboardServices $dashboardServices)
    {
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $dashboardData = $this->dashboardServices->getDashboardData();
        return view('dashboard', ['dashboardData' => $dashboardData] );
    }
}
