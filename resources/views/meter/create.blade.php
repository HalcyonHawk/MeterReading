@extends('layouts.app')

@section('content')

<div class="container">
<div class="card">
    <div class="card-header"><h3>Create Meter</h3></div>
    <div class="card-body">
        <form method="POST" action="{{ route('meter.store') }}">
            @csrf

            <div class="row mb-3">
                <label for="identifier" class="col-md-4 col-form-label text-md-end">{{ __('Identifier (MPXN)') }}
                    <span style="color: #FF0000"> *</span></label>

                <div class="col-md-6">
                    <input id="identifier" type="text" class="form-control @error('identifier') is-invalid @enderror" name="identifier" value="{{ old('identifier') }}" required autofocus>

                    @error('identifier')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('identifier') }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            <div class="row mb-3">
                <label for="install_date" class="col-md-4 col-form-label text-md-end">{{ __('Date') }}
                    <span style="color: #FF0000"> *</span></label>

                <div class="col-md-6">
                    <input id="install_date" type="date" class="form-control @error('install_date') is-invalid @enderror" name="install_date" value="{{ $currentDate ?? old('install_date') }}" required>

                    @error('install_date')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('install_date') }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            <div class="row mb-3">
                <label for="type" class="col-md-4 col-form-label text-md-end">{{ __('Meter Type') }}
                    <span style="color: #FF0000"> *</span></label>

                <div class="col-md-6">
                    <select class="form-control" name="type">
                        {{-- Disabled as this is required --}}
                        <option disabled selected value="null">Unassigned</option>
                        <option value="ELECTRIC">Electric</option>
                        <option value="GAS">Gas</option>
                    </select>

                    @error('type')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('type') }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            <div class="row mb-3">
                <label for="eac" class="col-md-4 col-form-label text-md-end">{{ __('Estimated Annual Consumption') }}
                    <span style="color: #FF0000"> *</span></label>

                <div class="col-md-6">
                    <input id="eac" type="number" min="2000" max="8000" class="form-control @error('eac') is-invalid @enderror" name="eac" value="{{ old('eac') }}" required>

                    @error('eac')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('eac') }}</strong>
                        </span>
                    @enderror
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
