<?php

namespace App\Services;

use App\Models\Meter;
use App\Models\MeterReading;
use Carbon\Carbon;


class MeterReadingService
{
    /**
     * Calculate the estimated meter reading
     *
     * @param Meter $meter
     * @param string $givenDate
     *
     * @return int estimated meter reading
     */
    public function getEstimatedMeterReading($meter, $givenDate)
    {
        //Get newest meter reading for given meter that is not deleted
        $previousReading = MeterReading::where('meter_id', $meter->meter_id)
            ->where('deleted_at', null)
            ->orderBy('date', 'DESC')->first();

        $givenDate = Carbon::parse($givenDate);

        //Convert the date to a Carbon instance
        $previousDate = Carbon::parse($previousReading->date);

        //timePassed = Time between previous reading date and given date to estimate for
        //Calculate the number of days between the two dates
        $timeElapsed = $previousDate->diffInDays($givenDate);

        //Calculate portion of year that has passed
        //portionOfYear = timePassed / number of days in a year (365)
        $portionOfYear = $timeElapsed / 365;

        //Estimated consumption for portion of year thats passed
        //estimatedConsumption = estimated_annual_consumption * portionOfYear
        $estimatedConsumption = $meter->eac * $portionOfYear;

        //Add estimated consumption to the previous meter reading
        //estimatedMeterReading = previousMeterReading * estimatedConsumption
        $estimatedMeterReading = $previousReading->reading * $estimatedConsumption;

        return $estimatedMeterReading;
    }

    /**
     * No longer used! Now done in a Job
     * Import csv.
     */
    public function importCSV(Request $request)
    {
        // Retrieve the uploaded CSV file from the request
        $file = $request->file('csv_file');

        // Define an array to store the invalid entries
        $invalidEntries = [];

        // Read the CSV file
        if (($handle = fopen($file->path(), 'r')) !== false) {
            while (($data = fgetcsv($handle)) !== false) {
                // Perform validation on each CSV entry
                $isValid = $this->validateCSVEntry($data);

                if ($isValid) {
                    // Insert the valid entry into the database
                    MeterReading::create([
                        // Map the CSV data to the appropriate database fields
                        'reading' => $data[0],
                        'meter_id' => $data[1],
                        'date' => $data[2],
                    ]);
                } else {
                    // Store the invalid entry
                    $invalidEntries[] = $data;
                }
            }

            fclose($handle);
        }

        // Provide a message to the user regarding invalid entries
        if (count($invalidEntries) > 0) {
            $message = 'The following lines are invalid: ' . implode(', ', array_keys($invalidEntries));
        } else {
            $message = 'All entries were successfully imported.';
        }

        return response()->json(['message' => $message]);
    }

    private function validateCSVEntry($data)
    {
        // TODO: Validate csv file
    }
}
