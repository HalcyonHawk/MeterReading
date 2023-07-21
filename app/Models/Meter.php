<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meter extends Model
{
    use HasFactory;

    protected $table = 'meters';
    protected $primaryKey = 'meter_id';
    public $timestamps = false;
    protected $guarded = [];

    /**
     * Format date to be displayed
     * TODO: Store UTC in database, convert to users timezone here
     */
    public function getInstallDateFormattedAttribute()
    {
        return date_format(date_create($this->install_date), 'd/m/Y');
    }

    /**
     * Get the type formatted to be dispalyed.
     * Only 2 types so if statement ok
     */
    public function getTypeNameAttribute()
    {
        return ($this->type == 'ELECTRIC') ? 'Electric' : 'Gas';
    }

    /**
     * A meter has many meter readings
     */
    public function meterReadings()
    {
        return $this->hasMany('App\Models\MeterReading', 'meter_reading_id', 'meter_reading_id');
    }
}
