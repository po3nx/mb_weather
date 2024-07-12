<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Weather extends Model {
    protected $fillable = [
        'lat',
        'lon',
        'time', 
        'pictocode', 
        'isdaylight', 
        'rainspot',
        'winddirection',
        'windspeed',
        'temperature',
        'felttemperature',
        'precipitation',
        'precipitation_probability',
        'uvindex',
        'relativehumidity',
        'snowfraction',
        'convective_precipitation',
        'sealevelpressure'
    ];
}