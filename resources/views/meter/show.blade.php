@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header"><h2>Meter - {{ $meter->identifier }}</h2></div>

        <div class="card-body">
            {{--  Details about this meter --}}
            <h2>Details</h2>

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Identifier (MPXN)</th>
                        <th>Install Date</th>
                        <th>Type</th>
                        <th>Estimated Annual Consumption</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($meters as $meter)
                    <tr>
                        <td>>{{ $meter->identifier }}</td>
                        <td>{{ $meter->installDateFormatted }}</td>
                        <td>{{ $meter->typeName }}</td>
                        <td>{{ $meter->eac }}</td>

                        @can('update', App\Meter::class)
                        <td><a class="btn btn-primary" href="{{ route('meter.edit', ['meter' => $meter->meter_id])}}">Edit</a></td>
                        @endcan
                        @can('delete', $meter)
                        <td>
                            <form action="{{ route('meter.destroy', ['meter' => $meter->meter_id])}}" method="POST">
                                {{ csrf_field() }}
                                {{ method_field('Delete') }}
                                <button onclick="return confirm('Are you sure?');" type="submit" class="btn btn-primary">Remove</button>
                            </form>
                        </td>
                        @endcan
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="py-2"></div>

            <h3>Estimated Meter Reading</h3>

            <form action={{ route('meter.estimated', ['meter' => $meter->meter_id]) }}>
                <div class="form-group">
                    <label for="start_date">Dates</label>

                    <div class="row">
                        <div class="col-md-6">
                            <label for="dateFrom">From</label>
                            <input type="date" class="form-control" name="dateFrom" value="{{ request('dateFrom') }}">
                        </div>
                        <div class="col-md-6">
                            <label for="dateTo">To</label>
                            <input type="date" class="form-control" name="dateTo" value="{{ request('dateTo') }}">
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Submit</button>
            </form>

            <h4>{{ $estimatedMeterReading }}</h4>

            <div class="py-2"></div>

            {{-- Meter readings for this meter --}}
            <h2>Meter readings</h2>

            @can('create', App\MeterReading::class)
            <a href="{{ route('meter.readng.create', ['meter' => $meter->meter_id]) }}">
                <button class="btn btn-primary">Add Meter Reading</button>
            </a>
            @endcan

            @can('upload', App\MeterReading::class)
            {{-- TODO: Add loading bar --}}
            <a href="{{ route('meter.reading.upload', ['meter' => $meter->meter_id]) }}">
                <button class="btn btn-primary">Upload CSV of Meter Readings</button>
            </a>
            @endcan

            {{-- Only display meter readings user can view --}}
            @can('view', App\MeterReading::class)
            @if ($meterReadings->count())

            {{-- TODO: Add optional filter by user here --}}

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Identifier (MPXN)</th>
                        <th>Install Date</th>
                        <th>Type</th>
                        <th>Estimated Annual Consumption</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($meterReadings as $reading)
                    <tr>
                        <td>{{ $reading->value }}</td>
                        {{-- Attributes --}}
                        <td>{{ $reading->dateFormatted }}</td>
                        <td>{{ $reading->typeName }}</td>
                        {{-- Name of user that submitted the reading --}}
                        <td>{{ $reading->user->name }}</td>

                        @can('update', App\Meter::class)
                        <td><a class="btn btn-primary" href="{{ route('meter.reading.edit', [
                            'meter' => $meter->meter_id,
                            'reading' => $reading->meter_reading_id
                            ])}}">Edit</a></td>
                        @endcan
                        @can('delete', $reading)
                        <td>
                            <form action="{{ route('meter.reading.destroy', [
                                'meter' => $meter->meter_id,
                                'reading' => $reading->meter_reading_id])}}" method="POST">
                                {{ csrf_field() }}
                                {{ method_field('Delete') }}
                                <button onclick="return confirm('Are you sure?');" type="submit" class="btn btn-primary">Remove</button>
                            </form>
                        </td>
                        @endcan
                        @can('forceDelete', $reading)
                        <td>
                            <form action="{{ route('meter.reading.force_destroy', [
                                'meter' => $meter->meter_id,
                                'reading => $reading->meter_reading_id'])}}" method="POST">
                                {{ csrf_field() }}
                                {{ method_field('Delete') }}
                                <button onclick="return confirm('Are you sure?');" type="submit" class="btn btn-primary">Permanant Delete</button>
                            </form>
                        </td>
                        @endcan
                    </tr>
                    @endforeach
                </tbody>
            </table>

            {{ $meterReadings->links() }}

            @else
            <div class="alert alert-info">
                No meter readings
            </div>
            @endif
            @endcan

        </div>
    </div>
</div>
@endsection
