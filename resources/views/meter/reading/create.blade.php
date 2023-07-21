@extends('layouts.app')

@section('content')

<div class="container">
<div class="card">
    <div class="card-header"><h3>Add Meter Reading</h3></div>
    <div class="card-body">
        <form method="POST" action="{{ route('meter.reading.store', ['meter' => $meter]) }}">
            @csrf
            <input type="hidden" name="meter_id">
            <input type="hidden" name="user_id">

            <div class="row mb-3">
                <label for="reading" class="col-md-4 col-form-label text-md-end">{{ __('Meter Reading Value') }}
                    <span style="color: #FF0000"> *</span></label>

                <div class="col-md-6">
                    <input id="reading" type="text" class="form-control @error('reading') is-invalid @enderror" name="reading" value="{{ old('reading') }}" required autofocus>

                    @error('reading')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('reading') }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            <div class="row mb-3">
                <label for="date" class="col-md-4 col-form-label text-md-end">{{ __('Date') }}
                    <span style="color: #FF0000"> *</span></label>

                <div class="col-md-6">
                    <input id="date" type="date" class="form-control @error('date') is-invalid @enderror" name="date" value="{{ $currentDate ?? old('date') }}" required>

                    @error('date')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('date') }}</strong>
                        </span>
                    @enderror
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
