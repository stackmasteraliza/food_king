@extends('layouts.pos')

@section('content')
    <div class="container">
        <h1>POS Session Summary</h1>
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Session ID: {{ $session->id }}</h5>
                <p class="card-text">Shift Type: {{ $session->shiftType->name }}</p>
                <p class="card-text">Cashier: {{ $session->cashier->name }}</p>
                <p class="card-text">Start Time: {{ $session->start_time }}</p>
                <p class="card-text">End Time: {{ $session->end_time ?? 'N/A' }}</p>
                <p class="card-text">Opening Float: {{ $session->opening_float }}</p>
                <p class="card-text">Total Sales: {{ $session->total_sales }}</p>
                <p class="card-text">Total Refunds: {{ $session->total_refunds }}</p>
                <p class="card-text">Cash Expected: {{ $session->cash_expected }}</p>
                <p class="card-text">Cash Actual: {{ $session->cash_actual }}</p>
                <p class="card-text">Status: {{ ucfirst($session->status) }}</p>
                @if($session->status == 'pending_approval')
                    <a href="{{ route('pos.approvals.create', $session->id) }}" class="btn btn-primary">Approve Session</a>
                @endif
            </div>
        </div>
    </div>
@endsection
