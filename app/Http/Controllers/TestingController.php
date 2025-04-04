<?php

namespace App\Http\Controllers;

use App\Models\HouseTesting;
use Illuminate\Http\Request;
use App\Models\HouseTraining;
use PhpOffice\PhpSpreadsheet\IOFactory;

class TestingController extends Controller
{
    public function index()
    {
        $testings = HouseTesting::paginate(10);
        return view('dataTesting', compact('testings'));
    }

    public function importTesting(Request $request)
    {
        $request->validate(['file' => 'required|mimes:xlsx,xls,csv']);

        try {
            $file = $request->file('file');
            $spreadsheet = IOFactory::load($file->getPathname());
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray();

            // Skip header
            array_shift($rows);

            foreach ($rows as $row) {
                HouseTesting::create([
                    'Square_Footage' => $row[0] ?? 0,
                    'Num_Bedrooms' => $row[1] ?? 0,
                    'Num_Bathrooms' => $row[2] ?? 0,
                    'Year_Built' => $row[3] ?? 0,
                    'Lot_Size' => $row[4] ?? 0,
                    'Garage_Size' => $row[5] ?? 0,
                    'Neighborhood_Quality' => $row[6] ?? 0,
                    'House_Price' => $row[7] ?? null,
                    'predicted_price' => null
                ]);
            }

            return back()->with('success', 'Data testing imported successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Error importing testing data: ' . $e->getMessage());
        }
    }

    public function deleteAll()
    {
        try {
            $count = HouseTesting::count();
            HouseTesting::truncate(); // This will delete all records and reset auto-increment

            return back()->with('success', "Successfully deleted all {$count} training records.");
        } catch (\Exception $e) {
            return back()->with('error', 'Error deleting training data: ' . $e->getMessage());
        }
    }

    public function showResults()
    {
        $testings = HouseTesting::whereNotNull('predicted_price')->paginate(10);
        return view('result', ['testings' => $testings]);
    }

    public function predict(Request $request)
    {
        $request->validate(['k' => 'required|integer|min:1']);
        $k = $request->k;

        try {
            // Ambil data training dan testing
            $trainings = HouseTraining::all();
            $testings = HouseTesting::paginate(10); // Pagination langsung di query

            if ($trainings->isEmpty() || $testings->isEmpty()) {
                return back()->with('error', 'Training or testing data is empty!');
            }

            // Normalisasi data
            $normalizedTrainings = $this->normalizeData($trainings);
            $normalizedTestings = $this->normalizeData($testings->items());

            // Periksa jika data ternormalisasi kosong
            if (empty($normalizedTrainings) || empty($normalizedTestings)) {
                return back()->with('error', 'Normalized data is empty!');
            }

            // Lakukan prediksi untuk setiap data testing
            foreach ($testings as $index => $testing) {
                $distances = [];

                foreach ($normalizedTrainings as $training) {
                    $distance = $this->euclideanDistance($normalizedTestings[$index], $training);
                    $distances[] = [
                        'distance' => $distance,
                        'price' => $training['House_Price']
                    ];
                }

                usort($distances, function ($a, $b) {
                    return $a['distance'] <=> $b['distance'];
                });

                $neighbors = array_slice($distances, 0, $k);
                $sum = array_sum(array_column($neighbors, 'price'));
                $predictedPrice = $sum / $k;

                // Update data testing
                $testing->predicted_price = $predictedPrice;
                $testing->save();
            }

            // Hitung akurasi
            $accuracy = $this->calculateAccuracy($testings);

            return view('result', [
                'testings' => $testings,
                'accuracy' => $accuracy,
                'k' => $k
            ]);
        } catch (\Exception $e) {
            return back()->with('error', 'Error during prediction: ' . $e->getMessage());
        }
    }

    private function normalizeData($data)
    {
        // Handle both collections and arrays
        if (is_array($data) && empty($data)) return [];
        if (is_object($data) && method_exists($data, 'isEmpty') && $data->isEmpty()) return [];

        // Convert to collection if it's an array
        $collection = is_array($data) ? collect($data) : $data;

        $features = [
            'Square_Footage' => $collection->pluck('Square_Footage')->toArray(),
            'Num_Bedrooms' => $collection->pluck('Num_Bedrooms')->toArray(),
            'Num_Bathrooms' => $collection->pluck('Num_Bathrooms')->toArray(),
            'Year_Built' => $collection->pluck('Year_Built')->toArray(),
            'Lot_Size' => $collection->pluck('Lot_Size')->toArray(),
            'Garage_Size' => $collection->pluck('Garage_Size')->toArray(),
            'Neighborhood_Quality' => $collection->pluck('Neighborhood_Quality')->toArray(),
        ];

        $minMax = [];
        foreach ($features as $feature => $values) {
            $minMax[$feature] = [
                'min' => min($values),
                'max' => max($values)
            ];
        }

        $normalizedData = [];
        foreach ($collection as $item) {
            $normalizedData[] = [
                'Square_Footage' => ($item->Square_Footage - $minMax['Square_Footage']['min']) /
                    ($minMax['Square_Footage']['max'] - $minMax['Square_Footage']['min']),
                'Num_Bedrooms' => ($item->Num_Bedrooms - $minMax['Num_Bedrooms']['min']) /
                    ($minMax['Num_Bedrooms']['max'] - $minMax['Num_Bedrooms']['min']),
                'Num_Bathrooms' => ($item->Num_Bathrooms - $minMax['Num_Bathrooms']['min']) /
                    ($minMax['Num_Bathrooms']['max'] - $minMax['Num_Bathrooms']['min']),
                'Year_Built' => ($item->Year_Built - $minMax['Year_Built']['min']) /
                    ($minMax['Year_Built']['max'] - $minMax['Year_Built']['min']),
                'Lot_Size' => ($item->Lot_Size - $minMax['Lot_Size']['min']) /
                    ($minMax['Lot_Size']['max'] - $minMax['Lot_Size']['min']),
                'Garage_Size' => ($item->Garage_Size - $minMax['Garage_Size']['min']) /
                    ($minMax['Garage_Size']['max'] - $minMax['Garage_Size']['min']),
                'Neighborhood_Quality' => ($item->Neighborhood_Quality - $minMax['Neighborhood_Quality']['min']) /
                    ($minMax['Neighborhood_Quality']['max'] - $minMax['Neighborhood_Quality']['min']),
                'House_Price' => $item->House_Price
            ];
        }

        return $normalizedData;
    }

    private function euclideanDistance($a, $b)
    {
        $sum = 0;
        $features = [
            'Square_Footage',
            'Num_Bedrooms',
            'Num_Bathrooms',
            'Year_Built',
            'Lot_Size',
            'Garage_Size',
            'Neighborhood_Quality'
        ];

        foreach ($features as $feature) {
            $sum += pow($a[$feature] - $b[$feature], 2);
        }

        return sqrt($sum);
    }

    private function calculateAccuracy($testings)
    {
        $validTestings = $testings->filter(function ($testing) {
            return !is_null($testing->House_Price) && !is_null($testing->predicted_price);
        });

        if ($validTestings->isEmpty()) {
            return null;
        }

        $sumSquaredError = $validTestings->sum(function ($testing) {
            return pow($testing->House_Price - $testing->predicted_price, 2);
        });

        $mse = $sumSquaredError / $validTestings->count();
        $rmse = sqrt($mse);
        $avgPrice = $validTestings->avg('House_Price');
        $accuracyPercentage = $avgPrice > 0 ? (1 - ($rmse / $avgPrice)) * 100 : 0;

        return [
            'rmse' => round($rmse, 2),
            'accuracy_percentage' => round($accuracyPercentage, 2),
            'mape' => $this->calculateMAPE($validTestings)
        ];
    }

    private function calculateMAPE($testings)
    {
        $count = $testings->count();

        if ($count === 0) {
            return 0; // Atau return nilai default lain sesuai kebutuhan
        }

        $sumPercentageError = $testings->sum(function ($testing) {
            return abs(($testing->House_Price - $testing->predicted_price) / ($testing->House_Price ?: 1)); // Hindari pembagian dengan nol
        });

        return round(($sumPercentageError / $count) * 100, 2);
    }
}