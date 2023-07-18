@extends('layouts.app')

@section('content')

<div class="container">
<div class="card">
    <div class="card-header"><h3>Edit Meter</h3></div>
    <div class="card-body">
        <form method="POST" action="{{ route('meter.update', ['meter' => $meter->meter_id]) }}">
            @csrf
            <input type="hidden" name="_method" value="PUT">

            <div class="form-group row">
                <label for="identifier" class="col-md-4 col-form-label text-md-right">{{ __('Identifier (MPXN)') }}
                    <span style="color: #FF0000"> *</span></label>

                <div class="col-md-6">
                    <input type="text" id="identifier" class="form-control" name="identifier" value="{{ $meter->identifier }}" required autofocus>
                </div>
            </div>

            <div class="form-group row">
                <label for="install_date" class="col-md-4 col-form-label text-md-right">{{ __('Install Date') }}
                    <span style="color: #FF0000"> *</span></label>

                <div class="col-md-6">
                    <input id="install_date" type="date" class="form-control" name="install_date" value="{{ $meter->install_date }}" required>
                </div>
            </div>

            <div class="form-group row">
                <label for="type" class="col-md-4 col-form-label text-md-right">{{ __('Meter Type') }}
                    <span style="color: #FF0000"> *</span></label>

                <div class="col-md-6">
                    <select class="form-control" name="type">
                        <option value="ELECTRIC" @if ($meter->type == 'electric') selected @endif>Eletric</option>
                        <option value="GAS" @if ($meter->type == 'gas') selected @endif>Gas</option>
                    </select>
                </div>
            </div>

            <div class="form-group row">
                <label for="identifier" class="col-md-4 col-form-label text-md-right">{{ __('Estimated Annual Consumption') }}
                    <span style="color: #FF0000"> *</span></label>

                <div class="col-md-6">
                    <input type="number" min="2000" max="8000" id="eac" class="form-control" name="eac" value="{{ $meter->eac }}" required autofocus>
                </div>
            </div>

            <div class="form-group row mb-0">
                <div class="col-md-6 offset-md-4">
                    <button type="submit" class="btn btn-primary">
                        {{ __('Update') }}
                    </button>
                </div>
            </div>

        </form>
    </div>
</div>
</div>

@stop
