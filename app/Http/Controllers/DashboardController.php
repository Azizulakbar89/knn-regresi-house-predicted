<?php

namespace App\Http\Controllers;

use App\Models\HouseTesting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function showPriceAnalysis(Request $request)
    {
        // Get price statistics
        $priceStats = $this->getPriceStatistics();

        // Get all available years (for filter dropdown)
        $availableYears = HouseTesting::whereNotNull('Year_Built')
            ->selectRaw('Year_Built as year')
            ->groupBy('year')
            ->orderBy('year', 'DESC')
            ->pluck('year');

        if ($availableYears->isEmpty()) {
            $availableYears = collect([date('Y')]);
        }

        // Get ALL years data (not filtered)
        $priceData = $this->getAllYearsPriceData();

        // AJAX response
        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'years' => $priceData['years'],
                'highestPrices' => $priceData['highestPrices'],
                'lowestPrices' => $priceData['lowestPrices']
            ]);
        }

        return view('welcome', [
            'priceStats' => $priceStats,
            'availableYears' => $availableYears,
            'chartData' => $priceData // Pass initial data
        ]);
    }

    private function getAllYearsPriceData()
    {
        $data = HouseTesting::query()
            ->select([
                DB::raw('Year_Built as year'),
                DB::raw('MAX(predicted_price) as highest_price'),
                DB::raw('MIN(predicted_price) as lowest_price')
            ])
            ->whereNotNull('predicted_price')
            ->whereNotNull('Year_Built')
            ->groupBy('Year_Built')
            ->orderBy(DB::raw('CAST(Year_Built AS UNSIGNED)'))
            ->get();

        return [
            'years' => $data->pluck('year')->map(fn($y) => (string)$y)->toArray(),
            'highestPrices' => $data->pluck('highest_price')->map(fn($p) => (float)$p)->toArray(),
            'lowestPrices' => $data->pluck('lowest_price')->map(fn($p) => (float)$p)->toArray()
        ];
    }

    private function getPriceStatistics()
    {
        try {
            $stats = HouseTesting::query()
                ->select([
                    DB::raw('COUNT(DISTINCT Year_Built) as years_count'),
                    DB::raw('AVG(max_price) as avg_high'),
                    DB::raw('AVG(min_price) as avg_low')
                ])
                ->fromSub(function ($query) {
                    $query->from('house_testings')
                        ->select([
                            'Year_Built',
                            DB::raw('MAX(predicted_price) as max_price'),
                            DB::raw('MIN(predicted_price) as min_price')
                        ])
                        ->whereNotNull('predicted_price')
                        ->whereNotNull('Year_Built')
                        ->groupBy('Year_Built');
                }, 'subquery')
                ->first();

            return [
                'avgHigh' => $stats->avg_high ?? 0,
                'avgLow' => $stats->avg_low ?? 0,
                'years' => $stats->years_count ?? 0
            ];
        } catch (\Exception $e) {
            return [
                'avgHigh' => 0,
                'avgLow' => 0,
                'years' => 0
            ];
        }
    }
}
