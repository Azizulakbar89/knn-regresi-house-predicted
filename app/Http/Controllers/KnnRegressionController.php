<?php

namespace App\Http\Controllers;

use App\Models\HouseTraining;
use App\Models\HouseTesting;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;

class KnnRegressionController extends Controller
{
    // public function index()
    // {
    //     $trainings = HouseTraining::all();
    //     $testings = HouseTesting::all();
    //     return view('dataTraining', compact('trainings', 'testings'));
    // }

    // public function importTraining(Request $request)
    // {
    //     $request->validate(['file' => 'required|mimes:xlsx,xls,csv']);

    //     try {
    //         $file = $request->file('file');
    //         $spreadsheet = IOFactory::load($file->getPathname());
    //         $sheet = $spreadsheet->getActiveSheet();
    //         $rows = $sheet->toArray();

    //         // Skip header
    //         array_shift($rows);

    //         foreach ($rows as $row) {
    //             HouseTraining::create([
    //                 'Square_Footage' => $row[0] ?? 0,
    //                 'Num_Bedrooms' => $row[1] ?? 0,
    //                 'Num_Bathrooms' => $row[2] ?? 0,
    //                 'Year_Built' => $row[3] ?? 0,
    //                 'Lot_Size' => $row[4] ?? 0,
    //                 'Garage_Size' => $row[5] ?? 0,
    //                 'Neighborhood_Quality' => $row[6] ?? 0,
    //                 'House_Price' => $row[7] ?? 0,
    //             ]);
    //         }

    //         return back()->with('success', 'Data training imported successfully!');
    //     } catch (\Exception $e) {
    //         return back()->with('error', 'Error importing training data: ' . $e->getMessage());
    //     }
    // }





}
