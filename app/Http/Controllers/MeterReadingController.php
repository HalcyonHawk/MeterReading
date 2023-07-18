<?php

namespace App\Http\Controllers;

use App\Models\MeterReading;
use App\Http\Requests\StoreMeterReadingRequest;
use App\Http\Requests\UpdateMeterReadingRequest;

use App\Services\MeterReadingService;
use App\Jobs\ProcessCSVFile;

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
    public function create($meterId)
    {
        $this->authorize('create', MeterReading::class);

        $currentDate = today()->toDateString();

        return view('meter.reading.create', ['meterId' => $merterId, 'currentDate' => $currentDate]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreMeterReadingRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreMeterReadingRequest $request, $meterId)
    {
        $this->authorize('create', MeterReading::class);

        $estimatedReading = $this->meterReadingService->getEstimatedMeterReading($meterId, $request->date);

        //Get validated input
        $validated = $request->validate(MeterReading::getValidationRules($estimatedReading));

        //Set user id as the user logged in
        $user = Auth::user();
        $validated['user_id'] = $user->user_id;

        $meterReading = MeterReading::create($validated);

        return redirect()->route('meter.show', ['meter' => $meterId])
            ->with('message', 'Meter reading added');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\MeterReading  $meterReading
     * @return \Illuminate\Http\Response
     */
    // public function show($meterId, MeterReading $meterReading)
    // {
    //     $this->authorize('view', MeterReading::class);

    //     $meterReading = MeterReading::where('deleted_at', null)->find($meterReading);

    //     return view('meter.reading.show', ['meter' => $meterId, 'meterReading' => $meterReading]);
    // }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\MeterReading  $meterReading
     * @return \Illuminate\Http\Response
     */
    public function edit($meterId, MeterReading $meterReading)
    {
        $this->authorize('update', MeterReading::class);

        $meterReading = MeterReading::where('deleted_at', null)->find($meterReading);

        return view('meter.reading.edit', ['meterId' => $meterId, 'meterReading' => $meterReading]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateMeterReadingRequest  $request
     * @param  \App\Models\MeterReading  $meterReading
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateMeterReadingRequest $request, $meterId, MeterReading $meterReading)
    {
        $this->authorize('update', MeterReading::class);

        $meterReading = MeterReading::findOrFail($meterReading);

        $estimatedReading = $this->meterReadingService->getEstimatedMeterReading($meterId, $request->date);

        $validated = $request->except('_method')->validate(MeterReading::getValidationRules($estimatedReading));

        $meterReading->update($validated);

        return redirect()->route('meter.show', ['meter' => $meterId])
            ->with('message', 'Meter reading updated');
    }

    /**
     * Soft delete the specified resource from storage.
     *
     * @param  \App\Models\MeterReading  $meterReading
     * @return \Illuminate\Http\Response
     */
    public function destroy($meterId, MeterReading $meterReading)
    {
        $this->authorize('delete', MeterReading::class);

        $meterReading = MeterReading::findOrFail($meterReading);
        $meterReading->update(['deleted_at' => now()]);

        return redirect()->route('meter.show', ['meter' => $meterId])
            ->with('message', 'Meter reading deleted');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MeterReading  $meterReading
     * @return \Illuminate\Http\Response
     */
    public function forceDestroy($meterId, MeterReading $meterReading)
    {
        $this->authorize('forceDelete', MeterReading::class);

        $meterReading = MeterReading::findOrFail($meterReading);
        $meterReading->delete();

        return redirect()->route('meter.show', ['meter' => $meterId])
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
        $this->authorize('upload', MeterReading::class);

        // Retrieve the uploaded CSV file from the request
        $file = $request->file('csv_file');
        // Store the uploaded file in a temporary location
        $filePath = $file->store('temp');
        // Dispatch the job to process the CSV file
        $response = ProcessCSVFile::dispatch($filePath);

        return $response;
    }
}
