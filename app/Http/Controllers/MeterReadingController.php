<?php

namespace App\Http\Controllers;

use App\Models\MeterReading;
use App\Models\Meter;
use App\Http\Requests\StoreMeterReadingRequest;
use App\Http\Requests\UpdateMeterReadingRequest;
use Illuminate\Support\Facades\Auth;
use App\Services\MeterReadingService;
use App\Jobs\ProcessCSVFile;
use Illuminate\Http\Request;

class MeterReadingController extends Controller
{
    protected $meterReadingService;

    public function __construct(MeterReadingService $meterReadingService)
    {
        $this->meterReadingService = $meterReadingService;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Meter $meter)
    {
        $this->authorize('create', MeterReading::class);

        $currentDate = today()->toDateString();

        return view('meter.reading.create', ['meter' => $meter, 'currentDate' => $currentDate]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreMeterReadingRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Meter $meter)
    {
        $this->authorize('create', MeterReading::class);

        $user = Auth::user();

        //Validate the input
        $storedMeterRequest = new StoreMeterReadingRequest();
        $storedMeterRequest->setUser($user);
        $storedMeterRequest->setMeter($meter);
        $validated = $storedMeterRequest->validate($storedMeterRequest->rules($request));

        //Create meter reading for this meter and the user logged in
        //using the validated data
        $meterReading = MeterReading::create([
            'meter_id' => $meter->meter_id,
            'user_id' => $user->id,
            'reading' => $validated->reading,
            'date' => $validated->date
        ]);

        return redirect()->route('meter.show', ['meter' => $meter])
            ->with('message', 'Meter reading added');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\MeterReading  $meterReading
     * @return \Illuminate\Http\Response
     */
    // public function show(Meter $meter, MeterReading $meterReading)
    // {
    //     $this->authorize('view', MeterReading::class);

    //     $meterReading = MeterReading::where('deleted_at', null)->find($meterReading);

    //     return view('meter.reading.show', ['meter' => $meter, 'meterReading' => $meterReading]);
    // }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\MeterReading  $meterReading
     * @return \Illuminate\Http\Response
     */
    public function edit(Meter $meter, MeterReading $meterReading)
    {
        $this->authorize('update', $meterReading);

        if ($meterReading->deleted_at != null) {
            return redirect()->back();
        }

        return view('meter.reading.edit', ['meter' => $meter, 'meterReading' => $meterReading]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateMeterReadingRequest  $request
     * @param  \App\Models\MeterReading  $meterReading
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateMeterReadingRequest $request, Meter $meter, MeterReading $meterReading)
    {
        $this->authorize('update', $meterReading);

        $user = Auth::user();

        $estimatedMeterReading = $this->meterReadingService->getEstimatedMeterReading($meter, $request->date);

        $validated = $request->except('_method');

        $updateMeterRequest = new StoreMeterReadingRequest();
        $updateMeterRequest->setUser($user);
        $updateMeterRequest->setMeter($meter);
        $validated = $updateMeterRequest->validate($updateMeterRequest->rules());

        $meterReading->update($validated);

        return redirect()->route('meter.show', ['meter' => $meter])
            ->with('message', 'Meter reading updated');
    }

    /**
     * Soft delete the specified resource from storage.
     *
     * @param  \App\Models\MeterReading  $meterReading
     * @return \Illuminate\Http\Response
     */
    public function destroy(Meter $meter, MeterReading $meterReading)
    {
        $this->authorize('delete', $meterReading);

        $meterReading->update(['deleted_at' => now()]);

        return redirect()->route('meter.show', ['meter' => $meter])
            ->with('message', 'Meter reading deleted');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MeterReading  $meterReading
     * @return \Illuminate\Http\Response
     */
    public function forceDestroy(Meter $meter, MeterReading $meterReading)
    {
        $this->authorize('forceDelete', $meterReading);

        $meterReading->delete();

        return redirect()->route('meter.show', ['meter' => $meter])
            ->with('message', 'Meter reading permanantly deleted');
    }

    /**
     * Upload a csv using a Job
     *
     * @param  \App\Http\Requests\UpdateMeterReadingRequest  $request
     *
     * @return string Message to inform of any not being sussesfully uploaded
     */
    public function uploadCSV(Request $request)
    {
        $this->authorize('upload', $meterReading);

        // Retrieve the uploaded CSV file from the request
        $file = $request->file('csv_file');
        // Store the uploaded file in a temporary location
        $filePath = $file->store('temp');
        // Dispatch the job to process the CSV file
        $response = ProcessCSVFile::dispatch($filePath);

        return $response;
    }
}
