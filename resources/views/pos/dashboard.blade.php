@extends('layouts.pos')

@section('content')
    <div class="container">
        <h1>POS Dashboard</h1>
        <div class="row">
            <div class="col-md-6">
                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Session Management</h5>
                        <p class="card-text">Start or manage your POS sessions.</p>
                        @php
                            $activeSession = auth()->user()->posSessions()->where('status', 'open')->first();
                        @endphp
                        @if($activeSession)
                            <a href="{{ route('pos.active') }}" class="btn btn-primary">Go to Active Session</a>
                        @else
                            <a href="{{ route('pos.start') }}" class="btn btn-primary">Start New Session</a>
                        @endif
                        <a href="{{ route('pos.sessions.index') }}" class="btn btn-link">View All Sessions</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Shift Types</h5>
                        <p class="card-text">Configure different types of shifts for your POS system.</p>
                        <a href="{{ route('shift-types.index') }}" class="btn btn-primary">Manage Shift Types</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Session Approvals</h5>
                        <p class="card-text">Review and approve ended sessions (Manager access only).</p>
                        <a href="{{ route('pos.approvals.index') }}" class="btn btn-primary">View Approvals</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Quick Stats</h5>
                        <p class="card-text">Overview of recent POS activity.</p>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">Total Sessions: {{ auth()->user()->posSessions()->count() }}</li>
                            <li class="list-group-item">Pending Approvals: {{ \App\Models\POSSession::where('status', 'pending_approval')->count() }}</li>
                            <li class="list-group-item">Active Sessions: {{ \App\Models\POSSession::where('status', 'open')->count() }}</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
