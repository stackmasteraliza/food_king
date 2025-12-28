@extends('layouts.pos')

@section('content')
    <div class="container">
        <h1>POS Sessions</h1>
        <a href="{{ route('pos.start') }}" class="btn btn-primary mb-3">Start New Session</a>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Shift Type</th>
                    <th>Cashier</th>
                    <th>Start Time</th>
                    <th>End Time</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sessions as $session)
                    <tr>
                        <td>{{ $session->id }}</td>
                        <td>{{ $session->shiftType->name }}</td>
                        <td>{{ $session->cashier->name }}</td>
                        <td>{{ $session->start_time }}</td>
                        <td>{{ $session->end_time ?? 'N/A' }}</td>
                        <td>{{ ucfirst($session->status) }}</td>
                        <td>
                            <a href="{{ route('pos.summary', $session->id) }}" class="btn btn-info btn-sm">View Summary</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
