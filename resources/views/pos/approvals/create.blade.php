@extends('layouts.pos')

@section('content')
    <div class="container">
        <h1>Approve POS Session</h1>
        <div class="card mb-3">
            <div class="card-body">
                <h5 class="card-title">Session ID: {{ $session->id }}</h5>
                <p class="card-text">Shift Type: {{ $session->shiftType->name }}</p>
                <p class="card-text">Cashier: {{ $session->cashier->name }}</p>
                <p class="card-text">Start Time: {{ $session->start_time }}</p>
                <p class="card-text">End Time: {{ $session->end_time }}</p>
                <p class="card-text">Opening Float: {{ $session->opening_float }}</p>
                <p class="card-text">Total Sales: {{ $session->total_sales }}</p>
                <p class="card-text">Total Refunds: {{ $session->total_refunds }}</p>
                <p class="card-text">Cash Expected: {{ $session->cash_expected }}</p>
                <p class="card-text">Cash Actual: {{ $session->cash_actual }}</p>
            </div>
        </div>
        <form action="{{ route('pos.approvals.store', $session->id) }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="delivered_amount">Delivered Amount</label>
                <input type="number" name="delivered_amount" id="delivered_amount" class="form-control @error('delivered_amount') is-invalid @enderror" step="0.01" min="0" required value="{{ old('delivered_amount', $session->cash_actual) }}">
                @error('delivered_amount')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label for="status">Approval Status</label>
                <select name="status" id="status" class="form-control @error('status') is-invalid @enderror" required>
                    <option value="approved">Approved</option>
                    <option value="rejected">Rejected</option>
                </select>
                @error('status')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label for="comments">Comments (Optional)</label>
                <textarea name="comments" id="comments" class="form-control @error('comments') is-invalid @enderror">{{ old('comments') }}</textarea>
                @error('comments')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <button type="submit" class="btn btn-primary mt-3">Submit Approval</button>
        </form>
    </div>
@endsection
