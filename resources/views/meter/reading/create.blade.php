@extends('layouts.app')

@section('content')

<div class="container">
<div class="card">
    <div class="card-header"><h3>Add Meter Reading</h3></div>
    <div class="card-body">
        <form method="POST" action="{{ route('meter.reading.store', ['meter' => $meter->meter->meter_id]) }}">
            @csrf
            <input type="hidden" name="meter_id" value="{{ $meter->meter_id }}">
            <input type="hidden" name="user_id">

            <div class="form-group row">
                <label for="reading" class="col-md-4 col-form-label text-md-right">{{ __('Meter Reading Value') }}
                    <span style="color: #FF0000"> *</span></label>

                <div class="col-md-6">
                    <input type="numer" class="form-control" name="reading" value="{{ old('reading') }}" required autofocus>
                </div>
            </div>

            <div class="form-group row">
                <label for="date" class="col-md-4 col-form-label text-md-right">{{ __('Date') }}
                    <span style="color: #FF0000"> *</span></label>

                <div class="col-md-6">
                    <input id="date" type="date" class="form-control" name="date" value="{{ $currentDate }}" required>
                </div>
            </div>

            <div class="form-group row mb-0">
                <div class="col-md-6 offset-md-4">
                    <button type="submit" class="btn btn-primary">
                        {{ __('Add') }}
                    </button>
                </div>
            </div>

        </form>
    </div>
</div>
</div>
@endsection
