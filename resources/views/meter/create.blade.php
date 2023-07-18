@extends('layouts.app')

@section('content')

<div class="container">
<div class="card">
    <div class="card-header"><h3>Create Meter</h3></div>
    <div class="card-body">
        <form method="POST" action="{{ route('meter.store') }}">
            @csrf

            <div class="form-group row">
                <label for="identifier" class="col-md-4 col-form-label text-md-right">{{ __('Identifier (MPXN)') }}
                    <span style="color: #FF0000"> *</span></label>

                <div class="col-md-6">
                    <input type="text" id="identifier" class="form-control" name="identifier" value="{{ old('identifier') }}" required autofocus>
                </div>
            </div>

            <div class="form-group row">
                <label for="install_date" class="col-md-4 col-form-label text-md-right">{{ __('Install Date') }}
                    <span style="color: #FF0000"> *</span></label>

                <div class="col-md-6">
                    <input id="install_date" type="date" class="form-control" name="install_date" value="{{ $currentDate }}" required>
                </div>
            </div>

            <div class="form-group row">
                <label for="type" class="col-md-4 col-form-label text-md-right">{{ __('Meter Type') }}
                    <span style="color: #FF0000"> *</span></label>

                <div class="col-md-6">
                    <select class="form-control" name="type">
                        {{-- Disabled as this is required --}}
                        <option disabled selected value="null">Unassigned</option>
                        <option value="ELECTRIC">Eletric</option>
                        <option value="GAS">Gas</option>
                    </select>
                </div>
            </div>

            <div class="form-group row">
                <label for="identifier" class="col-md-4 col-form-label text-md-right">{{ __('Estimated Annual Consumption') }}
                    <span style="color: #FF0000"> *</span></label>

                <div class="col-md-6">
                    <input type="number" min="2000" max="8000" id="eac" class="form-control" name="eac" value="{{ old('eac') }}" required autofocus>
                </div>
            </div>

            <div class="form-group row mb-0">
                <div class="col-md-6 offset-md-4">
                    <button type="submit" class="btn btn-primary">
                        {{ __('Create') }}
                    </button>
                </div>
            </div>

        </form>
    </div>
</div>
</div>
@endsection
