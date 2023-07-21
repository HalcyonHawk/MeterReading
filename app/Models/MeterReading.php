<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Rules\WithinEstimatedRange;

class MeterReading extends Model
{
    use HasFactory;

    protected $table = 'meter_readings';
    protected $primaryKey = 'meter_reading_id';
    public $timestamps = false;
    protected $guarded = [];

    /**
     * Format date to be displayed
     * TODO: Store UTC in database, convert to users timezone here
     */
    public function getDateFormattedAttribute()
    {
        return date_format(date_create($this->install_date), 'd/m/Y');
    }

    /**
     * Validation rules for meter readings
     */
    public static function getValidationRules($estimatedMeterReading)
    {
        return [
            'reading' => ['required', 'numeric', new WithinEstimatedRange($estimatedMeterReading)],
        ];
    }

    /**
     * A meter reading belongs to a user
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }

    /**
     * A meter reading belongs to a meter
     */
    public function meter()
    {
        return $this->belongsTo('App\Models\Meter', 'meter_id', 'meter_id');
    }
}
