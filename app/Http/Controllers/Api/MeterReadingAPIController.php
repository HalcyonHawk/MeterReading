<?php

namespace App\Http\Controllers\Api;

use App\Services\MeterReadingService;

use App\Http\Resources\EstimatedMeterReadingResource;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;


class MeterReadingAPIController extends Controller
{
    public function __construct(MeterReadingService $meterReadingService)
    {
        $this->meterReadingService = $meterReadingService;
    }

    /**
     * Get estimated meter reading formatted
     *
     * @param Meter $meter meter the reading belongs to
     *
     * @return JSON Estimated meter reading formatted to JSON
     */
    public function getEstimatedMeterReading($meter, $date)
    {
        $estimatedMeterReading = $this->meterReadingService->getEstimatedMeterReading($meter, $date);

        return MeterReadingResource::collection($estimatedMeterReading);
    }
}
