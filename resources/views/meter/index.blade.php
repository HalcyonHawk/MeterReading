@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header"><h2>Meters</h2></div>

        <div class="card-body">
            {{-- Only users that have permission to create meters --}}
            @can('create', App\Meter::class)
            <a href="{{ route('meter.create') }}">
                <button class="btn btn-primary" style="padding: 10px 60px">Create New Meter</button>
            </a>
            @endcan

            @if ($meters->count())

            <p>Here are all the meters on the system.</p>

            <div class="py-2"></div>

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
                        <td><a href="{{ route('meter.show', ['meter' => $meter->meter_id]) }}">{{ $meter->identifier }}</a></td>
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

            {{ $meters->links() }}

            @else
            <div class="alert alert-info">
                No meters
            </div>
            @endif
        </div>
    </div>
</div>

@endsection
