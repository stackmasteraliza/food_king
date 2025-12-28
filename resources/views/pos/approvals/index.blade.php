@extends('layouts.pos')

@section('content')
    <div class="container">
        <h1>Session Approvals</h1>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Session ID</th>
                    <th>Shift Type</th>
                    <th>Cashier</th>
                    <th>Manager</th>
                    <th>Delivered Amount</th>
                    <th>Variance</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($approvals as $approval)
                    <tr>
                        <td>{{ $approval->id }}</td>
                        <td>{{ $approval->pos_session_id }}</td>
                        <td>{{ $approval->posSession->shiftType->name }}</td>
                        <td>{{ $approval->posSession->cashier->name }}</td>
                        <td>{{ $approval->manager->name }}</td>
                        <td>{{ $approval->delivered_amount }}</td>
                        <td>{{ $approval->variance }}</td>
                        <td>{{ ucfirst($approval->status) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
