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
    public function index(Request $request)
    {

        $year = $request->query('year');
        $month = $request->query('month');

        if ($year === null && $month === null) {
            $now = now();
            $year = $now->year;
            $month = $now->month;
        }

        $dashboardData = $this->dashboardServices->getDashboardData($year, $month);
        return view('dashboard', ['dashboardData' => $dashboardData] );
    }

    public function years()
    {
        $years = $this->dashboardServices->yearsAvailables();
        return response()->json($years);
    }

    public function months(Request $request)
    {
        $year = $request->query('year');
        $months = $this->dashboardServices->months($year);
        return response()->json($months);
    }

    public function api()
    {

        $year = request()->query('year');
        $month = request()->query('month');

        $dashboardData = $this->dashboardServices->getDashboardData( $year, $month );
        return response()->json($dashboardData);
    }
}
