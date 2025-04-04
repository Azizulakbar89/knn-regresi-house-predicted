<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class HouseTraining extends Model
{
    use HasFactory;

    protected $fillable = [
        'Square_Footage',
        'Num_Bedrooms',
        'Num_Bathrooms',
        'Year_Built',
        'Lot_Size',
        'Garage_Size',
        'Neighborhood_Quality',
        'House_Price'
    ];
}