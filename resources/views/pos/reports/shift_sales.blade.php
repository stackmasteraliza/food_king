@extends('layouts.pos')

@section('content')
    <div class="container">
        <h1>Shift-wise Sales Report</h1>

        <form action="{{ route('pos.reports.shift-sales') }}" method="GET" class="mb-4">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="start_date">Start Date</label>
                        <input type="date" name="start_date" id="start_date" class="form-control" value="{{ request('start_date') }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="end_date">End Date</label>
                        <input type="date" name="end_date" id="end_date" class="form-control" value="{{ request('end_date') }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary mt-4">Filter Report</button>
                </div>
            </div>
        </form>

        @if(isset($sessions) && $sessions->count() > 0)
            @foreach($sessions as $session)
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        Session ID: {{ $session->id }} | Cashier: {{ $session->cashier->name ?? 'N/A' }} | Shift Type: {{ $session->shiftType->name ?? 'N/A' }}
                        <br>
                        Start Time: {{ $session->start_time }} | End Time: {{ $session->end_time ?? 'N/A' }}
                    </div>
                    <div class="card-body">
                        <h5>Summary</h5>
                        <ul class="list-group list-group-flush mb-3">
                            <li class="list-group-item">Opening Float: {{ number_format($session->opening_float, 2) }}</li>
                            <li class="list-group-item">Total Sales: {{ number_format($session->total_sales, 2) }}</li>
                            <li class="list-group-item">Total Refunds: {{ number_format($session->total_refunds, 2) }}</li>
                            <li class="list-group-item">Cash Expected: {{ number_format($session->cash_expected, 2) }}</li>
                            <li class="list-group-item">Cash Actual: {{ number_format($session->cash_actual, 2) }}</li>
                            <li class="list-group-item">Variance: {{ number_format($session->cash_actual - $session->cash_expected, 2) }}</li>
                        </ul>

                        @if($session->cashMovements->count() > 0)
                            <h6>Cash Movements</h6>
                            <table class="table table-sm table-bordered mb-3">
                                <thead>
                                    <tr>
                                        <th>Type</th>
                                        <th>Amount</th>
                                        <th>Description</th>
                                        <th>Timestamp</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($session->cashMovements as $movement)
                                        <tr>
                                            <td>{{ ucfirst($movement->type) }}</td>
                                            <td>{{ number_format($movement->amount, 2) }}</td>
                                            <td>{{ $movement->description ?? 'N/A' }}</td>
                                            <td>{{ $movement->timestamp }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif

                        @if($session->orders->count() > 0)
                            <h6>Orders</h6>
                            <table class="table table-sm table-bordered">
                                <thead>
                                    <tr>
                                        <th>Order ID</th>
                                        <th>Customer</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                        <th>Payment Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($session->orders as $order)
                                        <tr>
                                            <td>{{ $order->id }}</td>
                                            <td>{{ $order->user->name ?? 'Guest' }}</td>
                                            <td>{{ number_format($order->total, 2) }}</td>
                                            <td>{{ ucfirst($order->status) }}</td>
                                            <td>{{ ucfirst($order->payment_status) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif
                    </div>
                </div>
            @endforeach
        @else
            <div class="alert alert-info">No POS sessions found for the selected dates.</div>
        @endif
    </div>
@endsection 