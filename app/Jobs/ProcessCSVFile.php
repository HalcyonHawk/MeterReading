<?php

namespace App\Jobs;

use App\Models\MeterReading;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessCSVFile implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $filePath;

    /**
     * Create a new job instance.
     *
     * @param string $filePath
     */
    public function __construct($filePath)
    {
        $this->filePath = $filePath;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Define an array to store the invalid entries
        $invalidEntries = [];

        // Read the CSV file
        if (($handle = fopen($this->filePath, 'r')) !== false) {
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

        // Optionally, you can perform additional actions with the invalid entries if needed

        // For example, you can log the invalid entries or send a notification
        if (count($invalidEntries) > 0) {
            $message = 'The following lines are invalid: ' . implode(', ', array_keys($invalidEntries));
        } else {
            $message = 'All entries were successfully imported.';
        }
    }

    /**
     * Validate a CSV entry.
     *
     * @param array $data
     * @return bool
     */
    private function validateCSVEntry($data)
    {
        // TODO: Validate csv file
    }
}
