<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    //
    public function index()
    {
       $today = Carbon::today();
        // Gunakan clone() agar variabel dasar tidak berubah saat dimanipulasi
        $startOfWeek = Carbon::now()->startOfWeek()->format('Y-m-d');
        $endOfWeek = Carbon::now()->endOfWeek()->format('Y-m-d');

        // 2. Ambil data Omzet dengan penulisan yang benar
        // Pastikan 'whereDate' (bukan whereData) dan sum() ada di luar kurung
        $omzetHarian = Sale::whereDate('sales_date', $today)->sum('total');
        
        $omzetMingguan = Sale::whereBetween('sales_date', [$startOfWeek, $endOfWeek])->sum('total');
        
        $omzetBulanan = Sale::whereMonth('sales_date', Carbon::now()->month)
                            ->whereYear('sales_date', Carbon::now()->year)
                            ->sum('total');
                            
        $omzetTahunan = Sale::whereYear('sales_date', Carbon::now()->year)->sum('total');
    
     $chartData = Sale::select(
                DB::raw('DATE(sales_date) as date'),
                DB::raw('SUM(total) as total')
            )
            ->where('sales_date', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->get();

        $labels = $chartData->pluck('date'); // Tanggal untuk sumbu X
        $totals = $chartData->pluck('total'); // Angka omzet untuk sumbu Y

        return view('reports.index', compact(
            'omzetHarian', 
            'omzetMingguan', 
            'omzetBulanan', 
            'omzetTahunan',
            'labels',
            'totals'
        ));   
    
    }
}
