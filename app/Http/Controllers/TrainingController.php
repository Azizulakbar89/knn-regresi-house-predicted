<?php

namespace App\Http\Controllers;

use App\Models\HouseTraining;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Http\Request;

class TrainingController extends Controller
{
    public function index()
    {
        $trainings = HouseTraining::paginate(10); // Mengubah all() menjadi paginate(10)
        return view('dataTraining', compact('trainings'));
    }

    public function importTraining(Request $request)
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
                HouseTraining::create([
                    'Square_Footage' => $row[0] ?? 0,
                    'Num_Bedrooms' => $row[1] ?? 0,
                    'Num_Bathrooms' => $row[2] ?? 0,
                    'Year_Built' => $row[3] ?? 0,
                    'Lot_Size' => $row[4] ?? 0,
                    'Garage_Size' => $row[5] ?? 0,
                    'Neighborhood_Quality' => $row[6] ?? 0,
                    'House_Price' => $row[7] ?? 0,
                ]);
            }

            return back()->with('success', 'Data training imported successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Error importing training data: ' . $e->getMessage());
        }
    }

    // Add this new method for deleting all training data
    public function deleteAll()
    {
        try {
            $count = HouseTraining::count();
            HouseTraining::truncate(); // This will delete all records and reset auto-increment

            return back()->with('success', "Successfully deleted all {$count} training records.");
        } catch (\Exception $e) {
            return back()->with('error', 'Error deleting training data: ' . $e->getMessage());
        }
    }
}