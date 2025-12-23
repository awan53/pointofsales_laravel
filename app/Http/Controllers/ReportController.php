<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $today = Carbon::today();
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $omzetPeriode = 0;

        // 1. Filter Omzet Periode
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $start = Carbon::parse($startDate)->startOfDay();
            $end = Carbon::parse($endDate)->endOfDay();
            $omzetPeriode = Sale::whereBetween('sales_date', [$start, $end])->sum('total');
        }

        // 2. Omzet Standar (Harian, Mingguan, Bulanan)
        $omzetHarian = Sale::whereDate('sales_date', $today)->sum('total');
        
        $startOfWeek = Carbon::now()->startOfWeek()->toDateString();
        $endOfWeek = Carbon::now()->endOfWeek()->toDateString();
        $omzetMingguan = Sale::whereBetween('sales_date', [$startOfWeek, $endOfWeek])->sum('total');
        
        $omzetBulanan = Sale::whereMonth('sales_date', Carbon::now()->month)
                            ->whereYear('sales_date', Carbon::now()->year)
                            ->sum('total');
                            
        $omzetTahunan = Sale::whereYear('sales_date', Carbon::now()->year)->sum('total');

        // 3. Data Chart
        $chartData = Sale::select(
                DB::raw('DATE(sales_date) as date'),
                DB::raw('SUM(total) as total')
            )
            ->where('sales_date', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->get();

        $labels = $chartData->pluck('date');
        $totals = $chartData->pluck('total');

        // 4. Return View (Sekarang semua variabel sudah di dalam fungsi)
        return view('reports.index', compact(
            'omzetHarian', 
            'omzetMingguan', 
            'omzetBulanan', 
            'omzetTahunan',
            'omzetPeriode', 
            'labels',
            'totals',
            'startDate',
            'endDate'
        )); 
    } // Penutup fungsi index ada di sini
} // Penutup class ada di sini