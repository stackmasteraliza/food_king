@extends('layouts.pos')

@section('content')
    <div class="container">
        <h1>Active POS Session</h1>
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Session ID: {{ $session->id }}</h5>
                <p class="card-text">Shift Type: {{ $session->shiftType->name }}</p>
                <p class="card-text">Start Time: {{ $session->start_time }}</p>
                <p class="card-text">Opening Float: {{ $session->opening_float }}</p>
                <p class="card-text">Status: {{ ucfirst($session->status) }}</p>
                <a href="{{ route('pos.cash.movement') }}" class="btn btn-info mb-3">Record Cash Movement</a>
                <form action="{{ route('pos.end') }}" method="POST" class="mt-3">
                    @csrf
                    <div class="form-group">
                        <label for="cash_actual">Actual Cash at End of Session</label>
                        <input type="number" name="cash_actual" id="cash_actual" class="form-control" step="0.01" min="0" required>
                    </div>
                    <button type="submit" class="btn btn-danger mt-2">End Session</button>
                </form>
            </div>
        </div>
    </div>
@endsection
