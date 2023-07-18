<?php

namespace App\Http\Controllers;

use App\Models\Meter;
use App\Http\Requests\StoreMeterRequest;
use App\Http\Requests\UpdateMeterRequest;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Gate;
use App\Services\MeterReadingService;


class MeterController extends Controller
{
    protected $meterReadingService;

    public function __construct(MeterReadingService $meterReadingService)
    {
        $this->meterReadingService = $meterReadingService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $meters = Meter::get()->paginate(20);

        return view('meter.index', [
            'meters' => $meters,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', Meter::class);

        return view('meter.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreMeterRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreMeterRequest $request)
    {
        $this->authorize('create', Meter::class);

        //Get validated input
        $validated = $request->validate();

        //Create meter in database
        $meter = Meter::create($vaildated);

        //Display page that shows the new meter
        return redirect()->route('meter.show', ['meter' => $meter])
            ->with('message', 'New meter added');
    }

    /**
     * Display the specified meter and its readings
     *
     * @param  \App\Models\Meter  $meter
     * @return \Illuminate\Http\Response
     */
    public function show(Meter $meter)
    {
        $this->authorize('view', Meter::class);

        $meter = Meter::findOrFail($meter);

        //$meterReadings = MeterReading::where('deleted_at', null)
        $meterReadings = MeterReading::with('user')->withNoTrashed()
            ->where('meter_id', $meter->meter_id);

        //Filter option if can view any, else can only view their own
        if ($this->authorize('viewAny', Meter::class)) {
            //Optional filter by user
            if (request()->has('user_id') && (request('user_id') != 0)) {
                $meterReadings->where(
                    function ($query) {
                        $query->where('user_id', request('user_id'));
                    }
                );
            }
        } else {
            $meterReadings->where(
                function ($query) {
                    $query->where('user_id', Auth::user()->user_id);
                }
            );
        }
        //Seperate results
        $meterReadings->paginate(20);

        $estimatedReading =  $this->meterReadingService->getEstimatedMeterReading($meter, now());

        return view('meter.show', [
            'meter' => $meter,
            'meterReadings' => $meterReadings,
            'estimatedReading' => $estimatedReading
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Meter  $meter
     * @return \Illuminate\Http\Response
     */
    public function edit(Meter $meter)
    {
        $this->authorize('update', Meter::class);

        return view('meter.edit', [
            'meter' => Meter::findOrFail($meter)
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateMeterRequest  $request
     * @param  \App\Models\Meter  $meter
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateMeterRequest $request, Meter $meter)
    {
        $this->authorize('update', Meter::class);
        // //If user doesn't have permission
        // if (Gate::forUser($user)->denies('update-meter', $meter)) {
        //     // Send back to page with error message
        //     return Response::deny('You must be an administrator.');
        // }

        //Get validated input
        $validated = $request->validated();

        $meter->update($validated);

        return redirect()->route('meter.show', ['meter' => $meter])
            ->with('message', 'Meter reading updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Meter  $meter
     * @return \Illuminate\Http\Response
     */
    public function destroy(Meter $meter)
    {
        $this->authorize('delete', $meter);
        // if (Gate::forUser($user)->denies('delete-meter', $meter)) {
        //     return Response::deny('You must be an administrator.');
        // }

        $meter->delete();

        return redirect()->route('meter.index')
            ->with('message', 'Meter deleted');
    }
}
